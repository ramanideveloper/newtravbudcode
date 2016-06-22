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
class BookmarkForm extends ActiveRecord implements IdentityInterface
{
 
  //  public $profile_picture;

    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;

    /**
     * @inheritdoc
     */
    public static function collectionName()
    {
        return 'tbl_bookmark';
    }

     public function attributes()
    {
        return ['_id', 'user_id', 'blabel', 'blink', 'created_date', 'modified_date', 'is_deleted', 'created_by'];
    }
    
    public function getPosts()
    {
        return $this->hasMany(PostForm::className(), ['post_user_id' => '_id']);
    }
    /**
     * @inheritdoc
     */
    /*
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }*/

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
             // name, email, subject and body are required
                [['blabel', 'blink'], 'required'],
        ];
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
    
    function editbookmark()
    {
        if(isset($_POST['editid']) && !empty($_POST['editid'])) {
            $data = array();

            $session = Yii::$app->session;
            $email = $session->get('email_id');
            $s3 = LoginForm::find()->where(['email' => $email])->one();
            $user_id = (string)$s3['_id'];
            $fetrec = BookmarkForm::find()->where(['_id' => $_POST['editid'],'user_id' => $user_id])->one();
            if($fetrec)
            {
                $bdate = time();
                $editbook = new BookmarkForm();
                $editbook = BookmarkForm::find()->where(['_id' => $_POST['editid'],'user_id' => $user_id])->one();
                $editbook->blabel = $_POST['blabel'];
                $editbook->blink = $_POST['blink'];
                $editbook->modified_date = $bdate;
                if($editbook->update()){
                    print true;
                }
                else
                {
                    print false;
                }
            }
            else
            {
                print false;
            }
        }
    }
    
    function delbookmark()
    {
        if(isset($_POST['delid']) && !empty($_POST['delid'])) {
            $data = array();

            $session = Yii::$app->session;
            $email = $session->get('email_id');
            $s3 = LoginForm::find()->where(['email' => $email])->one();
            $user_id = (string)$s3['_id'];
            $fetrec = BookmarkForm::find()->where(['_id' => $_POST['delid'],'user_id' => $user_id])->one();
            if($fetrec)
            {
                $delbook = new BookmarkForm();
                $delbook = BookmarkForm::find()->where(['_id' => $_POST['delid'],'user_id' => $user_id])->one();
                $delbook->is_deleted = '1';
                if($delbook->update()){
                    print true;
                }
                else
                {
                    print false;
                }
            }
            else
            {
                print false;
            }
        }
    }
    
    function setLinks()
    {
        $ser = 'http://'.$_SERVER['SERVER_NAME'];
        $mylink = $ser.'/frontend/web/index.php?r=site%2Findex2';
        $basciinfo = $ser.'/frontend/web/index.php?r=site%2Fbasicinfo';
        $addpics = $ser.'/frontend/web/index.php?r=site%2Fprofile-picture';
        $security = $ser.'/frontend/web/index.php?r=site%2Fsecurity-setting';
        $notifications = $ser.'/frontend/web/index.php?r=site%2Fnotification-setting';
        $blabels = array('My Profile','Basic Information','Profile Photo','Security','Notifications'); 
        $blinks = array($mylink,$basciinfo,$addpics,$security,$notifications);
        $btotal = sizeof($blabels);
        $session = Yii::$app->session;
        $email = $session->get('email_id');
        $s3 = LoginForm::find()->where(['email' => $email])->one();
        for($i=0;$i<$btotal;$i++)
        {
            $bdate = time();
            $addbook = new BookmarkForm();
            $addbook->user_id = (string)$s3['_id'];
            $addbook->blabel = $blabels[$i];
            $addbook->blink = $blinks[$i];
            $addbook->created_date = $bdate;
            $addbook->modified_date = $bdate;
            $addbook->is_deleted = '0';
            $addbook->created_by = (string)$s3['_id'];
            $addbook->insert();
        }
    }
    
    function addbookmark()
    {
        if(isset($_POST['blabel']) && !empty($_POST['blabel']) && isset($_POST['blink']) && !empty($_POST['blink'])) {
            $lablen = strlen($_POST['blabel']);
            if($lablen > 20)
            {
                print false;
            }
            else{
                $data = array();            

                $session = Yii::$app->session;
                $email = $session->get('email_id');
                $s3 = LoginForm::find()->where(['email' => $email])->one();

                $date = time();
                $addbook = new BookmarkForm();
                $addbook->user_id = (string)$s3['_id'];
                $addbook->blabel = $_POST['blabel'];
                $addbook->blink = $_POST['blink'];
                $addbook->created_date = $date;
                $addbook->modified_date = $date;
                $addbook->is_deleted = '0';
                $addbook->created_by = (string)$s3['_id'];
                //$addbook->insert();

                if($addbook->insert()){
                    print true;
                }
                else
                {
                    print false;
                }
            }
        }
    }
}
