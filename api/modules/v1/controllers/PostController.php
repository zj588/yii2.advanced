<?php

namespace api\modules\v1\controllers;

use api\modules\v1\models\Post;
use yii\data\ActiveDataProvider;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\RateLimiter;

class PostController extends \yii\web\Controller
{
	public $modelClass = 'api\modules\v1\models\Post';

	public function behaviors() 
	{
	    $behaviors = parent::behaviors();

	    // $behaviors['authenticator'] = [
     //        'class' => HttpBearerAuth::className(),
     //    ];

        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::className(),
            'cors'  => [
                'Origin' => ['*'],
                // 'Access-Control-Request-Headers' => ['authorization'],
            ],
        ];

        //速率限制
        $behaviors['rateLimiter'] = [
			'class' => RateLimiter::className(),
			'enableRateLimitHeaders' => true,
		];

        return $behaviors;
	}

    public function actionIndex()
    {
    	return Post::find()->all();

    	// $query = Post::find();

        // $data = new ActiveDataProvider([
        //     'query' => $query,
        //     // 'pagination' => [
        //     //     'pageSize' => $perPage,
        //     //     'pageSizeParam'=>'perPage',
        //     //     'pageParam'=>'currentPage',
        //     // ],
        //     'sort' => [
        //         'defaultOrder' => [
        //             'id' => SORT_ASC
        //         ]
        //     ]
        // ]);

        // return $data;
    }

    public function actionView($id) {

      return Post::find()->where(['id'=>$id])->one();

    }

}
