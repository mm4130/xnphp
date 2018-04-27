# Files

**Read** :
* fget : normally reading files or url
* fgetjson : json reading
* fgetprogress : progress downloading
* fgetjsonprogress : progrees downloding and return json result
* fsubget : substr get content _(no limit memory)_
* mb_fsubget : mb_substr get content _(no limit memory)_
* fexplode : explode content
* fpos : get position str in file content _(no limit memory)_
* mb_fpos : get mb position str in file content _(no limit memory)_
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
* fput : write content in file
* fputjson : write json content in file
* fadd : add content in file _(no limit memory)_
* faddjson : add json content in file
* fcopy : copy from file or url to file
* freplace : replace file content _(no limit memory)_
```php
integer fput ( string File_name , string Content );
integer fputjson ( string File_name , mixed Content );

integer fadd ( string File_name , string Content );
integer faddjson ( string File_name , mixed Content );

integer fcopy ( string From_file , string To_file );

integer freaplce ( string File_name , string From_content , string To_content );
```

**Info** :
* fperms : file perms
* ftype : file type (dir/file)
* fsize : file size
* fspeed : open,close address (check speed)
* fexists : file exists
* fvalid : file or url valid
* urlfound : check valid url
* fdir : dir address
* fname : file address
* fformat : file format
```php
integer[4] fperms ( string File_name );
string ftype ( string File_name );
numeric fsize ( string File_name );
bool fspeed ( string File_name );
bool fexists ( string File_name );
bool fvalid ( string File_name );
bool urlfound ( string URLAddress );
string fdir ( string File_name );
string fname ( string File_name );
string fformat ( string File_name );
```

**Tool** :
* fdel : delete file
* fcreate : touch file
```php
bool fdel ( string File_name );
bool fcreate ( string File_name );
```

---
# Dirs

**Scan** :
* dirscan : get files list
* dirread : get files full list and Can use files tool
* dirsearch : search in files
* preg_dirsearch : search in files by regex
```php
array dirscan ( string Dir_address );
array dirread ( string Dir_address );
array dirsearch ( string Dir_address , string Search );
array preg_dirsearch ( string Dir_address , string Search );
```

**Info** :
* dirfilesinfo : sizes info
```php
array dirfilesinfo ( string Dir_address );
@return [
  "file" => integer File_count
  "folder" => integer Folder_count
  "size" => integer Size_files
]
```

**Tool** :
* dirdel : remove diractory and files in diractory
* dircopy : copy diractory and files in diractory
```php
bool dirdel ( string Dir_address );
bool dircopy ( string From_dir , string To_dir );
```
