<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="language" content="en" />
<link rel="stylesheet" type="text/css" href="views/css/banned.css" />

<title>Banned</title>
</head>

<body>

<div class="container" id="page">

<div id="header">
<div id="logo"></div>

</div><!-- header -->

<div class="container">
<div id="content">
	<div id="pit">
			<h1>OOPS you have fallen into the tar!</h1>
			<p>Welcome annoying Bot.
			 <br>This site&rsquo;s <a href="//<?php echo $_SERVER['HTTP_HOST'];?>/robots.txt">robots.txt</a> file told you, stay away from this location.<br> 
			 Now you have been banned for <?php echo $tarpit->settings['ban_expires']; 
			 ?>, <br>
			 Congratulations. 
<?php include "_captcha.php"; ?>
				</div>
</div>
</div>
<div id="copyright"><a href="http://code.google.com/p/php-labrea/" title="PhpLabrea the php bot trapper"><?php echo $tarpit->settings['version']; ?></a></div>
		</div>
</body>
</html>
