<html>
    <head></head>
    <body>
        <?php

        $filled = '';
        $closed = '.';
        $status = '';

        if (array_key_exists('result', $_SESSION)) {
            $filled = $_SESSION['result'];
        }

        echo "\n\n<pre>\n\n";

        if (array_key_exists('flash_message', $_SESSION)) {
            $status = "*** ".$_SESSION['flash_message']." ***\n\n";

            if($_SESSION['flash_message'] === 'Show'){
               $status = '';
               $closed = ' ';
            }
        }

        echo $status;
        echo " ";

        for ($x = 1; $x <= count(array_keys($table)); $x++) {
            echo " $x ";
        }

        echo PHP_EOL;

        foreach ($table as $k => $v) {
            echo $k;

            for ($x = 1; $x <= count(array_keys($table)); $x++) {

                /**
                 * @note: in table will be better formatting, but missing from requirements
                 *        10 is not hardcoded, 10 is first number with strlen === 3
                 */
                if($x > 10){
                    echo " ";
                }

                if ($filled && is_array($filled) && array_key_exists($k.$x, $filled)) {

                    if ($filled[$k.$x] == 0) {
                        echo " - ";
                    } else {
                        echo " X ";
                    }
                } else {
                    echo " $closed ";
                }
            }
            echo PHP_EOL;
        }
        echo "</pre>";
        ?>

        <form method="POST" action="main/shoot">
            <p>Enter coordinates (row, col), e.g. A5 <input type="text" name="coordinates" size="5" autocomplete="off"> <input type="submit" value="Submit Query"></p>
        </form>

    </body>
</html>

