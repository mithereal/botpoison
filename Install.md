* Unzip to the root dir.
* Locate the main php script, usually index.php.
* Edit your index.php bootstrap adding to the top of the file
```php
include($_SERVER['DOCUMENT_ROOT'] . "/php-labrea/classes/tarpit.php"); 
    // change the following paths if necessary
    $tarpit=new tarpit;
 
    if($tarpit->isBot()){
            $location="http://" . $_SERVER['HTTP_HOST'] ."/php-labrea";
            header('location:'.$location);
            exit;
    }
```
* Edit your main view file 

```php
<?php $pitdir = "http://" . $_SERVER['HTTP_HOST'] . "/php-labrea/"; ?>
    <a href="<?php echo $pitdir; ?>"><img src="images/pixel.gif" border="0" 
    alt=" " width="1" height="1" style="display:none;"></a>
```
