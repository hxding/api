<?php


namespace App\Http\Library;

/**
 * 
 */
class Helper
{
    /**
     * T 生成请求 sign
     */
    public static function generateTSing($data, $key)
    {
        $signStr = '';
        foreach($data as $k => $v){
            $signStr .= $k . '=' . $v . '&';
        }
        $signStr = $signStr . 'key=' . $key;
        return md5($signStr);
    }
}
