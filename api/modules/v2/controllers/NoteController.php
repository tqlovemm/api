<?php

namespace api\modules\v2\controllers;

use Yii;
use yii\helpers\Response;
use yii\web\Controller;

class NoteController extends Controller
{

   public function actionView($id){



       $user_id = $_GET['user_id'];

       $query = Yii::$app->db->createCommand("select * from {{%user_notes}} WHERE thread_id=".$id." and note_by=".$user_id)->execute();


       if(empty($query)){

           Yii::$app->db->createCommand("UPDATE {{%forum_thread}} SET note=note+1 WHERE id=".$id)->execute();
           Yii::$app->db->createCommand("insert into {{%user_notes}} (note_by,note_at,thread_id) values ({$user_id},".time().",{$id})")->execute();
           $query_one = Yii::$app->db->createCommand("select * from {{%forum_thread}} WHERE id=".$id)->queryOne();

           Response::show(202,'点赞成功',$query_one['note']);

       }else{

           $query_one = Yii::$app->db->createCommand("select * from {{%forum_thread}} WHERE id=".$id)->queryOne();

           Response::show(402,'已经存在',$query_one['note']);

       }

   }

}
