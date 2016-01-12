<?php

namespace api\modules\v2\models;

use app\components\db\ActiveRecord;
use Yii;
use yii\helpers\Url;
use yii\web\IdentityInterface;
use yii\web\Link;
use yii\web\Linkable;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $username
 * @property string $avatar
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property integer $role
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class User1 extends ActiveRecord implements IdentityInterface,Linkable
{
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }
    public function getLinks()
    {
        // TODO: Implement getLinks() method.
        return [
            Link::REL_SELF => Url::to(['user/view', 'id' => $this->id], true),
        ];
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access-token' => $token]);
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return $this->authKey;
    }

    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username','email'], 'required'],
            [['role', 'status', 'created_at', 'updated_at'], 'integer'],
            [['username', 'avatar', 'password_hash', 'password_reset_token', 'email'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32]
        ];
    }

    // 返回的数据格式化
    public function fields()
    {
        $fields = parent::fields();
        // remove fields that contain sensitive information
        unset($fields['auth_key'],$fields['password_hash'], $fields['password_reset_token'],$fields['avatarid'],$fields['avatartemp'],$fields['role']);

        return $fields;
    }

    public function getThread(){

       $model = Yii::$app->db->createCommand("select t.id as thread_id,t.content,t.created_at,t.updated_at,t.post_count,t.note,t.read_count,t.is_stick,t.image_path as image from {{%forum_thread}} as t WHERE t.user_id=".$this->id)->queryAll();

       for($i=0;$i<count($model);$i++){

           $model[$i]['image'] = json_decode($model[$i]['image']);
       }


        return $model;

    }
    public function extraFields()
    {
        return [
            'thread'=>'thread',
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'avatar' => '头像',
            'auth_key' => 'Auth Key',
            'password_hash' => 'Password Hash',
            'password_reset_token' => 'Password Reset Token',
            'email' => 'Email',
            'role' => 'Role',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

}
