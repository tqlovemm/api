<?php

namespace api\modules\v2\models;

use Yii;
use app\components\db\ActiveRecord;

/**
 * This is the model class for table "user_profile".
 *
 * @property integer $user_id
 * @property string $birthdate
 * @property string $signature
 * @property string $address
 * @property string $description
 * @property string $mark
 * @property string $make_friend
 * @property string $hobby
 * @property integer $height
 * @property integer $weight
 * @property integer $updated_at
 * @property integer $created_at
 *
 */
class Profile extends ActiveRecord
{

    public static function tableName()
    {
        return '{{%user_profile}}';
    }

    public function getId()
    {
        return $this->user_id;
    }


    public function rules()
    {
        return [
            [['user_id','height','weight','updated_at','created_at'], 'integer'],
            [['address','mark','hobby','make_friend','signature','description'], 'string'],
            [['birthdate'],'string']
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

    public function attributeLabels()
    {
        return [
            'user_id' => '用户ID',
            'birthdate' => '生日',
            'signature' => '个人签名',
            'address' => '地址',
            'make_friend' => '交友要求',
            'description' => '自我介绍',
            'mark' => '标签',
            'hobby' => '兴趣爱好',
            'height' => '身高',
            'weight' => '体重',
            'created_at' => '体重',
            'updated_at' => '体重',

        ];
    }

}
