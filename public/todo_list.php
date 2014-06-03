<?
var_dump($_POST);
var_dump($_GET);
$filename = 'data/list.txt';
$TODO_list = import($filename);
$errorMessage = '';
//Import a file
function import($filename){
	$items = [];
	if (is_readable($filename) && (filesize($filename) > 0)) {
		$handle = fopen($filename, "r");
		$contents = trim(fread($handle, filesize($filename)));
		$file_array = explode("\n", $contents);
		fclose($handle);
		return $file_array;
	}
	return $items;
}	
//Saving the File
function save_file($data_to_save, $array){
	$filename = $data_to_save; 
	if (is_writable($data_to_save)) {
		$handle = fopen($filename, 'w');
		foreach($array as $items){
			fwrite($handle, PHP_EOL . $items);
		}
		fclose($handle);
	}
}
	//Removing items
if (isset($_GET['removeIndex'])) {
	$removeIndex = $_GET['removeIndex'];
	unset($TODO_list[$removeIndex]);
	save_file($filename, $TODO_list);
	header('Location: /todo_list.php');
} 
	// Adding items 
if (!empty($_POST['new_item'])) {
	array_push($TODO_list, $_POST['new_item']);
	save_file($filename, $TODO_list);
	header('Location: /todo_list.php');
}
	//uploading with no errors
if (count($_FILES) > 0 && $_FILES['file1']['type'] != "text/plain"){   //File must be text
	$errorMessage = "Error! File must be plain text. \n";
		echo $errorMessage;
	} else if  (count($_FILES) > 0 && $_FILES['file1']['error'] == 0) {
		$Appending_array = ($_FILES);
		$upload_dir = '/vagrant/sites/todo.dev/public/uploads/';
		$filename = basename($_FILES['file1']['name']);
		$saved_filename = $upload_dir . $filename;
		move_uploaded_file($_FILES['file1']['tmp_name'], $saved_filename);

			$testFile = $saved_filename;
			$newfile= import($testFile);
			$TODO_list= array_merge($TODO_list, $newfile);
			save_file($TODO_list, $filename);
	} 
?>
<!-- INPUT NEW ITEMS -->
<!DOCTYPE html>
<html>
<head> </head>
<body>
<hr>
<h2>My "to-do" List</h2>
<? if (!empty($errorMessage)) : ?>
<?= '<p>{$errorMessage}</p>'; ?>
<? endif; ?>
<ul>
<? foreach ($TODO_list as $key => $item) : ?>
<li> 
	<?=htmlspecialchars(strip_tags($item));?> 
	<a href="todo_list.php?removeIndex=<?=$key?>">Remove Item</a><br>
</li>
<? endforeach; ?>
</ul>
	
</p>
<form method = "POST" action="/todo_list.php"> 
	<p>
		<label> New Item: <input id="new_item" name="new_item" type="text" placeholder="New Item Here" autofocus></label>
	</p>
	<button type="submit"> Enter </button>
</form>
<h2>Upload File</h2>
<hr>

<? if (isset($saved_filename)) : ?>
<?=  "<p>You can download your file <a href='/uploads/{$filename}'>here</a>.</p>"; ?>
<? endif; ?>
	<form method="POST" enctype="multipart/form-data">
		<label for="file1">File to upload: </label>
		<input type="file" id="file1" name="file1"> 
		<p>
			<input type="submit" value="Upload">
		</p>	
	</form>
</body>
</html>