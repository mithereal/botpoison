<?php
$settings=array(
'retrunurl'=>'http://' . $_SERVER['SERVER_NAME'],
'displaywhois'=>false,
'ban_expires'=>'24 hours',
'blacklistfile'=>'includes/labrea.db',
'sendemail'=>false,
'jail'=>'blog',  //name of file to trap bots; located in /view/xxx.php 'poison|banned|blog' 
'emailsender'=>'botcheck' . @$_SERVER['HTTP_HOST'],
'htaccesspath'=>'/',  //path to htaccess file
'alertemail'=>'mithereal@gmail.com',
'returnurl'=> $_SERVER['HTTP_HOST'],
'kronfile'=>'includes/k.dat',  //for kron tempfile
'verbose'=>true,  //this effects how kron.php outputs if at all.
'version'=>'PhpLaBrea V1.0',
);
?>
