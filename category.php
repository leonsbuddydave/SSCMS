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

		// ...entries
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

		public function GetEntries()
		{
			return $this->Entries->posts;
		}

		function CategoryExists()
		{
			return file_exists($this->CategoryFilename)
				&& file_exists($this->DefinitionFilename);
		}

		function ReadEntries()
		{
			$this->Entries = json_decode( Read::FileContents( $this->CategoryFilename ) );
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

		public function RemoveEntryById($entryID)
		{
			// find it
			$index = $this->GetEntryIndexById($entryID);

			// kill it
			array_splice($this->Entries->posts, $index, 1 );

			// finish it
			$this->Commit();
		}

		/*
			This function and the next one are very similar
			the only difference is that one returns only the index,
			while the other returns the entire entry
			This decision may make me an idiot
		*/
		public function GetEntryById($entryID)
		{
			// php is fucking unreadable
			return $this->Entries->posts[ $this->GetEntryIndexById($entryID) ];
		}

		public function GetEntryIndexById($entryID)
		{
			$i = $entryID;
			$ec = count( $this->Entries->posts );

			// this is what to check for in 
			// case the requested entry does not exist
			$index = -1;

			// if we hit it right on the mark, whee
			if ( $this->Entries->posts[$i]->id == $entryID )
				$index = $i;
			else
			{
				// otherwise we have to play 
				// "Which Direction To Iterate"
				$t = 0;
				if ( $this->Entries->posts[$i]->id < $entryID )
					$t = 1;
				else
					$t = -1;

				// iterate in the chosen direction within the
				// bounds of the entry
				while ( $i > 0 && $i < $ec )
				{
					// if our id matches, we got it
					if ( $this->Entries->posts[$i]->id == $entryID )
					{
						$index = $i;
						break; // found it, go
					}

					// iterate up or down
					$i += $t;
				}
			}

			return $index;
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