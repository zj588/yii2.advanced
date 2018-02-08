<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Adminuser */
/* @var $authAssignmentsArr */
/* @var $allPrivilegeArr */

$this->title = Yii::t('app', '权限修改: ', [
    'modelClass' => 'Adminuser',
]) . $model->username;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Adminusers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', '设置');
?>
<div class="adminuser-privilege">

    <br>
    <div class="adminuser-privilege-form">

        <?php $form = ActiveForm::begin(); ?>

        <div class="form-group"> <?= $model->username ?>：</div>

        <?= Html::checkboxList('newPri', $authAssignmentsArr, $allPrivilegeArr) ?>

        <div class="form-group">
            <?= Html::submitButton('设置', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
