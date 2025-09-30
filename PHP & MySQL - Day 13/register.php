<?php
	include_once('config.php');	

	if(isset($_POST['submit']))
	{
		$name = $_POST['name'];
		$surname = $_POST['surname'];
		$username = $_POST['username'];
		$email = $_POST['email'];
		$tempPass = $_POST['password'];  // Password input from the form
		
		// Don't hash the password; use it as is
		$password = $tempPass;

		// Check if any field is empty
		if(empty($name) || empty($surname) || empty($username) || empty($email) || empty($tempPass))
		{
			echo "You need to fill all the fields.";
		}
		else
		{
			// Check if the username already exists
			$sql = "SELECT username FROM users WHERE username=:username";
			$tempSQL = $conn->prepare($sql);
			$tempSQL->bindParam(':username', $username);
			$tempSQL->execute();

			if($tempSQL->rowCount() > 0)
			{
				echo "Username exists!";
				header("refresh:2; url=signup.php"); 
			}
			else
			{
				// Insert new user into the database with plain password
				$sql = "INSERT INTO users (name, surname, username, email, password) VALUES (:name, :surname, :username, :email, :password)";
				$insertSql = $conn->prepare($sql);

				$insertSql->bindParam(':name', $name); 
				$insertSql->bindParam(':surname', $surname); 
				$insertSql->bindParam(':username', $username);
				$insertSql->bindParam(':email', $email);
				$insertSql->bindParam(':password', $password); // Insert plain text password

				$insertSql->execute();

				echo "Data saved successfully ...";
				header("refresh:2; url=login.php"); 
			}
		}
	}
?>
