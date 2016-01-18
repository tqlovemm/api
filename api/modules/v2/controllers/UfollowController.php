<?php

namespace api\modules\v2\controllers;

use api\modules\v2\models\Data;
use api\modules\v2\models\User;
use Yii;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\QueryParamAuth;
use yii\rest\ActiveController;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\helpers\Response;

class UfollowController extends ActiveController
{
    public $modelClass = 'api\modules\v2\models\Ufollow';
    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

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
        $query = $modelClass::find();

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

    public function getArray($obj){
        $arr = is_object($obj) ? get_object_vars($obj) : $obj;
        if(is_array($arr)){
            return array_map(__FUNCTION__, $arr);
        }else{
            return $arr;
        }
    }
    public function actionView($id)
    {

        $following = $this->findModels2($id);   //喜欢
        $follower = $this->findModels($id);    //粉丝


        $er = array();
        $ing = array();
        if(!empty(count($follower))){

            for($i=0;$i<count($follower);$i++){

                $uid = $follower[$i]['user_id'];
                $command = Yii::$app->db->createCommand('SELECT u.id as user_id,u.groupid,u.username,u.email,u.cellphone,u.sex,u.status,u.avatar,u.created_at,
                                                        ud.post_count,ud.feed_count,ud.following_count,ud.follower_count,ud.thread_count,ud.empirical_value,ud.unread_message_count,
                                                        up.birthdate,up.signature,up.address,up.description as self_introduction,up.mark,up.make_friend,up.hobby,up.height,up.weight
                                                        FROM {{%user}} as u LEFT JOIN {{%user_data}} as ud ON ud.user_id=u.id LEFT JOIN {{%user_profile}} as up ON up.user_id=u.id WHERE id='.$uid);
                $post = $command->queryOne();

                $to = Yii::$app->db->createCommand('select * from {{%user_follow}} WHERE user_id='.$uid.' and people_id='.$id)->execute();
                $from = Yii::$app->db->createCommand('select * from {{%user_follow}} WHERE user_id='.$id.' and people_id='.$uid)->execute();

                $post['mark'] = json_decode($post['mark']);
                $post['make_friend'] = json_decode($post['make_friend']);
                $post['hobby'] = json_decode($post['hobby']);

                if($to&&$from){

                    $post['each']=1;
                }else{

                    $post['each']=0;
                }

                array_push($er,$post);

            }
        }
        if(!empty(count($following))){

            for($i=0;$i<count($following);$i++){

                $uid = $following[$i]['people_id'];
                $command = Yii::$app->db->createCommand('SELECT u.id as user_id,u.groupid,u.username,u.email,u.cellphone,u.sex,u.status,u.avatar,u.created_at,
                                                        ud.post_count,ud.feed_count,ud.following_count,ud.follower_count,ud.thread_count,ud.empirical_value,ud.unread_message_count,
                                                        up.birthdate,up.signature,up.address,up.description as self_introduction,up.mark,up.make_friend,up.hobby,up.height,up.weight
                                                        FROM {{%user}} as u LEFT JOIN {{%user_data}} as ud ON ud.user_id=u.id LEFT JOIN {{%user_profile}} as up ON up.user_id=u.id WHERE id='.$uid);
                $post = $command->queryOne();


                $to = Yii::$app->db->createCommand('select * from {{%user_follow}} WHERE user_id='.$uid.' and people_id='.$id)->execute();
                $from = Yii::$app->db->createCommand('select * from {{%user_follow}} WHERE user_id='.$id.' and people_id='.$uid)->execute();

                $post['mark'] = json_decode($post['mark']);
                $post['make_friend'] = json_decode($post['make_friend']);
                $post['hobby'] = json_decode($post['hobby']);
                if($to&&$from){

                    $post['each']=1;
                }else{

                    $post['each']=0;
                }

                array_push($ing,$post);

            }
        }

        $follow['following'] = $ing;
        $follow['follower'] = $er;

        return $follow;
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
    protected function findModels2($id)
    {
        $modelClass = $this->modelClass;
        if (($model = $modelClass::find()->where('user_id='.$id)->asArray()->all()) !== null) {

            return $model;

        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    protected function findModels($id)
    {
        $modelClass = $this->modelClass;
        if (($model = $modelClass::find()->where('people_id='.$id)->asArray()->all()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


}
