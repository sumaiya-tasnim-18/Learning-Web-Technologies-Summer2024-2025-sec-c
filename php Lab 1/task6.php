<?php
$numbers = [10, 20, 30, 40, 50];
$search = 50;
$found = false;
 
foreach ($numbers as $num) {
    if ($num == $search) {
        $found = true;
        break;
    }
}
if ($found) {
    echo "$search is in the array";
} else {
    echo "$search is not in the array";
}
?>
 