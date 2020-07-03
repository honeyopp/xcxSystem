<?php

// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------
// 应用公共文件


function getImg($img, $type = '')
{
    $img = str_replace('\\', '/', $img);
    if (empty($type)) return $img;
    $imgs = explode('.', $img);
    $imgs[count($imgs) - 1] = $type . '.' . $imgs[count($imgs) - 1];
    return join('.', $imgs);
}

function getWeek($date)
{
    $t = strtotime($date);
    $weekarray = array("周日", "周一", "周二", "周三", "周四", "周五", "周六");
    return $weekarray[date('w', $t)];
}

function getDateArr($num)
{
    $start = $_SERVER['REQUEST_TIME'];
    $end = $start + $num * 86400;
    $return = [];
    for ($i = $start; $i <= $end; $i = $i + 86400) {
        $return[] = date('Y-m-d', $i);
    }
    return $return;
}

function getDates($num = 30)
{
    $start = $_SERVER['REQUEST_TIME'];
    $end = $start + $num * 86400;
    $weekarray = array("周日", "周一", "周二", "周三", "周四", "周五", "周六");
    $today = date('Y-m-d', $start);
    $date = [];
    for ($i = $start; $i <= $end; $i = $i + 86400) {
        $day = date('Y-m-d', $i);
        $w = date('w', $i);

        if ($day == $today) {
            $day2 = '今天';
        } else {
            $day2 = date('m-d', $i);
        }
        $date[] = [
            'day' => $day,
            'w' => $weekarray[$w],
            'day2' => $day2
        ];
    }
    return $date;
}

function getDate60()
{
    return getDates(60);
}


function formatDateTime($dataTime)
{
    $t = $_SERVER['REQUEST_TIME'] - $dataTime;

    if ($t < 360) {
        return '刚刚';
    }
    if ($t < 720) {
        return '10分前';
    }
    if ($t < 3600) {
        return '1小时内';
    }
    if ($t < 86400) {
        return '今天';
    }
    if ($t < 86400 * 2) {
        return '昨天';
    }
    if ($t < 86400 * 3) {
        return '前天';
    }
    if ($t < 86400 * 7) {
        return '一周内';
    }
    if ($t < 86400 * 15) {
        return '半月内';
    }
    if ($t < 86400 * 30) {
        return '一月内';
    }
    if ($t < 86400 * 90) {
        return '三月内';
    }
    if ($t < 86400 * 180) {
        return '半年内';
    }
    if ($t < 86400 * 365) {
        return '一年内';
    }
    if ($t >= 86400 * 365) {
        return '一年以上';
    }
}

/*
 * 经度纬度 转换成距离
 * $lat1 $lng1 是 数据的经度纬度
 * $lat2,$lng2 是获取定位的经度纬度
 */

function rad($d)
{
    return $d * 3.1415926535898 / 180.0;
}

function getDistanceNone($lat1, $lng1, $lat2, $lng2)
{
    $EARTH_RADIUS = 6378.137;
    $radLat1 = rad($lat1);
    //echo $radLat1;  
    $radLat2 = rad($lat2);
    $a = $radLat1 - $radLat2;
    $b = rad($lng1) - rad($lng2);
    $s = 2 * asin(sqrt(pow(sin($a / 2), 2) + cos($radLat1) * cos($radLat2) * pow(sin($b / 2), 2)));
    $s = $s * $EARTH_RADIUS;
    $s = round($s * 10000);
    return $s;
}

function getDistance($lat1, $lng1, $lat2, $lng2)
{
    $s = getDistanceNone($lat1, $lng1, $lat2, $lng2);
    $s = $s / 10000;
    if ($s < 1) {
        $s = round($s * 1000);
        $s .= 'm';
    } else {
        $s = round($s, 2);
        $s .= 'km';
    }
    return $s;
}

/**
 * 判断输入的字符串是否是一个合法的电话号码（仅限中国大陆）
 *
 * @param string $string
 * @return boolean
 */
function isPhone($string)
{
    if (preg_match('/^[0,4]\d{2,3}-\d{7,8}$/', $string))
        return true;
    return false;
}

/**
 * 判断输入的字符串是否是一个合法的手机号(仅限中国大陆)
 *
 * @param string $string
 * @return boolean
 */
