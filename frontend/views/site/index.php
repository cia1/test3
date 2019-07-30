<?php

use yii\grid\GridView;
use yii\data\ActiveDataProvider;

/**
 * @var $this yii\web\View
 * @var $dataProvider ActiveDataProvider
 */

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Тестовое задание</h1>
    </div>

    <div class="body-content">

        <div class="row">
            <div class="col-lg-4">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'columns' => [
                        'first_name',
                        'last_name',
                        [
                            'attribute' => 'cnt',
                            'label' => 'Кол-во > 7',
                        ],
                    ],
                ]); ?>
            </div>
        </div>

    </div>
</div>
