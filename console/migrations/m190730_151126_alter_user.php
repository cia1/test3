<?php

use yii\db\Migration;
use common\models\Status;

class m190730_151126_alter_user extends Migration
{

    public function safeUp()
    {
        $this->dropColumn('{{%user}}','status');
        $this->addColumn('{{%user}}','statusId',$this->tinyInteger()->unsigned()->notNull()->defaultValue(Status::STATUS_REQUEST)->comment('ID статуса пользователя'));
        $this->addColumn('{{%user}}','first_name',$this->string(32)->notNull()->comment('Имя'));
        $this->addColumn('{{%user}}','last_name',$this->string(32)->notNull()->comment('Фамилия'));
        $this->addColumn('{{%user}}','phone',$this->string(13)->comment('Номер телефона'));
        $this->addColumn('{{%user}}','rating',"MEDIUMINT UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Рейтинг'");
    }

    public function safeDown()
    {
        $this->dropColumn('{{%user}}','first_name');
        $this->dropColumn('{{%user}}','last_name');
        $this->dropColumn('{{%user}}','phone');
        $this->dropColumn('{{%user}}','rating');
        $this->dropColumn('{{%user}}','statusId');
        $this->addColumn('{{%user}}','status',$this->smallInteger(6)->defaultValue(10));
    }

}
