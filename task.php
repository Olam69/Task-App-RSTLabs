<?php

	session_start();

	require_once('cors.php');


	$tok = isset($_GET['token']) ? $_GET['token'] : (isset($_SESSION['token']) ? $_SESSION['token'] : '');

	if(strlen($tok) < 10){
		exit_with("login","ERR!\\nParameter `token` cannot be left empty.\\n\\nPlease refer to manual.");
	}

	require_once("dbcontroller.php");
	$GLOBALS['db'] = $db;
	$token = mysqli_real_escape_string($db,test_input($tok));

?>
<?php

	// Global functions

	function test_input($data){
		return htmlspecialchars(stripslashes(trim($data))); //All in one!
	}

	function alert($to_alert){
		echo '<script> alert("'.$to_alert.'"); </script>';
	}

	function exit_with($fallback_page, $exit_message) {
		if(isset($_SESSION['token'])) header("location: $fallback_page");
		else {
			alert($exit_message);
		}
		exit();
	}

	function check_id() {
		if (!isset($_GET['_id']) || empty($_GET['_id'])) {
			exit_with("my-tasks", "ERR!\\nExit Code: No _id parameter set.");
		}
		else return mysqli_real_escape_string($GLOBALS['db'],test_input($_GET['_id']));
	}

?>
<?php

	// Attending to $_POSTs

	if (isset($_POST['create']) || isset($_POST['edit']) || isset($_POST['task_title']) || isset($_POST['task_body'])) {
		$crud_permit = true;
		require("crud.php");
		exit();	
	}

?>
<?php

	// Attending to actions

	if (!isset($_GET['action']) || empty($_GET['action'])) {

		// Simply fetch the particular _id and exit

		$fetch_permit = true;
		$where = true;
		$_id = check_id();
		require("fetch.php");

		exit();
	}

	$action = $_GET['action'];

	$_id = $task_title = $task_body = "";

	$some_actions = ['markdone', 'markundone', 'delete', 'delete_all'];


	if ($action == "edit") {

		$_id = check_id();

		$query = "SELECT task_title, task_body FROM $token WHERE _id='$_id'";
		$results = mysqli_query($db,$query);

		if (mysqli_num_rows($results) == 1) {
			while ($row = mysqli_fetch_assoc($results)) {
				$task_title = $row['task_title'];
				$task_body = $row['task_body'];
			}
		}
		else {
			exit_with("my-tasks", 'ERR!\\nExit Code: No task with _id: \"'.$_id.'\" found.');
		}

	}

	elseif (in_array($action, $some_actions)) {

		if ($action == 'delete_all') {
			$confirm = isset($_GET['confirm']) ? $_GET['confirm'] : false;
			if ($confirm !== "true") {
				exit_with("my-tasks", "ERR!\\nParameter `confirm` cannot be left empty and can only take true.\\n\\nPlease refer to manual.");
			}
			goto permit;
		}

		$_id = check_id();

		permit:
			$crud_permit = true;
			require("crud.php");
			exit();
	}

	elseif ($action == "new") {	
		// Press on!
	}

	else {
		// If value of $action does not match any of the above cases...
		exit_with("my-tasks", "Invalid `action` variable.\\n\\nPlease refer to manual.");
	}


	// Final distinction between logged in and API user

	if(!isset($_SESSION['token']) && isset($_GET['token'])) {
		$query = "SELECT username FROM users WHERE token='$token'";
		$results = mysqli_query($db, $query);
		if(mysqli_num_rows($results) == 1){
			while ($row = mysqli_fetch_assoc($results)) {
				$username = $row['username'];
			}
			goto lastly;
		}
		else {
			alert("Invalid token!");
			exit();
		}
	}


	$username = $_SESSION['username']; 

	// Saving checkpoint
	$_SESSION['coming_from_page'] = $_SERVER['REQUEST_URI'];



	lastly:
		mysqli_close($db);
		unset($GLOBALS['db']);


?>
<!DOCTYPE html>
<html lang="en">
<head>
	
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="Task manager webapp to manage your tasks efficiently. Intuitive and very easy to use.">

	<title>Task Action | RSTLabs</title>

	<link rel="icon" href="assets/images/favicon.ico">
	<link rel="stylesheet" href="vendor/css/bootstrap/bootstrap.min.css">
	<link rel="stylesheet" href="vendor/css/fontawesome.min.css">
	<link rel="stylesheet" href="vendor/css/rstlib/components.rstlib.css">

	<link rel="stylesheet" href="assets/css/styles.css">

	<script src="vendor/js/jquery/jquery.min.js"></script>
	<script src="vendor/js/bootstrap/bootstrap.bundle.min.js"></script>

	<script src="assets/js/scripts.js"></script>

	<style>

		header,section {
			background: transparent;
			backdrop-filter: none;
		}


		@media (max-width: 1023px) {
			section[class*=border-] {
				border: none!important;
			}
		}

		section form {
			background: rgba(255, 255, 255, 0.15);
			width: min(95%, 500px);
			margin: auto;
		}

		form textarea {
			min-height: 224px!important; /* calc(4 * height of input.form-control) */
		}
		
	</style>

</head>
<body class="position-relative p-md-4 p-lg-5">

	<img src="assets/images/setup.jpg" class="body-overlay-img misc-middle position-fixed z-1">

	<header class="px-2 px-md-3 px-lg-0 py-4 position-relative z-2">

		<h1 class="title fs-2 text-capitalize">Task Action</h1>

		<a href="my-tasks" class="logout-span d-inline-block mt-3 pt-1 pb-2 px-2 rounded bg-gradient cursor-pointer">
			<i class="fa fa-arrow-left"></i> <?php echo $username; ?>
		</a>

	</header>

	<section class="py-4 z-2 border-top border-bottom">

		<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" class="rounded my-4 my-lg-5 py-4 px-3">

			<div class="d-flex align-items-center justify-content-between">
				<h2 class="h3 title mb-4 fw-normal text-capitalize"><?php echo $action; ?> Task</h2>
			</div>

			<div class="form-group">
				<div class="form-floating mb-3">
					<input type="text" id="floatingTitle" class="form-control" name="task_title" value="<?php echo $task_title; ?>" placeholder="task title" autocomplete="off" required>
					<label for="floatingTitle">Task title</label>
				</div>

				<div class="form-floating mb-3">
					<textarea id="floatingBody" class="form-control" name="task_body" placeholder="content here..." required><?php echo $task_body; ?></textarea>
					<label for="floatingBody">Content here...</label>
				</div>
			</div>

			<?php if ($action=="edit") echo '<input type="hidden" name="_id" value="'.$_id.'" />'; ?>

			<button type="submit" class="w-100 btn btn-gradient btn-lg" name="<?php if ($action=="new") { echo 'create'; } else { echo 'edit'; } ?>"><i class="fa fa-save me-1"></i> Save<?php if ($action=="edit") { echo ' Changes'; } ?></button>

		</form>

	</section>


	<?php include "footer.php"; ?>


</body>
</html>