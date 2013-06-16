<?php
	require_once("dam/dam.php");
?>

<pre>
<?php
	$n = new Div();
	$n->setAttribute("you", "butt");
	$n->setAttribute("dog", "hands");
	$n->append( new Div() );
	$n->addClass("GAHHHH");
	$n->addClass("YO");
	print $n->flatten(true);

	$d = new DocumentFragment();
	$d->buildFromShorthand("ul>li+img+div#someid.testclass.fartclass*3");
	print $d->flatten();
?>

</pre>