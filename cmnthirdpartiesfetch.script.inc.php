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

DEFINE('CMN_VERSION','2.3.0');

xecho('cmnthirdpartiesfetch v'.CMN_VERSION."\n");

$tp = array();

xecho("Fetching from Kraken: ");
try {
  $kraken = new \Payward\KrakenAPI('','');
  $dataKraken = $kraken->QueryPublic('Ticker', array('pair' => 'XBTCZEUR'));
  if (is_array($dataKraken) && isset($dataKraken['error']) && (count($dataKraken['error']) == 0)
    && isset($dataKraken['result']) && is_array($dataKraken['result'])
    && isset($dataKraken['result']['XXBTZEUR']) && is_array($dataKraken['result']['XXBTZEUR'])
    && isset($dataKraken['result']['XXBTZEUR']['p']) && is_array($dataKraken['result']['XXBTZEUR']['p'])
    && isset($dataKraken['result']['XXBTZEUR']['p'][1]) ) {
    $tp["eurobtc"] = array("StatValue" => $dataKraken['result']['XXBTZEUR']['p'][1],
                           "LastUpdate" => time(),
                           "Source" => "kraken");
    echo "OK (".$dataKraken['result']['XXBTZEUR']['p'][1]." EUR/BTC)\n";
  }
}
catch (Exception $e) {
  // Error
}

/*
xecho("Fetching from Cryptsy: ");
$res = file_get_contents('http://pubapi2.cryptsy.com/api.php?method=singlemarketdata&marketid=155');
if ($res !== false) {
  $res = json_decode($res,true);
//  var_dump($res);
  if (($res !== false) && is_array($res) && (count($res) == 2) && array_key_exists('return',$res)
   && is_array($res["return"]) && array_key_exists("markets",$res["return"])
   && is_array($res["return"]["markets"]) && array_key_exists("CHC",$res["return"]["markets"])
   && is_array($res["return"]["markets"]["CHC"]) && array_key_exists("lasttradeprice",$res["return"]["markets"]["CHC"])) {
    $tp["btcchc"] = array("StatValue" => $res["return"]["markets"]["CHC"]["lasttradeprice"],
                          "LastUpdate" => time(),
                          "Source" => "cryptsy");
    echo "OK (".$res["return"]["markets"]["CHC"]["lasttradeprice"]." BTC/CHC)\n";
  }
  else {
    echo "Failed (JSON)\n";
  }
}
else {
  echo "Failed (GET)\n";
}
*/

/*
xecho("Fetching from Poloniex: ");
$res = file_get_contents('https://poloniex.com/public?command=returnTicker');
if ($res !== false) {
  $res = json_decode($res,true);
//  var_dump($res);
  if (($res !== false) && is_array($res) && (count($res) > 0) && array_key_exists('BTC_CHC',$res)
      && is_array($res["BTC_CHC"]) && array_key_exists("last",$res["BTC_CHC"])) {
    $tp["btcchc"] = array("StatValue" => $res["BTC_CHC"]["last"],
        "LastUpdate" => time(),
        "Source" => "poloniex");
    echo "OK (".$res["BTC_CHC"]["last"]." BTC/CHC)\n";
  }
  else {
    echo "Failed (JSON)\n";
  }
}
else {
  echo "Failed (GET)\n";
}
*/

/*
xecho("Fetching from Bitstamp: ");
$res = file_get_contents('https://www.bitstamp.net/api/ticker/');
if ($res !== false) {
  $res = json_decode($res,true);
  if (($res !== false) && is_array($res) && array_key_exists('timestamp',$res) && array_key_exists('last',$res)) {
    $tbstamp = date('Y-m-d H:i:s',$res['timestamp']);
    $sql[] = sprintf("('usdbtc','".$mysqli->real_escape_string($res['last'])."','".$tbstamp."','bitstamp')");
    $tp["usdbtc"] = array("StatValue" => $res["last"],
                          "LastUpdate" => intval($res['timestamp']),
                          "Source" => "bitstamp");
    echo "OK (".$res['last']." / $tbstamp)\n";
  }
  else {
    echo "Failed (JSON)\n";
  }
}
else {
  echo "Failed (GET)\n";
}
*/

/*
xecho("Fetching from BTC-e: ");
$res = file_get_contents('https://btc-e.com/api/2/btc_usd/ticker');
if ($res !== false) {
  $res = json_decode($res,true);
  if (($res !== false) && is_array($res) && array_key_exists('ticker',$res) && array_key_exists('last',$res['ticker']) && array_key_exists('updated',$res['ticker'])) {
    $tbstamp = date('Y-m-d H:i:s',$res['ticker']['updated']);
    $tp["usdbtc"] = array("StatValue" => $res['ticker']["last"],
                          "LastUpdate" => intval($res['ticker']['updated']),
                          "Source" => "btc-e");
    echo "OK (".$res['ticker']['last']." / $tbstamp)\n";
  }
  else {
    echo "Failed (JSON)\n";
  }
}
else {
  echo "Failed (GET)\n";
}
*/

