<p align="center">
    <a href="http://www.serendipityhq.com" target="_blank">
        <img style="max-width: 350px" src="http://www.serendipityhq.com/assets/open-source-projects/Logo-SerendipityHQ-Icon-Text-Purple.png">
    </a>
</p>

<h1 align="center">Serendipity HQ Text Matrix</h1>
<p align="center">Renders into a plain text table an array representing a matrix of data.</p>
<p align="center">
    <a href="https://github.com/Aerendir/component-text-matrix/releases"><img src="https://img.shields.io/packagist/v/serendipity_hq/component-text-matrix.svg?style=flat-square"></a>
    <a href="https://opensource.org/licenses/MIT"><img src="https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square"></a>
    <a href="https://github.com/Aerendir/component-text-matrix/releases"><img src="https://img.shields.io/packagist/php-v/serendipity_hq/component-text-matrix?color=%238892BF&style=flat-square&logo=php" /></a>
</p>
<p>
    Supports <br />
    <a title="Supports Symfony ^4.4" href="https://github.com/Aerendir/component-text-matrix/actions"><img title="Supports Symfony ^4.4" src="https://img.shields.io/badge/Symfony-%5E4.4-333?style=flat-square&logo=symfony" /></a>
    <a title="Supports Symfony ^5.4" href="https://github.com/Aerendir/component-text-matrix/actions"><img title="Supports Symfony ^5.4" src="https://img.shields.io/badge/Symfony-%5E5.4-333?style=flat-square&logo=symfony" /></a>
    <a title="Supports Symfony ^6.0" href="https://github.com/Aerendir/component-text-matrix/actions"><img title="Supports Symfony ^6.0" src="https://img.shields.io/badge/Symfony-%5E6.0-333?style=flat-square&logo=symfony" /></a>
</p>
<p>
    Tested on <br />
    <a title="Tested with Symfony ^4.4" href="https://github.com/Aerendir/component-text-matrix/actions"><img title="Tested with Symfony ^4.4" src="https://img.shields.io/badge/Symfony-%5E4.4-333?style=flat-square&logo=symfony" /></a>
    <a title="Tested with Symfony ^5.4" href="https://github.com/Aerendir/component-text-matrix/actions"><img title="Tested with Symfony ^5.4" src="https://img.shields.io/badge/Symfony-%5E5.4-333?style=flat-square&logo=symfony" /></a>
    <a title="Tested with Symfony ^6.0" href="https://github.com/Aerendir/component-text-matrix/actions"><img title="Tested with Symfony ^6.0" src="https://img.shields.io/badge/Symfony-%5E6.0-333?style=flat-square&logo=symfony" /></a>
</p>
<p align="center">
    <a href="https://www.php.net/manual/en/book.iconv.php"><img src="https://img.shields.io/badge/Requires-ext--iconv-%238892BF?style=flat-square&logo=php"></a>
</p>

## Current Status

[![Coverage](https://sonarcloud.io/api/project_badges/measure?project=Aerendir_component-text-matrix&metric=coverage)](https://sonarcloud.io/dashboard?id=Aerendir_component-text-matrix)
[![Maintainability Rating](https://sonarcloud.io/api/project_badges/measure?project=Aerendir_component-text-matrix&metric=sqale_rating)](https://sonarcloud.io/dashboard?id=Aerendir_component-text-matrix)
[![Quality Gate Status](https://sonarcloud.io/api/project_badges/measure?project=Aerendir_component-text-matrix&metric=alert_status)](https://sonarcloud.io/dashboard?id=Aerendir_component-text-matrix)
[![Reliability Rating](https://sonarcloud.io/api/project_badges/measure?project=Aerendir_component-text-matrix&metric=reliability_rating)](https://sonarcloud.io/dashboard?id=Aerendir_component-text-matrix)
[![Security Rating](https://sonarcloud.io/api/project_badges/measure?project=Aerendir_component-text-matrix&metric=security_rating)](https://sonarcloud.io/dashboard?id=Aerendir_component-text-matrix)
[![Technical Debt](https://sonarcloud.io/api/project_badges/measure?project=Aerendir_component-text-matrix&metric=sqale_index)](https://sonarcloud.io/dashboard?id=Aerendir_component-text-matrix)
[![Vulnerabilities](https://sonarcloud.io/api/project_badges/measure?project=Aerendir_component-text-matrix&metric=vulnerabilities)](https://sonarcloud.io/dashboard?id=Aerendir_component-text-matrix)

![Phan](https://github.com/Aerendir/component-text-matrix/workflows/Phan/badge.svg)
![PHPStan](https://github.com/Aerendir/component-text-matrix/workflows/PHPStan/badge.svg)
![PSalm](https://github.com/Aerendir/component-text-matrix/workflows/PSalm/badge.svg)
![PHPUnit](https://github.com/Aerendir/component-text-matrix/workflows/PHPunit/badge.svg)
![Composer](https://github.com/Aerendir/component-text-matrix/workflows/Composer/badge.svg)
![PHP CS Fixer](https://github.com/Aerendir/component-text-matrix/workflows/PHP%20CS%20Fixer/badge.svg)
![Rector](https://github.com/Aerendir/component-text-matrix/workflows/Rector/badge.svg)

## Features

- Four direction padding (top, right, bottom and left) on a per column basis (following the CSS syntax);
- Max columns width, with possibility to cut words or to maintain their integrity (as the word-wrap CSS property);
- Left and right alignement on a per column basis;
- Customizable vertical, horizontal and cross separators, both for header and for content;
- Different default style for the header (the first row passed);
- Ability to remove the first line divider for the header;

<hr />
<h3 align="center">
    <b>Do you like this library?</b><br />
    <b><a href="#js-repo-pjax-container">LEAVE A &#9733;</a></b>
</h3>
<p align="center">
    or run<br />
    <code>composer global require symfony/thanks && composer thanks</code><br />
    to say thank you to all libraries you use in your current project, this included!
</p>
<hr />

## Install Serendipity HQ Text Matrix via Composer

    $ composer require serendipity_hq/component-text-matrix

This library follows the http://semver.org/ versioning conventions.

## How to use Serendipity HQ Text Matrix

component-text-matrix allows a great flexibility in rendering data as plain text tables.
You can customize a lot of aspctes of your generated table.

If some feature is missed, you can open an issue and integrate it and submit a pull request. Remeber to test you new feature: not tested features will not be merged.

### Basic usage

To use component-text-matrix you need only an array representing a matrix:

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

$table = new \SerendipityHQ\Component\component-text-matrix\component-text-matrix($data);

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

$table = new \SerendipityHQ\Component\component-text-matrix\component-text-matrix($data);

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

$table = new \SerendipityHQ\Component\component-text-matrix\component-text-matrix($data);

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

<hr />
<h3 align="center">
    <b>Do you like this library?</b><br />
    <b><a href="#js-repo-pjax-container">LEAVE A &#9733;</a></b>
</h3>
<p align="center">
    or run<br />
    <code>composer global require symfony/thanks && composer thanks</code><br />
    to say thank you to all libraries you use in your current project, this included!
</p>
<hr />
