<?php

  if (!isset($fetch_permit) || $fetch_permit !== true) {
    header("location: login");
    exit();
  }


  $objresult = [];


  $query = "SELECT * FROM $token";
  $query .= isset($where) ? " WHERE _id='$_id'" : '';
  $results = mysqli_query($db, $query);
  if(!$results) {
    array_push($objresult, array("task_title"=>"Invalid token!", "task_body"=>"Invalid token!","isCompleted"=>"Invalid token!","_id"=>null));
  }
  
  else {
    if (mysqli_num_rows($results) > 0) {
      while($row = mysqli_fetch_assoc($results)){
        array_push($objresult, array("task_title"=>$row['task_title'], "task_body"=>htmlspecialchars_decode($row['task_body']),"isCompleted"=>$row['isCompleted'],"_id"=>$row['_id']));
      }
    }

    else {
      array_push($objresult, array("task_title"=>"empty", "task_body"=>"empty","isCompleted"=>false,"_id"=>null));
    }
  }


  // Using $results as a direct array in my-tasks.php without having to json_encode and json_decode since its in the same server as this page and mere file inclusion.
  $results = $objresult;


  if (isset($_POST['login']) || isset($_POST['register'])) {
    $_SESSION['token'] = $token;
    if (isset($_POST['remember-me']) && $_POST['remember-me']=="on") {
      // Set cookies. 120days.
      setcookie('token', $_SESSION['token'], time()+(3600*24*120));
      setcookie('name', $_SESSION['username'], time()+(3600*24*120));
    }
    header("location: my-tasks");
  }


  elseif(isset($_GET['token']) || isset($_POST['token'])) echo json_encode($objresult);

?>