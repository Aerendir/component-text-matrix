<?php

/*
 * This file is part of the PHPTextMatrix Component.
 *
 * (c) Adamo Crespi <hello@aerendir.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SerendipityHQ\Component\PHPArrayToTxtTable;
use SerendipityHQ\Component\PHPTextMatrix\PHPTextMatrix;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;

/**
 * @author Adamo "Aerendir" Crespi <hello@aerendir.me>
 */
class PHPArrayToTxtTableTest extends \PHPUnit_Framework_TestCase
{
    /** @var  array */
    private $data;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->data = [
            [
                'a' => 'a1',
                'b' => 'b1',
                'c' => 'c1',
            ],
            [
                'a' => 'a2',
                'b' => 'b2',
                'c' => 'c2',
            ],
            [
                'a' => 'a3',
                'b' => 'b3',
                'c' => 'c3',
            ]
        ];
    }

    public function testTextMatrix()
    {
        $expected = <<<EOF
+--+--+--+
|a1|b1|c1|
+--+--+--+
|a2|b2|c2|
+--+--+--+
|a3|b3|c3|
+--+--+--+

EOF;

        $textMatrix = new PHPTextMatrix($this->data);
        $result = $textMatrix->render();

        $this::assertEquals($expected, $result);
    }

    public function testValidationInterceptsMismatchingColumns()
    {
        // Remove a column from a row
        unset($this->data[1]['b']);

        $textMatrix = new PHPTextMatrix($this->data);
        $result = $textMatrix->render();

        $this::assertFalse($result);

        $errors = $textMatrix->getErrors();

        $this::assertSame(1, count($errors));
        $this::assertContains('The number of columns mismatches', $errors[0]);
    }

    public function testValidationInterceptsEmptyMatrix()
    {
        // Remove a column from a row
        $this->data = [];

        $textMatrix = new PHPTextMatrix($this->data);
        $result = $textMatrix->render();

        $this::assertFalse($result);

        $errors = $textMatrix->getErrors();

        $this::assertSame(1, count($errors));
        $this::assertContains('There are no rows in the table', $errors[0]);
    }

    public function testHeaderDrawing()
    {
        $header = [
            'a' => 'Column A',
            'b' => 'Column B',
            'c' => 'Column C',
        ];

        $options = [
            'has_header' => true
        ];

        array_unshift($this->data, $header);

        $expected = <<<EOF
#========#========#========#
#Column A#Column B#Column C#
#========#========#========#
|a1      |b1      |c1      |
+--------+--------+--------+
|a2      |b2      |c2      |
+--------+--------+--------+
|a3      |b3      |c3      |
+--------+--------+--------+

EOF;

        $textMatrix = new PHPTextMatrix($this->data);
        $result = $textMatrix->render($options);

        $this::assertSame($expected, $result);
    }

    public function testHeaderDrawingCanRemoveHeaderTopSeparator()
    {
        $header = [
            'a' => 'Column A',
            'b' => 'Column B',
            'c' => 'Column C',
        ];

        array_unshift($this->data, $header);

        $options = [
            'has_header' => true,
            'show_head_top_sep' => false,
        ];

        $expected = <<<EOF
#Column A#Column B#Column C#
#========#========#========#
|a1      |b1      |c1      |
+--------+--------+--------+
|a2      |b2      |c2      |
+--------+--------+--------+
|a3      |b3      |c3      |
+--------+--------+--------+

EOF;

        $textMatrix = new PHPTextMatrix($this->data);
        $result = $textMatrix->render($options);

        $this::assertSame($expected, $result);
    }

    public function testPaddingAsInteger()
    {
        $options = [
            'cells_padding' => 1
        ];

        $expected = <<<EOF
+----+----+----+
|    |    |    |
| a1 | b1 | c1 |
|    |    |    |
+----+----+----+
|    |    |    |
| a2 | b2 | c2 |
|    |    |    |
+----+----+----+
|    |    |    |
| a3 | b3 | c3 |
|    |    |    |
+----+----+----+

EOF;

        $textMatrix = new PHPTextMatrix($this->data);
        $result = $textMatrix->render($options);

        $this::assertSame($expected, $result);
    }

    public function testPaddingAsArrayWithOneSetting()
    {
        $options = [
            'cells_padding' => [1]
        ];

        $expected = <<<EOF
+----+----+----+
|    |    |    |
| a1 | b1 | c1 |
|    |    |    |
+----+----+----+
|    |    |    |
| a2 | b2 | c2 |
|    |    |    |
+----+----+----+
|    |    |    |
| a3 | b3 | c3 |
|    |    |    |
+----+----+----+

EOF;

        $textMatrix = new PHPTextMatrix($this->data);
        $result = $textMatrix->render($options);

        $this::assertSame($expected, $result);
    }

    public function testPaddingAsArrayWithTwoSettings()
    {
        $options = [
            'cells_padding' => [1, 2]
        ];

        $expected = <<<EOF
+------+------+------+
|      |      |      |
|  a1  |  b1  |  c1  |
|      |      |      |
+------+------+------+
|      |      |      |
|  a2  |  b2  |  c2  |
|      |      |      |
+------+------+------+
|      |      |      |
|  a3  |  b3  |  c3  |
|      |      |      |
+------+------+------+

EOF;

        $textMatrix = new PHPTextMatrix($this->data);
        $result = $textMatrix->render($options);

        $this::assertSame($expected, $result);
    }

    public function testPaddingAsArrayWithThreeSettings()
    {
        $options = [
            'cells_padding' => [1, 2, 3]
        ];

        $expected = <<<EOF
+------+------+------+
|      |      |      |
|  a1  |  b1  |  c1  |
|      |      |      |
|      |      |      |
|      |      |      |
+------+------+------+
|      |      |      |
|  a2  |  b2  |  c2  |
|      |      |      |
|      |      |      |
|      |      |      |
+------+------+------+
|      |      |      |
|  a3  |  b3  |  c3  |
|      |      |      |
|      |      |      |
|      |      |      |
+------+------+------+

EOF;

        $textMatrix = new PHPTextMatrix($this->data);
        $result = $textMatrix->render($options);

        $this::assertSame($expected, $result);
    }

    public function testPaddingAsArrayWithFourSettings()
    {
        $options = [
            'cells_padding' => [1, 2, 3, 4]
        ];

        $expected = <<<EOF
+--------+--------+--------+
|        |        |        |
|    a1  |    b1  |    c1  |
|        |        |        |
|        |        |        |
|        |        |        |
+--------+--------+--------+
|        |        |        |
|    a2  |    b2  |    c2  |
|        |        |        |
|        |        |        |
|        |        |        |
+--------+--------+--------+
|        |        |        |
|    a3  |    b3  |    c3  |
|        |        |        |
|        |        |        |
|        |        |        |
+--------+--------+--------+

EOF;

        $textMatrix = new PHPTextMatrix($this->data);
        $result = $textMatrix->render($options);

        $this::assertSame($expected, $result);
    }

    public function testPaddingAcceptsMaxFourOptions()
    {
        $options = [
            'cells_padding' => [1, 2, 3, 4, 5]
        ];

        $textMatrix = new PHPTextMatrix($this->data);

        $this::throwException(InvalidOptionsException::class);
        $textMatrix->render($options);
    }

    public function testColumnMaxWidth()
    {
        $data = [
            [
                'a' => 'a1',
                'b' => 'longer than 11',
                'c' => 'c1',
            ],
            [
                'a' => 'a2',
                'b' => 'longer than 11',
                'c' => 'c2',
            ],
            [
                'a' => 'a3',
                'b' => 'longer than 11',
                'c' => 'c3',
            ]
        ];

        $options = [
            'columns' => [
                'b' => [
                    'max_width' => 11
                ]
            ]
        ];

        $expected = <<<EOF
+--+-----------+--+
|a1|longer than|c1|
|  |11         |  |
+--+-----------+--+
|a2|longer than|c2|
|  |11         |  |
+--+-----------+--+
|a3|longer than|c3|
|  |11         |  |
+--+-----------+--+

EOF;

        $textMatrix = new PHPTextMatrix($data);
        $result = $textMatrix->render($options);

        $this::assertSame($expected, $result);
    }

    public function testCutDefaultsToFalse()
    {
        $data = [
            [
                'a' => 'a1',
                'b' => 'longer than 11',
                'c' => 'c1',
            ],
            [
                'a' => 'a2',
                'b' => 'longer than 11',
                'c' => 'c2',
            ],
            [
                'a' => 'a3',
                'b' => 'longer than 11',
                'c' => 'c3',
            ]
        ];

        $options = [
            'columns' => [
                'b' => [
                    // This is shorter than "longer" that is 6 characters long
                    'max_width' => 5
                ]
            ]
        ];

        $expected = <<<EOF
+--+------+--+
|a1|longer|c1|
|  |than  |  |
|  |11    |  |
+--+------+--+
|a2|longer|c2|
|  |than  |  |
|  |11    |  |
+--+------+--+
|a3|longer|c3|
|  |than  |  |
|  |11    |  |
+--+------+--+

EOF;

        $textMatrix = new PHPTextMatrix($data);
        $result = $textMatrix->render($options);

        $this::assertSame($expected, $result);
    }

    public function testCutToTrue()
    {
        $data = [
            [
                'a' => 'a1',
                'b' => 'longer than 11',
                'c' => 'c1',
            ],
            [
                'a' => 'a2',
                'b' => 'longer than 11',
                'c' => 'c2',
            ],
            [
                'a' => 'a3',
                'b' => 'longer than 11',
                'c' => 'c3',
            ]
        ];

        $options = [
            'columns' => [
                'b' => [
                    // This is shorter than "longer" that is 6 characters long
                    'max_width' => 5,
                    'cut' => true
                ]
            ]
        ];

        $expected = <<<EOF
+--+-----+--+
|a1|longe|c1|
|  |r    |  |
|  |than |  |
|  |11   |  |
+--+-----+--+
|a2|longe|c2|
|  |r    |  |
|  |than |  |
|  |11   |  |
+--+-----+--+
|a3|longe|c3|
|  |r    |  |
|  |than |  |
|  |11   |  |
+--+-----+--+

EOF;

        $textMatrix = new PHPTextMatrix($data);
        $result = $textMatrix->render($options);

        $this::assertSame($expected, $result);
    }

    public function testAlignRight()
    {
        $data = [
            [
                'a' => 'a1',
                'b' => 'longer than 11',
                'c' => 'c1',
            ],
            [
                'a' => 'a2',
                'b' => 'longer than 11',
                'c' => 'c2',
            ],
            [
                'a' => 'a3',
                'b' => 'longer than 11',
                'c' => 'c3',
            ]
        ];

        $options = [
            'columns' => [
                'b' => [
                    'max_width' => 5,
                    'align' => 'right'
                ]
            ]
        ];

        $expected = <<<EOF
+--+------+--+
|a1|longer|c1|
|  |  than|  |
|  |    11|  |
+--+------+--+
|a2|longer|c2|
|  |  than|  |
|  |    11|  |
+--+------+--+
|a3|longer|c3|
|  |  than|  |
|  |    11|  |
+--+------+--+

EOF;

        $textMatrix = new PHPTextMatrix($data);
        $result = $textMatrix->render($options);

        $this::assertSame($expected, $result);
    }
}