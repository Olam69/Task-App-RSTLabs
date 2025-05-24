<?php

	session_start();

	if (!isset($_SESSION['token'])) {
		header('location: login');
		exit(); // Blocks intrusion. Leave line as is.
	}

	$token = $_SESSION['token'];

	if (strlen($token) < 10) header('location: login?logout');


	$username = $_SESSION['username'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
	
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="How to use the Task manager webapp API to manage your tasks the way you see fit.">

	<title>Welcome | Task List | RSTLabs</title>

	<link rel="icon" href="assets/images/favicon.ico">
	<link rel="stylesheet" href="vendor/css/bootstrap/bootstrap.min.css">
	<link rel="stylesheet" href="vendor/css/fontawesome.min.css">
	<link rel="stylesheet" href="vendor/css/rstlib/components.rstlib.css">

	<link rel="stylesheet" href="assets/css/styles.css">
	<link rel="stylesheet" href="assets/css/readme.css">

	<script src="vendor/js/jquery/jquery.min.js"></script>
	<script src="vendor/js/rstlib/search.rstlib.js"></script>
	<script src="vendor/js/rstlib/lightcookie.rstlib.js"></script>

	<script src="assets/js/scripts.js"></script>


	<style>

		section {
			background: rgba(255, 255, 255, .9);
			backdrop-filter: blur(30px);
		}
		@media (max-width: 1023px) {
			section[class*=rounded] {
				border-radius: 0!important;
			}
		}

		section .optional-show {
			<?php if (isset($_GET['token-only'])) echo "display: none"; ?>
		}

		section #write ol a {
			cursor: initial;
		}

		section hr {
			border-color: #777;
		}

	</style>

