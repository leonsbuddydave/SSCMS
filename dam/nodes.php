<?php
	class Node
	{
		var $children = array();
		var $parentNode = null;

		function __construct()
		{

		}

		public function append(HTMLNode $node)
		{
			// my son
			$node->setParent($this);
			$this->children[] = $node;
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

		function __construct($tagName)
		{
			$this->tagName = $tagName;
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
			$startTag .= ">";

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

	class DocumentFragment extends Node
	{
		var $nodes = array();

		var $operators = array(">", "+", "^", "*", "#", ".");

		/*
			Allows us to build this document fragment
			using Emmet-like shorthand
		*/
		public function buildFromShorthand($shorthandString)
		{
			// we have a shorthand string to parse here
			// we'll use this to generate a document chunk
			$elements = $this->explodeShorthandString($shorthandString);

			// make this the top level element at the moment
			$currentContext = $this;

			$lastElementCreated = null;
			$lastTokenUsed = null;

			$l = count($elements);
			for ($i = 0; $i < $l; $i++)
			{
				$element = $elements[$i];

				// this is going to be a bit ugly
				if ( $this->isTagName($element) )
				{
					// Create a new element of this type
					$n = new HTMLNode($element);

					// Append it to whatever our current context is
					$currentContext->append( $n );

					// set that as the most recently created element
					$lastElementCreated = $n;
				}
				else if ( $this->isOperator($element) )
				{
					switch ($element)
					{
						// moving down into the previous context
						case ">":
							{ $currentContext = $lastElementCreated; }
						break;

						// moving up a context
						case "^":
							{
								$targetContext = $currentContext->getParent();

								if ($targetContext !== null)
									$currentContext = $targetContext;
							}
						break;

						// create an element with this id
						case "#":
							{
								$target = null;

								// if the previous token was an element,
								// our ID operation will target it
								if ( $this->isTagName( $lastTokenUsed ) )
								{
									$target = $lastElementCreated;
								}
								else // we'll make a new thing to target
								{
									$target = new Div();
									$currentContext->append($target);
									$lastElementCreated = $target;
								}

								$target->setID( $elements[++$i] );
							}
						break;

						// create an element with this class
						case ".":
							{
								$target = null;

								// if the previous token was an element,
								// our ID operation will target it
								if ( $this->isTagName( $lastTokenUsed ) )
								{
									$target = $lastElementCreated;
								}
								else // we'll make a new thing to target
								{
									$target = new Div();
									$currentContext->append($target);
									$lastElementCreated = $target;
								}

								$target->addClass( $elements[++$i] );
							}
						break;

						// adding another element onto the current context
						case "+":
							// should not change context at all
						break;
					}
				}
				else if ( $this->isNumeric($element) )
				{

				}

				$lastTokenUsed = $elements[$i];
			}
		}

		public function flatten()
		{
			$output = "";
			foreach ($this->children as $child)
			{
				$output .= $child->flatten();
			}

			return $output;
		}

		public function isOperator($o)
		{
			return in_array($o, $this->operators);
		}

		public function isTagName($t)
		{
			// may be kind of naive here, we'll see
			return !preg_match('/[^A-Za-z]+/', $t);
		}

		public function isNumeric($n)
		{
			return preg_match('/[0-9]+/', $n);
		}

		private function explodeShorthandString($shorthandString)
		{
			// elements consist of one chunk of shorthand
			// be it an element, operator or other
			$elements = array("");
			$elIndex = 0;
			$tokens = str_split($shorthandString);

			foreach ($tokens as $t)
			{
				// if we're at an operator
				if ( $this->isOperator($t) )
				{
					// append the operator
					$elements[] = $t;

					// add a string as the next guy
					// in preparation for an element
					$elements[] = "";
				}
				else
				{
					$i = count($elements) - 1;
					$elements[ $i ] .= $t;
				}
			}

			return $elements;
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