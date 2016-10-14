## Generic PHP Application

* Install with composer ie. composer require mithereal/botpoison


## Example
```php
<?php
 
require_once __DIR__ . '/vendor/autoload.php';
 
# create the blackhole

$blackhole = new Blackhole();
  
# ban an ip

$blackhole->swallow('127.0.0.1');
  
# check if ip has been banned

echo $blackhole->detect('127.0.0.1');
  
# ban an ip

$blackhole->spit('127.0.0.1');

# render a view with injected data (we are poisoning the bot by injecting the Email Poision Data into the view file then rendering to txt)

echo $blackhole->exploit('page.html','Email');
```
