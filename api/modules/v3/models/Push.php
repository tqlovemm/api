<?php

namespace api\modules\v3\models;

use app\components\db\ActiveRecord;
use Yii;


/**
 * This is the model class for table "pre_user".
 *
 * @property integer $id
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $username
 * @property string $cid
 */
class Push extends ActiveRecord
{

    public static function tableName()
    {
        return '{{%user}}';
    }

    public function getId()
    {
        return $this->id;
    }


    public function rules()
    {
        return [

            [['username','cid'], 'string'],
            [['created_at','updated_at'], 'integer'],
        ];
    }
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => '用户名',
            'cid' => 'app用户唯一标识',
            'updated_at' => 'Updated At',
            'created_at' => 'Updated At',


        ];
    }



    // 返回的数据格式化
    public function fields()
    {
        $fields = parent::fields();
        $fields["user_id"] = $fields['id'];
    //  remove fields that contain sensitive information
        unset($fields['id']);

        return $fields;

    }




}
