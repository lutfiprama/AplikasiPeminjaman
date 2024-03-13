<?php
require_once('database.php');
session_start();

if (isset($_POST['masuk'])) {
    $nis = $_POST['nis'];
    $username = $_POST['username'];
    $password = md5($_POST['password']);
    $inputdata = "INSERT INTO users (nis, username, password) VALUES ('$nis', '$username', '$password')";

    if (inputdata($inputdata)) {
        $_SESSION['username'] = $username;
        $_SESSION['status'] = "login";
        header("location: login.php");
        exit();
    } else {
        header("location: signup.php?msg=gagal");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Signup</title>
</head>
<style>
    body {
  background-color: #f5f5f5;
  font-family: 'Arial', sans-serif;
  margin: 0;
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
}

.box {
  background-color: #fff;
  border-radius: 8px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  overflow: hidden;
  width: 300px;
  text-align: center;
  padding: 20px;
  box-sizing: border-box;
}

form {
  display: flex;
  flex-direction: column;
}

h2 {
  color: #333;
}

.inputBox {
  position: relative;
  margin-bottom: 20px;
}

input {
  width: 100%;
  padding: 10px;
  box-sizing: border-box;
  border: 1px solid #ccc;
  border-radius: 4px;
  margin-top: 8px;
}

span {
  position: absolute;
  top: 10px;
  left: 10px;
  color: #aaa;
  pointer-events: none;
  transition: 0.2s;
}

input:focus + span, input:valid + span {
  top: -12px;
  font-size: 12px;
  color: #4CAF50;
}

.links {
  margin-top: 15px;
}

a {
  color: #4CAF50;
  text-decoration: none;
}

input[type="submit"] {
  background-color: #4CAF50;
  color: #fff;
  border: none;
  padding: 10px;
  border-radius: 4px;
  cursor: pointer;
  transition: background-color 0.3s;
}

input[type="submit"]:hover {
  background-color: #45a049;
}


  </style>
<body>
    <div class="box">
        <form autocomplete="off" action="signup.php" method="POST">
            <h2>Sign up</h2>
            <div class="inputBox">
                <input type="text" name="nis" required="required" />
                <span>NIS</span>
                <i></i>
</div>

            <div class="inputBox">
                <input type="text" name="username" required="required" />
                <span>Username</span>
                <i></i>
            </div>
            <div class="inputBox">
                <input type="password" name="password" required="required" />
                <span>Password</span>
                <i></i>
            </div>

        
            <input type="submit" name="masuk" value="Create Account" />
        </form>
    </div>
    
</body>
</html>
