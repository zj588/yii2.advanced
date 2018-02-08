<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'language' => 'zh-CN',
    'modules' => [
        /**
         * TODO：修改 vendor->mdmsoft->yii2-admin->models->search->AuthItem.php->第66行
         * // $this->type == Item::TYPE_ROLE：返回角色列表
         * // strncmp($item->name, '/', 1) === 0：权限的第一个字符就是'/'，返回权限列表
         *
         * return $this->type == Item::TYPE_ROLE xor strncmp($item->name, '/', 1) === 0;
         */
        // yii-admin插件配置(rbac)
        'admin' => [
            'class' => 'mdm\admin\Module',
        ],
    ],
    'aliases' => [      // yii-admin插件配置(rbac)
        '@mdm/admin' => '@vendor/mdmsoft/yii2-admin',
    ],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
        ],
        'user' => [
            'identityClass' => 'common\models\AdminUser',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],

        'authManager' => [      // yii-admin插件配置(rbac)
            'class' => 'yii\rbac\DbManager',
            'defaultRoles' => ['guest'],
        ],
    ],
    'as access' => [        // yii-admin插件配置(rbac)
        'class' => 'mdm\admin\components\AccessControl',
        'allowActions' => [
            // 这里是允许访问的action，不受权限控制
            // controller/action
            'site/login',
            'site/logout',
            'site/index'
        ]
    ],
//    'as myBehavior2' => \backend\components\MyBehavior::className(),
//    'as access' => \backend\components\AccessController::className(),
    'params' => $params,
];
