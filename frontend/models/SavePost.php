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

class SavePost extends ActiveRecord
{
   /*public $imageFile1;*/
    /**
     * @return string the name of the index associated with this ActiveRecord class.
     */
    public static function collectionName()
    {
        return 'user_savedpost';
    }

    
    
    /**
     * @return array list of attribute names.
     */
    public function attributes()
    {
        return ['_id', 'user_id', 'post_id', 'post_type', 'is_saved', 'saved_date'];
    }
    
     public function getUserDetail()
    {
        return $this->hasOne(UserForm::className(), ['_id' => 'user_id']);
    }
    
     public function getPostData()
    {
        return $this->hasOne(PostForm::className(), ['_id' => 'post_id']);
    }
    
}