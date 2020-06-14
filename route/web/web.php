<?php
use think\facade\Route;
//搜索页路由
Route::rule('list', 'index/list');



// //短信发送接口
// Route::group('detail', [
// 	'chapter' => [
// 		'detail/chapter',
// 	],
// ]);


Route::rule('search', 'index/search');
