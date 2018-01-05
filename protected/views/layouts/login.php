<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo CHtml::encode($this->pageTitle); ?></title>
  </head>
  <body>
    <?php echo $content; ?>

    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300&subset=latin,cyrillic-ext' rel='stylesheet' type='text/css'>
    <link href="/css/reset.css" rel="stylesheet">
    <link href="/css/main.css" rel="stylesheet">
    <link href="/css/login.css" rel="stylesheet">
    
    <script src="//vk.com/js/api/openapi.js" type="text/javascript"></script>
    <script src="/js/jquery/jquery.min.js"></script>
    <script src="/js/main.js"></script>
    <script src="/js/login.js"></script>

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