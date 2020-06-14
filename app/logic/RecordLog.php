<?php
namespace app\logic;

use app\model\RecordLog as RecordLogModel;

class RecordLog extends RecordLogModel
{
    //
    public function baseSave($category, $userId = 0, $key = 0, $value = 0)
    {
        $this->save([
            'category' => $category,
            'user_id' => $userId,
            'name' => $key,
            'value' => $value,
            'create_time' => time(),
        ]);
    }
}
