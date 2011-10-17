<?php
require 'classes/blog.php';
$blog=new blog;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />
	<link rel="stylesheet" type="text/css" href="http://<?php echo $_SERVER['HTTP_HOST']?>/php-labrea/views/css/blog.css" />

	<title><?php echo $blog->title; ?></title>
</head>

<body>

<div class="container" id="page">

	<div id="header">
		<div id="logo"><?php //echo $blog->title;?></div>

	</div><!-- header -->

	<div id="mainmenu">
		<ul id="yw0">
<li class="active"><a href="">Home</a></li>
<li><a href="">About</a></li>
<li><a href="">Contact</a></li>
<li><a href="">Login</a></li>
</ul>	</div><!-- mainmenu -->

	<!-- breadcrumbs -->

	<div class="container">
	<div id="content">
	<div id="introtxt">
<h1>Welcome to <i><?php echo $blog->title; ?></i></h1>
</div>	
<div id="story" class="blogtext">
	<?php echo $blog->tellStory(); ?>
		</div>
				<?php include "_captcha.php"; ?>
		<div class="comments">
			<div class="title">Comments:</div>
			<?php echo $blog->getComments(); ?>
		</div>
</div><!-- content -->
</div><!-- container -->

	<div id="footer">
		<div id="copyright"><a href="http://code.google.com/p/php-labrea/" title="PhpLabrea the php bot trapper"><?php echo $tarpit->settings['version']; ?></a></div>
		</div>
		</div>
		</div><!-- footer -->

</div><!-- page -->

</body>
</html>

