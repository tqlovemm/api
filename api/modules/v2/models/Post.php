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
            [['created_at','updated_at','user_id','thread_id'], 'integer'],
        ];
    }

    // 返回的数据格式化
    public function fields()
    {
        $fields = parent::fields();

        // remove fields that contain sensitive information
        unset($fields['auth_key'], $fields['password_hash'], $fields['password_reset_token']);

        return $fields;
    }
    public function getUser()
    {
        $model = User::find()->select("id,username,groupid,sex,email,avatar,cellphone")->where('id=:uid',[':uid'=>$this->user_id])->orderBy('created_at DESC');


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

}
