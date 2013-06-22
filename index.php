<?php
	require_once("sscms.php");
?>
<!doctype html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>Shit-Simple CMS</title>

		<link rel="stylesheet" href="./css/index.css">
	</head>
	<body>
		
		<nav>
			<div class="navLink"><a target="contentPage" href="edit-entry.php">New Entry</a></div>
			<div class="navLink"><a target="contentPage" href="edit-category.php">New Category</a></div>
		</nav>
		
		<iframe src="" frameborder="0" id="contentPage"></iframe>
	</body>
</html>