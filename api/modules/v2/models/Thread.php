<?php

namespace api\modules\v2\models;

use app\components\db\ActiveRecord;
use common\models\User;
use Yii;


/**
 * This is the model class for table "forum_thread".
 *
 * @property integer $id
 * @property string $content
 * @property string $user_id
 * @property string $post_count
 * @property string $note
 * @property string $read_count
 * @property string $is_stick
 * @property integer $image_path
 * @property integer $created_at
 * @property integer $updated_at
 */
class Thread extends ActiveRecord
{

    public static function tableName()
    {
        return '{{%forum_thread}}';
    }

    public function getId()
    {
        return $this->id;
    }

    public function rules()
    {
        return [
            [['content','image_path','user_id'], 'required'],
            [['created_at', 'updated_at','note','post_count','read_count','is_stick','user_id'], 'integer'],
        ];
    }
    public function getUser(){

        $user = Yii::$app->db->createCommand('select u.id as user_id,u.groupid,u.username,u.nickname,u.email,u.cellphone,u.sex,u.status,u.avatar,u.created_at,d.*,p.* from {{%user}} as u LEFT JOIN {{%user_data}} as d ON d.user_id=u.id LEFT JOIN {{%user_profile}} as p ON p.user_id=u.id WHERE id='.$this->user_id)->queryOne();

        if(isset($_GET['uid'])){

            $uid = $_GET['uid'];

            $follow = Yii::$app->db->createCommand('select * from {{%user_follow}} WHERE user_id='.$uid.' and people_id='.$this->user_id)->queryOne();

            $user['follow']=(integer)!empty($follow);
            return $user;
        }
        return $user;
    }
    public function extraFields()
    {
        return [
            'user'=>'user',
        ];
    }
    // 返回的数据格式化
    public function fields()
    {
        //$fields = parent::fields();

        // remove fields that contain sensitive information
        // unset($fields['auth_key'], $fields['password_hash'], $fields['password_reset_token']);

        return [

            'thread_id'=>'id','created_at','updated_at','user_id','post_count','note','read_count','is_stick',
            'content'=>function($model){

                $preg = "/<\/?[^>]+>/i";
                return $model['content'] = trim(preg_replace($preg,'',$model['content']),'&nbsp;');
            },
            'image_path'=>function($model){

                return json_decode($model['image_path']);
            }

        ];

    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'content' => '内容',
            'post_count' => '评论数',
            'note' => '点赞数',
            'read_count' => '阅读数',
            'is_stick' => '置顶',
            'image_path' => '图片路径',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
    public function changeCount(){

        Data::updateKey('thread_count',$this->user_id);
    }

}
