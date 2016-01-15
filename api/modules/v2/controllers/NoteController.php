<?php

namespace api\modules\v2\controllers;

use Yii;
use yii\helpers\Response;
use yii\web\Controller;


class NoteController extends Controller
{

   public function actionView($id){

       Yii::$app->db->createCommand("UPDATE {{%forum_thread}} SET note=note+1 WHERE id=".$id)->execute();
       Response::show(202,'点赞成功');

   }

}
