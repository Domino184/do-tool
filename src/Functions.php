<?php

/*
 * +----------------------------------------------------------------------
 * | do-tool工具库
 * +----------------------------------------------------------------------
 * | Author: Domino184 <m18434900825@163.com>
 * +----------------------------------------------------------------------
 */

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
        if ($firstRun) {
            return;
        } //避免重复调用; 5000次100ms;
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

if (!function_exists('def_format_num')) {
    /**
     * 把数字1-1亿换成汉字表述，如：123->一百二十三
     * @param      $num
     * @param bool $mode
     * @param bool $sim
     * @return string
     */
    function def_format_num($num)
    {
        $chiNum  = ['零', '一', '两', '三', '四', '五', '六', '七', '八', '九'];
        $chiUni  = ['', '十', '百', '千', '万', '十万', '百万', '千万', '亿'];
        $chiStr  = '';
        $num_str = (string)$num;
        $count = strlen($num_str);
        $last_flag = true; //上一个 是否为0
        $zero_flag = true; //是否第一个
        $temp_num = null; //临时数字
        $chiStr = '';//拼接结果
        if ($count == 2) {//两位数
            $temp_num = $num_str[0];
            $chiStr = $temp_num == 1 ? $chiUni[1] : $chiNum[$temp_num] . $chiUni[1];
            $temp_num = $num_str[1];
            $chiStr .= $temp_num == 0 ? '' : $chiNum[$temp_num];
        } elseif ($count > 2) {
            $index = 0;
            for ($i = $count - 1; $i >= 0; $i--) {
                $temp_num = $num_str[$i];
                if ($temp_num == 0) {
                    if (!$zero_flag && !$last_flag) {
                        $chiStr = $chiNum[$temp_num] . $chiStr;
                        $last_flag = true;
                    }
                } else {
                    $chiStr = $chiNum[$temp_num] . $chiUni[$index % 9] . $chiStr;
                    $zero_flag = false;
                    $last_flag = false;
                }
                $index++;
            }
        } else {
            $chiStr = $chiNum[$num_str[0]];
        }
        return $chiStr;
    }
}

if (!function_exists('def_format_time')) {
    /**
     * Author: Domino
     * Date: 2020/3/1
     * Time: 13:09
     * @title 格式化时间
     * @param $time
     * @param string $format
     */
    function def_format_time($time, $format = 'datetime')
    {
        $time = is_numeric($time) ? $time : strtotime($time);
        $this_week_start = mktime(0, 0, 0, date('m'), date('d') - date("w", mktime(0, 0, 0, date('m'), date('d'), date('Y'))) + 1, date('Y'));
        $this_week_end = mktime(23, 59, 59, date('m'), date('d') - date("w", mktime(0, 0, 0, date('m'), date('d'), date('Y'))) + 7, date('Y'));
        $yesterday_start = mktime(0, 0, 0, date('m'), date('d') - 1, date('Y'));
        $yesterday_end = mktime(23, 59, 59, date('m'), date('d') - 1, date('Y'));
        $now_time = time();
        // 当年
        if (date('Y', $now_time) == date('Y', $time)) {
            // 当月且是当天
            if (date('m', $now_time) == date('m', $time) && date('d', $now_time) == date('d', $time)) {
                // 当天
                $time = date('H:i', $time);
            } else {
                if ($time >= $yesterday_start && $time <= $yesterday_end) { // 昨天
                    switch ($format) {
                        case 'date':
                            $time = '昨天';
                            break;
                        case 'datetime':
                            $time = '昨天 ' . date('H:i', $time);
                            break;
                    }
                } elseif ($time >= $this_week_start && $time <= $this_week_end) { // 本周
                    switch ($format) {
                        case 'date':
                            $time = def_format_week($time);
                            break;
                        case 'datetime':
                            $time = def_format_week($time) . ' ' . date('H:i', $time);
                            break;
                    }
                } else {
                    switch ($format) {
                        case 'date':
                            $time = date('m月d日', $time);
                            break;
                        case 'datetime':
                            $time = date('m月d日', $time) . ' ' . def_format_today(date('H', $time)). ' ' . date('H:i', $time);
                            break;
                    }
                }
            }
        } else {
            switch ($format) {
                case 'date':
                    $time = date('Y年m月d日', $time);
                    break;
                case 'datetime':
                    $time = date('Y年m月d日', $time) . ' ' . def_format_today(date('H', $time)) . date('H:i', $time);
                    break;
            }
        }
        return $time;
    }
}

