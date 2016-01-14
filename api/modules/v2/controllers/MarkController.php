<?php

namespace api\modules\v2\controllers;

use Yii;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\QueryParamAuth;
use yii\rest\ActiveController;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;


class MarkController extends ActiveController
{
    public $modelClass = 'api\modules\v2\models\Mark';


    public function behaviors()
    {
        $behaviors = parent::behaviors();
        // token 验证  请按需开启
        /*$behaviors['authenticator'] = [
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
        $query = Yii::$app->db->createCommand('SELECT GROUP_CONCAT(hobby_name) as hobby,GROUP_CONCAT(mark_name) as mark,GROUP_CONCAT(make_friend_name) as make_friend FROM {{%user_mark}}')->queryOne();
        $query['mark'] = array_filter(explode(',',$query['mark']));
        $query['make_friend'] = array_filter(explode(',',$query['make_friend']));
        $query['hobby'] = array_filter(explode(',',$query['hobby']));
        return $query;

    }

    public function actionView($id)
    {

        $query = $this->findModel($id);

        return $query;

    }
    public function actionCreate()
    {
      /*  $model = new $this->modelClass();
        $model->load(Yii::$app->getRequest()->getBodyParams(), '');
        if (!$model->save()) {
            return array_values($model->getFirstErrors())[0];
        }

        Response::show('202','保存成功');*/
    }

    public function actionUpdate($id)
    {
       /* $model = $this->findModels($id);

        $model->load(Yii::$app->getRequest()->getBodyParams(), '');

        if (!$model->save()) {
            return array_values($model->getFirstErrors())[0];
        }

        Response::show(202,'更新成功');*/
    }

    public function actionDelete($id)
    {
       /* if($this->findModel($id)->delete()){

            Response::show('202','删除成功');

        }*/
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
    protected function findModels($id){

        $modelClass = $this->modelClass;

        if (($model = $modelClass::find()->where('user_id=:user_id',[':user_id'=>$id])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }



    }

}
