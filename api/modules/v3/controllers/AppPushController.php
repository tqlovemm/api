<?php

namespace api\modules\v3\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\web\NotFoundHttpException;
use yii\helpers\Response;
use yii\data\ActiveDataProvider;

class AppPushController extends ActiveController
{
    public $modelClass = 'api\modules\v3\models\AppPush';
    //分页
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
        $query = $modelClass::find()->where(['cid'=>$_GET['cid']])->orWhere(['type'=>1])->orderBy('created_at DESC');

        return new ActiveDataProvider([
            'query' => $query,
        ]);
    }
    public function actionView($id){

        $query_all = Yii::$app->db->createCommand("select count(*) from {{%app_push}} where cid='$id'")->queryScalar();
        $query_unread_all = Yii::$app->db->createCommand("select count(*) from {{%app_push}} where cid='$id'and is_read=1")->queryScalar();
        $query_unread_group_1 = Yii::$app->db->createCommand("select type,count(type) as num from {{%app_push}} where cid='$id'and is_read=1 group by type")->queryAll();

        $query_unread_group=array();

        foreach($query_unread_group_1 as $item){$query_unread_group[$item['type']]=$item['num'];}

        $query_unread_group['total']=$query_all;

        $query_unread_group['unread_total']=$query_unread_all;

        return $query_unread_group;

    }


    public function actionUpdate($id){

        $model = $this->findModels($id);

        $model->load(Yii::$app->getRequest()->getBodyParams(), '');

        if (!$model->save()) {

            return array_values($model->getFirstErrors())[0];
        }

        return $model;

    }
    public function actionDelete($id)
    {

        if(isset($_GET['cid'])){

            if($this->findModel($id,$_GET['cid'])->delete()){

                Response::show('202','删除成功');
            }

        }else{

            if($this->findModelDAll($id)->deleteAll(['cid'=>$id])){

                Response::show('202','删除成功');

            }
        }
    }


    protected function findModel($id,$cid)
    {
        $modelClass = $this->modelClass;

            if (($model = $modelClass::findOne(['id'=>$id,'cid'=>$cid])) !== null) {
                return $model;
            } else {
                throw new NotFoundHttpException('The requested page does not exist.');
            }

    }
    protected function findModels($id)
    {
        $modelClass = $this->modelClass;

            if (($model = $modelClass::findOne($id)) !== null) {
                return $model;
            } else {
                throw new NotFoundHttpException('The requested page does not exist.');
            }

    }
    protected function findModelDAll($id)
    {
        $modelClass = $this->modelClass;

            if (($model = $modelClass::findOne(['cid'=>$id])) !== null) {
                return $model;
            } else {
                throw new NotFoundHttpException('The requested page does not exist.');
            }

    }

}