</head>
<body class="position-relative p-md-4 p-lg-5 typora-export os-windows">

	<img src="assets/images/setup.jpg" class="body-overlay-img misc-middle position-fixed z-1">

	<header class="px-2 px-md-3 px-lg-0 py-4 position-relative z-2">

		<h1 class="title fs-2 text-capitalize m-0">Welcome</h1>

		<a href="my-tasks" class="logout-span d-inline-block mt-3 pt-1 pb-2 px-2 rounded bg-gradient cursor-pointer">
			<i class="fa fa-arrow-left"></i> <?php echo $username; ?>
		</a>

	</header>


	<section class="markdown-export-content p-4 py-lg-5 z-2 rounded-top">
		
		<div class="text-end newspaper pb-1">Your API Token:<br><code><?php echo $token; ?></code></div>

		<hr class="optional-show">

		<div id='write' class="pt-3 optional-show"><h4 id='using-api---developer-mode' class="mt-0"><span>Using API - Developer Mode</span></h4><p><span>All tools are already provided in the main site, but in case you want to take the fun to the next level:</span></p><p><span style="font-size: small;"><strong>TIP</strong>: Feel free to use <strong>AJAX, IFRAME, cURL (highly recommended), or any other API procedure you know</strong>, as long as you keep your token private.</span></p><h4 id='samples' class="mt-0"><span>Samples</span></h4><ol start='' ><li><p><span>Fetch all tasks:</span></p><blockquote><p>&quot;<a target='_blank' class='url'>http://localhost/BetterDev_Challenge/01/?token=your_token</a><span>&quot;</span><br><span>- json_output</span></p></blockquote><p>&nbsp;</p></li><li><p><span>Fetch a particular task (NOTE: </span><em><span>_</span><span>id</span></em><span> &laquo; Note the underscore):</span></p><blockquote><p>&quot;<a target='_blank' class='url'>http://localhost/BetterDev_Challenge/01/task?token=your_token&_id=the__id_of_the_note</a><span>&quot;</span><br><span>- json_output</span></p></blockquote><p>&nbsp;</p></li><li><p><span>Create a new task (NOTE: Uses post method only):</span></p><blockquote><p>&quot;<a target='_blank' class='url'>http://localhost/BetterDev_Challenge/01/task?token=your_token</a><span>&quot;</span><br><span>- string_output</span></p></blockquote><p><span>Then you send your values via your preferred language&#39;s POST method of sending values. Example:</span></p><p><span>PHP:</span></p><pre class="md-fences md-end-block ty-contain-cm modeLoaded md-focus" spellcheck="false" lang="php"><div class="CodeMirror cm-s-inner cm-s-null-scroll CodeMirror-wrap CodeMirror-focused" lang="php"><div style="overflow: hidden; position: relative; width: 3px; height: 0px; top: 10.168px; left: 4px;"><textarea autocorrect="off" autocapitalize="off" spellcheck="false" tabindex="0" style="position: absolute; bottom: -1em; padding: 0px; width: 1000px; height: 1em; outline: none;"></textarea></div><div class="CodeMirror-scrollbar-filler" cm-not-content="true"></div><div class="CodeMirror-gutter-filler" cm-not-content="true"></div><div class="CodeMirror-scroll" tabindex="-1"><div class="CodeMirror-sizer" style="margin-left: 0px; margin-bottom: 0px; border-right-width: 0px; padding-right: 0px; padding-bottom: 0px;"><div style="position: relative; top: 0px;"><div class="CodeMirror-lines" role="presentation"><div role="presentation" style="position: relative; outline: none;"><div class="CodeMirror-measure"><pre><span>xxxxxxxxxx</span></pre></div><div class="CodeMirror-measure"></div><div style="position: relative; z-index: 1;"></div><div class="CodeMirror-code" role="presentation"><div class="CodeMirror-activeline" style="position: relative;"><div class="CodeMirror-activeline-background CodeMirror-linebackground"></div><div class="CodeMirror-gutter-background CodeMirror-activeline-gutter" style="left: 0px; width: 0px;"></div><pre class=" CodeMirror-line " role="presentation"><span role="presentation" style="padding-right: 0.1px;"><span class="cm-builtin">curl_setopt</span>(<span class="cm-variable-2">$ch</span>, <span class="cm-variable">CURLOPT_POSTFIELDS</span>, <span class="cm-keyword">array</span>(<span class="cm-string">'create'</span><span class="cm-operator">=&gt;</span><span class="cm-atom">true</span>, <span class="cm-string">'task_title'</span><span class="cm-operator">=&gt;</span><span class="cm-string">'New Title'</span>, <span class="cm-string">'task_body'</span><span class="cm-operator">=&gt;</span><span class="cm-string">'Hello world!'</span>));</span></pre></div></div></div></div></div></div><div style="position: absolute; height: 0px; width: 1px; border-bottom: 0px solid transparent; top: 74px;"></div><div class="CodeMirror-gutters" style="display: none; height: 74px;"></div></div></div></pre><p><span>OR</span></p><pre class="md-fences md-end-block ty-contain-cm modeLoaded" spellcheck="false" lang="php"><div class="CodeMirror cm-s-inner cm-s-null-scroll CodeMirror-wrap" lang="php"><div style="overflow: hidden; position: relative; width: 3px; height: 0px; top: 10.168px; left: 4px;"><textarea autocorrect="off" autocapitalize="off" spellcheck="false" tabindex="0" style="position: absolute; bottom: -1em; padding: 0px; width: 1000px; height: 1em; outline: none;"></textarea></div><div class="CodeMirror-scrollbar-filler" cm-not-content="true"></div><div class="CodeMirror-gutter-filler" cm-not-content="true"></div><div class="CodeMirror-scroll" tabindex="-1"><div class="CodeMirror-sizer" style="margin-left: 0px; margin-bottom: 0px; border-right-width: 0px; padding-right: 0px; padding-bottom: 0px;"><div style="position: relative; top: 0px;"><div class="CodeMirror-lines" role="presentation"><div role="presentation" style="position: relative; outline: none;"><div class="CodeMirror-measure"><pre><span>xxxxxxxxxx</span></pre></div><div class="CodeMirror-measure"></div><div style="position: relative; z-index: 1;"></div><div class="CodeMirror-code" role="presentation"><div class="CodeMirror-activeline" style="position: relative;"><div class="CodeMirror-activeline-background CodeMirror-linebackground"></div><div class="CodeMirror-gutter-background CodeMirror-activeline-gutter" style="left: 0px; width: 0px;"></div><pre class=" CodeMirror-line " role="presentation"><span role="presentation" style="padding-right: 0.1px;"><span class="cm-builtin">curl_setopt</span>(<span class="cm-variable-2">$ch</span>, <span class="cm-variable">CURLOPT_POSTFIELDS</span>, <span class="cm-string">"create&amp;task_title=New%20Title&amp;task_body=Hello%20world!"</span>);</span></pre></div></div></div></div></div></div><div style="position: absolute; height: 0px; width: 1px; border-bottom: 0px solid transparent; top: 74px;"></div><div class="CodeMirror-gutters" style="display: none; height: 74px;"></div></div></div></pre><p><span>Feel free to use any language or any way you know (even AJAX!).</span></p><p><strong><em><span>NOTE Again: Create and Edit use post method only.</span></em></strong></p><p>&nbsp;</p></li><li><p><span>Edit a task (NOTE: Uses post method only):</span></p><blockquote><p>&quot;<a target='_blank' class='url'>http://localhost/BetterDev_Challenge/01/task?token=your_token</a><span>&quot;</span><br><span>- string_output</span></p></blockquote><p><span>Then you send your values via your preferred language&#39;s POST method of sending values. Example:</span></p><p><span>PHP:</span></p><pre class="md-fences md-end-block ty-contain-cm modeLoaded" spellcheck="false" lang="php"><div class="CodeMirror cm-s-inner cm-s-null-scroll CodeMirror-wrap" lang="php"><div style="overflow: hidden; position: relative; width: 3px; height: 0px; top: 10.168px; left: 4px;"><textarea autocorrect="off" autocapitalize="off" spellcheck="false" tabindex="0" style="position: absolute; bottom: -1em; padding: 0px; width: 1000px; height: 1em; outline: none;"></textarea></div><div class="CodeMirror-scrollbar-filler" cm-not-content="true"></div><div class="CodeMirror-gutter-filler" cm-not-content="true"></div><div class="CodeMirror-scroll" tabindex="-1"><div class="CodeMirror-sizer" style="margin-left: 0px; margin-bottom: 0px; border-right-width: 0px; padding-right: 0px; padding-bottom: 0px;"><div style="position: relative; top: 0px;"><div class="CodeMirror-lines" role="presentation"><div role="presentation" style="position: relative; outline: none;"><div class="CodeMirror-measure"><pre><span>xxxxxxxxxx</span></pre></div><div class="CodeMirror-measure"></div><div style="position: relative; z-index: 1;"></div><div class="CodeMirror-code" role="presentation"><div class="CodeMirror-activeline" style="position: relative;"><div class="CodeMirror-activeline-background CodeMirror-linebackground"></div><div class="CodeMirror-gutter-background CodeMirror-activeline-gutter" style="left: 0px; width: 0px;"></div><pre class=" CodeMirror-line " role="presentation"><span role="presentation" style="padding-right: 0.1px;"><span class="cm-builtin">curl_setopt</span>(<span class="cm-variable-2">$ch</span>, <span class="cm-variable">CURLOPT_POSTFIELDS</span>, <span class="cm-keyword">array</span>(<span class="cm-string">'edit'</span><span class="cm-operator">=&gt;</span><span class="cm-atom">true</span>, <span class="cm-string">'_id'</span><span class="cm-operator">=&gt;</span><span class="cm-string">'the__id_of_the_note'</span>, <span class="cm-string">'task_title'</span><span class="cm-operator">=&gt;</span><span class="cm-string">'New Title'</span>, <span class="cm-string">'task_body'</span><span class="cm-operator">=&gt;</span><span class="cm-string">'Hello world!'</span>));</span></pre></div></div></div></div></div></div><div style="position: absolute; height: 0px; width: 1px; border-bottom: 0px solid transparent; top: 99px;"></div><div class="CodeMirror-gutters" style="display: none; height: 99px;"></div></div></div></pre><p><em><span>OR</span></em></p><pre class="md-fences md-end-block ty-contain-cm modeLoaded" spellcheck="false" lang="php"><div class="CodeMirror cm-s-inner cm-s-null-scroll CodeMirror-wrap" lang="php"><div style="overflow: hidden; position: relative; width: 3px; height: 0px; top: 10.168px; left: 4px;"><textarea autocorrect="off" autocapitalize="off" spellcheck="false" tabindex="0" style="position: absolute; bottom: -1em; padding: 0px; width: 1000px; height: 1em; outline: none;"></textarea></div><div class="CodeMirror-scrollbar-filler" cm-not-content="true"></div><div class="CodeMirror-gutter-filler" cm-not-content="true"></div><div class="CodeMirror-scroll" tabindex="-1"><div class="CodeMirror-sizer" style="margin-left: 0px; margin-bottom: 0px; border-right-width: 0px; padding-right: 0px; padding-bottom: 0px;"><div style="position: relative; top: 0px;"><div class="CodeMirror-lines" role="presentation"><div role="presentation" style="position: relative; outline: none;"><div class="CodeMirror-measure"><pre><span>xxxxxxxxxx</span></pre></div><div class="CodeMirror-measure"></div><div style="position: relative; z-index: 1;"></div><div class="CodeMirror-code" role="presentation"><div class="CodeMirror-activeline" style="position: relative;"><div class="CodeMirror-activeline-background CodeMirror-linebackground"></div><div class="CodeMirror-gutter-background CodeMirror-activeline-gutter" style="left: 0px; width: 0px;"></div><pre class=" CodeMirror-line " role="presentation"><span role="presentation" style="padding-right: 0.1px;"><span class="cm-builtin">curl_setopt</span>(<span class="cm-variable-2">$ch</span>, <span class="cm-variable">CURLOPT_POSTFIELDS</span>, <span class="cm-string">"edit&amp;_id=the__id_of_the_note&amp;task_title=New%20Title&amp;task_body=Hello%20world!"</span>);</span></pre></div></div></div></div></div></div><div style="position: absolute; height: 0px; width: 1px; border-bottom: 0px solid transparent; top: 74px;"></div><div class="CodeMirror-gutters" style="display: none; height: 74px;"></div></div></div></pre><p><strong><span>BIG TIP:</span></strong></p><p><span>You can even omit either of the value of task_title or task_body but represent with empty string &#39;&#39; instead:</span></p><pre class="md-fences md-end-block ty-contain-cm modeLoaded" spellcheck="false" lang="php"><div class="CodeMirror cm-s-inner cm-s-null-scroll CodeMirror-wrap" lang="php"><div style="overflow: hidden; position: relative; width: 3px; height: 0px; top: 10.168px; left: 4px;"><textarea autocorrect="off" autocapitalize="off" spellcheck="false" tabindex="0" style="position: absolute; bottom: -1em; padding: 0px; width: 1000px; height: 1em; outline: none;"></textarea></div><div class="CodeMirror-scrollbar-filler" cm-not-content="true"></div><div class="CodeMirror-gutter-filler" cm-not-content="true"></div><div class="CodeMirror-scroll" tabindex="-1"><div class="CodeMirror-sizer" style="margin-left: 0px; margin-bottom: 0px; border-right-width: 0px; padding-right: 0px; padding-bottom: 0px;"><div style="position: relative; top: 0px;"><div class="CodeMirror-lines" role="presentation"><div role="presentation" style="position: relative; outline: none;"><div class="CodeMirror-measure"><pre><span>xxxxxxxxxx</span></pre></div><div class="CodeMirror-measure"></div><div style="position: relative; z-index: 1;"></div><div class="CodeMirror-code" role="presentation"><div class="CodeMirror-activeline" style="position: relative;"><div class="CodeMirror-activeline-background CodeMirror-linebackground"></div><div class="CodeMirror-gutter-background CodeMirror-activeline-gutter" style="left: 0px; width: 0px;"></div><pre class=" CodeMirror-line " role="presentation"><span role="presentation" style="padding-right: 0.1px;"><span class="cm-builtin">curl_setopt</span>(<span class="cm-variable-2">$ch</span>, <span class="cm-variable">CURLOPT_POSTFIELDS</span>, <span class="cm-keyword">array</span>(<span class="cm-string">'edit'</span><span class="cm-operator">=&gt;</span><span class="cm-atom">true</span>, <span class="cm-string">'_id'</span><span class="cm-operator">=&gt;</span><span class="cm-string">'the__id_of_the_note'</span>, <span class="cm-string">'task_title'</span><span class="cm-operator">=&gt;</span><span class="cm-string">''</span>, <span class="cm-string">'task_body'</span><span class="cm-operator">=&gt;</span><span class="cm-string">'Hello world! Only task body will get edited. Cool!'</span>));</span></pre></div></div></div></div></div></div><div style="position: absolute; height: 0px; width: 1px; border-bottom: 0px solid transparent; top: 99px;"></div><div class="CodeMirror-gutters" style="display: none; height: 99px;"></div></div></div></pre><p><span>Feel free to use any language or any way you know (even AJAX!).</span></p><p><strong><em><span>NOTE Again: Create and Edit use post method only.</span></em></strong></p><p>&nbsp;</p></li><li><p><span>Mark a task done:</span></p><blockquote><p>&quot;<a target='_blank' class='url'>http://localhost/BetterDev_Challenge/01/task?token=your_token&action=markdone&_id=the__id_of_the_note</a><span>&quot;</span><br><span>- string_output</span></p></blockquote><p>&nbsp;</p></li><li><p><span>Mark a task undone:</span></p><blockquote><p>&quot;<a target='_blank' class='url'>http://localhost/BetterDev_Challenge/01/task?token=your_token&action=markundone&_id=the__id_of_the_note</a><span>&quot;</span><br><span>- string_output</span></p></blockquote><p>&nbsp;</p></li><li><p><span>Delete a task:</span></p><blockquote><p>&quot;<a target='_blank' class='url'>http://localhost/BetterDev_Challenge/01/task?token=your_token&action=delete&_id=the__id_of_the_note</a><span>&quot;</span><br><span>- string_output</span></p></blockquote><p>&nbsp;</p></li><li><p><span>Delete all tasks:</span></p><blockquote><p>&quot;<a target='_blank' class='url'>http://localhost/BetterDev_Challenge/01/task?token=your_token&action=delete_all&confirm=true</a><span>&quot;</span><br><span>- string_output</span></p></blockquote></li></ol><p class="pt-4"><span>Your token will be generated at sign up. It belongs specifically to you and you should keep it safe. </span></p><p><span>You can only sign up and get your token at the main site:</span>
		<p class="my-0">And after that, you are free to do with the token any of the above commands without ever having to visit the main site ever again. This means that you can even design your own website/webapp/app completely and use the APIs from there, the freedom is fully yours!</p>
		</p><p><span>In case you lose your token, you can get it again when you login on the main site with your username. Just never forget your password because you cannot get that one back.</span></p><p><strong><span>NOTE: Your token is both personal and private and should not be exposed or even input into a browser&#39;s URL bar! Do not go developer mode if you are not familiar with the concept of using APIs!</span></strong></p>

		<p><span style="font-size: small;"><strong>TIP</strong>: Feel free to use <strong>AJAX, IFRAME, cURL (highly recommended), or any other API procedure you know</strong>, as long as you keep your token private.</span></p>

		<p class="pt-4"><marquee><strong>NOTE:</strong> Everything on this page is for developer mode only. It's a way you can use the "Task List by RSTLabs" services without even visiting the website. Pay no attention to the content if not a developer, as everything you may need is all provided in the website for absolutely free, for absolutely everyone.</marquee></p>

	</div>

	</section>

	<script>
		
		$("#write a").removeAttr('href');

		$("#write a").click(function(e) {
			alert("Do not attempt to paste any command link into a browser's URL bar as these links contain a sensitive data: your token. Instead, you should use more subtle methods such as AJAX, IFRAME (caution), or more secure methods such as cURL, etc for even more privacy over your token.\n\nKeep your token private at all times!");
			e.preventDefault();
		});

	</script>


	<?php include "footer.php"; ?>


</body>
</html>