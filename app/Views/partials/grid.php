<?php
fwrite(STDOUT, " ");
for ($x = 1; $x <= count(array_keys($table)); $x++) {
    fwrite(STDOUT, "  $x");
}

fwrite(STDOUT, "\n");

foreach ($table as $k => $v) {
    fwrite(STDOUT, "$k ");

    for ($x = 1; $x <= count(array_keys($table)); $x++) {

        /**
         * @note: in table will be better formatting, but missing from requirements
         *        10 is not hardcoded, 10 is first number with strlen === 3
         */
        if ($x > 10) {
            fwrite(STDOUT, " ");
        }

        if ($filled && is_array($filled) && array_key_exists($k.$x, $filled)) {
            if ($filled[$k.$x] == 0) {
                fwrite(STDOUT, " _ ");
            } else {
                fwrite(STDOUT, " X ");
            }
        } else {
            fwrite(STDOUT, " $closed ");
        }
    }
    fwrite(STDOUT, "\n");
}
fwrite(STDOUT, $bottomBar);
