<link href="/jquery-ui/jquery-ui.min.css" rel="stylesheet">
<link href="/css/home/AddBook.css" rel="stylesheet">

<form class="form-horizontal" id="add-book" method="POST">
	<input type="hidden" name="cover" id="cover-input"/>
	<input type="hidden" name="cover_pos" id="cover-pos" value="<?= $book->cover_pos; ?>"/>
	<input type="hidden" name="cover_pos_percent" id="cover-pos-percent" value="<?= $book->cover_pos_percent; ?>"/>
	<div class="col-2">
		<div class="row">
			<label>Author</label>
			<div class="input-holder">
				<input type="text" id="author" name="author" class="required" value="<?= $book->author; ?>"/>
			</div>
		</div>
		<div class="row">
			<label>Name</label>
			<div class="input-holder">
				<input type="text" id="name" name="name" class="required" value="<?= $book->name; ?>"/>
			</div>
		</div>
		<div class="row">
			<label>Read date</label>
			<div class="input-holder">
				<input type="text" class="date-input required" value="<?= Common::RuDate($book->read_date); ?>" name="read_date"/>
			</div>
		</div>
		<div class="row">
			<label>Score</label>
			<div class="input-holder">
				<div class="rating" editable="true"><input type="hidden" name="rating" value="<?= $book->rating; ?>"/></div>
			</div>
		</div>
		<div class="row">
			<label>Review</label>
			<div class="input-holder">
				<textarea placeholder="What are your thoughts abount this book" name="comment"><?= $book->comment; ?></textarea>
			</div>
		</div>
		<div class="row">
			<label>Tags</label>
			<div class="input-holder">
				<input type="text" name="tags" class="tags" value="<?= $book->tags; ?>" placeholder="Type tags separated by comma" hintsUrl="/ajax/TagHints?"/>
			</div>
		</div>
	</div>
	<div class="col-1" id="cover-col">
		<div id="cover-holder" >
			<div id="icons-holder">
				<i class="fa fa-4x fa-book"></i>
				<i class="fa fa-4x fa-plus"></i>
			</div>
			<img src="<?= $book->getCover(); ?>" id="cover-image"/>
			<div id="preview-chose"><i class="fa fa-arrows"></i></div>
		</div>
		<!-- <div>
			<a id="autoload" class="inner-link">Найти обложку автоматически</a>
		</div> -->
	</div>
	<div style="clear: both"></div>
	<div class="button-holder">
		<button type="submit" class="btn btn-blue">Save</button>
	</div>
</form>
<input type="file" id="cover-select" />
<div id="shadow"></div>
<div id="dialog-holder">
	<div class="dialog" id="chose-cover-dialog">
		<i class="fa fa-times-circle fa-2x"></i>
		<div class="covers-content"></div>
		<div id="load-more">
			<a id="back" class="inner-link">Назад</a>
			<a id="next" class="inner-link">Далее</a>
		</div>
	</div>
</div>

<div id="tag-hint">
	<ul>	
	</ul>
</div>

<script>
	var authors = [<? foreach($authors as $a) echo "'$a'," ?>];
</script>

<script src="https://www.google.com/jsapi"></script>
<script src="/jquery-ui/jquery-ui.min.js"></script>
<script src="/js/jquery/jquery.ui.touch-punch.js"></script>
<script src="/js/home/AddBook.js"></script>