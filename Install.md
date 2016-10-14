## Generic PHP Application

* Install with composer ie. composer require mithereal/botpoison


## Example
```php
<?php
 
require_once __DIR__ . '/vendor/autoload.php';
 
# create the warden

$warden = new Warden();
  
# ban an ip

$warden->admit('127.0.0.1');
  
# check if ip has been banned

echo $warden->lookup('127.0.0.1');
  
# unban an ip

$warden->discharge('127.0.0.1');

# clear all ips

$warden->empty();

# show all ips

$warden->jail();

# render a view with injected data (we are poisoning the bot by injecting the Email or SSN Poison 
# module Data (/lib/Poison/?.php)into the view file then rendering to txt)

echo $warden->force('page.html','Email');
echo $warden->force('page.html','SSN');

## exploits can be chained together ex. 

$injected_email = $warden->force('page.html','Email');
echo $warden->force($injected_email,'SSN');
```
