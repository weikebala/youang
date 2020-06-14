<?php
namespace app\vod\controller;
use app\WebBaseController;
use think\facade\View;

class Search extends WebBaseController
{
    public function index()
    {
        return View::fetch('search');
    }
    
}
