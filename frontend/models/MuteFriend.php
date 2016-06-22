<?php
namespace frontend\models;

use yii\base\Model;
use Yii;
use yii\mongodb\ActiveRecord;

/**
 * This is the model class for collection "user_mutes".
 *
 * @property \MongoId|string $_id
 * @property mixed $post_text
 * @property mixed $post_status
 * @property mixed $post_created_date
 * @property mixed $post_user_id
 * @property mixed $image
 */

class MuteFriend extends ActiveRecord
{
   /*public $imageFile1;*/
    /**
     * @return string the name of the index associated with this ActiveRecord class.
     */
    public static function collectionName()
    {
        return 'user_mute';
    }

    /**
     * @return array list of attribute names.
     */
    public function attributes()
    {
        return ['_id', 'user_id', 'mute_ids'];
    }
    
    
    /**
     * Get all user mute friends ids
     * @param  user id 
     * @return array
     * @Author Sonali Patel
     * @Date   06-04-2016 (dd-mm-yyyy)
     */  
    public function getMutefriendsIds($user_id)
    {
        return MuteFriend::find()->where(['user_id'=>"$user_id"])->one();
    }
    
}