/*xecho("Fetching from Bitfinex: ");
$res = file_get_contents('https://api.bitfinex.com/v1/pubticker/btcusd');
if ($res !== false) {
  $res = json_decode($res,true);
  if (($res !== false) && is_array($res) && array_key_exists('last_price',$res) && array_key_exists('timestamp',$res)) {
    $tbstamp = date('Y-m-d H:i:s',$res['timestamp']);
    $tp["usdbtc"] = array("StatValue" => $res["last_price"],
        "LastUpdate" => intval($res['timestamp']),
        "Source" => "bitfinex");
    echo "OK (".$res['last_price']." / $tbstamp)\n";
  }
  else {
    echo "Failed (JSON)\n";
  }
}
else {
  echo "Failed (GET)\n";
}*/

xecho("Fetching from itBit: ");
$res = file_get_contents('https://api.itbit.com/v1/markets/XBTUSD/ticker');
if ($res !== false) {
  $res = json_decode($res,true);
  if (($res !== false) && is_array($res) && array_key_exists('pair',$res) && ($res["pair"] == "XBTUSD") && array_key_exists('lastPrice',$res) && array_key_exists('serverTimeUTC',$res)) {
    $timestamp = strtotime($res['serverTimeUTC']);
    $tbstamp = date('Y-m-d H:i:s',$timestamp);
    $tp["usdbtc"] = array("StatValue" => floatval($res["lastPrice"]),
        "LastUpdate" => intval($timestamp),
        "Source" => "itbit");
    echo "OK (".$res['lastPrice']." / $tbstamp)\n";
  }
  else {
    echo "Failed (JSON)\n";
  }
}
else {
  echo "Failed (GET)\n";
}

// https://bittrex.com/api/v1.1/public/getticker?market=BTC-CHC

xecho("Fetching from CoinMarketCap: ");
$res = file_get_contents('https://api.coinmarketcap.com/v1/ticker/chaincoin/?convert=BTC');
$resdone = 0;
if ($res !== false) {
  $res = json_decode($res,true);
  $res = $res[0];
  if (($res !== false) && is_array($res) && array_key_exists('symbol',$res) && ($res['symbol'] == 'CHC') && array_key_exists('last_updated',$res)) {
    $tbstamp = date('Y-m-d H:i:s',$res['last_updated']);
    if (array_key_exists('price_btc',$res)) {
      $tp["btcchc"] = array("StatValue" => $res["price_btc"],
                                  "LastUpdate" => intval($res['last_updated']),
                                  "Source" => "coinmarketcap");
      $resdone++;
    }
    else {
      echo "Failed (JSON/price_btc) ";
    }
    if (array_key_exists('rank',$res)) {
      $tp["marketcappos"] = array("StatValue" => $res["rank"],
                                  "LastUpdate" => intval($res['last_updated']),
                                  "Source" => "coinmarketcap");
      $resdone++;
    }
    else {
      echo "Failed (JSON/position) ";
    }
    if (array_key_exists('percent_change_24h',$res)) {
      $tp["marketcapchange"] = array("StatValue" => $res["percent_change_24h"],
                                     "LastUpdate" => intval($res['last_updated']),
                                     "Source" => "coinmarketcap");
      $resdone++;
    }
    else {
      echo "Failed (JSON/change) ";
    }
    if (array_key_exists('available_supply',$res)) {
      $tp["marketcapsupply"] = array("StatValue" => $res["available_supply"],
                                     "LastUpdate" => intval($res['last_updated']),
                                     "Source" => "coinmarketcap");
      $resdone++;
    }
    else {
      echo "Failed (JSON/supply) ";
    }
    if (array_key_exists('market_cap_btc',$res)) {
      $tp["marketcapbtc"] = array("StatValue" => $res['market_cap_btc'],
                                  "LastUpdate" => intval($res['last_updated']),
                                  "Source" => "coinmarketcap");
      $resdone++;
    }
    else {
      echo "Failed (JSON/market_cap/btc) ";
    }
    if (array_key_exists('market_cap_usd',$res)) {
      $tp["marketcapusd"] = array("StatValue" => $res['market_cap_usd'],
                                  "LastUpdate" => intval($res['last_updated']),
                                  "Source" => "coinmarketcap");
      $resdone++;
    }
    else {
      echo "Failed (JSON/market_cap/usd) ";
    }
    if (array_key_exists('market_cap_eur',$res)) {
      $tp["marketcapeur"] = array("StatValue" => $res['market_cap_eur'],
                                  "LastUpdate" => intval($res['last_updated']),
                                  "Source" => "coinmarketcap");
      $resdone++;
    }
    else {
      echo "Failed (JSON/market_cap/eur) ";
    }
    if (array_key_exists('24h_volume_usd',$res)) {
      $tp["volumeusd"] = array("StatValue" => $res['24h_volume_usd'],
                               "LastUpdate" => intval($res['last_updated']),
                               "Source" => "coinmarketcap");
      $resdone++;
    }
    else {
      echo "Failed (JSON/volume/usd) ";
    }
    if (array_key_exists('24h_volume_eur',$res)) {
      $tp["volumeeur"] = array("StatValue" => $res['24h_volume_eur'],
                               "LastUpdate" => intval($res['last_updated']),
                               "Source" => "coinmarketcap");
      $resdone++;
    }
    else {
      echo "Failed (JSON/volume/eur) ";
    }
    if (array_key_exists('24h_volume_btc',$res)) {
      $tp["volumebtc"] = array("StatValue" => $res['24h_volume_btc'],
                               "LastUpdate" => intval($res['last_updated']),
                               "Source" => "coinmarketcap");
      $resdone++;
    }
    else {
      echo "Failed (JSON/volume/btc) ";
    }
    if ($resdone > 0) {
      if ($resdone >= 7) {
        echo "OK";
      }
      else {
        echo "Partial";
      }
      echo " ($resdone values retrieved)\n";
    }
    else {
      echo "NOK\n";
    }
  }
  else {
    echo "Failed (JSON)\n";
  }
}
else {
  echo "Failed (GET)\n";
}

