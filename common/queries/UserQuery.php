<?php

namespace common\queries;

use common\models\Status;
use yii\base\InvalidArgumentException;
use yii\db\ActiveQuery;

/**
 * ActiveQuery "Пользователи"
 */
class UserQuery extends ActiveQuery
{

    /**
     * Фильтр по статусу, больше или равен
     * @param int $status
     * @return UserQuery
     */
    public function statusMore(int $status): self
    {
        if (in_array($status, Status::STATUSES_ALL) === false) throw new InvalidArgumentException('Unknown status');
        return $this->andOnCondition(['>=', 'statusId', $status]);
    }

    public function withActions(): self
    {
        return $this->joinWith('actions')->groupBy('user.id');
    }

    public function sortByActionRating(bool $desc = false): self
    {
        return $this->addOrderBy('SUM(action.rate)/COUNT(action.id)' . ($desc === true ? ' DESC' : ''));
    }
}
