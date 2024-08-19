<?php

  if (!isset($crud_permit) || $crud_permit !== true) {
    header("location: login");
    exit();
  }


  function query_by_id($token, $_id) {
    $q = "SELECT task_title, task_body FROM $token WHERE _id='$_id'";
    $results = mysqli_query($GLOBALS['db'],$q);
    if (mysqli_num_rows($results) < 1) {
      exit_with("my-tasks", 'ERR!\\nExit Code: No task with _id: \"'.$_id.'\" found.');
    }
    return $results;
  }



  // $token has already been dealt with in task.php and is ready to use.



  $coming_from_page = isset($_SESSION['coming_from_page']) ? $_SESSION['coming_from_page'] : "my-tasks.php";



  if (isset($_POST['create']) || isset($_POST['edit']) || isset($_POST['task_title']) || isset($_POST['task_body'])) {

    // One of them must be set
    if(!isset($_POST['create']) && !isset($_POST['edit'])) {
      exit_with($coming_from_page, "ERR!\\nExit Code: A critical variable appears missing.\\nTip: Either of parameter `edit` or `create` must be present via your POST method and must have value `true`!\\n\\nPlease refer to manual.'");
    }

    // Both of them must be set
    if (!isset($_POST['task_title']) || !isset($_POST['task_body'])) {
      exit_with($coming_from_page, "ERR!\\nExit Code: A critical variable (task_title, task_body) appears missing.\\nTip: Edit and Create only accept values via POST method!\\n\\nPlease refer to manual.'");
    }

    $task_title = mysqli_real_escape_string($db, test_input($_POST['task_title']));
    $task_body = mysqli_real_escape_string($db, htmlspecialchars(trim($_POST['task_body']))); // skip stripslashes() due to the nature of textarea

  }



  // Edit title and body

  if (isset($_POST['edit'])) {

    if (!isset($_POST['_id']) || empty($_POST['_id']) || $_POST['_id'] == " ") {
      exit_with($coming_from_page, "ERR!\\nExit Code: _id cannot be left empty.\\nTip: Edit and Create only accept values via POST method, _id is one them.\\n\\nPlease refer to manual.'");
    }

    $_id = mysqli_real_escape_string($db, test_input($_POST['_id']));

    $results = query_by_id($token, $_id);

    while ($row = mysqli_fetch_assoc($results)) {
      $db_task_title = $row['task_title'];
      $db_task_body = $row['task_body'];
    }


    // Only makes changes if given value is not empty and is not the same as db_value

    $query = "UPDATE $token SET";
    $make_change = false;

    if (!empty($task_title) && $task_title != $db_task_title) {
      $query .= " task_title='$task_title',";
      $make_change = true;
    }

    if (!empty($task_body) && $task_body != $db_task_body) {
      $query .= " task_body='$task_body',";
      $make_change = true;
    }

    $query = substr($query,0,-1);

    $query .= " WHERE _id='$_id'";

    if ($make_change) {
      if(!mysqli_query($db, $query)) {
        exit_with($coming_from_page, "ERR!\\nExit Code: Unable to make required changes.\\nTip: Could be token or invalid _id.");
      }
    }


    // The following is for scroll effect. Nothing major.
    $q = "SELECT isCompleted from $token WHERE _id='$_id'";
    $res = mysqli_query($db, $q);
    while($row = mysqli_fetch_assoc($res)) $isCompleted = $row['isCompleted'];

    $lnd = $isCompleted ? '#table_two_lnd' : '';


    exit_with("my-tasks".$lnd, "Task $_id edit success!");

  }



  // Create

  elseif (isset($_POST['create'])) {

    $empty_variable = false;
    $arr = [$task_title, $task_body];

    foreach ($arr as $str) {
      if (empty($str) || $str == " ") $empty_variable = true;
    }

    if ($empty_variable) exit_with($coming_from_page, "ERR!\\nExit Code: task_title and task_body cannot be empty\\nTip: Edit and Create only accept POST values.\\n\\nPlease refer to manual.");

    $_id = uniqid();

    $query = "INSERT INTO $token (task_title,task_body,isCompleted,_id) VALUES ('$task_title','$task_body',false,'$_id')";

    if(!mysqli_query($db, $query)) {
      exit_with($coming_from_page, "ERR!\\nExit Code: Unable to create task.\\nTip: Could be token.");
    }

    exit_with("my-tasks", "Task created successfully! Assigned _id: $_id.");

  }



  // Markdone or Markundone

  if ($action == "markdone" || $action == "markundone") {

    query_by_id($token, $_id);

    $bool = $action == "markdone" ? 1 : 0; // where 1 means true and 0 false.
    
    $query = "UPDATE $token SET isCompleted=$bool WHERE _id='$_id'";

    if(!mysqli_query($db, $query)) {
      exit_with($coming_from_page, "ERR!\\nExit Code: Unable to make required changes.\\nTip: Could be token or invalid _id.");
    }

    if(!isset($_GET['isAjax'])) {
      exit_with("my-tasks", "Task $_id $action success!");
    }

  }



  // Delete

  elseif ($action == "delete") {

    query_by_id($token, $_id);

    $query = "DELETE from $token WHERE _id='$_id'";
    
    if(!mysqli_query($db, $query)) {
      exit_with("my-tasks", "ERR!\\nExit Code: Unable to delete task.\\nTip: Could be token or invalid _id.");
    }

    if(!isset($_GET['isAjax'])) {
      exit_with("my-tasks", "Deleted! Task $_id delete success!");
    }

  }



  // Delete all

  elseif ($action == "delete_all") {

    // Validate confirm, yet again.
    if (!isset($confirm) || $confirm!=="true") {
      exit_with("my-tasks", "ERR!\\nParameter `confirm` cannot be left empty and can only take true.\\n\\nPlease refer to manual.");
    }

    $query = "DELETE from $token";
    
    if(!mysqli_query($db, $query)) {
      exit_with("my-tasks", "ERR!\\nExit Code: Unable to delete all tasks.\\nTip: Could be token.");
    }

    if(!isset($_GET['isAjax'])) {
      exit_with("my-tasks", "Deleted! All tasks deleted successfully!");
    }

  }



  mysqli_close($db);


?>