<?php

namespace app\service;

use app\logic\Setting;

class WxPay
{

    public function pay($data)
    {

        $conf = [
            'app_id' => 'wechatPayAppId',
            'mch_id' => 'wechatPayMchId',
            'md5_key' => 'wechatPayMd5Key',
            'app_cert_pem' => 'wechatPayApiclientCert',
            'app_key_pem' => 'wechatPayApiclientKey',
            'sign_type' => 'wechatPaySignType',
            'notify_url' => 'wechatPayNotifyUrl',
        ];

        $setting = new Setting();

        $wechat = [];
        foreach ($conf as $key => $value)
        {
            $v = $setting->getSettingContent($value);

            if ($v)
            {
                //获取绝对路径
                if ($key == 'app_cert_pem' || $key == 'app_key_pem')
                {
                    $v = \think\facade\Filesystem::disk('public')->path($v);
                }
                $wechat[$key] = $v;
            }
            else
            {
                return false;
            }

        }
        //
        $config = [

            'use_sandbox' => $setting->getSettingContent('wechatPaySandbox'),
            'limit_pay' => [],
            'fee_type' => 'CNY',

        ];
        //
        $wxConfig = array_merge($wechat, $config);

        // 订单信息
        $payData = [
            'body' => $data['title'],
            'subject' => $data['title'],
            'trade_no' => $data['order_no'],
            'time_expire' => time() + 600, // 表示必须 600s 内付款
            'amount' => $data['amount_total'], // 微信沙箱模式，需要金额固定为3.01
            'return_param' => $data['user_id'],
            'client_ip' => isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1', // 客户地址
            'openid' => isset($data['open_id']) ? isset($data['open_id']) : "",
            'product_id' => $data['order_no'],

            // 如果是服务商，请提供以下参数
            'sub_appid' => '', //微信分配的子商户公众账号ID
            'sub_mch_id' => '', // 微信支付分配的子商户号
        ];

        // 使用
        try {
            $client = new \Payment\Client(\Payment\Client::WECHAT, $wxConfig);
            $res    = $client->pay(\Payment\Client::WX_CHANNEL_QR, $payData);
        }
        catch (InvalidArgumentException $e)
        {
            echo $e->getMessage();
            exit;
        }
        catch (\Payment\Exceptions\GatewayException $e)
        {
            echo $e->getMessage();
            exit;
        }
        catch (\Payment\Exceptions\ClassNotFoundException $e)
        {
            echo $e->getMessage();
            exit;
        }

        var_dump($res);
    }
}
