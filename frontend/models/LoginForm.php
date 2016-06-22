<?php 
namespace frontend\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\web\IdentityInterface;
use yii\mongodb\ActiveRecord;
use yii\web\UploadedFile;
use frontend\models\PostForm;


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
class LoginForm extends ActiveRecord implements IdentityInterface
{
 
  //  public $profile_picture;

    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;

    /**
     * @inheritdoc
     */
    public static function collectionName()
    {
        return 'tbl_user';
    }

     public function attributes()
    {

        return ['_id', 'fb_id', 'username', 'fname','lname','fullname', 'password', 'con_password', 'pwd_changed_date', 'email','alternate_email','photo','thumbnail','cover_photo', 'birth_date','gender','created_date','updated_date','created_at','updated_at','status','phone','isd_code','country','city','captcha','member_type','last_login_time','forgotcode','forgotpassstatus','lat','long','login_from_ip'];

    }
    
    public function getPosts()
    {
        return $this->hasMany(PostForm::className(), ['post_user_id' => '_id']);
    }
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
             // name, email, subject and body are required
                [['email', 'password'], 'required', 'on' => 'login'],
                // email has to be a valid email address
             //   ['email', 'email','except' => 'profile_picture'],
                //['phone', 'unique'],
                 [['fname', 'lname','fullname', 'email', 'password', 'con_password', 'birth_date', 'gender','created_date','updated_date','status'], 'required', 'on' => 'signup'],
                [['con_password'], 'compare', 'compareAttribute' => 'password', 'on' => 'signup'],
                [['email'], 'required', 'on' => 'forgot'],
                [['fname', 'lname', 'email', 'password', 'birth_date', 'gender','phone','country','city','captcha'], 'required', 'on' => 'profile'],
                //['phone', 'unique', 'message' => 'This Phone No Has already been taken.','on' => 'profile'],
           
