<?php
	require_once("category.php");

	class EntryQuery
	{
		var $queryString;

		var $order = "descending",
			$count = 10,
			$category = "default"
			$offset = 30;

		function construct($queryString)
		{
			$this->queryString = $queryString;
		}

		// all the fun stuff will come later woo
		// "fun"
		// I just wanna do queries like
		// cat=blog&count=10&order=ascending&offset=30
	}

?>