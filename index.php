<?php

  //<iframe src="01?token=$token"></iframe>

  require_once('cors.php');

  

  function alert($to_alert){
    echo '<script> alert("'.$to_alert.'"); </script>';
  }


  function test_input($data){
    return htmlspecialchars(stripslashes(trim($data))); //All in one!
  }


  function validate($username,$password) {
    $err = "";
    if (!preg_match("/^[@a-zA-Z]{5,30}$/", $username)){
      $err .= "Username cannot contain spaces and special characters. Can only contain letters and the @symbol. Must be at least 5 characters long, at most 30 characters long.";
    }
    if (strlen($password) < 6) {
      $err .= "Password length cannot be less than 6.";
    }
    return $err;
  }


  $tok = isset($_POST['token']) ? $_POST['token'] : (isset($_GET['token']) ? $_GET['token'] : '');

  if(strlen($tok) > 10){
    require_once("dbcontroller.php");
    $token = mysqli_real_escape_string($db,test_input($tok));
  }
  elseif(isset($_POST['login'])){
    $username = test_input($_POST['username']);
    $password = $_POST['password'];
    $err = validate($username,$password);
    if (strlen($err) > 0) {
      alert($err);
      goto skipped;
    }
    require_once("dbcontroller.php");
    $username = mysqli_real_escape_string($db,$username);
    $password = md5($password);
    $q = "SELECT token FROM users WHERE username='$username' AND password='$password'";
    $res = mysqli_query($db, $q);
    if (mysqli_num_rows($res) == 1) {
      while ($row = mysqli_fetch_assoc($res)) {
        $token = $row['token'];
      }
    }
    else {
      alert("Invalid credentials!");
      goto skipped;
    }
  }
  elseif(isset($_POST['register'])){
    $username = test_input($_POST['username']);
    $password = $_POST['password'];
    $err = validate($username,$password);
    if (strlen($err) > 0) {
      alert($err);
      goto skipped;
    }
    require_once("dbcontroller.php");
    $username = mysqli_real_escape_string($db,$username);
    
    $query = "SELECT * FROM users WHERE username='$username'";
    $results = mysqli_query($db, $query);
    if (mysqli_num_rows($results) > 0) {
      alert("Sorry! Username already exits!");
      goto skipped;
    }

    $token = sha1($username);
    $token = uniqid(substr($token, 31, 9)); // uniqid last 9 digits of the earlier sha1
    $password = md5($_POST['password']);
    
    $q = "CREATE TABLE IF NOT EXISTS $token (".
            "id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,".
            "_id varchar(120) NOT NULL,".
            "task_title varchar(100) NOT NULL,".
            "task_body varchar(500) NOT NULL,".
            "isCompleted BOOLEAN DEFAULT false NOT NULL".
        ")".
        "ENGINE = InnoDB;";

    if(!mysqli_query($db, $q)) {
      alert("Error creating token.\\n\\nPlease try again, or kindly contact admin if error persists.");
      goto skipped;
    }
    $q = "INSERT INTO users (username,password,token) VALUES ('$username','$password','$token')";
    mysqli_query($db,$q);
 }
  else{
    header("location: login");
  }


  if (isset($_POST['by-form'])) {
    if (!isset($username)) {
      $q = "SELECT username FROM users WHERE token='$token'";
      $res = mysqli_query($db, $q);
      while ($row = mysqli_fetch_assoc($res)) {
        $username = $row['username'];
      }
    }
    $_SESSION['username'] = $username;
  }


  $fetch_permit = true;
  require("fetch.php");


  skipped:
    mysqli_close($db);
    // There is nothing here...
    // Since an error had been encountered and we wouldn't want other stuffs to execute, we best just skip all.

?>
