# Root

import files :
```php
require "xn.php";
```

or import one file (for example xnfile.php file) :
```php
require "xnroot.php";

require "xnfile.php";
```
* all files need Root File!

# XNError :

class XNError for errors from xn methods :

```php
try {
  throw new XNError("Main","Error Test");
}catch(XNError $e){
  var_dump($e);
}
```
use XNError **Class** :
```php
new XNError( string From , string Error [, string Error_code = 0 ] );
```
_methods_ :

**show**
hidden or show errors from Output ( not throw )
```php
XNError::show(false); // off show errors
XNError::show(); // togle show errors
XNError::show("error.log"); // show errors and save in file
```

**handlr**
set Handle for XNErrors
```php
XNError::handlr( function( Object:XNError $error ){
// Codes to run ...
});
```
# XN Connects :
**xnscript**
get xn script info
```php
array xnscript ( );
@return [
  "varsion" => string Version ,
  "libs" => array Libs_imported ,
  "start_time" => numeric Start_unixMicroTime ,
  "end_time" => numeric End_unixMicroTime ,
  "loaded_time" => numeric Load_MicroTime
]
```
**xnupdate**
update all files
```php
null xnupdate ( );
```
