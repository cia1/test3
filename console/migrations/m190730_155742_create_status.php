<?php

use yii\db\Migration;

class m190730_155742_create_status extends Migration
{

    public function up()
    {
        $this->createTable('{{%status}}',[
            'id'=> 'TINYINT UNSIGNED PRIMARY KEY AUTO_INCREMENT',
            'title'=>$this->string(60)->notNull(),
            'right_send'=>$this->boolean()->notNull()->defaultValue(0),
            'right_publish'=>$this->boolean()->notNull()->defaultValue(0),
            'right_view'=>$this->boolean()->notNull()->defaultValue(0)
        ],"COMMENT 'Справочник статусов пользователей'");

    }

    public function down()
    {
        $this->dropTable('{{%status}}');
    }

}
