<?php
namespace backend\components;

use Yii;
use yii\web\ForbiddenHttpException;

class AccessController extends \yii\base\ActionFilter
{
    /**
     *  对用户请求的路由进行验证
     *  @return true 表示有权访问
     */
    public function beforeAction ($action)
    {
        // 当前路由
        $actionId = $action->getUniqueId();
        $actionId = '/' . $actionId;

        // 当前登录用户的id
        $user = Yii::$app->getUser();
        $userId = $user->id;

        // 获取当前用户已经分配过的路由权限
        // 写的比较简单，有过基础的可自行完善，比如解决"*"的问题，看不懂的该行注释自行忽略
        $routes = [
            '/site/login',
            '/site/logout',
            '/site/index'
        ];
        $manager = Yii::$app->getAuthManager();
        foreach ($manager->getPermissionsByUser($userId) as $name => $value) {
            if ($name[0] === '/') {
                $routes[] = $name;
            }
        }

        // 判断当前用户是否有权限访问正在请求的路由
        if ($userId === 1 || in_array($actionId, $routes)) {
            return true;
        }

        $this->denyAccess($user);
    }

    /**
     *  拒绝用户访问
     *  访客，跳转去登录；已登录，抛出403
     *  @param $user 当前用户
     *  @throws ForbiddenHttpException 如果用户已经登录，抛出403.
     */
    protected function denyAccess($user)
    {
        if ($user->getIsGuest()) {
            $user->loginRequired();
        } else {
            throw new ForbiddenHttpException('不允许访问.');
        }
    }
}