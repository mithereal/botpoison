<?php
$settings=array(
'displaywhois'=>true,
'ban_expires'=>'random hours',
'blacklistfile'=>'includes/labrea.db',
'sendemail'=>false,
'jail'=>'banned',  //name of file to trap bots; located in /view/banned.php 
'emailsender'=>'botcheck' . @$_SERVER['HTTP_HOST'],
'alertemail'=>'mithereal@gmail.com',
'returnurl'=> $_SERVER['HTTP_HOST'],
'kronfile'=>'includes/k.dat',  //for kron tempfile
'verbose'=>true,  //this effects how kron.php outputs if at all.
'version'=>'PhpLaBrea V1.0',
);
?>
