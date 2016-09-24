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

    /** @var array $errors Contains the errors found by the validate() method */
    private $errors;

    /** @var array $cellPadding The number of spaces to put on the left and on the right of the content of each cell */
    private $cellPadding = [
        // Top padding
        1,
        // Right padding
        1,
        // Bottom padding
        1,
        // Left padding
        1
    ];

    /**
     * @var array $columnsWidths For each column, contains the length of the longest cell.
     *                           The longest content found in the column is the width of the column itself.
     */
    private $columnsWidths = [];

    /** @var  int $tableWidth The total width of the table */
    private $tableWidth;

    /** @var string $hSep The horizontal separator */
    private $hSep = '-';

    /** @var string $vSep The vertical separator */
    private $vSep = '|';

    /** @var string $xSep The cross separator */
    private $xSep = '+';

    /** @var  string $table The rendered table in the plain text format */
    private $table;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Set the padding of the cells.
     *
     * This is the number of spaces to put on the left and on the right and on top and bottom of the content in each cell.
     *
     * This method follows the rules of the padding CSS rule.
     * @see http://www.w3schools.com/css/css_padding.asp
     *
     * @param int|array $padding
     *
     * @throws \InvalidArgumentException If the value is not an integer
     */
    public function setCellPadding($padding)
    {
        if (
            false === is_int($padding) && false === is_array($padding)
            || true === is_array($padding) && 0 === count($padding)
        ) {
            throw new \InvalidArgumentException('The padding has to be an integer or an array.');
        }

        if (is_string($padding)) {
            // Set the same padding for all directions
            $this->cellPadding = [$padding, $padding, $padding, $padding];
            return;
        }

        $count = count($padding);

        switch ($count) {
            case 1:
                // Set the same padding for all directions
                $this->cellPadding = [$padding[0], $padding[0], $padding[0], $padding[0]];
                break;

            case 2:
                // Set the same padding for all directions
                $this->cellPadding = [$padding[0], $padding[1], $padding[0], $padding[1]];
                break;

            case 3:
                // Set the same padding for all directions
                $this->cellPadding = [$padding[0], $padding[1], $padding[2], $padding[1]];
                break;

            case 4:
                // Set the same padding for all directions
                $this->cellPadding = [$padding[0], $padding[1], $padding[2], $padding[3]];
                break;
        }
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
        if (false === $this->validate())
            return false;

        /*
         * The first thing to do is to calculate the width of each column.
         * The width is equal to the longest value found in the column.
         */
        $this->calculateWidthOfColumns();

        // Now we can calculate the total length of the table
        $this->calculateWidthOfTable();

        $table = $this->drawDivider();

        foreach ($this->data as $rowPosition => $row) {
            $table .= $this->drawRow($row);
            $table .= $this->drawDivider();
        }

        $this->table = $table;
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
     * Calculates the width of each column of the table.
     *
     * This method is used only for plain text tables.
     *
     * @return array
     */
    private function calculateWidthOfColumns()
    {
        // For each row...
        foreach ($this->data as $rowPosition => $rowContent) {
            // ... cycle each column to calculate the length of its cell
            foreach ($rowContent as $columnName => $cellContent) {
                // Get the length of the cell
                $contentLength = iconv_strlen($cellContent);

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

    /**
     * Calculates the width of the entire table.
     */
    private function calculateWidthOfTable()
    {
        $width = 0;

        foreach ($this->columnsWidths as $columnWidth) {
            // To the width of each column add the left and right padding and the separator
            $width += $columnWidth + $this->cellPadding[1] + $this->cellPadding[3] + 1;
        }

        // And add the last separator
        $this->tableWidth = $width + 1;
    }

    /**
     * Draws the horizontal divider.
     *
     * @return string
     */
    private function drawDivider()
    {
        $divider = '';
        foreach ($this->columnsWidths as $width) {
            // Column width position for the xSep + left and rigth padding
            $times = $width + $this->cellPadding[1] + $this->cellPadding[3];
            $divider .= $this->xSep . $this->repeatChar($this->hSep, $times);
        }

        //$divider .= $this->xSep . $this->repeatChar($this->hSep, $this->tableWidth - 2) . $this->xSep . PHP_EOL;

        return $divider . $this->xSep . PHP_EOL;
    }

    /**
     * @param array $row
     *
     * @return string
     */
    private function drawRow(array $row)
    {
        $rowContent = '';
        foreach ($row as $columnName => $cellContent) {
            $rightSpaces = 0;

            if (iconv_strlen($cellContent) < $this->columnsWidths[$columnName]) {
                $rightSpaces = $this->columnsWidths[$columnName] - iconv_strlen($cellContent);
            }

            // Vertical Separator
            $rowContent .= $this->vSep
                // + left padding
                . $this->drawSpaces($this->cellPadding[3])
                // + content
                . trim($cellContent)
                // + right spaces
                . $this->drawSpaces($rightSpaces)
                // + right padding
                . $this->drawSpaces($this->cellPadding[1]);
        }

        // Cell content + the last vertical separator
        return $rowContent . $this->vSep . PHP_EOL;
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
}
