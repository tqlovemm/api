<?php

namespace api\modules\v2\controllers;


use Yii;
use yii\rest\ActiveController;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\helpers\Response;


class FlopContentDataController extends ActiveController
{

    public $modelClass = 'api\modules\v2\models\FlopContentData';

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

        if(isset($_GET['user_id'])){

            $query = $modelClass::find()->where(['user_id'=>$_GET['user_id']])->orderBy('created_at desc');

        }else{

            return false;
        }

        return new ActiveDataProvider([
            'query' => $query,
        ]);

    }

    public function actionCreate()
    {

        $model = new $this->modelClass();
        $model->load(Yii::$app->getRequest()->getBodyParams(), '');
        if (!$model->save()) {

            return array_values($model->getFirstErrors())[0];
        }

        Response::show('202','保存成功');

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

    public function actionView($id)
    {
        $model = $this->findModel($id);
        return $model;
    }

    protected function findModel($id)
    {
        $modelClass = $this->modelClass;
        if (($model = $modelClass::findOne(['flag'=>$id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
