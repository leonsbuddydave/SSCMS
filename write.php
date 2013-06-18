<?php
	class Write
	{
		public static function WholeFile($filename, $contents)
		{
			$handle = fopen($filename, 'w');
			fwrite($handle, $contents);
			fclose($handle);
		}

		public static function Touch($filename)
		{
			$handle = fopen($filename, 'w');
			fwrite($handle, "");
			fclose($handle);
		}

		public static function Delete($filename)
		{
			unlink($filename);
		}

	}
?>