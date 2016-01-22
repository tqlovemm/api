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
 * @property string $description
 */
class ClaimsThread extends ActiveRecord
{

    public static function tableName()
    {
        return '{{%thread_claims}}';
    }

    public function getId()
    {
        return $this->thread_id;
    }


    public function rules()
    {
        return [
            [['content','user_id','thread_id'], 'required'],
            [['created_at','updated_at','user_id','thread_id'], 'integer'],
            [['content','description'], 'string'],
        ];
    }

    // 返回的数据格式化
    public function fields()
    {
        $fields = parent::fields();

        // remove fields that contain sensitive information

        return $fields;
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
}
