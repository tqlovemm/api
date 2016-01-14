<?php

namespace api\modules\v2\controllers;

use api\modules\v2\models\User;
use Yii;
use yii\rest\ActiveController;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\QueryParamAuth;
use yii\helpers\Response;

class User1Controller extends ActiveController
{
    public $modelClass = 'api\modules\v2\models\User1';
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

        $model = $this->findModel($id);

        if(!is_numeric($id)){
            $model['avatar'] = 'http://182.254.217.147:8888/uploads/user/avatar/'.$model['avatar'];
            $data = Yii::$app->db->createCommand('select * from {{%user_data}} WHERE user_id='.$model['id'])->queryOne();
            $profile = Yii::$app->db->createCommand('select * from {{%user_profile}} WHERE user_id='.$model['id'])->queryOne();
            unset($model['password_hash'],$model['auth_key'],$model['password_reset_token'],$model['avatarid'],$model['avatartemp'],$model['id'],$model['role'],$model['identity']);
            return $model+$data+$profile;
        }

        return $model;

    }
    protected function findModel($id)
    {
        $modelClass = $this->modelClass;
        if (is_numeric($id)) {
            $model = $modelClass::findOne($id);
        } else {
            $model = $modelClass::find()->where(['username' => $id])->asArray()->one();
        }

        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
