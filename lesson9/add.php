<?php

include_once('config.php');

if(isset($_POST['submit'])){
    $name=$_POST['name'];
    $surname=$_POST['surname'];
    $email=$_POST['email'];

    $sql="INSERT INTO users(name,surname,email) VALUES(:name,:surname,:email)";
    $sqlquery=$conn->prepare($sql);

    $sqlquery->bindParam(':name',$name);
    $sqlquery->bindParam(':surname',$surname);
    $sqlquery->bindParam(':email',$email);

    $sqlquery->execute();


  echo "data saved successfully";
}
?>