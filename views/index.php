<!DOCTYPE html>
<head><link rel="stylesheet" type="text/css" href="views/css/index.css" /></head>
<title>Greetings from the Underworld</title>

<body>
<div id="pit">
<h1>OOPS you have fallen into the tar!</h1>
<p>You did not Listen!!
<br>
This site&rsquo;s <a href="//<?php echo $_SERVER['HTTP_HOST'];?>/robots.txt">robots.txt</a> file says stay away from this location.
<br> 
Now, robots will review this Whois Data very casually. If it is determined that you do not suck donkey balls, you will be allowed to enter this site. Otherwise you are banned for <?php echo $tarpit->settings['ban_expires']; ?>. 
<br>
If you think this is a mistake, you can click the following button to authenticate yourself,
<br>
<form id="form" action="" method="post">
<button id="Next" value="Next"type="submit" href="">I'm not a Bot</button> 
</form>
<br>
Or you can <a href="<?php echo $_SERVER['HTTP_HOST'];?>labrea/contact/">Contact Us</a>.</p> 
<?php 

if(isset($arin))
echo $arin;
?>
<div id="copyright"><a href="http://code.google.com/p/php-labrea/" title="PhpLabrea the php bot trapper"><?php echo $tarpit->settings['version']; ?></a></div>
</div>
</body>
</html>
