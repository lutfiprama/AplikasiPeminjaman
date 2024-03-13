<?php
require_once('database.php');
session_start();

if (isset($_POST['masuk'])) {
    $login_result = cek_login($_POST['username'], $_POST['password']);

    if ($login_result) {
        $_SESSION['username'] = $login_result['username'];
        $_SESSION['status'] = "login";


        $_SESSION['role'] = $login_result['role'];

        $user_data = get_user_data($_SESSION['username']);

        if ($user_data) {
            $_SESSION['user_data'] = $user_data;
        } else {
            echo "Error: User data retrieval failed.";
            exit();
        }

        if ($_SESSION['role'] == "admin") {
            header("location: admin.php");
        } else {
            header("location: index.php");
        }
    } else {
        header("location: login.php?msg=gagal");
    }
}
?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <title>Login</title>
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
      <form autocomplete="off" action="" method="POST">
        <h2>Log in</h2>
        <div class="inputBox">
          <input type="text" name="username" required="required" />
          <span>Userame</span>
          <i></i>
        </div>
        <div class="inputBox">
          <input type="password" name="password" required="required" />
          <span>Password</span>
          <i></i>
        </div>

        <div class="links">
          <a href="signup.php">Tidak memiliki akun?</a>
        </div>
        <input type="submit" name="masuk" value="Login" />
      </form>
    </div>
  </body>
</html>
