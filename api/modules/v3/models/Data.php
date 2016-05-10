<?php

namespace api\modules\v3\models;

use Yii;
use app\components\db\ActiveRecord;

/**
 * This is the model class for table "user_data".
 *
 * @property integer $user_id
 * @property integer $post_count
 * @property integer $feed_count
 * @property integer $following_count
 * @property integer $follower_count
 * @property integer $unread_notice_count
 * @property integer $unread_message_count
 * @property integer $thread_count
 * @property integer $empirical_value
 *
 */
class Data extends ActiveRecord
{

    public static function tableName()
    {
        return '{{%user_data}}';
    }

    public function getId()
    {
        return $this->user_id;
    }


    public function rules()
    {
        return [
            [['user_id','post_count','feed_count','following_count','thread_count','empirical_value','unread_message_count','unread_notice_count','follower_count'], 'integer'],
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

    public static function updateKey($key, $userId, $nums = 1, $add = true)
    {
        switch ($key) {
            case 'post_count':
            case 'thread_count':
            case 'feed_count':
            case 'following_count':
            case 'follower_count':
            case 'unread_notice_count':
            case 'unread_message_count':
            case 'empirical_value':
                break;
            default:
                return false;
                break;
        }
        if ($add) {
            return Yii::$app->db->createCommand("UPDATE {{%user_data}} SET {$key}={$key}+{$nums} WHERE user_id=".$userId)->execute();
        } else {
            return Yii::$app->db->createCommand("UPDATE {{%user_data}} SET {$key}={$nums} WHERE user_id=".$userId)->execute();
        }
    }

}
