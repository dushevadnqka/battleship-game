<?php
fwrite(STDOUT, "\n");

$filled = '';

require 'partials/grid.php';

do {
    do {
        $selection = readline("Coordinates: ");
    } while (trim($selection) == '');

    $triger = \App\Controllers\ConsoleController::process($selection);

    if (array_key_exists('message', $triger)) {
        fwrite(STDOUT, "\n*** ".$triger['message']." ***\n");
    }

    if (array_key_exists('result', $triger)) {
        $filled = $triger['result'];
    }

    require 'partials/grid.php';
} while ($selection != 'quit');
exit(0);
