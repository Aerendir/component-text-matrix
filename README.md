[![Latest Stable Version](https://poser.pugx.org/serendipity_hq/php_text_matrix/v/stable.png)](https://packagist.org/packages/serendipity_hq/php_text_matrix)
[![Build Status](https://travis-ci.org/Aerendir/PHPTextMatrix.svg?branch=master)](https://travis-ci.org/Aerendir/PHPTextMatrix)
[![Total Downloads](https://poser.pugx.org/serendipity_hq/php_text_matrix/downloads.svg)](https://packagist.org/packages/serendipity_hq/php_text_matrix)
[![License](https://poser.pugx.org/serendipity_hq/php_text_matrix/license.svg)](https://packagist.org/packages/serendipity_hq/php_text_matrix)
[![Code Climate](https://codeclimate.com/github/Aerendir/PHPTextMatrix/badges/gpa.svg)](https://codeclimate.com/github/Aerendir/PHPTextMatrix)
[![Test Coverage](https://codeclimate.com/github/Aerendir/PHPTextMatrix/badges/coverage.svg)](https://codeclimate.com/github/Aerendir/PHPTextMatrix)
[![Issue Count](https://codeclimate.com/github/Aerendir/PHPTextMatrix/badges/issue_count.svg)](https://codeclimate.com/github/Aerendir/PHPTextMatrix)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/54b8a799-e95e-4773-b209-c96862d34476/mini.png)](https://insight.sensiolabs.com/projects/54b8a799-e95e-4773-b209-c96862d34476)
[![Dependency Status](https://www.versioneye.com/user/projects/57e6a26dbd6fa600316f6bf1/badge.svg?style=flat)](https://www.versioneye.com/user/projects/57e6a26dbd6fa600316f6bf1)

# PHPTextMatrix

PHPTextMatrix renders into a plain text table an array representing a matrix of data.

Features:

- Four direction padding (top, right, bottom and left) on a per column basis (following the CSS syntax);
- Max columns width, with possibility to cut words or to maintain their integrity (as the word-wrap CSS property);
- Left and right alignement on a per column basis;
- Customizable vertical, horizontal and cross separators, both for header and for content;
- Different default style for the header (the first row passed);
- Ability to remove the first line divider for the header;

## Install PHPTextMatrix via Composer

    $ composer require serendipity_hq/php_text_matrix

or, in your composer.json

    "require": {
      "serendipity_hq/php_text_matrix": "~1"
    }
  

This library follows the http://semver.org/ versioning conventions.

### Requirements

- PHP: >= 5.6

## How to use PHPTextMatrix

PHPTextMatrix allows a great flexibility in rendering data as plain text tables.
You can customize a lot of aspctes of your generated table.

If some feature is missed, you can open an issue and integrate it and submit a pull request. Remeber to test you new feature: not tested features will not be merged.

### Basic usage

To use PHPTextMatrix you need only an array representing a matrix:

```
// The array containing the data
$data = [
    [
        'title' => '20.000 Leghe sotto i mari',
        'author' => 'Jules Verne',
        'review' => 'Una misteriosa creatura marina che affonda navi da guerra di ogni paese, la scoperta di uno straordinario mezzo meccanico - il sommergibile Nautilus capace di esplorare le profondità degli abissi marini, il suo comandante, il Capitano Nemo, un uomo nobile e generoso che cela però nell\'animo un insopprimibile desiderio di odio e di vendetta, il professor Arronax, il suo servitore Consiglio e il fiociniere Ned Land. E poi le meraviglie di un continente sconosciuto - l\'oceano e i suoi abissi - le esplorazioni attraverso tutti i mari, le lotte contro mostri spaventosi, la fede in una scienza che dovrebbe offrire all\'uomo un progresso inarrestabile, il desiderio di libertà che anima tutti i personaggi del libro.'
    ],
    [
        'title' => 'Il signore degli anelli:        La compagnia dell\'anello',
        'author' => 'J. R. R. Tolkien',
        'review' => 'In questo primo romanzo della trilogia di Tolkien, il lettore conosce gli Hobbit, minuscoli esseri saggi e longevi. Frodo, venuto in possesso dell\'Anello del Potere, è costretto a partire per il paese delle tenebre. Un gruppo di Hobbit lo accompagna e, strada facendo, si associano alla compagnia altri esseri: Elfi, Nani e Uomini, anch\'essi legati al destino di Frodo. Le tappe del cammino li conducono attraverso molte esperienze diverse, finché la scomparsa di Gandalf, trascinato negli abissi da un\'orrenda creatura, li lascia senza guida. Così si scioglie la Compagnia dell\'Anello e i suoi membri si disperdono, minacciati da forze tenebrose, mentre la meta sembra disperatamente allontanarsi.'
    ],
    [
        'title' => 'Il leviatano',
        'author' => 'T. Hobbes contraddittorietà',
        'review' => 'Vitale rappresentazione dello Stato moderno, longeva e barocca figura di pensiero politico nelle sembianze di un mostro biblico, il "Leviatano" è certamente l\'opera filosofica più dibattuta degli ultimi quattro secoli: vi sono contenute le radici del moderno Stato di diritto e di esperienze politiche antiassolutistiche come la Rivoluzione francese, elementi necessari a comprendere le vicende più recenti delle democrazie europee. Pubblicato per la prima volta nel 1651, al termine di un\'epoca che aveva visto l\'Europa dilaniata da guerre civili e di religione, il "Leviatano" definisce tutte le logiche e le categorie della modernità, inclusa la loro duplicità e contraddittorietà. Perché reca in sé non solo un\'architettura di istituzioni, ma anche un campo di conflitti, non solo la stabilità, ma anche la possibilità del fallimento. Superare la forma Stato descritta in queste pagine resta una delle grandi sfide del XXI secolo.'
    ]
];

$table = new \SerendipityHQ\Component\PHPTextMatrix\PHPTextMatrix($data);

echo '<pre>';
echo $table->render();
echo '</pre>';
```

This will render a table like this:

```
+-------------------------------------------------+---------------------------+----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
|20.000 Leghe sotto i mari                        |Jules Verne                |Una misteriosa creatura marina che affonda navi da guerra di ogni paese, la scoperta di uno straordinario mezzo meccanico - il sommergibile Nautilus capace di esplorare le profondità degli abissi marini, il suo comandante, il Capitano Nemo, un uomo nobile e generoso che cela però nell'animo un insopprimibile desiderio di odio e di vendetta, il professor Arronax, il suo servitore Consiglio e il fiociniere Ned Land. E poi le meraviglie di un continente sconosciuto - l'oceano e i suoi abissi - le esplorazioni attraverso tutti i mari, le lotte contro mostri spaventosi, la fede in una scienza che dovrebbe offrire all'uomo un progresso inarrestabile, il desiderio di libertà che anima tutti i personaggi del libro.                                                                                                                                                                                                                          |
+-------------------------------------------------+---------------------------+----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
|Il signore degli anelli: La compagnia dell'anello|J. R. R. Tolkien           |In questo primo romanzo della trilogia di Tolkien, il lettore conosce gli Hobbit, minuscoli esseri saggi e longevi. Frodo, venuto in possesso dell'Anello del Potere, è costretto a partire per il paese delle tenebre. Un gruppo di Hobbit lo accompagna e, strada facendo, si associano alla compagnia altri esseri: Elfi, Nani e Uomini, anch'essi legati al destino di Frodo. Le tappe del cammino li conducono attraverso molte esperienze diverse, finché la scomparsa di Gandalf, trascinato negli abissi da un'orrenda creatura, li lascia senza guida. Così si scioglie la Compagnia dell'Anello e i suoi membri si disperdono, minacciati da forze tenebrose, mentre la meta sembra disperatamente allontanarsi.                                                                                                                                                                                                                                            |
+-------------------------------------------------+---------------------------+----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
|Il leviatano                                     |T. Hobbes contraddittorietà|Vitale rappresentazione dello Stato moderno, longeva e barocca figura di pensiero politico nelle sembianze di un mostro biblico, il "Leviatano" è certamente l'opera filosofica più dibattuta degli ultimi quattro secoli: vi sono contenute le radici del moderno Stato di diritto e di esperienze politiche antiassolutistiche come la Rivoluzione francese, elementi necessari a comprendere le vicende più recenti delle democrazie europee. Pubblicato per la prima volta nel 1651, al termine di un'epoca che aveva visto l'Europa dilaniata da guerre civili e di religione, il "Leviatano" definisce tutte le logiche e le categorie della modernità, inclusa la loro duplicità e contraddittorietà. Perché reca in sé non solo un'architettura di istituzioni, ma anche un campo di conflitti, non solo la stabilità, ma anche la possibilità del fallimento. Superare la forma Stato descritta in queste pagine resta una delle grandi sfide del XXI secolo.|
+-------------------------------------------------+---------------------------+----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
```

### Padding, columns max width and word wrapping

Passing some options to the `render()` method it is possible to customize your plain text table:

```
$options = [
    'columns' => [
        'review' => [
            'max_width' => 50
        ],
        'title' => [
            'max_width' => 20,
            // Equal to CSS word-break: break-all
            'cut' => true
        ]
    ],
    'cells_padding' => 5
];

$table = new \SerendipityHQ\Component\PHPTextMatrix\PHPTextMatrix($data);

echo '<pre>';
echo $table->render($options);
echo '</pre>';
```

This will generate the following table:

```
+------------------------------+-------------------------------------+------------------------------------------------------------+
|                              |                                     |                                                            |
|                              |                                     |                                                            |
|                              |                                     |                                                            |
|                              |                                     |                                                            |
|                              |                                     |                                                            |
|     20.000 Leghe sotto i     |     Jules Verne                     |     Una misteriosa creatura marina che affonda navi da     |
|     mari                     |                                     |     guerra di ogni paese, la scoperta di uno               |
|                              |                                     |     straordinario mezzo meccanico - il sommergibile        |
|                              |                                     |     Nautilus capace di esplorare le profondità degli       |
|                              |                                     |     abissi marini, il suo comandante, il Capitano          |
|                              |                                     |     Nemo, un uomo nobile e generoso che cela però          |
|                              |                                     |     nell'animo un insopprimibile desiderio di odio e       |
|                              |                                     |     di vendetta, il professor Arronax, il suo              |
|                              |                                     |     servitore Consiglio e il fiociniere Ned Land. E        |
|                              |                                     |     poi le meraviglie di un continente sconosciuto -       |
|                              |                                     |     l'oceano e i suoi abissi - le esplorazioni             |
|                              |                                     |     attraverso tutti i mari, le lotte contro mostri        |
|                              |                                     |     spaventosi, la fede in una scienza che dovrebbe        |
|                              |                                     |     offrire all'uomo un progresso inarrestabile, il        |
|                              |                                     |     desiderio di libertà che anima tutti i personaggi      |
|                              |                                     |     del libro.                                             |
|                              |                                     |                                                            |
|                              |                                     |                                                            |
|                              |                                     |                                                            |
|                              |                                     |                                                            |
|                              |                                     |                                                            |
+------------------------------+-------------------------------------+------------------------------------------------------------+
|                              |                                     |                                                            |
|                              |                                     |                                                            |
|                              |                                     |                                                            |
|                              |                                     |                                                            |
|                              |                                     |                                                            |
|     Il signore degli         |     J. R. R. Tolkien                |     In questo primo romanzo della trilogia di Tolkien,     |
|     anelli: La compagnia     |                                     |     il lettore conosce gli Hobbit, minuscoli esseri        |
|     dell'anello              |                                     |     saggi e longevi. Frodo, venuto in possesso             |
|                              |                                     |     dell'Anello del Potere, è costretto a partire per      |
|                              |                                     |     il paese delle tenebre. Un gruppo di Hobbit lo         |
|                              |                                     |     accompagna e, strada facendo, si associano alla        |
|                              |                                     |     compagnia altri esseri: Elfi, Nani e Uomini,           |
|                              |                                     |     anch'essi legati al destino di Frodo. Le tappe del     |
|                              |                                     |     cammino li conducono attraverso molte esperienze       |
|                              |                                     |     diverse, finché la scomparsa di Gandalf,               |
|                              |                                     |     trascinato negli abissi da un'orrenda creatura, li     |
|                              |                                     |     lascia senza guida. Così si scioglie la Compagnia      |
|                              |                                     |     dell'Anello e i suoi membri si disperdono,             |
|                              |                                     |     minacciati da forze tenebrose, mentre la meta          |
|                              |                                     |     sembra disperatamente allontanarsi.                    |
|                              |                                     |                                                            |
|                              |                                     |                                                            |
|                              |                                     |                                                            |
|                              |                                     |                                                            |
|                              |                                     |                                                            |
+------------------------------+-------------------------------------+------------------------------------------------------------+
|                              |                                     |                                                            |
|                              |                                     |                                                            |
|                              |                                     |                                                            |
|                              |                                     |                                                            |
|                              |                                     |                                                            |
|     Il leviatano             |     T. Hobbes contraddittorietà     |     Vitale rappresentazione dello Stato moderno,           |
|                              |                                     |     longeva e barocca figura di pensiero politico          |
|                              |                                     |     nelle sembianze di un mostro biblico, il               |
|                              |                                     |     "Leviatano" è certamente l'opera filosofica più        |
|                              |                                     |     dibattuta degli ultimi quattro secoli: vi sono         |
|                              |                                     |     contenute le radici del moderno Stato di diritto e     |
|                              |                                     |     di esperienze politiche antiassolutistiche come la     |
|                              |                                     |     Rivoluzione francese, elementi necessari a             |
|                              |                                     |     comprendere le vicende più recenti delle               |
|                              |                                     |     democrazie europee. Pubblicato per la prima volta      |
|                              |                                     |     nel 1651, al termine di un'epoca che aveva visto       |
|                              |                                     |     l'Europa dilaniata da guerre civili e di               |
|                              |                                     |     religione, il "Leviatano" definisce tutte le           |
|                              |                                     |     logiche e le categorie della modernità, inclusa        |
|                              |                                     |     la loro duplicità e contraddittorietà. Perché          |
|                              |                                     |     reca in sé non solo un'architettura di                 |
|                              |                                     |     istituzioni, ma anche un campo di conflitti, non       |
|                              |                                     |     solo la stabilità, ma anche la possibilità del         |
|                              |                                     |     fallimento. Superare la forma Stato descritta in       |
|                              |                                     |     queste pagine resta una delle grandi sfide del XXI     |
|                              |                                     |     secolo.                                                |
|                              |                                     |                                                            |
|                              |                                     |                                                            |
|                              |                                     |                                                            |
|                              |                                     |                                                            |
|                              |                                     |                                                            |
+------------------------------+-------------------------------------+------------------------------------------------------------+
```

### Customize Header and separators

```
$data = [
    [
        'quantity' => 'Quantity',
        'description' => 'Description',
        'price' => 'Price'
    ],
    [
        'quantity' => '1 month',
        'description' => 'TrustBack.Me:       Base plan' . PHP_EOL . 'From Sep 26 2016 to Oct 26 2016.',
        'price' => '$29.00'
    ],
    [
        'quantity' => '',
        'description' => 'Credit applied',
        'price' => '-$29.00'
    ]
];

// The array containing the data
$options = [
    'has_header' => true,
    'cells_padding' => [0, 3],
    'sep_head_v' => ' ',
    'sep_head_x' => ' ',
    'sep_head_h' => '-',
    'sep_v' => ' ',
    'sep_x' => ' ',
    'sep_h' => ' ',
    'show_head_top_sep' => false,
    'columns' => [
        'description' => [
            'max_width' => 40,
            // Equal to CSS word-break: break-all
            'cut' => true
        ],
        'price' => [
            'align' => 'right',
            'min_width' => 15
        ]
    ]
];

$table = new \SerendipityHQ\Component\PHPTextMatrix\PHPTextMatrix($data);

echo '<pre>';
echo $table->render($options);
echo '</pre>';
```

This is the generated table:

```
    Quantity       Description                                      Price    
 -------------- -------------------------------------- --------------------- 
    1 month        TrustBack.Me: Base plan                         $29.00    
                   From Sep 26 2016 to Oct 26 2016.                          
                                                                             
                   Credit applied                                 -$29.00    
                                                                             
```

### Final notes

* Use the `cut` option setting it to tru or false to determine if the word can be broken or not (see the [`wordwrap` PHP function](http://php.net/manual/en/function.wordwrap.php))
* The padding follows the rules of the [CSS `padding` rule](http://www.w3schools.com/css/css_padding.asp), so:
If the padding property has **four** values:
    * padding: 25px 50px 75px 100px;
        * top padding is 25px
        * right padding is 50px
        * bottom padding is 75px
        * left padding is 100px
If the padding property has **three** values:
    * padding: 25px 50px 75px;
        * top padding is 25px
        * right and left paddings are 50px
        * bottom padding is 75px
If the padding property has **two** values:
    * padding: 25px 50px;
        * top and bottom paddings are 25px
        * right and left paddings are 50px
If the padding property has **one** value:
    * padding: 25px;
        * all four paddings are 25px
If one value is passed, you can pass it a simple `integer` (ex.: `['cells_padding' => 1]`) or as an array (ex.: `['cells_padding' => [1]`). 

For more information, see the unit tests in the `tests` folder, the methods `resolveOptions()` (where all options are validated) or the examples.
