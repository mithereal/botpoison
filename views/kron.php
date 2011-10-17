<!DOCTYPE html>
<head><link rel="stylesheet" type="text/css" href="views/css/index.css" /></head>
<title>Kron Jobs</title>

<body>
<div id="pit">
<h1>Kron Jobs Completed</h1>
<OL>
	<?php
	foreach($krons as $job)
	{
		echo "<li>$job</li>";
	}
	?>
	
</OL>
<div id="copyright"><a href="http://code.google.com/p/php-labrea/" title="PhpLabrea the php bot trapper"><?php echo $tarpit->settings['version']; ?></a></div>
</div>
</body>
</html>
