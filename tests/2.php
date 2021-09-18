<?php

/**
 * @param string $str
 * @param array $arr
 * @param string $delimiter1
 * @param string $delimiter2
 * @return string|string[]|null
 * @author Domino <m18434900825@163.com>
 * @title 正则分隔符替换
 * @time 2021/3/2 000215:28
 */

function tpl_replace($str = '', $arr = [], $delimiter1 = '{', $delimiter2 = '}')
{
    $regex = '/' . $delimiter1 . '(.+?)' . $delimiter2 . '/i';
    $str = preg_replace_callback($regex, function ($matches) use ($arr) {
        return !empty($arr[$matches[1]]) ? $arr[$matches[1]] : $matches[1];
    }, $str);
    return $str;
}

$str = "a{hi}b{hello}c{world}";
$arr = [
    '111' => 'hi',
];
//print_r(tpl_replace($str, $arr));

$str1 = "asdfasf[微笑]asdfsadf[恐怖]";

$res = preg_match_all('/\[(.+?)\]/', $str1, $matches);

print_r($matches);
//print_r(tpl_replace($str, $arr));