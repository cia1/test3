<?php

namespace common\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Действие пользователя
 *
 * @property int $id
 * @property int $userId ID пользователя
 * @property int $rate Рейтинг пользователя
 * @property int $created_at Дата и время совершения действия (UNIX TIME)
 * @property string $target Цель
 * @property string $type Тип действия
 * @property int $duration Продолжительность действия
 */
class Action extends ActiveRecord
{

    /** @inheritDoc */
    public static function tableName(): string
    {
        return '{{%action}}';
    }

    /** @inheritDoc */
    public function rules(): array
    {
        return [
            [
                ['userId', 'target', 'type', 'duration'],
                'required'
            ],
            ['userId', 'integer'],
            ['userId', 'exist', 'targetClass' => User::class, 'targetAttribute' => 'id'],
            ['rate', 'integer', 'min' => 1, 'max' => 10],
            [
                ['target', 'type'],
                'filter', 'trim'
            ],
            [
                ['target', 'type'],
                'string', 'max' => 255
            ],
            ['duration', 'integer', 'min' => 0, 'max' => 65535]
        ];
    }

    /** {@inheritdoc} */
    public function behaviors(): array
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => null
            ],

        ];
    }

    /**
     * Связь "Пользователь"
     * @return ActiveQuery
     */
    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'userId']);
    }

}
