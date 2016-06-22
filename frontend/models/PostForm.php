<?php
namespace frontend\models;
use yii\base\Model;
use Yii;
use yii\mongodb\ActiveRecord;
use yii\web\UploadedFile;
//namespace mongosoft\mongodb;
use yii\behaviors\TimestampBehavior;
//use frontend\models\Friend;

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

class PostForm extends ActiveRecord
{
   /*public $imageFile1;*/
    /**
     * @return string the name of the index associated with this ActiveRecord class.
     */
    public static function collectionName()
    {
        return 'tbl_user_post';
    }

    /**
     * @return array list of attribute names.
     */
    public function attributes()
    {


        return ['_id','post_type', 'post_title', 'post_text', 'shared_by','post_status', 'post_created_date', 'post_user_id','image','link_title','link_description','share_by','shared_from','album_title','is_album','album_place','album_img_date','post_privacy','is_timeline','is_deleted','currentlocation','parent_post_id','is_coverpic','share_setting','comment_setting', 'post_tags','custom_share', 'custom_notshare', 'anyone_tag'];


    }
   
    /**
     * Post model relations
     */
    public function getUser()
    {
        return $this->hasOne(UserForm::className(), ['_id' => 'post_user_id']);
    }// Post has_one user.
    
     /**
     * Friend model relations
     */
    public function getSavedPosts()
    {
        return $this->hasMany(SavePost::className(), ['post_id' => '_id']);
    }
    /**
     * Like model relations
     */
    public function getPostlike()
    {
        return $this->hasMany(Like::className(), ['_id' => 'user_id']);
    }// Post has_one user.
    
     /**
     * Comment model relations
     */
    public function getPostcomment()
    {
        return $this->hasMany(Comment::className(), ['_id' => 'user_id']);
    }// Post has_one user.
    
   /*  public function rules()
    {
        return [
            [['imageFile1'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg'],
        ];
    }*/
    
    /**
     * Get all Posts in decsending order
     */
     public function getAllPost()
    {
        return PostForm::find()->with('user')->where(['is_deleted'=>"0"])->orderBy(['post_created_date'=>SORT_DESC])->all();
        
    }
    /**
     * To update all the posts with is_deleted = 0;
     
    
    public function updateallposts()
    {
        $update = new PostForm();
        
        $update =  PostForm::find()->all();
        $update->is_deleted = '0';
        $update->update();
        
        return true;
        
        
    }*/
    
    
     /**
     * Get all Posts in decsending order
     * @param user id.
     * @return user posts.

     */
     public function getUserPost($userid)
    {
        $session = Yii::$app->session;
	$uid = (string)$session->get('user_id');
	if($uid == (string)$userid)
	{
            return PostForm::find()->with('user')->where(['post_user_id'=>"$userid",'is_deleted'=>'0'])->orwhere(['like','post_tags',"$userid"])->orderBy(['post_created_date'=>SORT_DESC])->all();
	}
	else
	{
            return PostForm::find()->with('user')->where(['post_user_id'=>"$userid",'is_deleted'=>'0'])->andWhere(['not in', 'post_privacy', 'Private'])->orwhere(['like','post_tags',"$userid"])->orderBy(['post_created_date'=>SORT_DESC])->all();
	}
        
    }
    
     /**
     * Get all Posts in decsending order
     * @param user id.
     * @return user posts photos.

     */
     public function getUserPostPhotos($userid)
    {
        $session = Yii::$app->session;
	$uid = (string)$session->get('user_id');
	if($uid == (string)$userid)
	{
            return PostForm::find()->with('user')->where(['post_user_id'=>"$userid",'is_deleted'=>'0','is_timeline' => null])->orderBy(['post_created_date'=>SORT_DESC])->all();
	}
	else
	{
            return PostForm::find()->with('user')->where(['post_user_id'=>"$userid",'is_deleted'=>'0','is_timeline' => null])->andWhere(['not in', 'post_privacy', 'Private'])->orderBy(['post_created_date'=>SORT_DESC])->all();
	}
        
    }
    
