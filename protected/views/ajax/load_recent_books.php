<? 
foreach ($books as $book) 
	$this->renderPartial('_render_book', array('book' => $book));
if (!count($books) && $read) echo '<div id="no_books_hint">No books found. Please, <a href="/home/AddBook">add a book</a></div>';
if ($load_more)
	$this->renderPartial('_render_load_more_button');