<?php

namespace api\modules\v2\models;
use app\components\db\ActiveRecord;
use Yii;

/**
 * This is the model class for table "flop_content".
 *
 * @property integer $id
 * @property string $content
 * @property integer $flop_id
 * @property string $area
 * @property string $number
 * @property string $path
 * @property integer $sex
 * @property integer $height
 * @property integer $weight
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $like_count
 * @property integer $nope_count
 */
class FlopContent extends ActiveRecord
{

    public static function tableName()
    {
        return '{{%flop_content}}';
    }

    public function getId()
    {
        return $this->id;
    }

    public function rules()
    {
        return [
            [['created_at','sex', 'updated_at','like_count','nope_count','weight','height','flop_id'], 'integer'],
            [['number','path','path','area' ], 'string'],
        ];
    }


    // 返回的数据格式化
    public function fields()
    {
        //$fields = parent::fields();

        // remove fields that contain sensitive information
        // unset($fields['auth_key'], $fields['password_hash'], $fields['password_reset_token']);

        return [

            'flop_content_id'=>'id','created_at','updated_at',
            'flop_id','like_count','nope_count','weight','height',
            'area','number','content'=>function($model){

                return json_encode($model['content']);

            }, 'path'

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
            'nope_count' => '不喜欢数',
            'number' => '编号',
            'flop_id' => '地区id',
            'read_count' => '阅读数',
            'weight' => '体重',
            'height' => '身高',
            'like_count' => '喜欢数',
            'path' => '图片路径',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }


}
