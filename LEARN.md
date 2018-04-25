## **xn.php** > main file
```php
require "xn.php";
```
## **xnfiles.php** > working fast wich files
```php
require "xnfiles.php";
```
```php

string fget( string File_name ); // return file or url Contents

array||object fgetjson( string File_name [, mixed Json_type = false ] ); // return file or url Contents

integer[saved size] fput( string File_name , string Content ); // save contents in file

integer[saved size] fputjson( string File_name , mixed Content [, mixed Json_type = false ] ); // save contents in file

bool fcreate( string File_name ); // touch file

bool fvalid( string File_name ); // is valid file or url

bool fspeed( string File_name ); // open and close file or url - get speed

bool freplace( string File_name , string From_text , string To_text ); // replace in content file // no limit memory

integer||bool[false] fpos( string File_name , string Srarch_content [, Integer From_position = 0 ] ); // return position or -1 or false on file or url // not limit memory

integer[saved size] fadd( string File_name , string Content ); // add contents // no limit memory

bool fdel( string File_name ); // reomve file

bool fexists( string File_name ); // exists file

Numeric fsize( string File_name ); // file size

string fdir( string File_name ); // dir name

string fname( string File_name ); // file name

string fformat( string File_name ); // file format

integer fperms( string File_name ); // file perms

integer||bool[false]mb_fpos( string File_name , string Srarch_content [, integer From_position = 0 ] ); // get mb position // not limit memory

array fexplode( string File_name , string By_text ); // explode file content
@example :
$file_lines = fexplode( "users.txt" , "\n" );

bool foundurl( string URL_address ); // valid url address

string fsubget( string File_name , string From [, Numeric Length ] ); // substr file content

string mb_fsubget( string File_name , strinf From [, Numwric Length ] ); // substr file content

bool fcopy( string from , string to ); // copy file in file or url in file

string fgetprogress( string File_name , function Callback ( string $content ) , integer Limit ); // progressing get file
@example :
$size = fsize("http://google.com");
fgetprogress ( "http://google.com" , function ($data) {
  $downloaded = strlen($data);
  $progress = $downloaded/$size*100;
} , 100 );

array||object fgetjsonprogress ( string File_name , function Callback ( string $content ) , integer Limit ); // progressing get file
! return true in Callback to ended download in progressing fget

array dirfilesinfo( string Dir_name ); // return files info
@return 
[
  size => size all files ,
  file => coutn files
  folder => count folders
]
array dirread( string Dir_name ); // return dir files


```
