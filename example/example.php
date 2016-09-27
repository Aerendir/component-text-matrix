<?php

require_once '../vendor/autoload.php';

// The array containing the data
$data = [
    [
        'title'  => '20.000 Leghe sotto i mari',
        'author' => 'Jules Verne',
        'review'=> 'Una misteriosa creatura marina che affonda navi da guerra di ogni paese, la scoperta di uno straordinario mezzo meccanico - il sommergibile Nautilus capace di esplorare le profondità degli abissi marini, il suo comandante, il Capitano Nemo, un uomo nobile e generoso che cela però nell\'animo un insopprimibile desiderio di odio e di vendetta, il professor Arronax, il suo servitore Consiglio e il fiociniere Ned Land. E poi le meraviglie di un continente sconosciuto - l\'oceano e i suoi abissi - le esplorazioni attraverso tutti i mari, le lotte contro mostri spaventosi, la fede in una scienza che dovrebbe offrire all\'uomo un progresso inarrestabile, il desiderio di libertà che anima tutti i personaggi del libro.'
    ],
    [
        'title'  => 'Il signore degli anelli:        La compagnia dell\'anello',
        'author' => 'J. R. R. Tolkien',
        'review' => 'In questo primo romanzo della trilogia di Tolkien, il lettore conosce gli Hobbit, minuscoli esseri saggi e longevi. Frodo, venuto in possesso dell\'Anello del Potere, è costretto a partire per il paese delle tenebre. Un gruppo di Hobbit lo accompagna e, strada facendo, si associano alla compagnia altri esseri: Elfi, Nani e Uomini, anch\'essi legati al destino di Frodo. Le tappe del cammino li conducono attraverso molte esperienze diverse, finché la scomparsa di Gandalf, trascinato negli abissi da un\'orrenda creatura, li lascia senza guida. Così si scioglie la Compagnia dell\'Anello e i suoi membri si disperdono, minacciati da forze tenebrose, mentre la meta sembra disperatamente allontanarsi.'
    ],
    [
        'title'  => 'Il leviatano',
        'author' => 'T. Hobbes contraddittorietà',
        'review' => 'Vitale rappresentazione dello Stato moderno, longeva e barocca figura di pensiero politico nelle sembianze di un mostro biblico, il "Leviatano" è certamente l\'opera filosofica più dibattuta degli ultimi quattro secoli: vi sono contenute le radici del moderno Stato di diritto e di esperienze politiche antiassolutistiche come la Rivoluzione francese, elementi necessari a comprendere le vicende più recenti delle democrazie europee. Pubblicato per la prima volta nel 1651, al termine di un\'epoca che aveva visto l\'Europa dilaniata da guerre civili e di religione, il "Leviatano" definisce tutte le logiche e le categorie della modernità, inclusa la loro duplicità e contraddittorietà. Perché reca in sé non solo un\'architettura di istituzioni, ma anche un campo di conflitti, non solo la stabilità, ma anche la possibilità del fallimento. Superare la forma Stato descritta in queste pagine resta una delle grandi sfide del XXI secolo.'
    ]
];

$table = new \SerendipityHQ\Component\PHPTextMatrix\PHPTextMatrix($data);

echo '<pre>';
echo $table->render();
echo '</pre>';

// The array containing the data
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
            'align' => 'right'
        ]
    ]
];

$table = new \SerendipityHQ\Component\PHPTextMatrix\PHPTextMatrix($data);

echo '<pre>';
echo $table->render($options);
echo '</pre>';
