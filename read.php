<?php
	
	class Read
	{

		public static function FileContents($filename)
		{
			return file_get_contents($filename);
		}

	}

?>