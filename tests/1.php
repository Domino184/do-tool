<?php

/**
 * 正则表达式：基本字符
 */
$str = 'Goole Runoob Taobao';

$subject = array('1', 'a');
$pattern = array('/\d/', '/[a-z]/', '/[1a]/');
$replace = array('A:$0', 'B:$0');


echo "preg_filter returns\n";
echo "<pre/>";
print_r(preg_filter($pattern, $replace, $subject));

