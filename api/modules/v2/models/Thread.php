<?php

namespace api\modules\v2\models;

use app\components\db\ActiveRecord;
use common\models\User;
use Yii;


/**
 * This is the model class for table "forum_thread".
 *
 * @property integer $id
 * @property string $content
 * @property string $user_id
 * @property string $post_count
 * @property string $note
 * @property string $read_count
 * @property string $is_stick
 * @property integer $image_path
 * @property integer $created_at
 * @property integer $updated_at
 */
class Thread extends ActiveRecord
{

    public static function tableName()
    {
        return '{{%forum_thread}}';
    }

    public function getId()
    {
        return $this->id;
    }

    public function rules()
    {
        return [
            [['content','image_path','user_id'], 'required'],
            [['created_at', 'updated_at','note','post_count','read_count','is_stick','user_id'], 'integer'],
        ];
    }
    public function getUser(){


        return User::find()->select('username,email,cellphone,sex,avatar');

    }
    public function extraFields()
    {
        return [
            'user'=>'user',
        ];
    }
    // 返回的数据格式化
    public function fields()
    {
        //$fields = parent::fields();
/* "id": 1139,
      "title": "人厨房秤",
      "content": "<p><img src=\"http://13loveme.com/uploads/umeditor/20151209/14496519659767.jpg\" _src=\"http://13loveme.com/uploads/umeditor/20151209/14496519659767.jpg\"/></p>",
      "created_at": 1449651974,
      "updated_at": 1450162892,
      "user_id": 10054,
      "board_id": 1003,
      "post_count": 0,
      "note": 2,
      "read_count": 76,
      "is_stick": 0,
      "image_path"
 * */

        // remove fields that contain sensitive information
        // unset($fields['auth_key'], $fields['password_hash'], $fields['password_reset_token']);

        return [

            'id','created_at','updated_at','user_id','post_count','note','read_count','is_stick',
            'content'=>function($model){

                $preg = "/<\/?[^>]+>/i";
                return $model['content'] = trim(preg_replace($preg,'',$model['content']),'&nbsp;');
            },
            'image_path'=>function($model){


                return json_decode($model['image_path']);
            }

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
            'post_count' => '评论数',
            'note' => '点赞数',
            'read_count' => '阅读数',
            'is_stick' => '置顶',
            'image_path' => '图片路径',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
    public function changeCount($num=1){

        Data::updateKey('thread_count',$this->user_id,$num);
    }

}
