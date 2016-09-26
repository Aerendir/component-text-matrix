<?php

/*
 * This file is part of the PHPTextMatrix Component.
 *
 * (c) Adamo Crespi <hello@aerendir.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SerendipityHQ\Component\PHPTextMatrix;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * The class renders a table as plain text given an array of values.
 *
 * The class is built as a value object, so each instance of the class IS an immutable table.
 *
 * As it hasn't to be an immutable value object, the class provides methods to add or remove rows and to edit other
 * aspects of the table.
 *
 * @author Adamo "Aerendir" Crespi <hello@aerendir.me>
 */
class PHPTextMatrix
{
    /** @var  array $data The data to render in the table */
    private $data;

    /** @var array $header Contains the names of the columns. Those are the keys of the columns. */
    private $header = [];

    /** @var array $errors Contains the errors found by the validate() method */
    private $errors;

    /**
     * @var array $columnsWidths For each column, contains the length of the longest line in each splitted cell.
     *                           The longest line found in the column is the width of the column itself.
     */
    private $columnsWidths = [];

    /**
     * @var array $rowsHeights For each row, contains the height of the highest cell
     *                         The highest cell found in the row is the height of the row itself.
     */
    private $rowsHeights = [];

    /** @var  array $options The options to render the table */
    private $options;

    /** @var  string $table The rendered table in the plain text format */
    private $table;

    /** @var  int $tableWidth The total width of the table */
    private $tableWidth;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Renders the table as plain text.
     *
     * @param array $options
     *
     * @return string
     */
    public function render(array $options = [])
    {
        // Set the options to use
        $this->resolveOptions($options);

        if (false === $this->validate())
            return false;

        // Splits the content of the cells to fit into the defined line length
        $this->splitCellsContent();

        /*
         * Calculate the height of each row and the width of each column.
         * The height is equal to highest cell content found in the row.
         * The width is equal to the longest line found in the content of each cell of the column.
         */
        $this->calculateSizes();

        // Now we can calculate the total length of the table
        $this->calculateWidthOfTable();

        $table = $this->options['has_header'] ? $this->drawHeaderDivider() : $this->drawDivider();

        // If the top divider of the header hasn't to be shown...
        if ($this->options['has_header'] && false === $this->options['show_head_top_sep'])
            // ... Remove it
            $table = '';

        foreach ($this->data as $rowPosition => $rowContent) {
            $table .= $this->drawRow($rowPosition, $rowContent);
            $table .= $this->options['has_header'] && 0 === $rowPosition ? $this->drawHeaderDivider() : $this->drawDivider();
        }

        $this->table = $table;

        // Reset options
        $this->options = [];

        return $this->table;
    }

    /**
     * Validates the given array to check all the rows have the same number of columns.
     *
     * Returns false if the validation fails and calling the getErrors() method it is possible to know
     * which are these errors.
     *
     * @return bool False if the validation fails, true if all succeeds
     */
    public function validate()
    {
        // The number of columns
        $numberOfColumns = null;

        // Reset the errors
        $this->errors = [];

        // Check that there are rows in the data
        if (0 >= count($this->data)) {
            $message = 'There are no rows in the table';
            $this->errors[] = $message;

            return false;
        }

        // Check that all rows have the same number of columns
        foreach ($this->data as $row) {
            $found = count($row);

            if (null === $numberOfColumns)
                $numberOfColumns = $found;

            if ($numberOfColumns !== $found) {
                $message = sprintf(
                    'The number of columns mismatches. First row has %s columns while column %s has %s.',
                    $numberOfColumns,
                    key($row),
                    $found
                );
                $this->errors[] = $message;
            }
        }

        return 0 < count($this->errors) ? false : true;
    }

    /**
     * Returns the errors found by validate().
     *
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Splits the content of each cell into multiple lines according to the set max column width.
     */
    private function splitCellsContent()
    {
        // For each row...
        foreach ($this->data as $rowPosition => $rowContent) {
            // ... cycle each column to get its content
            foreach ($rowContent as $columnName => $cellContent) {
                // If we don't have a max width set for the column...
                if (false === isset($this->options['columns'][$columnName]['max_width'])) {
                    // ... simply wrap the content in an array and continue
                    $this->data[$rowPosition][$columnName] = [$cellContent];
                    goto addVerticalPadding;
                }

                // ... We have a max_width set: split the column
                $length = $this->options['columns'][$columnName]['max_width'];
                $cellContent = $this->reduceSpaces($cellContent);
                $cut = $this->options['columns'][$columnName]['cut'];

                $wrapped = wordwrap($cellContent, $length, PHP_EOL, $cut);

                $this->data[$rowPosition][$columnName] = explode(PHP_EOL, $wrapped);

                // At the end, add the vertical padding to the cell's content
                addVerticalPadding:
                if (0 < $this->options['cells_padding'][0]) {
                    // Now add the top padding
                    for ($paddingLine = 0; $paddingLine < $this->options['cells_padding'][0]; $paddingLine++)
                        array_unshift($this->data[$rowPosition][$columnName], '');
                }

                if (0 < $this->options['cells_padding'][2]) {
                    // And the bottom padding
                    for ($paddingLine = 0; $paddingLine < $this->options['cells_padding'][0]; $paddingLine++)
                        array_push($this->data[$rowPosition][$columnName], '');
                }
            }
        }
    }

