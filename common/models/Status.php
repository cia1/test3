<?php

namespace common\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Статус пользователя
 *
 * @property integer $id
 * @property string $title Название статуса
 * @property bool $right_send Разрешено ли отправлять сообщения
 * @property bool $right_publish Разрешена ли публикация информации
 * @property bool $right_view Разрешён ли просмотр информации
 */
class Status extends ActiveRecord
{

    public const STATUS_REQUEST = 1; //Заявка подана
    public const STATUS_REGISTERED = 2; //Зарегистрирован
    public const STATUS_CONFIRMED = 3; //Подтверждён
    public const STATUS_APPLIED = 4; //Одобрен
    public const STATUS_SPECIAL = 5; //Особый

    public const STATUSES_ACTIVE = [self::STATUS_CONFIRMED, self::STATUS_APPLIED, self::STATUS_SPECIAL];
    public const STATUSES_INACTIVE = [self::STATUS_REQUEST, self::STATUS_REGISTERED];

    //Это избыточная дублирующая информация, но позволит сэкономить на SQL-запросах. При условии что статусы меняются крайне редко, это оправдано
    public const STATUSES_ALL = [self::STATUS_REQUEST, self::STATUS_REGISTERED, self::STATUS_CONFIRMED, self::STATUS_APPLIED, self::STATUS_SPECIAL];

    /** @inheritDoc */
    public static function tableName(): string
    {
        return '{{%status}}';
    }

    /** @inheritDoc */
    public function rules(): array
    {
        return [
            ['title', 'required'],
            ['title', 'filter', 'trim'],
            ['title', 'string', 'max' => 60],
            [
                ['right_send', 'right_publish', 'right_view'],
                'boolean'
            ]

        ];
    }

    /**
     * Связь "Пользователи"
     * @return ActiveQuery
     */
    public function getUsers(): ActiveQuery
    {
        return $this->hasMany(User::class, ['statusId' => 'id']);
    }
}
