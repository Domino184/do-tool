<?php

/*
 * +----------------------------------------------------------------------
 * | do-tool工具库
 * +----------------------------------------------------------------------
 * | Author: Domino184 <m18434900825@163.com>
 * +----------------------------------------------------------------------
 */

declare(strict_types=1);

namespace DoTool;

/**
 * 通用树型类
 */
class Tree
{
    /**
     * 配置参数
     * @var array
     */
    protected static $config = [
        'id'                 => 'id',        // id名称
        'pid'                => 'pid',       // pid名称
        'child'              => 'childs',    // 子元素键名
        'name'               => 'name',      // 下拉列表的选项名
        'icon'               => '├',         // 下拉列表的图标
        'placeholder'        => '&nbsp;',    // 下拉列表的占位符
        'placeholder_number' => 3,           // 下拉列表的占位符数量
    ];

    /**
     * 架构函数
     * @param array $config
     */
    public function __construct($config = [])
    {
        self::$config = array_merge(self::$config, $config);
    }

    /**
     * 配置参数
     * @param array $config
     * @return object
     */
    public static function config($config = [])
    {
        if (!empty($config)) {
            return self::$config = array_merge(self::$config, $config);
        }
    }

    /**
     * 将数据集格式化成树形结构
     * @param array $data         原始数据
     * @param int   $pid          父级id
     * @param int   $limitLevel   限制返回几层，0为不限制
     * @param int   $currentLevel 当前层数
     * @return array
     */
    public static function toTree($data = [], $pid = 0, $limitLevel = 0, $currentLevel = 0)
    {
        $trees = [];
        $data  = array_values($data);

        foreach ($data as $k => $v) {
            if ($v[self::$config['pid']] == $pid) {
                if ($limitLevel > 0 && $limitLevel == $currentLevel) {
                    return $trees;
                }
                unset($data[$k]);
                $childs = self::toTree($data, $v[self::$config['id']], $limitLevel, ($currentLevel + 1));
                if (!empty($childs)) {
                    $v[self::$config['child']] = $childs;
                }
                $trees[] = $v;
            }
        }

        return $trees;
    }
}
