<?php

namespace api\modules\v3\models;

use app\components\db\ActiveRecord;
use Yii;


/**
 * This is the model class for table "pre_app_push".
 *
 * @property integer $id
 * @property integer $status
 * @property string $cid
 * @property string $title
 * @property string $msg
 * @property string $extras
 * @property string $response
 */
class AppPush extends ActiveRecord
{

    public static function tableName()
    {
        return '{{%app_push}}';
    }

    public function getId()
    {
        return $this->id;
    }


    public function rules()
    {
        return [

            [['cid','msg','extras','response','title','status'],'required'],
            [['cid','msg','extras','response','title'], 'string'],
            [['status'], 'integer'],
        ];
    }
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'msg' => '推送内容',
            'extras' => '推送内容json',
            'title' => '推送标题',
            'response' => '响应',
            'cid' => 'app唯一标识',
            'status' => '状态',

        ];
    }


    // 返回的数据格式化
    public function fields()
    {
/*      $fields = parent::fields();
        $fields["dating_id"] = $fields['id'];
    //  remove fields that contain sensitive information
        unset($fields['id']);*/

        return [

            'push_id'=>'id','title','msg','extras','status','cid','response'

        ];

    }




}
