# Files

**Read** :
fget : normally reading files or url
fgetjson : json reading
fgetprogress : progress downloading
fgetjsonprogress : progrees downloding and return json result
fsubget : substr get content (no limit memory)
mb_fsubget : mb_substr get content (no limit memory)
fexplode : explode content
fpos : get position str in file content (no limit memory)
mb_fpos : get mb position str in file content (no limit memory)
```php
string fget ( string File_name );
array|object fgetjson ( string File_name [, mixed Json_type = false ] );

string fgetprogress ( string File_name , function Callback ( string $data ) , integer Offset );
array|object fgetjsonprogress ( string File_name , function Callback ( string $data ) , integer Offset [, mixed Json_type = false ] );

string fsubget ( string File_name , numeric From [, numeric Length ] );
string mb_fsubget ( string File_name , numeric From [, numeric Length ] );

array fexplode ( string File_name , string Parse_by );

numeric fpos ( string File_name , string Search_query [, integer Offset ] );
numeric mb_fpos ( string File_name , string Search_query [, integer Offset ] );
```
**Write** :
fput : write content in file
fputjson : write json content in file
fadd : add content in file (no limit memory)
faddjson : add json content in file
fcopy : copy from file or url to file
freplace : replace file content (no limit memory)
```php
integer fput ( string File_name , string Content );
integer fputjson ( string File_name , mixed Content );

integer fadd ( string File_name , string Content );
integer faddjson ( string File_name , mixed Content );

integer fcopy ( string From_file , string To_file );

integer freaplce ( string File_name , string From_content , string To_content );
```
**Info** :


---
# Dirs


