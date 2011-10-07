<?php 
require 'controller.php';
require 'model.php';
$controller=new controller;
$model=new tarpit;
$model->addBot();
//$badbot=$model->isBot();
$valid=$controller->targetValid();
if (isset($badbot)) { 
	if(isset($controller->settings['sendemail'])){
		$controller->sendEmail();
		}
	}
?>
<!DOCTYPE html>
<?php
require '/view/index.php';
?>
</html>

