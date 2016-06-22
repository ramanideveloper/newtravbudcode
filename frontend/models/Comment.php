<?php
namespace frontend\models;
use yii\base\Model;
use Yii;
use yii\mongodb\ActiveRecord;
use yii\web\UploadedFile;

/**
 * This is the model class for collection "tbl_user_post".
 *
 * @property \MongoId|string $_id
 * @property mixed $post_text
 * @property mixed $post_status
 * @property mixed $post_created_date
 * @property mixed $post_user_id
 * @property mixed $image
 */

class Comment extends ActiveRecord
{
   /*public $imageFile1;*/
    /**
     * @return string the name of the index associated with this ActiveRecord class.
     */
    public static function collectionName()
    {
        return 'post_comment';
    }

    /**
     * @return array list of attribute names.
     */
    public function attributes()
    {
        return ['_id', 'user_id', 'post_id', 'comment','comment_type','image','status','created_date','updated_date','ip','parent_comment_id'];
    }
   
    /**
     * User model relations
     */
    public function getUser()
    {
        return $this->hasOne(UserForm::className(), ['_id' => 'user_id']);
    }// Post has_one user.
    
    /**
     * Post model relations
     */
    public function getPost()
    {
        return $this->hasOne(PostForm::className(), ['_id' => 'post_id']);
    }// User has_many Posts.
    
     /**
     * Like model relations
     */
    public function getLike()
    {
        return $this->hasMany(Like::className(), ['comment_id' => '_id']);
    }
    
    /**
     * Get all Likes in decsending order
     */
     public function getAllComment()
    {
        return Comment::find()->with('user')->orderBy(['created_date'=>SORT_DESC])->all();
        
    }
    
     /**
     * Get all user posts Commnets in decsending order
     * @param  post id 
     * @return array
     * @Author  Sonali Patel
     * @Date    02-03-2016 (dd-mm-yyyy)
     */ 
     public function getAllPostLike($post_id)
    {
        $comments = Comment::find()->with('user')->with('post')->where(['post_id' => "$post_id",'status' => '1','parent_comment_id'=>'0'])->orderBy(['created_date'=>SORT_DESC])->all();
        return $comments;
        
    }
    
    
    
     /**
     * Get first 3  posts Comments in decsending order
     * @param  post id 
     * @return array
     * @Author  Sonali Patel
     * @Date    07-04-2016 (dd-mm-yyyy)
     */ 
     public function getFirstThreePostComments($post_id)
    {
       return  $init_comments = Comment::find()->with('user')->with('post')->where(['post_id' => "$post_id",'status' => '1','parent_comment_id'=>'0'])->orderBy(['created_date'=>SORT_DESC])->limit(3)->all();
        
    }
     /**
     * Get all user posts Likes in decsending order
     */
     public function getUserPostComment($user_id)
    {
        return Comment::find()->with('user')->with('post')->where(['user_id' => $user_id,'status' => '1','parent_comment_id'=>'0'])->orderBy(['created_date'=>SORT_DESC])->all();
        
    }
    
     /**
     * Get all user posts Likes in decsending order
     */
     public function getCommentCount($post_id)
    {
        $comments = Comment::find()->where(['post_id' => $post_id,'status' => '1'])->orderBy(['created_date'=>SORT_DESC])->all();
        
        return count($comments);
    }
    
    /**
     * Get comment reply
     * @param  comment id 
     * @return Array
     * @Author Sonali Patel
     * @Date   11-04-2016 (dd-mm-yyyy)
     */  
    public function getCommentReply($comment_id)
    {
        return Comment::find()->with('user')->with('post')->where(['parent_comment_id' => "$comment_id",'status' => '1'])->orderBy(['created_date'=>SORT_DESC])->all();
    }
    
}