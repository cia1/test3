<?php

use yii\db\Migration;

class m190730_171914_add_fk_action_user extends Migration
{

    public function up()
    {
        $this->addForeignKey(
            'userId','{{%action}}','userId',
            '{{%user}}','id',
            'CASCADE','CASCADE'
        );

    }

    public function down()
    {
        $this->dropForeignKey('userId','{{%action}}');
    }

}
