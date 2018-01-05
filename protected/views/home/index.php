<link href="/css/home/index.css" rel="stylesheet">

<div id="search-holder">
	<div id="search-parent">
		<i class="fa fa-search"></i>
		<form id="search-form">
			<input type="text" name="q" value="<?= $q; ?>" id="search" autocomplete="off" placeholder="Name, author or tag" />
			<div id="search-autocomplete">
				<ul></ul>
			</div>
		</form>
	</div>
</div>

<div id="ajax-content"></div>

<script src="/js/home/index.js"></script>