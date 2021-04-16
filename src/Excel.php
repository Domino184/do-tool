<?php
declare(strict_types=1);

namespace DoTool;

use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class Excel
{
    /**
     * @title 唯一实例
     * @var null
     */
    private static $instance = null;

    /**
     * 导出标题
     * @var string
     */
    private $title = '导出';

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
     * 初始化行 （标题、备注、表头）
     * @var int
     */
    private $topNum = 1;

    /**
     * 数据长度
     * @var int
     */
    private $length = 0;

    /**
     * 最大的数据单元
     * @var string
     */
    private $maxCell = 'A';

    /**
     * 选定的数据列
     * @var array
     */
    private $cells = [];

    /**
     * 输出格式
     * @var string
     */
    private $outputFormat = 'php://output';

    /**
     * 单元列
     * @var string[]
     */
    private $cellKey = [
        'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M',
        'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
        'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM',
        'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ',
    ];

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
     * @param string $val
     * @author Domino <m18434900825@163.com>
     * @title  设置字体输出格式
     * @time   2021/3/19 001913:19
     */
    public function setFontSize(int $val)
    {
        if ($val) $this->fontSize = $val;
        return $this;
    }

    /**
     * @param string $val
     * @author Domino <m18434900825@163.com>
     * @title  设置输出格式
     * @time   2021/3/17 001712:34
     */
    public function setOutputFormat(string $val)
    {
        if ($val) $this->outputFormat = $val;
        return $this;
    }

    /**
     * @param array  $header    表头 ['11', '22', '33']
     * @param array  $data      数据
     * @param string $title     标题
     * @param string $sheetName 单元名称
     * @param array  $info      第二行信息
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @author Domino <m18434900825@163.com>
     * @title  title
     * @time   2021/3/17 001712:19
     */
    public function export($data = [], $header = [], $title = '', $sheetName = '')
    {
        // todo 多个工作区间导出、数据判断（文字、图片、链接）
        $spreadsheet = new Spreadsheet();

        if (!$title) {
            $title = $this->title;
        } else {
            $this->title = $title;
        }
        if (empty($header)) {
            die('表头不能为空');
        }
        if (count($header) > count($this->cellKey)) {
            die('表头长度过长');
        }
        if (empty($data)) {
            die('数据不能为空');
        }
        if (!$sheetName) {
            $sheetName = $this->title;
        }
        $this->maxCell = $this->cellKey[count($header) - 1];
        $this->cells   = array_slice($this->cellKey, 0, count($header));
        $this->length  = count($data);
        // 设置基础信息
        $spreadsheet->getProperties()
            ->setCreator("Neo")
            ->setLastModifiedBy("Neo")
            ->setTitle($title)
            ->setSubject($sheetName)
            ->setDescription("")
            ->setKeywords($sheetName)
            ->setCategory("");
        // 设置单元区间
        $spreadsheet->setActiveSheetIndex(0);
        $sheet = $spreadsheet->getActiveSheet();
        // 设置字体
        $spreadsheet->getDefaultStyle()->getFont()->setSize($this->fontSize);
        // 设置单元格标题
        $sheet->setTitle($sheetName);
        // 设置默认宽度 && 高度
        $sheet->getDefaultRowDimension()->setRowHeight($this->height);
        // 表头文字加粗
        $sheet->getStyle('A1:' . $this->maxCell . '1')->getFont()->setBold(true);
        // 设置背景颜色
        $sheet->getStyle('A1:' . $this->maxCell . '1')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('DDEBF7');
        // 设置表头行高
        $sheet->getRowDimension(1)->setRowHeight($this->height);
        // 设置表头自动过滤
        $spreadsheet->getActiveSheet()->setAutoFilter('A1:' . $this->maxCell . ($this->topNum + $this->length));
        // 设置边框
//        $sheet->getStyle('A2:'. $this->maxCell . ($this->topNum + $this->length))->applyFromArray($this->styleArray);
        // 设置表头数据
        foreach ($this->cells as $k => $v) {
            $sheet->getStyle($v . '1')->applyFromArray($this->styleHeaderArray); // 设置边框
            $sheet->getColumnDimension($v)->setAutoSize(true); // 自适应宽度
            $sheet->setCellValue($v . '1', $header[$k]);
        }

        // 设置数据
        foreach ($this->cells as $k => $v) {
            foreach ($data as $m => $n) {
                $sheet->getRowDimension($m + 2)->setRowHeight($this->height); // 设置行高
                $sheet->getStyle($v . ($m + 2))->applyFromArray($this->styleArray); // 设置边框
                $str = '';
                if (isset($n[$k]) && !is_array($n[$k])) {
                    $str = $n[$k];
                } else if (isset($n[$k]) && is_array($n[$k])) {
                    foreach ($n[$k] as $value) {
                        $str .= $value . chr(10);
                    }
                }
                $format = $this->isValFormat($str);
                switch ($format) {
                    case 'phone':
                    case 'id_card':
                    case 'email':
                    case 'bank':
                        $sheet->setCellValue($v . ($this->topNum + 1 + $m), $str . "\t");
                        break;
                    case 'url':
                        $sheet->setCellValue($v . ($this->topNum + 1 + $m), $str);
                        $sheet->getCell($v . ($this->topNum + 1 + $m))->getHyperlink()->setUrl($str);
                        break;
                    default:
                        $sheet->setCellValue($v . ($this->topNum + 1 + $m), $str);
                        break;
                }
            }
        }
        // 导出
        $objWriter = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename  = $this->title . '.xlsx';
        ob_end_clean();
        ob_start();
        // 解决跨域
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: POST,PUT,GET,DELETE');
        header('Access-Control-Allow-Headers: User-Agent, Keep-Alive, Origin, No-Cache, X-Requested-With, If-Modified-Since, Pragma, Last-Modified, Cache-Control, Expires, Content-Type, X-E4M-With');
        header('Access-Control-Allow-Credentials: true');
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $objWriter->save($this->outputFormat);
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
     * @param string $val
     * @author Domino <m18434900825@163.com>
     * @title  格式化字符串
     * @time   2021/3/17 001716:44
     */
    private function isValFormat($val = '')
    {
        // 判断邮箱、手机号、身份证、银行卡号
        $format = 'text';
        if (is_scalar($val)) { // 检测是否为标量
            if (filter_var($val, FILTER_VALIDATE_EMAIL)) { // 邮箱
                $format = 'email';
            } else if (preg_match('/^1[3-9]\d{9}$/', (string)$val)) { // 手机号
                $format = 'mobile';
            } else if (preg_match('/(^[1-9]\d{5}(18|19|([23]\d))\d{2}((0[1-9])|(10|11|12))(([0-2][1-9])|10|20|30|31)\d{3}[0-9Xx]$)|(^[1-9]\d{5}\d{2}((0[1-9])|(10|11|12))(([0-2][1-9])|10|20|30|31)\d{3}$)/', (string)$val)) {
                $format = 'id_card';
            } else if (is_numeric($val) && strpos($val, '.') === false) { // 判断正整数
                if (check_bank_card($val)) $format = 'bank';
            } else if (filter_var($val, FILTER_SANITIZE_URL)) {
                $format = 'url';
            }
        }

        return $format;
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