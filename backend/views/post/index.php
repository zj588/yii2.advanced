<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Poststatus;

/* @var $this yii\web\View */
/* @var $searchModel common\models\PostSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Posts');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="post-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Post'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn'],

            'id',
            'title',
            // 'author_id',
            // 'content:ntext',
            // [
            //     'attribute' => 'title',
            //     'headerOptions' => [
            //         'width' => '30%',
            //     ]
            // ],
            // [
            //     'attribute' => 'content',
            //     'headerOptions' => [
            //         'width' => '300',
            //     ]
            // ],
            [
                'attribute' => 'author',
                'value' => 'author.nickname',
            ],
            'tags:ntext',
            [
                'attribute' => 'status',
                'value' => 'status0.name',
                'filter' => Poststatus::find()->select('name, id')->orderBy('position')->indexBy('id')->column(),
            ],
            // 'created_at:datetime',
            'updated_at:datetime',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
