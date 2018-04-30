# coding
** base10 **
* base10_encode : encode
* base10_decode : decode
```php
base10_encode ( string Str );
base10_decode ( string Str[base10] );
```

** base2 **
* base2_encode : encode _(not limit length MAX_INT)_
* base2_decode : decode _(not limit length MAX_INT)_
```php
base2_encode ( string Str );
base2_decode ( string Str[base2] );
```

** base Convert **
* baseconvert : convert string base
```php
baseconvert ( string Str , string From_base[chars] , string To_base[chars] );

@example : // from number to hex
baseconvert("57674237353","0123456789","0123456789ABCDEF");
```
