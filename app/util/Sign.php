<?php
namespace app\util;

class Sign
{
	/**
	 * Notes:   生成签名
	 * @Author   hui
	 * @DateTime 2019-08-06
	 * @return   [type]     [description]
	 */
	public static function getSign($params)
	{
        //对参与签名数组进行排序
        ksort($params);

        $paramstring = '';
        if(is_array($params)) {
            foreach ($params as $key => $value) {
                if (strlen($paramstring) == 0) {
                    $paramstring .= $key . '=' . $value;
                } else {
                    $paramstring .= '&' . $key . '=' . $value;
                }
            }
        }

        //在签名串尾部再追加32位随机串
        $paramstring .= '&key=B@Y3PoRsHyXPm^MW*SIv%cyYD1n9cmIw';

        return strtoupper(md5($paramstring));
	}

	/**
	 * Notes:    验证签名
	 * @Author   hui
	 * @DateTime 2019-08-06
	 * @return   [type]     [description]
	 */
	public static function checkSign($paramstring,$signature)
	{
        if(strtoupper(md5($paramstring)) != $signature) {
        	return 0;
		}
	}
}