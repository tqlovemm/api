<?php

namespace api\modules\v1\controllers;

use yii\rest\ActiveController;

/**
 * Country Controller API
 *
 * @author Budi Irawan <deerawan@gmail.com>
 */
class ThreadController extends ActiveController
{
    public $modelClass = 'api\modules\v1\models\Thread';

    public function fields()
    {
        return ['id', 'user_id'];
    }


}


