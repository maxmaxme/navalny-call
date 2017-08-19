<?
require_once '../config.php';
if (!$auth->canViewTree)
	die('Access denied');

$addButtons = $auth->canEditTree ? 1 : 0;

?><!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<title>Дерево</title>
	<link type="text/css" href="/style/site.css" rel="stylesheet">
	<link type="text/css" href="/font-awesome/css/font-awesome.min.css" rel="stylesheet">
</head>
<body>

<div id="tree">
	<h4><a>Звонок</a></h4>
	<?= getBranch(1, $addButtons) ?>
</div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="/js/mustache.min.js"></script>
<script src="/js/engine.js"></script>
<script src="/js/mustacheTemplates.js"></script>
<script src="/js/tree.js"></script>
</body>
</html>
