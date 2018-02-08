<?php

use yii\helpers\Html;
use yii\grid\GridView;

use yii\bootstrap\Modal;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\AdminuserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Adminusers');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="adminuser-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('添加新用户', ['signup'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
//        'options' => ['id' => 'category'],
        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'username',
            'nickname',
            'password',
            'email:email',
            // 'profile:ntext',

            [
                'header' => '操作',
                'headerOptions' => ['width' => '150'],
                'class' => 'yii\grid\ActionColumn',
                'template'=> '{view} | {update} | {delete} | {privilege}',
                'buttons' => [
                    'privilege' => function ($url, $model, $key) {
                        return Html::a('', ['adminuser/privilege', 'id'=>$model->id], ['class' => 'privilege glyphicon glyphicon-user', 'title' => '权限', 'data-toggle'=>"modal", 'data-target'=>"#privilege-modal", 'data-id' => $key]);
                    },
                ]
            ],
        ],
    ]); ?>
</div>

<?php
Modal::begin([
    'id' => 'privilege-modal',
    'header' => '<h4 class="modal-title">修改权限</h4>',
    'size'=>Modal::SIZE_DEFAULT,//设定弹窗宽度，可以自己写一个类继承与modal类，定义一个常量的class属性，然后引入该css样式
    'options'=>[
        'data-keyboard'=>false,
    ],
    'footer' => '<a href="#" class="btn btn-primary" data-dismiss="modal">关闭</a>',
]);
$requestUrl = Url::toRoute('privilege');

$lotteryJS = <<<JS
    $('.privilege').on('click', function () {
        $.get('{$requestUrl}', { id: $(this).closest('tr').data('key') }, 
            function (data) {
          // 弹窗的主题渲染页面
                $('.modal-body').html(data);
            }  
        );
    });
JS;
$this->registerJs($lotteryJS);

Modal::end();

?>
