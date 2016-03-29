<?php

namespace api\modules\v3\models;

use app\components\db\ActiveRecord;
use Yii;


/**
 * This is the model class for table "pre_weekly_content".
 *
 * @property integer $id
 * @property integer $album_id
 * @property string $name
 * @property string $thumb
 * @property string $path
 * @property string $store_name
 * @property integer $updated_at
 * @property integer $created_at
 * @property integer $is_cover
 */
class DatingContent extends ActiveRecord
{

    public static function tableName()
    {
        return '{{%weekly_content}}';
    }

    public function getId()
    {
        return $this->id;
    }


    public function rules()
    {
        return [

            [['album_id','name','path','store_name'], 'string'],
            [['created_at','updated_at','is_cover','album_id'], 'integer'],
        ];
    }
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'album_id' => '相册ID',
            'name' => '地区',
            'path' => '图片',
            'store_name' => '存储名',
            'is_cover' => 'IS COVER',
            'updated_at' => 'Updated At',
            'created_at' => 'Updated At',
        ];
    }
    // 返回的数据格式化
    public function fields()
    {
      $fields = parent::fields();
        $fields["dating_content_id"] = $fields['id'];
    //  remove fields that contain sensitive information
        unset($fields['id']);
        return $fields;

    }


}
