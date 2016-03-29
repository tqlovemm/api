<?php

namespace api\modules\v3\models;

use app\components\db\ActiveRecord;
use Yii;


/**
 * This is the model class for table "pre_weekly_comment".
 *
 * @property integer $id
 * @property integer $weekly_id
 * @property integer $user_id
 * @property string $content
 * @property integer $status
 * @property integer $likes
 * @property integer $updated_at
 * @property integer $created_at
 * @property integer $first_comment
 */
class DatingComment extends ActiveRecord
{

    public static function tableName()
    {
        return '{{%weekly_comment}}';
    }

    public function rules()
    {
        return [

            [['user_id'],'required'],
            [['content'], 'string'],
            [['created_at','updated_at','status','weekly_id','likes','user_id'], 'integer'],
        ];
    }
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'content' => '内容',
            'weekly_id' => '相册ID',
            'likes' => '点赞',
            'user_id' => '会员ID',
            'updated_at' => 'Updated At',
            'created_at' => 'Updated At',
            'status' => '状态',

        ];
    }
    // 返回的数据格式化
    public function fields()
    {
        $fields = parent::fields();
        $fields["comment_id"] = $fields['id'];
    //  remove fields that contain sensitive information
        unset($fields['id']);
        return $fields;
    }


}
