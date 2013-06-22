<?php
	require_once("dam/dam.php");
	require_once("category.php");
	require_once("entry.php");
	require_once("entryquery.php");

	// for testing purposes
	/*$d = new Category("blog");
	$d->Destroy();

	$c = new Category("blog");
	
	$testSetSize = 100;

	// add a bunch of stupid entries
	for ($i = 0; $i < $testSetSize; $i++)
	{
		$e = new Entry();
		$e->setData("title", "Lorem ipsum dolor set amet.");
		$c->AddEntry( $e );
	}

	// Remove every tenth entry
	for ($i = 0; $i < $testSetSize; $i++)
	{
		if ( $i % 10 == 0 )
			$c->RemoveEntryById($i);
	}

	$f = new EntryQuery("category=blog&id=8,29");
	print_r( $f->Exec() );

	$f = new EntryQuery("category=blog&count=10&offset=15");
	print_r( $f->Exec() );

	// this is weird as shit I hate that this works
	$type = "EntryQuery";
	$s = new $type("category=blog");*/
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