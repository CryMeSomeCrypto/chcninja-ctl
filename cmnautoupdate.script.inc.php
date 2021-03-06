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

DEFINE('CMN_VERSION','0.1.1');

xecho('cmnautoupdate v'.CMN_VERSION."\n");

function die2($retcode) {
    unlink(CMN_AUTOUPDATE_SEMAPHORE);
    die($retcode);
}

if (file_exists(CMN_AUTOUPDATE_SEMAPHORE) && (posix_getpgid(intval(file_get_contents(CMN_AUTOUPDATE_SEMAPHORE))) !== false) ) {
    xecho("Already running (PID ".sprintf('%d',file_get_contents(CMN_AUTOUPDATE_SEMAPHORE)).")\n");
    die(10);
}
file_put_contents(CMN_AUTOUPDATE_SEMAPHORE,sprintf('%s',getmypid()));

xecho("Reading latest version fetched from server: ");
$curdatafile = dirname(__FILE__).'/cmnautoupdate.data.json';
if (file_exists($curdatafile)) {
    $data = file_get_contents($curdatafile);
    if ($data === FALSE) {
        echo "ERROR (Could not read file)\n";
        die2(1);
    }
    $data = json_decode($data,true);
    if ($data === FALSE) {
        echo "ERROR (Could not decode JSON data)\n";
        die2(1);
    }
    $dt = new \DateTime($data['Last-Modified']);
    echo "OK (".$dt->format('Y-m-d H:i:s').")\n";
}
else {
    echo "First execution!\n";
    $data = array("Content-Length" => "-1",'Last-Modified' => 'Always');
}

xecho("Fetching from Chaincoin server: ");

$url = CMN_AUTOUPDATE_TEST;
$headers = get_headers($url, 1);
$dt = NULL;
if ((is_array($headers) && array_key_exists(0,$headers) && strstr($headers[0], '200'))) {
    $dt = new \DateTime($headers['Last-Modified']);
    echo "OK (".$dt->format('Y-m-d H:i:s').")\n";
}
else {
    echo "ERROR\n";
}

if ($data['Last-Modified'] == $headers['Last-Modified']) {
    xecho("Nothing to do, no new binary...\n");
//    xecho("Restarting testnet node: ");
//    exec("/opt/cmnctl/cmnctl restart testnet p2pool",$output,$ret);
//    var_dump($output);
//    var_dump($ret);
//    echo "OK\n";
    die2(0);
}
else {
    xecho("Downloading new build from server: ");
    $rawfile = file_get_contents(CMN_AUTOUPDATE_TEST);
    if ($rawfile === FALSE) {
         echo "ERROR\n";
        die2(2);
    }
    echo "OK\n";
    xecho("Saving file: ");
    $tdir = tempnam('/tmp','cmnautoupdate.build.'.md5($headers['Last-Modified']).'.');
    if (file_exists($tdir)) {
        unlink($tdir);
    }
    mkdir($tdir);
    $fnam = $tdir."/build.tar.gz";
    echo $fnam." ";
    if (file_put_contents($fnam,$rawfile) === FALSE) {
        echo "ERROR\n";
        die2(3);
    };
    echo "OK\n";
    unset($rawfile);
    xecho("Extracting file: ");
    $cdir = getcwd();
    chdir($tdir);
    exec("/bin/tar xf build.tar.gz",$output,$ret);
    chdir($cdir);
    if ($ret != 0) {
        echo "ERROR (untar failed with return code $ret)\n";
        die2(4);
    }
    unlink($fnam);
    $folder = FALSE;
    if ($handle = opendir($tdir)) {
        while (false !== ($entry = readdir($handle))) {
            if ($entry != "." && $entry != "..") {
                $folder = $entry;
            }
        }
        closedir($handle);
    }
    $chaincoindpath = $tdir."/".$folder."/bin/chaincoind";
    if (($folder === FALSE) || (!file_exists($chaincoindpath))) {
        echo "ERROR (Could not extract correctly)\n";
        die2(5);
    }
    echo "OK (".$chaincoindpath.")\n";
    xecho("Retrieving version number: ");
    exec($chaincoindpath." -?",$output,$ret);
    if ($ret != 0) {
        echo "ERROR (chaincoind return code $ret)\n";
        die2(5);
    }
    if (!preg_match('/^Chaincoin Core Daemon version v(.+)$/',$output[0],$match)) {
        echo "ERROR (chaincoind return version do not match regexp '".$output[0]."')\n";
        die2(6);
    };
    $version = $match[1];
    echo "OK (".$version.")\n";
    xecho("Adding new version to database: ");
    rename($chaincoindpath,"/opt/chaincoind/0.12/chaincoind-".$version);
    exec("/opt/cmnctl/cmnctl version /opt/chaincoind/0.12/chaincoind-".$version." ".$version." 1 1",$output,$ret);
    var_dump($output);
    var_dump($ret);
    delTree($tdir);
    echo "OK\n";
    xecho("Restarting testnet node: ");
    exec("/opt/cmnctl/cmnctl restart testnet p2pool",$output,$ret);
    if ($ret != 0) {
        echo "ERROR (return code of cmnctl restart was $ret)\n";
        var_dump($output);
        die2(8);
    }
    exec("/opt/cmnctl/cmnctl restart testnet masternode",$output,$ret);
    if ($ret != 0) {
        echo "ERROR (return code of cmnctl restart was $ret)\n";
        var_dump($output);
        die2(8);
    }
    echo "OK\n";
    xecho("Saving data for next run...");
    if (file_put_contents($curdatafile,json_encode($headers)) === FALSE) {
        echo "ERROR\n";
        die2(7);
    }
    echo "OK\n";
}

?>
