<?php
fwrite(STDOUT, "\n");

$filled    = '';
$closed    = '.';
$status    = '';
$bottomBar = "\n--------------------------------------------------------------------------\n"
    ."Enter coordinates (row, col), e.g. A5 and press return | Enter 'quit' to quit\n"
    ."--------------------------------------------------------------------------\n"
    ."\n";

require 'partials/grid.php';

do {
    do {
        $selection = readline("Coordinates: ");
    } while (trim($selection) == '');

    $action = new \App\Controllers\ConsoleController();

    $triger = $action->process($selection);

    if (array_key_exists('message', $triger)) {

        $status = "\n*** ".$triger['message']." ***\n";

        if ($triger['message'] === 'Show') {
            $status = '';
            $closed = ' ';
        } elseif ($triger['message'] === 'End') {
            $count     = count($triger['data']);
            $bottomBar = "\nWell done! You completed the game in $count shots.\nTo play a new game, please type in your cli 'php public/index.php' and hit return!\n\n";
            require 'partials/grid.php';
            exit(0);
        }else{
           $closed    = '.';
        }

        fwrite(STDOUT, $status);
    }

    if (array_key_exists('data', $triger)) {
        $filled = $triger['data'];
    }

    require 'partials/grid.php';
} while ($selection != 'quit');
exit(0);
