<?php
	class Node
	{
		var $children = array();
		var $parentNode = null;

		// appends a new child to this node and
		// then returns it
		// good chaining shit
		public function append(HTMLNode $node)
		{
			// my son
			$node->setParent($this);
			$this->children[] = $node;
			
			return $node;
		}

		public function setParent($parent)
		{
			$this->parentNode = $parent;
		}

		public function getParent()
		{
			return $this->parentNode;
		}
	}

	class HTMLNode extends Node
	{
		var $tagName = "";
		var $closingTag = true;
		var $attrs = array();
		var $classes = array();

		// text contents, for tags like p and script
		// these are tags that shouldn't have children,
		// but if they do,
		// the text will come beforehand
		var $contents = "";

		function __construct($tagName)
		{
			$this->tagName = $tagName;
		}

		function __clone()
		{
			$this->children = array();
		}

		public function setNodeType($tagName)
		{
			$this->tagName = $tagName;
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
			$this->classes[] = $className;

			// make sure the array is unique
			$this->classes = array_unique($this->classes);
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

		public function setContents($stringContents)
		{
			$this->contents = $stringContents;
		}

		public function hasAnyClasses()
		{
			return count($this->classes) > 0;
		}

		/*
			Turns this node and its children into a flattened string
			state for printing (converts them to actual HTML)
		*/
		public function flatten()
		{
			// Create the start of the tag
			$startTag = "<" . $this->tagName;

			// if this node has any classes, append them
			if ($this->hasAnyClasses())
				$startTag .= " class=\"" . $this->compiledClasses() . "\"";

			// total number of attributes
			$attrCount = count($this->attrs);

			// for keeping count of the attributes
			$i = 0;

			// Convert and append the attributes
			foreach ($this->attrs as $attr=>$value)
			{
				// prepend with a space if this is the first attribute
				if ($i === 0)
					$startTag .= " ";

				// append the attribute itself
				$startTag .= $attr . "=\"" . $value . "\"";
				$i++;

				// if this is NOT the last attribute, add a space
				if ($i !== $attrCount)
					$startTag .= " ";
			}

			// Add the end of the start tag
			$startTag .= ">" . $this->contents;

			// Somewhere in here we'll need to add the tag's
			// contents, as well as its flattened children
			foreach ($this->children as $child)
			{
				$startTag .= $child->flatten();
			}

			// Attach the end tag
			$startTag .=  "</" . $this->tagName . ">";

			return $startTag;
		}

		// return space delimited class structure
		private function compiledClasses()
		{
			return implode(" ", $this->classes);
		}
	}

	class Div extends HTMLNode
	{
		var $tagName = "div";

		function __construct()
		{

		}
	}

	class P extends HTMLNode
	{
		var $tagName = "p";
	}
?>