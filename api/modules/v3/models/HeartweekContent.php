<?php

namespace api\modules\v3\models;

use app\components\db\ActiveRecord;
use Yii;


/**
 * This is the model class for table "pre_heartweek_content".
 *
 * @property integer $id
 * @property string $content
 * @property string $name
 * @property string $path
 * @property integer $album_id
 * @property string $thumb
 * @property integer $updated_at
 * @property integer $created_at
 */
class HeartweekContent extends ActiveRecord
{

    public static function tableName()
    {
        return '{{%heartweek_content}}';
    }

    public function getId()
    {
        return $this->id;
    }


    public function rules()
    {
        return [

            [['content','name','path','thumb'], 'string'],
            [['created_at','updated_at','album_id'], 'integer'],
        ];
    }
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'content' => '内容',
            'name' => '标题',
            'thumb' => '链接',
            'album_id' => 'Album ID',
            'avatar' => '头像',
            'updated_at' => 'Updated At',
            'created_at' => 'Updated At',
            'status' => '状态',

        ];
    }


    // 返回的数据格式化
    public function fields()
    {
        $fields = parent::fields();
        $fields["heartweek_content_id"] = $fields['id'];
    //  remove fields that contain sensitive information
        unset($fields['id']);

        return $fields;

    }




}
