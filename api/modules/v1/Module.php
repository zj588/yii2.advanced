<?php

namespace api\modules\v1;

use Yii;
use yii\filters\Cors;
use yii\web\Response;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\QueryParamAuth;

/**
 * v1 module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'api\modules\v1\controllers';

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        // unset($behaviors['contentNegotiator']['formats']['application/xml']);
        // $behaviors['cors'] = [
        //     'class' => Cors::className(),
        //     'cors' => [
        //         'Origin' => ['http://api.dev'],
        //         'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
        //         'Access-Control-Request-Headers' => ['*'],
        //         'Access-Control-Allow-Credentials' => true,
        //         'Access-Control-Max-Age' => 86400,
        //         'Access-Control-Expose-Headers' => ['X-Pagination-Current-Page']
        //     ]
        // ];
        // $behaviors['authenticator'] = [
        //     'class' => QueryParamAuth::className(),
        //     'optional' => [
        //         'login',
        //         'signup-test'
        //     ],
        // ];

        return $behaviors;
    }
    public function init()
    {
        parent::init();
        // \Yii::$app->user->enableSession = false;
        //绑定beforeSend事件，更改数据输出格式
        \Yii::$app->getResponse()->on(Response::EVENT_BEFORE_SEND, [$this, 'beforeSend']);
    }


    public function beforeSend($event)
    {
        $response = $event->sender;
//         if ($response->data !== null) {
            if (!$response->isSuccessful) {
                $result = $response->data;
                if ($response->statusCode == 422) {
                    $response->data = [
                        'errcode' => $response->statusCode,
                        'errmsg' => $result[0]['message'],
                    ];
                } else {
                    $response->data = [
                        'errcode' =>isset($result['status']) ? $result['status'] : $response->statusCode,
                        'errmsg' => $response::$httpStatuses[$response->statusCode],
                    ];
                }
                $response->statusCode = 200;
            } else {
                $result['data'] = $response->data;
                if(isset($response->data['system_error'])){
                    $response->data = [
                        'errcode' => $response->data['system_error'],
                        'errmsg' => $result['data']['message'],
                    ];
                }else{
                    if (isset($result['data'])) {
                        $response->data = array_merge([
                            'errcode' => 0,
                            'errmsg' => 'ok',
                        ], $result);
                    } else {
                        //$result == null :未登录设置errcode为-1
                        $response->data = array_merge([
                            'errcode' => -1,
                            'errmsg' => 'ok',
                        ], $result);
                    }
                }


            }
//         }else{
//             $response->data = [
//                 'errcode' => 501,
//                 'errmsg' => '授权失败',
//             ];
//         }
        $response->format = Response::FORMAT_JSON;
        \Yii::$app->getResponse()->getHeaders()->set('Access-Control-Allow-Origin', 'http://api.dev');
        \Yii::$app->getResponse()->getHeaders()->set('Access-Control-Allow-Credentials', 'true');
        \Yii::$app->response->headers->set('Server', 'Microsoft/Win98');
        \Yii::$app->response->headers->set('X-Powered-By', 'Servlet/2.5 JSP/2.1');
        if (isset($_GET['callback'])) {
            $response->format = Response::FORMAT_JSONP;
            $response->data = [
                'callback' => $_GET['callback'],
                'data'=>$response->data,
            ];
        }



//        $response = $event->sender;
//
//        $isSuccessful = $response->StatusCode;
//        if($isSuccessful >=200 && $isSuccessful <300 && empty($response->data['system_error'])){
//            if(!empty($response->data['challenge'])){
//
//            }else{
//                $response->data = [
//                    'data' => $response->data,
//                    'success'=>[
//                        'success' => $response::$httpStatuses[$response->statusCode],
//                        'code' => $response->statusCode
//                    ]
//
//                ];
//            }
//
//        }else{
//            $response->data = [
//                'data' => '',
//                'error'=>$response->data,
//
//            ];
//            $response->StatusCode=200;
//        }
//
//
//        $response->format = Response::FORMAT_JSON;
//
//        \Yii::$app->getResponse()->getHeaders()->set('Access-Control-Allow-Origin', '*');
//        \Yii::$app->getResponse()->getHeaders()->set('Access-Control-Allow-Credentials', 'true');
//        if (isset($_GET['callback'])) {
//            $response->format = Response::FORMAT_JSONP;
//            $response->data = [
//                'callback' => $_GET['callback'],
//                'data'=>$response->data,
//            ];
//        }

    }
}
