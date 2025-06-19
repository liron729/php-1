<?php

$file=fopen("example.txt", "r");

while(!feof($file)) {
    $line = fgets($file). "<br>";
}

fclose($file);
?>