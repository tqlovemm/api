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

        }else{

            $user_id = Yii::$app->db->createCommand('select user_id from {{%forum_thread}} where id='.$model->thread_id)->queryOne();
            $cid = Yii::$app->db->createCommand('select cid from {{%user}} where id='.$user_id['user_id'])->queryOne();

            if(!empty($cid['cid'])){

                $title = "有人评价了您的帖子";
                $msg = "有人评价了您的帖子";
                $date = time();
                $icon = 'http://13loveme.com:82/images/app_push/u=1630850300,1879297584&fm=21&gp=0.png';
                $extras = json_encode(array('push_title'=>urlencode($title),'push_content'=>urlencode($msg),'push_post_id'=>$model->thread_id,'push_type'=>'SSCOMM_NEWSCOMMENT_DETAIL'));
                Yii::$app->db->createCommand("insert into {{%app_push}} (status,cid,title,msg,extras,platform,response,icon,created_at,updated_at) values('SSCOMM_NEWSCOMMENT_DETAIL','$cid[cid]','$title','$msg','$extras','all','NULL','$icon',$date,$date)")->execute();
            }

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
