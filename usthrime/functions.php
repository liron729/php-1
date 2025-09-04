<?php

$a=5;
$b=10;

function add($a, $b) {
    return $a + $b;
}
echo add($a, $b);
function subtract($a, $b) {
    return $a - $b;
}
echo subtract($a, $b);

function multiply($a, $b) {
    return $a * $b;
}
echo multiply($a, $b);

function divide($a, $b) {
    if ($b == 0) {
        return null; 
    }
    return $a / $b;
}
echo divide($a, $b);
?>