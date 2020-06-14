<?php
// 事件定义文件
return [
    'bind'      => [
    ],

    'listen'    => [
        'AppInit'  => [],
        'HttpRun'  => [],
        'HttpEnd'  => [],
        'LogLevel' => [],
        'LogWrite' => [],
        'UserLogin' => [],
        'LogSendSMS' => [],
        'LogViewCourse' => [],
        'LogViewVideo' => [],
    ],

    'subscribe' => [
        app\subscribe\User::class,
        app\subscribe\Record::class,
    ],
];
