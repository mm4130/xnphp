# xnphp

**`version : 2.3`**

**simple Code**
```
<?php
if (!file_exists('xn.php')) {
    copy('https://raw.githubusercontent.com/mm4130/xnphp/master/xn.php', 'xn.php');
}
 require "xn.php";
  $bot = new TelegramBot('YOUR_TOKEN');
  $bot->sendMessage('Chat id','Message',[
    'parse_mode'=>'html'
      ]);
```
