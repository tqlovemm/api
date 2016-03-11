<?php

namespace api\modules\v2\models;

use app\components\db\ActiveRecord;
use Yii;


/**
 * This is the model class for table "flop".
 *
 * @property integer $id
 * @property string $content
 * @property string $area
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_by
 */
class Flop extends ActiveRecord
{

    public static function tableName()
    {
        return '{{%flop}}';
    }

    public function getId()
    {
        return $this->id;
    }

    public function rules()
    {
        return [
            [['area','content','status'], 'required'],
            [['area','content'], 'string'],
            [['created_at', 'updated_at','created_by','status'], 'integer'],

        ];
    }

    // 返回的数据格式化
    public function fields()
    {
        //$fields = parent::fields();

        // remove fields that contain sensitive information
        //unset($fields['cover_id'], $fields['created_by']);

        return [

            'flop_id'=>'id','area','content','created_at','status',
        ];

    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'content' => '简介',
            'area' => '地区',
            'created_by' => '创建人',
            'status' => '状态',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

}
