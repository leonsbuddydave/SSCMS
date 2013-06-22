<?php
	// These fields are used for the custom forms
	// involved in category definitions
	// woo

/*	class FieldType
	{
		const TEXT = "text";
		const IMAGE = "image";
		const FILE = "file";
		const DROPDOWN = "dropdown";

		// like text, but validated
		const URL = "url";

		// big body shit
		const TEXTAREA = "textarea";

		// html/bbcode textarea
		const FANCY_TEXTAREA = "fancy-textarea";
	}
*/
	$FieldTypes =
		array(
				"TEXT" => "TextField"
			);

	class Field
	{
		public var $name = "";
		public var $type = "";
		public var $defaultValue = "";

		// here's where everything field-specific will go
		public var $settings = array();

		

		public function __construct($name)
		{
			$this->name = $name;
		}

		public function Flatten()
		{

		}

		public function ToJSON()
		{
			return json_encode($this);
		}
	}

	class TextField extends Field
	{
		public var $type;
		public var $defaultValue = "Text field...";

		public var $settings =
			array(

				);

		public function __construct($name)
		{
			$this->type = FieldType.TEXT;
		}

	}
?>