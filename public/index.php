<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2019 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// [ 应用入口文件 ]
namespace think;

require __DIR__ . '/../vendor/autoload.php';

define('WEB_ROOT', __DIR__ . '/../');



header("Access-Control-Allow-Origin: *"); // Support CORS
header('Access-Control-Allow-Headers:x-requested-with,content-type,Device-Type,User-Token');

header('Access-Control-Allow-Methods:GET, POST, PATCH, PUT, DELETE');
header('Access-Control-Allow-Headers:Authorization, Content-Type, If-Match, If-Modified-Since, If-None-Match, If-Unmodified-Since, X-Requested-With');

// 执行HTTP应用并响应
$http = (new App())->http;

$response = $http->run();

$response->send();

$http->end($response);