if (!function_exists('def_format_span_time')) {
    /**
     * 两个时间戳差值语义化
     * @param        $remote
     * @param null   $local
     * @param string $output
     * @return bool|string
     */
    function def_format_span_time($remote, $local = null, $output = 'years,months,weeks,days,hours,minutes,seconds') {
        $year = 31536000;
        $month = 2592000;
        $week = 604800;
        $day = 86400;
        $hour = 3600;
        $minute = 60;
        // Normalize output
        $output = trim(strtolower((string)$output));
        if (!$output) {
            // Invalid output
            return false;
        }
        // Array with the output formats
        $output = preg_split('/[^a-z]+/', $output);
        // Convert the list of outputs to an associative array
        $output = array_combine($output, array_fill(0, count($output), 0));
        // Make the output values into keys
        extract(array_flip($output), EXTR_SKIP);
        if ($local === null) {
            // Calculate the span from the current time
            $local = time();
        }
        // Calculate timespan (seconds)
        $timespan = abs($remote - $local);
        $outputFormat = '';
        if (isset($output['years'])) {
            $timespan -= $year * ($output['years'] = (int)floor($timespan / $year));
            $outputFormat .= $output['years'] . '年';
        }
        if (isset($output['months'])) {
            $timespan -= $month * ($output['months'] = (int)floor($timespan / $month));
            $outputFormat .= $output['months'] . '月';
        }
        if (isset($output['weeks'])) {
            $timespan -= $week * ($output['weeks'] = (int)floor($timespan / $week));
            $outputFormat .= $output['weeks'] . '周';
        }
        if (isset($output['days'])) {
            $timespan -= $day * ($output['days'] = (int)floor($timespan / $day));
            $outputFormat .= $output['days'] . '天';
        }
        if (isset($output['hours'])) {
            $timespan -= $hour * ($output['hours'] = (int)floor($timespan / $hour));
            $outputFormat .= $output['hours'] . '小时';
        }
        if (isset($output['minutes'])) {
            $timespan -= $minute * ($output['minutes'] = (int)floor($timespan / $minute));
            $outputFormat .= $output['minutes'] . '分';
        }
        // Seconds ago, 1
        if (isset($output['seconds'])) {
            $output['seconds'] = $timespan;
            $outputFormat .= $output['seconds'] . '秒';
        }
        return $outputFormat;
    }
}

if (!function_exists('def_format_today')) {
    /**
     * Author: Domino
     * Date: 2020/3/1
     * Time: 13:30
     * @title 格式化时间
     */
    function def_format_today($time)
    {
        // 1-5点是凌晨，5-8点是早晨，9-13点是中午，14-18点是下午，19-24点是晚上。
        if ($time > 0 && $time <= 5) {
            return '凌晨';
        } elseif ($time > 5 && $time <= 8) {
            return '早晨';
        } elseif ($time > 8 && $time <= 13) {
            return '中午';
        } elseif ($time > 13 && $time <= 18) {
            return '下午';
        } else {
            return '晚上';
        }
    }
}

if (!function_exists('def_str_filter')) {
    /**
     * @author Domino <m18434900825@163.com>
     * @title 字符串处理
     * @time 2021/1/26 002613:00
     */
    function def_str_filter($value)
    {
        if (!is_array($value)) {
            $value = explode(',', $value);
        }
        $value = array_unique(array_filter($value));
        return implode(',', $value);
    }
}

if (!function_exists('def_arr_filter')) {
    /**
     * @author Domino <m18434900825@163.com>
     * @title 数组处理
     * @time 2021/1/26 002613:00
     */
    function def_arr_filter($value)
    {
        if (!is_array($value)) {
            $value = (array)explode(',', $value);
        }
        return array_unique(array_filter($value));
    }
}

if (!function_exists('def_str_compress')) {
    /**
     * 字符串压缩
     * @param     $content
     * @param int $level
     * @return string
     */
    function def_str_compress($content, $level = 1)
    {
        ini_set("memory_limit", "-1");
        $content = base64_encode(gzcompress($content, $level));
        return (string)$content;
    }
}
if (!function_exists('def_str_decompress')) {
    /**
     * 字符串解压
     * @param $content
     * @return string
     */
    function def_str_decompress($content)
    {
        ini_set("memory_limit", "-1");
        $content = @gzuncompress(base64_decode($content));
        return (string)$content;
    }
}

if (!function_exists('get_client_ip_addr')) {
    /**
     * 获取客户端ip真实ip地址（排除代理情况）
     */
    function get_client_ip_addr()
    {
        if (isset($_SERVER)){
            if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])){
                $realip = $_SERVER["HTTP_X_FORWARDED_FOR"];
            } else if (isset($_SERVER["HTTP_CLIENT_IP"])) {
                $realip = $_SERVER["HTTP_CLIENT_IP"];
            } else {
                $realip = $_SERVER["REMOTE_ADDR"];
            }
        } else {
            if (getenv("HTTP_X_FORWARDED_FOR")){
                $realip = getenv("HTTP_X_FORWARDED_FOR");
            } else if (getenv("HTTP_CLIENT_IP")) {
                $realip = getenv("HTTP_CLIENT_IP");
            } else {
                $realip = getenv("REMOTE_ADDR");
            }
        }
        return $realip;
    }
}
