<?php

/*
    This file is part of CHC Ninja.
    https://github.com/crymesomecrypto/chcninja-ctl

    CHC Ninja is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    CHC Ninja is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with CHC Ninja.  If not, see <http://www.gnu.org/licenses/>.

 */

define('CMN_DIR',__DIR__);

// Display log line (with date)
function xecho($line) {
  echo date('Y-m-d H:i:s').' - '.$line;
}

// Check if PID is running and is chaincoind
function cmn_checkpid($pid) {
  if ($pid !== false) {
    $output = array();
    exec('ps -o comm -p '.$pid,$output,$retval);
    if (($retval == 0) && (is_array($output)) && (count($output)>=2)) {
      return (((strlen($output[1]) >= 5) && (substr($output[1], 0, 10) == 'chaincoind')));
    }
    else {
      return false;
    }
  }
  else {
    return false;
  }

}

// Returns the PID for the specified username
function cmn_getpid($uname,$testnet = false) {

  if ($testnet) {
    $testinfo = '/testnet3';
  }
  else {
    $testinfo = '';
  }
  if (file_exists(CMN_PID_PATH.$uname."/.chaincoincore$testinfo/chaincoind.pid") !== FALSE) {
    $res = trim(file_get_contents(CMN_PID_PATH.$uname."/.chaincoincore$testinfo/chaincoind.pid"));
  }
  else if (file_exists(CMN_PID_PATH.$uname."/.bitcore/data$testinfo/chaincoind.pid") !== FALSE) {
    $res = trim(file_get_contents(CMN_PID_PATH.$uname."/.bitcore/data$testinfo/chaincoind.pid"));
  }
  else {
    $res = false;
  }
  return $res;

}

// Retrieve the uid/gid of username
function cmn_getuid($uname,&$gid) {

  $passwd = file_get_contents('/etc/passwd');
  $passwdlist = explode("\n",$passwd);
  foreach($passwdlist as $line) {
    $passwdline = explode(":",$line);
    if ($passwdline[0] == $uname) {
      $gid = $passwdline[3];
      return $passwdline[2];
    }
  }

}

// Run CHC Ninja public webservice GET method command
function cmn_api_get($command,$payload = array(),&$response) {

  global $argv;

  if (substr($command,0,1) != '/') {
    $command = '/'.$command;
  }

  $ch = curl_init();
  curl_setopt( $ch, CURLOPT_USERAGENT, basename($argv[0])."/".CMN_VERSION );
  curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 2 );
  curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, true );
  curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 10 );
  curl_setopt( $ch, CURLOPT_TIMEOUT, 30 );
  curl_setopt( $ch, CURLOPT_MAXREDIRS, 0 );
  curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true);
  if (count($payload) > 0) {
    $payloadurl = '?'.http_build_query($payload);
  }
  else {
    $payloadurl = '';
  }
  curl_setopt( $ch, CURLOPT_URL, CMN_URL_API.$command.$payloadurl );
  curl_setopt( $ch, CURLOPT_HTTPHEADER, array(
      'Content-Length: 0'
  ) );

  $content = curl_exec( $ch );
  $response = curl_getinfo( $ch );

  return $content;

}

// Run CHC Ninja webservice GET method command
function cmn_cmd_get($command,$payload = array(),&$response) {

  global $argv;

  if (substr($command,0,1) != '/') {
    $command = '/'.$command;
  }

  $ch = curl_init();
  curl_setopt( $ch, CURLOPT_USERAGENT, basename($argv[0])."/".CMN_VERSION );
  curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 2 );
  curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, true );
  curl_setopt( $ch, CURLOPT_SSLCERT, CMN_SSL_CERT);
  curl_setopt( $ch, CURLOPT_SSLKEY, CMN_SSL_KEY);
  curl_setopt( $ch, CURLOPT_CAINFO, CMN_SSL_CAINFO );
  curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 10 );
  curl_setopt( $ch, CURLOPT_INTERFACE, CMN_INTERFACE );
  curl_setopt( $ch, CURLOPT_TIMEOUT, 30 );
  curl_setopt( $ch, CURLOPT_MAXREDIRS, 0 );
  curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true);
  if (count($payload) > 0) {
    $payloadurl = '?'.http_build_query($payload);
  }
  else {
    $payloadurl = '';
  }
  curl_setopt( $ch, CURLOPT_URL, CMN_URL_CMD.$command.$payloadurl );
  curl_setopt( $ch, CURLOPT_HTTPHEADER, array(
      'Content-Length: 0'
  ) );

  $content = curl_exec( $ch );
  $response = curl_getinfo( $ch );

  return $content;

}

// Run CHC Masternode Ninja webservice POST method command
function cmn_cmd_post($command,$payload,&$response) {

  global $argv;

  if (substr($command,0,1) != '/') {
    $command = '/'.$command;
  }
  $ch = curl_init();
  curl_setopt( $ch, CURLOPT_USERAGENT, basename($argv[0])."/".CMN_VERSION );
  curl_setopt( $ch, CURLOPT_URL, CMN_URL_CMD.$command );
  curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 2 );
  curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, true );
  curl_setopt( $ch, CURLOPT_SSLCERT, CMN_SSL_CERT);
  curl_setopt( $ch, CURLOPT_SSLKEY, CMN_SSL_KEY);
  curl_setopt( $ch, CURLOPT_CAINFO, CMN_SSL_CAINFO );
  curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 10 );
  curl_setopt( $ch, CURLOPT_INTERFACE, CMN_INTERFACE );
  curl_setopt( $ch, CURLOPT_TIMEOUT, 30 );
  curl_setopt( $ch, CURLOPT_MAXREDIRS, 0 );
  curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'POST' );
  curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true);
  $payloadjson = json_encode($payload);
  if ($payloadjson === false) {
    return false;
  }
  curl_setopt( $ch, CURLOPT_HTTPHEADER, array(
      'Content-Type: application/json',
      'Content-Length: ' . strlen($payloadjson))
  );

//  curl_setopt( $ch, CURLOPT_POSTFIELDSIZE, strlen($payloadjson));
  curl_setopt( $ch, CURLOPT_POSTFIELDS, $payloadjson );
  $content = curl_exec( $ch );
  $response = curl_getinfo( $ch );

  return $content;

}

// Get chaincoind version from binary
function cmn_chaincoindversion($dpath) {

  if (file_exists($dpath) || is_link($dpath)) {
    exec($dpath.' -?',$output,$retval);
    if (preg_match("/Chaincoin Core Daemon version v(.*)/", $output[0], $output_array) == 1) {
      return $output_array[1];
    }
    else {
      return false;
    }
  }
  else {
    return false;
  }

}

// Get array($ip/$port) for IPv4 or IPv6 (ip:port)
function getipport($addr) {
  $portpos = strrpos($addr,":");
  $ip = substr($addr,0,$portpos);
  $port = substr($addr,$portpos+1,strlen($addr)-$portpos-1);
  return array($ip,$port);
}

// Random password generator
function randomPassword($length = 8) {
  $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
  $pass = array(); //remember to declare $pass as an array
  $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
  for ($i = 0; $i < $length; $i++) {
    $n = rand(0, $alphaLength);
    $pass[] = $alphabet[$n];
  }
  return implode($pass); //turn the array into a string
}

function delTree($dir) {
    $files = array_diff(scandir($dir), array('.','..'));
    foreach ($files as $file) {
        (is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file");
    }
    return rmdir($dir);
}

function xechoToFile($logfile,$line) {
  $data = date('Y-m-d H:i:s').' - '.$line;
  file_put_contents($logfile,$data,FILE_APPEND);
}

?>
