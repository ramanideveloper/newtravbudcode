<?php
namespace frontend\models;

use yii\base\Model;
use Yii;
use yii\mongodb\ActiveRecord;

/**
 * This is the model class for collection "user_unfollowers".
 *
 * @property \MongoId|string $_id
 * @property mixed $post_text
 * @property mixed $post_status
 * @property mixed $post_created_date
 * @property mixed $post_user_id
 * @property mixed $image
 */

class UnfollowFriend extends ActiveRecord
{
   /*public $imageFile1;*/
    /**
     * @return string the name of the index associated with this ActiveRecord class.
     */
    public static function collectionName()
    {
        return 'user_unfollowers';
    }

    /**
     * @return array list of attribute names.
     */
    public function attributes()
    {
        return ['_id', 'user_id', 'unfollow_ids'];
    }
    
     /**
     * Get all user notification in decsending order
     * @param  nill
     * @return array
     * @Author  Sonali Patel
     * @Date    02-03-2016 (dd-mm-yyyy)
    */ 
     public function  getUnfollowfriendsIds($uid)
     {
       return UnfollowFriend::find()->where(['user_id'=>"$uid"])->one();  
     }
}