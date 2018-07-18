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

// Indicate for each of your nodes which one you need to retrieve blocktemplate from (bt) and/or block info (block)
// Best practice for now is only retrieve block from one node and blocktemplate from all
$unamelist = array(
       'cmn01' => array('bt' => true,   'block' => false,   'mempool' => false),
       'cmn02' => array('bt' => true,   'block' => false,   'mempool' => false),
       'cmn03' => array('bt' => true,   'block' => false,   'mempool' => false),
       'cmn04' => array('bt' => true,   'block' => false,   'mempool' => false),
       'cmn05' => array('bt' => true,   'block' => false,   'mempool' => false),
       'cmn06' => array('bt' => true,   'block' => false,   'mempool' => false),
       'cmn07' => array('bt' => true,   'block' => false,   'mempool' => false),
       'cmn08' => array('bt' => true,   'block' => false,   'mempool' => false),
       'cmn09' => array('bt' => true,   'block' => false,   'mempool' => false),
       'cmn10' => array('bt' => true,   'block' => false,   'mempool' => false),
       'cmn11' => array('bt' => true,   'block' => false,   'mempool' => false),
       'cmn12' => array('bt' => true,   'block' => false,   'mempool' => false),
       'cmn13' => array('bt' => true,   'block' => false,   'mempool' => false),
       'cmn14' => array('bt' => true,   'block' => false,   'mempool' => false),
       'cmn15' => array('bt' => true,   'block' => false,   'mempool' => false),
       'p2pool' => array('bt' => true,   'block' => true,   'mempool' => true),
       'tcmn01' => array('bt' => true,   'block' => false,   'mempool' => false),
       'tcmn02' => array('bt' => true,   'block' => false,   'mempool' => false),
       'tcmn03' => array('bt' => true,   'block' => false,   'mempool' => false),
       'tcmn04' => array('bt' => true,   'block' => false,   'mempool' => false),
       'tcmn05' => array('bt' => true,   'block' => false,   'mempool' => false),
       'tcmn06' => array('bt' => true,   'block' => false,   'mempool' => false),
       'tcmn07' => array('bt' => true,   'block' => false,   'mempool' => false),
       'tcmn08' => array('bt' => true,   'block' => false,   'mempool' => false),
       'tcmn09' => array('bt' => true,   'block' => false,   'mempool' => false),
       'tcmn10' => array('bt' => true,   'block' => false,   'mempool' => false),
       'tcmn11' => array('bt' => true,   'block' => false,   'mempool' => false),
       'tcmn12' => array('bt' => true,   'block' => false,   'mempool' => false),
       'tcmn13' => array('bt' => true,   'block' => false,   'mempool' => false),
       'tcmn14' => array('bt' => true,   'block' => false,   'mempool' => false),
       'tcmn15' => array('bt' => true,   'block' => false,   'mempool' => false),
       'tp2pool' => array('bt' => true,   'block' => true,   'mempool' => true),
);

?>
