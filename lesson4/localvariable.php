<?php
$x=5; //global variable
function localvariable(){
    $y=10; //local variable
    echo $y;

}
localvariable();
echo "\n, $x";
?>