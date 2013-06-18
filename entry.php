<?php

	class Entry
	{
		var $id = 0;

		var $entryData;

		function __construct()
		{
			$this->entryData = array();
		}

		public function setID($id)
		{
			$this->id = $id;
		}

		public function setData($key, $value)
		{
			$this->entryData[$key] = $value;
		}

	}

?>