    /**
     * @see http://stackoverflow.com/a/2326133/1399706
     *
     * @param string $cellContent
     *
     * @return string mixed
     */
    private function reduceSpaces($cellContent)
    {
        return preg_replace('/\x20+/', ' ', $cellContent);
    }

    /**
     * Calculates the width of each column of the table.
     *
     * This method is used only for plain text tables.
     */
    private function calculateSizes()
    {
        // For each row...
        foreach ($this->data as $rowPosition => $rowContent) {
            // ... cycle each column to get its content ...
            foreach ($rowContent as $columnName => $cellContent) {

                // If we don't already know the height of this row...
                if (false === isset($this->rowsHeights[$rowPosition]))
                    // ... we save the current calculated height
                    $this->rowsHeights[$rowPosition] = count($cellContent);

                // At this point we have the heigth for sure: on each cycle, we need the highest height
                if (count($cellContent) > $this->rowsHeights[$rowPosition])
                    /*
                     * The height of this row is the highest found: use this to set the height of the entire row.
                     */
                    $this->rowsHeights[$rowPosition] = count($cellContent);

                // ... and calculate the length of each line to get the max length of the column
                foreach ($cellContent as $lineNumber => $lineContent) {
                    // Get the length of the cell
                    $contentLength = iconv_strlen($lineContent);

                    // If we don't already have a length for this column...
                    if (false === isset($this->columnsWidths[$columnName]))
                        // ... we save the current calculated length
                        $this->columnsWidths[$columnName] = $contentLength;

                    // At this point we have a length for sure: on each cycle we need the longest length
                    if ($contentLength > $this->columnsWidths[$columnName])
                        /*
                         * The length of the content of this cell is longer than the value we already have.
                         * So we need to use this new value as length for the current column.
                         */
                        $this->columnsWidths[$columnName] = $contentLength;
                }
            }
        }
    }

    /**
     * Calculates the width of the entire table.
     */
    private function calculateWidthOfTable()
    {
        $width = 0;

        foreach ($this->columnsWidths as $columnWidth) {
            // To the width of each column add the left and right padding and the separator
            $width += $columnWidth + $this->options['cells_padding'][1] + $this->options['cells_padding'][3] + 1;
        }

        // And add the last separator
        $this->tableWidth = $width + 1;
    }

    /**
     * @return string
     */
    private function drawHeaderDivider()
    {
        return $this->drawDivider('sep_head_');
    }

    /**
     * Draws the horizontal divider.
     *
     * @param string $prefix
     *
     * @return string
     */
    private function drawDivider($prefix = 'sep_')
    {
        $divider = '';
        foreach ($this->columnsWidths as $width) {
            // Column width position for the xSep + left and rigth padding
            $times = $width + $this->options['cells_padding'][1] + $this->options['cells_padding'][3];
            $divider .= $this->options[$prefix . 'x'] . $this->repeatChar($this->options[$prefix . 'h'], $times);
        }

        return $divider . $this->options[$prefix . 'x'] . PHP_EOL;
    }

    /**
     * @param integer $lineNumber
     * @param array  $rowContent
     * @param string $sepPrefix
     *
     * @return string
     */
    private function drawLine($lineNumber, $rowContent, $sepPrefix = 'sep_')
    {
        $line = '';
        foreach ($rowContent as $columnName => $cellContent) {
            $lineContent = '';

            // If the line contains some text...
            if (isset($cellContent[$lineNumber]))
                // Use it
                $lineContent = $cellContent[$lineNumber];

            $alignSpaces = 0;

            // Count characters and draw spaces if needed
            if (iconv_strlen($lineContent) < $this->columnsWidths[$columnName]) {
                $alignSpaces = $this->columnsWidths[$columnName] - iconv_strlen($lineContent);
            }

            // Draw the line
            $line
                // Vertical Separator
                .= $this->options[$sepPrefix . 'v']
                // + left padding
                . $this->drawSpaces($this->options['cells_padding'][3]);

            if (false === isset($this->options['columns'][$columnName]['align']))
                $this->options['columns'][$columnName]['align'] = $this->options['default_cell_align'];

            switch ($this->options['columns'][$columnName]['align']) {
                case 'left':
                    $line .=
                        // + content
                        trim($lineContent)
                        // + right spaces
                        . $this->drawSpaces($alignSpaces);
                    break;

                case 'right':
                    $line .=
                        // + right spaces
                        $this->drawSpaces($alignSpaces)
                        // + content
                        . trim($lineContent);
                    break;
            }

            // + right padding
            $line .= $this->drawSpaces($this->options['cells_padding'][1]);
        }

        return $line . $this->options[$sepPrefix . 'v'] . PHP_EOL;
    }

