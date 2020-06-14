<?php

namespace app\api\service;

class IosPay
{

	/**
	 * 验证AppStore内付
	 * @param  string $receipt_data 付款后凭证
	 * @return array                验证是否成功
	 */
	public function validate_apple_pay($receipt_data)
	{
		/**
		 * 21000 App Store不能读取你提供的JSON对象
		 * 21002 receipt-data域的数据有问题
		 * 21003 receipt无法通过验证
		 * 21004 提供的shared secret不匹配你账号中的shared secret
		 * 21005 receipt服务器当前不可用
		 * 21006 receipt合法，但是订阅已过期。服务器接收到这个状态码时，receipt数据仍然会解码并一起发送
		 * 21007 receipt是Sandbox receipt，但却发送至生产系统的验证服务
		 * 21008 receipt是生产receipt，但却发送至Sandbox环境的验证服务
		 */

		// 验证参数
		if (strlen($receipt_data) < 20)
		{
			$result = [
				'status' => false,
				'message' => '非法参数',
			];
			return $result;
		}

		// 请求验证
		$html = $this->acurl($receipt_data);
		$data = json_decode($html, true);

		// 如果是沙盒数据 则验证沙盒模式
		if ($data['status'] == '21007')
		{
			// 请求验证
			$html            = $this->acurl($receipt_data, 1);
			$data            = json_decode($html, true);
			// $data['sandbox'] = '1';
		}

		// 判断是否购买成功
		if (intval($data['status']) === 0)
		{

			$result = [
				'status' => true,
				'message' => '购买成功',
				'money' => $this->getIosPayMoney($data),
				'product_id' => $this->getIosPayMoney($data,1),
			];
		}
		else
		{
			$result = [
				'status' => false,
				'message' => '购买失败 status:' . $data['status'],
				'money' => 0,
				'product_id' => 0,
			];
		}
		return $result;
	}

	/**
	 * Notes:    获取当前的充值金额
	 * @Author   hui
	 * @DateTime 2019-09-18
	 * @param    [type]     $data [description]
	 * @return   [type]           [description]
	 */
	public function getIosPayMoney($data,$product_id = 0)
	{
		if (isset($data['receipt']['in_app'][0]['product_id']))
		{

			$ios_currency = [
			    'com.fableUp.artedu.1lb'=>1,
			    'com.fableUp.artedu.16b'=>6,
			    'com.fableUp.artedu.25lb'=>25,
			    'com.fableUp.artedu.68lb'=>68,
			    'com.fableUp.artedu.118lb'=>118,
			    'com.fableUp.artedu.228lb'=>228,
			    'com.fableUp.artedu.328lb'=>328,
			    'com.fableUp.artedu.588lb'=>588,
			    'com.fableUp.artedu.998lb'=>998
			];

			$config = $data['receipt']['in_app'][0]['product_id'];
			if ($product_id) {
				//返回ios返回的product_id
				return $config;
			}
			return $ios_currency[$config];

		}
		return 0;
	}

	//请求
	public function acurl($receipt_data, $sandbox = 0)
	{
		//小票信息
		$POSTFIELDS = ["receipt-data" => $receipt_data];
		$POSTFIELDS = json_encode($POSTFIELDS);
		//正式购买地址 沙盒购买地址
		$url_buy     = "https://buy.itunes.apple.com/verifyReceipt";
		$url_sandbox = "https://sandbox.itunes.apple.com/verifyReceipt";
		$url         = $sandbox ? $url_sandbox : $url_buy;

		//简单的curl
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $POSTFIELDS);
		$result = curl_exec($ch);
		curl_close($ch);
		return $result;
	}

}