$ts = array();

xecho("Fetching budgets list from Chaincoin Services: ");
$res = file_get_contents('https://services.chaincoin.io/api/v1/budget?partner='.CMN_CHCWHALE_PARTNERID);
$proposals = array();
if ($res !== false) {
  $res = json_decode($res,true);
  if (($res !== false) && is_array($res) && array_key_exists('status',$res) && ($res['status'] == 'ok') && array_key_exists('proposals',$res) && is_array($res["proposals"]) ) {
    foreach($res["proposals"] as $proposal) {
      if ($proposal !== false && is_array($proposal) && array_key_exists('hash',$proposal) && is_string($proposal["hash"])) {
        if (preg_match("/^[0-9a-f]{64}$/s", $proposal["hash"]) === 1) {
          $proposals[] = $proposal["hash"];
        }
      }
    }
    echo "OK (".count($proposals)." budgets)\n";
  }
  else {
    echo "Failed (JSON)\n";
  }
}
else {
  echo "Failed (GET)\n";
}

foreach($proposals as $proposal) {
  xecho("Fetching budget $proposal from Chaincoin Services: ");
  $res = file_get_contents('https://services.chaincoin.io/api/v1/proposal?partner='.CMN_CHCWHALE_PARTNERID.'&hash='.$proposal);
  $tsentry = array("proposal" => array(),
                   "comments" => array());
  if ($res !== false) {
    $res = json_decode($res,true);
    if (($res !== false) && is_array($res) && array_key_exists('status',$res) && ($res['status'] == 'ok')
                                           && array_key_exists('proposal',$res) && is_array($res["proposal"])
                                           && array_key_exists('comments',$res) && is_array($res["comments"])) {
      $tsentry["proposal"] = $res["proposal"];
      foreach($res["comments"] as $comment) {
        if ($comment !== false && is_array($comment) && array_key_exists('id',$comment) && is_string($comment["id"])
          && array_key_exists('username',$comment) && is_string($comment["username"])
          && array_key_exists('date',$comment) && is_string($comment["date"])
          && array_key_exists('order',$comment) && is_int($comment["order"])
          && array_key_exists('level',$comment)
          && array_key_exists('recently_posted',$comment) && is_bool($comment["recently_posted"])
          && array_key_exists('posted_by_owner',$comment) && is_bool($comment["posted_by_owner"])
          && array_key_exists('reply_url',$comment) && is_string($comment["reply_url"])
          && array_key_exists('content',$comment) && is_string($comment["content"])
           ) {
          if (preg_match("/^[0-9]+$/s", $comment["id"]) === 1) {
            if (!filter_var($comment["reply_url"], FILTER_VALIDATE_URL) === false) {
              $tsentry["comments"][] = $comment;
              echo ".";
            }
            else {
              echo "u";
            }
          }
          else {
            echo "i";
          }
        }
        else {
          echo "e";
        }
      }
      $ts[] = $tsentry;
      echo " OK (".count($tsentry["comments"])." comments)\n";
    }
    else {
      echo "Failed (JSON)\n";
    }
  }
  else {
    echo "Failed (GET)\n";
  }
}

xecho("Submitting to web service: ");
$payload = array("thirdparties" => $tp,
                 "chcwhale" => $ts);
$content = cmn_cmd_post('/thirdparties',$payload,$response);
if (strlen($content) > 0) {
  $content = json_decode($content,true);
  if (($response['http_code'] >= 200) && ($response['http_code'] <= 299)) {
    echo "Success (".$content['data']['thirdparties'].")\n";
  }
  elseif (($response['http_code'] >= 400) && ($response['http_code'] <= 499)) {
    echo "Error (".$response['http_code'].": ".$content['messages'][0].")\n";
  }
}
else {
  echo "Error (empty result) [HTTP CODE ".$response['http_code']."]\n";
}

?>
