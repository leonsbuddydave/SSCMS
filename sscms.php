<?php
	require_once("dam/dam.php");
	require_once("category.php");
	require_once("entry.php");

	// for testing purposes
	$d = new Category("blog");
	$d->Destroy();

	$c = new Category("blog");
	
	$e = new Entry();
	$e->setData("title", "Particle Man Gets Wet");

	$c->AddEntry( $e );
?>

<?php
	// $n = new Div();
	// $n->setAttribute("you", "butt");
	// $n->setAttribute("dog", "hands");
	// $n->append( new Div() );
	// $n->addClass("GAHHHH");
	// $n->addClass("YO");
	// print $n->flatten(true);

	// $d = new DocumentFragment();
	// $d->buildFromShorthand("ul>li+img+div#someid.testclass.fartclass*3");
	// print $d->flatten();
?>