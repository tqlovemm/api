<?php

namespace api\modules\v2\models;

use Yii;
use app\components\db\ActiveRecord;

/**
 * This is the model class for table "user_mark".
 *
 * @property integer $id
 * @property string $mark_name
 * @property string $make_friend_name
 * @property string $hobby_name
 *
 */
class Mark extends ActiveRecord
{

    public static function tableName()
    {
        return '{{%user_mark}}';
    }

    public function getId()
    {
        return $this->user_id;
    }

    public function rules()
    {
        return [
            [['mark_name','make_friend_name','hobby_name'], 'string'],
        ];
    }

    // 返回的数据格式化
    public function fields()
    {
        //$fields = parent::fields();

        // remove fields that contain sensitive information
        //unset($fields['auth_key'], $fields['password_hash'], $fields['password_reset_token']);


        return [

            'id','mark_name','make_friend_name','hobby_name',
        ];
    }

    public function attributeLabels()
    {
        return [
            'mark_name' => '标签名',
            'make_friend_name' => '交友要求',
            'hobby_name' => '兴趣爱好',


        ];
    }

}
