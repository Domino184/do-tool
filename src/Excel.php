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

use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use PhpOffice\PhpSpreadsheet\Writer\Html;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Excel
{
    /**
     * @title 唯一实例
     * @var null
     */
    private static $instance = null;

    /**
     * 单元格宽度
     * @var int
     */
    private $width = 20;

    /**
     * 单元格高度
     * @var int
     */
    private $height = 20;

    /**
     * 字体大小
     * @var int
     */
    private $fontSize = 8;

    /**
     * 表头背景颜色
     * @var string
     */
    private $headerBgColor = 'DDEBF7';

    /**
     * 初始化行 （标题、备注、表头）
     * @var int
     */
    private $topNum = 1;

    /**
     * 导出标题
     * @var string
     */
    private $title = 'Sheet1';

    /**
     * 设置header
     * @var array
     */
    private $header = [];

    /**
     * 设置数据
     * @var array
     */
    private $data = [];

    /**
     * 设置保存文件格式
     * @var string
     */
    private $suffix = 'xlsx';

    /**
     * 单元格样式
     * @var array
     */
    private $styleArray = [
        'font'      => [
            'name' => '宋体',
        ],
        'alignment' => [
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            'wrapText'   => true, // 换行
        ],
        'borders'   => [
            'left'   => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            ],
            'right'  => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            ],
            'bottom' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            ],
        ],
    ];

    /**
     * 表头单元格样式
     * @var array
     */
    private $styleHeaderArray = [
        'font'      => [
            'name' => '宋体',
        ],
        'alignment' => [
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP,
            'wrapText'   => true, // 换行
        ],
        'borders'   => [
            'top'    => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            ],
            'left'   => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            ],
            'right'  => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            ],
            'bottom' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                'color'       => [
                    'rgb' => '42A642' // 绿色
                ],
            ],
        ],

    ];

    /**
     * 构建方法私有化
     * Excel constructor.
     */
    private function __construct()
    {
    }

    /**
     * @return Excel|null
     * @author Domino <m18434900825@163.com>
     * @title  单一入口
     * @time   2021/3/17 001711:29
     */
    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * 设置标题
     * @param string $val
     * @return $this
     */
    public function setTitle(string $val)
    {
        $this->title = $val;
        return $this;
    }

    /**
     * @param array $val
     * @return $this
     */
    public function setHeader(array $val)
    {
        $this->header = $val;
        return $this;
    }

    /**
     * @param array $val
     * @return $this
     */
    public function setData(array $val)
    {
        $this->data = $val;
        return $this;
    }

    /**
     * @param string $val
     * @return $this
     */
    public function setSuffix(string $val)
    {
        $this->suffix = strtolower($val);
        return $this;
    }

    /**
     * @param string $filename 输出文件名
     * @param string $path 保存路径
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function export(string $filename = '', string $path = '')
    {
        // todo 多个工作区间导出、数据判断（文字、图片、链接）
        $spreadsheet = new Spreadsheet();
        $filename = !empty($filename) ? $filename : time();
        if (empty($this->header)) {
            die('表头不能为空');
        }
        if (empty($this->data)) {
            die('数据不能为空');
        }
        $this->maxCell = Coordinate::stringFromColumnIndex(count($this->header));
        // 设置基础信息
        $spreadsheet->getProperties()
            ->setCreator("Neo")
            ->setLastModifiedBy("Neo")
            ->setTitle($this->title)
            ->setSubject($this->title)
            ->setDescription("")
            ->setKeywords($this->title)
            ->setCategory("");
        // 设置单元区间
        $spreadsheet->setActiveSheetIndex(0);
        // 设置字体大小
        $spreadsheet->getDefaultStyle()->getFont()->setSize($this->fontSize);
        $sheet = $spreadsheet->getActiveSheet();
        // 设置单元格标题
        $sheet->setTitle($this->title);
        // 设置默认宽度 && 高度
        $sheet->getDefaultRowDimension()->setRowHeight($this->height);
        // 表头文字加粗
        $sheet->getStyle(Coordinate::stringFromColumnIndex($this->topNum) . $this->topNum . ':' . $this->maxCell . $this->topNum)
            ->getFont()
            ->setBold(true);
        // 设置表头背景颜色
        $sheet->getStyle(Coordinate::stringFromColumnIndex($this->topNum) . $this->topNum . ':' . $this->maxCell . $this->topNum)
            ->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()
            ->setRGB($this->headerBgColor);

        // 设置表头数据
        foreach ($this->header as $k => $v) {
            $sheet->getStyle(Coordinate::stringFromColumnIndex($k + 1) . $this->topNum)
                ->applyFromArray($this->styleHeaderArray); // 设置边框
            $sheet->setCellValueExplicit(Coordinate::stringFromColumnIndex($k + 1) . $this->topNum, $v, DataType::TYPE_STRING);
        }

        // 设置数据
        $size = ceil(count($this->data) / 500);
        for ($i = 0; $i < $size; $i++) {
            $buffer = array_slice($this->data, $i * 500, 500);
            foreach ($buffer as $k => $v) {
                $row = $k + $this->topNum + 1; // 行
                foreach ($v as $m => $n) {
                    $sheet->getStyle(Coordinate::stringFromColumnIndex($m + 1) . $row)->applyFromArray($this->styleArray); // 设置边框
                    $sheet->setCellValueExplicit(Coordinate::stringFromColumnIndex($m + 1) . $row, $n . "\t", DataType::TYPE_STRING);
                }
            }
        }

        // 设置表头自动过滤（起始节点-结束节点）
        $sheet->setAutoFilter(Coordinate::stringFromColumnIndex($this->topNum) . $this->topNum . ':' . $this->maxCell . ($this->topNum + count($this->data)));

        // 直接输出下载
        switch ($this->suffix) {
            case 'xlsx':
                $writer = new Xlsx($spreadsheet);
                if (!empty($path)) {
                    $writer->save($path);
                } else {
                    header('Access-Control-Allow-Origin: *');
                    header('Access-Control-Allow-Methods: POST,PUT,GET,DELETE');
                    header('Access-Control-Allow-Headers: User-Agent, Keep-Alive, Origin, No-Cache, X-Requested-With, If-Modified-Since, Pragma, Last-Modified, Cache-Control, Expires, Content-Type, X-E4M-With');
                    header('Access-Control-Allow-Credentials: true');
                    header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8;");
                    header("Content-Disposition: inline;filename=\"{$filename}.xlsx\"");
                    header('Cache-Control: max-age=0');
                    $writer->save('php://output');
                }
                exit();
                break;
            case 'xls':
                $writer = new Xls($spreadsheet);
                if (!empty($path)) {
                    $writer->save($path);
                } else {
                    header('Access-Control-Allow-Origin: *');
                    header('Access-Control-Allow-Methods: POST,PUT,GET,DELETE');
                    header('Access-Control-Allow-Headers: User-Agent, Keep-Alive, Origin, No-Cache, X-Requested-With, If-Modified-Since, Pragma, Last-Modified, Cache-Control, Expires, Content-Type, X-E4M-With');
                    header('Access-Control-Allow-Credentials: true');
                    header("Content-Type:application/vnd.ms-excel;charset=utf-8;");
                    header("Content-Disposition:inline;filename=\"{$filename}.xls\"");
                    header('Cache-Control: max-age=0');
                    $writer->save('php://output');
                }
                exit();
                break;
            case 'csv':
                $writer = new Csv($spreadsheet);
                if (!empty($path)) {
                    $writer->save($path);
                } else {
                    header('Access-Control-Allow-Origin: *');
                    header('Access-Control-Allow-Methods: POST,PUT,GET,DELETE');
                    header('Access-Control-Allow-Headers: User-Agent, Keep-Alive, Origin, No-Cache, X-Requested-With, If-Modified-Since, Pragma, Last-Modified, Cache-Control, Expires, Content-Type, X-E4M-With');
                    header('Access-Control-Allow-Credentials: true');
                    header("Content-type:text/csv;charset=utf-8;");
                    header("Content-Disposition:attachment; filename={$filename}.csv");
                    header('Cache-Control: max-age=0');
                    $writer->save('php://output');
                }
                exit();
                break;
            case 'html':
                $writer = new Html($spreadsheet);
                if (!empty($path)) {
                    $writer->save($path);
                } else {
                    header('Access-Control-Allow-Origin: *');
                    header('Access-Control-Allow-Methods: POST,PUT,GET,DELETE');
                    header('Access-Control-Allow-Headers: User-Agent, Keep-Alive, Origin, No-Cache, X-Requested-With, If-Modified-Since, Pragma, Last-Modified, Cache-Control, Expires, Content-Type, X-E4M-With');
                    header('Access-Control-Allow-Credentials: true');
                    header("Content-Type:text/html;charset=utf-8;");
                    header("Content-Disposition:attachment;filename=\"{$filename}.{$this->suffix}\"");
                    header('Cache-Control: max-age=0');
                    $writer->save('php://output');
                }
                exit();
                break;
        }

        return true;
    }

    /**
     * 导入
     * @param string $filePath      excel的服务器存放地址 可以取临时地址
     * @param int    $startRow      开始和行数
     * @param bool   $hasImg        导出的时候是否有图片
     * @param string $suffix        格式
     * @param string $imageFilePath 作为临时使用的 图片存放的地址
     * @return array|mixed
     * @throws \Exception
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    public function import($filePath, $startRow = 1, $hasImg = false, $suffix = 'Xlsx', $imageFilePath = null)
    {
        if ($hasImg) {
            if ($imageFilePath == null) {
                $imageFilePath = '.' . DIRECTORY_SEPARATOR . 'execlImg' . DIRECTORY_SEPARATOR . \date('Y-m-d') . DIRECTORY_SEPARATOR;
            }
            if (!file_exists($imageFilePath)) {
                //如果目录不存在则递归创建
                mkdir($imageFilePath, 0777, true);
            }
        }
        $reader = IOFactory::createReader($suffix);
        if (!$reader->canRead($filePath)) {
            die('不能读取Excel');
        }

        $spreadsheet = $reader->load($filePath);
        $sheetCount  = $spreadsheet->getSheetCount();// 获取sheet(工作表)的数量

        // 获取所有的sheet表格数据
        $excleDatas  = [];
        $emptyRowNum = 0;
        for ($i = 0; $i < $sheetCount; $i++) {
            $objWorksheet = $spreadsheet->getSheet($i); // 读取excel文件中的第一个工作表
            $data         = $objWorksheet->toArray();
            if ($hasImg) {
                foreach ($objWorksheet->getDrawingCollection() as $drawing) {
                    [$startColumn, $startRow] = Coordinate::coordinateFromString($drawing->getCoordinates());
                    $imageFileName = $drawing->getCoordinates() . mt_rand(1000, 9999);
                    $imageFileName .= '.' . $drawing->getExtension();
                    $source        = imagecreatefromjpeg($drawing->getPath());
                    imagejpeg($source, $imageFilePath . $imageFileName);

                    $startColumn                       = $this->ABC2decimal($startColumn);
                    $data[$startRow - 1][$startColumn] = $imageFilePath . $imageFileName;
                }
            }
            $excleDatas[$i] = $data; // 多个sheet的数组的集合
        }

        // 这里我只需要用到第一个sheet的数据，所以只返回了第一个sheet的数据
        $returnData = $excleDatas ? array_shift($excleDatas) : [];

        // 第一行数据就是空的，为了保留其原始数据，第一行数据就不做array_fiter操作；
        $returnData = $returnData && isset($returnData[$startRow]) && !empty($returnData[$startRow]) ? array_filter($returnData) : $returnData;

        return $returnData;
    }

    private function ABC2decimal($abc)
    {
        $ten = 0;
        $len = strlen($abc);
        for ($i = 1; $i <= $len; $i++) {
            $char = substr($abc, 0 - $i, 1);//反向获取单个字符

            $int = ord($char);
            $ten += ($int - 65) * pow(26, $i - 1);
        }

        return $ten;
    }

    /**
     * @author Domino <m18434900825@163.com>
     * @title  私有化克隆
     * @time   2021/3/19 001913:21
     */
    private function __clone()
    {
        // TODO: Implement __clone() method.
    }

    /**
     * 私有化析构方法
     */
    private function __destruct()
    {
        // TODO: Implement __destruct() method.
    }
}
