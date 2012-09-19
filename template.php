<?php header("Content-Type: text/html;charset=UTF-8");?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title></title>
        <meta name="description" content="Prueba de concepto generador de payas">
        <meta name="viewport" content="width=device-width">

        <link rel="stylesheet" href="css/bootstrap.min.css">
        <style>
            body {
                padding-top: 60px;
                padding-bottom: 40px;
            }
        </style>
        <link rel="stylesheet" href="css/bootstrap-responsive.min.css">
        <link rel="stylesheet" href="css/main.css">
		<link rel="stylesheet" href="css/avgrund.css">

        <script src="js/vendor/modernizr-2.6.1-respond-1.1.0.min.js"></script>
    </head>
    <body>
        <!--[if lt IE 7]>
            <p class="chromeframe">You are using an outdated browser. <a href="http://browsehappy.com/">Upgrade your browser today</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to better experience this site.</p>
        <![endif]-->

        <!-- This code is taken from http://twitter.github.com/bootstrap/examples/hero.html -->


        <div class="navbar navbar-inverse navbar-fixed-top">
            <div class="navbar-inner">
                <div class="container">
                    <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </a>
                    <a class="brand" href="#">Generador de Payas</a>
                    <div class="nav-collapse collapse">
                        <ul class="nav">
                            <li class="active"><a href="#">Home</a></li>
                            <li><a href="#about" onclick="javascript:openDialog();">Acerca de</a></li>
                    </div><!--/.nav-collapse -->
                </div>
            </div>
        </div>

		<aside id="default-popup" class="avgrund-popup">
			<h2>Acerca de..</h2>
			<p>El Generador de payas funciona como el ajo porque lo programó un mono. Si usted cree que programa mejor que un mono puede hacer click en el link del pie que dice "Fork me on GitHub" y generar su propia versión con juegos de azar y mujerzuelas. El autor (@lacosox)</p>
			<button onclick="javascript:closeDialog();">Close</button>
		</aside>

        <div class="container">

            <!-- Main hero unit for a primary marketing message or call to action -->
            <div class="hero-unit">
                <em><h3 class="text-info" >
                <?php printPayaHtml($payaHtmlArray);?>
                </h3></em>

	    <form class="form-inline" action="<?php echo $_SERVER['PHP_SELF'];?>" method="get">
              <div class="control-group">
                <div class="controls">
                <?php if (isset($base)): ?>
                <input id="palabra_usuario" name="base" type="text"  value="<?php echo $base;?>" class="span2">
                <?php else:?>
                  <input id="palabra_usuario" name="base" type="text" placeholder="Rimar con..." class="span2">
                <?php endif; ?>
	    
	    <button type="submit" class="btn">Generar Paya&raquo;</button>
                </div>
              </div>
	    </form>
<!-- AddThis Button BEGIN -->
<div class="addthis_toolbox addthis_default_style ">
<a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>
<a class="addthis_button_tweet"></a>
<a class="addthis_counter addthis_pill_style"></a>
</div>
<script type="text/javascript">var addthis_config = {"data_track_addressbar":true};</script>
<script type="text/javascript" src="http://s7.addthis.com/js/300/addthis_widget.js#pubid=ra-4e4eb2e17c08f43b"></script>
<!-- AddThis Button END -->
            </div>

            <hr>

            <footer>
                <p>&copy; Lacosox.org 2012 - <strong><a href="https://github.com/knxroot/payas_generator" class="text-success">Fork me on GitHub</a></strong></p>
            </footer>

        </div> <!-- /container -->

		<div class="avgrund-cover"></div>



		<script type="text/javascript" src="js/avgrund.js"></script>


        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.8.1.min.js"><\/script>')</script>

        <script src="js/vendor/bootstrap.min.js"></script>

        <script src="js/plugins.js"></script>
        <script src="js/main.js"></script>

        <script>
            var _gaq=[['_setAccount','UA-34911399-1'],['_trackPageview']];
            (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
            g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
            s.parentNode.insertBefore(g,s)}(document,'script'));
        </script>
    </body>
</html>
