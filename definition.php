<?php
	require_once("write.php");
	require_once("read.php");
	require_once("directories.php");
	require_once("extensions.php");

	// Controls access to the form definition of
	// a given category
	class Definition
	{
		const DEFAULT_CATEGORY_DEFINITION = '{"fields":{}}';

		// name of the category this is attached to
		var $name;

		// filename that stores this definition
		var $DefinitionFilename;

		// the individual fields that make up this definition
		var $FormFields = array();

		public function __construct($name)
		{
			$this->name = $name;

			$this->DefinitionFilename = Directories::CATEGORY . $name . "." . Extensions::CATEGORY_DEFINITION;
		
			if ( !$this->DefinitionExists() )
				$this->Create();
		}

		function DefinitionExists()
		{
			return file_exists( $this->DefinitionFilename );
		}

		private function Create()
		{
			// Category structure file - defines the structure of the data
			Write::WholeFile($this->DefinitionFilename, self::DEFAULT_CATEGORY_DEFINITION);
		}

		public function Destroy()
		{
			Write::Delete( $this->DefinitionFilename );
		}

	}

?>