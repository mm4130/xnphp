# Time
use :
```php
require "xntime.php";
```
**option** :
_date_ option :
```php
xndateoption( int Type );
```
Types :<br>
1 -> AD<br>
2 -> Solar<br>
3 -> Lunar<br>
_time_ option :
```php
xntimeoption( string timeZone );
```

example from combination the date and time options :
```php
xndateoption(2) + xntimeoption("Asia/Tehran");
```
**get time&date** :
```php
xntime( xnoption ); // unix time and milisecound
xndate( string format , xnoption );
```

continuation example :
```php
$option = xndateoption(2) + xntimeoption("Asia/Tehran");
$date = xndate("ca",$option);
$time = xntime($option);
```
