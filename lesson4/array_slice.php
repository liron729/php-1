<?php
    
    $sport=array('football','basketball','handball','voleyball');
    $slice = array_slice($sport,2);
    $slice = array_slice($sport,-2);
    $slice = array_slice($sport,0, 3);
    var_dump($output1, $output2, $output3);


?>