<?php

namespace api\modules\v2\models;

use app\components\db\ActiveRecord;
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

    // 返回的数据格式化
    public function fields()
    {
        $fields = parent::fields();

        // remove fields that contain sensitive information
        unset($fields['auth_key'], $fields['password_hash'], $fields['password_reset_token']);

        return $fields;
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

}
