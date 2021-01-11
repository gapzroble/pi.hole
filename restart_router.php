#!/usr/bin/php
<?php
echo 'Login..', PHP_EOL;
echo 'Login..', PHP_EOL;
if (!login()) {
  exit(2);
}

echo 'Restart..', PHP_EOL;
if (!restart()) {
  exit(2);
}
echo 'Progress..', PHP_EOL;
if (!progress()) {
  exit(2);
}

echo 'Logout..', PHP_EOL;
logout();

function login() {
  return curl(
    'http://192.168.254.254/Forms/login_security_1',
    array('Referer: http://192.168.254.254/login_security.html'),
    'tipsFlag=0&timevalue=0&Login_Name=user&Login_Pwd=Ha2S%2BeOKqmzA6nrlmTeh7w%3D%3D&uiWebLoginhiddenUsername=ee11cbb19052e40b07aac0ca060c23ee&uiWebLoginhiddenPassword=789814b28f14d9f46b0d3a7f13ce4afc'
  );
}

function logout() {
  return curl(
    'http://192.168.254.254/Forms/c9logout_1',
    array('Referer: http://192.168.254.254/c9logout.html'),
    'logoutFlag_C9=1'
  );
}

function restart() {
  return curl(
    'http://192.168.254.254/Forms/tools_system_1', 
    array('Referer: http://192.168.254.254/maintenance/tools_system.htm'),
    'restoreFlag=0'
  );
}

function progress() {
  return curl('http://192.168.254.254/progress.htm', array(
    'Referer: http://192.168.254.254/maintenance/tools_system.htm',
  ));
}

function curl($url, $headers = array(), $postvars = null) {
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_HEADER, true);
  //curl_setopt($ch, CURLOPT_VERBOSE, true);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0); 
  curl_setopt($ch, CURLOPT_TIMEOUT, 2); //timeout in second

  if ($postvars) {
    curl_setopt ($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postvars);
  }

  $default_headers = array(
    'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
    'Accept-Encoding: gzip, deflate',
    'Accept-Language: en',
    'Cache-Control: max-age=0',
    'Connection: keep-alive',
    'DNT: 1',
    'Host: 192.168.254.254',
    'Origin: http://192.168.254.254',
    'Referer: http://192.168.254.254/maintenance/tools_system.htm',
    'Upgrade-Insecure-Requests: 1',
    'User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/84.0.4147.105 Safari/537.36',
  );
  if ($postvars) {
    $default_headers[] = 'Content-Type: application/x-www-form-urlencoded';
  }
  curl_setopt($ch, CURLOPT_HTTPHEADER, array_merge($default_headers, $headers));

  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

  $tmpfname = dirname(__FILE__).'/cookie.txt';
  curl_setopt($ch, CURLOPT_COOKIEJAR, $tmpfname);
  curl_setopt($ch, CURLOPT_COOKIEFILE, $tmpfname);

  $output = curl_exec($ch);
  //echo $output;

  $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  //var_dump('code', $code);

  curl_close($ch);   

  switch ($code) {
    case 403:
    return false;
  }

  return true;
}
