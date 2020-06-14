<?php

namespace app\service;

use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;
use app\logic\Setting;

class AliSms
{
    public function sendSms($data)
    {
        $setting = new Setting();

        $accessKeyId     = $setting->getSettingContent('smsKey');
        $accessKeySecret = $setting->getSettingContent('smsSecret');
        $SignName        = $setting->getSettingContent('smsSign');
        $template        = isset($data['type']) && $data['type'] == 1 ? 'smsForgetTemplateCode' : 'smsLoginTemplateCode';
        
        if (!$accessKeyId || !$accessKeySecret || !$SignName || !$template) {
            return 0;
        }
        $templateCode = $setting->getSettingContent($template);


        AlibabaCloud::accessKeyClient($accessKeyId, $accessKeySecret)
            ->regionId('cn-hangzhou') // replace regionId as you need
            ->asDefaultClient();

        try {

            $result = AlibabaCloud::rpc()
                ->product('Dysmsapi')
            // ->scheme('https') // https | http
                ->version('2017-05-25')
                ->action('SendSms')
                ->method('POST')
                ->options([
                    'query' => [
                        'RegionId' => "default",
                        'PhoneNumbers' => $data['mobile'], //手机号
                        'SignName' => $SignName, //签名
                        'TemplateCode' => $templateCode, //模板
                        'TemplateParam' => json_encode(['code' => $data['code']]),
                    ],
                ])
                ->request();

            $res = $result->toArray();
            return isset($res['Code']) && $res['Code'] == 'OK' ? 1 : 0;

        }
        catch (ClientException $e)
        {
            //echo $e->getErrorMessage() . PHP_EOL;
            return ['Code' => $e->getErrorCode(), 'Message' => $e->getMessage()];
        }
        catch (ServerException $e)
        {
            //echo $e->getErrorMessage() . PHP_EOL;
            return ['Code' => $e->getErrorCode(), 'Message' => $e->getMessage()];
        }
        return $result->toArray();
    }
}
