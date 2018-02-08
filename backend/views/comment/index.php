<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\CommentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', '评论管理');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="comment-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],

            'id',
//            'content:ntext',
            [
                'attribute' => 'content',
                'value' => 'beginning'
//                'value' => function ($model) {
//                    $string = strip_tags($model->content);
//                    $str_length = mb_strlen($string);
//
//                    return mb_substr($string, 0, 50, 'utf-8') . ($str_length > 50 ? '...' : '');
//                }
            ],
//            'status',
//            'create_time:datetime',
//            'userid',
            // 'email:email',
            // 'url:url',
            // 'post_id',
            [
                'attribute' => 'status',
                'value' => 'status0.name',
                'filter' => \common\models\Commentstatus::find()->select('name, id')->orderBy('position')->indexBy('id')->column(),
                'contentOptions' => function ($model) {
                    return $model->status === 1 ? ['class' => 'bg-danger'] : [];
                }
            ],
            [
                'attribute' => 'created_at',
                'format' => ['date', 'php:Y-m-d H:i:s'],
            ],
            [
                'attribute' => 'user.username',
                'value' => 'user.username',
            ],
            [
                'label' => 'post',
                'value' => 'post.title',
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {delete} {approve}',
                'buttons' => [
                    'approve' => function ($url, $model, $key) {
                        $options = [
                            'title' => Yii::t('yii', '审核'),
                            'aria-label' => Yii::t('yii', '审核'),
                            'data-confirm' => Yii::t('yii', '你确定通过这条评论吗？'),
                            'data-method' => 'post',
                            'data-pjax' => '0',
                        ];
                        return Html::a('<span class="glyphicon glyphicon-check"></span>', $url, $options);
                    }
                ]
            ],
        ],
    ]); ?>
</div>
