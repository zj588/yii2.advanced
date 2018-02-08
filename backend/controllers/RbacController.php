<?php

namespace backend\controllers;

use common\models\AuthItem;
use common\models\AuthItemChild;
use Yii;
use yii\web\Controller;

class RbacController extends Controller
{
    private $auth;

    public function init()
    {
        parent::init();
        $this->auth = Yii::$app->authManager;
    }

    /**
     * 新增权限
     * @return string|\yii\web\Response
     */
    public function actionPermissionCreate()
    {
        $allPrivilege = AuthItem::find()->select([ 'description', 'name'])->where(['type' => 1])->indexBy('name')->asArray()->column();
        $auth_item = new AuthItem();

        if ($auth_item->load(Yii::$app->request->post()) && $auth_item->save()) {
            //给角色赋予权限
            $role = Yii::$app->request->post('role');
            if (!empty($role)) {
                $auth_item_child = new AuthItemChild();
                $auth_item_child->parent = $role;
                $auth_item_child->child = $auth_item->name;
                $auth_item_child->save();
            }

            return $this->redirect(['permission-create']);
        } else {
            return $this->render('permission_create', [
                'model' => $auth_item,
                'allPrivilege' => $allPrivilege,
            ]);
        }
    }
}