<?php

use common\models\Status;
use yii\db\Migration;

class m190730_160353_insert_status extends Migration
{

    public function up()
    {
        $this->batchInsert('{{%status}}', ['id', 'title', 'right_send', 'right_publish', 'right_view'],[
            [Status::STATUS_REQUEST, 'Заявка подана',false,false,false],
            [Status::STATUS_REGISTERED, 'Зарегистрирован',true,false,false],
            [Status::STATUS_CONFIRMED, 'Подтверждён', true, false, true],
            [Status::STATUS_APPLIED, 'Одобрен', true, true, true],
            [Status::STATUS_SPECIAL, 'Особый', true, true, true]
        ]);

    }

    public function down()
    {
        $this->truncateTable('{{%status}}');
    }

}
