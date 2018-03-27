<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
	<link href='//fonts.googleapis.com/css?family=Open+Sans:600,400,300&subset=latin,cyrillic-ext' rel='stylesheet' type='text/css'>
	<link href="/css/font-awesome/css/font-awesome.min.css" rel="stylesheet">
	<link href="/css/reset.css" rel="stylesheet">
	<link href="/css/main.css" rel="stylesheet">
	<link href="/css/home.css" rel="stylesheet">
	<script src="/js/jquery/jquery.min.js"></script>
</head>
<body>
	<header>
		<a id="logo" href='/home'>Bookkip</a>
		<div id="top-menu">
			<a href="/home/addbook"><i class="fa fa-plus-square"></i> Add book</a>
			<a href="/home"><i class="fa fa-book"></i> My books</a>
			<a href="/home/readlater"><i class="fa fa-bookmark-o"></i> Read later</a>
			<a href="/home/support"><i class="fa fa-question-circle"></i> Support <b id="new-messages-count"></b></a>
		</div>
		<div id="user-name"><?= Yii::app()->user->name; ?> <i class="fa fa-caret-down"></i></div>

		<div id="navicon"><i id="fa-navicon" class="fa fa-bars"></i></div>
		<div id="mobile-menu">
			<a href="/home/addbook"><i class="fa fa-plus-square"></i> Add book</a>
			<a href="/home"><i class="fa fa-book"></i> My books</a>
			<a href="/home/readlater"><i class="fa fa-bookmark-o"></i> Read later</a>
			<a href="/home/support"><i class="fa fa-question-circle"></i> Support <b id="new-messages-count"></b></a>
			<a href="/site/logout"><i class="fa fa-power-off"></i> Log out</a>
		</div>
	</header>
	<div id="body">
		<div id="content">
			<?php echo $content; ?>
		</div>
	 </div>

	<div id="user-menu">
		<ul>
			<li><a href="/site/logout"><i class="fa fa-power-off"></i> Log out</a></li>
		</ul>
	</div>

	<script src="/js/jquery/jquery.maskedinput.min.js"></script>
	<script src="/js/main.js"></script>
	<script src="/js/home/layout.js"></script>

	<!-- Yandex.Metrika counter -->
	<script type="text/javascript">
	(function (d, w, c) {
	    (w[c] = w[c] || []).push(function() {
	        try {
	            w.yaCounter25520660 = new Ya.Metrika({
	                id:25520660,
	                clickmap:true,
	                trackLinks:true,
	                accurateTrackBounce:true,
	                webvisor:true
	            });
	        } catch(e) { }
	    });

	    var n = d.getElementsByTagName("script")[0],
	        s = d.createElement("script"),
	        f = function () { n.parentNode.insertBefore(s, n); };
	    s.type = "text/javascript";
	    s.async = true;
	    s.src = "https://mc.yandex.ru/metrika/watch.js";

	    if (w.opera == "[object Opera]") {
	        d.addEventListener("DOMContentLoaded", f, false);
	    } else { f(); }
	})(document, window, "yandex_metrika_callbacks");
	</script>
	<noscript><div><img src="https://mc.yandex.ru/watch/25520660" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
	<!-- /Yandex.Metrika counter -->
</body>
</html>