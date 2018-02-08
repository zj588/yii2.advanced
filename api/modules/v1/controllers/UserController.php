<?php

namespace api\modules\v1\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use common\models\User;
use common\models\LoginForm;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth ;

class UserController extends \yii\web\Controller
{
	public $enableCsrfValidation=false;


	public function behaviors() 
	{
	    $behaviors = parent::behaviors();

	    $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::className(),
            'optional' => [
                'login',
                'signup-test'
            ],
        ];

        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::className(),
            'cors'  => [
                'Origin' => ['*'],
                // 'Access-Control-Request-Headers' => ['authorization'],
            ],
        ];

        //速率限制
  //       $behaviors['rateLimiter'] = [
		// 	'class' => RateLimiter::className(),
		// 	'enableRateLimitHeaders' => true,
		// ];

        return $behaviors;
	}


	/**
	 * 添加测试用户
	 */
	public function actionSignupTest ()
	{
	    $user = new User();
	    $user->generateAuthKey();
	    $user->setPassword('123456');
	    $user->username = '555';
	    $user->email = '555@555.com';
	    $user->save(false);
	    return [
	        'code' => 0
	    ];
	}


	public function actionLogin ()
	{
	    $model = new LoginForm;
	    $model->setAttributes(Yii::$app->request->post());
	    if ($user = $model->login()) {
	        return $user->api_token;
	    } else {
	        return $model->errors;
	    }
	}

	/**
	 * 获取用户信息
	 */
	public function actionUserProfile ()
	{
	    // 到这一步，token都认为是有效的了
	    // 下面只需要实现业务逻辑即可
	    $user = $this->authenticate(Yii::$app->user, Yii::$app->request, Yii::$app->response);
	    return [
	        'id' => $user->id,
	        'username' => $user->username,
	        'email' => $user->email,
	    ];
	    // 
	    // $user = User::findIdentityByAccessToken($token);
	    // return [
	    //     'id' => $user->id,
	    //     'username' => $user->username,
	    //     'email' => $user->email,
	    // ];
	}

}