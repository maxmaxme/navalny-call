<?
require_once '../config.php';
?><!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<title>Дерево</title>
	<link type="text/css" href="/style/site.css" rel="stylesheet">
</head>
<body>

<div id="tree">
	<h4><a>Звонок</a></h4>
	<?= getBranch(1, 1) ?>
</div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="/js/mustache.min.js"></script>
<script src="/js/engine.js"></script>
<script src="/js/mustacheTemplates.js"></script>
<script src="/js/tree.js"></script>
</body>
</html>
