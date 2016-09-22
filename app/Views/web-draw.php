<html>
    <head></head>
    <body>
        <?php
        $filled = '';

        if (array_key_exists('result', $_SESSION)) {
            $filled = $_SESSION['result'];
        }

        echo "\n\n<pre>\n\n";

        if (array_key_exists('flash_message', $_SESSION)) {
            echo "*** ".$_SESSION['flash_message']." ***\n\n";
        }
        echo " ";

        for ($x = 1; $x <= count(array_keys($table)); $x++) {
            echo " $x ";
        }

        echo PHP_EOL;

        foreach ($table as $k => $v) {
            echo $k;

            for ($x = 1; $x <= count(array_keys($table)); $x++) {
                if ($filled && is_array($filled) && array_key_exists($k, $filled)
                    && array_key_exists($x, $filled[$k])) {
                    if ($filled[$k][$x] == 0) {
                        echo " _ ";
                    } else {
                        echo " X ";
                    }
                } else {
                    echo " . ";
                }
            }
            echo PHP_EOL;
        }
        echo '</pre>';

        if (array_key_exists('ending_message', $_SESSION)) {
            echo $_SESSION['ending_message'];
        }
        ?>

        <form method="POST" action="main/shoot">
            <p>Enter coordinates (row, col), e.g. A5 <input type='text' name='coordinates' size='5' autocomplete='off'> <input type='submit' value='Submit Query'></p>
        </form>

    </body>
</html>

