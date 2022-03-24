<?php

//$arr = [123, 11, 1, 3, 5, 0];
//
//$length = count($arr);
//
//for ($i = 1; $i < $length; $i++) {
//    for ($j = 0; $j < ($length - $i); $j ++) {
//        if ($arr[$j] < $arr[$j + 1]) {
//            $temp = $arr[$j];
//            $arr[$j] = $arr[$j+1];
//            $arr[$j+1] = $temp;
//        }
//    }
//}
//
//var_dump($arr);

$str = "山推机械大中小微挖掘机铲车，压路8611222机，可谈整车置换性能可视可试，价 +86-180640741122谈从优满意而归，合作共赢️13955445686 0997-8611222";
$pattern = "/[\D]*([\d]{0,4}\-?[\d]{7,11})[\D]*/i";
preg_match_all($pattern, $str, $matches);
echo "<pre>";
var_dump($matches);