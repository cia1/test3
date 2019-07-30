<?php

use yii\db\Migration;

class m190730_164955_add_fk_user_status extends Migration
{

    public function up()
    {
        $this->addForeignKey(
            'statusId','{{%user}}','statusId',
            '{{%status}}','id',
            'RESTRICT','RESTRICT'
        );
    }

    public function down()
    {
        $this->dropForeignKey('statusId','{{%user}}');
    }

}
