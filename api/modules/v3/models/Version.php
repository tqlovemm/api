<?php

namespace api\modules\v3\models;

use app\components\db\ActiveRecord;
use Yii;


/**
 * This is the model class for table "pre_app_version".
 *
 * @property integer $id
 * @property string $build
 * @property string $version
 * @property string $app_name
 * @property string $url
 * @property string $platform
 * @property integer $update_info
 * @property integer $is_force_update
 */
class Version extends ActiveRecord
{

    public static function tableName()
    {
        return '{{%app_version}}';
    }


    public function rules()
    {
        return [

            [['build','version','app_name','url','platform','update_info'], 'string'],
            [['is_force_update'], 'integer'],
        ];
    }
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'build' => '构建版本',
            'version' => '版本号',
            'app_name' => 'app名称',
            'url' => 'app更新链接',
            'platform' => '平台（Android/ios）',
            'is_force_update' => '是否强制更新',
            'update_info' => '更新内容',

        ];
    }

    // 返回的数据格式化
    public function fields()
    {
        $fields = parent::fields();
        $fields["app_id"] = $fields['id'];
    //  remove fields that contain sensitive information
        unset($fields['id']);

        return $fields;

    }




}
