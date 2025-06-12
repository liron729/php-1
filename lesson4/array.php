<?php


$sports=['football','basketball','handball','voleyball']
// echo $sports[0];
// echo end($sports);
// echo count($sports);

// for($i = 0; $i < 4; $i++) {
//     echo $sports[$i] ; "\n";
// }

$len = count($sports);
for($i = 0; $i < $len; $i++) {
    echo $sports[$i] . "\n";
}




?>