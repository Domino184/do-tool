<?php
/**
 * ------------------------------------------------------
 * | Domino <m18434900825@163.com>
 * ------------------------------------------------------
 */
declare(strict_types=1);
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
var_dump(fib_2(100));exit;