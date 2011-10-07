<?php 
<head><link rel="stylesheet" type="text/css" href="view/index.css" /></head>
	<title>Greetings from the Underworld of penis!</title>
	
	<body>
		<div id="pit">
			<h1>You have fallen into the tar!</h1>
			<p>This site&rsquo;s <a href="<?php echo $_SERVER['HTTP_HOST'];?>/robots.txt">robots.txt</a> file explicitly forbids your presence at this location. 
				The following Whois data will be reviewed carefully. If it is determined that you suck, you will be banned from this site for <?php echo $controller->settings['ban_expires']; ?>. 
				If you think this is a mistake, <em>now</em> is the time to <a href="<?php echo $_SERVER['HTTP_HOST'];?>labrea/contact/">Contact Us</a>.</p>

<?php 

if(isset($valid))
	{
	if(isset($controller->settings['displaywhois'])){
	$controller->arin();
	}

// if bot is unique, send email and log entry

	}
?>
			<p><a href="http://code.google.com/p/php-labrea/" title="PhpLabrea the php bot trapper">PhpLabrea V1.0</a></p>
		</div>
	</body>
	?>
