<?php

namespace api\modules\v3\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\helpers\Response;

class HeartweekController extends ActiveController
{
    public $modelClass = 'api\modules\v3\models\Heartweek';
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

        if(isset($_GET['type'])){$type = $_GET['type'];}else{$type=1;}

        $modelClass = $this->modelClass;

        $query = $modelClass::find()->select('id,title,content,created_at,avatar,file')->where(['status'=>$type]);

        return new ActiveDataProvider([
            'query' => $query,
        ]);
    }

    public function actionView($id)
    {
        $query = $this->findModel($id);

        return new ActiveDataProvider([
            'query' => $query,
        ]);

    }
    public function actionCreate()
    {
/*      $model = new $this->modelClass();
        $model->load(Yii::$app->getRequest()->getBodyParams(), '');
        if (!$model->save()) {
            return array_values($model->getFirstErrors())[0];
        }

        $model->PostCuntPlus();*/
        Response::show(402,'不允许的操作');
    }

    public function actionUpdate($id)
    {
       /* $model = $this->findModel($id);
        $model->load(Yii::$app->getRequest()->getBodyParams(), '');

        if (!$model->save()) {
            return array_values($model->getFirstErrors())[0];
        }*/

        Response::show(402,'不允许的操作');
    }

    public function actionDelete($id)
    {
       /* $model = new $this->modelClass();
        $thread_id = $_GET['thread_id'];
        if($this->findModel($id)->delete()){
            $model->PostCuntDel($thread_id);
            Response::show('202','删除成功');

        }*/
        Response::show(402,'不允许的操作');
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
