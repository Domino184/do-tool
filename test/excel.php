<?php
require __DIR__ . '/../vendor/autoload.php';

use DoUtil\Excel;

//$data = Excel::getInstance()->import('./客户评分.xlsx');
//$header = array_slice($data, 0, 1);
//$data1 = array_slice($data, 1);
$header = ['AAAA', 'BBBB', 'CCCC', 'DDDD', 'FFFF'];
$data = [
    ['12312', 'http://www.baidu.com', '111', '222', '333'],
    ['12312', 'http://www.baidu.com', '111', '222', 'https://up.enterdesk.com/edpic_360_360/5a/cd/a3/5acda38193e99d25d0a6d424d7a67f96.jpg'],
];
Excel::getInstance()->setFontSize('8')->export($data, $header, '客户评分');
//echo '<pre>';
//var_dump($data);