<?php

use yii\db\Migration;

class m190730_163340_create_action extends Migration
{

    public function up()
    {
        $this->createTable('{{%action}}',[
            'id'=>$this->primaryKey()->unsigned(),
            'userId'=>$this->integer(11)->notNull()->comment('ID пользователя'),
            'rate'=>$this->tinyInteger(2)->unsigned()->notNull()->defaultValue(0)->comment('Оценка (1-10)'),
            'created_at'=>$this->integer()->unsigned()->notNull()->defaultExpression('NOW()')->comment('Дата совершения действия'),
            'target'=>$this->string(255)->notNull()->comment('Цель'),
            'type'=>$this->string(255)->notNull()->comment('Тип действия'),
            'duration'=>$this->smallInteger()->unsigned()->notNull()->defaultValue(0)->comment('Продолжительность, сек.')
        ],"COMMENT 'Действия пользователей'");

    }

    public function down()
    {
        $this->dropTable('{{%action}}');
    }

}
