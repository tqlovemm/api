<?php

namespace api\modules\v2\controllers;


use Yii;
use yii\rest\ActiveController;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\helpers\Response;


class FlopController extends ActiveController
{
    public $modelClass = 'api\modules\v2\models\Flop';


    public function behaviors()
    {
        $behaviors = parent::behaviors();
        // token 验证  请按需开启
        /* $behaviors['authenticator'] = [
             'class' => CompositeAuth::className(),
             'authMethods' => [
                 QueryParamAuth::className(),
             ],
         ];*/
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
        $query = $modelClass::find();
        return new ActiveDataProvider([
            'query' => $query,
        ]);
    }

    public function actionCreate()
    {


        Response::show(401,'不允许的操作');

    }

    public function actionUpdate($id)
    {

        Response::show(401,'不允许的操作');
    }

    public function actionDelete($id)
    {
        Response::show(401,'不允许的操作');
    }

    public function actionView($id)
    {
        $model = $this->findModel($id);
        return $model;
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
