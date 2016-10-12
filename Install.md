## Generic PHP Application

* Unzip to the root dir.
* Locate the main php script (bootstrap), usually index.php.
* Edit your bootstrap file by adding to the top of the file

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

* Edit your main view file (optional)

```php
<?php $pitdir = "http://" . $_SERVER['HTTP_HOST'] . "/php-labrea/"; ?>
    <a href="<?php echo $pitdir; ?>"><img src="images/pixel.gif" border="0" 
    alt=" " width="1" height="1" style="display:none;"></a>
```
## Wordpress Application

* Unzip to the root dir.
* Edit wp-load.php by adding to the top of the file

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

* Edit your main view file (optional) /wp-content/themes/your-theme/header.php or login into wordpress after login into wordpress go Appreance>>editor in right side you find "Templates" below that you find list files search header.php, or https://wordpress.org/plugins/insert-headers-and-footers/, and paste code below you can also download the plugin 



```php
<?php $pitdir = "http://" . $_SERVER['HTTP_HOST'] . "/php-labrea/"; ?>
    <a href="<?php echo $pitdir; ?>"><img src="images/pixel.gif" border="0" 
    alt=" 
