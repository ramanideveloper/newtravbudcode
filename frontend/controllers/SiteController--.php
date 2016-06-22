<?php
namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\mongodb\ActiveRecord;
use frontend\models\UserForm;
use frontend\models\LoginForm;
use frontend\models\SignupForm;
use frontend\models\PostForm;
use yii\helpers\HtmlPurifier;
use yii\web\UploadedFile;
/**
 * Site controller
 */
class SiteController extends Controller
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
                
         ];           
      }
    /**
     * Get Fblogin Response.
     *
     * @return mixed
     */
     public function oAuthSuccess($client) 
     {
        // get user data from client
        $userAttributes = $client->getUserAttributes();
        $email =  $userAttributes['email'];
        
        // setting session
        $session = Yii::$app->session;
        $session->set('email',$userAttributes['email']);  
        
         // check point
        $result = UserForm::isUserExist($email);
        if($result)
        {    
           // redirect to inner page 
           $url = Yii::$app->urlManager->createUrl(['site/index2']);
           Yii::$app->getResponse()->redirect($url);
        }
        else
        {
            // insert user detail and redirect
            $date = time();
            $user = new UserForm();
            $user->fb_id = $userAttributes['id'];
            $user->username = $userAttributes['name'];
            $user->email = $userAttributes['email'];
            $user->photo = $userAttributes['picture']['data']['url'];
            $user->created_date = $date;
            $user->updated_date = $date;
            $user->insert();
             
            // fetch all post 
            $posts = PostForm::getAllPost();
            return $this->render('index2',array('posts' => $posts));
       }
    }
    
    /**
     * Displays homepage.
     *
     * @return mixed
    */    
  
     public function actionIndex()
     { 
    
        $model = new \frontend\models\LoginForm();
         $model->scenario = 'signup';
        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->save()) {
// Send Mail
        /*    
    Yii::$app->mailer->compose()
    ->setFrom('from@domain.com')
    ->setTo('markand.trivedi@scalsys.in')
    ->setSubject('Activation Link of Travbud Account')
    ->setTextBody('Plain text content')
    ->send(); 
*/

           return $this->render('index', [
            'model' => $model,
        ]);
        }
        else
        {
        return $this->render('index', [
            'model' => $model,
        ]);

        }



     }




     /**
     * Displays innerpage.
     *
     * @return mixed
     */
   
    public function actionIndex2()
     {
        if (\Yii::$app->user->isGuest) 
        {
            return $this->goHome();
        }
        else
        {
            $posts = PostForm::getAllPost();
            $result = PostForm::find()->with('user')->orderBy(['post_created_date'=>SORT_DESC])->all();
            $count = count($result);
            return $this->render('index2',array('posts' => $posts,'count' => $count));
        }
     }
     /**
     * Logs out the current user.
     *
     * @return mixed
     */
     public function actionLogin()
    {
         if(isset($_POST['login']) && !empty($_POST['login']))
        {
           //  echo "<pre>";
           // print_r($_POST);
           // die();
            //login


            $model = new \frontend\models\LoginForm();
             $model->scenario = 'login';

             if (!\Yii::$app->user->isGuest) {
                return $this->goHome();
            }
 
           // $model = new AdminLoginForm();
            if ($model->load(Yii::$app->request->post()) && $model->login()) {
              //  echo "<pre>";
             //   print_r($_POST['LoginForm']['email']);
              //  die();
                ?>
                <script>alert("You Have Successfully Login");</script>
                <?php
                return $this->render('index', [
                    'model' => $model,
                ]);
                //return $this->goBack();
            } else {
               //    echo "<pre>";
               // print_r($_POST);
              //  die();
                ?>
                <script>alert("Oops..! Something Went Wrong");</script>
                <?php
                return $this->render('index', [
                    'model' => $model,
                ]);
            }

            //login
        }
        
    }
     
       public function actionLogout()
    {
            Yii::$app->user->logout();
            $url = Yii::$app->urlManager->createUrl(['site/index']);
            Yii::$app->getResponse()->redirect($url);
    }
    
     /**
     * Post status.
     *
     */
        
      public function actionAjaxPost()
      {
         // setting layout
         $this->layout = 'ajax_layout';
            
         // get session
         $session = Yii::$app->session;
         $email = $session->get('email');
         
         $id =  UserForm::getLastInsertedRecord($email);
         $temp = $id['_id'];
  
         if(isset($_POST['test']))
         {
            // Filter input.
            $purifier = new HtmlPurifier();
            $text =  HtmlPurifier::process($_POST['test']);
             
            $date = time();
            $post = new PostForm();
            $post->post_text = $text;
            $post->post_status = '1';
            $post->post_created_date = $date;
            $post->post_user_id = $temp;
            $post->insert();
            $this->render('post_ajax',array('post' => $post));
         }
         else
         {
           //return false;
         }
       }
       
     /**
     * Get All Posts in descending order.
     *
     */ 
     public function actionAjaxStatus()
        {
            $this->layout = 'ajax_layout';
            $posts = PostForm::getAllPost();
            
            $result = PostForm::find()->with('user')->orderBy(['post_created_date'=>SORT_DESC])->all();
            $this->render('ajax_status',array('posts' => $posts));
        }
        
     /**
     * Upload Image.
     *
     */ 
     public function actionUpload()
     {
         $this->layout = 'ajax_layout';
         
         $model = new PostForm();
         $session = Yii::$app->session;
         $email = $session->get('email');
         $id = UserForm::getLastInsertedRecord($email);
         $temp = $id['_id'];
               
         $date = time();
         $purifier = new HtmlPurifier();
         $text =  HtmlPurifier::process($_POST['test']);
         $post = new PostForm();
         $post->post_status = '1';
         $post->post_text = $text;
         $post->post_created_date = $date;
         $post->post_user_id = $temp;
         if($_FILES["imageFile1"]["name"] != "") 
         {
            $url = './uploads/';
            $urls = '/uploads/';
            move_uploaded_file($_FILES["imageFile1"]["tmp_name"], $url.$_FILES["imageFile1"]["name"]);
            $post->image = $urls.$_FILES["imageFile1"]["name"];
         }
         else
         {
             
         }
      
        $post->insert();
        $posts = PostForm::getAllPost();
        $count = count($posts);
        return $count;
        //$this->render('post_ajax',array('post' => $post));
     }
     
      public function actionNewPost()
        {
             $posts = PostForm::getAllPost();
             $count = count($posts);
             return $count;
               
        }
}