function isMobile($string)
{
    return ctype_digit($string) && (11 == strlen($string)) && ($string[0] == 1);
}

function SecurityEditorHtml($str)
{
    $farr = array(
        "/\s+/", //过滤多余的空白 
        "/<(\/?)(script|i?frame|style|html|body|title|link|meta|\?|\%)([^>]*?)>/isU",
        "/(<[^>]*)on[a-zA-Z]+\s*=([^>]*>)/isU"
    );
    $tarr = array(
        " ",
        "＜\\1\\2\\3＞",
        "\\1\\2",
    );
    $str = preg_replace($farr, $tarr, $str);
    return $str;
}


//加解密函数

function authcode($string, $operation = 'ENCODE', $key = '', $expiry = 0)
{
    // 动态密匙长度，相同的明文会生成不同密文就是依靠动态密匙   
    $ckey_length = 4;

    // 密匙   
    $key = md5($key ? $key : config('auth.code'));

    // 密匙a会参与加解密   
    $keya = md5(substr($key, 0, 16));
    // 密匙b会用来做数据完整性验证   
    $keyb = md5(substr($key, 16, 16));
    // 密匙c用于变化生成的密文   
    $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length) :
        substr(md5(microtime()), -$ckey_length)) : '';
    // 参与运算的密匙   
    $cryptkey = $keya . md5($keya . $keyc);
    $key_length = strlen($cryptkey);
    // 明文，前10位用来保存时间戳，解密时验证数据有效性，10到26位用来保存$keyb(密匙b)， 
//解密时会通过这个密匙验证数据完整性   
    // 如果是解码的话，会从第$ckey_length位开始，因为密文前$ckey_length位保存 动态密匙，以保证解密正确   
    $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) :
        sprintf('%010d', $expiry ? $expiry + time() : 0) . substr(md5($string . $keyb), 0, 16) . $string;
    $string_length = strlen($string);
    $result = '';
    $box = range(0, 255);
    $rndkey = array();
    // 产生密匙簿   
    for ($i = 0; $i <= 255; $i++) {
        $rndkey[$i] = ord($cryptkey[$i % $key_length]);
    }
    // 用固定的算法，打乱密匙簿，增加随机性，好像很复杂，实际上对并不会增加密文的强度   
    for ($j = $i = 0; $i < 256; $i++) {
        $j = ($j + $box[$i] + $rndkey[$i]) % 256;
        $tmp = $box[$i];
        $box[$i] = $box[$j];
        $box[$j] = $tmp;
    }
    // 核心加解密部分   
    for ($a = $j = $i = 0; $i < $string_length; $i++) {
        $a = ($a + 1) % 256;
        $j = ($j + $box[$a]) % 256;
        $tmp = $box[$a];
        $box[$a] = $box[$j];
        $box[$j] = $tmp;
        // 从密匙簿得出密匙进行异或，再转成字符   
        $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
    }
    if ($operation == 'DECODE') {
        // 验证数据有效性，请看未加密明文的格式   
        if ((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) &&
            substr($result, 10, 16) == substr(md5(substr($result, 26) . $keyb), 0, 16)
        ) {
            return substr($result, 26);
        } else {
            return '';
        }
    } else {
        // 把动态密匙保存在密文里，这也是为什么同样的明文，生产不同密文后能解密的原因   
        // 因为加密后的密文可能是一些特殊字符，复制过程可能会丢失，所以用base64编码   
        return $keyc . str_replace('=', '', base64_encode($result));
    }

    //替换url包成url参数唯一性；

}

//把用户消费记录way字段 转换汉字；


//二维数组转换函数

//参数  1 数组 2 要排序的值 3 排序方式

function array_sort($arr, $keys, $type = 'asc')
{
    $keysvalue = $new_array = array();
    foreach ($arr as $k => $v) {
        $keysvalue[$k] = $v[$keys];
    }
    if ($type == 'asc') {
        asort($keysvalue);
    } else {
        arsort($keysvalue);
    }
    reset($keysvalue);
    foreach ($keysvalue as $k => $v) {
        $new_array[] = $arr[$k];
    }
    return $new_array;
}
