# CHC Ninja Control Scripts (chcninja-ctl)
Based on Dash Ninja By Alexandre (aka elbereth) Devilliers

Check the running live website at https://chcninja.com

This is part of what makes the CHC Ninja monitoring application.
It contains:
* chc-node.php : is a php implementation of Chaincoin protocol to retrieve subver during port checking
* chcblocknotify : is the blocknotify script (for stats)
* chcblockretrieve : is a script used to retrieve block information when blocknotify script did not work (for stats)
* chaincoindupdate : is an auto-update chaincoind script (uses git)
* cmnbalance : is the balance check script (for stats)
* cmnblockcomputeexpected : is a script used to compute and store the expected fields in cmd_info_blocks table
* cmnblockdegapper : is a script that detects if blocks are missing in cmd_info_blocks table and retrieve them if needed
* cmnblockparser : is the block parser script (for stats)
* cmnctl : is the control script (start, stop and status of nodes)
* cmnctlrpc : is the RPC call sub-script for the control script
* cmnctlstartstopdaemon : is the start/stop daemon sub-script for the control script
* cmncron : is the cron script
* cmnportcheck : is the port check script (for stats)
* cmnportcheckdo : is the actual port check sub-script for the port check script
* cmnreset : is the reset .dat files script
* cmnthirdpartiesfetch : is the script that fetches third party data from the web (for stats)
* cmnvotesrrd and cmnvotesrrdexport: are obsolete v11 votes storage and exported (for graphs)

## Requirement:
* CHC Ninja Back-end: https://github.com/crymesomecrypto/chcninja-be
* CHC Ninja Database: https://github.com/crymesomecrypto/chcninja-db
* CHC Ninja Front-End: https://github.com/crymesomecrypto/chcninja-fe
* PHP 5.6 with curl

Important: Almost all the scripts uses the private rest API to retrieve and submit data to the database (only cmnblockcomputeexpected uses direct MySQL access).

## Install:
* Go to /opt
* Get latest code from github:
```shell
git clone https://github.com/crymesomecrypto/chcninja-ctl.git
```
* Get sub-modules:
```shell
cd chcninja-ctl
git submodule update --init --recursive
```
* Configure the tool.

## Configuration:
* Copy cmn.config.inc.php.sample to cmn.config.inc.php and setup your installation.
* Add cmncron to your crontab (every minute is what official CHC Ninja uses)
```
*/1 * * * * /opt/chcninja-ctl/cmncron
```
If you want to enable logging, you need to create the /var/log/cmn/ folder and give the user write access.
Then add "log" as first argument when calling cmncron:
```
*/1 * * * * /opt/chcninja-ctl/cmncron log
```
* Add cmnthirdpartiesfetch to your crontab (every minute is fine, can be longer)
```
*/1 * * * * /opt/chcninja-ctl/cmnthirdpartiesfetch >> /dev/null
```

### chcblocknotify:
* You need /dev/shm available and writable.
* Edit chcblocknotify.config.inc.php to indicates each of your nodes you wish to retrieve block info from.
* You can either retrieve block templates (bt = true) and/or block/transaction (blocks = true). For the latter you need to have txindex=1 in your chaincoin config file.
* Add in each of your nodes in chaincoin.conf a line to enable blocknotify feature:
```
blocknotify=/opt/chcninja-ctl/chcblocknotify
```
* Restart your node.
* On each block received by the node, the script will be called and data will be created in /dev/shm.
