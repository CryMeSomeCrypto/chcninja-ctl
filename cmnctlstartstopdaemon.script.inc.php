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

if (!defined('CMN_SCRIPT') || !defined('CMN_CONFIG') || (CMN_SCRIPT !== true) || (CMN_CONFIG !== true)) {
  die('Not executable');
}

define('CMN_VERSION','1.2.4');

// Start the masternodes
function cmn_start($uname,$conf,$chaincoind,$extra="") {

  $testnet = ($conf->getconfig('testnet') == 1);
  $pid = cmn_getpid($uname,$testnet);
  $startcmn = (cmn_checkpid($pid) === false);
  if (!$startcmn) {
    echo "Already running. Nothing to do.";
    $res = true;
  }
  else {
    $cmnenabled = ($conf->gecmnctlconfig('enable') == 1);
    if ($cmnenabled) {
      $RUNASUID = cmn_getuid($uname,$RUNASGID);
      if ($testnet) {
        $nice = CMN_NICELEVEL_TEST;
      }
      else {
        $nice = CMN_NICELEVEL_MAIN;
      }
      $trycount = 0;
      $res = false;
      while ((!$res) && (!cmn_checkpid(cmn_getpid($uname,$testnet))) && ($trycount < 3)) {
        echo "T$trycount.";
        exec("/sbin/start-stop-daemon -S -c $RUNASUID:$RUNASGID -N " . $nice . " -x " . $chaincoind . " -u $RUNASUID -a " . $chaincoind . " -q -b -- -daemon $extra");
        usleep(250000);
        $waitcount = 0;
        while ((!cmn_checkpid(cmn_getpid($uname, $testnet))) && ($waitcount < CMN_STOPWAIT)) {
          usleep(1000000);
          $waitcount++;
          echo ".";
        }
        if (cmn_checkpid(cmn_getpid($uname, $testnet))) {
          echo "Started!";
          $res = true;
        }
        $trycount++;
        if ($trycount == 3) {
          echo "Could not start!";
        };
      }
    }
    else {
      echo "DISABLED";
      $res = true;
    }
  }
  return $res;

}

// Stop the masternode
function cmn_stop($uname,$conf) {

  $testnet = ($conf->getconfig('testnet') == 1);
  if ($testnet) {
    $testinfo = '/testnet3';
  }
  else {
    $testinfo = '';
  }

  $rpc = new Bitcoin($conf->getconfig('rpcuser'),$conf->getconfig('rpcpassword'),'localhost',$conf->getconfig('rpcport'));

  $pid = cmn_getpid($uname,$testnet);

  if ($pid !== false) {
    $tmp = $rpc->stop();
    if (($rpc->response['result'] != "Chaincoin server stopping") && ($rpc->response['result'] != "Chaincoin Core server stopping")) {
      echo "Unexpected daemon answer (".$rpc->response['result'].") ";
    }
    usleep(250000);
    $waitcount = 0;
    while (cmn_checkpid($pid) && ($waitcount < CMN_STOPWAIT)) {
      usleep(1000000);
      $waitcount++;
      echo ".";
    }
    if (cmn_checkpid($pid)) {
      echo "Soft Stop Failed! Forcing Kill... ";
      exec('kill -s kill '.$pid);
      $waitcount = 0;
      while (cmn_checkpid($pid) && ($waitcount < CMN_STOPWAIT)) {
        echo '.';
        usleep(1000000);
        $waitcount++;
      }
      if (cmn_checkpid($pid)) {
        echo "Failed!";
        $res = false;
      }
      else {
        if (file_exists('/home/'.$uname."/.chaincoincore$testinfo/chaincoind.pid")) {
          unlink('/home/'.$uname."/.chaincoincore$testinfo/chaincoind.pid");
        }
        echo "OK (Killed) ";
        $res = true;
      }
    }
    else {
      echo " OK (Soft Stop) ";
      $res = true;
    }
  }
  else {
    echo "NOT started ";
    $res = true;
  }
  return $res;

}

if (($argc < 3) && ($argv > 5)) {
  xecho("Usage: ".basename($argv[0])." uname (start|stop|restart) [chaincoind] [extra_params]\n");
  die(1);
}

$uname = $argv[1];
$command = $argv[2];
if ($argc > 3) {
  $chaincoind = $argv[3];
}
else {
  $chaincoind = CMN_TERRACOIND_DEFAULT;
}
if ($argc > 4) {
  $extra = $argv[4];
}
else {
  $extra = "";
}

if (!is_dir(CMN_PID_PATH.$uname)) {
  xecho("This node don't exist: ".CMN_PID_PATH.$uname."\n");
  die(2);
}

$conf = new ChaincoinConfig($uname);
if (!$conf->isConfigLoaded()) {
  xecho("Error (Config could not be loaded)\n");
  die(7);
}

if ($command == 'start') {
  if (!is_executable($chaincoind)) {
    xecho("Error ($chaincoind is not an executable file)\n");
    die(8);
  }
  xecho("Starting $uname: ");
  if (cmn_start($uname,$conf,$chaincoind,$extra)) {
    echo "\n";
    die(0);
  }
  else {
    echo "\n";
    die(5);
  }
}
elseif ($command == 'stop') {
  xecho("Stopping $uname: ");
  if (cmn_stop($uname,$conf)) {
    echo "\n";
    die(0);
  }
  else {
    echo "\n";
    die(6);
  }
}
elseif ($command == 'restart') {
  if (!is_executable($chaincoind)) {
    xecho("Error ($chaincoind is not an executable file)\n");
    die(8);
  }
  xecho("Restarting $uname: ");
  if (cmn_stop($uname,$conf)) {
    if (cmn_start($uname,$conf,$chaincoind,$extra)) {
     echo "\n";
     die(0);
    }
    else {
    echo "\n";
      die(5);
    }
  }
  else {
    echo(" Could not stop daemon. Giving up.\n");
    die(4);
  }
}
else {
  xecho('Unknown command: '.$command."\n");
  die(3);
}

?>