                [['photo'], 'image', 'extensions' => ['png', 'jpg', 'gif','jpeg'], 'on' => 'profile_picture'],
              
                 
                // verifyCode needs to be entered correctly
                ['captcha', 'captcha', 'on' => 'profile'],
        ];



    }
    
    public function scenarios()
    {
        $scenarios = parent::scenarios();     
        return $scenarios;
    }
    
    public function login()
    {
        $email = $_POST['LoginForm']['email'];
        $password = $_POST['LoginForm']['password'];    
     
        $login = LoginForm::find()->where(['email' => $email,'password' => $password])->orwhere(['phone'=> $email,'password' => $password])->one();
    
        $count = count($login);
        if($count == '1'){
        $id = $login['_id'];
        $session = Yii::$app->session; 
        
        $email = $login['email'];
        $session->set('email',$email); 
            if($login->status == '1' || $login->status == '0')
                {
                    
                   // return $login; 
                    return 1;
                }
                else{
                    if($login->phone)
                    {
                        if($login->photo){
                            
                            $about = Personalinfo::getLastInsertedRecord($id);
                            $about_us = $about['about'];
                                                   
                            if(!empty($about_us)){
                               // Go To Step 5 Only Verification Requires
                                //echo "5";
                                return 5;
                            }
                            else{
                                   //Go To Step 4 Personal Info Requires
                                //echo "4";
                                return 4;
                            }
                            
                        }
                        else{
                            // Go To Step 3 Profile_Picure Not Found
                            
                            //echo "3";
                            return 3;
                        }
                     
                    }    
                    else{
                        //Go To Step 2 Profile is Not Set
                       // echo "2";
                        return 2;
                    }
                    
                }
        }
        else{
            //email id not exist
            return 6;
        }
      
             
          //  return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        
    }
        public function generateUsername($fname,$lname)
    {
        $ans = strtolower(substr($fname, 0, 4)).strtolower($lname);
	
	$rand = rand(0,9999);
	
	$ans = $ans . $rand;
	
	return $ans;
    }
    public function signup()
    {
        $email = $_POST['LoginForm']['email'];
        
        $signup = LoginForm::find()->where(['email' => $email])->one();

       return $signup;
         
    }
    public function signup2()
    {
        $fname = $_POST['LoginForm']['fname'];
        $lname = $_POST['LoginForm']['lname'];
        $email = $_POST['LoginForm']['email'];
      
        $password = $_POST['LoginForm']['password'];
        $phone = $_POST['LoginForm']['phone'];
        $birth_date = $_POST['LoginForm']['birth_date'];
        $gender = $_POST['LoginForm']['gender'];
        $country = $_POST['LoginForm']['country'];
        $city = $_POST['LoginForm']['city'];
        $isd_code = $_POST['isd_code'];
        
        
       $s2 = LoginForm::find()->where(['email' => $email])->one();
             
            if(!empty($s2))
            {
             $s2->fname = $fname;
             $s2->lname = $lname;
             $s2->email = $email;
             $s2->phone = $phone;
             $s2->isd_code = $isd_code;
             $s2->password = $password;
             $s2->con_password = $password;
             $s2->gender = $gender;
             $s2->birth_date = $birth_date;
             $s2->country = $country;
             $s2->city = $city;
             $s2->update();
             
             return $s2;
            } 

      return false;
         
    }
    
     public function signup3()
    {
        $session = Yii::$app->session;
        $email = $session->get('email_id'); 
        $profile_picture = $_FILES['LoginForm']['name']['photo'];
        
        $chars = '0123456789';
        $count = mb_strlen($chars);

        for ($i = 0, $rand = ''; $i < 8; $i++) {
        $index = rand(0, $count - 1);
        $rand .= mb_substr($chars, $index, 1);
        }
        
        $profile_picture = $rand . $profile_picture;
        $s3 = LoginForm::find()->where(['email' => $email])->one();
             
        if(!empty($s3))
        {
            $this->photo = UploadedFile::getInstance($this, 'photo');
            $this->photo->saveAs('profile/'.$profile_picture);
            //$this->photo->saveAs('uploads/'.$profile_picture);
            
            $data = str_replace('data:image/png;base64,', '', $_POST['imagevalue']);
            $data = str_replace(' ', '+', $data);
            $data = base64_decode($data);
            $file = 'profile/thumb_'.$profile_picture;
            $success = file_put_contents($file, $data);
            
            $s3->thumbnail = 'thumb_'.$profile_picture;
            $s3->photo = $profile_picture;

            $s3->update();
         //echo 'dddd'.(string)$s3['_id'];
        // if($s3->id != '')
        {
            //insert profile photo as user post
            $date = time();
            $post = new PostForm();
            $post->post_status = '1';
            $post->post_type = 'profilepic';
            $post->image = 'thumb_'.$profile_picture;
            $post->post_created_date = $date;
            $post->post_user_id = (string)$s3['_id'];
            $post->insert();
        }
      //  exit;
         return $s3;
        
        } 

      return false;
         
    }
    
     public function signup4()
    {
        $session = Yii::$app->session;
        $email = $session->get('email_id'); 
        
       $abc = array();
       
       $abc = $_POST['LoginForm']['host_services'];
       
       
       $host_services = implode(",", $abc);
     
        $about = $_POST['LoginForm']['about'];
        $education = $_POST['LoginForm']['education'];
        $occupation = $_POST['LoginForm']['occupation'];
        $interest = $_POST['LoginForm']['interest'];
        $first_lang = $_POST['LoginForm']['first_lang'];
        $other_lang = $_POST['LoginForm']['other_lang'];
        $host = $_POST['LoginForm']['host']; 
        $host_gender = $_POST['LoginForm']['host_gender'];
   
        $s4 = LoginForm::find()->where(['email' => $email])->one();
             
        if(!empty($s4))
        {
       
        $s4->about = $about;
        $s4->education = $education;
        $s4->occupation = $occupation;
        $s4->interest = $interest;
        $s4->first_lang = $first_lang;
        $s4->other_lang = $other_lang;
        $s4->host = $host;
        $s4->host_services = $host_services;
        $s4->host_gender = $host_gender;
        $s4->update();

         return $s4;
        
        } 

      return false;
         
    }
    
    
       public function forgot()
        {

            $email = $_POST['fmail'];
   
            $chars = '0123456789';
            $count = mb_strlen($chars);

            for ($i = 0, $rand = ''; $i < 8; $i++) {
            $index = rand(0, $count - 1);
            $rand .= mb_substr($chars, $index, 1);
            }
            
            //$rand = rand(0,9999999);
            
             $forgot = LoginForm::find()->where(['email' => $email])->one();
             
            if(!empty($forgot))
            {
             $forgot->password = $rand;
             $forgot->con_password = $rand;
             $forgot->update();
        
                 return $forgot;
            } 
                 
        }
    
        public function forgotpass()
        {

            $email = $_POST['fhmail'];
          
            $rand = rand(100000,999999); 
            
            $forgot = LoginForm::find()->where(['email' => $email])->one();
             
            if(!empty($forgot))
            {
                $forgot->forgotcode = $rand;
                $forgot->forgotpassstatus = 0;
                $forgot->update();
        
                return $forgot;
            }
            return false;
                 
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
     public function getLastInsertedRecord($email)
    {
        return LoginForm::find()->select(['_id'])->where(['email' => $email])->one();
      
    }
}
