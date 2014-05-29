<!DOCTYPE html>
<html>
<?php 
	
	var_dump($_GET);
	var_dump($_POST);
?>
<head>
	<title>TODO List</title>
</head>
<body>
		<h1>TODO List</h1>
			<?php
			
			$TODO_list = ['Take out the paper', 'Take out Trash', 'Get no spending cash'];

			foreach($TODO_list as $key => $todo) {
				echo  "<ul><li>$todo<br></li></ul>";
			}
			// <ul>
			// 	<li>Take out the paper</li>
			// 	<li>Take out the trash</li>
			// 	<li>Get no spending cash</li>
			// </ul>

			?>
		<form method="POST">
		<p>
		<label name="list_input"> Items: </label>
    	<input id="list_input" name="list_input" type="text" placeholder="Place Items Here"></label>
    	</p>
    	<p>
    		<input type="submit" value="Add to List"></input>
		</form>
</body>
</html>