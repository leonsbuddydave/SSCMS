<?php
	require_once("category.php");

	class Order
	{
		const ASCENDING = "ascending";
		const DESCENDING = "descending";
	}

	class EntryQuery
	{
		var $queryString;

		var $isValidQuery = false;

		var $category = "", // required
			$count = 10,
			$order = "descending",
			$offset = 0,
			$ids = array();

		var $query = array(
				"category" => "", // required
				"count" => 10,
				"order" => "descending",
				"offset" => 0,
				"id" => array()
			);

		function __construct($queryString)
		{
			$this->queryString = $queryString;

			if ( $this->IsValidQueryString($queryString) )
			{
				$this->ExtractQueryComponents($queryString);

				$this->isValidQuery = true;
			}
			else
			{
				$this->isValidQuery = false;
			}
		}

		public function Exec()
		{
			$cr = new Category( $this->query["category"] );
			$entries = $cr->GetEntries();
			$results = array();

			/*
				We'll handle queries a little differently depending
				on whether or not they contain ID requests
				since IDs are supposed to be unique, we'll
				ignore offset and count completely
			*/
			if ( count( $this->query["id"] ) > 0 )
			{
				// these things are going to be sorted
				// by default, so a linear search starting
				// at the index matching the id will work nicely

				foreach ( $this->query["id"] as $id )
				{
					$results[] = $cr->GetEntryById($id);
				}
			}

			/*
				Otherwise, we're doing a much simpler batch request
				TODO: Move this functionality into the Category class,
				then call it from here. This class is just for managing
				the compressed queries themselves
			*/
			else
			{
				$i = $this->query["offset"];
				$c = $i + $this->query["count"];
				$ec = count( $entries );

				while ($i < $c && $i < $ec)
				{
					$results[] = $entries[$i];
					$i++;
				}

			}

			return $results;
		}

		private function ExtractQueryComponents($queryString)
		{
			$tokens = $this->SeparateTokens($queryString);

			$this->ParseTokens($tokens);
		}

		private function IsValidQueryString($queryString)
		{
			$format = (substr_count($queryString, "&") == (substr_count($queryString, "=") - 1));
			$containsCat = (strpos($queryString, "category=") !== false);

			return $format && $containsCat;
		}

		private function SeparateTokens($queryString)
		{
			return explode("&", $queryString);
		}

		private function ParseTokens($tokens)
		{
			foreach ($tokens as $token)
			{
				$t = explode("=", $token);

				switch ($t[0])
				{
					// strings
					case "category":
					case "order":
						$this->query[ $t[0] ] = $t[1];
						break;

					// ints
					case "count":
					case "offset":
						$this->query[ $t[0] ] = intval( $t[1] );
						break;

					// string arrays
					case "id":
						$this->query[ $t[0] ] = explode(",", $t[1]);
						break;
				}
			}
		}
	}

?>