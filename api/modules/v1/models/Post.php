<?php

namespace api\modules\v1\models;

use Yii;


class Post extends \common\models\Post
{
    public function fields()
    {
        $fields = parent::fields();
        return $fields;
    }
}
