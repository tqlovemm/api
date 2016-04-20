<?php

namespace api\modules\v3\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\helpers\Response;

class AppPushController extends ActiveController
{
    public $modelClass = 'api\modules\v3\models\AppPush';
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
        $query = $this->findModelAll($id);

        return $query;


    }

    public function actionDelete($id)
    {

        if(isset($_GET['cid'])){

            if($this->findModel($id,$_GET['cid'])->delete()){

                Response::show('202','删除成功');
            }

        }else{

            if($this->findModelDAll($id)->deleteAll(['cid'=>$id])){

                Response::show('202','删除成功');

            }
        }
    }


    protected function findModel($id,$cid)
    {
        $modelClass = $this->modelClass;

            if (($model = $modelClass::findOne(['id'=>$id,'cid'=>$cid])) !== null) {
                return $model;
            } else {
                throw new NotFoundHttpException('The requested page does not exist.');
            }

    }
    protected function findModelDAll($id)
    {
        $modelClass = $this->modelClass;

            if (($model = $modelClass::findOne(['cid'=>$id])) !== null) {
                return $model;
            } else {
                throw new NotFoundHttpException('The requested page does not exist.');
            }

    }
    protected function findModelAll($id)
    {
        $modelClass = $this->modelClass;

            if (($model = $modelClass::findAll(['cid'=>$id])) !== null) {
                return $model;
            } else {
                throw new NotFoundHttpException('The requested page does not exist.');
            }

    }

}
