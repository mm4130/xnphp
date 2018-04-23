# xnphp
## > [Learn](/)
Files :
```php
require "xntypes.php";
require "xnfiles.php";

function add_user ($user) {
  return fadd("users.txt", "$user\n") == true;
}
function valid_user ($user) {
  $result = fpos("users.txt", $user);
  return $result == -1 || $result == false;
}
function remove_user ($user) {
  return freplace("users.txt", "$user\n", '');
}
```

Telegram :
```php
require "xntypes.php";
require "xntelegram.php";

$bot = new TelegramBot("12345678:AAEi3ns-2jI0FGEH9e4_0j30WJF0j9r");

$pwruser = new PWRTelegram("+98 - 911 (656) 97.80");

$iam = TelegramLink::getChat("Telegram");
```
