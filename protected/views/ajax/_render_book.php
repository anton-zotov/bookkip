<div class="book-small">
	<a href="/home/addbook?id=<?= $book->id; ?>">
		<? if ($book->getCover()): ?>
			<div class="cover-holder">
				<img src="<?= $book->getCover(); ?>" class="cover-image" style="top: <?= $book->getCoverOffset(); ?>"/>
			</div>
		<? else: ?>
			<div class="cover-holder empty">
				<i class="fa fa-4x fa-book"></i>
			</div>
		<? endif; ?>
		<div class="book-title"><?= $book->name; ?></div>
		<div class="book-author"><?= $book->author; ?></div>
		<div class="rating"><input type="hidden" name="rating" value="<?= $book->rating; ?>"/></div>
		<? if ($book->read_date): ?><div class="book-date"><?= $book->getReadDate(); ?></div><?endif;?>
		<? if ($tags = trim($book->tags, ',')): ?>
			<div class="book-tags">
				<? 
				$first = true;
				foreach (explode(',', $tags) as $tag)
				{
					if (!$first) echo ', ';
					echo "<a class='book-tag' onclick='TagClick(\"$tag\");'>$tag<div class='tag-border'></div></a>";
					$first = false;
				}
				?>
			</div>
		<? endif; ?>
	</a>
</div>