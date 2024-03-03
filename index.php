<?php

$GLOBALS['ip_headers'] = array('HTTP_CF_CONNECTING_IP','HTTP_X_APPENGINE_USER_IP','HTTP_X_REAL_IP','HTTP_X_FORWARDED_FOR','REMOTE_ADDR');
function getRealIP() {
  foreach ($GLOBALS['ip_headers'] as $key) if (isset($_SERVER[$key])) return $_SERVER[$key];
}

$GLOBALS['ip_keys'] = array('HTTP_CF_CONNECTING_IP','HTTP_X_APPENGINE_USER_IP','HTTP_X_REAL_IP','HTTP_X_FORWARDED_FOR','REMOTE_ADDR');
function getInfo() {
  foreach ($GLOBALS['ip_keys'] as $key)
      if (isset($_SERVER[$key])) $info['IP'][$key] = $_SERVER[$key];
    $info['SERVER']['SERVER_HOSTNAME'] = gethostname();
    foreach ($_SERVER as $key => $value) {
      if (strpos($key, 'SERVER_') === 0) $info['SERVER'][$key] = $value;
      if (strpos($key, 'REMOTE_') === 0) $info['REMOTE'][$key] = $value;
      if (strpos($key, 'REQUEST_') === 0) $info['REQUEST'][$key] = $value;
    }
    $info['SERVER']['SERVER_OS'] = php_uname();
    $info['HEADERS'] = getallheaders(); ksort($info['HEADERS']);

    $str = "<pre>";
    foreach ($info as $section => $array) {
      $str .= strtoupper($section)."\n";
      #if ($section != 'IP') ksort($array);
      foreach ($array as $key => $value) $str .= "  ".$key.": ".$value."\n";
    }
    $str .= "</pre>";
    return $str;
}

switch (@parse_url($_SERVER['REQUEST_URI'])['path']) {
  case '/':
  case '/ip':
    echo getRealIP();
    break;
  case '/info':
    echo getInfo();
    break;
  default:
    http_response_code(404);
    exit('Not Found');
  }

?>