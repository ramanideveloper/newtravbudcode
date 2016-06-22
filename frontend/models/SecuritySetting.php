<?php 
namespace frontend\models;

use Yii;
use yii\base\NotSupportedException;

use yii\web\IdentityInterface;
use yii\mongodb\ActiveRecord;
use yii\web\UploadedFile;

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
class SecuritySetting extends ActiveRecord implements IdentityInterface
{
 
  //  public $profile_picture;

    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;

    /**
     * @inheritdoc
     */
    public static function collectionName()
    {
        return 'security_setting';
    }

     public function attributes()
    {
        return ['_id','user_id','security_questions','answer','gf_ans','born_ans','eml_ans','device_type','browser_type','my_view_status','my_post_view_status',
                'restricted_list','blocked_list','block_event_invites','pair_social_actions','contact_me',
                'message_filtering','friend_request','bothering_me','dashboard_view_status','add_public_wall',
                'see_public_wall','review_posts','view_posts_tagged_in','view_others_posts_on_mywall','review_tags',
                'recent_activities','friend_list','view_photos','created_date','modified_date','ip','created_by','modified_by','is_deleted'];
    }
    
    public function getPosts()
    {
        return $this->hasMany(PostForm::className(), ['post_user_id' => '_id']);
    }
    /**
     * @inheritdoc
     */
    

    /**
     * @inheritdoc
     */
    /*
    public function rules()
    {
        return [
                [['security_questions'], 'required'],
                  
             
        ];



    }*/
    
