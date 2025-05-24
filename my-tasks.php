<?php

	session_start();

	if (!isset($_SESSION['token'])) {
		header('location: login');
		exit(); // Blocks intrusion. Leave line as is.
	}

	$token = $_SESSION['token'];

	if (strlen($token) < 10) header('location: login?logout');


	require_once("dbcontroller.php");

	$fetch_permit = true;
	require_once("fetch.php");


	$total_records_per_page = 10;
	$start_page_at = 0;
	$records_length = $total_records_per_page - 1; // This will allow to display from 0 to 9 (both inclusive)


	$username = $_SESSION['username'];

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
	<script src="vendor/js/rstlib/search.rstlib.js"></script>
	<script src="vendor/js/rstlib/lightcookie.rstlib.js"></script>

	<script src="assets/js/scripts.js"></script>

	<style>

		/*** Header ***/
		header input {
			background: #f3f3f3!important;
			border-color: transparent!important;
			box-shadow: none!important;
		}

		header .animate-opacity {
			animation-duration: .3s;
		}


		/*** Table Section ***/
		section.table-section td a {
			color: var(--bs-table-striped-color);
			opacity: 1;
		}

		section.table-section td a:hover {
			opacity: .8;
		}

		section.table-section button:not(.btn) {
			background: transparent;
			padding: 3px;
			cursor: pointer;
			text-shadow: 0 0 20px;
			border-radius: 50%;
		}

		section.table-section button:not(.text-success):focus {
			text-shadow: none;
			transition-duration: 0s;
		}

		table th.action-col {
			width: 40px;
			text-align: center;
		}

		section.table-section .text-faded:not(.text-success):after {
			content: '?';
			position: relative;
			top: -3px;
			left: 2px;
		}

		section.table-section .note_content:not(.unblur) {
			filter: blur(20px);
		}

		section.table-section .note_content.unblur {
			transition: .4s all;
		}


		/*** Pagination ***/
		.pagination .page-link {
			background: transparent;
			color: #ccc;
			border-color: #ccc;
			border-radius: 0!important;
		}

		.pagination .page-link.active, .pagination .page-link:hover {
			background: rgba(255,255,255, .3);
			color: #fff;
		}

		.pagination .page-link.active {
			box-shadow: 0 0 0 0.25rem rgba(255,255,255, .3);
		}

		.pagination .page-link:not(.active):focus {
			background: transparent;
			color: black;
			box-shadow: none;
		}


		/*** Add New ***/
		.add-new > * {
			margin: auto;
			display: block;
		}
		.add-new .line {
			height: 1px;
			border-top: 1px solid #aaa;
			width: 40%;
		}
		.add-new a {
			color: wheat;
			border: 1px dashed wheat;
			z-index: 2;
		}
		.add-new a:hover, .add-new a:focus {
			background: wheat;
			color: var(--bs-dark);
		}
		.add-new a:focus {
			box-shadow: 0 0 0 .25rem rgba(245, 222, 179, .5);
		}

	</style>

