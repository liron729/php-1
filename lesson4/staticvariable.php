<?php
function callcounter(){
    static $count = 0;
    $count++;
    echo "the value of count variable is: $count <br>";
}

callcounter();
callcounter();
callcounter();
callcounter();
callcounter();

?>