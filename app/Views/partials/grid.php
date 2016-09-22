<?php

fwrite(STDOUT, " ");
for ($x = 1; $x <= count(array_keys($table)); $x++) {
    fwrite(STDOUT, "  $x");
}

fwrite(STDOUT, "\n");

foreach ($table as $k => $v) {
    fwrite(STDOUT, "$k ");

    for ($x = 1; $x <= count(array_keys($table)); $x++) {
        if ($filled && is_array($filled) && array_key_exists($k, $filled) && array_key_exists($x, $filled[$k])) {
            if ($filled[$k][$x] == 0) {
                fwrite(STDOUT, " _ ");
            } else {
                fwrite(STDOUT, " X ");
            }
        } else {
            fwrite(STDOUT, " . ");
        }
    }
    fwrite(STDOUT, "\n");
}
fwrite(
    STDOUT,
    "\n--------------------------------------------------------------------------\n"
    ."Enter coordinates (row, col), e.g. A5 and press return | Enter 'quit' to quit\n"
    ."--------------------------------------------------------------------------\n"
    ."\n"
);
