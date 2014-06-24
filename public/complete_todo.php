
<!DOCTYPE html>
<html>
<head> 
<?
require_once 'classes/filestore.php';
$TDL = new Filestore('data/list.txt');
// var_dump($TDL->filename);
$TODO_list = $TDL->read($TDL->filename);
// var_dump($TODO_list);
$errorMessage = '';

	//Removing items
if (isset($_GET['removeIndex'])) {
	$removeIndex = $_GET['removeIndex'];
	unset($TODO_list[$removeIndex]);
	$TDL->write($TODO_list);
	header('Location:http://todo.dev/complete_todo.php');
	exit(0);
} 
	// Adding items 
class CustomException extends Exception {}
if (!empty($_POST['new_item'])) {
	try {
		if (strlen($_POST['new_item']) > 125) {
		    throw new CustomException("<script type='text/javascript'>alert('Total charachters must not excede 5 charachters.');</script>");
		}
		array_push($TODO_list, $_POST['new_item']);
		if (!is_array($TODO_list)) {
			throw new Exception ("<script type='text/javascript'>alert('Submitted list must be an array.');</script>");
		}
		$TDL->write($TODO_list);
		header('Location: /complete_todo.php');
	} catch (CustomException $ce) {
		echo  $ce->getMessage();
		$msg = $ce-> getMessage() . PHP_EOL;
	} catch (Exception $e) {
		echo "Extention:" . $e->getMessage() . PHP_EOL;
		$msg = $e-> getMessage() . PHP_EOL;
	}
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

			$uploaded_TDL = new Filestore($saved_filename);
			$newfile= $uploaded_TDL->read_lines();
			$TODO_list= array_merge($TODO_list, $newfile);
			$TDL->write($TODO_list);

	} 
?>
	<link rel="stylesheet" href="/todo_style.css">
</head>
<body>
<div id="body"> 
	<div id="container"> 	
<h2> My "to-do" List</h2>
<hr>
<? if (!empty($errorMessage)) : ?>
<?= '<p>{$errorMessage}</p>'; ?>
<? endif; ?>
<ul>
<? foreach ($TODO_list as $key => $item) : ?>
<li> 
	<?=htmlspecialchars(strip_tags($item));?> 
	<a href="complete_todo.php?removeIndex=<?=$key?>">*Remove Item*</a><br>
</li>
<? endforeach; ?>
</ul>
	
</p>
<form method = "POST" action="/complete_todo.php"> 
	<p>
		<label for="new_item"> New Item: </label>
		<input id="new_item" name="new_item" type="text" placeholder="New Item Here" autofocus></label>
	</p>
	<button type="submit"> Enter </button>
</form>
<h2 >Upload File</h2>
<hr>

<? if (isset($saved_filename)) : ?>
<?=  "<p>You can download your file <a href='/uploads/{$filename}'>here</a>.</p>"; ?>
<? endif; ?>
	<form method="POST" enctype="multipart/form-data">
		<label for="file1">File to upload: </label>
		<input type="file" id="file1" name="file1"> 
		<p>
			<button type="submit"> Upload </button>
		</p>	
	</form>
</div>
</div>
</body>
</html>