<?php

namespace api\modules\v2\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\helpers\Response;

class ThreadController extends ActiveController
{
    public $modelClass = 'api\modules\v2\models\Thread';


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
        if(isset($_GET['max_id'])){

            $max_id = $_GET['max_id'];
            $latest = $modelClass::find()->where("id>{$max_id}")->orderBy('created_at desc');

            return new ActiveDataProvider([
                'query' => $latest,
            ]);
        }
        if(isset($_GET['min_id'])){

            $min_id = $_GET['min_id'];

            $before = $modelClass::find()->where("id<{$min_id}")->orderBy('created_at desc');

            return new ActiveDataProvider([
                'query' => $before,
            ]);

        }


        $query = $modelClass::find()->orderBy('created_at desc');


        return new ActiveDataProvider([
            'query' => $query,
        ]);
    }

    public function actionCreate()
    {


        $model = new $this->modelClass();

        $model->load(Yii::$app->getRequest()->getBodyParams(), '');

        $images = $model->image_path;
        $Mpath = array();
        $images = explode(',',$images);
        for($i=0;$i<count($images);$i++){

            $pathStr = "uploads/umeditor/".date("Ymd");
            if ( !file_exists( $pathStr ) ) {
                if ( !mkdir( $pathStr , 0777 , true ) ) {
                    return false;
                }
            }
            $savePath = $pathStr.'/'.time().rand(1,10000).'.jpg';
            file_put_contents($savePath,base64_decode($images[$i]));
            $abs_path = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER["SERVER_PORT"].'/'.$savePath;
            $model->content = $model->content.'<img src="'.$abs_path.'"/>';
            array_push($Mpath,$abs_path);

        }
        $model->image_path = json_encode($Mpath);

        if (!$model->save()) {
            return array_values($model->getFirstErrors())[0];
        }

        $model->changeCount(1);

        Response::show('202','保存成功');

    }

    public function actionUpdate($id)
    {
        /*$model = $this->findModel($id);
        $model->load(Yii::$app->getRequest()->getBodyParams(), '');

        if (!$model->save()) {
            return array_values($model->getFirstErrors())[0];
        }
        return $model;*/
        Response::show(401,'不允许的操作');
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
        if (($model = $modelClass::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
