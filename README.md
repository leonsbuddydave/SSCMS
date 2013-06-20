SSCMS stands for Shit-Simple CMS.

Because I hate Wordpress and I'm tired of using Wordpress.

Wordpress is a cancer.

NOTES: (Formal documentation will come later)

```php

// Creates a new category, or loads it if it already exists
$c = new Category("blog");

// Creates a new entry, gives it a title
// ( doesn't have to be a title - could be any data )
// then adds it to our category
$e = new Entry();
$e->setData("title", "I'm out of coffee and I hate tea");
$c->AddEntry( $e );

// Retrieve a batch of entries from the category
$entries = (new EntryQuery("category=blog&count=10&offset=15"))->Exec();

// Retrieve a single entry
$singleEntry = (new EntryQuery("category=blog&id=16"))->Exec();

// Retrieve multiple entries by their IDs
$severalSpecificEntries = (new EntryQuery("category=blog&id=16,25,30"))->Exec();


```