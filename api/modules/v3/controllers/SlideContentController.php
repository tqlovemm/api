<?php

namespace api\modules\v3\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\web\NotFoundHttpException;

class SlideContentController extends ActiveController
{
    public $modelClass = 'api\modules\v3\models\HeartweekSlideContent';
    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        return $behaviors;
    }

    public function actions()
    {
        $actions = parent::actions();
        // 注销系统自带的实现方法
        unset($actions['index'], $actions['update'], $actions['create'], $actions['delete'], $actions['view']);
        return $actions;
    }


    public function actionView($id)
    {
        $query = $this->findModel($id);

        return $query;

    }

    protected function findModel($id)
    {
        $modelClass = $this->modelClass;

            if (($model = $modelClass::findAll(['album_id'=>$id])) !== null) {
                return $model;
            } else {
                throw new NotFoundHttpException('The requested page does not exist.');
            }

    }

}
