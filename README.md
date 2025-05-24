
#### Using API - Developer Mode

All tools are already provided in the main site, but in case you want to take the fun to the next level:

#### Samples

> 1. Fetch all tasks:
>
>    > json_output = "http://main-site/?token=your_token"
>
>    
>
>
> 2. Fetch a particular task (NOTE: _\_id_ << Note the underscore):
>
>    > json_output = "http://main-site/task?token=your_token&_id=the__id_of_the_note"
>
>    
>
>
> 3. Create a new task (NOTE: Uses post method only):
>
>    > string_output = "http://main-site/task?token=your_token"
>
>    Then you send your values via your preferred language's POST method of sending values. Example:
>
>    PHP:
>
>    ```php
>    curl_setopt($ch, CURLOPT_POSTFIELDS, array('create'=>true, 'task_title'=>'New Title', 'task_body'=>'Hello world!'));
>    ```
>
>    OR
>
>    ```php
>    curl_setopt($ch, CURLOPT_POSTFIELDS, "create&task_title=New%20Title&task_body=Hello%20world!");
>    ```
>
>    Feel free to use any language or any way you know (even AJAX!).
>
>    **_NOTE Again: Create and Edit use post method only._**
>
>    
>
>
> 4. Edit a task (NOTE: Uses post method only):
>
>    > string_output = "http://main-site/task?token=your_token"
>
>    Then you send your values via your preferred language's POST method of sending values. Example:
>
>    PHP:
>
>    ```php
>    curl_setopt($ch, CURLOPT_POSTFIELDS, array('edit'=>true, '__id'=>'the__id_of_the_note', 'task_title'=>'New Title', 'task_body'=>'Hello world!'));
>    ```
>
>    _OR_
>
>    ```php
>    curl_setopt($ch, CURLOPT_POSTFIELDS, "edit&__id=the__id_of_the_note&task_title=New%20Title&task_body=Hello%20world!");
>    ```
>
>    **BIG TIP:**
>
>    You can even omit either of the value of task_title or task_body but represent with empty string '' instead:
>
>    ```php
>    curl_setopt($ch, CURLOPT_POSTFIELDS, array('edit'=>true, '__id'=>'the__id_of_the_note', 'task_title'=>'', 'task_body'=>'Hello world! Only task body will get edited. Cool!'));
>    ```
>
>    Feel free to use any language or any way you know (even AJAX!).
>
>    **_NOTE Again: Create and Edit use post method only._**
>
>    
>
>
> 5. Mark a task done (if you are through with a task but don't want to delete it yet, just markdone for self-indication):
>
>    > string_output = "http://main-site/task?token=your_token&action=markdone&__id=the__id_of_the_note"
>
>    
>
>
> 6. Mark a task undone (reverse of above):
>
>    > string_output = "http://main-site/task?token=your_token&action=markundone&__id=the__id_of_the_note"
>
>    
>
>
> 7. Delete a task:
>
>    > string_output = "http://main-site/task?token=your_token&action=delete&__id=the__id_of_the_note"
>
>    
>
>
> 8. Delete all tasks:
>
>    > string_output = "http://main-site/task?token=your_token&action=delete_all&confirm=true"




Your token will be generated at sign up. It belongs specifically to you and you should keep it safe. 

You can only sign up and get your token at the main site:
And after that, you are free to do with the token any of the above commands without ever having to visit the main site ever again. This means that you can even design your own website/webapp/app completely and use the APIs from there, the freedom is fully yours!

In case you lose your token, you can get it again when you login on the main site with your username. Just never forget your password because you cannot get that one back.

**NOTE: Your token is both personal and private and should not be exposed or even input into a browser's URL bar! Do not go developer mode if you are not familiar with the concept of using APIs!**


**TIP**: Feel free to use **_AJAX, IFRAME, cURL (highly recommended), or any other API procedure you know_**, as long as you keep your token private.


**NOTE: Everything on this page are for developers only. It's a way you can use the Task List by RSTLabs service without even visiting the website. Pay no attention to the content if not a developer, as everything you may need is all provided in the website for absolutely free, for absolutely everyone.**
