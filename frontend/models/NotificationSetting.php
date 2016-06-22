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
class NotificationSetting extends ActiveRecord implements IdentityInterface
{
 
  //  public $profile_picture;

    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;

    /**
     * @inheritdoc
     */
    public static function collectionName()
    {
        return 'notification_setting';
    }

     public function attributes()
    {
        return ['_id','user_id','friend_activity','email_on_account_issues','member_activity',
                'friend_activity_on_user_post','group_activity','non_friend_activity','friend_request',
                'e_card','member_invite_on_meeting','question_activity','credit_activity',
                'sound_on_notification','sound_on_message',
                'created_date','modified_date','ip','created_by','modified_by','is_deleted'];
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
    public function rules()
    {
        return [
                [['friend_activity'], 'required'],
                  
             
        ];



    }
    
    public function scenarios()
    {
        $scenarios = parent::scenarios();     
        return $scenarios;
    }
    
    public function notification()
    {
        $session = Yii::$app->session;
           $email = $session->get('email'); 
           $user_id = (string) $session->get('user_id');
         
               
            $notification = NotificationSetting::find()->where(['user_id' => $user_id])->one();

             if(!empty($notification)){
                 
         
                  
                $notification->friend_activity = $_POST['friend_activity'];
                $notification->email_on_account_issues = $_POST['email_on_account_issues'];
                $notification->member_activity = $_POST['member_activity'];
                $notification->friend_activity_on_user_post = $_POST['friend_activity_on_user_post'];
                $notification->group_activity = $_POST['group_activity'];
                $notification->non_friend_activity = $_POST['non_friend_activity'];
                $notification->friend_request = $_POST['friend_request'];
                $notification->e_card = $_POST['e_card'];
                $notification->member_invite_on_meeting = $_POST['member_invite_on_meeting'];
                $notification->question_activity = $_POST['question_activity'];
                $notification->credit_activity = $_POST['credit_activity'];
                $notification->sound_on_notification = $_POST['sound_on_notification'];
                $notification->sound_on_message = $_POST['sound_on_message'];
               
                $notification->update();
                
                return 1;
            
             }
             else{
                $notification = new NotificationSetting();                  
                $notification->user_id = $user_id;  
                $notification->friend_activity = $_POST['friend_activity'];
                $notification->email_on_account_issues = $_POST['email_on_account_issues'];
                $notification->member_activity = $_POST['member_activity'];
                $notification->friend_activity_on_user_post = $_POST['friend_activity_on_user_post'];
                $notification->group_activity = $_POST['group_activity'];
                $notification->non_friend_activity = $_POST['non_friend_activity'];
                $notification->friend_request = $_POST['friend_request'];
                $notification->e_card = $_POST['e_card'];
                $notification->member_invite_on_meeting = $_POST['member_invite_on_meeting'];
                $notification->question_activity = $_POST['question_activity'];
                $notification->credit_activity = $_POST['credit_activity'];
                $notification->sound_on_notification = $_POST['sound_on_notification'];
                $notification->sound_on_message = $_POST['sound_on_message'];
                
                $notification->insert();
                return 2;
               
             }
    }
    
    
     public function notification2()
    {
        $session = Yii::$app->session;
        $email = $_GET['email'];
        
        $user = LoginForm::find()->where(['email' => $email])->one();
        
        $user_id = (string)$user->_id;
        
        $notification = NotificationSetting::find()->where(['user_id' => $user_id])->one();
        
       if(!empty($notification)){

           $notification->friend_activity = 'Yes';
           $notification->email_on_account_issues = 'Yes';
           $notification->member_activity = 'Yes';
           $notification->friend_activity_on_user_post = 'Yes';
           $notification->group_activity = 'Yes';
           $notification->non_friend_activity = 'No';
           $notification->friend_request = 'Yes';
           $notification->e_card = 'Yes';
           $notification->member_invite_on_meeting = 'Yes';
           $notification->question_activity = 'Yes';
           $notification->credit_activity = 'Yes';
           $notification->sound_on_notification = 'Yes';
           $notification->sound_on_message = 'Yes';

           $notification->update();

          
       }
       else{
           $notification = new NotificationSetting();  
           $notification->user_id = $user_id;
           $notification->friend_activity = 'Yes';
           $notification->email_on_account_issues = 'Yes';
           $notification->member_activity = 'Yes';
           $notification->friend_activity_on_user_post = 'Yes';
           $notification->group_activity = 'Yes';
           $notification->non_friend_activity = 'No';
           $notification->friend_request = 'Yes';
           $notification->e_card = 'Yes';
           $notification->member_invite_on_meeting = 'Yes';
           $notification->question_activity = 'Yes';
           $notification->credit_activity = 'Yes';
           $notification->sound_on_notification = 'Yes';
           $notification->sound_on_message = 'No';

            $notification->insert();
           
           
       }
          return 1; 
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
        return NotificationSetting::find()->select(['about'])->where(['user_id' => $id])->one();
    }
}
