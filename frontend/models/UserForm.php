<?php
namespace frontend\models;
use yii\base\Model;
use Yii;
use yii\mongodb\ActiveRecord;


/**
 * This is the model class for collection "tbl_user_post".
 *
 * @property \MongoId|string $_id
 * @property integer $fb_id
 * @property mixed $username
 * @property mixed $password
 * @property mixed $email
 * @property mixed $photo
 * @property mixed $created_date
 * @property mixed $updated_date
 */
class UserForm extends ActiveRecord
{
    /**
     * @return string the name of the index associated with this ActiveRecord class.
     */
    public static function collectionName()
    {
        return 'tbl_user';
    }

    /**
     * @return array list of attribute names.
     */
    public function attributes()
    {
        return ['_id', 'fb_id', 'username', 'fname','lname','fullname', 'password', 'con_password', 'pwd_changed_date', 'email','alternate_email','photo','thumbnail','profile_picture','cover_photo', 'birth_date','gender','created_date','updated_date','created_at','updated_at','status','phone','isd_code','country','city','captcha','reference_user_id','member_type','last_login_time','forgotcode','forgotpassstatus','lat','long','login_from_ip'];

    }
   
    /**
     * Friend model relations
     */
    public function getFriends()
    {
        return $this->hasMany(Friend::className(), ['from_id' => '_id']);
    }
   
    /**
     * Friend model relations
     */
    public function getSavedPosts()
    {
        return $this->hasMany(UserForm::className(), ['user_id' => '_id']);
    }
    
       // return PostForm::find()->with('user')->orderBy(['post_created_date'=>SORT_DESC])->all();
        

    /**
     * User model relations
     */
    public function getPosts()
    {
        return $this->hasMany(PostForm::className(), ['post_user_id' => '_id']);
    }// User has_many Posts.
    
    
     /**
     * Like model relations
     */
    public function getUserlikes()
    {
        return $this->hasMany(Like::className(), ['user_id' => '_id']);
    }// User has_many Posts.
    
     /**
     * Comment model relations
     */
    public function getUsercomment()
    {
        return $this->hasMany(Comment::className(), ['user_id' => '_id']);
    }// User has_many Posts.
    
     /**
     * Check If User Exist
     */
     public function isUserExist($email)
    {
        return  UserForm::find()->where(['email' => $email])->one();
    }
    
    
    
    /**
     * Get Last Inserted Id
     */
    public function getLastInsertedRecord($email)
    {
        return UserForm::find()->select(['_id'])->where(['email' => $email])->one();
    }
}