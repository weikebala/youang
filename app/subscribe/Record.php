<?php
declare (strict_types = 1);

namespace app\subscribe;

use app\logic\Course;
use app\logic\RecordLog;

class Record
{
    protected $eventPrefix = 'Log';

    public function onSendSMS($sms)
    {
        $log = new RecordLog();

        $log->save(
            [
                'name' => $sms['mobile'],
                'category' => 'smsCode',
                'value' => $sms['code'],
                'create_time' => time(),
            ]);

        //短信发送后记录
    }

    //更新观看日志&&观看次数
    public function onViewCourse($course)
    {

        $courseObj = new Course();
        $logObj    = new RecordLog();

        if (isset($course['id']))
        {
            $courseObj->where('id', $course['id'])->inc('views')->update();
        }
        if (getUserInfoData())
        {

            //查询是否已经记录过
            $logs = $logObj->where([
                'user_id' => getUserInfoData(),
                'name' => $course['id'],
                'category' => 'courseView',
            ])->whereDay('create_time')
                ->find();

            //
            if (empty($logs))
            {
                $logObj->save([
                    'user_id' => getUserInfoData(),
                    'name' => $course['id'],
                    'category' => 'courseView',
                    'create_time' => time(),
                ]);
            }
        }
    }

    //更新用户学习状态
    public function onViewVideo($video)
    {

        $log       = new RecordLog();
        $courseObj = new Course();

        //查询是否已经观看过
        $where = [
            'user_id' => getUserInfoData(0, 'id'),
            'name' => $video['course_id'],
            'category' => 'studyCourse',
        ];

        //查看当前用户是否观看过该课程

        $studyInfo = $log->where($where)->find();

        if (empty($studyInfo))
        {

            $courseObj->where('id', $video['course_id'])->inc('study_num')->update();
        }

        $where   = array_merge($where, ['value' => $video['id']]);
        $logInfo = $log->where($where)->find();

        if (empty($logInfo))
        {
            //添加数据到数据库
            $log->save(array_merge(['create_time' => time()], $where));
        }

    }

}
