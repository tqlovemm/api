<?php

namespace api\modules\v2\models;

use app\components\db\ActiveRecord;
use common\models\User;
use Yii;


/**
 * This is the model class for table "user_follow".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $people_id
 */
class Ufollow extends ActiveRecord
{

    public static function tableName()
    {
        return '{{%user_follow}}';
    }

    public function getId()
    {
        return $this->id;
    }

    public function rules()
    {
        return [
            [['user_id','people_id'], 'required'],
            [['created_at', 'updated_at','user_id','people_id'], 'integer'],
        ];
    }

    // 返回的数据格式化
    public function fields()
    {
        $fields = parent::fields();

        // remove fields that contain sensitive information
        // unset($fields['auth_key'], $fields['password_hash'], $fields['password_reset_token']);
        unset($fields['id']);
        return $fields;

    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => '用户id',
            'people_id' => '被粉id',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
    public function changeCount(){

        Data::updateKey('thread_count',$this->user_id);
    }

}
