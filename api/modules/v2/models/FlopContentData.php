<?php

namespace api\modules\v2\models;

use app\components\db\ActiveRecord;
use Yii;


/**
 * This is the model class for table "pre_flop_content_data".
 *
 * @property integer $id
 * @property string $content
 * @property string $priority
 * @property integer $user_id
 * @property integer $flag
 * @property integer $created_at
 * @property integer $updated_at
 */
class FlopContentData extends ActiveRecord
{

    public static function tableName()
    {
        return '{{%flop_content_data}}';
    }

    public function rules()
    {
        return [

            [['created_at', 'updated_at','user_id','flag'], 'integer'],
            [['content', 'priority'], 'string'],
        ];
    }


    public function fields()
    {
        $fields = parent::fields();
        $fields["flop_content_data_id"] = $fields['id'];
        // remove fields that contain sensitive information

        return $fields;

    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'content' => '内容',
            'priority' => '翻牌',
            'flag' => '标识',
            'user_id' => '用户ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

}
