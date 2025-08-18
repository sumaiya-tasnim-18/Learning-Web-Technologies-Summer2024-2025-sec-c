<?php
echo "Odd numbers between 10 and 100 are:<br>";

for ($i = 10; $i <= 100; $i++) {

    if ($i % 2 != 0) {
        echo $i . " ";
    }
}
?>
