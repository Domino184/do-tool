<?php

if (!function_exists('def_format_bytes')) {
    /**
     * 将字节转换为可读文本
     * @param int    $size      大小
     * @param string $delimiter 分隔符
     * @return string
     */
    function def_format_bytes($size = 0, $delimiter = '')
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
        for ($i = 0; $size >= 1024 && $i < 6; $i++) {
            $size /= 1024;
        }
        return round($size, 2) . $delimiter . $units[$i];
    }
}

if (!function_exists('def_format_week')) {
    /**
     * 根据日期获取星期几
     * @param string $time
     * @return array
     */
    function def_format_week($time)
    {
        $time  = is_numeric($time) ? $time : strtotime($time);
        $weeks = ["周日", "周一", "周二", "周三", "周四", "周五", "周六"];
        $week  = date('w', $time);
        return $weeks[$week] ?? '';
    }
}

if (!function_exists('def_filter')) {
    /**
     * Author：Domino
     * Date: 2019/12/21
     * Time: 15:55
     * @title 过滤，获取两个数组的交集
     * @param      $data
     * @param null $whiteList
     * @return array
     */
    function def_filter($data, $whiteList = [])
    {
        $data = array_intersect_key($data, array_flip($whiteList));
        return $data;
    }
}

if (!function_exists('def_regex_match')) {
    /**
     * @param $value
     * @param $rule
     * @author Domino <m18434900825@163.com>
     * @title  自定义正则验证
     * @time   2020/12/23 002316:36
     */
    function def_regex_match($value, $rule)
    {
        if (0 !== strpos($rule, '/') && !preg_match('/\/[imsU]{0,4}$/', $rule)) {
            // 不是正则表达式则两端补上/
            $rule = '/^' . $rule . '$/';
        }

        return is_scalar($value) && 1 === preg_match($rule, (string)$value);
    }
}

if (!function_exists('set_timeout')) {
    /**
     * @param int $timeout
     * @author Domino <m18434900825@163.com>
     * @title  设置延时时间
     * @time   2020/12/30 003011:23
     */
    function set_timeout($timeout = 172800)
    {
        static $firstRun = false;
        if ($firstRun) return; //避免重复调用; 5000次100ms;
        $firstRun = true;

        @ini_set("max_execution_time", $timeout);
        @ini_set('request_terminate_timeout', $timeout);
        @set_time_limit($timeout);
        @ini_set('memory_limit', '4000M');//4G;
    }
}

if (!function_exists('is_valid_time')) {
    /**
     * @param $value
     * @author Domino <m18434900825@163.com>
     * @title  判断是否为有效的时间
     * @time   2021/1/7 000715:30
     */
    function is_valid_time($time)
    {
        if ($time <= 0) {
            return false;
        }
        $time_format = is_numeric($time) ? true : false;
        if ($time_format) {
            $time = date('Y-m-d H:i:s', $time);
        }
        $ret = strtotime($time);
        return $ret !== false && $ret != -1;
    }
}

if (!function_exists('def_http_build_query')) {
    /**
     * Author：Domino
     * Date: 2019/12/18
     * Time: 11:14
     * @title get请求的url拼装
     */
    function def_http_build_query($url, $param = [])
    {
        $query_string = is_array($param) ? http_build_query($param) : $param;
        $geturl       = $query_string ? $url . (stripos($url, "?") !== false ? "&" : "?") . $query_string : $url;
        return $geturl;
    }
}

if (!function_exists('check_empty')) {
    /**
     * @param $value
     * @return bool
     * @author Domino <m18434900825@163.com>
     * @title  检查空
     * @time   2021/1/18 001810:27
     */
    function check_empty($value)
    {
        if (!isset($value)) {
            return true;
        }
        if ($value === null) {
            return true;
        }
        if (is_array($value) && count($value) == 0) {
            return true;
        }
        if (is_string($value) && trim($value) === "") {
            return true;
        }
        return false;
    }
}

if (!function_exists('check_max_length')) {
    /**
     * @author Domino <m18434900825@163.com>
     * @title  检查最大长度
     * @time   2021/1/18 001810:48
     */
    function check_max_length($value, $maxLength)
    {
        if (!check_empty($value) && mb_strlen($value, "UTF-8") > $maxLength) {
            return false;
        }
        return true;
    }
}

if (!function_exists('check_max_list_size')) {
    /**
     * @author Domino <m18434900825@163.com>
     * @title  检查列表长度
     * @time   2021/1/18 001810:50
     */
    function check_max_list_size($value, $maxSize)
    {
        if (check_empty($value)) {
            return false;
        }
        $list = preg_split("/,/", $value);
        if (count($list) > $maxSize) {
            return false;
        }
        return true;
    }
}

if (!function_exists('tpl_replace')) {
    /**
     * @param string $str
     * @param        $separator1 string 前界定符
     * @param        $separator2 string 后界定符
     * @author Domino <m18434900825@163.com>
     * @title  正则替换指定界定符中间内容 （也可使用正则(?<=)来实现）
     * @time   2021/3/2 000215:20
     */
    function tpl_replace($str = '', $arr = [], $separator1 = '{', $separator2 = '}')
    {
        $regex = '/' . $separator1 . '(.+?)' . $separator2 . '/i';
        $str   = preg_replace_callback($regex, function ($matches) use ($arr) {
            return !empty($arr[$matches[1]]) ? $arr[$matches[1]] : $matches[0];
        }, $str);
        return $str;
    }
}

if (!function_exists('check_bank_card')) {
    /**
     * @param $card
     * @return bool
     * @author Domino <m18434900825@163.com>
     * @title  验证银行卡
     * @time   2021/3/17 001716:58
     */
    function check_bank_card($card)
    {
        $arr_no = str_split($card);
        $last_n = $arr_no[count($arr_no) - 1];
        krsort($arr_no);
        $i     = 1;
        $total = 0;
        foreach ($arr_no as $n) {
            if ($i % 2 == 0) {
                $ix = $n * 2;
                if ($ix >= 10) {
                    $nx    = 1 + ($ix % 10);
                    $total += $nx;
                } else {
                    $total += $ix;
                }
            } else {
                $total += $n;
            }
            $i++;
        }
        $total -= $last_n;
        $total *= 9;
        return $last_n == ($total % 10);
    }
}