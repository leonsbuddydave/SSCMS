<?php
	class HTMLNode
	{
		var $tagName = "";
		var $closingTag = true;
		var $attrs = array();
		var $children = array();
		var $classes = array();

		var $parentNode = null;

		function __construct()
		{

		}

		/*
			Sets the value of an attribute
			Adds the attribute if it does not exist
		*/
		public function setAttribute($name, $value)
		{
			if ( !isset($name) )
				throw new Exception("HTMLNode->setAttribute needs a name parameter.");
			else if ( !isset($value) )
				$value = ""; // we can move on by setting value to nothing

			$name = strtolower($name);
			if ($name === "class")
				throw new Exception("Don't set class using setAttribute, use add/removeClass instead.");

			$this->attrs[$name] = $value;
		}

		// sugar
		public function setID($id)
		{
			$this->setAttribute("id", $id);
		}

		// adds a class
		public function addClass($className)
		{
			// add the class
			$this->classes[] = $class;

			// make sure the array is unique
			$this->classes = array_unique($this->classes);
		}

		public function setParent($parent)
		{
			$this->parentNode = $parent;
		}

		// removes a class
		public function removeClass($className)
		{
			if ( ($key = array_search($className, $this->classes)) )
			{
				// get rid of it if we find anything
				unset( $this->classes[$key] );
			}
		}

		public function append(HTMLNode $node)
		{
			// my son
			$node->setParent($this);
			$this->children[] = $node;
		}

		/*
			Turns this node and its children into a flattened string
			state for printing (converts them to actual HTML)
		*/
		public function flatten()
		{
			// Create the start of the tag
			$startTag = "<" . $this->tagName . " ";

			// Convert and append its attributes
			foreach ($this->attrs as $attr=>$value)
			{
				$startTag .= $attr . "=\"" . $value . "\" ";
			}

			$startTag .= $this->compiledClasses();

			// Add the end of the start tag
			$startTag .= ">";

			// Somewhere in here we'll need to add the tag's
			// contents, as well as its flattened children
			foreach ($this->children as $child)
			{
				$startTag .= $child->flatten();
			}

			// Attach the end tag
			$startTag .= "</" . $this->tagName . ">";

			return $startTag;
		}

		// return space delimited class structure
		private function compiledClasses()
		{
			return implode(" ", $this->classes);
		}
	}

	class DocumentFragment
	{
		var $nodes = array();

		var $operators = array(">", "+", "^", "*", "#", ".");

		var $operatorSplitRegex = "";

		// takes the operators we need to split by
		// and builds a useful regex from them
		private function compileShorthandRegex()
		{
			$this->operatorSplitRegex = "'[";

			foreach ($this->operators as $o)
			{
				$this->operatorSplitRegex .= "\\" . $o;
			}

			$this->operatorSplitRegex .= "]'";
		}

		function __construct()
		{
			$this->compileShorthandRegex();
		}

		/*
			Allows us to build this document fragment
			using emmet-like shorthand
		*/
		public function buildFromShorthand($shorthandString)
		{
			// we have a shorthand string to parse here
			// we'll use this to generate a document chunk
			$this->populateFromShorthand($shorthandString);
			print $this->operatorSplitRegex;
		}

		private function populateFromShorthand($shorthandString)
		{
			$elements = $this->explodeShorthandString($shorthandString);

			print_r($elements);

			$l = count($elements);
			for ($i = 0; $i < $l; $i++)
			{

			}
		}

		private function explodeShorthandString($shorthandString)
		{
			
		}
	}

	class Div extends HTMLNode
	{
		var $tagName = "div";

		function __construct()
		{

		}
	}
?>