<?php

namespace xn\Telegram;

class PWRTelegram {
      // your token or NULL
  public $token,
      // your number or NULL
         $phone;
  
  public function __invoke($phone = '') {
    $phone = str_replace(['+', ' ', '(', ')', '.', ','], '', $phone);
    if (is_numeric($phone)) $this->phone = $phone;
    else $this->token = $phone;
  }
  public function checkAPI() {
    $f = @fopen("https://api.pwrtelegram.xyz", 'r');
    if (!$f) return false;
    fclose($f);
    return true;
  }
  public function __construct($phone = '') {
    $phone = str_replace(['+', ' ', '(', ')', '.', ','], '', $phone);
    if (is_numeric($phone)) $this->phone = $phone;
    else $this->token = $phone;
  }
  public function request($method, $args = [], $level = 2) {
    if (@$this->token) {
      if ($level == 1) {
        $r = @fclose(@fopen("https://api.pwrtelegram.xyz/user$this->token/$method?" . http_build_query($args) , "r"));
      }
      elseif ($level == 2) {
        $ch = curl_init("https://api.pwrtelegram.xyz/user$this->token/$method");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $args);
        $r = json_decode(curl_exec($ch));
        curl_close($ch);
      }
      elseif ($level == 3) {
        $r = json_decode(file_get_contents("https://api.pwrtelegram.xyz/user$this->token/$method?" . http_build_query($args)));
      }
      else {
        new XNError("PWRTelegram", "invalid level type", 1);
        return false;
      }
      if ($r === false) return false;
      if ($r === true) return true;
      if ($r === null) {
        new XNError("PWRTelegram", "PWRTelegram api is offlined", 1);
        return null;
      }
      if (!$r->ok) {
        new XNError("PWRTelegram", "$r->description [$r->error_code]", 1);
        return $r;
      }
      return $r;
    }
    if ($level == 1) {
      $r = @fclose(@fopen("https://api.pwrtelegram.xyz/$method?" . http_build_query($args) , "r"));
    }
    elseif ($level == 2) {
      $ch = curl_init("https://api.pwrtelegram.xyz/$method");
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $args);
      $r = json_decode(curl_exec($ch));
      curl_close($ch);
    }
    elseif ($level == 3) {
      $r = json_decode(file_get_contents("https://api.pwrtelegram.xyz/$method?" . http_build_query($args)));
    }
    else {
      new XNError("PWRTelegram", "invalid level type", 1);
      return false;
    }
    if ($r === false) return false;
    if ($r === true) return true;
    if ($r === null) {
      new XNError("PWRTelegram", "PWRTelegram api is offlined", 1);
      return null;
    }
    if (!$r->ok) {
      new XNError("PWRTelegram", "$r->description [$r->error_code]", 1);
      return $r;
    }
    return $r;
  }
  public function login($level = 2) {
    $r = $this->request("phonelogin", ["phone" => $this->phone], $level);
    $this->token = $r->result;
    return $r;
  }
  public function completeLogin($pass, $level = 2) {
    $res = $this->request("completephonelogin", ["code" => $pass], $level);
    if ($res->ok) $this->token = $res->result;
    return $res;
  }
  public function complete2FA($pass, $level = 2) {
    $res = $this->request("complete2FALogin", ["password" => $pass], $level);
    if ($res->ok) $this->token = $res->result;
    return $res;
  }
  public function signup($first, $last = '', $level = 2) {
    $res = $this->request("completesignup", $last ? [
      "first_name" => $first,
      "last_name"  => $last
    ] : [
      "first_name" => $first
    ], $level);
    if ($res->ok) $this->token = $res->result;
    return $res;
  }
  public function fullLogin($args = [], $level = 2) {
    if (!$this->token) return $this->login($level);
    if (!isset($args['code'])) return false;
    $res = $this->completeLogin($args['code'], $level);
    if ($res->ok) return $res;
    if (strpos($res->description, "2FA is enabled: call the complete2FALogin method with the password as password parameter") === 0) {
      if (!isset($args['password'])) return false;
      return $this->complete2FA($args['password'], $level);
    }
    if ($res->description == "Need to sign up: call the completesignup method") {
      if (!isset($args['first_name'])) return false;
      if (!isset($args['last_name'])) $args['last_name'] = '';
      return $this->signup($args['first_name'], $args['last_name'], $level);
    }
    return $res;
  }
  public function getChat($chat) {
    return $this->request("getChat", ["chat_id" => $chat]);
  }
  // messages.*
  public function messagesRequest($method, $args = [], $level = 2) {
    return $this->request("messages.$method", $args, $level);
  }
  // auth.*
  public function authRequest($method, $args = [], $level = 2) {
    return $this->request("auth.$method", $args, $level);
  }
  // account.*
  public function accountRequest($method, $args = [], $level = 2) {
    return $this->request("account.$method", $args, $level);
  }
  // channels.*
  public function channelsRequest($method, $args = [], $level = 2) {
    return $this->request("channels.$method", $args, $level);
  }
  // help.*
  public function helpRequest($method, $args = [], $level = 2) {
    return $this->request("help.$method", $args, $level);
  }
  // contacts.*
  public function contactsRequest($method, $args = [], $level = 2) {
    return $this->request("contacts.$method", $args, $level);
  }
  // phone.*
  public function phoneRequest($method, $args = [], $level = 2) {
    return $this->request("phone.$method", $args, $level);
  }
  // photos.*
  public function photosRequest($method, $args = [], $level = 2) {
    return $this->request("photos.$method", $args, $level);
  }
  // stickers.*
  public function stickersRequest($method, $args = [], $level = 2) {
    return $this->request("stickers.$method", $args, $level);
  }
  // payments.*
  public function paymentsRequest($method, $args = [], $level = 2) {
    return $this->request("payments.$method", $args, $level);
  }
  // upload.*
  public function uploadRequest($method, $args = [], $level = 2) {
    return $this->request("upload.$method", $args, $level);
  }
  // users.*
  public function usersRequest($method, $args = [], $level = 2) {
    return $this->request("users.$method", $args, $level);
  }
  // langpack.*
  public function langpackRequest($method, $args = [], $level = 2) {
    return $this->request("langpack.$method", $args, $level);
  }
  public function getUpdates($offset = - 1, $limit = 1, $timeout = 0) {
    return (array)$this->request("getUpdates", [
      "offset"  => $offset,
      "limit"   => $limit,
      "timeout" => $timeout
    ]);
  }
  public function LastUpdate() {
    return $this->getUpdates(-1, 1, 0);
  }
  public function readUpdates($func, $while = 0, $limit = 1, $timeout = 0) {
    if ($while == 0) $while = - 1;
    $offset = 0;
    while ($while > 0 || $while < 0) {
      $updates = $this->getUpdates($offset, $limit, $timeout) ['result'];
      foreach($updates as $update) {
        $offset = $update->update_id + 1;
        if ($func($update)) return true;
      }
      $while--;
    }
  }
  // messages.installStickerSet
  public function installStickerSet($stickerset, $archived = false, $level = 2) {
    return $this->messagesRequest("installStickerSet", [
      "stickerset" => $stickerset,
      "archived"   => $archived
    ], $level);
  }
  // channels.inviteToChannel
  public function inviteToChannel($channel, $users, $level = 2) {
    return $this->channelsRequest("inviteToChannel", [
      "channel" => $channel,
      "users"   => $users
    ], $level);
  }
  // contacts.block
  public function block($user, $level = 2) {
    return $this->contactsRequest("block", ["id" => $user], $level);
  }
  // messages.setTyping
  public function sendAction($user, $action = "typing", $level = 2) {
    return $this->messagesRequest("setTyping", [
      "peer"   => $user,
      "action" => $action
    ], $level);
  }
  // messages.getMessageEditData
  public function getMessageEditData($peer, $id, $level = 2) {
    return $this->messagesRequest("getMessageEditData", [
      "peer" => $peer,
      "id"   => $id
    ], $level);
  }
  // messages.checkChatInvite
  public function checkChatInvite($hash, $level = 2) {
    return $this->messagesRequest("checkChatInvite", ["hash" => $hash], $level);
  }
  // auth.checkPhone
  public function checkPhone($phone, $level = 2) {
    return $this->authRequest("checkPhone", ["phone_number" => $phone], $level);
  }
  // account.checkUsername
  public function availableUsername($username, $level = 2) {
    return $this->accountRequest("checkUsername", ["username" => $username], $level);
  }
  // channels.checkUsername
  public function checkUsername($channel, $username, $level = 2) {
    return $this->channelsRequest("checkUsername", [
      "channel"  => $channel,
      "username" => $username
    ], $level);
  }
  // messages.createChat
  public function createChat($title, $userns, $level = 2) {
    return $this->messagesRequest("createChat", [
      "users" => $users,
      "title" => $title
    ], $level);
  }
  // channels.createChannel
  public function createChannel($title, $args = [], $level = 2) {
    $result = ["title" => $title];
    $result['about'] = isset($args['about']) ? $args['about'] : '';
    if (isset($args['broadcast'])) $result['broadcast'] = $args['broadcast'];
    if (isset($args['megagroup'])) $result['megagroup'] = $args['megagroup'];
    return $this->channelsRequest("createChannel", $result, $level);
  }
  // channels.deleteChannel
  public function deleteChannel($channel, $level = 2) {
    return $this->channelsRequest("deleteChannel", ["channel" => $channel], $level);
  }
  // contacts.deleteContact
  public function deleteContact($id, $level = 2) {
    return $this->contactsRequest("deleteContact", ["id" => $id], $level);
  }
  // channels.deleteMessages
  public function deleteMessages($channel, $ids, $level = 2) {
    if ($channel === true || $channel === false) return $this->messagesRequest("deleteMessages", ["revoke" => $channel, "id" => json_encode($ids) ], $level);
    return $this->channelsRequest("deleteMessages", [
      "channel" => $channel,
      "id"      => json_encode($ids)
    ], $level);
  }
  // contacts.unblock
  public function unblock($user, $level = 2) {
    return $this->contactsRequest("unblock", ["id" => $user], $level);
  }
  // messages.forwardNessage
  public function forwardMessage($from, $to, $id, $args = [], $level = 2) {
    $args['from_peer'] = $from;
    $args['to_peer'] = $to;
    $args['id'] = $id;
    return $this->messagesRequest("forwardMessage", $args, $level);
  }
  // channels/exportInvite
  public function exportInvite($channel, $level = 2) {
    return $this->channelsRequest("exportInvite", ["channel" => $channel], $level);
  }
  // messages.exportChatInvite
  public function exportChatInvite($chat, $level = 2) {
    return $this->messagesRequest("exportChatInvite", ["chat_id" => $chat], $level);
  }
  // messages.getStickerSet
  public function getStickerSet($stickerset, $level = 2) {
    return $this->messagesRequest("getStickerSet", ["stickerset" => $stickerset], $level);
  }
  // contacts.exportCard
  public function exportCard($level = 2) {
    return $this->contactsRequest("exportCard", [], $level);
  }
  // channels.editTitle
  public function editTitle($channel, $title, $level = 2) {
    return $this->channelsRequest("editTitle", [
      "channel" => $channel,
      "title"   => $title
    ], $level);
  }
  // messages.editChatTitle
  public function editChatTitle($chat, $title, $level = 2) {
    return $this->messagesRequest("editChatTitle", [
      "chat_id" => $chat,
      "title"   => $title
    ], $level);
  }
  // channels.editAbout
  public function editAbout($channel, $about, $level = 2) {
    return $this->channelsRequest("editAbout", [
      "channel" => $channel,
      "about" => $about
    ], $level);
  }
  // contacts.deleteContacts
  public function deleteContacts($id, $level = 2) {
    return $this->contactsRequest("deleteContacts", ["id" => $id], $level);
  }
  // messages.getAllChats
  public function getAllChats($id, $level = 3) {
    return $this->messagesRequest("getAllChats", ["except_ids" => $id], $level);
  }
  // messaegs.getAllStickers
  public function getAllStickers($hash, $level = 3) {
    return $this->messagesRequest("getAllStickers", ["hash" => $hash], $level);
  }
  // messages.getPeerDialogs
  public function getPeerDialogs($peers, $level = 3) {
    return $this->messagesRequest("getPeerDialogs", ["peers" => $peers], $level);
  }
  // messages.getGameHighScores
  public function getGameHighScores($peer, $id, $user, $level = 2) {
    return $this->messagesRequest("getGameHighScores", [
      "peer"    => $peer,
      "id"      => $id,
      "user_id" => $user
    ], $level);
  }
  // help.getAppUpdate
  public function getAppUpdate($level = 2) {
    return $this->helpRequest("getAppUpdate", [], $level);
  }
  // messages.getChats
  public function getChats($id, $level = 2) {
    return $this->messagesRequest("getChats", ["id" => $id], $level);
  }
  // users.getUsers
  public function getUsers($id, $level = 2) {
    return $this->usersRequest("getUsers", ["id" => $id], $level);
  }
  // channels.getChannels
  public function getChannels($id, $level = 2) {
    return $this->channelsRequest("getChannels", ["id" => $id], $level);
  }
  // help.getSupport
  public function getSupport($level = 2) {
    return $this->helpRequest("getSupport", [], $level);
  }
  // langpack.getDiggerence
  public function getDifference($from, $level = 2) {
    return $this->langpackRequest("getDifference", [], $level);
  }
  // messages.sendMessage
  public function sendMessage($peer, $message, $args = [], $level = 2) {
    $args['peer'] = $peer;
    $args['message'] = $message;
    return $this->messagesRequest("sendMessage", $args, $level);
  }
  public function contactsSearch($q, $limit = 0, $level = 2) {
    return $this->contactsRequest("search", [
      "q"     => $q,
      "limit" => $limit
    ], $level);
  }
  public function searchGlobal($q, $date = 0, $peer = 0, $id = 0, $limit = 0, $level = 2) {
    return $this->messagesRequest("searchGlobal", [
      "q"           => $q,
      "offset_date" => $date,
      "offset_peer" => $peer,
      "offset_id"   => $id,
      "limit"       => $limit
    ], $level);
  }
  public function resetAuthorizations($level = 2) {
    return $this > authRequest("resetAuthorizations", [], $level);
  }
  public function deleteUserHistory($args = [], $level = 2) {
    if (!is_array($args)) $args = ["channel" => $args];
    return $this->channelsRequest("deleteUserHistory", $args, $level);
  }
  public function dropTempAuthKeys($keys, $level = 2) {
    return $this->authRequest("dropTempAuthKeys", ["except_auth_keys" => $keys], $level);
  }
  public function deleteHistory($args = [], $level = 2) {
    return $this->messagesRequest("deleteHistory", $args, $level);
  }
  public function deleteAccount($reason, $level = 2) {
    return $this->accountRequest("deleteAccount", ["reason" => $reason], $level);
  }
  public function updateDeviceLocked($period, $level = 2) {
    return $this->accountRequest("updateDeviceLocked", ["period" => $period], $level);
  }
  public function getWebFile($location, $offset, $limit, $level = 2) {
    return $this->uploadRequest("getWebFile", [
      "location" => $location,
      "offset"   => $offset,
      "limit"    => $limit
    ], $level);
  }
  public function editMessage($peer, $id, $args = [], $level = 2) {
    $args['peer'] = $peer;
    $args['id'] = $id;
    return $this->messagesRequest("editMessage", $args, $level);
  }
  public function editAdmin($channel, $user, $admin, $level = 2) {
    return $this->channelsRequest("editAdmin", [
      "user_id"      => $user,
      "channel"      => $channel,
      "admin_rights" => $admin
    ], $level);
  }
  public function editChatAdmin($chat, $user, $admin, $level = 2) {
    return $this->messagesRequest("editChatAdmin", [
      "chat_id"  => $chat,
      "user_id"  => $user,
      "is_admin" => $admin
    ], $level);
  }
  public function editChatPhoto($chat, $photo, $level = 2) {
    return $this->messagesRequest("editChatPhoto", [
      "chat_id" => $chat,
      "photo"   => $photo
    ], $level);
  }
  public function toggleChatAdmins($chat, $enabled = true, $level = 2) {
    return $this->messagesRequest("toggleChatAdmins", [
      "chat_id" => $chat,
      "enabled" => $enabled
    ], $level);
  }
  public function togglePreHistoryHidden($channel, $enabled = true, $level = 2) {
    return $this->channelsRequest("togglePreHistoryHidden", [
      "channel" => $channel,
      "enabled" => $enabled
    ], $level);
  }
  public function getCdnConfig($level = 2) {
    return $this->helpRequest("getCdnConfig", [], $level);
  }
  public function getAccountTTL($level = 2) {
    return $this->accountRequest("getAccountTTL", [], $level);
  }
  public function getAdminLog($q, $args = [], $level = 2) {
    $args['q'] = $q;
    return $this->channelsRequest("getAdminLog", $args, $level);
  }
  public function getArchivedStickers($offset, $limit, $masks = false, $level = 2) {
    return $this->messagesRequest("getArchivedStickers", [
      "offset_id" => $offset,
      "limit"     => $limit,
      "mask"      => $mask
    ], $level);
  }
  public function getAuthorizations($level = 2) {
    return $this->accountRequest("getAuthorizations", [], $level);
  }
  public function getAllDrafts($level = 2) {
    return $this->messagesRequest("getAllDrafts", [], $level);
  }
  public function getAdminedPublicChannels($level = 2) {
    return $this->channelsRequest("getAdminedPublicChannels", [], $level);
  }
  public function getMessagesViews($peer, $id, $increment = false, $level = 2) {
    return $this->messagesRequest("getMessagesViews", [
      "peer"      => $peer,
      "id"        => $id,
      "increment" => $increment
    ], $level);
  }
  public function getLanguages($level = 2) {
    return $this->langpackRequest("getLanguages", [], $level);
  }
  public function getBlocked($offset, $limit, $level = 2) {
    return $this->contactsRequest("getBlocked", [
      "offset" => $offset,
      "limit"  => $limit
    ], $level);
  }
  public function getParticipants($offset, $limit, $hash, $filter, $channel = false, $level = 2) {
    return $this->channelsRequest("getParticipants", $channel ? [
      "offset"  => $offset,
      "limit"   => $limit,
      "hash"    => $hash,
      "filter"  => $filter,
      "channel" => $channel
    ] : [
      "offset" => $offset,
      "limit"  => $limit,
      "hash"   => $hash,
      "filter" => $filter
    ], $level);
  }
  public function getCallConfig($level = 2) {
    return $this->phoneRequest("getCallConfig", [], $level);
  }
  public function getCommonChats($max, $limit, $user = false, $level = 2) {
    return $this->messagesRequest("getCommonChats", $user ? [
      "max_id"  => $max,
      "limit"   => $limit,
      "user_id" => $user
    ] : [
      "max_id" => $max,
      "limit"  => $limit
    ], $level);
  }
  public function getDocumentByHash($hash, $size, $mime, $level = 2) {
    return $this->messagesRequest("getDocumentByHash", [
      "sha256"    => $hash,
      "size"      => $size,
      "mime_type" => $mime
    ], $level);
  }
  public function getInlineGameHighScores($user, $id, $level = 2) {
    return $this->messagesRequest("getInlineGameHighScores", [
      "user_id" => $usre,
      "id"      => $id
    ], $level);
  }
  public function getInviteText($level = 2) {
    return $this->helpRequest("getInviteText", [], $level);
  }
  public function getStrings($lang, $keys, $level = 2) {
    return $this->langpackRequest("getStrings", [
      "lang_code" => $lang,
      "keys"      => $keys
    ], $level);
  }
  public function getLangPack($lang, $level = 2) {
    return $this->langpackRequest("getLangPack", ["lang_code" => $lang], $level);
  }
  public function getTopPeers($offset, $limit, $hash, $args = [], $level = 2) {
    $args['offset'] = $oggset;
    $args['limit']  = $limit;
    $args['hash']   = $hash;
    return $this->contactsRequest("getTopPeers", $args, $level);
  }
  public function getNearestDc($level = 2) {
    return $this->helpRequest("getNearestDc", [], $level);
  }
  public function getStatuses($level = 2) {
    return $this->contactsRequest("getStatuses", [], $level);
  }
  public function getNotifySettings($peer, $level = 2) {
    return $this->accountRequest("getNotifySettings", ["peer" => $peer], $level);
  }
  public function getPinnedDialogs($level = 2) {
    return $this->messagesRequest("getPinnedDialogs", [], $level = 2);
  }
  public function getHistory($ofid, $ofdate, $ofadd, $limit, $maxid, $minid, $hash, $peer = false, $level = 2) {
    $args = [
      "offset_id"   => $ofid,
      "offset_date" => $ofdate,
      "add_offset"  => $ofadd,
      "limit"       => $limit,
      "max_id"      => $maxid,
      "min_id"      => $minid,
      "hash"        => $hash
    ];
    if ($peer) $args['peer'] = $peer;
    return $this->messagesRequest("getHistory", $args, $level);
  }
  public function getPrivacy($key, $level = 2) {
    return $this->accountRequest("getPrivacy", ["key" => $key], $level);
  }
  public function updateStatus($offline = true, $level = 2) {
    return $this->accountRequest("updateStatus", ["offline" => $offline], $level);
  }
  public function offline($level = 2) {
    return $this->updateStatus(true, $level);
  }
  public function online($level = 2) {
    return $this->updateStatus(false, $level);
  }
  public function changeUsername($channel, $username, $level = 2) {
    return $this->channelsRequest("updateUsername", [
      "channel"  => $channel,
      "username" => $username
    ], $level);
  }
  public function updateUsername($username, $level = 2) {
    return $this->accountRequest("updateUsername", ["username" => $username], $level);
  }
  public function updatePasswordSettings($hash, $setting, $level = 2) {
    return $this->accountRequest("updatePasswordSettings", [
      "current_password_hash" => $hash,
      "new_settings"          => $setting
    ], $level);
  }
  public function getPassword($level = 2) {
    return $this->accountRequest("getPassword", [], $level);
  }
  public function getPasswordSettings($hash, $level = 2) {
    return $this->accountRequest("getPasswordSettings", ["current_password_hash" => $hash], $level);
  }
  public function passwordSettings($email, $level = 2) {
    return $this->accountRequest("passwordSettings", ["email" => $email], $level);
  }
  public function sendChangePhoneCode($phone, $args = [], $level = 2) {
    $args['phone_number'] = $phone;
    return $this->accountRequest("sendChangePhoneCode", $args, $level);
  }
  public function changePhone($phone, $code, $hash, $level = 2) {
    return $this->accountRequest("changePhone", [
      "phone_number"    => $phone,
      "phone_code_hash" => $hash,
      "phone_code"      => $code
    ], $level);
  }
  public function faveSticker($unfave, $id = false, $level = 2) {
    return $this->messagesRequest("faveSticker", $id ? ["unfave" => $unfave, "id" => $id] : ["unfave" => $unfave], $level);
  }
  public function addChatUser($chat, $user, $fwd, $level = 2) {
    return $this->messagesRequest("addChatUser", [
      "chat_id"   => $chat,
      "user_id"   => $user,
      "fwd_limit" => $fwd
    ], $level);
  }
  public function saveRecentSticker($unsave, $args = [], $level = 2) {
    $args['unsave'] = $unsave;
    return $this->messagesRequest("saveRecentSticker", $args, $level);
  }
  public function addStickerToSet($stickerset, $sticker, $level = 2) {
    return $this->stickersRequest("addStickerToSet", [
      "stickerset" => $stickerset,
      "sticker"    => $sticker
    ], $result);
  }
  public function toggleInvites($channel, $enabled, $level = 2) {
    return $this->channelsRequest("toggleInvites", [
      "channel" => $channel,
      "enabled" => $enabled
    ], $level);
  }
  public function changeStickerPosition($sticker, $pos, $level = 2) {
    return $this->stickersRequest("changeStickerPosition", [
      "sticker"  => $sticker,
      "position" => $pos
    ], $level);
  }
  public function resetWebAuthorization($hash, $level = 2) {
    return $this->accountRequest("resetWebAuthorization", ["hash" => $hash], $level);
  }
  public function getFavedStickers($hash = 0, $level = 2) {
    return $this->messagesRequest("getFavedStickers", ["hash" => $hash], $level);
  }
  public function getFeaturedStickers($hash = 0, $level = 2) {
    return $this->messagesRequest("getFeaturedStickers", ["hash" => $hash], $level);
  }
  public function getRecentLocations($peer, $limit, $level = 2) {
    return $this->messagesRequest("getRecentLocations", [
      "peer"  => $peer,
      "limit" => $limit
    ], $level);
  }
  public function getRecentStickers($hash, $att = false, $level = 2) {
    return $this->messagesRequest("getRecentStickers", [
      "hash"     => $hash,
      "attached" => $att
    ], $level);
  }
  public function getRecentMeUrls($referer, $level = 2) {
    return $this->helpRequest("getRecentMeUrls", ["referer" => $referer], $level);
  }
  public function getSavedGifs($hash = 0, $level = 2) {
    return $this->messagesRequest("getSavedGifs", ["hash" => $hash], $level);
  }
  public function getConfig($level = 2) {
    return $this->helpRequest("getConfig", [], $level);
  }
  public function getAttachedStickers($media, $level = 2) {
    return $this->messagesRequest("getAttachedStickers", ["media" => $media], $level);
  }
  public function getWebAuthorizations($level = 2) {
    return $this->accountRequest("getWebAuthorizations", [], $level);
  }
  public function getTmpPassword($hash, $per, $level = 2) {
    return $this->accountRequest("getTmpPassword", [
      "hash"   => $hash,
      "period" => $per
    ], $level);
  }
  public function getTermsOfService($level = 2) {
    return $this->helpRequest("getTermsOfService", [], $level);
  }
  public function getBotCallbackAnswer($id, $args = [], $level = 2) {
    $args['msg_id'] = $id;
    return $this->messagesRequest("getBotCallbackAnswer", $args, $level);
  }
  public function getAppChangelog($x, $level = 2) {
    return $this->helpRequest("getAppChangelog", ["prev_app_version" => $x], $level);
  }
  public function exportMessageLink($id, $grouped, $channel = false, $level = 2) {
    return $this->channelsRequest("exportMessageLink", $channel ? [
      "id" => $id,
      "grouped" => $grouped,
      "channel" => $channel
    ] : [
      "id"      => $id,
      "grouped" => $grouped
    ], $level);
  }
  public function getUserPhotos($user, $offset, $max, $limit, $level = 2) {
    return $this->photosRequest("getUserPhotos", [
      "user_id" => $user,
      "offset"  => $offset,
      "max_id"  => $max,
      "limit"   => $limit
    ], $level);
  }
  public function getPeerSettings($peer = false, $level = 2) {
    return $this->messagesRequest("getPeerSettings", ["peer" => $peer], $level);
  }
  public function getUnreadMentions($peer, $ofid, $ofadd, $limit, $maxid, $minid, $level = 2) {
    return $this->messagesRequest("getUnreadMentions", [
      "peer"       => $peer,
      "offset_id"  => $ofid,
      "add_offset" => $ofadd,
      "limit"      => $limit,
      "max_id"     => $maxid,
      "min_id"     => $minid
    ], $level);
  }
  public function getWebPage($url, $hash = 0, $level = 2) {
    return $this->messagesRequest("getWebPage", [
      "url"  => $url,
      "hash" => $hash
    ], $level);
  }
  public function getWebPagePreview($message, $args = [], $level = 2) {
    $args['message'] = $message;
    return $this->messagesRequest("getWebPagePreview", $args, $level);
  }
  public function getDialogs($ofdate, $ofid, $limit, $args = [], $level = 2) {
    $args['offset_date'] = $ofdate;
    $args['offset_id']   = $ofid;
    $args['limit']       = $limit;
    return $this->messagesRequest("getDialogs", $args, $level);
  }
  public function hideReportSpam($peer, $level = 2) {
    return $this->messagesRequest("hideReportSpam", ["peer" => $peer], $level);
  }
  public function importCard($card, $level = 2) {
    return $this->contactsRequest("importCard", ["export_card" => $card], $level);
  }
  public function importChatInvite($hash, $level = 2) {
    return $this->messagesRequest("importChatInvite", ["hash" => $hash], $level);
  }
  public function initConnection($args = [], $level = 2) {
    return $this->request("initConnection", $args, $level);
  }
  public function cancelCode($number, $hash, $level = 2) {
    return $this->authRequest("cancelCode", [
      "phone_number"    => $number,
      "phone_code_hash" => $hash
    ], $level);
  }
  public function sendInvites($number, $message, $level = 2) {
    return $this->authRequest("sendInvites", [
      "phone_number" => $number,
      "message"      => $message
    ], $level);
  }
  public function invokeWithLayer($layer, $query, $level = 2) {
    return $this->request("invokeWithLayer", [
      "layer" => $layer,
      "query" => $query
    ], $level);
  }
  public function invokeWithoutUpdates($query, $level = 2) {
    return $this->request("invokeWithoutUpdates", ["query" => $query], $level);
  }
  public function invokeAfterMsg($id, $query, $level = 2) {
    return $this->request("invokeAfterMsg", [
      "msg_id" => $id,
      "query"  => $query
    ], $level);
  }
  public function joinChannel($channel, $level = 2) {
    return $this->channelsRequest("joinChannel", ["channel" => $channel], $level);
  }
  public function editBanned($channel, $user, $banned = true, $level = 2) {
    return $this->channelsRequest("editBanned", [
      "channe"        => $channel,
      "user_id"       => $user,
      "banned_rights" => $banned
    ], $level);
  }
  public function leaveChannel($channel, $level = 2) {
    return $this->channelsRequest("leaveChannel", ["channel" => $channel], $level);
  }
  public function saveAppLog($events, $level = 2) {
    return $this->helpRequest("saveAppLog", ["events" => $events], $level);
  }
  public function readHistory($channel, $max, $level = 2) {
    return $this->channelsRequest("readHistory", [
      "channel" => $channel,
      "max_id"  => $max
    ], $level);
  }
  public function readMessageContents($channel, $id, $level = 2) {
    return $this->channelsRequest("readMessageContents", [
      "channel" => $channel,
      "id"      => $id
    ], $level);
  }
  public function readMentions($peer, $level = 2) {
    return $this->messagesRequest("readMentions", ["peer" => $peer], $level);
  }
  public function updateProfile($args = [], $level = 2) {
    return $this->accountRequest("updateProfile", $args, $level);
  }
  public function startBot($peer = false, $bot, $start = false, $level = 2) {
    return $this->messagesRequest("startBot", $peer ? [
      "bot"         => $bot,
      "peer"        => $peer,
      "start_param" => $start
    ] : [
      "bot"         => $bot,
      "start_param" => $start
    ], $level);
  }
  public function readEncryptedHistory($peer, $max, $level = 2) {
    return $this->messagesRequest("readEncryptedHistory", [
      "peer"   => $peer,
      "max_id" => $max
    ], $level);
  }
  public function readChatHistory($peer, $max, $level = 2) {
    return $this->messagesRequest("readHistory", [
      "peer"   => $peer,
      "max_id" => $max
    ], $level);
  }
  public function receivedMessages($max, $level = 2) {
    return $this->messagesRequest("receivedMessages", ["max_id" => $max], $level);
  }
  public function readFeaturedStickers($id, $level = 2) {
    return $this->messagesRequest("readFeaturedStickers", ["id" => $id], $level);
  }
  public function receivedCall($peer, $level = 2) {
    return $this->phoneRequest("receivedCall", ["peer" => $peer], $level);
  }
  public function toggleDialogPin($peer, $pin = null, $level = 2) {
    return $this->messagesRequest("toggleDialogPin", $pin !== null ? [
      "peer" => $peer,
      "pin"  => $pin
    ] : [
      "peer" => $peer
    ], $level);
  }
  public function registerDevice($type, $token, $app, $other, $level = 2) {
    return $this->accountRequest("registerDevice", [
      "token_type"  => $type,
      "token"       => $token,
      "app_sendbox" => $app,
      "other_uids"  => $other
    ], $level);
  }
  public function uninstallStickerSet($stikerset, $level = 2) {
    return $this->messagesRequest("uninstallStickerSet", ["stickerset" => $stickerset], $level);
  }
  public function removeStickerFromSet($sticker, $level = 2) {
    return $this->stickersRequest("removeStickerFromSet", ["sticker" => $sticker], $level);
  }
  public function reorderPinnedDialogs($order, $force = null, $level = 2) {
    return $this->messagesRequest("reorderPinnedDialogs", $force !== null ? [
      "order" => $order,
      "force" => $force
    ] : [
      "order" => $order
    ], $level);
  }
  public function reorderStickerSets($order, $masks = null, $level = 2) {
    return $this->messagesRequest("reorderStickerSets", $masks !== null ? [
      "order" => $order,
      "force" => $masks
    ] : [
      "order" => $order
    ], $level);
  }
  public function reportSpamChannel($channel, $user = false, $id, $level = 2) {
    return $this->channelsRequest("reportSpam", $user ? [
      "channel" => $channel,
      "user_id" => $user,
      "id" => $id
    ] : [
      "channel" => $channel,
      "id" => $id
    ], $level);
  }
  public function reportSpam($peer, $level = 2) {
    return $this->messagesRequest("reportSpam", ["peer" => $peer], $level);
  }
  public function resendCode($phone, $hash, $level = 2) {
    return $this->authRequest("resendCode", [
      "phone_number"    => $phone,
      "phone_code_hash" => $hash
    ], $level);
  }
  public function reportEncryptedSpam($peer, $level = 2) {
    return $this->messagesRequest("reportEncryptedSpam", ["peer" => $peer], $level);
  }
  public function reportPeer($peer, $reason, $level = 2) {
    return $this->accountRequest("reportPeer", [
      "peer"   => $peer,
      "reason" => $reason
    ], $level);
  }
  public function resetNotifySettings($level = 2) {
    return $this->accountRequest("resetNotifySettings", [], $level);
  }
  public function resetWebAuthorizations($level = 2) {
    return $this - accountRequest("resetWebAuthorizations", [], $level);
  }
  public function resetSaved($level = 2) {
    return $this->contactsRequest("resetSaved", [], $level);
  }
  public function resetTopPeerRating($category, $peer, $level = 2) {
    return $this->contactsRequest("resetTopPeerRating", [
      "category" => $category,
      "peer"     => $peer
    ], $level);
  }
  public function invokeAfterMsgs($msg, $query, $level = 2) {
    return $this->request("invokeAfterMsgs", [
      "msg_ids" => $msg,
      "query"   => $query
    ], $level);
  }
  public function getWallPapers($level = 2) {
    return $this->accountRequest("getWallPapers", [], $level);
  }
  public function saveGif($id, $level = 2) {
    return $this->messagesRequest("saveGif", [
      "id"     => $id,
      "unsave" => false
    ], $level);
  }
  public function unsaveGif($id, $level = 2) {
    return $this->messagesRequest("saveGif", [
      "id"     => $id,
      "unsave" => true
    ], $level);
  }
  public function saveDraft($peer, $message, $args = [], $level = 2) {
    $args['peer'] = $peer;
    $args['message'] = $message;
    return $this->messagesRequest("saveDraft", $args, $level);
  }
  public function saveCallDebug($peer, $debug, $level = 2) {
    return $this->phoneRequest("saveCallDebug", [
      "peer"  => $peer,
      "debug" => $debug
    ], $level);
  }
  public function sendEncryptedFile($peer, $message, $file, $level = 2) {
    return $this->messagesRequest("sendEncryptedFile", [
      "peer"    => $peer,
      "message" => $message,
      "file"    => $file
    ], $level);
  }
  public function sendMedia($peer, $media, $args = [], $level = 2) {
    if (!isset($args['message'])) $args['message'] = '';
    return $this->messagesRequest("sendMedia", $args, $level);
  }
  public function sendEncryptedService($peer, $message, $level = 2) {
    return $this->messagesRequest("sendEncryptedService", [
      "peer"    => $peer,
      "message" => $message
    ], $level);
  }
  public function sendMultiMedia($peer, $media, $args = [], $level = 2) {
    $args['peer'] = $peer;
    $args['multi_media'] = $media;
    return $this->messagesRequest("sendMultiMedia", $args, $level);
  }
  public function requestPasswordRecovery($level = 2) {
    return $this->authRequest("requestPasswordRecovery", [], $level);
  }
  public function sendConfirmPhoneCode($hash, $allow = false, $current = false, $level = 2) {
    return $this->accountRequest("sendConfirmPhoneCode", [
      "hash"            => $hash,
      "allow_flashcall" => $allow,
      "current_number"  => $current
    ], $level);
  }
  public function sendEncrypted($peer, $message, $level = 2) {
    return $this->messagesRequest("sendEncrypted", [
      "peer"    => $peer,
      "message" => $message
    ], $level);
  }
  public function sendScreenshotNotification($peer, $reply, $level = 2) {
    return $this->messagesRequest("sendScreenshotNotification", [
      "peer"            => $peer,
      "reply_to_msg_id" => $reply
    ], $level);
  }
  public function setEncryptedTyping($peer, $typing = true, $level = 2) {
    return $this->messagesRequest("setEncryptedTyping", [
      "peer"   => $peer,
      "typing" => $typing
    ], $level);
  }
  public function setAccountTTL($ttl, $level = 2) {
    return $this->accountRequest("setAccountTTL", ["ttl" => $ttl], $level);
  }
  public function setCallRating($peer, $rating, $comment, $level = 2) {
    return $this->phoneRequest("setCallRating", [
      "peer"    => $peer,
      "rating"  => $rating,
      "comment" => $comment
    ], $level);
  }
  public function setPrivacy($key, $rules, $level = 2) {
    return $this->accountRequest("setPrivacy", [
      "key"   => $key,
      "rules" => $rules
    ], $level);
  }
  public function updatePinnedMessage($channel, $id, $silent = false, $level = 2) {
    return $this->channelsRequest("updatePinnedMessage", [
      "channel" => $channel,
      "id"      => $id,
      "silent"  => $silent
    ], $level);
  }
  public function setStickers($channel, $stickerset, $level = 2) {
    return $this->channelsRequest("setStickers", [
      "channel"    => $channe,
      "stickerset" => $stickerset
    ], $level);
  }
  public function unregisterDevice($type, $token, $other, $level = 2) {
    return $this->accountRequest("unregisterDevice", [
      "token_type" => $type,
      "token"      => $token,
      "other_uids" => $other
    ], $level);
  }
  public function toggleSignatures($channel, $enabled = true, $level = 2) {
    return $this->channelsRequest("toggleSignatures", [
      "channel" => $channel,
      "enabled" => $enabled
    ], $level);
  }
  public function updateProfilePhoto($id, $level = 2) {
    return $this->photosRequest("updateProfilePhoto", ["id" => $id], $level);
  }
  public function uploadMedia($peer, $media, $level = 2) {
    return $this->messagesRequest("uploadMedia", [
      "peer"  => $peer,
      "media" => $media
    ], $level);
  }
  public function uploadEncryptedFile($peer, $file, $level = 2) {
    return $this->messagesRequest("uploadEncryptedFile", [
      "peer" => $peer,
      "file" => $file
    ], $level);
  }
  public function uploadProfilePhoto($file, $level = 2){
    return $this->photosRequest("uploadProfilePhoto", ["file" => $file], $level);
  }
  public function recoverPassword($code, $level = 2){
    return $this->authRequest("recoverPassword", ["code" => $code], $level);
  }
  public function close() {
    $this->token = null;
    $this->phone = null;
  }
}

?>
