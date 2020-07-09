<?php

/*
 * This file is part of the PHP Text Matrix Component.
 *
 * Copyright Adamo Aerendir Crespi 2016-2020.
 *
 * See the LICENSE for more details.
 *
 * @author    Adamo Aerendir Crespi <hello@aerendir.me>
 * @copyright Copyright (C) 2012 - 2020 Aerendir. All rights reserved.
 * @license   MIT License
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
final class PHPTextMatrix
{
    /** @var string */
    public const ALIGN = 'align';

    /** @var string */
    public const ALIGN_LEFT = 'left';

    /** @var string */
    public const ALIGN_RIGHT = 'right';

    /** @var string */
    public const CELLS_PADDING = 'cells_padding';

    /** @var string */
    public const COLUMNS = 'columns';

    /** @var string */
    public const CUT = 'cut';

    /** @var string */
    public const HAS_HEADER = 'has_header';

    /** @var string */
    public const MAX_WIDTH = 'max_width';

    /** @var string */
    public const MIN_WIDTH = 'min_width';

    /** @var string */
    public const SHOW_HEAD_TOP_SEP = 'show_head_top_sep';

    /** @var string The horizontal header separator */
    public const SEP_HEAD_H = 'sep_head_h';

    /** @var string The vertical header separator */
    public const SEP_HEAD_V = 'sep_head_v';

    /** @var string The cross header separator */
    public const SEP_HEAD_X = 'sep_head_x';

    /** @var string The horizontal separator */
    public const SEP_H = 'sep_h';

    /** @var string The vertical separator */
    public const SEP_V = 'sep_v';

    /** @var string The cross separator */
    public const SEP_X = 'sep_x';

    /** @var string */
    private const SEP_ = 'sep_';

    /** @var string */
    private const ARRAY = 'array';

    /** @var string */
    private const INTEGER = 'integer';

    /* @var string */
    private const STRING = 'string';

    /** @var array $data The data to render in the table */
    private $data = [];

    /** @var array $errors Contains the errors found by the validate() method */
    private $errors = [];

    /**
     * @var array For each column, contains the length of the longest line in each splitted cell.
     *            The longest line found in the column is the width of the column itself
     */
    private $columnsWidths = [];

    /**
     * @var array For each row, contains the height of the highest cell
     *            The highest cell found in the row is the height of the row itself
     */
    private $rowsHeights = [];

    /** @var array $options The options to render the table */
    private $options = [];

    /** @var int $tableWidth The total width of the table */
    private $tableWidth = 0;

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
     * @return bool|string
     */
    public function render(array $options = [])
    {
        // Set the options to use
        $this->resolveOptions($options);

        if (false === $this->validate()) {
            return false;
        }

        // Splits the content of the cells to fit into the defined line length
        $this->splitCellsContent();

        /*
         * Calculate the height of each row and the width of each column.
         * The height is equal to highest cell content found in the row.
         * The width is equal to the longest line found in the content of each cell of the column.
         */
        $this->calculateSizes();

        $this->tableWidth = $this->calculateTableWidth();

        $table = $this->options[self::HAS_HEADER] ? $this->drawHeaderDivider() : $this->drawDivider();

        // If the top divider of the header hasn't to be shown...
        if ($this->options[self::HAS_HEADER] && false === $this->options[self::SHOW_HEAD_TOP_SEP]) {
            // ... Remove it
            $table = '';
        }

        /**
         * @var int $rowPosition
         * @var array $rowContent
         */
        foreach ($this->data as $rowPosition => $rowContent) {
            $table .= $this->drawRow($rowPosition, $rowContent);
            $table .= $this->options[self::HAS_HEADER] && 0 === $rowPosition ? $this->drawHeaderDivider() : $this->drawDivider();
        }

        // Reset options
        $this->options = [];

        return $table;
    }

    /**
     * Validates the given array to check all the rows have the same number of columns.
     *
     * Returns false if the validation fails and calling the getErrors() method it is possible to know
     * which are these errors.
     *
     * @return bool False if the validation fails, true if all succeeds
     */
    public function validate(): bool
    {
        // The number of columns
        $numberOfColumns = null;

        // Reset the errors
        $this->errors = [];

        // Check that there are rows in the data
        if (0 >= \count($this->data)) {
            $message        = 'There are no rows in the table';
            $this->errors[] = $message;

            return false;
        }

        // Check that all rows have the same number of columns
        /** @var array $row */
        foreach ($this->data as $row) {
            $found = \count($row);

            if (null === $numberOfColumns) {
                $numberOfColumns = $found;
            }

            if ($numberOfColumns !== $found) {
                $message = \Safe\sprintf(
                    'The number of columns mismatches. First row has %s columns while column %s has %s.',
                    $numberOfColumns,
                    \key($row),
                    $found
                );
                $this->errors[] = $message;
            }
        }

        return 0 >= \count($this->errors);
    }

    /**
     * Returns the errors found by validate().
     *
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Returns the total width of the table.
     *
     * @return int
     */
    public function getTableWidth(): int
    {
        return $this->tableWidth;
    }

    /**
     * Splits the content of each cell into multiple lines according to the set max column width.
     */
    private function splitCellsContent(): void
    {
        // For each row...
        /**
         * @var int $rowPosition
         * @var array $rowContent
         */
        foreach ($this->data as $rowPosition => $rowContent) {
            // ... cycle each column to get its content
            /**
             * @var string $columnName
             * @var string $cellContent
             */
            foreach ($rowContent as $columnName => $cellContent) {
                // Remove extra spaces from the string
                $cellContent = $this->reduceSpaces($cellContent);

                // If we don't have a max width set for the column...
                if (false === isset($this->options[self::COLUMNS][$columnName][self::MAX_WIDTH])) {
                    // ... simply wrap the content in an array and continue
                    $this->data[$rowPosition][$columnName] = [$cellContent];
                    $this->addVerticalPadding($rowPosition, $columnName);
                    continue;
                }

                // ... We have a max_width set: split the column
                $length = $this->options[self::COLUMNS][$columnName][self::MAX_WIDTH];
                $cut    = $this->options[self::COLUMNS][$columnName][self::CUT];

                $wrapped = \wordwrap($cellContent, $length, PHP_EOL, $cut);

                $this->data[$rowPosition][$columnName] = \explode(PHP_EOL, $wrapped);

                // At the end, add the vertical padding to the cell's content
                $this->addVerticalPadding($rowPosition, $columnName);
            }
        }
    }

    private function addVerticalPadding(int $rowPosition, string $columnName):void
    {
        if (0 < $this->options[self::CELLS_PADDING][0]) {
            // Now add the top padding
            for ($paddingLine = 0; $paddingLine < $this->options[self::CELLS_PADDING][0]; ++$paddingLine) {
                \array_unshift($this->data[$rowPosition][$columnName], '');
            }
        }

        if (0 < $this->options[self::CELLS_PADDING][2]) {
            // And the bottom padding
            for ($paddingLine = 0; $paddingLine < $this->options[self::CELLS_PADDING][2]; ++$paddingLine) {
                $this->data[$rowPosition][$columnName][] = '';
            }
        }
    }

    /**
     * @see http://stackoverflow.com/a/2326133/1399706
     *
     * @param string $cellContent
     */
    private function reduceSpaces(string $cellContent): string
    {
        $result = \Safe\preg_replace('#\x20+#', ' ', $cellContent);

        if (\is_array($result)) {
            $result = $result[0];
        }

        return $result;
    }

    /**
     * Calculates the width of each column of the table.
     *
     * @suppress PhanTypeNoPropertiesForeach
     */
    private function calculateSizes(): void
    {
        // For each row...
        foreach ($this->data as $rowPosition => $rowContent) {
            // ... cycle each column to get its content ...
            foreach ($rowContent as $columnName => $cellContent) {
                // If we don't already know the height of this row...
                if (false === isset($this->rowsHeights[$rowPosition])) {
                    // ... we save the current calculated height
                    $this->rowsHeights[$rowPosition] = \is_array($cellContent) || $cellContent instanceof \Countable ? \count($cellContent) : 0;
                }

                // Set the min_width if it is set
                if (isset($this->options[self::COLUMNS][$columnName][self::MIN_WIDTH])) {
                    $this->columnsWidths[$columnName] = $this->options[self::COLUMNS][$columnName][self::MIN_WIDTH];
                }

                // At this point we have the heigth for sure: on each cycle, we need the highest height
                if ((\is_array($cellContent) || $cellContent instanceof \Countable ? \count($cellContent) : 0) > $this->rowsHeights[$rowPosition]) {
                    /*
                     * The height of this row is the highest found: use this to set the height of the entire row.
                     */
                    $this->rowsHeights[$rowPosition] = \is_array($cellContent) || $cellContent instanceof \Countable ? \count($cellContent) : 0;
                }

                // ... and calculate the length of each line to get the max length of the column
                foreach ($cellContent as $lineContent) {
                    // Get the length of the cell
                    $contentLength = \iconv_strlen($lineContent);

                    // If we don't already have a length for this column...
                    if (false === isset($this->columnsWidths[$columnName])) {
                        // ... we save the current calculated length
                        $this->columnsWidths[$columnName] = $contentLength;
                    }

                    // At this point we have a length for sure: on each cycle we need the longest length
                    if ($contentLength > $this->columnsWidths[$columnName]) {
                        /*
                         * The length of the content of this cell is longer than the value we already have.
                         * So we need to use this new value as length for the current column.
                         */
                        $this->columnsWidths[$columnName] = $contentLength;
                    }
                }
            }
        }
    }

    /**
     * Calculates the total width of the table.
     *
     * @return int
     */
    private function calculateTableWidth(): int
    {
        // Now calculate the total width of the table
        $tableWidth = 0;

        // Add the width of the columns
        foreach ($this->columnsWidths as $width) {
            $tableWidth += $width;
        }

        // Add 1 for the first separator
        ++$tableWidth;

        // Add the left and right padding * number of columns
        $tableWidth += ($this->options[self::CELLS_PADDING][1] + $this->options[self::CELLS_PADDING][3]) * \count($this->columnsWidths);

        // Add the left separators
        $tableWidth += \count($this->columnsWidths);

        return $tableWidth;
    }

    /**
     * @return string
     */
    private function drawHeaderDivider(): string
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
    private function drawDivider(string $prefix = self::SEP_): string
    {
        $divider = '';
        foreach ($this->columnsWidths as $width) {
            // Column width position for the xSep + left and rigth padding
            $times = $width + $this->options[self::CELLS_PADDING][1] + $this->options[self::CELLS_PADDING][3];
            $divider .= $this->options[$prefix . 'x'] . $this->repeatChar($this->options[$prefix . 'h'], $times);
        }

        return $divider . $this->options[$prefix . 'x'] . PHP_EOL;
    }

    /**
     * @param int    $lineNumber
     * @param array  $rowContent
     * @param string $sepPrefix
     *
     * @return string
     */
    private function drawLine(int $lineNumber, array $rowContent, string $sepPrefix = self::SEP_): string
    {
        $line = '';
        foreach ($rowContent as $columnName => $cellContent) {
            $lineContent = $cellContent[$lineNumber] ?? '';
            $alignSpaces = 0;

            // Count characters and draw spaces if needed
            if (\iconv_strlen($lineContent) < $this->columnsWidths[$columnName]) {
                $alignSpaces = $this->columnsWidths[$columnName] - \iconv_strlen($lineContent);
            }

            // Draw the line
            $line
                // Vertical Separator
                .= $this->options[$sepPrefix . 'v']
                // + left padding
                . $this->drawSpaces($this->options[self::CELLS_PADDING][3]);

            if (false === isset($this->options[self::COLUMNS][$columnName][self::ALIGN])) {
                $this->options[self::COLUMNS][$columnName][self::ALIGN] = $this->options['default_cell_align'];
            }

            switch ($this->options[self::COLUMNS][$columnName][self::ALIGN]) {
                case self::ALIGN_LEFT:
                    $line .=
                        // + content
                        \trim($lineContent)
                        // + right spaces
                        . $this->drawSpaces($alignSpaces);
                    break;

                case 'right':
                    $line .=
                        // + right spaces
                        $this->drawSpaces($alignSpaces)
                        // + content
                        . \trim($lineContent);
                    break;
            }

            // + right padding
            $line .= $this->drawSpaces($this->options[self::CELLS_PADDING][1]);
        }

        return $line . $this->options[$sepPrefix . 'v'] . PHP_EOL;
    }

    /**
     * @param int   $rowPosition
     * @param array $rowContent
     *
     * @return string
     */
    private function drawRow(int $rowPosition, array $rowContent): string
    {
        $row = '';
        for ($lineNumber = 0; $lineNumber < $this->rowsHeights[$rowPosition]; ++$lineNumber) {
            $sepPrefix = $this->options[self::HAS_HEADER] && 0 === $rowPosition ? 'sep_head_' : self::SEP_;
            $row .= $this->drawLine($lineNumber, $rowContent, $sepPrefix);
        }

        // Cell content + the last vertical separator
        return $row;
    }

    /**
     * Draws a string of empty spaces.
     *
     * @param int $amount
     *
     * @return string
     */
    private function drawSpaces(int $amount): string
    {
        return $this->repeatChar(' ', $amount);
    }

    /**
     * @param string $char  The character to repeat
     * @param int    $times The number of times the char has to be repeated
     *
     * @return string
     */
    private function repeatChar(string $char, int $times): string
    {
        if (0 === $times) {
            return '';
        }

        $string = '';
        for ($i = 1; $i <= $times; ++$i) {
            $string .= $char;
        }

        return $string;
    }

    /**
     * @param array $options
     */
    private function resolveOptions(array $options = []): void
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            // The horizontal header separator
            self::SEP_HEAD_H => '=',
            // The vertical header separator
            self::SEP_HEAD_V => '#',
            // The cross header separator
            self::SEP_HEAD_X => '#',
            // The horizontal separator
            self::SEP_H => '-',
            // The vertical separator
            self::SEP_V => '|',
            // The cross separator
            self::SEP_X      => '+',
            self::HAS_HEADER => false,
            // Determines if the top divider of the header has to be shown or not
            self::SHOW_HEAD_TOP_SEP => true,
            // Determine if the content has to be aligned on the left or on the right
            'default_cell_align' => self::ALIGN_LEFT,
            ])
            // This options can be passed or not
                ->setDefined(self::CELLS_PADDING)
                ->setDefined(self::COLUMNS);

        // Set type validation
        $resolver->setAllowedTypes(self::SEP_H, self::STRING)
            ->setAllowedTypes(self::SEP_V, self::STRING)
            ->setAllowedTypes(self::SEP_X, self::STRING)
            ->setAllowedTypes(self::CELLS_PADDING, [self::ARRAY, self::INTEGER])
            ->setAllowedTypes(self::COLUMNS, self::ARRAY);

        // Set value validation
        $resolver->setAllowedValues(self::CELLS_PADDING, static function ($value): bool {
            if (\is_array($value)) {
                return \count($value) <= 4;
            }

            return true;
        });

        $this->options = $resolver->resolve($options);

        $this->options[self::CELLS_PADDING] = $this->resolveCellsPaddings();
        $this->options[self::COLUMNS]       = $this->resolveColumnsOptions();
    }

    /**
     * Set the padding of the cells.
     *
     * This is the number of spaces to put on the left and on the right and on top and bottom of the content in each cell.
     *
     * This method follows the rules of the padding CSS rule.
     *
     * @see http://www.w3schools.com/css/css_padding.asp
     *
     * @throws \InvalidArgumentException If the value is not an integer
     *
     * @return array
     */
    private function resolveCellsPaddings(): array
    {
        $return = [0, 0, 0, 0];

        // If padding is not set, return default values
        if (false === isset($this->options[self::CELLS_PADDING])) {
            return $return;
        }

        // Create a resolver to validate passed values
        $resolver = new OptionsResolver();
        $resolver->setDefined('0');
        $resolver->setDefined('1');
        $resolver->setDefined('2');
        $resolver->setDefined('3');

        $resolver->setAllowedTypes('0', self::INTEGER);
        $resolver->setAllowedTypes('1', self::INTEGER);
        $resolver->setAllowedTypes('2', self::INTEGER);
        $resolver->setAllowedTypes('3', self::INTEGER);

        /** @var array|int $padding */
        $padding = $this->options[self::CELLS_PADDING];

        // If is only an integer, make it an array with only one set value
        if (\is_int($padding)) {
            $padding = [$padding];
        }

        $count = \count($padding);

        switch ($count) {
            case 1:
                // Set the same padding for all directions
                $return = [$padding[0], $padding[0], $padding[0], $padding[0]];
                break;

            case 2:
                // 0: Top and Bottom; 1: Left and Right
                $return = [$padding[0], $padding[1], $padding[0], $padding[1]];
                break;

            case 3:
                // 0: Top; 1: Left and Right; 2: Bottom
                $return = [$padding[0], $padding[1], $padding[2], $padding[1]];
                break;

            case 4:
                // 0: Top; 1: Right; 2: Bottom; 3: Left
                $return = [$padding[0], $padding[1], $padding[2], $padding[3]];
                break;
        }

        return $return;
    }

    /**
     * @return array
     */
    private function resolveColumnsOptions(): array
    {
        $return = [];
        // Sub- resovler for columns
        if (isset($this->options[self::COLUMNS])) {
            $resolver = new OptionsResolver();
            $resolver->setDefined(self::MAX_WIDTH);
            $resolver->setDefined(self::MIN_WIDTH);
            $resolver->setDefault(self::CUT, false);
            $resolver->setDefault(self::ALIGN, self::ALIGN_LEFT);

            $resolver->setAllowedTypes(self::MAX_WIDTH, self::INTEGER);
            $resolver->setAllowedTypes(self::MIN_WIDTH, self::INTEGER);
            $resolver->setAllowedValues(self::CUT, [true, false]);
            $resolver->setAllowedValues(self::ALIGN, [self::ALIGN_LEFT, self::ALIGN_RIGHT]);

            /**
             * @var string $columnName
             * @var array $columnOptions
             */
            foreach ($this->options[self::COLUMNS] as $columnName => $columnOptions) {
                $resolved            = $resolver->resolve($columnOptions);
                $return[$columnName] = $resolved;
            }
        }

        return $return;
    }
}
