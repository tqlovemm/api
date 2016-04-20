<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/1/19
 * Time: 15:01
 */

namespace api\modules\v2\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\Response;

class FollowController extends Controller
{

    public function actionView($id){

        $user_id = $_GET['user_id'];
        $query = Yii::$app->db->createCommand('select * from {{%user_follow}} where user_id='.$id.' and people_id='.$user_id)->queryOne();
        if(!empty($query)){

            Yii::$app->db->createCommand("delete from {{%user_follow}} where user_id=".$id." and people_id=".$user_id)->execute();
            Yii::$app->db->createCommand("UPDATE {{%user_data}} SET following_count=following_count-1 WHERE user_id=".$id)->execute();
            Yii::$app->db->createCommand("UPDATE {{%user_data}} SET follower_count=follower_count-1 WHERE user_id=".$user_id)->execute();
            Response::show(200,'取消关注');

        }else{

            Yii::$app->db->createCommand("insert into {{%user_follow}} (user_id,people_id) VALUES ({$id},{$user_id})")->execute();
            Yii::$app->db->createCommand("UPDATE {{%user_data}} SET following_count=following_count+1 WHERE user_id=".$id)->execute();
            Yii::$app->db->createCommand("UPDATE {{%user_data}} SET follower_count=follower_count+1 WHERE user_id=".$user_id)->execute();
            $cid = Yii::$app->db->createCommand('select cid from {{%user}} where id='.$user_id)->queryOne();

            if(!empty($cid['cid'])){
                $title = "有人关注您为好友";
                $msg = "有人关注您为好友";
                $extras = json_encode(array('push_title'=>urlencode($title),'push_content'=>urlencode($msg),'push_type'=>'SSCOMM_FANS'));
                Yii::$app->db->createCommand("insert into {{%app_push}} (status,cid,title,msg,extras,platform,response) values(2,'$cid[cid]','$title','$msg','$extras','all','NULL')")->execute();

            }

            Response::show(202,'关注成功');

        }
    }
}
