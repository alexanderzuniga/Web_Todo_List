<?
function getOffset() {
		$page = isset($_GET['page']) ? $_GET['page'] : 1;
		return ($page - 1) * 4;
	}
$dbc = new PDO('mysql:host=127.0.0.1;dbname=todo_list_db', 'root', '');

$dbc->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$count = $dbc->query('SELECT count(*) FROM todo_list ')->fetchColumn();

$numPages = ceil($count / 4);

$page = isset($_GET['page']) ? $_GET['page'] : 1;

$nextPage = $page + 1;
$prevPage = $page - 1;

$TODO_list = $dbc->query('SELECT * FROM todo_list LIMIT 4 OFFSET ' .  getOffset())->fetchAll(PDO::FETCH_ASSOC);
//var_dump($TODO_list);
if(!empty($_POST)) {
	if (empty($_POST['remove'])) {
		try {
			foreach ($_POST as $key => $value) {
				if ($value == ''){
					throw new Exception("Please insert item to $key ", 1);
				}
			} 
			$stmt = $dbc->prepare('INSERT INTO todo_list (todo) VALUES (:todo)');

		    $stmt->bindValue(':todo', $_POST['todo'], PDO::PARAM_STR);

		    $stmt->execute();
		    header('Location: http://todo.dev/complete_todo.php');

		} catch (Exception $e) {
			echo $e->getMessage();
		}
//Removing items
	} else {
		$removeItem = $dbc->prepare('DELETE FROM todo_list WHERE id = :id');
		$removeItem->bindValue(':id', $_POST['remove'], PDO::PARAM_INT);
		$removeItem->execute();
		unset($_POST['remove']);
		header('Location:http://todo.dev/complete_todo.php');
		exit(0);
	} 
}
require_once 'classes/filestore.php';
?>
<!DOCTYPE html>
<html>
<head> 
	<link rel="stylesheet" href="/todo_style.css">
</head>
<body>
<div id="body"> 
	<div id="container"> 	
<h2> My "to-do" List </h2>
<hr>
<? if (!empty($errorMessage)) : ?>
<?= '<p>{$errorMessage}</p>'; ?>
<? endif; ?>
<ul>
<? foreach($TODO_list as $todo): ?>
	<li> 
	<?= $todo['todo']; ?>
	<button class="btn-remove" type="submit" name="remove" data-todo="<?= $todo['id'];?>">Remove</button> 

	</li>
	<? endforeach; ?>
</ul>
</p>

<!-- New item FORM  -->
<form method = "POST" action="/complete_todo.php"> 
	<p>
		<label for="todo"> New Item: </label>
		<input id="todo" name="todo" type="text" placeholder="New Item Here" autofocus>
	</p>
	<button type="submit"> Enter </button>
</form> 

<!-- Removal hidden form  -->
<form id="removeForm" action="complete_todo.php" method="POST">
    <input id="removeId" type="hidden" name="remove" value="">
</form>
</div>
</div>
</body>
<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<script>
$('.btn-remove').click(function () {
    var todoId = $(this).data('todo');
    if (confirm('Are you sure you want to remove item ' + todoId + '?')) {
        $('#removeId').val(todoId);
        $('#removeForm').submit();
    }
});
</script>
</html>