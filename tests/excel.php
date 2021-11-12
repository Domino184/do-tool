<?php
require __DIR__ . '/../vendor/autoload.php';

use DoTool\Excel;

//$data = Excel::getInstance()->import('./客户评分.xlsx');
//$header = array_slice($data, 0, 1);
//$data1 = array_slice($data, 1);
$header = ['项', 'NO', '所在期间', '立讯工号', '姓名', '身份证号码', '资源', '原到职日期'];
$data = [
    ['东远', '1', '202012', '12285744', '张利锋', '410328199104158030', '提前离职无费用', '2020/8/26'],
    ['东远', '2', '202012', '12632328', '黄达', '410328199104158030', '提前离职无费用', '2020/12/26'],
];
Excel::getInstance()
    ->setTitle('客户评分')
    ->setHeader($header)
    ->setData($data)
    ->export();
//echo '<pre>';
//var_dump($data);