<?php

use common\models\Action;
use common\models\Status;
use common\models\User;
use yii\db\Migration;

/**
 * Создаёт демо-данные для тестовой задачи: пользователи и их действия.
 * Генерирование токена доступа требует времени, поэтому эта миграция будет выполняться несколько секунд.
 */
class m190730_172123_demo_data extends Migration
{

    private const _USER = [
        'Николай Носов',
        'Александр Пушкин',
        'Лев Толстой',
        'Фёдор Достоевский',
        'Антон Чехов',
        'Николай Гоголь',
        'Иван Тургенев',
        'Михаил Булгаков',
        'Владимир Набоков',
        'Иван Бунин',
        'Михаил Лермонтов',
        'Максим Горький',
        'Александр Солженицын',
        'Александр Куприн',
        'Сергей Есенин',
        'Анна Ахматова',
        'Николая Лесков',
        'Иван Гончаров',
        'Иван Крылов',
        'Евгений Замятин',
        'Михаил Шолохов',
        'Николай Карамзин',
        'Марина Цветаева',
        'Алексей Толстой',
        'Василий Жуковский',
        'Леонид Андреев',
        'Афанасий Фет',
        'Фёдор Тютчев'
    ];

    public function safeUp()
    {
        //Создание пользователей
        $statusCount = count(Status::STATUSES_ALL) - 1;
        foreach (self::_USER as $i => $item) {
            $phone = '+7';
            for ($y = 0; $y < 10; $y++) $phone .= rand(0, 9);
            $item = explode(' ', $item);
            $item = [
                'username' => 'user' . ($i + 1),
                'statusId' => Status::STATUSES_ALL[rand(0, $statusCount)],
                'email' => 'user' . ($i + 1) . '@example.com',
                'first_name' => $item[0],
                'last_name' => $item[1],
                'phone' => $phone,
                'rating' => rand(0, 10000)
            ];
            $user = new User();
            $user->load($item, '');
            $user->setPassword('Some-Password1234');
            $user->generateAuthKey();
            $user->save();
            //Создать действия для каждого пользователя
            for ($y = 0, $cnt = rand(5, 30); $y < $cnt; $y++) {
                $action = new Action();
                $action->rate = rand(1, 10);
                $action->target = 'target-' . ($y + 1);
                $action->type = 'type-' . ($y + 1);
                $action->duration = rand(0, 200);
                $action->link('user', $user);
            }
        }
    }

    public function down()
    {
        $this->delete('{{%user}}');
    }

}
