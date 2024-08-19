<?php

	session_start();


	if (isset($_GET['logout'])) {
		
		session_destroy();

		// Expire cookies
		$arr = ['token','name','trust_table_one','trust_table_two'];
		foreach ($arr as $val) {
			setcookie($val,'',time()+(-3600));
		}
		
		header('location: login');
	
	}


	if ((isset($_COOKIE['token']) && !isset($_COOKIE['name'])) || isset($_COOKIE['name']) && !isset($_COOKIE['token'])) {
		header("location: login?logout");
	}

	if (!isset($_SESSION['token']) && isset($_COOKIE['token'])) {
		$_SESSION['token'] = $_COOKIE['token'];
		$_SESSION['username'] = $_COOKIE['name'];
	}

	if (isset($_SESSION['token'])) {
		header('location: my-tasks');
	}

	if (isset($_POST['login']) || isset($_POST['register'])) {
		require "index.php";
	}


?>
<!DOCTYPE html>
<html lang="en">
<head>
	
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="Task manager webapp to manage your tasks efficiently. Intuitive and very easy to use.">

	<title>Task List | RSTLabs</title>

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

		section.log-reg .nav-link:not(.active) {
			color: #eee;
		}

		section.log-reg form {
			background: rgba(255, 255, 255, 0.15);
			width: min(95%, 500px);
			margin: auto;
		}
		
	</style>

</head>
<body class="position-relative p-md-4 p-lg-5">

	<img src="assets/images/setup.jpg" class="body-overlay-img misc-middle position-fixed z-1">

	<header class="px-2 px-md-3 px-lg-0 py-4 position-relative z-2">

		<h1 class="title fs-2">Login | Create Account</h1>

	</header>

	<section class="log-reg py-4 z-2">

		<div class="nav nav-tabs mb-3" id="nav-tab" role="tablist">
			<button class="nav-link active" id="nav-login-tab" data-bs-toggle="tab" data-bs-target="#nav-login" type="button" role="tab" aria-controls="nav-login" aria-selected="true">Login</button>
			<button class="nav-link" id="nav-register-tab" data-bs-toggle="tab" data-bs-target="#nav-register" type="button" role="tab" aria-controls="nav-register" aria-selected="false">Create Account</button>
		</div>

		<div class="tab-content" id="nav-tabContent">
			

			<!-- Login -->

			<div class="tab-pane fade show active" id="nav-login" role="tabpanel" aria-labelledby="nav-login-tab">

				<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" id="login_form" class="rounded py-4 px-3 mt-5 mb-4">

					<div class="d-flex align-items-center justify-content-between">
						<h2 class="h3 title mb-4 fw-normal">Sign in</h2>
						<div class="mb-4 text-center" style="cursor: default;">
							<label class="p-2" title="Login with username">
								<input type="radio" name="verify" value="by-username" onclick="toggle_showForm('u','t')" checked>
							</label>
							<span class="text-light">OR</span>
							<label class="p-2" title="Login with token">
								<input type="radio" name="verify" value="by-token" onclick="toggle_showForm('t','u')">
							</label>
						</div>
					</div>


					<!-- Login with username -->
					<div id="u" class="form-group pb-1">
						<div class="form-floating mb-3">
							<input type="text" id="floatingUsername" class="form-control" name="username" placeholder="username" pattern="[@a-zA-Z]{5,30}" title="Username cannot contain spaces and special characters. Can only contain letters and the @symbol. Must be at least 5 characters long, at most 30 characters long." required>
							<label for="floatingUsername">Username</label>
						</div>

						<div class="form-floating mb-3">
							<input type="password" id="floatingPassword" class="form-control" name="password" placeholder="Password" minlength="6" required>
							<label for="floatingPassword">Password</label>
						</div>
					</div>

					<!-- Login with token -->
					<div id="t" class="form-group my-4 py-2" style="display: none;">
						<div class="mb-3">
							<input type="text" id="floatingToken" class="form-control text-center fs-6" name="token" style="height: 45px;" placeholder="paste your token...">
						</div>
					</div>


					<div class="text-center mb-4">
						<label style="color: wheat;">
							<input type="checkbox" name="remember-me"> Remember me
						</label>
					</div>

					<input type="hidden" name="by-form">

					<button type="submit" class="w-100 btn btn-gradient btn-lg" name="login">Sign in</button>

				</form>
			
			</div>



			<!-- Register -->

			<div class="tab-pane fade" id="nav-register" role="tabpanel" aria-labelledby="nav-register-tab">
			
				<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" class="rounded py-4 px-3 mt-5 mb-4">

					<h2 class="h3 title mb-4 fw-normal">Create Account</h2>

					<div class="form-floating mb-3">
						<input type="text" id="floatingRegInput" class="form-control" name="username" placeholder="choose your username" autocomplete="off" pattern="[@a-zA-Z]{4,30}" title="Username cannot contain spaces and special characters. Can only contain letters and the @symbol. Must be at least 4 characters long, at most 30 characters long." required>
						<label for="floatingRegInput">Username you will remember</label>
					</div>

					<div class="form-floating mb-3">
						<input type="password" id="floatingRegPassword" class="form-control" name="password" placeholder="Password" minlength="6" autocomplete="off" required>
						<label for="floatingRegPassword">Password</label>
					</div>

					<input type="hidden" name="by-form">

					<button type="submit" class="w-100 btn btn-gradient btn-lg" name="register">Create Account</button>

				</form>
			
			</div>
		
		</div>

	</section>


	<?php include "footer.php"; ?>


</body>
</html>