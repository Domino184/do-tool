<?php

/*
 * +----------------------------------------------------------------------
 * | do-tool工具库
 * +----------------------------------------------------------------------
 * | Author: Domino184 <m18434900825@163.com>
 * +----------------------------------------------------------------------
 */

require __DIR__ . '/../vendor/autoload.php';

use DoTool\Ip2Region;
$dbFile = __DIR__ . '/ip2region.db';
$ip2regionObj = new Ip2Region($dbFile);
$line = "61.165.188.4";
$data   = $ip2regionObj->memorySearch($line);
var_dump($data);