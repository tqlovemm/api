<?php

namespace api\modules\v2\controllers;

use api\modules\v2\models\Post;
use Yii;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\QueryParamAuth;
use yii\rest\ActiveController;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\helpers\Response;

class PostController extends ActiveController
{
    public $modelClass = 'api\modules\v2\models\Post';
    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        // token 验证  请按需开启
      /*   $behaviors['authenticator'] = [
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

    public function actionView($id)
    {
        $query = $this->findModels($id);

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



        $model->PostCuntPlus();
        Response::show('202','保存成功');
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->load(Yii::$app->getRequest()->getBodyParams(), '');

        if (!$model->save()) {
            return array_values($model->getFirstErrors())[0];
        }

        Response::show(202,'更新成功');
    }

    public function actionDelete($id)
    {
        $model = new $this->modelClass();
        $thread_id = $_GET['thread_id'];
        if($this->findModel($id)->delete()){
            $model->PostCuntDel($thread_id);
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
    protected function findModels($thread_id){

        $modelClass = $this->modelClass;

        if (($model = $modelClass::find()->where('thread_id=:thread_id',['thread_id'=>$thread_id])->orderBy('created_at DESC')) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
