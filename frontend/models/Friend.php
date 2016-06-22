<?php 
namespace frontend\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\web\IdentityInterface;
use yii\mongodb\ActiveRecord;
use yii\db\ActiveQuery;
use yii\mongodb\Query;
use yii\web\UploadedFile;
use frontend\models\LoginForm;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
//use yii\db\Expression;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class Friend extends ActiveRecord 
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;

    /**
     * @inheritdoc
     */
    public static function collectionName()
    {
        return 'friend';
    }

     public function attributes()
    {
        return ['_id', 'from_id', 'to_id', 'status', 'action_user_id','created_date','updated_date'];
    }
    
    public function getUserdata()
    {
        return $this->hasOne(UserForm::className(), ['_id' => 'from_id']);
    }
       
    public function scenarios()
    {
        $scenarios = parent::scenarios();     
        return $scenarios;
    }
   
    
     /**
     * Displays users pending requests.
     *
     * @return mixed
     */
   
    public function getuserFriends($user_id)
     {
            $user_friends =  Friend::find()->with('userdata')->Where(['status'=>'1'])->andWhere(['to_id'=> "$user_id"])->all();
            return $user_friends;
    
     }
     
     /**
     * Displays users pending requests.
     *
     * @return mixed
     */
   
    public function friendPendingRequests()
     {
            $session = Yii::$app->session;
            $uid = $session->get('user_id');
            $pending_request =  Friend::find()->with('userdata')->Where(['not','action_user_id', "$uid"])->andwhere(['status'=>'0'])->andwhere(['to_id'=>"$uid"])->andWhere(['not','from_id', "$uid"])->all();
          return $pending_request;
         
 
     }
     
     /**
     * Displays users pending requests count.
     *
     * @return mixed
     */
   
    public function friendRequestbadge()
     {
            $session = Yii::$app->session;
            $uid = $session->get('user_id');
            $array = [$uid];  
            $result_requests = Friend::find()->Where(['not','action_user_id', "$uid"])->andwhere(['status'=>'0'])->andwhere(['to_id'=>"$uid"])->andWhere(['not','from_id', "$uid"])->all();
         
          $count = count($result_requests);
        
          return $count;
       
          
     }
     
     
      /**
     * Get all user posts Likes in decsending order
     * @param  post id 
     * @return array
     * @Author  Sonali Patel
     * @Date    02-04-2016 (dd-mm-yyyy)
     */  
     public function userlistFirstfive()
     {

            $session = Yii::$app->session;
            $uid = $session->get('user_id');
            $array = [$uid];
            $exist_ids = $pending_ids = array();   
            $requestexists =  Friend::find()->where(['from_id'=>"$uid"])->all();
            if(!empty($requestexists) && count($requestexists)>0)
            {
                foreach($requestexists AS $requestexist)
                {
                    $exist_ids[] = $requestexist['from_id'];
                }
            }
            //
            $requestpendings =  Friend::find()->where(['to_id'=>"$uid"])->all();
             if(!empty($requestpendings) && count($requestpendings)>0)
            {
                foreach($requestpendings AS $requestpending)
                {
                    $pending_ids[] = $requestpending['from_id'];
                }
            }
          
            
            $array_final = array_unique (array_merge ($exist_ids, $pending_ids,$array));
           // print_r($array_final);exit;
            $result_friends = LoginForm::find()->where(['not in','_id',$array_final])->andwhere(['status'=>'1'])->orderBy(['ontop' => SORT_DESC,
        'rand()' => SORT_DESC,])->limit(5)->all();
            $count = count($result_friends);
            return $result_friends;
        
     }
    /**
     * Get all user posts Likes in decsending order
     * @param  post id 
     * @return array
     * @Author  Sonali Patel
     * @Date    02-04-2016 (dd-mm-yyyy)
     */  
     public function userlist()
     {

            $session = Yii::$app->session;
            $uid = $session->get('user_id');
            $array = [$uid];
            
            $result_friends = LoginForm::find()->where(['not in','_id',$array])->andwhere(['status'=>'1'])->orderBy(['_id'=>SORT_DESC])->all();
        
            return $result_friends;
        
     }
     
     public function searchfriend()
     {

            $session = Yii::$app->session;
            $email = $session->get('name');
            
            
          //  $array = [$email];
            
            $result_friends = LoginForm::find()->where(['like','email',$email])
                    ->orwhere(['like','fname',$email])
                    ->orwhere(['like','lname',$email])
                    ->orwhere(['like','photo',$email])
                    ->orwhere(['like','phone',$email])
                    ->orwhere(['like','fullname',$email])
                    ->andwhere(['status'=>'1'])->orderBy(['_id'=>SORT_DESC])->all();
    
        
            return $result_friends;
        
     }
     
     /**
     * Get all users for share post
     * @param  post id 
     * @return array
     * @Author  Alap Shah
     * @Date    28-04-2016 (dd-mm-yyyy)
     */
     function userlistsuggetions($sug)
     {
        if (\Yii::$app->user->isGuest)
        {
            return $this->goHome();
        }
        else
        {
            return LoginForm::find()->where(['like','username',$sug,false])->andwhere(['status'=>'1'])->orderBy(['_id'=>SORT_DESC])->limit(10)->all();
        }
     }
     
     /**
     * Displays count of mutual friends.
     *
     * @return mixed
     */
     public function mutualfriendcount($id)
     {
        $session = Yii::$app->session;
        $uid = $session->get('user_id');
        
        //Find friends of both the user 
        $friends_of_a = Friend::find()->where(['from_id'=>"$uid",'status'=>'1'])->all();
        $friends_of_b  = Friend::find()->where(['from_id'=>"$id",'status'=>'1'])->all();
       
        //Create array for find intersection
        $arr_a = $arr_b = array();
        foreach($friends_of_a as $t)
        {
            $arr_a[$t->to_id] = $t->attributes;
        }

        foreach($friends_of_b as $t1)
        {
            $arr_b[$t1->to_id] = $t1->attributes;

        }
        //Return same/mutual friends
        $result_mutual = array_intersect_key($arr_a,$arr_b);
        $count = count($result_mutual);
        return $count;
     }
     
     /**
     * Displays count of request exists.
     *
     * @return mixed
     */
     public function requestexists($id)
     {
        $session = Yii::$app->session;
        $uid = $session->get('user_id');
        
        $result_exists = Friend::find()->where(['from_id'=>"$id",'to_id'=>"$uid"])->all();
        $count = count($result_exists);
        return $count;
     }
      /**
     * Displays count of already request exists.
     *
     * @return mixed
     */
     public function requestalreadysend($id)
     {
        $session = Yii::$app->session;
        $uid = $session->get('user_id');
  
        $result_exists = Friend::find()->where(['from_id'=>"$uid",'to_id'=>"$id"])->all();
        $count = count($result_exists);

        return $count;
     }
      /**
     * Displays users pending requests.
     *
     * @return mixed
     */
   
    public function getFriendsCity($uid)
     {
        //$session = Yii::$app->session;
        //$uid = $session->get('user_id');
        $user_friends =  Friend::find()->with('userdata')->Where(['status'=>'1'])->andWhere(['to_id'=> "$uid"])->all();
        $cities = '';
        foreach($user_friends AS $user_friend)
        {
            $city = Personalinfo::getCity($user_friend['userdata']['_id']);
           // echo '<pre>';print_r($city['current_city']);
           $cities .=  "'".$city['current_city']."',";
        }
      //  exit;
        return $cities;
    
     }
   
   /**
     * Displays users pending requests.
     *
     * @return mixed
     */
   
    public function getFriendsNames($uid)
     {
        //$session = Yii::$app->session;
        //$uid = $session->get('user_id');
        $user_friends =  Friend::find()->with('userdata')->Where(['status'=>'1'])->andWhere(['to_id'=> "$uid"])->all();
        $names = '';//echo '<pre>';print_r($user_friends);
        foreach($user_friends AS $user_friend)
        {
           // $city = Personalinfo::getCity($user_friend['userdata']['_id']);
            
           // echo '<pre>';print_r($city['current_city']);
           $names .=  "'".$user_friend['userdata']['fname'].' '.$user_friend['userdata']['lname']."',";
        }
        // exit;
        return $names;
    
     }
     /**
     * Displays users pending requests.
     *
     * @return mixed
     */
   
    public function getFriendsImages($uid,$from)
     {
        $user_friends =  Friend::find()->with('userdata')->Where(['status'=>'1'])->andWhere(['to_id'=> "$uid"])->all();
        $images = '';
        foreach($user_friends AS $user_friend)
        {
            if($from == 'wallajax')
            {
                $mapdp = $this->getimage($user_friend['userdata']['_id'],'thumb');
            }
            else
            {
                $mapdp = $this->context->getimage($user_friend['userdata']['_id'],'thumb');
            }
           $images .=  "'".$mapdp."',";
        }
        // exit;
        return $images;
    
     }
    /**
     * check if two users are friends or tobe freinds
     * @param  first user id ,second user id 
     * @return array
     * @Author  Alap Shah
     * @Date    28-04-2016 (dd-mm-yyyy)
     */
     function friends_or_tobe_friends($to_id,$from_id)
     {
        $is_friends =  Friend::find()->with('userdata')->Where(['from_id'=>"$from_id",'to_id'=> "$to_id"])->orWhere(['from_id'=> "$to_id",'to_id'=>"$from_id"])->all();
        
        if(count($is_friends)>0)
            return true;
        else
            return false;
     }
}
