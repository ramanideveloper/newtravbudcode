<?php
namespace frontend\models;
use yii\base\Model;
use Yii;
use yii\mongodb\ActiveRecord;

use yii\web\UploadedFile;

/**
 * This is the model class for collection "user_visitor".
 *
 * @property \MongoId|string $_id
 * @property mixed $post_text
 * @property mixed $post_status
 * @property mixed $post_created_date
 * @property mixed $post_user_id
 * @property mixed $image
 */

class ProfileVisitor extends ActiveRecord
{
   /*public $imageFile1;*/
    /**
     * @return string the name of the index associated with this ActiveRecord class.
     */
    public static function collectionName()
    {
        return 'user_visitor';
    }

    /**
     * @return array list of attribute names.
     */
    public function attributes()
    {
        return ['_id', 'user_id', 'visitor_id', 'visited_date', 'status', 'ip'];
    }
   
    /**
     * User model relations
     */
    public function getUser()
    {
        return $this->hasOne(UserForm::className(), ['_id' => 'visitor_id']);
    }
    
    /**
     * Get all Visitors in decsending order
     */
     public function getAllVisitors($guserid)
    {
        $visitors = ProfileVisitor::find()->with('user')->where(['user_id'=>"$guserid"])->orderBy(['visited_date'=>SORT_DESC])->all();
        $count = count($visitors);
        return $count;
    }
    
    /**
     * Get all Visitors in decsending order
     */
     public function getAllProfileVisitors($guserid)
    {
        return ProfileVisitor::find()->where(['user_id'=>"$guserid"])->orderBy(['visited_date'=>SORT_DESC])->all();
    }
}