    public function scenarios()
    {
        $scenarios = parent::scenarios();     
        return $scenarios;
    }
   
    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findByUsername($this->username);
        }

        return $this->_user;
    }
    
    public function security()
    {
        $session = Yii::$app->session;
        $email = $session->get('email'); 
        $user_id = (string) $session->get('user_id');
        
        $data = array();
        

         $security = SecuritySetting::find()->where(['user_id' => $user_id])->one();


          if(!empty($security)){

              
         $security->security_questions = $_POST['security_questions'];
         
         if($_POST['security_questions'] == 'What is your email on file?'){
             
             $security->eml_ans = $_POST['SecuritySetting']['answer'];
         }
         else if($_POST['security_questions'] == 'What city you born?'){
             
             $security->born_ans = $_POST['SecuritySetting']['answer'];
         }
         else if($_POST['security_questions'] == "What is your girl friend name?"){
             $security->gf_ans = $_POST['SecuritySetting']['answer'];
         }
         /*else {
             $security->answer = $_POST['SecuritySetting']['answer'];
         }*/
         
         
         $security->my_view_status = $_POST['my_view_status'];
         $security->my_post_view_status = $_POST['my_post_view_status'];
       
         $security->contact_me = $_POST['contact_me'];
         $security->friend_request = $_POST['friend_request'];
         //$security->dashboard_view_status = $_POST['dashboard_view_status'];
         $security->add_public_wall = $_POST['add_public_wall'];
         $security->view_photos = $_POST['view_photos'];
         //$security->see_public_wall = $_POST['see_public_wall'];
         $security->review_posts = $_POST['review_posts'];
         $security->view_posts_tagged_in = $_POST['view_posts_tagged_in'];
         $security->view_others_posts_on_mywall = $_POST['view_others_posts_on_mywall'];
         $security->review_tags = $_POST['review_tags'];
         $security->recent_activities = $_POST['recent_activities'];
         $security->friend_list = $_POST['friend_list'];
         
         $security->update();

             return 1;

          }
          else{
             $security = new SecuritySetting();  
             $security->user_id = $user_id;
             $security->security_questions = $_POST['security_questions'];
             
             $security->security_questions = $_POST['security_questions'];
         
            if($_POST['security_questions'] == 'What is your email on file?'){

                $security->eml_ans = $_POST['SecuritySetting']['answer'];
            }
            else if($_POST['security_questions'] == 'What city you born?'){

                $security->born_ans = $_POST['SecuritySetting']['answer'];
            }
            else if($_POST['security_questions'] == "What is your girl friend name?"){
             $security->gf_ans = $_POST['SecuritySetting']['answer'];
            }
            /*
            else {
                $security->gf_ans = $_POST['SecuritySetting']['gf_ans'];
            }*/
             
            // $security->answer = $_POST['SecuritySetting']['answer'];
             $security->my_view_status = $_POST['my_view_status'];
             $security->my_post_view_status = $_POST['my_post_view_status'];
           
             $security->contact_me = $_POST['contact_me'];
             $security->friend_request = $_POST['friend_request'];
             //$security->dashboard_view_status = $_POST['dashboard_view_status'];
             $security->add_public_wall = $_POST['add_public_wall'];
             $security->view_photos = $_POST['view_photos'];
             //$security->see_public_wall = $_POST['see_public_wall'];
             $security->review_posts = $_POST['review_posts'];
             $security->view_posts_tagged_in = $_POST['view_posts_tagged_in'];
             $security->view_others_posts_on_mywall = $_POST['view_others_posts_on_mywall'];
             $security->review_tags = $_POST['review_tags'];
             $security->recent_activities = $_POST['recent_activities'];
             $security->friend_list = $_POST['friend_list'];

             $security->insert();

             return 2;

          }
    }
    
    
    public function security2()
    {

        $session = Yii::$app->session;
        $email = $_GET['email']; 
        $user = LoginForm::find()->where(['email' => $email])->one();
     
        $user_id = (string)$user->_id;

        $security2 = SecuritySetting::find()->where(['user_id' => $user_id])->one();

          if(!empty($security2)){
      
         $security2->my_view_status = 'Private';
         $security2->my_post_view_status = 'Public';      
         $security2->contact_me = 'Private';
         $security2->friend_request = 'Friends of Friends';
         $security2->view_photos = 'Friends';
         $security2->dashboard_view_status = 'Friends';
         $security2->add_public_wall = 'Private';
         $security2->see_public_wall = 'Private';
         $security2->review_posts = 'Disabled';
         $security2->view_posts_tagged_in = 'Public';
         $security2->view_others_posts_on_mywall = 'Public';
         $security2->review_tags = 'Yes';
         $security2->recent_activities = 'Private';
         $security2->friend_list = 'Friends';
         
         $security2->update();

          }
          else{
            
             $security3 = new SecuritySetting(); 
             $security3->user_id = $user_id;
             $security3->my_view_status = 'Private';
             $security3->my_post_view_status = 'Public';      
             $security3->contact_me = 'Private';
             $security3->view_photos = 'Friends';
             $security3->friend_request = 'Friends of Friends';
             $security3->dashboard_view_status = 'Friends';
             $security3->add_public_wall = 'Private';
             $security3->see_public_wall = 'Private';
             $security3->review_posts = 'Disabled';
             $security3->view_posts_tagged_in = 'Public';
             $security3->view_others_posts_on_mywall = 'Public';
             $security3->review_tags = 'Yes';
             $security3->recent_activities = 'Private';
             $security3->friend_list = 'Public';

             $security3->insert();  

          }
          return 1;
    }
    
    
    public function blocking()
    {   
     
        $session = Yii::$app->session;
        $email = $session->get('email'); 
        $user_id = (string) $session->get('user_id');
        
        if(isset($_POST['SecuritySetting']['restricted_list']) && !empty($_POST['SecuritySetting']['restricted_list'])){
            $abc = $_POST['SecuritySetting']['restricted_list'];
            $restricted_list = implode(",", $abc);
        }
        else{
            $restricted_list = '';
        }
        if(isset($_POST['SecuritySetting']['blocked_list']) && !empty($_POST['SecuritySetting']['blocked_list'])){
            $xyz = $_POST['SecuritySetting']['blocked_list'];
            $blocked_list = implode(",", $xyz);
        }
        else{
             $blocked_list = '';
        }
        if(isset($_POST['SecuritySetting']['block_event_invites']) && !empty($_POST['SecuritySetting']['block_event_invites'])){
           $pqr = $_POST['SecuritySetting']['block_event_invites'];
            $block_event_invites = implode(",", $pqr);
        }
        else{
            $block_event_invites = '';
        }
         if(isset($_POST['SecuritySetting']['message_filtering']) && !empty($_POST['SecuritySetting']['message_filtering'])){
            $def = $_POST['SecuritySetting']['message_filtering'];
            $message_filtering = implode(",", $def);
        }
        else{
            $message_filtering = '';
        }
        
       $data = array();

         $security = SecuritySetting::find()->where(['user_id' => $user_id])->one();
      
          if(!empty($security)){

         $security->restricted_list = $restricted_list; 
         $security->blocked_list = $blocked_list;
         $security->block_event_invites = $block_event_invites;
         $security->message_filtering = $message_filtering;
         //$security->bothering_me = $_POST['bothering_me'];
         $security->update();

             return 1;

          }
          else{
              
              
             $security = new SecuritySetting();  
             $security->user_id = $user_id;
             $security->restricted_list = $restricted_list;
             $security->blocked_list = $blocked_list;
             $security->block_event_invites = $block_event_invites;
             $security->message_filtering = $message_filtering;
             //$security->bothering_me = $_POST['bothering_me'];   
             
            $security->insert();
            
             return 2;
            
             

          }
    }
    

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
       // return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
        return static::findOne(['email' => $id]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
    
    public function getLastInsertedRecord($id)
    {
        return Personalinfo::find()->select(['about'])->where(['user_id' => $id])->one();
    }
}
