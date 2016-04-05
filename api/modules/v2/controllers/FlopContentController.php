<?php

namespace api\modules\v2\controllers;


use Yii;
use yii\helpers\ArrayHelper;
use yii\rest\ActiveController;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\helpers\Response;
use api\modules\v2\models\FlopContentData;


class FlopContentController extends ActiveController
{
    public $modelClass = 'api\modules\v2\models\FlopContent';


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
        $exist = FlopContentData::find()->select('priority')->where(['>','created_at',time()-86400*5])->asArray()->all();
        //$model = Yii::$app->db->createCommand("select priority from {{%flop_content_data}} where ")->queryAll();

        $exists = array_filter(explode(',',implode(',',array_filter(ArrayHelper::map($exist,'priority','priority')))));

        //$exists = array_unique(array_filter(explode(',',implode(',',ArrayHelper::map($exist,'priority','priority')))));
        $count = count($modelClass::find()->all());


        /*按区域选择*/
        if(isset($_GET['area'])){

            if($count>25){

                $query = $modelClass::find()->where(['area'=>$_GET['area']])->andWhere(['not in','id',$exists]);

            }else{

                $query = $modelClass::find()->where(['area'=>$_GET['area']]);
            }

        }

        if(isset($_GET['culling'])&&$_GET['culling']==1){

            /*精选*/
            $query = $modelClass::find()->where(['flop_id'=>49])->andWhere(['not in','id',$exists])->orWhere(['>','like_count',10])->andWhere(['not in','id',$exists]);

        }


        return new ActiveDataProvider([
            'query' => $query,
        ]);
    }

    public function actionCreate()
    {
        Response::show(401,'不允许的操作');
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->load(Yii::$app->getRequest()->getBodyParams(), '');

        if (!$model->save()) {
            return array_values($model->getFirstErrors())[0];
        }
        return $model;

    }

    public function actionDelete($id)
    {
        Response::show(401,'不允许的操作');
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
