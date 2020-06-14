<?php
namespace app\admin\controller;

use app\AdminBaseController;
use app\logic\File as FileLogic;
use app\logic\Image;
use think\facade\Session;

class File extends AdminBaseController
{

    protected $middleware = ['adminAuth'];

    public function initialize()
    {
        if (empty(Session::get('adminUserInfo')))
        {
            redirect(getDomain() . '/admin/login/login')->send();exit;
        }
        $this->getWebTheme();
    }

    /**
     * [upload 图片上传接口]
     * @return [type] [description]
     */
    public function imageUpload()
    {

        $file     = $this->request->file('file');
        $param    = $this->request->param();
        $image    = new Image();
        $savename = $image->uploadImage($file, $param);
        if ($savename)
        {

            return json([
                'errno' => 0,
                'msg' => '上传成功',
                'path' => $savename,
                'data' => $image->editorImage($savename),
            ]);

        }
        else
        {

            return json([
                'errno' => 1,
                'msg' => '上传失败',
            ]);

        }
    }

    /**
     * [videoUpload 视频上传接口]
     * @return [type] [description]
     */
    public function uploadVideo()
    {

        $file     = $this->request->file('file');
        $param    = $this->request->param();
        $vod      = new FileLogic();
        $savename = $vod->uploadVideo($file, $param);
        $savename ? $this->success('上传成功', '', ['path' => $savename]) : $this->error('未开启上传通道...');
    }

    /**
     * [videoUpload 文件上传接口]
     * @return [type] [description]
     */
    public function uploadFile()
    {

        $file     = $this->request->file('file');
        $param    = $this->request->param();
        $vod      = new FileLogic();
        $savename = $vod->uploadFile($file, $param);
        $this->success('上传成功', ['path' => $savename]);
    }

    /**
     * [uploadCloud 更新到云上]
     * @return [type] [description]
     */
    public function uploadCloud()
    {
        return json(['code' => 1, 'message' => '上传成功']);
    }

}
