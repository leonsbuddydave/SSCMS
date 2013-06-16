<?php
	require_once("dam/dam.php");

	$n = new Div();
	$n->setAttribute("you", "butt");
	$n->append( new Div() );
	print $n->flatten();

	$d = new DocumentFragment();
	$d->buildFromShorthand("ul>li*4");
?>