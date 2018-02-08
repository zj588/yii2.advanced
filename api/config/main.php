<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-api',
    'basePath' => dirname(__DIR__),
    // 'controllerNamespace' => 'api\controllers',
    'modules' => [
        'v1' => [
            'class' => 'api\modules\v1\Module',
            'basePath' => '@api/modules/v1',
        ],
    ],
    'bootstrap' => ['log'],
    // 'modules' => [],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-api',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
                'text/json' => 'yii\web\JsonParser',
            ]
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            // 'identityCookie' => ['name' => '_identity-api', 'httpOnly' => true],
            'enableSession' => false,
            'loginUrl' => null,
        ],
        // 'session' => [
        //     // this is the name of the session cookie used for login on the api
        //     'name' => 'advanced-api',
        // ],
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
            'enableStrictParsing' =>true,
            'showScriptName' => false,
            'rules' => require(__DIR__ . '/url-rules.php'),
        ],

        // 数据返回格式
        // 'response' => [
        //     'class' => 'yii\web\Response',
        //     'on beforeSend' => function ($event) {
        //         $response = $event->sender;
        //         $code = $response->getStatusCode();
        //         $msg = $response->statusText;
        //         if ($code == 404) {
        //             !empty($response->data['message']) && $msg = $response->data['message'];
        //         }
        //         $data = [
        //             'code' => $code,
        //             'msg' => $msg,
        //         ];
        //         $code == 200 && $data['data'] = $response->data;
        //         $response->data = $data;
        //         $response->format = yii\web\Response::FORMAT_JSON;
        //     },
        // ],
        
    ],
    'params' => $params,
];
