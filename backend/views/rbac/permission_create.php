<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $model common\models\AuthItem */
/* @var $allPrivilege [] */

$this->title = Yii::t('app', '新增角色或权限');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Adminuser'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="comment-create">

    <hr>

    <div class="comment-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'name')->textInput() ?>

        <?= $form->field($model, 'type')->dropDownList([1 => '角色', 2 => '权限'], ['prompt' => '请选择角色或权限']) ?>

        <div id="role" style="display: none;">
            分配角色：<br>
            <?= Html::dropDownList('role', null, $allPrivilege, ['prompt' => '分配角色']) ?>
        </div>

        <?= $form->field($model, 'description')->textInput() ?>

        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', '新增') : Yii::t('app', '修改'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>

<?php
$js = <<<js
$(function () {
    $('#authitem-type').change(function(){
        var data = $(this).val();
        if (data == 2){
            $('#role').show();
        } else {
            $('#role').hide();
        }
    });
});
js;
$this->registerJs($js);
?>