     /**
     * Get all Posts count of friends
     * @param user id.
     * @return user posts count.

     */
     public function getUserPostBudge()
    {
        $session = Yii::$app->session;
        $uid = $session->get('user_id');
        $friends =  Friend::getuserFriends($uid);
        
        $ids = array();
        foreach($friends as $friend)
        {
            $ids[]= $friend['from_id'];
        }
        $user_last_login_date = UserForm::find()->where(['_id' => "$uid"])->one();//last_login_time
       
        $mute_ids = MuteFriend::getMutefriendsIds($uid);
        $mute_friend_ids =  (explode(",",$mute_ids['mute_ids']));
        //$mute_friend_ids[] = $mute_ids['mute_ids'];
        //echo '<pre>';print_r($mute_friend_ids);die;
    // $last_login_time = $user_last_login_date->last_login_time;
   
    //  $friend_posts =  PostForm::find()->with('user')->where(['post_created_date'=>'1459591686']
     // ['post_created_date' => ['$gt', new MongoDate(strtotime("1459591686"))]]
                       // ['post_type'=>'text']           )          ->all();
       // $friend_posts =  PostForm::find()->with('user')->where(['in','post_user_id',$ids])->andwhere(['post_created_date'=> ['$gt',"$last_login_time"]])->orderBy(['post_created_date'=>SORT_DESC])->all();
        
        $friend_posts =  PostForm::find()->with('user')->where(['in','post_user_id',$ids])->andwhere(['not in','post_user_id',$mute_friend_ids])->orderBy(['post_created_date'=>SORT_DESC])->all();
      //   $friend_posts =  PostForm::find()->with('user')->where(['in','post_user_id',$ids])->orderBy(['post_created_date'=>SORT_DESC])->all();
      //  echo '<pre>'; print_r($friend_posts);die;
        return $post_count = count($friend_posts);
    }
    
     /**
     * Get all Posts of friends
     * @param user id.
     * @return user posts count.

     */
     public function getUserNotifications()
    {
        $session = Yii::$app->session;
        $uid = $session->get('user_id');
        $friends =  Friend::getuserFriends($uid);
        $user_last_login_date = UserForm::find()->where(['_id' => "$uid"])->one();//last_login_time
       //$last_login_time = $user_last_login_date->last_login_time;
        $ids = array();
        foreach($friends as $friend)
        {
            $ids[]= $friend['from_id'];
        }
        //->andwhere(['post_created_date'=> ['$gt',"$last_login_time"]])
        $friend_posts =  PostForm::find()->with('user')->where(['in','post_user_id',$ids])->orderBy(['post_created_date'=>SORT_DESC])->all();
      
        return $friend_posts;
    }
    
    
    /**
     * Get all Posts of user and his/her friends
     * @param user id.
     * @return user posts resultset.

     */
     public function getUserFriendsPosts($flag= '')
    {
        $session = Yii::$app->session;
        $uid = $session->get('user_id');
        $friends =  Friend::getuserFriends($uid);
        
        $ids = array();
        foreach($friends as $friend)
        {
            $ids[]= $friend['from_id'];
        }
     
        $ids[] = array_push($ids,(string)$uid);
        $unfollow_ids = UnfollowFriend::getUnfollowfriendsIds($uid);
        $unfollow_friend_ids =  (explode(",",$unfollow_ids['unfollow_ids']));
       
        $hidepost = HidePost::find()->where(['user_id' => (string)$uid])->one();
        $hide_ids = explode(',',$hidepost['post_ids']); 
        
        if($flag == 'updates')
        {
            $user_friend_posts =  PostForm::find()->with('user')->where(['in','post_user_id',$ids])->andwhere(['is_deleted'=>"0"])->andwhere(['not in','post_user_id',$unfollow_friend_ids])->andwhere(['not in','_id',$hide_ids])->andwhere(['not','post_privacy','Private'])->orderBy(['post_created_date'=>SORT_DESC])->all();
        }
        else 
        {
            $user_friend_posts =  PostForm::find()->with('user')->where(['in','post_user_id',$ids])->andwhere(['is_deleted'=>"0"])->andwhere(['not in','post_user_id',$unfollow_friend_ids])->andwhere(['not in','_id',$hide_ids])->orderBy(['post_created_date'=>SORT_DESC])->all();
        }
        return $user_friend_posts;
    }
    
