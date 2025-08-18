<?php

$amount = 1000;  

$vat = ($amount * 15) / 100;

$total = $amount + $vat;

echo "Amount: " . $amount . " TK<br>";
echo "VAT (15%): " . $vat . " TK<br>";
echo "Total Amount (including VAT): " . $total . " TK";
?>
