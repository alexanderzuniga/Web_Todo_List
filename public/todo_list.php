<!DOCTYPE html>
<html>
<head>
<?php 
	function save_file($data_to_save, $filename = 'data/list.txt')
	{
    	$handle = fopen($filename, 'w');
    	$contents = implode("\n", $data_to_save);
    	fwrite($handle, $contents);
    	fclose($handle);
	}
	///////
	function import($filename = 'data/list.txt'){
		if (is_readable($filename) && (filesize($filename) > 0)) {
			    $handle = fopen($filename, "r");
			    $contents = fread($handle, filesize($filename));
			    $file_array = explode("\n", $contents);
			    fclose($handle);
			    return $file_array;
			}
		}
///////////	
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

			if (!empty($_POST['new_item'])) {
				$TODO_list[] = $_POST['new_item'];
				save_file($TODO_list);
			}

			foreach ($TODO_list as $key => $item) {
				echo "<ul><li>$item<br></li></ul>";
			}
			?>
			</p>
		<form method = "POST"> 
		<p>
    		<label> New Item: <input id="new_item" name="new_item" type="text" placeholder="New Item Here" ></label>
    	</p>
		<button type="submit"> Enter </button>
		</form>


</body>
</html>