    /**
     * Get all Posts in decsending order
     * @param user id.
     * @return user posts.

     */
     public function getAlbums($userid)
    {
        $session = Yii::$app->session;
        $uid = (string)$session->get('user_id');
        if($uid == (string)$userid)
        {
            return PostForm::find()->with('user')->where(['post_user_id'=>"$userid",'is_album'=>'1','is_deleted'=>'0','is_timeline' => null])->orderBy(['post_created_date'=>SORT_ASC])->all();
        }
        else
        {
            return PostForm::find()->with('user')->where(['post_user_id'=>"$userid",'is_album'=>'1','is_deleted'=>'0','is_timeline' => null])->andWhere(['not in', 'post_privacy', 'Private'])->orderBy(['post_created_date'=>SORT_ASC])->all();
        }
        
    }
    
    /**
     * Get all Posts in decsending order
     * @param user id.
     * @return user posts.

     */
     public function getProfilePics($userid)
    {
        $session = Yii::$app->session;
        $uid = (string)$session->get('user_id');
        if($uid == (string)$userid)
        {
            return PostForm::find()->with('user')->where(['post_user_id'=>"$userid",'post_type'=>'profilepic','is_deleted'=>'0','is_timeline' => null])->orderBy(['post_created_date'=>SORT_DESC])->all();
        }
        else
        {
            return PostForm::find()->with('user')->where(['post_user_id'=>"$userid",'post_type'=>'profilepic','is_deleted'=>'0','is_timeline' => null])->andWhere(['not in', 'post_privacy', 'Private'])->orderBy(['post_created_date'=>SORT_DESC])->all();
        }
    }
    
    /**
     * Get all Posts in decsending order
     * @param user id.
     * @return user posts.

     */
     public function getCoverPics($userid)
    {
        $session = Yii::$app->session;
        $uid = (string)$session->get('user_id');
        if($uid == (string)$userid)
        {
            return PostForm::find()->with('user')->where(['post_user_id'=>"$userid",'post_type'=>'image','is_coverpic'=>'1','is_deleted'=>'0','is_timeline' => null])->orderBy(['post_created_date'=>SORT_DESC])->all();
        }
        else
        {
            return PostForm::find()->with('user')->where(['post_user_id'=>"$userid",'post_type'=>'image','is_coverpic'=>'1','is_deleted'=>'0','is_timeline' => null])->andWhere(['not in', 'post_privacy', 'Private'])->orderBy(['post_created_date'=>SORT_DESC])->all();
        }
    }
    
    /**
     * Get all Posts in decsending order
     * @param user id.
     * @return user posts.

     */
     public function getPics($userid)
    {
        $session = Yii::$app->session;
        $uid = (string)$session->get('user_id');
        if($uid == (string)$userid)
        {
            $results = PostForm::find()->where(['post_type'=>'text and image'])
                ->orwhere(['post_type'=>'image'])
                ->andwhere(['post_user_id'=>"$userid",'is_deleted'=>'0','is_timeline' => null,'is_coverpic' => null])
                ->orderBy(['post_created_date'=>SORT_DESC])->all();
        }
        else
        {
            $results = PostForm::find()->where(['post_type'=>'text and image'])
                ->orwhere(['post_type'=>'image'])
                ->andwhere(['post_user_id'=>"$userid",'is_deleted'=>'0','is_timeline' => null,'is_coverpic' => null])
                ->andWhere(['not in', 'post_privacy', 'Private'])
                ->orderBy(['post_created_date'=>SORT_DESC])->all();
        }
        $total = '';
        foreach($results as $result)
        {
            if(isset($result['image']) && !empty($result['image']))
            {
                $eximgs = explode(',',$result['image'],-1);
                if(count($eximgs) > 0){$tpics = count($eximgs);}else{$tpics = 0;}
                $total = $tpics + $total;
            }
        }
        return $total;
    }
}