<?php
namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\mongodb\ActiveRecord;
use yii\helpers\HtmlPurifier;
use yii\web\UploadedFile;
use yii\helpers\ArrayHelper;
use frontend\models\PostForm;
use frontend\models\LoginForm;
use frontend\models\UserForm;
use frontend\models\Like;
use yii\helpers\Url;
use frontend\models\Friend;
use frontend\models\Personalinfo;
use frontend\models\ProfileVisitor;
use frontend\models\SecuritySetting;
use frontend\models\SavePost;
use yii\widgets\ActiveForm;
use frontend\models\Interests;
use frontend\models\Language;
use frontend\models\UserSetting;
use frontend\models\Occupation;
/**
 * Account controller
 */
class AccountController extends Controller
{
   public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }
    /**
    * Create auth action
    *
    * @return mixed
    */ 
    public function actions()
    {
          return [
          'auth' => [
            'class' => 'yii\authclient\AuthAction',
            'successCallback' => [$this, 'oAuthSuccess'],

          ],
              'captcha' => [
              'class' => 'yii\captcha\CaptchaAction',
          ],

       ];           
    }
    
    public function actionGetpagedata()
    {
        $session = Yii::$app->session;
        $user_id = $userid =  $session->get('user_id');
        if($_POST['page'] == 'basicinfo')
        {
            echo 'Basic Information';
        }
        if($_POST['page'] == 'profilepicture')
        {
            echo 'Profile Picture';
            //include('../views/site/profile_picture.php');
        }
        if($_POST['page'] == 'security')
        {
            echo 'Security Settings';
        }
        if($_POST['page'] == 'notifications')
        {
            echo 'Notification Settings';
        }
        if($_POST['page'] == 'blocking')
        {
            echo 'Blocking';
        }
    }     
}
?>

