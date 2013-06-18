<?php
	require_once("write.php");
	require_once("read.php");
	require_once("directories.php");
	require_once("extensions.php");

	class Category
	{

		const DEFAULT_CATEGORY_CONTENTS = '{"posts":[],"last_id":-1}';
		const DEFAULT_CATEGORY_DEFINITION = '{"fields":{}}';

		// the name of this category
		var $CategoryName;

		// some useful filenames
		var $CategoryFilename, $DefinitionFilename;

		// 
		var $Entries;

		function __construct($name)
		{
			$this->CategoryName = $name;

			$this->CategoryFilename = Directories::CATEGORY . $name . "." . Extensions::CATEGORY;
			$this->DefinitionFilename = Directories::CATEGORY . $name . "." . Extensions::CATEGORY_DEFINITION;
		
			if (!$this->CategoryExists())
				$this->Create();

			$this->ReadEntries();
		}

		function CategoryExists()
		{
			return file_exists($this->CategoryFilename)
				&& file_exists($this->DefinitionFilename);
		}

		function ReadEntries()
		{
			$this->Entries = json_decode( Read::FileContents( $this->CategoryFilename ) );
			print_r($this->Entries);
		}

		function Commit()
		{
			Write::WholeFile( $this->CategoryFilename, json_encode( $this->Entries ) );
		}

		public function AddEntry($newEntry)
		{
			// add the new entry
			$this->Entries->posts[] = $newEntry;

			// increment the last id used
			$id = (++$this->Entries->last_id);

			// give the new entry this id
			$newEntry->setID($id);

			// save
			$this->Commit();
		}

		public function Create()
		{
			// Category storage file - holds all the data
			Write::WholeFile($this->CategoryFilename, self::DEFAULT_CATEGORY_CONTENTS);

			// Category structure file - defines the structure of the data
			Write::WholeFile($this->DefinitionFilename, self::DEFAULT_CATEGORY_DEFINITION);
		}

		public function Destroy()
		{
			Write::Delete( $this->CategoryFilename );
			Write::Delete( $this->DefinitionFilename );
		}
	}
?>