</head>
<body class="position-relative p-md-4 p-lg-5">

	<img src="assets/images/setup.jpg" class="body-overlay-img misc-middle position-fixed z-1">

	<header class="px-2 px-md-3 px-lg-0 py-4 position-relative z-3">

		<div class="d-flex align-items-center justify-content-between">
			<h1 class="title w-25 w-md-auto fs-2">My Tasks</h1>
			<nav id="nav" class="text-end">
				<div class="btn-group w-75 d-md-none">
					<a href="task?action=new" class="btn btn-outline-secondary"><i class="fa fa-plus me-1"></i> new task</a>
					<button class="btn btn-outline-secondary" onclick="toggleNav()"><i class="fa fa-search me-1"></i> search</button>
				</div>
			</nav>
			<input type="search" id="search_box" class="form-control w-75 w-md-auto d-md-block animate-opacity collapse" placeholder="Search" autocomplete="on" oninput="rstSearch(this.value,'note_body')">
		</div>

		<button id="close_button" class="misc-topright btn btn-dark text-secondary d-md-none animate-opacity collapse" onclick="toggleNav(); clearSearchbox();">
			<i class="fa fa-close"></i>
		</button>

		<div class="mt-3">

			<div class="dropdown mt-3">
				<span class="logout-span pt-1 pb-2 px-2 rounded bg-gradient cursor-pointer dropdown-toggle" type="button" id="dropendMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
					<?php echo $username; ?>
				</span>
				<ul class="dropdown-menu" aria-labelledby="dropendMenuButton">
					<li><a class="dropdown-item" href="my-token?token-only">My Token</a></li>
					<li><a class="dropdown-item" href="my-token">Developer Options</a></li>
					<li><hr class="dropdown-divider"></li>
					<li><a class="dropdown-item" href="login?logout"><i class="fa fa-sign-out fa-rotate-180 me-1"></i> Logout</a></li>
				</ul>
			</div>

		</div>

	</header>

	<section class="table-section pb-4 z-2">

		<!-- Not Completed Tasks -->

		<table id="table_one" class="table table-striped mb-0 bg-light">
			<thead>
				<tr>
					<th>
						S/N
						<br>
						<button class="text-secondary" title="toggle" onclick="sort(this.children[0],'table_not_completed')"><i class="fa fa-arrow-up"></i></button>
					</th>
					<th>
						Title
					</th>
					<th>
						Content
						<button class="text-dark ms-md-2" title="show/hide content" onclick="toggle_showContent(this.children[0], 'table_one')"><i class="fa fa-eye fa-eye-slash rounded-circle"></i></button>
					</th>
					<th class="action-col"><span class="others-show">Completed</span></th>
					<th class="action-col"><span class="others-show">Delete</span></th>
				</tr>
			</thead>
			<tbody id="table_not_completed">
				<?php

					$not_completed_records = $count = 0;

					foreach ($results as $result) {
						
						// Showing only not_completed tasks
						if ($result['isCompleted'] == true) continue;

						$not_completed_records += 1;

				?>
						<tr id="<?php echo $result['_id']; ?>" class="note_body">
							<td class="serial"><?php echo ++$count; ?></td>
							<td><a href="task?action=edit&_id=<?php echo $result['_id']; ?>" class="text-decoration-none"><?php echo $result['task_title']; ?></a></td>
							<td class="note_content"><?php echo $result['task_body']; ?></td>
							<td class="text-center">
								<button class="text-faded" title="mark done" onclick="markDone(this,'<?php echo $result['_id']; ?>')"><i class="fa fa-check"></i></button>
							</td>
							<td class="text-center">
								<button title="delete" onclick="remove(this,'<?php echo $result['_id']; ?>')"><i class="fa fa-trash text-danger"></i></button>
							</td>
						</tr>

				<?php
					}
				?>
				<!--
				<tr id="idofthis" class="note_body">
					<th class="serial">1</th>
					<td><a href="task?action=edit&_id=idofthis">Mark</a></td>
					<td class="note_content">Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptas in, quia tenetur modi culpa natus magni repellat libero rem maxime? Libero consectetur nemo iste ea dignissimos rem unde quidem quas.</td>
					<td class="text-center">
						<button class="text-faded" title="mark done" onclick="markDone(this,'idofthis')"><i class="fa fa-check"></i></button>
					</td>
					<td class="text-center">
						<button title="delete" onclick="remove(this,'idofthis')"><i class="fa fa-trash text-danger"></i></button>
					</td>
				</tr>
				-->
			</tbody>
		</table>


		<?php

			$total_no_of_pages = ceil($not_completed_records / $total_records_per_page);

		?>

		<ul class="pagination mt-3 mb-0">
			<li class="page-item">
				<a class="page-link" href="#" aria-label="Previous">
					<span aria-hidden="true">&laquo;</span>
				</a>
			</li>

			<?php
				$count = 0;
				for ($i=0; $i < $not_completed_records; $i+=($records_length+1)) {
					$count++;
					echo '<li class="page-item"><a class="page-link ';
					echo $i==0 ? 'active' : '';
					echo '" href="#" onclick="page_to(this,\'table_one\','.$i.','.$records_length.')">'.$count.'</a></li>';
				}
			?>

			<!-- Sample
			<li class="page-item"><a class="page-link active" href="#" onclick="page_to(this,'table_one',0,9)">1</a></li>
			<li class="page-item"><a class="page-link" href="#" onclick="page_to(this,'table_one',10,9)">2</a></li>
			<li class="page-item"><a class="page-link" href="#" onclick="page_to(this,'table_one',20,9)">3</a></li>
			-->
			
			<li class="page-item">
				<a class="page-link" href="#" aria-label="Next">
					<span aria-hidden="true">&raquo;</span>
				</a>
			</li>
		</ul>

		<!-- Magic scripts -->
		<script>
			sort('','table_not_completed');
			page_to('','table_one',<?php echo $start_page_at; ?>,<?php echo $records_length; ?>);
		</script>
		<script>
			
			// To show or not to show

			let isTrust_one = get_lightcookie('trust_table_one','production-mode') == "true" ? true : false; // cookies are returned as string that's why.

			if(isTrust_one) {
				$('#table_one .fa-eye').removeClass('fa-eye-slash');
				$('#table_one .note_content').addClass('unblur');
			}

		</script>




		<!-- Add new -->
		<div class="add-new d-flex align-items-center pt-4 mt-1 mb-3 mb-md-2 px-md-3">
			<div class="w-100"><div class="line ms-auto"></div></div>
			<a href="task?action=new" class="btn rounded-circle"><i class="fa fa-plus"></i></a>
			<div class="w-100"><div class="line me-auto"></div></div>
		</div>




		<!-- Completed Tasks -->

		<div id="table_two_lnd" class="pt-5"></div>

		<p class="text-dark fw-bold p-2 d-inline-block" style="background: inherit;">Completed Tasks</p>
		<table id="table_two" class="table table-striped mb-0 bg-light">
			<thead>
				<tr>
					<th>
						S/N
						<br>
						<button class="text-secondary" title="toggle" onclick="sort(this.children[0],'table_completed')"><i class="fa fa-arrow-up"></i></button>
					</th>
					<th>Title</th>
					<th>
						Content
						<button class="text-dark ms-md-2" title="show/hide content" onclick="toggle_showContent(this.children[0], 'table_two')"><i class="fa fa-eye fa-eye-slash rounded-circle"></i></button>
					</th>
					<th class="action-col"><span class="others-show">Completed</span></th>
					<th class="action-col"><span class="others-show">Delete</span></th>
				</tr>
			</thead>
			<tbody id="table_completed">
				<?php

					$completed_records = $count = 0;

					foreach ($results as $result) {

						// Showing only completed tasks
						if ($result['isCompleted'] == false) continue;

						$completed_records += 1;

				?>
						<tr id="<?php echo $result['_id']; ?>" class="note_body completed">
							<td class="serial"><?php echo ++$count; ?></td>
							<td><a href="task?action=edit&_id=<?php echo $result['_id']; ?>" class="text-decoration-none"><?php echo $result['task_title']; ?></a></td>
							<td class="note_content"><?php echo $result['task_body']; ?></td>
							<td class="text-center">
								<button class="text-faded text-success" title="mark undone" onclick="markDone(this,'<?php echo $result['_id']; ?>')"><i class="fa fa-check"></i></button>
							</td>
							<td class="text-center">
								<button title="delete" onclick="remove(this,'<?php echo $result['_id']; ?>')"><i class="fa fa-trash text-danger"></i></button>
							</td>
						</tr>
				<?php
					}
				?>
			</tbody>
		</table>


		<?php

			$total_no_of_pages = ceil($completed_records / $total_records_per_page);

		?>

		<ul class="pagination mt-3 mb-0">
			<li class="page-item">
				<a class="page-link" href="#" aria-label="Previous">
					<span aria-hidden="true">&laquo;</span>
				</a>
			</li>

			<?php
				$count = 0;
				for ($i=0; $i < $completed_records; $i+=($records_length+1)) {
					$count++;
					echo '<li class="page-item"><a class="page-link ';
					echo $i==0 ? 'active' : '';
					echo '" href="#" onclick="page_to(this,\'table_two\','.$i.','.$records_length.')">'.$count.'</a></li>';
				}
			?>
			
			<li class="page-item">
				<a class="page-link" href="#" aria-label="Next">
					<span aria-hidden="true">&raquo;</span>
				</a>
			</li>
		</ul>

		<!-- Magic scripts -->
		<script>
			sort('','table_completed');
			page_to('','table_two',<?php echo $start_page_at; ?>,<?php echo $records_length; ?>);
		</script>
		<script>
			
			// To show or not to show
			
			let isTrust_two = get_lightcookie('trust_table_two','production-mode') == "true" ? true : false; // cookies are returned as string that's why.

			if(isTrust_two) {
				$('#table_two .fa-eye').removeClass('fa-eye-slash');
				$('#table_two .note_content').addClass('unblur');
			}

		</script>





		<!-- Some Function Definitions -->

		<script>

			const table_not_completed = document.getElementById('table_not_completed'),
				  table_completed = document.getElementById('table_completed');


			function markDone(elem, _id) {

				const note_body = document.getElementById(_id),
					  task_table_id = note_body.parentElement.id,
					  serial = note_body.getElementsByClassName('serial')[0].innerText;

				elem.classList.toggle('text-success');
				setTimeout(function(){ note_body.classList.add('muted'); },700);

				let inTableCompleted = task_table_id == "table_completed";


				$.get("task",{
					_id: _id,
					action: inTableCompleted ? 'markundone' : 'markdone',
					isAjax: true
				}, function(result){
					
					note_body.remove();	

					// Subtracts 1 from all $(#task_table_id .serial).text();
					// where #task_table_id is an alias for #table_not_completed and #table_completed respectively.
					subtract_one(task_table_id, serial);


					// Move note_body to either table_completed or table_not_completed

					const pack = note_body;

					setTimeout(function(){ pack.classList.remove('muted'); },800);

					if (inTableCompleted) {
						// update pack, and append to table_not_completed
						update_and_append(pack,table_not_completed);
						note_body.classList.remove('completed');
					}
					else {
						// update pack, and append to table_completed
						update_and_append(pack,table_completed);						
						note_body.classList.add('completed');
					}

				});
			
			}



			function remove(elem, _id) {

				const note_body = document.getElementById(_id),
					  task_table_id = note_body.parentElement.id,
					  serial = note_body.getElementsByClassName('serial')[0].innerText;

				let confirmed = note_body.classList.contains('completed') ? true : confirm('This task is not yet done! Sure to delete it?');

				if(confirmed) {
					
					note_body.classList.add('muted');

					// /task/1/delete
					$.get("task",{
						_id: _id,
						action: 'delete',
						isAjax: true
					}, function(result){
						note_body.remove();
						subtract_one(task_table_id, serial);
					});

				}

			}



			function subtract_one(e,_num) {
				$('#'+e+' .serial').each(function(i,x) {
					let n = +$(x).text();
					if(n>_num){ $(x).text(n - 1) }
				});
			}


			function update_and_append(pack,e) {
				// Update pack's serial number
				pack.getElementsByClassName('serial')[0].innerText = + (e.children.length>0 && (e.children[e.children.length-1].getElementsByClassName('serial')[0].innerText) || 0) + 1;
				// Append everything to e (i.e table_completed or table_not_completed respectively)
				e.appendChild(pack);
			}

		</script>



		<div class="mt-4 mb-3 pt-4 text-center">
			<a href="login?logout" class="btn btn-outline-danger bg-transparent text-light border-danger text-decoration-none fw-bold" style="letter-spacing: .5px;"><i class="fa fa-sign-out fa-rotate-180 me-1"></i> Logout</a>
		</div>

	</section>


	<?php include "footer.php"; ?>


</body>
</html>