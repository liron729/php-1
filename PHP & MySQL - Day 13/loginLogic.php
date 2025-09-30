<?php 
session_start();
require 'config.php';

if(isset($_POST['submit']))
{
  $username = $_POST['username'];
  $password = $_POST['password'];

  if(empty($username) || empty($password))
  {
    echo "Fill all the fields!";
    header( "refresh:2; url=login.php" ); 
  }else{
    $sql = "SELECT * FROM users WHERE username=:username";
    $insertSql = $conn->prepare($sql);
    $insertSql->bindParam(':username', $username);

    $insertSql->execute();
    
    if($insertSql->rowCount() > 0) { 
        $data = $insertSql->fetch();

        // Direct password comparison (no hashing)
        if($password == $data['password']){
          // Password is correct, start the session and redirect
          $_SESSION['username'] = $data['username'];
          header("Location: dashboard.php");
          exit; // Always exit after a redirect to prevent further code execution
        } else {
          // Incorrect password, redirect back to login page with error message
          echo "Password incorrect";
          header("refresh:2; url=login.php"); 
          exit; // Prevent further code execution after the redirect
        }
    } else {
        // User not found
        echo "User not found!";
        header("refresh:2; url=login.php"); 
        exit;
    }
  }
}
?>

