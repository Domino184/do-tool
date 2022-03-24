<?php

/*
 * +----------------------------------------------------------------------
 * | do-tool工具库
 * +----------------------------------------------------------------------
 * | Author: Domino184 <m18434900825@163.com>
 * +----------------------------------------------------------------------
 */

declare(strict_types=1);
require __DIR__ . '/../vendor/autoload.php';

// -------------------------------------------------------------------------------

var_dump(def_format_num(230000000));
var_dump(def_format_span_time(time() + 7200, time(), 'years,months,weeks,days,hours,minutes,seconds'));
var_dump(def_format_time(1619366400, 'date'));
exit();
// --------------------------------------------------------------------------------

// 斐波那契
function calle($n)
{
    if ($n == 1 || $n == 2) {
        return 1;
    } else {
        return calle($n - 1) + calle($n - 2);
    }
}
function fib_2($n = 1, $a = 1, $b = 1){ // 类型于for循环
    if ($n > 2) {        // 存储前一位，优化递归计算
        echo $n . ' ';
        return fib_2($n - 1, $a + $b, $a);
    }
    return $a;
}
var_dump(fib_2(100));

// --------------------------------------------------------------------------------------

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

// --------------------------------------------------------------------------------------------