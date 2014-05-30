<!DOCTYPE html>
<html>
<head>
	<?php 
//Import a file
		function import($filename = 'data/list.txt'){
			$items = [];
			if (is_readable($filename) && (filesize($filename) > 0)) {
				$handle = fopen($filename, "r");
				$contents = fread($handle, filesize($filename));
				$file_array = explode("\n", $contents);
				fclose($handle);
				return $file_array;
			}
			return $items;
		}	
//Saving the File
	function save_file($data_to_save, $filename = 'data/list.txt')
	{
		$handle = fopen($filename, 'w');
		$contents = implode("\n", $data_to_save);
		fwrite($handle, $contents);
		fclose($handle);
	}

///
	var_dump($_GET);
	var_dump($_POST);
	?>
	<title>TODO List</title>
</head>
<body>
	<h1>TODO List</h1>
	<p>
		<?php

		$TODO_list = import();
	//Saving to File	
		if (!empty($_POST['new_item'])) {
					$TODO_list[] = $_POST['new_item'];
					save_file($TODO_list);
					header('Location: /todo_list.php');	
					exit;
				}
	//Removing items
		if (isset($_GET['removeIndex'])) {
			unset($TODO_list[$_GET['removeIndex']]);
			save_file($TODO_list);
			header('Location: /todo_list.php');	
			exit;	
		} 
	//List out todos	
		foreach ($TODO_list as $key => $item) {
			echo "<ul>
			<li>$item <a href= \"todo_list.php?removeIndex={$key}\">Remove Item</a><br></li>
			</ul>";
		}
		?>
	</p>
	<form method = "POST" action="/todo_list.php"> 
		<p>
			<label> New Item: <input id="new_item" name="new_item" type="text" placeholder="New Item Here" autofocus></label>
		</p>
		<button type="submit"> Enter </button>
	</form>
</body>
</html>