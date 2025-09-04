<?php
$age = 20;
if ($age >= 18) {
    echo "You are an adult.<br>";
}

$score = 75;
if ($score >= 85) {
    echo "You passed the exam.<br>";
} else {
    echo "You failed the exam.<br>";
}

$day = "Wednesday";
if ($day == "Monday") {
    echo "Start of the week.<br>";
} elseif ($day == "Friday") {
    echo "End of the work week.<br>";
} else {
    echo "It's a regular day.<br>";
}

$color = "red";
switch ($color) {
    case "blue":
        echo "Color is blue.<br>";
        break;
    case "red":
        echo "Color is red.<br>";
        break;
    default:
        echo "Unknown color.<br>";
        break;
}
?>