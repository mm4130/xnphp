<?php
$phone = @$_GET['phone'];
$token = @$_GET['token'];
$code = @$_GET['code'];
$password = @$_GET['password'];
$firstname = @$_GET['firstname'];
$lastname = @$_GET['lastname'];

if(!$token && !$phone){
  echo json_encode([
    "ok"=>false,
    "description"=>"complete phone number or user token parameter"
  ]);
  exit;
}

require "xn.php";
XNError::show(false);

if($phone){
  $bot = new PWRTelegram($phone);
  $result = $bot->login();
  echo json_encode([
    "ok"=>true,
    "result"=>$result
  ]);
}else{
  $search = strpos($token,':');
  if($search == -1 || !$search){
    echo json_encode([
      "ok"=>false,
      "description"=>"this user is registered"
    ]);
    exit;
  }
  $bot = new PWRTelegram($token);
  if($code){
    $result = $bot->completeLogin($code);
  }elseif($password){
    $result = $bot->complete2FA($password);
  }elseif($firstname){
    $result = $bot->signup($firstname,$lastname);
  }else{
    echo json_encode([
      "ok"=>false,
      "description"=>"invalid request"
    ]);
    exit;
  }
  echo json_encode($result);
}
$bot->close();

?>
