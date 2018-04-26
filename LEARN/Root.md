# Root

import files :
```php
require "xn.php";
```

or import on file (for example xnfile.php file) :
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
}```
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
  ZNError::show("error.log"); // show errors and save in file
  ```
  
  **handlr**
  set Handle for XNErrors
  ```php
  XNError::handlr( function( Object:XNError $error ){
  // Codes to run ...
  });
  ```
