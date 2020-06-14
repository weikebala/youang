<?php
namespace app\logic;

use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Vod\Vod;
use OSS\Core\OssException;
use OSS\OssClient;

class File
{

    public function __construct()
    {
        $this->accessKeyId     = (new Setting)->getSettingContent('aliPlayerKey');
        $this->accessKeySecret = (new Setting)->getSettingContent('aliPlayerSecret');
        $this->regionId        = (new Setting)->getSettingContent('aliPlayerRegionId');
        $this->Client          = 'Client';
    }

    //vod上传初始化
    public function initVodClient()
    {

        return AlibabaCloud::accessKeyClient($this->accessKeyId, $this->accessKeySecret)
            ->regionId($this->regionId)
            ->connectTimeout(1)
            ->timeout(3)
            ->name($this->Client);

    }

    //视频上传
    public function uploadVideo($file, $param)
    {

        switch ($param['channel'])
        {
            case 'alivod':

                if ($this->getAlivodChannel())
                {
                    return $this->aliVodUpload($file, $param);
                }
                else
                {
                    return 0;
                }
                break;
            default:
                return $this->videoLocalUpload($file, $param);
                break;
        }

    }

    //检测通道是否可用
    public function getAlivodChannel()
    {
        if (empty($this->accessKeyId) || empty($this->accessKeySecret) || empty($this->regionId))
        {
            return 0;
        }
        else
        {
            return 1;
        }
    }

    //视频本地上传
    public function videoLocalUpload($file, $param)
    {
        return \think\facade\Filesystem::disk('public')->putFile('tovideo', $file);
    }

    //上传至阿里云
    public function aliVodUpload($file)
    {

        $this->initVodClient();

        $fileName = $file->md5() . '.' . $file->extension();
        $title    = isset($param['title']) ? $param['title'] : 'demoVideo';
        $auth     = $this->CreateUploadVideoAuth($title, $fileName);

        $uploadAuth    = json_decode(base64_decode($auth['UploadAuth']), true);
        $UploadAddress = json_decode(base64_decode($auth['UploadAddress']), true);
        try {

            $ossClient = new OssClient(
                $uploadAuth['AccessKeyId'],
                $uploadAuth['AccessKeySecret'],
                $UploadAddress['Endpoint'],
                false,
                $uploadAuth['SecurityToken']
            );

            $ossClient->setTimeout(86400);
            $ossClient->setConnectTimeout(3);
            $ossClient->setUseSSL(false);

            $result = $ossClient->uploadFile($UploadAddress['Bucket'], $UploadAddress['FileName'], $file->getPathName());

            return $auth['VideoId'];

        }
        catch (OssException $e)
        {
            print $e->getMessage();
        }
    }

    //
    public function CreateUploadVideoAuth($title, $fileName)
    {

        $auth = Vod::v20170321()->createUploadVideo()->client($this->Client)
            ->withTitle($title)
            ->withFileName($fileName)
            ->format('JSON')
            ->request();
        return $auth->toArray();
    }

    public function getPlayInfo($videoId)
    {

        $this->initVodClient();
        $info = Vod::v20170321()->getPlayInfo()->client($this->Client)
            ->withVideoId($videoId) // 指定接口参数
            ->withAuthTimeout(3600 * 24)
            ->withResultType('Multiple')
            ->format('JSON') // 指定返回格式
            ->request(); // 执行请求

        $data = $info->PlayInfoList->PlayInfo;
        
        if (isset($data[0]))
        {
            return $data[0]->PlayURL;
        }
        else
        {
            return '';
        }

    }

    public function createVideoPlayAuth($videoId)
    {

        $info = Vod::v20170321()->getVideoPlayAuth()->client($this->Client)
            ->withVideoId($videoId) // 指定接口参数
            ->withAuthTimeout(3600 * 24)
            ->format('JSON') // 指定返回格式
            ->request();
        // print_r($info->toArray());exit();
        return $info->toArray();

    }

    //文件上传--暂支持本地
    public function uploadFile($file, $param)
    {
        return \think\facade\Filesystem::disk('public')->putFile('tofile', $file);
    }

}
