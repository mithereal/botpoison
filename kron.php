<?php
require 'classes/tarpit.php';
$tarpit=new tarpit;
$krons=array(); 
$wiped=$tarpit->wipePit();
if(isset($wiped))
$krons['pit']="the tarpit was wiped of all data!";  //add text you want displayed as array value verbose must be set to true
$tarpit->touch();
if($tarpit->settings['verbose'] == true)
   require 'views/kron.php';	
?>

