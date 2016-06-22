<?php
namespace frontend\models;
use yii\base\Model;
use Yii;
use yii\mongodb\ActiveRecord;
use yii\web\UploadedFile;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for collection "tbl_user_photo".
 *
 * @property \MongoId|string $_id
 * @property mixed $post_text
 * @property mixed $post_status
 * @property mixed $post_created_date
 * @property mixed $post_user_id
 * @property mixed $image
 */

class PhotoForm extends ActiveRecord
{
   /*public $imageFile1;*/
    /**
     * @return string the name of the index associated with this ActiveRecord class.
     */
    public static function collectionName()
    {
        return 'tbl_user_photo';
    }

    /**
     * @return array list of attribute names.
     */
    public function attributes()
    {
        return ['_id', 'photo_type', 'photo_status', 'photo_created_date', 'is_deleted', 'photo_user_id', 'image', 'photo_privacy'];
    }
    
    /**
     * @return array list of photography post.
     */
    public function getAllPics()
    {
        return PhotoForm::find()->where(['is_deleted'=>"0"])->orderBy(['post_created_date'=>SORT_DESC])->all();
        
    }
}