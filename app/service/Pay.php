<?php

namespace app\service;

class Pay
{
	//支付
	public function pay($orderInfo)
	{	

		switch ($orderInfo['payType']) {
			case 1:
				return (new AliPay())->pay($orderInfo);
				break;
			default:
				(new WxPay())->pay($orderInfo);
				break;
		}
	}
}