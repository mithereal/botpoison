<?php
/*
 * ieally this file would be run every 10-30 minutes, for now we run every 24 h to wipe all data in the pit
 * later i will refine to make more granular
 */
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

