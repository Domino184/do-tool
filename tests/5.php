<?php
/**
 * ------------------------------------------------------
 * | Domino <m18434900825@163.com>
 * ------------------------------------------------------
 */
declare(strict_types=1);

#文件下载（支持断点续传）
class FileDownload
{
    #下载速度
    private $_speed = 10;

    /**
     * @desc 下载文件
     *
     * @param $file   string 下载的文件路径
     * @param $name   string 保存文件时的文件名，不写则最终下载文件默认为原文件名
     * @param $reload bool 是否使用断点续传方式下载
     */
    public function download($file, $name = '', $reload = false)
    {
        if (file_exists($file))  #判断文件是否存在
        {
            if ($name == '')     #判断命名参数是否存在
            {
                $name = basename($file);    #采用原文件名进行存储
            }
            $fHandle  = fopen($file, 'rb');   #只读方式打开;为移植性考虑，使用b标记打开文件（不同系统有不同换行符）
            $fileSize = filesize($file);    #文件大小
            $ranges   = $this->getRange($fileSize);  #断点续传时，先查看下载的区间范围
            header('cache-control:public');         #可以被任何缓存所缓存
            header('content-type:application/octet-stream');  #告诉浏览器响应的对象的类型（字节流、浏览器默认使用下载方式处理）
            header('content-disposition:attachment; filename=' . $name); #不打开此文件，刺激浏览器弹出下载窗口
            #判断是否使用续传方式进行下载
            #且请求头ranges不能为null（为null表示第一次请求下载）
            if ($reload && $ranges != null) {
                header('HTTP/1.1 206 Partial Content');     #发送自定义报文 206续传状态码
                header('Accept-Ranges:bytes');              #表明服务器支持Range请求，所支持的单位是字节
                # 剩余长度
                header(sprintf('content-length:%u', $ranges['end'] - $ranges['start']));
                # range信息
                header(sprintf('content-range:bytes %s-%s/%s', $ranges['start'], $ranges['end'], $fileSize));
                # fHandle指针跳到断点位置
                fseek($fHandle, sprintf('%u', $ranges['start']));
            } else {
                header('HTTP/1.1 200 OK');
                header('content-length:' . $fileSize);
            }
            while (!feof($fHandle)) {
                echo fread($fHandle, round($this->_speed * 1024, 0));
                ob_flush();    #把数据从PHP的缓冲中释放出来
                sleep(2); // 用于测试,减慢下载速度
            }
            ($fHandle != null) && fclose($fHandle);
        } else {
            #没文件
            header("HTTP/1.1 404 Not Found");
            return false;
        }
    }

    /**
     * @desc 获取请求头部range信息
     *
     * @param $fileSize int 该文件的大小
     *
     * @return array|null 返回range信息或者null
     */
    public function getRange($fileSize)
    {
        if (isset($_SERVER['HTTP_RANGE']) && !empty($_SERVER['HTTP_RANGE'])) {
            #请求头部range信息  Range: bytes=41078-\r\n
            $range = $_SERVER['HTTP_RANGE'];
            $range = preg_replace('/[\s|,].*/', '', $range);
            $range = explode('-', substr($range, 6));       #只需将41078-进行分割变成数组
            #断点续传头部range信息都是为 4444- 这种形式 ，因此切割后形成的数组就只有两个元素
            $range = array_combine(['start', 'end'], $range);
            if (empty($range['start'])) {
                $range['start'] = 0;
            }
            if (empty($range['end'])) {
                $range['end'] = $fileSize;
            }
            return $range;
        }
        return null;    #第一次请求没有range信息
    }

    /**
     * @desc 设置文件下载速度
     *
     * @param $speed int 下载速度
     */
    public function setSpeed($speed)
    {
        if (is_numeric($speed) && $speed > 16 && $speed < 4096) {
            $this->_speed = $speed;
        }
    }

}

