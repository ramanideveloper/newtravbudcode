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

class Like extends ActiveRecord
{
   /*public $imageFile1;*/
    /**
     * @return string the name of the index associated with this ActiveRecord class.
     */
    public static function collectionName()
    {
        return 'user_like';
    }

    /**
     * @return array list of attribute names.
     */
    public function attributes()
    {
        return ['_id', 'user_id', 'post_id', 'comment_id','like_type','status','created_date','updated_date','ip'];
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
     * Post model relations
     */
    public function getComment()
    {
        return $this->hasOne(Comment::className(), ['_id' => 'comment_id']);
    }
    
    /**
     * Get all Likes in decsending order
     */
     public function getAllLike()
    {
        return Like::find()->with('user')->orderBy(['created_date'=>SORT_DESC])->all();
        
    }
    
     /**
     * Get all Likes in decsending order
     */
     public function getAllPostLike($post_id)
    {
        return Like::find()->with('user')->with('post')->where(['post_id' => $post_id,'status' => '1'])->orderBy(['created_date'=>SORT_DESC])->all();
        
    }
    
     /**
     * Get all user posts Likes in decsending order
     */
     public function getUserPostLike($user_id)
    {
        return Like::find()->with('user')->with('post')->where(['user_id' => "$user_id",'status' => '1'])->orderBy(['created_date'=>SORT_DESC])->all();
        
    }
    
     /**
     * Get all user posts Likes in decsending order
     */
     public function getLikeCount($post_id)
    {
        $likes = Like::find()->where(['post_id' => $post_id,'status' => '1'])->orderBy(['created_date'=>SORT_DESC])->all();
        
        return count($likes);
    }
    
     /**
     * Get all user posts Likes in decsending order
     * @param  post id 
     * @return array
     * @Author  Sonali Patel
     * @Date    02-04-2016 (dd-mm-yyyy)
     */  
    
     public function getLikeUserNames($post_id)
    {
        $session = Yii::$app->session;
        $likes_buddy_counts = 0;
        $uid = $session->get('user_id');
        $likes_buddy_names = Like::find()->with('user')->where(['post_id' => "$post_id",'status' => '1'])->andwhere(['not in','user_id',array("$uid")])->orderBy(['created_date'=>SORT_DESC])->limit(3)->all();
        if(count($likes_buddy_names) == 3)
            $offset = 2;
        else 
            $offset = count($likes_buddy_names)-3;
        if($offset >=1)
        {
            $likes_buddy_counts = Like::find()->with('user')->where(['post_id' => "$post_id",'status' => '1'])->andwhere(['not in','user_id',array("$uid")])->orderBy(['created_date'=>SORT_DESC])->offset($offset)->all();
        } 
        $is_like_login = Like::find()->with('user')->where(['post_id' => "$post_id",'status' => '1','user_id'=> "$uid"])->orderBy(['updated_date'=>SORT_DESC])->one();
       
       if(!empty($is_like_login))
       {
           if(!empty($likes_buddy_names))
                $names = 'You, ';
           else
               $names = ucfirst($is_like_login['user']['fname']).' '.ucfirst($is_like_login['user']['lname']);
            $ctr = count($likes_buddy_counts)-1;
       }
       else
       {
           $names = '';
           $ctr = count($likes_buddy_counts);
       }
       foreach($likes_buddy_names AS $like_buddy_name)
       {
            $names .= ucfirst($like_buddy_name['user']['fname']).' '.ucfirst($like_buddy_name['user']['lname']).', ';
       }
       
       $data['count'] = $ctr; 
       $data['like_ctr'] = count($likes_buddy_names);
       $data['names'] =  trim($names, ", ");
       $data['login_user_details'] = $is_like_login;
       
       return $data;
    }
    /**
     * Get all user posts Likes in decsending order
     * @param  post id 
     * @return array
     * @Author  Sonali Patel
     * @Date    02-04-2016 (dd-mm-yyyy)
     */  
    
     public function getLikeUser($post_id)
    {
        $likes_buddy = Like::find()->with('user')->where(['post_id' => $post_id,'status' => '1'])->orderBy(['updated_date'=>SORT_DESC])->all();
        
       // echo '<pre>';print_r($likes_buddy);exit;
        
        return $likes_buddy;
    }
    
    public function getUserCommentLike($user_id,$comment_id)
    {
        $user_comments_like = Like::find()->where(['user_id' => "$user_id",'comment_id' => "$comment_id"])->orderBy(['updated_date'=>SORT_DESC])->one();
        //print_r($user_comments_like);exit;
        return $user_comments_like['status'];
    }
    
}