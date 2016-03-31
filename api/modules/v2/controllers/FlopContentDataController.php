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

        $appends = ",".$model->append;

        $query = Yii::$app->db->createCommand("select * from {{%flop_content_data}} where flag=$id")->queryOne();

        $content = explode(',',$query['content']);
        $priority = explode(',',$query['priority']);

        if(isset($_GET['type'])&&$_GET['type']==1){

            if(!in_array($model->append,$content)) {

                Yii::$app->db->createCommand("update {{%flop_content_data}} set content=concat(content,'$appends'),priority=concat(priority,'$appends') where flag=$id")->execute();
                Yii::$app->db->createCommand("update {{%flop_content}} set like_count=like_count+1 where id=$model->append")->execute();
                Response::show('202','加入成功');
            }

        }elseif((isset($_GET['type'])&&$_GET['type']==0)){


            if(in_array($model->append,$content)){

                $content= array_filter(array_unique($content));

                unset($content[array_search($model->append,$content)]);

                $content = implode(',',$content);

                Yii::$app->db->createCommand("update {{%flop_content_data}} set content='$content' where flag=$id")->execute();

            }

            if(in_array($model->append,$priority)){

                $priority= array_filter(array_unique($priority));

                unset($priority[array_search($model->append,$priority)]);

                $priority = implode(',',$priority);

                Yii::$app->db->createCommand("update {{%flop_content_data}} set priority='$priority' where flag=$id")->execute();
            }

            Response::show('202','删除成功');

        }elseif((isset($_GET['type'])&&$_GET['type']==2)){

            if(in_array($model->append,$priority)){

                $priority= array_filter(array_unique($priority));

                unset($priority[array_search($model->append,$priority)]);

                $priority = implode(',',$priority);

                Yii::$app->db->createCommand("update {{%flop_content_data}} set priority='$priority' where flag=$id")->execute();

                Response::show('202','删除成功');

            }

        }else{


            Response::show('402','参数错误');
        }

        /* if (!$model->save()) {
            return array_values($model->getFirstErrors())[0];
        }*/

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

        $content = explode(',',$model->content);$priority = explode(',',$model->priority);

        $contents = array();$priorities = array();

        foreach($content as $item){
            $photo = Yii::$app->db->createCommand("select path from {{%flop_content}} WHERE id=$item")->queryOne();
            array_push($contents,$photo);
        }

        foreach($priority as $item){
            $photo = Yii::$app->db->createCommand("select path from {{%flop_content}} WHERE id=$item")->queryOne();
            array_push($priorities,$photo);
        }

        $model->content = $contents;$model->priority = $priorities;

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