    /**
     * @param int   $rowPosition
     * @param array $rowContent
     *
     * @return string
     */
    private function drawRow($rowPosition, array $rowContent)
    {
        $row = '';
        for ($lineNumber = 0; $lineNumber < $this->rowsHeights[$rowPosition]; $lineNumber++) {
            $sepPrefix = $this->options['has_header'] && 0 === $rowPosition ? 'sep_head_' : 'sep_';
            $row .= $this->drawLine($lineNumber, $rowContent, $sepPrefix);
        }

        // Cell content + the last vertical separator
        return $row;
    }

    /**
     * Draws a string of empty spaces.
     *
     * @param int $amount
     * @return string
     */
    private function drawSpaces($amount)
    {
        return $this->repeatChar(' ', $amount);
    }

    /**
     * @param string $char The character to repeat
     * @param int $times The number of times the char has to be repeated
     *
     * @return string
     */
    private function repeatChar($char, $times)
    {
        if (0 === $times)
            return '';

        $string = '';
        for ($i = 1; $i <= $times; $i++)
            $string .= $char;

        return $string;
    }

    /**
     * @param array $options
     */
    private function resolveOptions(array $options = [])
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            // The horizontal header separator
            'sep_head_h' => '=',
            // The vertical header separator
            'sep_head_v' => '#',
            // The cross header separator
            'sep_head_x' => '#',
            // The horizontal separator
            'sep_h' => '-',
            // The vertical separator
            'sep_v' => '|',
            // The cross separator
            'sep_x' => '+',
            'has_header' => false,
            // Determines if the top divider of the header has to be shown or not
            'show_head_top_sep' => true,
            // Determine if the content has to be aligned on the left or on the right
            'default_cell_align' => 'left'
            ])
            // This options can be passed or not
                ->setDefined('cells_padding')
                ->setDefined('columns');

        // Set type validation
        $resolver->setAllowedTypes('sep_h', 'string')
            ->setAllowedTypes('sep_v', 'string')
            ->setAllowedTypes('sep_x', 'string')
            ->setAllowedTypes('cells_padding', ['array', 'integer'])
            ->setAllowedTypes('columns', 'array');

        // Set value validation
        $resolver->setAllowedValues('cells_padding', function ($value) {
            if (is_array($value)) {
                return count($value) <= 4 ? true : false;
            }

            return true;
        });

        $this->options = $resolver->resolve($options);

        $this->options['cells_padding'] = $this->resolveCellsPaddings();
        $this->options['columns']       = $this->resolveColumnsOptions();
    }

    /**
     * Set the padding of the cells.
     *
     * This is the number of spaces to put on the left and on the right and on top and bottom of the content in each cell.
     *
     * This method follows the rules of the padding CSS rule.
     * @see http://www.w3schools.com/css/css_padding.asp
     *
     * @return array
     *
     * @throws \InvalidArgumentException If the value is not an integer
     */
    private function resolveCellsPaddings()
    {
        $return = [0, 0, 0, 0];

        // If padding is not set, return default values
        if (false === isset($this->options['cells_padding']))
            return $return;

        // Create a resolver to validate passed values
        $resolver = new OptionsResolver();
        $resolver->setDefined(0);
        $resolver->setDefined(1);
        $resolver->setDefined(2);
        $resolver->setDefined(3);

        $resolver->setAllowedTypes(0, 'integer');
        $resolver->setAllowedTypes(1, 'integer');
        $resolver->setAllowedTypes(2, 'integer');
        $resolver->setAllowedTypes(3, 'integer');

        $padding = $this->options['cells_padding'];

        // If is only an integer, make it an array with only one set value
        if (is_int($padding))
            $padding = [$padding];

        $count = count($padding);

        switch ($count) {
            case 1:
                // Set the same padding for all directions
                $return = [$padding[0], $padding[0], $padding[0], $padding[0]];
                break;

            case 2:
                // Set the same padding for all directions
                $return = [$padding[0], $padding[1], $padding[0], $padding[1]];
                break;

            case 3:
                // Set the same padding for all directions
                $return = [$padding[0], $padding[1], $padding[2], $padding[1]];
                break;

            case 4:
                // Set specific paddings for each direction
                $return = [$padding[0], $padding[1], $padding[2], $padding[3]];
                break;

            default:

        }

        return $return;
    }

    /**
     * @return array
     */
    private function resolveColumnsOptions()
    {
        $return = [];
        // Sub- resovler for columns
        if (isset($this->options['columns'])) {
            $resolver = new OptionsResolver();
            $resolver->setDefined('max_width');
            $resolver->setDefault('cut', false);
            $resolver->setDefault('align', 'left');

            $resolver->setAllowedTypes('max_width', 'integer');
            $resolver->setAllowedValues('cut', [true, false]);
            $resolver->setAllowedValues('align', ['left', 'right']);

            foreach ($this->options['columns'] as $columnName => $columnOptions) {
                $resolved = $resolver->resolve($columnOptions);
                $return[$columnName] = $resolved;
            }
        }

        return $return;
    }
}
