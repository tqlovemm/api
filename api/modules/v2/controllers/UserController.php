<?php

namespace api\modules\v2\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\QueryParamAuth;
use yii\helpers\Response;

class UserController extends ActiveController
{
    public $modelClass = 'api\modules\v2\models\User';
    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

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
        $modelClass = $this->modelClass;
        $query = $modelClass::find();
        return new ActiveDataProvider([
            'query' => $query,
        ]);
    }

    public function actionCreate()
    {
       /* $model = new $this->modelClass();
        $model->load(Yii::$app->getRequest()->getBodyParams(), '');
        $model->email = base64_encode($model->email);
        if (!$model->save()) {
            return array_values($model->getFirstErrors())[0];
        }

        return $model;*/
        Response::show(401,'不允许的操作');

    }

    public function actionUpdate($id)
    {
   /*   $model = $this->findModel($id);
        $model->load(Yii::$app->getRequest()->getBodyParams(), '');
        if (!$model->save()) {
            return array_values($model->getFirstErrors())[0];
        }
        return $model;*/

        Response::show(401,'不允许的操作');
    }

    public function actionDelete($id)
    {
        /*return $this->findModel($id)->delete();*/
        Response::show(401,'不允许的操作');
    }

    public function actionView($id)
    {

        $command = Yii::$app->db->createCommand('SELECT * FROM {{%user}} as u LEFT JOIN {{%user_data}} as ud ON ud.user_id=u.id LEFT JOIN {{%user_profile}} as up ON up.user_id=u.id WHERE id='.$id);
        $post = $command->queryAll();
        return $post;

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
