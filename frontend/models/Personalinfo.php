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
class Personalinfo extends ActiveRecord implements IdentityInterface
{
 
  //  public $profile_picture;

    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;

    /**
     * @inheritdoc
     */
    public static function collectionName()
    {
        return 'personal_info';
    }

     public function attributes()
    {
        return ['_id','personalinfo_id', 'user_id','about','education','occupation','interests','language',
                'is_host','host_services','gender','religion','political_view','current_city','mission','hometown','created_date','modified_date',
                'ip','created_by','modified_by','is_deleted'];
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
                //[['about','education','occupation','interests','language','is_host','host_services','gender'], 'required', 'on' => 'personal_info'],
                [['about'], 'required', 'on' => 'personal_info'],
                 [['current_city','hometown','religion','political_view'], 'required', 'on' => 'basicinfo'],  
             
        ];



    }
    
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
    
   /**
     * Get  user city 
     * @param  user id 
     * @return city name
     * @Author  Sonali Patel
     * @Date    07-04-2016 (dd-mm-yyyy)
     */  
    public function getCity($friend_user_id)
    {
      //  echo $friend_user_id;
        return Personalinfo::find()->select(['current_city'])->where(['user_id' => "$friend_user_id"])->one();
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
    
     /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUserid($userid)
    {   //echo 'dcff'.$userid;
        $d = Personalinfo::find()->where(['user_id' => $userid])->one();
       // print_r($d);die;
    }
    
    
     /**
     * Finds user by user id
     *
     * @param string $userid
     * @return user personal data
     */
    public function getPersonalInfo($userid)
    {   
        $d = Personalinfo::find()->where(['user_id' => $userid])->one();
        return $d;
    }
    
}