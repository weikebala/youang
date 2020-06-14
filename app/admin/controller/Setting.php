<?php
namespace app\admin\controller;

use app\AdminBaseController;
use app\logic\Setting as logicSetting;
use think\facade\View;

class Setting extends AdminBaseController
{
    protected $middleware = ['adminAuth', 'Access'];

    //基础配置
    public function website()
    {

        $param   = $this->request->param();
        $setting = new logicSetting();

        if ($this->request->isPost())
        {
            $setting->saveSettingPost($param);
            $tpl = isset($param['type']) && !empty($param['type']) ? $param['type'] : 'baseConfig';
            return redirect((string) url('/admin/setting/website', ['tplType' => $tpl]));
        }
        else
        {

            $tpl = isset($param['tplType']) && !empty($param['tplType']) ? $param['tplType'] : 'baseConfig';

            View::assign('settinglist', $setting->getSettingList());
            View::assign('detail', $setting->getSettingCategoryList($tpl));
            View::assign('tpl', $tpl);
            return View::fetch($tpl, ['tplType' => $tpl]);

        }

    }

}
