<?php

namespace api\modules\v2\controllers;

use Yii;
use yii\data\Pagination;
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
        $command = Yii::$app->db->createCommand('SELECT u.id as user_id,u.groupid,u.username,u.email,u.cellphone,u.sex,u.status,u.avatar,u.created_at,
                                                        ud.post_count,ud.feed_count,ud.following_count,ud.follower_count,ud.thread_count,ud.empirical_value,ud.unread_message_count,
                                                        up.birthdate,up.signature,up.address,up.description as self_introduction,up.mark,up.make_friend,up.hobby,up.height,up.weight
                                                        FROM {{%user}} as u LEFT JOIN {{%user_data}} as ud ON ud.user_id=u.id LEFT JOIN {{%user_profile}} as up ON up.user_id=u.id WHERE id='.$id);
        $post = $command->queryAll();
        $post[0]['avatar']= 'http://182.254.217.147:8888/uploads/user/avatar/'.$post[0]['avatar'];
        return $post[0];
/*      $model = $this->findModel($id);
        $model->avatar = 'http://182.254.217.147:8888/uploads/user/avatar/'.$model->avatar;
        return $model;*/

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
