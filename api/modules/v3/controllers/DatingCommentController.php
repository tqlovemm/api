<?php

namespace api\modules\v3\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\helpers\Response;
class DatingCommentController extends ActiveController
{
    public $enableCsrfValidation = false;
    public $modelClass = 'api\modules\v3\models\DatingComment';
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

    public function actionIndex()
    {
        $modelClass = $this->modelClass;

        if(isset($_GET['dating_id'])){

            $query = $modelClass::find()->where(['weekly_id'=>$_GET['dating_id']]);

        }else{

            return false;
        }

        return new ActiveDataProvider([
            'query' => $query,
        ]);
    }

    public function actionView($id)
    {
        $query = $this->findModel($id);

        return $query;


    }
    public function actionCreate()
    {

        $model = new $this->modelClass;

        //$model->attributes = Yii::$app->request->post();
        $model->load(Yii::$app->getRequest()->getBodyParams(), '');

        if (!$model->save()) {

            return array_values($model->getFirstErrors())[0];

        }

        Response::show(202,'成功');
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->load(Yii::$app->getRequest()->getBodyParams(), '');

        if (!$model->save()) {
            return array_values($model->getFirstErrors())[0];
        }

        return $model;
    }

    public function actionDelete($id)
    {

        if($this->findModel($id)->delete()){

            Response::show('202','删除成功');
        }
    }


    protected function findModel($id)
    {
        $modelClass = $this->modelClass;

            if (($model = $modelClass::findOne($id)) !== null) {
                return $model;
            } else {
                throw new NotFoundHttpException('The requested page does not exist.');
            }

    }

}
