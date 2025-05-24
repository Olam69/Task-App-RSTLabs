<?php

	$ch = curl_init("http://localhost/BetterDev_Challenge/01/?token=69cf4ce4b64dd3ab7b99f2");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HEADER, false);
	$result_obj = curl_exec($ch);
	curl_close($ch);



	$results = json_decode($result_obj);
							// If you set the second argument to true, you can use as $result['task_title'] instead of $result->task_title; (i.e associative array if you are more comfortable with it instead of object notation)


	// Output an HTML table
	echo '<table>';
	echo '<tr><th>S/N</th><th>Title</th><th>Body</th><th>isCompleted</th><th>_ID</th></tr>';

	foreach ($results as $i => $result) {
	    echo '<tr>';
	    echo '<td>' . ($i+1) . '</td>';
        echo '<td>' . $result->task_title . '</td>';
        echo '<td>' . $result->task_body . '</td>';
        echo '<td>' . $result->isCompleted . '</td>';
        echo '<td>' . $result->_id . '</td>';
        echo '</tr>';
	}

	echo '</table>';



	/* API Samples */
	/*
		You would ideally not need to go developer mode as everything you would need is provided at our main website. But in case you want to take the fun to the next level:

		1. 	Fetch all tasks:
			json_output = "http://localhost/BetterDev_Challenge/01/?token=69cf4ce4b64dd3ab7b99f2"

		2.	Fetch a particular task (NOTE: _id):
			json_output = "http://localhost/BetterDev_Challenge/01/task?token=69cf4ce4b64dd3ab7b99f2&_id=64e1e93d9d687"

		3.	Create a new task (NOTE: Uses post method only):
			string_output = "http://localhost/BetterDev_Challenge/01/task?token=69cf4ce4b64dd3ab7b99f2"
			Then you send your values via your preferred language's POST method of sending values. Example:
			PHP:
				curl_setopt($ch, CURLOPT_POSTFIELDS, array('create'=>true, 'task_title'=>'New Title', 'task_body'=>'Hello world!'));
				OR
				curl_setopt($ch, CURLOPT_POSTFIELDS, "create&task_title=New%20Title&task_body=Hello%20world!");

			Feel free to use any language or any way you know (even AJAX!).
			NOTE Again: Create and Edit use post method only.

		4.	Edit a task (NOTE: Uses post method only):
			string_output = "http://localhost/BetterDev_Challenge/01/task?token=69cf4ce4b64dd3ab7b99f2"
			Then you send your values via your preferred language's POST method of sending values. Example:
			PHP:
				curl_setopt($ch, CURLOPT_POSTFIELDS, array('edit'=>true, '_id'=>'64e1e93d9d687', 'task_title'=>'New Title', 'task_body'=>'Hello world!'));
				OR
				curl_setopt($ch, CURLOPT_POSTFIELDS, "edit&_id=64e1e93d9d687&task_title=New%20Title&task_body=Hello%20world!");
				
				BIG TIP:
				You can even omit either of the value of task_title or task_body but represent with empty string '' instead:
				curl_setopt($ch, CURLOPT_POSTFIELDS, array('edit'=>true, '_id'=>'64e1e93d9d687', 'task_title'=>'', 'task_body'=>'Hello world! Only task body will get edited. Cool!'));

			Feel free to use any language or any way you know (even AJAX!).
			NOTE Again: Create and Edit use post method only.

		5.	Mark a task done (if you are through with a task but don't want to delete it yet, just markdone for self-indication):
			string_output = "http://localhost/BetterDev_Challenge/01/task?token=69cf4ce4b64dd3ab7b99f2&action=markdone&_id=64e1e93d9d687"

		6.	Mark a task undone (reverse of above):
			string_output = "http://localhost/BetterDev_Challenge/01/task?token=69cf4ce4b64dd3ab7b99f2&action=markundone&_id=64e1e93d9d687"

		7.	Delete a task:
			string_output = "http://localhost/BetterDev_Challenge/01/task?token=69cf4ce4b64dd3ab7b99f2&action=delete&_id=64e1e93d9d687"

		8.	Delete all tasks:
			string_output = "http://localhost/BetterDev_Challenge/01/task?token=69cf4ce4b64dd3ab7b99f2&action=delete_all&confirm=true"

		
		Your token will be generated at sign up. It belongs specifically to you and you should keep it safe. 

		You can only sign up and get your token at the main site:
		And after that, you are free to do with the token any of the above commands without ever having to visit the main site ever again. This means that you can even design your own app completely and use the APIs from there, the freedom is fully yours!

		In case you lose your token, you can get it again when you login on the main site with your username. Just never forget your password because you cannot get that one back.

		**NOTE: Your token is both personal and private and should not be exposed or even input into a browser's URL bar! Do not go developer mode if you are not familiar with the concept of using APIs!**

	*/


?>
