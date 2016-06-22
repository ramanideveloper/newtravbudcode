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

class Notification extends ActiveRecord
{
  
    /**
     * @return string the name of the index associated with this ActiveRecord class.
     */
    public static function collectionName()
    {
        return 'notification';
    }

    /**
     * @return array list of attribute names.
     */
    public function attributes()
    {
        return ['_id', 'user_id', 'post_id','post_owner_id','shared_by','share_id','from_friend_id', 'comment_content','comment_id','reply_comment_id','like_id','page_id','event_id','notification_type','status','created_date','updated_date','ip','is_deleted','review_setting'];
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
        return $this->hasMany(Like::className(), ['_id' => 'like_id']);
    }
    
    /**
     * Comment model relations
     */
    public function getComment()
    {
        return $this->hasMany(Comment::className(), ['_id' => 'comment_id']);
    }
     /**
     * Get all user notification in decsending order
     * @param  nill
     * @return array
     * @Author  Sonali Patel
     * @Date    02-03-2016 (dd-mm-yyyy)
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
        $mute_ids = MuteFriend::getMutefriendsIds($uid);
        $mute_friend_ids =  (explode(",",$mute_ids['mute_ids']));
        
        //Note ::: here date condition is pending 
        $login_user = LoginForm::find()->where(['_id' => "$uid"])->one();
        
        $view_noti_time =  $login_user->last_login_time;
        
        $notification_settings = NotificationSetting::find()->where(['user_id' => (string)$uid])->one();
        
        if($notification_settings)
        {
            if($notification_settings['friend_activity'] == 'Yes')
            {
        
                // $notification = Notification::find()->with('user')->with('post')->with('comment')->with('like')->where(['in','user_id',$ids])->andwhere(['not in','user_id',$mute_friend_ids])->orderBy(['created_date'=>SORT_DESC])->all();

                //$notification = Notification::find()->with('user')->with('post')->with('comment')->with('like')->where(['in','user_id',$ids])->andwhere(['not in','user_id',$mute_friend_ids])->andwhere(['created_date'=> ['$gte'=>"$view_noti_time"]])->orderBy(['created_date'=>SORT_DESC])->all();

                $notificationpost = Notification::find()->with('user')->with('post')->with('comment')->with('like')->where(['in','user_id',$ids])->andwhere(['not in','user_id',$mute_friend_ids])->andwhere(['not','notification_type','tag_friend'])->andwhere(['not','notification_type','friendrequestaccepted'])->andwhere(['not','notification_type','friendrequestdenied'])->andwhere(['not','notification_type','comment'])->andwhere(['not','notification_type','likepost'])->andwhere(['not','notification_type','likecomment'])->andwhere(['not','notification_type','commentreply'])->andwhere(['not','notification_type','sharepost'])->andwhere(['is_deleted'=>"0"])->andwhere(['created_date'=> ['$gte'=>"$view_noti_time"]])->orderBy(['created_date'=>SORT_DESC])->all();

                $notificationtag = Notification::find()->with('user')->with('post')->with('comment')->with('like')->where(['notification_type'=>'tag_friend','user_id'=>(string)$uid])->andwhere(['created_date'=> ['$gte'=>"$view_noti_time"]])->andwhere(['is_deleted'=>"0"])->orderBy(['created_date'=>SORT_DESC])->all();

                if($notification_settings['friend_request'] == 'Yes')
                {
                    $notificationfriendaccepted = Notification::find()->with('user')->with('post')->with('comment')->with('like')->where(['notification_type'=>'friendrequestaccepted','from_friend_id'=>(string)$uid])->andwhere(['is_deleted'=>"0"])->andwhere(['created_date'=> ['$gte'=>"$view_noti_time"]])->orderBy(['created_date'=>SORT_DESC])->all();

                    $notificationfrienddenied = Notification::find()->with('user')->with('post')->with('comment')->with('like')->where(['notification_type'=>'friendrequestdenied','from_friend_id'=>(string)$uid])->andwhere(['is_deleted'=>"0"])->andwhere(['created_date'=> ['$gte'=>"$view_noti_time"]])->orderBy(['created_date'=>SORT_DESC])->all();
                }
                else
                {
                    $notificationfriendaccepted = array();
                    $notificationfrienddenied = array();
                }

                if($notification_settings['friend_activity_on_user_post'] == 'Yes')
                {
                    $notificationcomment = Notification::find()->with('user')->with('post')->with('comment')->with('like')->where(['notification_type'=>'comment','post_owner_id'=>(string)$uid])->andwhere(['is_deleted'=>"0"])->andwhere(['created_date'=> ['$gte'=>"$view_noti_time"]])->orderBy(['created_date'=>SORT_DESC])->all();

                    $notificationlikepost = Notification::find()->with('user')->with('post')->with('comment')->with('like')->where(['notification_type'=>'likepost','post_owner_id'=>(string)$uid])->andwhere(['is_deleted'=>"0"])->andwhere(['created_date'=> ['$gte'=>"$view_noti_time"]])->orderBy(['created_date'=>SORT_DESC])->all();

                    $notificationlikecomment = Notification::find()->with('user')->with('post')->with('comment')->with('like')->where(['notification_type'=>'likecomment','post_owner_id'=>(string)$uid])->andwhere(['is_deleted'=>"0"])->andwhere(['created_date'=> ['$gte'=>"$view_noti_time"]])->orderBy(['created_date'=>SORT_DESC])->all();

                    $notificationcommentreply = Notification::find()->with('user')->with('post')->with('comment')->with('like')->where(['notification_type'=>'commentreply','post_owner_id'=>(string)$uid])->andwhere(['is_deleted'=>"0"])->andwhere(['created_date'=> ['$gte'=>"$view_noti_time"]])->orderBy(['created_date'=>SORT_DESC])->all();
                    
                    $notificationsharepost = Notification::find()->with('user')->with('post')->with('comment')->with('like')->where(['notification_type'=>'sharepost','post_owner_id'=>(string)$uid])->andwhere(['is_deleted'=>"0"])->andwhere(['created_date'=> ['$gte'=>"$view_noti_time"]])->orderBy(['created_date'=>SORT_DESC])->all();
                    
                    $notificationsharepostowner = Notification::find()->with('user')->with('post')->with('comment')->with('like')->where(['notification_type'=>'sharepost','user_id'=>(string)$uid])->andwhere(['is_deleted'=>"0"])->andwhere(['created_date'=> ['$gte'=>"$view_noti_time"]])->orderBy(['created_date'=>SORT_DESC])->all();
                }
                else
                {
                    $notificationcomment = array();
                    $notificationlikepost = array();
                    $notificationlikecomment = array();
                    $notificationcommentreply = array();
                    $notificationsharepost = array();
                    $notificationsharepostowner = array();
                }

                $notification = array_merge_recursive($notificationpost,$notificationtag,$notificationfriendaccepted,$notificationfrienddenied,$notificationcomment,$notificationlikepost,$notificationlikecomment,$notificationcommentreply,$notificationsharepost,$notificationsharepostowner);

                return count($notification);
            }
            else
            {
                return 0;
            }
        }
        else
        {
            // $notification = Notification::find()->with('user')->with('post')->with('comment')->with('like')->where(['in','user_id',$ids])->andwhere(['not in','user_id',$mute_friend_ids])->orderBy(['created_date'=>SORT_DESC])->all();

            //$notification = Notification::find()->with('user')->with('post')->with('comment')->with('like')->where(['in','user_id',$ids])->andwhere(['not in','user_id',$mute_friend_ids])->andwhere(['created_date'=> ['$gte'=>"$view_noti_time"]])->orderBy(['created_date'=>SORT_DESC])->all();

            $notificationpost = Notification::find()->with('user')->with('post')->with('comment')->with('like')->where(['in','user_id',$ids])->andwhere(['not in','user_id',$mute_friend_ids])->andwhere(['not','notification_type','tag_friend'])->andwhere(['not','notification_type','friendrequestaccepted'])->andwhere(['not','notification_type','friendrequestdenied'])->andwhere(['not','notification_type','comment'])->andwhere(['not','notification_type','likepost'])->andwhere(['not','notification_type','likecomment'])->andwhere(['not','notification_type','commentreply'])->andwhere(['not','notification_type','sharepost'])->andwhere(['is_deleted'=>"0"])->andwhere(['created_date'=> ['$gte'=>"$view_noti_time"]])->orderBy(['created_date'=>SORT_DESC])->all();

            $notificationtag = Notification::find()->with('user')->with('post')->with('comment')->with('like')->where(['notification_type'=>'tag_friend','user_id'=>(string)$uid])->andwhere(['created_date'=> ['$gte'=>"$view_noti_time"]])->andwhere(['is_deleted'=>"0"])->orderBy(['created_date'=>SORT_DESC])->all();

            $notificationfriendaccepted = Notification::find()->with('user')->with('post')->with('comment')->with('like')->where(['notification_type'=>'friendrequestaccepted','from_friend_id'=>(string)$uid])->andwhere(['is_deleted'=>"0"])->andwhere(['created_date'=> ['$gte'=>"$view_noti_time"]])->orderBy(['created_date'=>SORT_DESC])->all();

            $notificationfrienddenied = Notification::find()->with('user')->with('post')->with('comment')->with('like')->where(['notification_type'=>'friendrequestdenied','from_friend_id'=>(string)$uid])->andwhere(['is_deleted'=>"0"])->andwhere(['created_date'=> ['$gte'=>"$view_noti_time"]])->orderBy(['created_date'=>SORT_DESC])->all();

            $notificationcomment = Notification::find()->with('user')->with('post')->with('comment')->with('like')->where(['notification_type'=>'comment','post_owner_id'=>(string)$uid])->andwhere(['is_deleted'=>"0"])->andwhere(['created_date'=> ['$gte'=>"$view_noti_time"]])->orderBy(['created_date'=>SORT_DESC])->all();

            $notificationlikepost = Notification::find()->with('user')->with('post')->with('comment')->with('like')->where(['notification_type'=>'likepost','post_owner_id'=>(string)$uid])->andwhere(['is_deleted'=>"0"])->andwhere(['created_date'=> ['$gte'=>"$view_noti_time"]])->orderBy(['created_date'=>SORT_DESC])->all();

            $notificationlikecomment = Notification::find()->with('user')->with('post')->with('comment')->with('like')->where(['notification_type'=>'likecomment','post_owner_id'=>(string)$uid])->andwhere(['is_deleted'=>"0"])->andwhere(['created_date'=> ['$gte'=>"$view_noti_time"]])->orderBy(['created_date'=>SORT_DESC])->all();

            $notificationcommentreply = Notification::find()->with('user')->with('post')->with('comment')->with('like')->where(['notification_type'=>'commentreply','post_owner_id'=>(string)$uid])->andwhere(['is_deleted'=>"0"])->andwhere(['created_date'=> ['$gte'=>"$view_noti_time"]])->orderBy(['created_date'=>SORT_DESC])->all();

            $notificationsharepost = Notification::find()->with('user')->with('post')->with('comment')->with('like')->where(['notification_type'=>'sharepost','post_owner_id'=>(string)$uid])->andwhere(['is_deleted'=>"0"])->andwhere(['created_date'=> ['$gte'=>"$view_noti_time"]])->orderBy(['created_date'=>SORT_DESC])->all();

            $notificationsharepostowner = Notification::find()->with('user')->with('post')->with('comment')->with('like')->where(['notification_type'=>'sharepost','user_id'=>(string)$uid])->andwhere(['is_deleted'=>"0"])->andwhere(['created_date'=> ['$gte'=>"$view_noti_time"]])->orderBy(['created_date'=>SORT_DESC])->all();

            $notification = array_merge_recursive($notificationpost,$notificationtag,$notificationfriendaccepted,$notificationfrienddenied,$notificationcomment,$notificationlikepost,$notificationlikecomment,$notificationcommentreply,$notificationsharepost,$notificationsharepostowner);

            return count($notification);
        }
     }
    
    
    /**
     * Get all user notification in decsending order
     * @param  nill
     * @return array
     * @Author  Sonali Patel
     * @Date    02-03-2016 (dd-mm-yyyy)
    */ 
    public function getAllNotification()
    {
        $session = Yii::$app->session;
        $uid = $session->get('user_id');
        $friends =  Friend::getuserFriends($uid);
        $ids = array();
        foreach($friends as $friend)
        {
            $ids[]= $friend['from_id'];
        }
        $mute_ids = MuteFriend::getMutefriendsIds($uid);
        $mute_friend_ids =  (explode(",",$mute_ids['mute_ids']));
        
        $notification_settings = NotificationSetting::find()->where(['user_id' => (string)$uid])->one();
        
        if($notification_settings)
        {
            if($notification_settings['friend_activity'] == 'Yes')
            {
                //$notification = Notification::find()->with('user')->with('post')->with('comment')->with('like')->where(['in','user_id',$ids])->andwhere(['not in','user_id',$mute_friend_ids])->orderBy(['created_date'=>SORT_DESC])->all();

                $notificationpost = Notification::find()->with('user')->with('post')->with('comment')->with('like')->where(['in','user_id',$ids])->andwhere(['not in','user_id',$mute_friend_ids])->andwhere(['not','notification_type','tag_friend'])->andwhere(['not','notification_type','friendrequestaccepted'])->andwhere(['not','notification_type','friendrequestdenied'])->andwhere(['not','notification_type','comment'])->andwhere(['not','notification_type','likepost'])->andwhere(['not','notification_type','likecomment'])->andwhere(['not','notification_type','commentreply'])->andwhere(['not','notification_type','sharepost'])->andwhere(['is_deleted'=>"0"])->orderBy(['created_date'=>SORT_DESC])->all();

                $notificationtag = Notification::find()->with('user')->with('post')->with('comment')->with('like')->where(['notification_type'=>'tag_friend','user_id'=>(string)$uid])->andwhere(['is_deleted'=>"0"])->orderBy(['created_date'=>SORT_DESC])->all();

                if($notification_settings['friend_request'] == 'Yes')
                {
                    $notificationfriendaccepted = Notification::find()->with('user')->with('post')->with('comment')->with('like')->where(['notification_type'=>'friendrequestaccepted','from_friend_id'=>(string)$uid])->andwhere(['is_deleted'=>"0"])->orderBy(['created_date'=>SORT_DESC])->all();

                    $notificationfrienddenied = Notification::find()->with('user')->with('post')->with('comment')->with('like')->where(['notification_type'=>'friendrequestdenied','from_friend_id'=>(string)$uid])->andwhere(['is_deleted'=>"0"])->orderBy(['created_date'=>SORT_DESC])->all();
                }
                else
                {
                    $notificationfriendaccepted = array();
                    $notificationfrienddenied = array();
                }
                
                if($notification_settings['friend_activity_on_user_post'] == 'Yes')
                {
                    $notificationcomment = Notification::find()->with('user')->with('post')->with('comment')->with('like')->where(['notification_type'=>'comment','post_owner_id'=>(string)$uid])->andwhere(['is_deleted'=>"0"])->orderBy(['created_date'=>SORT_DESC])->all();

                    $notificationlikepost = Notification::find()->with('user')->with('post')->with('comment')->with('like')->where(['notification_type'=>'likepost','post_owner_id'=>(string)$uid])->andwhere(['is_deleted'=>"0"])->orderBy(['created_date'=>SORT_DESC])->all();

                    $notificationlikecomment = Notification::find()->with('user')->with('post')->with('comment')->with('like')->where(['notification_type'=>'likecomment','post_owner_id'=>(string)$uid])->andwhere(['is_deleted'=>"0"])->orderBy(['created_date'=>SORT_DESC])->all();

                    $notificationcommentreply = Notification::find()->with('user')->with('post')->with('comment')->with('like')->where(['notification_type'=>'commentreply','post_owner_id'=>(string)$uid])->andwhere(['is_deleted'=>"0"])->orderBy(['created_date'=>SORT_DESC])->all();
                    
                    $notificationsharepost = Notification::find()->with('user')->with('post')->with('comment')->with('like')->where(['notification_type'=>'sharepost','post_owner_id'=>(string)$uid])->andwhere(['is_deleted'=>"0"])->orderBy(['created_date'=>SORT_DESC])->all();
                    
                    $notificationsharepostowner = Notification::find()->with('user')->with('post')->with('comment')->with('like')->where(['notification_type'=>'sharepost','user_id'=>(string)$uid])->andwhere(['is_deleted'=>"0"])->orderBy(['created_date'=>SORT_DESC])->all();
                }
                else
                {
                    $notificationcomment = array();
                    $notificationlikepost = array();
                    $notificationlikecomment = array();
                    $notificationcommentreply = array();
                    $notificationsharepost = array();
                    $notificationsharepostowner = array();
                }

                $notification = array_merge_recursive($notificationpost,$notificationtag,$notificationfriendaccepted,$notificationfrienddenied,$notificationcomment,$notificationlikepost,$notificationlikecomment,$notificationcommentreply,$notificationsharepost,$notificationsharepostowner);
                foreach ($notification as $key)
                {
                    $sortkeys[] = $key["created_date"];
                }

                if(count($notification))
                {
                    array_multisort($sortkeys, SORT_DESC, SORT_STRING, $notification);
                }

                return $notification;
            }
            else
            {
                return 0;
            }
        }
        else
        {
            //$notification = Notification::find()->with('user')->with('post')->with('comment')->with('like')->where(['in','user_id',$ids])->andwhere(['not in','user_id',$mute_friend_ids])->orderBy(['created_date'=>SORT_DESC])->all();

            $notificationpost = Notification::find()->with('user')->with('post')->with('comment')->with('like')->where(['in','user_id',$ids])->andwhere(['not in','user_id',$mute_friend_ids])->andwhere(['not','notification_type','tag_friend'])->andwhere(['not','notification_type','friendrequestaccepted'])->andwhere(['not','notification_type','friendrequestdenied'])->andwhere(['not','notification_type','comment'])->andwhere(['not','notification_type','likepost'])->andwhere(['not','notification_type','likecomment'])->andwhere(['not','notification_type','commentreply'])->andwhere(['not','notification_type','sharepost'])->andwhere(['is_deleted'=>"0"])->orderBy(['created_date'=>SORT_DESC])->all();

            $notificationtag = Notification::find()->with('user')->with('post')->with('comment')->with('like')->where(['notification_type'=>'tag_friend','user_id'=>(string)$uid])->andwhere(['is_deleted'=>"0"])->orderBy(['created_date'=>SORT_DESC])->all();

            $notificationfriendaccepted = Notification::find()->with('user')->with('post')->with('comment')->with('like')->where(['notification_type'=>'friendrequestaccepted','from_friend_id'=>(string)$uid])->andwhere(['is_deleted'=>"0"])->orderBy(['created_date'=>SORT_DESC])->all();

            $notificationfrienddenied = Notification::find()->with('user')->with('post')->with('comment')->with('like')->where(['notification_type'=>'friendrequestdenied','from_friend_id'=>(string)$uid])->andwhere(['is_deleted'=>"0"])->orderBy(['created_date'=>SORT_DESC])->all();

            $notificationcomment = Notification::find()->with('user')->with('post')->with('comment')->with('like')->where(['notification_type'=>'comment','post_owner_id'=>(string)$uid])->andwhere(['is_deleted'=>"0"])->orderBy(['created_date'=>SORT_DESC])->all();

            $notificationlikepost = Notification::find()->with('user')->with('post')->with('comment')->with('like')->where(['notification_type'=>'likepost','post_owner_id'=>(string)$uid])->andwhere(['is_deleted'=>"0"])->orderBy(['created_date'=>SORT_DESC])->all();

            $notificationlikecomment = Notification::find()->with('user')->with('post')->with('comment')->with('like')->where(['notification_type'=>'likecomment','post_owner_id'=>(string)$uid])->andwhere(['is_deleted'=>"0"])->orderBy(['created_date'=>SORT_DESC])->all();

            $notificationcommentreply = Notification::find()->with('user')->with('post')->with('comment')->with('like')->where(['notification_type'=>'commentreply','post_owner_id'=>(string)$uid])->andwhere(['is_deleted'=>"0"])->orderBy(['created_date'=>SORT_DESC])->all();
            
            $notificationsharepost = Notification::find()->with('user')->with('post')->with('comment')->with('like')->where(['notification_type'=>'sharepost','post_owner_id'=>(string)$uid])->andwhere(['is_deleted'=>"0"])->orderBy(['created_date'=>SORT_DESC])->all();

            $notificationsharepostowner = Notification::find()->with('user')->with('post')->with('comment')->with('like')->where(['notification_type'=>'sharepost','user_id'=>(string)$uid])->andwhere(['is_deleted'=>"0"])->orderBy(['created_date'=>SORT_DESC])->all();

            $notification = array_merge_recursive($notificationpost,$notificationtag,$notificationfriendaccepted,$notificationfrienddenied,$notificationcomment,$notificationlikepost,$notificationlikecomment,$notificationcommentreply,$notificationsharepost,$notificationsharepostowner);
            foreach ($notification as $key)
            {
                $sortkeys[] = $key["created_date"];
            }

            if(count($notification))
            {
                array_multisort($sortkeys, SORT_DESC, SORT_STRING, $notification);
            }

            return $notification;
        }
    }
    
}