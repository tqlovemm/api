<?php

namespace api\modules\v2\models;

use app\components\db\ActiveRecord;
use Yii;


/**
 * This is the model class for table "forum_post".
 *
 * @property integer $id
 * @property string $content
 * @property integer $user_id
 * @property integer $thread_id
 * @property integer $created_at
 * @property integer $updated_at
 */
class Post extends ActiveRecord
{

    public static function tableName()
    {
        return '{{%forum_post}}';
    }

    public function getId()
    {
        return $this->id;
    }


    public function rules()
    {
        return [
            [['content','user_id','thread_id'], 'required'],
            [['content'], 'string'],
            [['created_at','updated_at','user_id','thread_id'], 'integer'],
        ];
    }
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'content' => '内容',
            'user_id' => '用户ID',
            'thread_id' => '帖子ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',

        ];
    }
    // 返回的数据格式化
    public function fields()
    {
        $fields = parent::fields();
        $fields["post_id"] = $fields['id'];
        // remove fields that contain sensitive information
        unset($fields['id']);

        return $fields;

    }
    public function getUser()
    {
        $models = User::find()->select("id,username,nickname,groupid,sex,email,avatar,cellphone")->where('id=:uid',[':uid'=>$this->user_id])->orderBy('created_at DESC');

        $model = Yii::$app->db->createCommand('select u.id as user_id,u.groupid,u.username,u.nickname,u.email,u.cellphone,u.sex,u.status,u.avatar,u.created_at from {{%user}} as u WHERE id='.$this->user_id.' order by created_at desc')->queryOne();

        return $model;
    }

    public function extraFields()
    {
        return [
            'user' => 'user',
        ];
    }

    /**
     * @inheritdoc
     */
    public function PostCuntPlus()
    {
        return Yii::$app->db->createCommand("UPDATE {{%forum_thread}} SET post_count=post_count+1 WHERE id=".$this->thread_id)->execute();
    }

    public function PostCuntDel($thread_id)
    {
        return Yii::$app->db->createCommand("UPDATE {{%forum_thread}} SET post_count=post_count-1 WHERE id=$thread_id")->execute();
    }



}
