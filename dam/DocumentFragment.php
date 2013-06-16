<?php
class DocumentFragment extends Node
{
	var $nodes = array();

	var $operators = array(">", "+", "^", "*", "#", ".", "[", "]", "=", " ");

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

							if ($element === "#")
								$target->setID( $elements[++$i] );
							else if ($element === ".")
								$target->addClass( $elements[++$i] );
						}
					break;

					case "*":
						{
							if ( !$this->isNumeric($elements[$i + 1]) )
								throw new Exception("Emmet Syntax: Multiplication operator without operand.");

							// Basically we need to clone that
							// <--- object
							// -----> that many times
							// woo
							// Emmet supports parenthese grouping for this operation, but for my current
							// level of interest in this project, fuuuuuck that
							$c = intval( $elements[$i + 1] ) - 1;
							for ($j = 0; $j < $c; $j++)
							{
								$copy = clone $lastElementCreated;
								$currentContext->append($copy);
							}
						}
					break;

					// adding another element onto the current context
					case "+":
						// we shouldn't really have to do anything here
						// but it doesn't cost me much to just have this here
					break;
				}
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
?>