<?php
declare(strict_types=1);

namespace DoTool;

class Tool
{
    /**
     * 对象转化为数组
     * @param $input
     * @return array|array[]
     */
    public static function objectToArray($input)
    {
        if (null === $input || '' === $input) {
            $output = [];
        }
        $recursive = function ($input) use (&$recursive) {
            if (\is_object($input)) {
                $input = get_object_vars($input);
            }
            if (!\is_array($input)) {
                return $input;
            }
            $data = [];
            foreach ($input as $k => $v) {
                $data[$k] = $recursive($v);
            }

            return $data;
        };
        $output    = $recursive($input);
        if (!\is_array($output)) {
            $output = [$output];
        }
        return $output;
    }

    /**
     * 加密成json字符串
     * @param array $data
     * @return string
     */
    public static function jsonEncode(array $data)
    {
        return (string)json_encode((array)$data, JSON_UNESCAPED_UNICODE);
    }

    /**
     * 解析json字符串
     * @param string $data
     * @return array
     */
    public static function jsonDecode(string $data)
    {
        return (array)json_decode((string)$data, true);
    }
}