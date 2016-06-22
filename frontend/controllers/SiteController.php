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
use frontend\models\UserSetting;
use frontend\models\Personalinfo;
use frontend\models\SecuritySetting;
use frontend\models\NotificationSetting;
use frontend\models\PostForm;
use frontend\models\Friend;
use yii\helpers\HtmlPurifier;
use yii\web\UploadedFile;
use yii\helpers\ArrayHelper;
use frontend\models\BookmarkForm;
use frontend\models\UnfollowFriend;
use frontend\models\MuteFriend;
use frontend\models\SavePost;
use frontend\models\ReportPost;
use frontend\models\CountryCode;
use frontend\models\Comment;
use frontend\models\HideComment;
use frontend\models\HidePost;
use frontend\models\Like;
use frontend\models\Notification;
use frontend\models\Language;
use frontend\models\Education;
use frontend\models\Interests;
use frontend\models\Occupation;
use frontend\models\PhotoForm;
use frontend\models\Slider;
use frontend\models\Cover;
use yii\helpers\Url;

/**
 * Site controller
 */
class SiteController
        extends Controller {

    public function behaviors() {
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
    public function actions() {
        return [
            'auth' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this,
                    'oAuthSuccess'],
            ],
            'captcha' => [
                //'class' => 'yii\captcha\CaptchaAction',
                'class' => 'mdm\captcha\CaptchaAction',
                'level' => 1,
            ],
        ];
    }

    /**
     * Get Fblogin Response.
     *
     * @return mixed
     */
    public function oAuthSuccess($client) {
        // get user data from client
        $userAttributes = $client->getUserAttributes();
        $email = $userAttributes['email'];
        $thumbnail = $userAttributes['picture']['data']['url'];
        
        /*echo "<pre>";
        print_r($userAttributes);
        die();*/

        // setting session
        $session = Yii::$app->session;
        if($session->get('pro_fb') == 'profile_facebook')
        {
            $update = LoginForm::find()->where(['_id' => (string)$session->get('user_id')])->one();
            $fb_id = $userAttributes['id'];
            $fb_img = "https://graph.facebook.com/$fb_id/picture?width=400&height=400";
            $date = time();
            $update->updated_date = $date;
            $update->photo = $fb_img;
            $update->thumbnail = $thumbnail;
            $update->update();
            $url = Yii::$app->urlManager->createUrl(['site/profile-picture']);
            Yii::$app->getResponse()->redirect($url);
        }
        else if($session->get('signup_fb') == 'signup_facebook')
        {
            $update = LoginForm::find()->where(['email' => $session->get('signup_email')])->one();
            $fb_id = $userAttributes['id'];
            $fb_img = "https://graph.facebook.com/$fb_id/picture?width=400&height=400";
            $date = time();
            $update->updated_date = $date;
            $update->photo = $fb_img;
            $update->thumbnail = $thumbnail;
            $update->update();
            $url = Yii::$app->urlManager->createUrl(['site/signup3']);
            Yii::$app->getResponse()->redirect($url);
        }
        else
        {
            $session->set('email',$userAttributes['email']);

            // check point
            $result = UserForm::isUserExist($email);
            if ($result) {
                $session->set('user_id',$result['_id']);
                $update = LoginForm::find()->where(['_id' => $result['_id']])->one();
                $fb_id = $userAttributes['id'];
                $fb_img = "https://graph.facebook.com/$fb_id/picture?width=400&height=400";
                $date = time();
                $name = $userAttributes['name'];
                $explode = explode(" ",$name);
                $fname = $explode[0];
                $lname = $explode[1];
                $fullname = $fname . " " .$lname;

                $photo = $update['photo'];
                if (substr($photo,0,4) == 'http') {
                    $update->photo = $fb_img;
                    $update->thumbnail = $thumbnail;
                }
                $update->fb_id = $fb_id;
                $update->fname = $fname;
                $update->lname = $lname;
                $update->fullname = $fullname;
                $update->email = $userAttributes['email'];

                $update->updated_date = $date;
                $update->status = '1';
                $update->login_from_ip = $_SERVER['REMOTE_ADDR'];
                $update->update();
            } else {
                // insert user detail and redirect
                $date = time();
                $name = $userAttributes['name'];
                $explode = explode(" ",$name);
                $fname = $explode[0];
                $lname = $explode[1];
                $fullname = $fname . " " .$lname;
                $fb_id = $userAttributes['id'];
                $fb_img = 'https://graph.facebook.com/' . $fb_id . '/picture?width=400&height=400';

                $user = new UserForm();

                /* Generate Username */
                $username = LoginForm::generateUsername($fname,$lname);
                $result_unm = LoginForm::find()->where(['username' => $username])->one();
                if (!empty($result_unm)) {
                    $username = LoginForm::generateUsername($fname,$lname);
                    $user->username = $username;
                } else {
                    $user->username = $username;
                }

                /* Generate Username */

                $user->fb_id = $fb_id;
                $user->fname = $fname;
                $user->lname = $lname;
                $user->fullname = $fullname;
                $user->email = $userAttributes['email'];
                $user->photo = $fb_img;
                $user->thumbnail = $thumbnail;
                $user->created_date = $date;
                $user->updated_date = $date;
                $user->status = '1';
                $user->login_from_ip = $_SERVER['REMOTE_ADDR'];
                $user->insert();

                $url = Yii::$app->urlManager->createUrl(['site/index2']);

                $id = LoginForm::getLastInsertedRecord($userAttributes['email']);
                $user_id = $id['_id'];

                $session->set('user_id',$user_id);

                Yii::$app->getResponse()->redirect($url);
                // getLastInsertedRecord
                // fetch all post 
                // $posts = PostForm::getAllPost();
                // return $this->render('index2',array('posts' => $posts));
            }
        }
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex() {
        
        $session = Yii::$app->session;
        $model = new \frontend\models\LoginForm();
        if ($session->get('email')) {
            $email = $session->get('email'); 
            $user_id = $session->get('user_id');
            $user = LoginForm::find()->where(['email' => $email])->one();
           
            //if(!($user->status == '1')){
            if($user->status != '0' && $user->status != '1' ){
               
            Yii::$app->user->logout();
            $url = Yii::$app->urlManager->createUrl(['site/index']);
            Yii::$app->getResponse()->redirect($url);
           
            }
            else{

            //$posts = PostForm::getAllPost();
            $posts = PostForm::getUserFriendsPosts();

             // START get friend list with (id, fb_id, thumb).
            $usrfrd = Friend::getuserFriends($user_id);
            $path = 'profile/';
            $usrfrdlist = array();
            foreach($usrfrd AS $ud) {
                $id = (string)$ud['userdata']['_id'];
                $fbid =  $ud['userdata']['fb_id'];
                $dp = $this->getimage($ud['userdata']['_id'],'thumb');

                //$nm =  $ud['userdata']['fullname'];
                $nm = (isset($ud['userdata']['fullname']) && !empty($ud['userdata']['fullname'])) ? $ud['userdata']['fullname'] : $ud['userdata']['fname'].' '.$ud['userdata']['lname'];
                $usrfrdlist[] = array('id' => $id, 'fbid' => $fbid, 'name' => $nm, 'text' => $nm, 'thumb' => $dp);
            }

            $nrusrfrdlist = $usrfrdlist;

            $f = array('id' => 'F', 'name' => 'Friend', 'text' => 'Friend', 'thumb' => $path.'frd.png');
            $fof = array('id' => 'FOF', 'name' => 'Friend Of Friend', 'text' => 'Friend Of Friend', 'thumb' => $path.'frdoffrd.png');
            array_unshift($usrfrdlist, $f);
            array_unshift($usrfrdlist, $fof);

            return $this->render('index2',
                            array(
                        'model' => $model,
                        'posts' => $posts,
                        'nrusrfrdlist' => $nrusrfrdlist,
                        'frdlist' => $usrfrdlist));
            }
        } else {
            return $this->render('index',['model' => $model,
            ]);
        }
    }

    /**
     * Displays innerpage.
     *
     * @return mixed
     */
    public function actionIndex2() {
        $title = 'sd';
          $model =
                new \frontend\models\LoginForm();

        if (\Yii::$app->user->isGuest) {
            return $this->goHome();
        } else {
            $session = Yii::$app->session;
            
            $email = $session->get('email');
            $user_id = $session->get('user_id');


            if (isset($_POST['theme'])) {
                $result =
                        LoginForm::find()->where(['email' => $email])->one();
                $id =
                        $result['_id'];

                $userSetting =
                        UserSetting::find()->where(['user_id' => $id])->one();
                $userSetting->user_theme =
                        $_POST['theme'];
                $userSetting->update();
            }

            //$posts = PostForm::getAllPost();
            $posts =  PostForm::getUserFriendsPosts();

            $result =  PostForm::find()->with('user')->orderBy(['post_created_date' => SORT_DESC])->all();
            //$count = count($result);
            
             // START get friend list with (id, fb_id, thumb).
            $usrfrd = Friend::getuserFriends($user_id);
            $usrfrdlist = array();
                
            foreach($usrfrd AS $ud) {
                $id = (string)$ud['userdata']['_id'];
                $fbid =  $ud['userdata']['fb_id'];
                $dp = $this->getimage($ud['userdata']['_id'],'thumb');

                //$nm =  $ud['userdata']['fullname'];
                $nm = (isset($ud['userdata']['fullname']) && !empty($ud['userdata']['fullname'])) ? $ud['userdata']['fullname'] : $ud['userdata']['fname'].' '.$ud['userdata']['lname'];
                $usrfrdlist[] = array('id' => $id, 'fbid' => $fbid, 'name' => $nm, 'text' => $nm, 'thumb' => $dp);
            }

            $nrusrfrdlist = $usrfrdlist;

            $f = array('id' => 'F', 'name' => 'Friend', 'text' => 'Friend', 'thumb' => 'profile/frd.png');
            $fof = array('id' => 'FOF', 'name' => 'Friend Of Friend', 'text' => 'Friend Of Friend', 'thumb' => 'profile/frdoffrd.png');
            array_unshift($usrfrdlist, $f);
            array_unshift($usrfrdlist, $fof);

            if( count($posts) > 0)
                $count =  count($posts);
            else
                $count = 0;
            return $this->render('index2',
                            array(
                        'model' => $model,
                        'posts' => $posts,
                        'nrusrfrdlist' => $nrusrfrdlist,
                        'frdlist' => $usrfrdlist));


        }
    }

    public function actionSignup() {

        $model =
                new \frontend\models\LoginForm();
        $model->scenario =
                'signup';
        $session =
                Yii::$app->session;
        if (isset($_POST['LoginForm']['email']) && !empty($_POST['LoginForm']['email'])) {
            $email =
                    $_POST['LoginForm']['email'];

            $session->set('email_id',
                    $email);
        }
        $id =
                $session->get('user_id');

        if (isset($id) && $id != '') {
            $date =
                    time();

            $update =
                    LoginForm::find()->where(['_id' => "$id"])->one();
            $session->set('email_id',
                    $update['email']);
            $update->fname =
                    $_POST['LoginForm']['fname'];
            $update->lname =
                    $_POST['LoginForm']['lname'];

            $update->username =
                    LoginForm::generateUsername($_POST['LoginForm']['fname'],
                            $_POST['LoginForm']['lname']);
            $update->password =
                    $_POST['LoginForm']['password'];
            $update->con_password =
                    $_POST['LoginForm']['password'];
            $update->birth_date =
                    $_POST['birthdate'];
            $update->gender =
                    $_POST['gender1'];
            $update->status =
                    '0';
            $update->created_date =
                    $date;
            $update->updated_date =
                    $date;
            $update->update();

            echo '1';
        } else {
            if (!($model->signup())) {

                if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->save()) {
                    $session->set('step',
                            '1');
                    $result =
                            LoginForm::find()->where(['email' => $email])->one();
                    $id =
                            (string) $result['_id'];
                    $fname =
                            $result['fname'];
                    $lname =
                            $result['lname'];

                    $username =
                            LoginForm::generateUsername($fname,
                                    $lname);
                    $result_unm =
                            LoginForm::find()->where(['username' => $username])->one();
                    if (!empty($result_unm)) {
                        $username =
                                LoginForm::generateUsername($fname,
                                        $lname);
                        $result->username =
                                $username;
                        $result->update();
                    } else {
                        $result->username =
                                $username;
                        $result->update();
                    }

                    $userSetting =
                            new UserSetting();
                    $userSetting->user_id =
                            $id;
                    $userSetting->user_theme =
                            'theme-dark-blue';
                    $userSetting->insert();

                    print true;
                } else {

                    print false;
                }
            } else {
                $status =
                        LoginForm::find()->where(['email' => $email])->one();
                if ($status->status == '1') {
                    echo "6";
                } else {
                    if ($status->phone) {
                        if ($status->photo) {
                            $id =
                                    $status['_id'];
                            $about =
                                    Personalinfo::getLastInsertedRecord($id);
                            $about_us =
                                    $about['about'];
                            if (!empty($about_us)) {
                                // Go To Step 5 Only Verification Requires
                                $session->set('step',
                                        '10');
                                echo "5";
                            } else {
                                //Go To Step 4 Personal Info Requires
                                $session->set('step',
                                        '10');
                                echo "4";
                            }
                        } else {
                            // Go To Step 3 Profile_Picure Not Found
                            $session->set('step',
                                    '10');
                            echo "3";
                        }
                    } else {
                        //Go To Step 2 Profile is Not Set
                        $session->set('step',
                                '10');
                        echo "2";
                    }
                }
            }
        }
    }

    /**
     * Signup2 For The 2nd Step of Signup.
     *
     * @return mixed
     */
    public function actionSignup2() {
        $model = new \frontend\models\LoginForm();
        $model->scenario = 'profile';
        
        $session = Yii::$app->session;
        $session_email = $session->get('email_id');
        
        if(isset($_POST['birth_access']) && !empty($_POST['birth_access'])){
           
            
            $birth = LoginForm::find()->where(['email' => $session_email])->one();
        
            $user_id = (string) $birth->_id;
            
            $birth2 = new UserSetting();
            $birth2 = UserSetting::find()->where(['user_id' => $user_id])->one();
          
            $birth2->birth_date_access = 'Private';
            $birth2->update();
            
            
            
        }

        if ($model->load(Yii::$app->request->post())) {
            
            

            $email = $_POST['LoginForm']['email'];
            if (!($session_email == $email)) {
                $update = LoginForm::find()->where(['email' => $session_email])->one();

                $update->email = $email;
                $update->update();

                $session = Yii::$app->session;
                $session->set('email_id',$email);
            }


            if ($model->validate() && $model->signup2()) {
                $session->set('step','2');
                echo "1";
            } else {
                echo "0";
            }
        } else {
            return $this->render('signup2',
                            [
                        'model' => $model,
            ]);
        }
    }

    public function actionSignup3() {
        $model =
                new \frontend\models\LoginForm();
        $model->scenario =
                'profile_picture';

        if ($model->load(Yii::$app->request->post())) {
            
            if(isset($_POST['web_cam_img']) && !empty($_POST['web_cam_img'])){

                $session = Yii::$app->session;
                $email = $session->get('email_id'); 
                $test = $_POST['web_cam_img'];
                $rest = array_pop(explode('/', $test));

                $s3 = LoginForm::find()->where(['email' => $email])->one();

                $s3->photo = $rest;
                $s3->thumbnail = $rest;

                $s3->update();

                $url = Yii::$app->urlManager->createUrl(['site/signup4']);
                     Yii::$app->getResponse()->redirect($url); 

               }
            else{
                if ($model->validate() && $model->signup3()) {
                    $session =
                            Yii::$app->session;
                    $session->set('step',
                            '3');
                    $url =
                            Yii::$app->urlManager->createUrl(['site/signup4']);
                    Yii::$app->getResponse()->redirect($url);
                } else {

                    return $this->render('signup3',
                                    [
                                'model' => $model,
                    ]);
}
            }
        } else {
            return $this->render('signup3',
                            [
                        'model' => $model,
            ]);
        }
    }
    
    public function actionSignup4()
    { 
       //$model = new \frontend\models\LoginForm();
       $model = new \frontend\models\Personalinfo();
       $model->scenario = 'personal_info';
      
       if ($model->load(Yii::$app->request->post())) {
       
            if($model->validate()){
                 
           $session = Yii::$app->session;
           $email = $session->get('email_id');
           $session->set('step','4');
           
            $id = LoginForm::getLastInsertedRecord($email);
            $user_id = $id['_id'];
            
            if(isset($_POST['Personalinfo']['education']) && !empty($_POST['Personalinfo']['education'])){
               
                $pqr = array(); 
                
                $record_education = ArrayHelper::map(Education::find()->all(), 'name', 'name');
               
                $pqr = $_POST['Personalinfo']['education'];
                
                $edu_differance=array_diff($pqr,$record_education);
                
                if(isset($edu_differance) && !empty($edu_differance)){
                    $insert_education = new Education();
                    foreach($edu_differance AS $edu_diff)                  
                    {
                        $insert_education->name = ucwords($edu_diff);
                        $insert_education->insert();
                    }                  
                }
                
                $education = implode(",", $pqr);
            }
            else{
                $education = '';
            }
            
            if(isset($_POST['Personalinfo']['interests']) && !empty($_POST['Personalinfo']['interests'])){
                $def = array();
 
                $record_interests = ArrayHelper::map(Interests::find()->all(), 'name', 'name');
           
                $def = $_POST['Personalinfo']['interests'];
                $interests_differance=array_diff($def,$record_interests);

                if(isset($interests_differance) && !empty($interests_differance)){
                        $insert_interests = new Interests();
                        foreach($interests_differance AS $int_diff)                  
                        {
                                $insert_interests->name = ucwords($int_diff);
                                $insert_interests->insert();
                        }                  
                }
                
                $interest = implode(",", $def);
            }
            else{
                $interest = '';
            }
            
            if(isset($_POST['Personalinfo']['occupation']) && !empty($_POST['Personalinfo']['occupation'])){
                
                $ghi = array();
                
                $record_occupation = ArrayHelper::map(Occupation::find()->all(), 'name', 'name');
               
                $ghi = $_POST['Personalinfo']['occupation'];
                $occupation_differance=array_diff($ghi,$record_occupation);

                if(isset($occupation_differance) && !empty($occupation_differance)){
                        $insert_occupation = new Occupation();
                        foreach($occupation_differance AS $ocu_diff)                  
                        {
                                $insert_occupation->name = ucwords($ocu_diff);
                                $insert_occupation->insert();
                        }                  
                }
                
                $occupation = implode(",", $ghi);
            }
            else{
                $occupation = '';
            }
            
            if(isset($_POST['Personalinfo']['language']) && !empty($_POST['Personalinfo']['language'])){
               $xyz = array(); 
                
                
                $record_language = ArrayHelper::map(Language::find()->all(), 'name', 'name');
               
                $xyz = $_POST['Personalinfo']['language'];
                $ans=array_diff($xyz,$record_language);
                
                if(isset($ans) && !empty($ans)){
                    $insert_language = new Language();
                    foreach($ans AS $language_diff)                  
                    {
                        $insert_language->name = ucwords($language_diff);
                        $insert_language->insert();
                    }                  
                }
                
                $language = implode(",", $xyz);
            }
            else{
                $language = '';
            }
            
            if(isset($_POST['Personalinfo']['host_services']) && !empty($_POST['Personalinfo']['host_services'])){
               $abc = array(); 
                $abc = $_POST['Personalinfo']['host_services'];
                $host_services = implode(",", $abc);
            }
            else{
                $host_services = '';
            }

             $about = $_POST['Personalinfo']['about'];
             
             if(isset($_POST['Personalinfo']['is_host']) && !empty($_POST['Personalinfo']['is_host'])){
                 
             $host = $_POST['Personalinfo']['is_host']; 
             }
             else{
                $host = ''; 
             }
             
             $host_gender = isset($_POST['Personalinfo']['gender']) ? $_POST['Personalinfo']['gender'] : '';
            
             $record = Personalinfo::find()->where(['user_id' => $user_id])->one();
          
             
             if(!empty($record)){
         
                 $update = new Personalinfo();
             
               
                $record->about = $about;
                $record->education = $education;
                $record->occupation = $occupation;
                $record->interests = $interest;
                $record->is_host = $host;
                $record->language = $language;
                $record->host_services = $host_services;
                $record->gender = $host_gender;
                $record->update();
            
             }
             else{
                
                 
                $insert = new Personalinfo();
             
                $insert->user_id = $user_id;
                $insert->about = $about;
                $insert->education = $education;
                $insert->occupation = $occupation;
                $insert->interests = $interest;
                $insert->is_host = $host;
                $insert->language = $language;
                $insert->host_services = $host_services;
                $insert->gender = $host_gender;
                $insert->insert();
               
             }
    
            $url = Yii::$app->urlManager->createUrl(['site/signup5']);
            Yii::$app->getResponse()->redirect($url); 
                 try {
                    $test = Yii::$app->mailer->compose()
                    ->setFrom('no-reply@travbud.com')
                    ->setTo($email)
                    ->setSubject('TravBud- Verification Link.')
                    ->setHtmlBody('<html>
    <head>
        <meta charset="utf-8" />
        <title>TravBud</title>
    </head>
    
    <body style="margin:0;padding:0;">
        <div style="color: #353535; float:left; font-size: 13px;width:100%; font-family:Arial, Helvetica, sans-serif;text-align:center;padding:20px 0 0;">
            <div style="width:600px;display:inline-block;">
                <img src="http://www.travbud.com/frontend/web/assets/a8535972/images/logo.png" style="margin:0 0 20px;"/>
                <div style="clear:both"></div>
                <div style="padding:3px;border:1px solid #ddd;margin:0 0 20px;">
                    <div style="background:#f5f5f5;padding:30px;">
                        <div style="text-align: center;font-size: 34px;font-weight: bold;color:#4083BF;margin:0 0 20px;">Congratulations!!</div>
                        <div style="text-align: center;font-size: 16px;">Your Have Succesfully Registered In Travbud</div>                      
                        <div style="text-align: center;font-size: 14px;margin:10px 0 0;">                           
                            <a href="www.travbud.com?email=' . $email . '" style="font-family: Arial, Helvetica, sans-serif;color: #ffffff;background-color:#4083BF;padding: 8px 20px;text-decoration: none;display:inline-block;margin: 10px 0 0;">Click Here To Login</a></div>
                        </div>
                    </div>
                </div>
                <div style="float: left;width: 100%;text-align: center">
                   <div style="color: #8f8f8f;text-align: center;">&copy;  www.travbud.com All rights reserved.</div>
                   <div style="text-align: center;font-weight: bold;width: 100%;margin:10px 0 20px;color:#505050;">For anything you can reach us directly at <a href="contact@travbud.com" style="color:#4083BF">contact@travbud.com</a></div>
               </div>
            </div>
        </div>
        
    </body>
</html>')
                            ->send();

                    $url = Yii::$app->urlManager->createUrl(['site/signup5']);
                    Yii::$app->getResponse()->redirect($url);
                } catch (ErrorException $e) {
                    echo 'Caught exception: ', $e->getMessage(), "\n";
                }
                /*
                  $url = Yii::$app->urlManager->createUrl(['site/signup5']);
                  Yii::$app->getResponse()->redirect($url); */
            } else {

                return $this->render('signup4',
                                [
                            'model' => $model,
                ]);
            }
        } else {
            return $this->render('signup4',
                            [
                        'model' => $model,
            ]);
        }
    }

    public function actionSignup5() {
       
        $model = new \frontend\models\LoginForm();
        //$model->scenario = 'verification';

        if ($model->load(Yii::$app->request->post())) {

            $session = Yii::$app->session;
            $session_email = $session->get('email_id');
            $email = $_POST['LoginForm']['email'];
            $session->set('step','5');
            if (!($session_email == $email)) {
                $update = LoginForm::find()->where(['email' => $session_email])->one();

                $update->email = $email;
                $update->update();

                $session = Yii::$app->session;
                $session->set('email_id',$email);
            }
            BookmarkForm::setLinks();
            // $email = $_POST['LoginForm']['email'];



            /* Verification Mail TO USER */


            try {
                $test =
                        Yii::$app->mailer->compose()
                        ->setFrom('no-reply@travbud.com')
                        ->setTo($email)
                        ->setSubject('TravBud- Verification Link.')
                        ->setHtmlBody('<html>
    <head>
        <meta charset="utf-8" />
        <title>TravBud</title>
    </head>
    
    <body style="margin:0;padding:0;">
        <div style="color: #353535; float:left; font-size: 13px;width:100%; font-family:Arial, Helvetica, sans-serif;text-align:center;padding:20px 0 0;">
            <div style="width:600px;display:inline-block;">
                <img src="http://www.travbud.com/frontend/web/assets/a8535972/images/logo.png" style="margin:0 0 20px;"/>
                <div style="clear:both"></div>
                <div style="padding:3px;border:1px solid #ddd;margin:0 0 20px;">
                    <div style="background:#f5f5f5;padding:30px;">
                        <div style="text-align: center;font-size: 34px;font-weight: bold;color:#4083BF;margin:0 0 20px;">Congratulations!!</div>
                        <div style="text-align: center;font-size: 16px;">Your Have Succesfully Registered In Travbud</div>                      
                        <div style="text-align: center;font-size: 14px;margin:10px 0 0;">                           
                            <a href="www.travbud.com?email=' . $email . '" style="font-family: Arial, Helvetica, sans-serif;color: #ffffff;background-color:#4083BF;padding: 8px 20px;text-decoration: none;display:inline-block;margin: 10px 0 0;">Click Here To Login</a></div>
                        </div>
                    </div>
                </div>
                <div style="float: left;width: 100%;text-align: center">
                   <div style="color: #8f8f8f;text-align: center;">&copy;  www.travbud.com All rights reserved.</div>
                   <div style="text-align: center;font-weight: bold;width: 100%;margin:10px 0 20px;color:#505050;">For anything you can reach us directly at <a href="contact@travbud.com" style="color:#4083BF">contact@travbud.com</a></div>
               </div>
            </div>
        </div>
        
    </body>
</html>')
                        ->send();

                $url = Yii::$app->urlManager->createUrl(['site/signup5']);
                Yii::$app->getResponse()->redirect($url);
            } catch (ErrorException $e) {
                echo 'Caught exception: ', $e->getMessage(), "\n";
            }

            /*
              $url = Yii::$app->urlManager->createUrl(['site/signup5']);
              Yii::$app->getResponse()->redirect($url); */
        } else {
            return $this->render('signup5',
                            [
                        'model' => $model,
            ]);
        }
    }
    
    
    public function actionVerify(){
       
        $session = Yii::$app->session;
        
        $email = $session->get('email_id');
        
       
      
       try {
                $test =
                        Yii::$app->mailer->compose()
                        ->setFrom('no-reply@travbud.com')
                        ->setTo($email)
                        ->setSubject('TravBud- Verification Link.')
                        ->setHtmlBody('<html>
    <head>
        <meta charset="utf-8" />
        <title>TravBud</title>
    </head>
    
    <body style="margin:0;padding:0;">
        <div style="color: #353535; float:left; font-size: 13px;width:100%; font-family:Arial, Helvetica, sans-serif;text-align:center;padding:20px 0 0;">
            <div style="width:600px;display:inline-block;">
                <img src="http://www.travbud.com/frontend/web/assets/a8535972/images/logo.png" style="margin:0 0 20px;"/>
                <div style="clear:both"></div>
                <div style="padding:3px;border:1px solid #ddd;margin:0 0 20px;">
                    <div style="background:#f5f5f5;padding:30px;">
                        <div style="text-align: center;font-size: 34px;font-weight: bold;color:#4083BF;margin:0 0 20px;">Congratulations!!</div>
                        <div style="text-align: center;font-size: 16px;">Your Have Succesfully Registered In Travbud</div>                      
                        <div style="text-align: center;font-size: 14px;margin:10px 0 0;">                           
                            <a href="www.travbud.com?email=' . $email . '" style="font-family: Arial, Helvetica, sans-serif;color: #ffffff;background-color:#4083BF;padding: 8px 20px;text-decoration: none;display:inline-block;margin: 10px 0 0;">Click Here To Login</a></div>
                        </div>
                    </div>
                </div>
                <div style="float: left;width: 100%;text-align: center">
                   <div style="color: #8f8f8f;text-align: center;">&copy;  www.travbud.com All rights reserved.</div>
                   <div style="text-align: center;font-weight: bold;width: 100%;margin:10px 0 20px;color:#505050;">For anything you can reach us directly at <a href="contact@travbud.com" style="color:#4083BF">contact@travbud.com</a></div>
               </div>
            </div>
        </div>
        
    </body>
</html>')
                        ->send();

                
            } catch (ErrorException $e) {
                echo 'Caught exception: ', $e->getMessage(), "\n";
            }
            
            if(isset($_POST['email']) && !empty($_POST['email'])){
              
                echo 1;
            }
            else{
                
               
                   $url = Yii::$app->urlManager->createUrl(['site/signup5']);
                   Yii::$app->getResponse()->redirect($url);     
               
            }

               
    
    }
    

    /**
     * Login the user.
     *
     * @return mixed
     */
    public function actionLogin() {
       
        $session = Yii::$app->session;
        $model = new \frontend\models\LoginForm();
        if (isset($_POST['login']) && !empty($_POST['login'])) {
            //login
            $model->scenario = 'login';

            if ($model->load(Yii::$app->request->post()) && $model->login()) {
                $email = $_POST['LoginForm']['email'];
                $password = $_POST['LoginForm']['password'];
                $session = Yii::$app->session;


                if (empty($email) && empty($password)) {
                    $session->set('loginerror','Please Enter Email & Password.');
                    $url = Yii::$app->urlManager->createUrl(['site/index']);
                    Yii::$app->getResponse()->redirect($url);
                } else if (empty($email)) {
                    $session->set('loginerror','Please Enter Email.');
                    $url = Yii::$app->urlManager->createUrl(['site/index']);
                    Yii::$app->getResponse()->redirect($url);
                } else if (empty($password)) {
                    $session->set('loginerror','Please Enter Password.');
                    $url = Yii::$app->urlManager->createUrl(['site/index']);
                    Yii::$app->getResponse()->redirect($url);
                } else {

                    $session->set('email_id', $email);
                    $value = $model->login();

                    /* if(isset($_POST['remember'])){
                      $cookies = Yii::$app->response->cookies;
                      $cookies->add(new \yii\web\Cookie([
                      'name' => 'email',
                      'value' => $_POST['LoginForm']['email'],
                      'expire' => time() + 3600,
                      ]));
                      } */
                    // echo '<pre>';print_r($_COOKIE);exit;
                    // echo 'val='.$value;exit;
                    if ($value == "1") {
                        
                        $email = $session->get('email');

                        $id = LoginForm::getLastInsertedRecord($email);
                        $user_id = $id['_id'];
                        $update = LoginForm::find()->where(['_id' => $user_id])->one();
                        $date =  time();
                        $update->lat = $_POST['lat'];
                        $update->long = $_POST['long'];
                        $update->login_from_ip = $_SERVER['REMOTE_ADDR'];
                        $update->update();
                        $session->set('user_id',$user_id);
                        $url = Yii::$app->urlManager->createUrl(['site/index2']);
                        Yii::$app->getResponse()->redirect($url);
                    } else if ($value == "2") {

                        $session->set('step','10');
                        $url =Yii::$app->urlManager->createUrl(['site/signup2']);
                        Yii::$app->getResponse()->redirect($url);
                    } else if ($value == "3") {
                        $session->set('step','10');
                        $url = Yii::$app->urlManager->createUrl(['site/signup3']);
                        Yii::$app->getResponse()->redirect($url);
                    } else if ($value == "4") {
                        $session->set('step','10');
                        $url = Yii::$app->urlManager->createUrl(['site/signup4']);
                        Yii::$app->getResponse()->redirect($url);
                    } else if ($value == "5") {
                        $session->set('step','10');
                        $url = Yii::$app->urlManager->createUrl(['site/signup5']);
                        Yii::$app->getResponse()->redirect($url);
                    } else {
                        $session->set('loginerror','Please Login with Correct Credentials.');
                        $url = Yii::$app->urlManager->createUrl(['site/index']);
                        Yii::$app->getResponse()->redirect($url);
                    }
                }
            } else {

                $url =
                        Yii::$app->urlManager->createUrl(['site/index']);
                Yii::$app->getResponse()->redirect($url);
            }
            //login
        } else {
            /* $cookies = Yii::$app->request->cookies;
              // get the cookie value
              if ($cookies->has('email'))
              {
              $cemail = $cookies->getValue('email');
              $session = Yii::$app->session;
              $session->set('email',$email);
              $url = Yii::$app->urlManager->createUrl(['site/index2']);
              Yii::$app->getResponse()->redirect($url);
              } */
            if ($session->get('email')) {
                $url =
                        Yii::$app->urlManager->createUrl(['site/index2']);



                Yii::$app->getResponse()->redirect($url);
            } else {
                $url =
                        Yii::$app->urlManager->createUrl(['site/index']);
                Yii::$app->getResponse()->redirect($url);
            }
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout() {
        // Yii::$app->getSession()->destroy();   

        $session =
                Yii::$app->session;
        $uid =
                $session->get('user_id');

        Yii::$app->user->logout();
        $url =
                Yii::$app->urlManager->createUrl(['site/index']);
        Yii::$app->getResponse()->redirect($url);
    }

    public function actionBookmarks() {
        $model =
                new \frontend\models\BookmarkForm();
        if (isset($_POST) && !empty($_POST)) {
            if (isset($_POST['delid']) && !empty($_POST['delid'])) {
                if ($model->delbookmark()) {
                    print true;
                } else {
                    print false;
                }
            }
            if (isset($_POST['editid']) && !empty($_POST['editid'])) {
                if ($model->editbookmark()) {
                    print true;
                } else {
                    print false;
                }
            }
            if (isset($_POST['addid']) && !empty($_POST['addid'])) {
                if ($model->addbookmark() && $model->validate()) {
                    print true;
                }
            }
        } else {
            return $this->render('bookmarks',
                            [ 'model' => $model,]);
        }
    }

    public function actionSavedpost() {
        $model =
                new \frontend\models\SavePost();
        return $this->render('savedposts',
                        [ 'model' => $model,]);
    }

    public function actionViewprofile() {
        $userid =
                $_POST['user_id'];
        $model =
                new \frontend\models\LoginForm();
        $getuserinfo =
                LoginForm::find()->where(['_id' => $userid])->one();
        $link =
                Url::to(['userwall/index',
                    'id' => "$userid"]);
        ?>
        <div class="profile-tip-cover"><img src="profile/cover.jpg"></div>
        <div class="profile-tip-avatar"> <a href="<?php echo $link; ?>">
        <?php if ($getuserinfo['photo'] != '' && $getuserinfo['fb_id'] == '') { ?>
                    <img src="profile/<?= $getuserinfo['photo'] ?>" class="img-responsive" height="35" width="35" >
        <?php
        } else if ($getuserinfo['fb_id'] != '') {
            ?>
                    <img src="<?= $getuserinfo['photo'] ?>" class="img-responsive" height="35" width="35">
            <?php
        } else {
            ?>
                    <img src="profile/<?= $getuserinfo['gender'] ?>.jpg" class="img-responsive" height="35" width="35">
        <?php } ?>
            </a> </div>
        <div class="profile-tip-info">
            <a href="<?php echo $link; ?>">
                <div class="cover-username"><?php echo ucfirst($getuserinfo['fname']) . ' ' . ucfirst($getuserinfo['lname']) ?></div>
                <div class="cover-headline">Member of Travbud</div>
            </a>
        </div>
        <?php
    }

    public function actionForgotpasswordold() {
        $model =
                new \frontend\models\LoginForm();
        return $this->render('forgot',
                        [ 'model' => $model,]);
    }

    public function actionResetpassword() {
        $model =
                new \frontend\models\LoginForm();
        return $this->render('reset_password',
                        [ 'model' => $model,]);
    }

    public function actionResetpassworddone() {
        //echo print_r($_POST);exit;
        $resetpass =
                $_POST['password'];
        $travid =
                $_POST['travid'];
        $model =
                new \frontend\models\LoginForm();
        
        if (isset($resetpass) && !empty($resetpass) && isset($travid) && !empty($travid)) {
            $data =
                    array();
            $getinfouser =
                    LoginForm::find()->where(['_id' => $travid])->one();
            if ($getinfouser) {
                $uname =
                        $getinfouser->fname;
                $finalemail =
                        $getinfouser->email;
                $resettime =
                        date("l, F d, Y");
                $fname =
                        ucfirst($uname);
                $user_agent     =   $_SERVER['HTTP_USER_AGENT'];
                function getOS() {
                    $user_agent     =   $_SERVER['HTTP_USER_AGENT'];
                $os_platform    =   "Unknown OS Platform";
                $os_array       =   array(
                                        '/windows nt 10/i'     =>  'Windows 10',
                                        '/windows nt 6.3/i'     =>  'Windows 8.1',
                                        '/windows nt 6.2/i'     =>  'Windows 8',
                                        '/windows nt 6.1/i'     =>  'Windows 7',
                                        '/windows nt 6.0/i'     =>  'Windows Vista',
                                        '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
                                        '/windows nt 5.1/i'     =>  'Windows XP',
                                        '/windows xp/i'         =>  'Windows XP',
                                        '/windows nt 5.0/i'     =>  'Windows 2000',
                                        '/windows me/i'         =>  'Windows ME',
                                        '/win98/i'              =>  'Windows 98',
                                        '/win95/i'              =>  'Windows 95',
                                        '/win16/i'              =>  'Windows 3.11',
                                        '/macintosh|mac os x/i' =>  'Mac OS X',
                                        '/mac_powerpc/i'        =>  'Mac OS 9',
                                        '/linux/i'              =>  'Linux',
                                        '/ubuntu/i'             =>  'Ubuntu',
                                        '/iphone/i'             =>  'iPhone',
                                        '/ipod/i'               =>  'iPod',
                                        '/ipad/i'               =>  'iPad',
                                        '/android/i'            =>  'Android',
                                        '/blackberry/i'         =>  'BlackBerry',
                                        '/webos/i'              =>  'Mobile'
                                    );

                foreach ($os_array as $regex => $value)
                {
                    if (preg_match($regex, $user_agent)) {
                        $os_platform    =   $value;
                    }
                }
                return $os_platform;
                }

                function getBrowser() {
                    $user_agent     =   $_SERVER['HTTP_USER_AGENT'];
                    $browser        =   "Unknown Browser";
                    $browser_array  =   array(
                                            '/msie/i'       =>  'Internet Explorer',
                                            '/firefox/i'    =>  'Firefox',
                                            '/safari/i'     =>  'Safari',
                                            '/chrome/i'     =>  'Chrome',
                                            '/edge/i'       =>  'Edge',
                                            '/opera/i'      =>  'Opera',
                                            '/netscape/i'   =>  'Netscape',
                                            '/maxthon/i'    =>  'Maxthon',
                                            '/konqueror/i'  =>  'Konqueror',
                                            '/mobile/i'     =>  'Handheld Browser'
                                        );

                    foreach ($browser_array as $regex => $value) { 
                        if (preg_match($regex, $user_agent)) {
                            $browser    =   $value;
                        }
                    }
                    return $browser;
                }

                $user_os        =   getOS();
                $user_browser   =   getBrowser();
                $user_pwd_ip   =   $_SERVER['REMOTE_ADDR'];
                $location = json_decode(file_get_contents('http://freegeoip.net/json/'.$user_pwd_ip));
                $user_geoloc = $location->city.', '.$location->region_code.', '.$location->country_code;

                if (isset($resetpass) && !empty($resetpass) && isset($travid) && !empty($travid)) {
                    $value =
                            new LoginForm();
                    $value =
                            LoginForm::find()->where(['_id' => $travid])->one();
                    $value->password =
                            $resetpass;
                    $value->con_password =
                            $resetpass;
                    $value->update();
                    try {
                        $test =
                                Yii::$app->mailer->compose()
                                //->setFrom('no-reply@travbud.com')
                                ->setFrom(array('csupport@travbud.com' => 'Travbud Security'))
                                ->setTo($finalemail)
                                ->setSubject('TravBud - Password Reset')
                                ->setHtmlBody('<html>
            <head>
                    <meta charset="utf-8" />
                    <title>TravBud</title>
            </head>

            <body style="margin:0;padding:0;background:#dfdfdf;">
                    <div style="color: #353535; float:left; font-size: 13px;width:100%; font-family:Arial, Helvetica, sans-serif;text-align:center;padding:40px 0 0;">
                            <div style="width:600px;display:inline-block;">
                                    <img src="http://www.travbud.com/frontend/web/assets/a8535972/images/logo.png" style="margin:0 0 10px;width:130px;float:left;"/>
                                    <div style="clear:both"></div>
                                    <div style="border:1px solid #ddd;margin:0 0 10px;">
                                            <div style="background:#fff;padding:20px;border-top:10px solid #333;text-align:left;">
                                                    <div style="color: #333;font-size: 13px;margin: 0 0 20px;">Hi ' . $fname . ',</div>
                                                    <div style="color: #333;font-size: 13px;">Your Travbud password was changed on ' . $resettime . '.</div><br/><br/>
                                                    <div style="color: #333;font-size: 13px;">Operating System: ' . $user_os . '.</div>
                                                    <div style="color: #333;font-size: 13px;">Browser: ' . $user_browser . '.</div>
                                                    <div style="color: #333;font-size: 13px;">IP Address: ' . $user_pwd_ip . '.</div>
                                                    <div style="color: #333;font-size: 13px;">Estimated location: ' . $user_geoloc . '.</div><br/><br/>
                                                    <div style="color: #333;font-size: 13px;"><strong>If you did this</strong>, you can safely disregard this email.</div>
                                                    <div style="color: #333;font-size: 13px;"><strong>If you didn\'t do this</strong>, please secure your account.</div><br/><br/>
                                                    <div style="color: #333;font-size: 13px;">Thanks,</div>
                                                    <div style="color: #333;font-size: 13px;">The Travbud Security Team</div>
                                            </div>
                                    </div>
                                    <div style="clear:both"></div>
                                    <div style="width:600px;display:inline-block;font-size:11px;">
                                       <div style="color: #777;text-align: left;">&copy;  www.travbud.com All rights reserved.</div>
                                       <div style="text-align: left;width: 100%;margin:5px  0 0;color:#777;">For support, you can reach us directly at <a href="csupport@travbud.com" style="color:#4083BF">csupport@travbud.com</a></div>
                               </div>
                            </div>
                    </div>

            </body>
    </html>')
                                ->send();
                    } catch (ErrorException $e) {
                        echo 'Caught exception: ', $e->getMessage(), "\n";
                    }

                    $session =
                            Yii::$app->session;
                    $session->set('email',
                            $finalemail);
                    $session->set('user_id',
                    $travid);
                    $url =
                            Yii::$app->urlManager->createUrl(['site/index2']);
                    Yii::$app->getResponse()->redirect($url);
                    //echo json_encode($data);
                } else {
                    $data['value'] =
                            '0';
                    echo json_encode($data);
                }
            } else {
                return false;
            }
        }
    }

    public function actionForgotpassword() {
        $model =
                new \frontend\models\LoginForm();
        if (isset($_POST['forgotemail']) && !empty($_POST['forgotemail'])) {
            $data =
                    array();
            $getinfouser =
                    LoginForm::find()->where(['email' => $_POST['forgotemail']])->orwhere(['alternate_email' => $_POST['forgotemail']])->one();
            if ($getinfouser) {
                $name =
                        $getinfouser->fname;
                $forgot_id =
                        $getinfouser->_id;
                $fname =
                        ucfirst($name);
                $encrypt =
                        strrev(base64_encode($forgot_id));
                $resetlink =
                        "http://www.travbud.com/frontend/web/index.php?r=site%2Fresetpassword&enc=$encrypt";
                print true;
                try {
                    $test =
                            Yii::$app->mailer->compose()
                            //->setFrom('no-reply@travbud.com')
                            ->setFrom(array('csupport@travbud.com' => 'Travbud Security'))
                            ->setTo($getinfouser['email'])
                            ->setSubject(''.$fname.', here\'s the link to reset your password')
                            ->setHtmlBody('<html>
            <head>
                    <meta charset="utf-8" />
                    <title>TravBud</title>
            </head>

            <body style="margin:0;padding:0;background:#dfdfdf;">
                    <div style="color: #353535; float:left; font-size: 13px;width:100%; font-family:Arial, Helvetica, sans-serif;text-align:center;padding:40px 0 0;">
                            <div style="width:600px;display:inline-block;">
                                    <img src="http://www.travbud.com/frontend/web/assets/a8535972/images/logo.png" style="margin:0 0 10px;width:130px;float:left;"/>
                                    <div style="clear:both"></div>
                                    <div style="border:1px solid #ddd;margin:0 0 10px;">
                                            <div style="background:#fff;padding:20px;border-top:10px solid #333;text-align:left;">
                                                    <div style="color: #333;font-size: 13px;margin: 0 0 20px;">Hi ' . $fname . '</div>
                                                    <div style="color: #333;font-size: 13px;">You recently requested a password reset.</div>
                                                    <div style="color: #333;font-size: 13px;margin: 0 0 20px;">To change your Travbud password, click <a href="' . $resetlink . '" target="_blank">here</a> or paste the following link into your browser: <br/><br/><a href="' . $resetlink . '" target="_blank">' . $resetlink . '</a></div>
                                                    <div style="color: #333;font-size: 13px;">Thank you for using Travbud!</div>
                                                    <div style="color: #333;font-size: 13px;">The Travbud Team</div>
                                            </div>
                                    </div>
                                    <div style="clear:both"></div>
                                    <div style="width:600px;display:inline-block;font-size:11px;">
                                       <div style="color: #777;text-align: left;">&copy;  www.travbud.com All rights reserved.</div>
                                       <div style="text-align: left;width: 100%;margin:5px  0 0;color:#777;">For support, you can reach us directly at <a href="csupport@travbud.com" style="color:#4083BF">csupport@travbud.com</a></div>
                               </div>
                            </div>
                    </div>

            </body>
    </html>')
                            ->send();
                    //print true;
                } catch (ErrorException $e) {
                    echo 'Caught exception: ', $e->getMessage(), "\n";
                }
            } else {
                print false;
            }
        } else {
            print false;
        }
    }

    public function actionShareoption() {
        $model =
                new \frontend\models\LoginForm();
        return $this->render('shareoption',
                        [ 'model' => $model,]);
    }

    public function actionReportpost() {
        $model =
                new \frontend\models\ReportPost();
        if (isset($_POST['pid']) && !empty($_POST['pid'])) {
            $date =
                    time();

            $session =
                    Yii::$app->session;
            $user_id =
                    (string) $session->get('user_id');

            $getdetails =
                    ReportPost::find()->where(['post_id' => $_POST['pid'],
                        'reporter_id' => $user_id])->one();
            if (!$getdetails) {
                $report_post =
                        new ReportPost();
                $report_post->post_id =
                        $_POST['pid'];
                $report_post->reporter_id =
                        $user_id;
                $report_post->reason =
                        $_POST['desc'];
                $report_post->created_date =
                        $date;
                if ($report_post->insert()) {
                    print true;
                } else {
                    print false;
                }
            } else {
                print false;
            }
        } else {
            return $this->render('reportpost',
                            [ 'model' => $model,]);
        }
    }

    public function actionEditpost() {

       //echo '<pre>';print_r($_POST);exit;
        $model =
                new \frontend\models\LoginForm();

        if (isset($_POST['pid']) && !empty($_POST['pid'])) {
            //echo 'sss'.$_POST['imageFilepost'];die;
            $date =
                    time();
            $pid =
                    $_POST['pid'];

            $update =
                    new PostForm();
            $update =
                    PostForm::find()->where(['_id' => $pid])->one();
            $text =
                    $update['post_text'];
            $image =
                    $update['image'];



            if (isset($_FILES) && !empty($_FILES)) {
                $imgcount = count($_FILES["imageFilepost"]["name"]);
                $img = '';
                $im =  '';
                for ($i =0;$i < $imgcount;$i++) {
                    $name = $_FILES["imageFilepost"]["name"][$i];
                    $tmp_name = $_FILES["imageFilepost"]["tmp_name"][$i];
                    if (isset($name) && $name != "") {
                        $url = '../web/uploads/';
                        $urls = '/uploads/';
                        move_uploaded_file($tmp_name, $url . $date . $name);
                        $img .= $urls . $date . $name . ',';
                    } 
                }
                $im = $image . $im . $img;
                $update->image = $im;
                if (isset($_POST['desc']) && !empty($_POST['desc'])) {
                    $update->post_text =
                            $_POST['desc'];
                    $update->post_type =
                            'text and image';
                } else if (isset($text) && !empty($text)) {
                    $update->post_type =
                            'text and image';
                } else {
                    $update->post_type =
                            'image';
                }
            } else {
                if (isset($_POST['desc']) && !empty($_POST['desc'])) {
                    $update->post_text =
                            $_POST['desc'];
                    if (isset($image) && !empty($image)) {
                        $update->post_type =
                                'text and image';
                    } else {
                        $update->post_type =
                                'text';
                    }
                }
            }
            //if(isset($_POST['title']) && !empty($_POST['title'])) {
                $update->post_title = $_POST['title'];
            //}
            $update->post_tags = (isset($_POST['posttags']) && !empty($_POST['posttags'])) ? $_POST['posttags'] : 'null';
            $update->post_privacy = $_POST['post_privacy'];
            $update->currentlocation = $_POST['edit_current_location'];
            $update->share_setting = $_POST['share_setting'];
            $update->comment_setting = $_POST['comment_setting'];
            $update->post_created_date =
                    "$date";

            $update->update();
            $last_insert_id = $_POST['pid'];
        
            $this->display_last_post($last_insert_id);
            /*
              $url = Yii::$app->urlManager->createUrl(['site/index2']);
              Yii::$app->getResponse()->redirect($url); */
        } else {
            echo "0";
            //return $this->render('editpost', [ 'model' => $model,]);
        }
    }

    public function actionUnfollowfriend() {
        $loginmodel =
                new \frontend\models\LoginForm();
        if (isset($_POST['fid']) && !empty($_POST['fid'])) {

            $fid =
                    (string) $_POST['fid'];

            $data =
                    array();

            $session =
                    Yii::$app->session;
            $email =
                    $session->get('email_id');
            $user_id =
                    (string) $session->get('user_id');

            $userexist =
                    UnfollowFriend::find()->where(['user_id' => $user_id])->one();
            $unfollow =
                    new UnfollowFriend();
            if ($userexist) {
                if (strstr($userexist['unfollow_ids'],
                                $fid)) {
                    print true;
                } else {
                    $unfollow =
                            UnfollowFriend::find()->where(['user_id' => $user_id])->one();
                    $unfollow->unfollow_ids =
                            $userexist['unfollow_ids'] . ',' . $fid;
                    if ($unfollow->update()) {
                        print true;
                    } else {
                        print false;
                    }
                }
            } else {
                $unfollow->user_id =
                        $user_id;
                $unfollow->unfollow_ids =
                        $fid;
                if ($unfollow->insert()) {
                    print true;
                } else {
                    print false;
                }
            }
        }
    }

    public function actionMutefriend() {
        $loginmodel =
                new \frontend\models\LoginForm();
        if (isset($_POST['fid']) && !empty($_POST['fid'])) {

            $fid =
                    (string) $_POST['fid'];

            $data =
                    array();

            $session =
                    Yii::$app->session;
            $email =
                    $session->get('email_id');
            $user_id =
                    (string) $session->get('user_id');

            $userexist =
                    MuteFriend::find()->where(['user_id' => $user_id])->one();
            $mute =
                    new MuteFriend();
            if ($userexist) {
                if (strstr($userexist['mute_ids'],
                                $fid)) {
                    print true;
                } else {
                    $mute =
                            MuteFriend::find()->where(['user_id' => $user_id])->one();
                    $mute->mute_ids =
                            $userexist['mute_ids'] . ',' . $fid;
                    if ($mute->update()) {
                        print true;
                    } else {
                        print false;
                    }
                }
            } else {
                $mute->user_id =
                        $user_id;
                $mute->mute_ids =
                        $fid;
                if ($mute->insert()) {
                    print true;
                } else {
                    print false;
                }
            }
        }
    }

    public function actionSharenowwithfriends() {
        $lmodel =
                new \frontend\models\LoginForm();
        $pmodel =
                new \frontend\models\PostForm();
        $fmodel =
                new \frontend\models\Friend();

        $session =
                Yii::$app->session;
        $user_id =
                (string) $session->get('user_id');
        $result_security =
                SecuritySetting::find()->where(['user_id' => $user_id])->one();
        if ($result_security) {
            $post_privacy =
                    $result_security['my_post_view_status'];
        } else {
            $post_privacy =
                    'Public';
        }

        if (isset($_POST['keyword']) && !empty($_POST['keyword'])) {
            $data =
                    array();

            $session =
                    Yii::$app->session;
            $email =
                    $session->get('email_id');
            $user_id =
                    (string) $session->get('user_id');
            $getusers =
                    Friend::userlistsuggetions($_POST['keyword']);
            foreach ($getusers as
                    $getuser) {
                $uid =
                        $getuser['_id'];
                $usrname =
                        $getuser['fname'] . " " . $getuser['lname'];
                $friends_to =
                        Friend::find()->where(['from_id' => "$uid",
                            'to_id' => "$user_id",
                            'status' => '1'])->one();
                $friends_from =
                        Friend::find()->where(['from_id' => "$user_id",
                            'to_id' => "$uid",
                            'status' => '1'])->one();
                if ($friends_to || $friends_from) {
                    $dp = $this->getimage($getuser['_id'],'thumb');
                    ?>
                    <div class="tb-share-box" onClick="selectName('<?php echo $usrname; ?>','<?php echo $uid; ?>');">
                        <input type="hidden" value="<?php echo $uid ?>" id="frndid" name="frndid"/>
                        <img style="height: 30px; width:30px; float:left;" alt="user-photo" class="img-responsive" src="<?= $dp ?>">
                        <span class="share-sp"><?php echo $usrname ?></span>
                    </div>
                    <?php
                }
            }
        }
        if (isset($_POST['spid']) && !empty($_POST['spid'])) {
            //echo '<pre>';print_r($_POST);exit;
            $data =
                    array();

            $session =
                    Yii::$app->session;
            $email =
                    $session->get('email_id');
            $user_id =
                    (string) $session->get('user_id');

            $getpostinfo =
                    PostForm::find()->where(['_id' => $_POST['spid'],])->one();
            if(isset($_POST['current_location']) && !empty($_POST['current_location']))
            {
                $currentlocation = $_POST['current_location'];
            }
            else
            {
                $currentlocation = '';
            }
            if ($getpostinfo) {
                $date =
                        time();
                $sharepost =
                        new PostForm();
                /* if(($user_id == $getpostinfo['post_user_id']) && $_POST['frndid']=='undefined')
                  {
                  $sharepost = PostForm::find()->where(['_id' => $_POST['spid'],])->one();
                  $sharepost->post_created_date = "$date";
                  $sharepost->post_privacy = $post_privacy;
                  if($sharepost->update())
                  {print true;}
                  else{print false;}
                  } */
                //else{
                if (!empty($getpostinfo['post_type'])) {
                    $sharepost->post_type =
                            $getpostinfo['post_type'];
                }
                if (!empty($_POST['desc'])) {
                    $sharepost->post_text =
                            $_POST['desc'];
                } else {
                    $sharepost->post_text =
                            $getpostinfo['post_text'];
                }
                $sharepost->post_status =
                        '1';
                $sharepost->post_created_date =
                        "$date";
                $sharepost->post_tags = (isset($_POST['posttags']) && !empty($_POST['posttags'])) ? $_POST['posttags'] : '';
                if ($_POST['frndid'] == 'undefined' || empty($_POST['frndid'])) {
                    $puser =
                            $user_id;
                } else {
                    $puser =
                            $_POST['frndid'];
                }
                if(isset($_POST['post_privacy']) && !empty($_POST['post_privacy']))
                {
                    $postprivacy = $_POST['post_privacy'];
                }
                else
                {
                    $postprivacy = 'Public';
                }
                $sharepost->post_user_id =
                        $puser;

                $sharepost->shared_from =
                        $getpostinfo['post_user_id'];
                $sharepost->currentlocation = $currentlocation;
                $sharepost->post_privacy = $postprivacy;
                if (!empty($getpostinfo['image'])) {
                    $sharepost->image =
                            $getpostinfo['image'];
                }
                if (!empty($getpostinfo['link_title'])) {
                    $sharepost->link_title =
                            $getpostinfo['link_title'];
                }
                if (!empty($getpostinfo['link_description'])) {
                    $sharepost->link_description =
                            $getpostinfo['link_description'];
                }
                if (!empty($getpostinfo['album_title'])) {
                    $sharepost->album_title =
                            $getpostinfo['album_title'];
                }
                if (!empty($getpostinfo['album_place'])) {
                    $sharepost->album_place =
                            $getpostinfo['album_place'];
                }
                if (!empty($getpostinfo['album_img_date'])) {
                    $sharepost->album_img_date =
                            $getpostinfo['album_img_date'];
                }
                if (!empty($getpostinfo['is_album'])) {
                    $sharepost->is_album =
                            $getpostinfo['is_album'];
                }
                $posttags = $_POST['posttags'];
                if($posttags != 'null')
                {
                    $gsu_id = $getpostinfo['post_user_id'];
                    $sec_result_set = SecuritySetting::find()->where(['user_id' => "$gsu_id"])->one();
                    if ($sec_result_set)
                    {
                        $tag_review_setting = $sec_result_set['review_tags'];
                    }
                    else
                    {
                        $tag_review_setting = 'Disabled';
                    }
                    if($tag_review_setting == "Enabled")
                    {
                        $review_tags = "1";
                    }
                    else
                    {
                        $review_tags = "0";
                    }
                }
                else
                {
                    $review_tags = "0";
                }
                $sharepost->parent_post_id = $_POST['spid'];
                $sharepost->is_timeline =
                        '1';
                $sharepost->is_deleted =
                        $review_tags;
                $sharepost->shared_by = $user_id;
                $sharepost->share_setting = $_POST['share_setting'];
                $sharepost->comment_setting = $_POST['comment_setting'];
                //$sharepost->post_privacy = 'Public';
                $sharepost->insert();
                $last_insert_id = $sharepost->_id;
                $result_security = SecuritySetting::find()->where(['user_id' => "$puser"])->one();
                if ($result_security)
                {
                    $tag_review_setting = $result_security['review_posts'];
                }
                else
                {
                    $tag_review_setting = 'Disabled';
                }
                // Insert record in notification table also
                $notification =  new Notification();
                $notification->share_id =   "$last_insert_id";
                $notification->post_id = $_POST['spid'];
                $notification->user_id = $puser;
                $notification->notification_type = 'sharepost';
                $notification->review_setting = $tag_review_setting;
                $notification->is_deleted = '0';
                $notification->status = '1';
                $notification->created_date = "$date";
                $notification->updated_date = "$date";
                $post_details = PostForm::find()->where(['_id' => $_POST['spid']])->one();
                $notification->post_owner_id = $post_details['post_user_id'];
                $notification->shared_by = $user_id;
                if($post_details['post_user_id'] != $user_id && $post_details['post_privacy'] != "Private")
                {
                    $notification->insert();
                    $tag_friends = explode(',',$_POST['posttags']);
                    $tag_count = count($tag_friends);
                    if($posttags != 'null')
                    {
                        for ($i = 0; $i < $tag_count; $i++)
                        {
                            $result_security = SecuritySetting::find()->where(['user_id' => "$tag_friends[$i]"])->one();
                            if ($result_security)
                            {
                                $tag_review_setting = $result_security['review_posts'];
                            }
                            else
                            {
                                $tag_review_setting = 'Disabled';
                            }
                            $notification =  new Notification();
                            $notification->post_id =   "$last_insert_id";
                            $notification->user_id = $tag_friends[$i];
                            $notification->notification_type = 'tag_friend';
                            $notification->review_setting = $tag_review_setting;
                            $notification->is_deleted = $review_tags;
                            $notification->status = '1';
                            $notification->created_date = "$date";
                            $notification->updated_date = "$date";
                            $notification->insert();
                        }
                    }
                }
                
                
               
                if ($last_insert_id) {
                    $sharepost =
                            PostForm::find()->where(['_id' => $_POST['spid'],])->one();
                   
                    $sharepost->share_by =
                            $getpostinfo['share_by'] . $user_id . ',';
                    if ($sharepost->update()) {
                        ?>
                            
                            <?php
                       // print true;
                           $this->display_last_post($last_insert_id);
                    } else {
                       // print false;
                    }
                  //  print true;
                } else {
                   // print false;
                }
                //}
            } else {
                print false;
            }
        }
        if (isset($_POST['shareid']) && !empty($_POST['shareid'])) {
            $data =
                    array();

            $session =
                    Yii::$app->session;
            $email =
                    $session->get('email_id');
            $user_id =
                    (string) $session->get('user_id');

            $getpostinfo =
                    PostForm::find()->where(['_id' => $_POST['shareid'],])->one();
            if ($getpostinfo) {
                $date =
                        time();
                $sharepost =
                        new PostForm();
                /* if($user_id == $getpostinfo['post_user_id'])
                  {
                  $sharepost = PostForm::find()->where(['_id' => $_POST['shareid'],])->one();
                  $sharepost->post_created_date = "$date";
                  $sharepost->post_privacy = $post_privacy;
                  if($sharepost->update())
                  {print true;}
                  else{print false;}
                  } */
                //else{
                if (!empty($getpostinfo['post_type'])) {
                    $sharepost->post_type =
                            $getpostinfo['post_type'];
                }
                if (!empty($getpostinfo['post_text'])) {
                    $sharepost->post_text =
                            $getpostinfo['post_text'];
                }
                $sharepost->post_status =
                        '1';
                $sharepost->is_deleted =
                        '0';
                $sharepost->post_privacy =
                        'Public';
                $sharepost->post_created_date =
                        "$date";
                $sharepost->post_user_id =
                        $user_id;
                $sharepost->shared_from =
                        $getpostinfo['post_user_id'];
                if (!empty($getpostinfo['image'])) {
                    $sharepost->image =
                            $getpostinfo['image'];
                }
                if (!empty($getpostinfo['link_title'])) {
                    $sharepost->link_title =
                            $getpostinfo['link_title'];
                }
                if (!empty($getpostinfo['link_description'])) {
                    $sharepost->link_description =
                            $getpostinfo['link_description'];
                }
                $last_insert_id = $sharepost->insert();
                if ($last_insert_id) {
                   $last_insert_id =  $sharepost->_id;
                    $sharepost =
                            PostForm::find()->where(['_id' => $_POST['shareid'],])->one();
                    $sharepost->share_by =
                            $getpostinfo['share_by'] . $user_id . ',';
                    if ($sharepost->update()) 
                    {
                      //  print true;
                      $this->display_last_post($last_insert_id);
                        
                    } else {
                       /// print false;
                    }
                } else {
                   // print false;
                }
                //}
            } else {
              //  print false;
            }
        }
        
        //$last_id = 
    }

    public function actionForgotstepone() {
        $model =
                new \frontend\models\LoginForm();
        if (isset($_POST['fmail']) && !empty($_POST['fmail'])) {
            $data =
                    array();
            $getinfouser =
                    LoginForm::find()->where(['email' => $_POST['fmail']])->one();
            if ($getinfouser) {
                $data['username'] =
                        $getinfouser->username;
                $data['email'] =
                        $getinfouser->email;
                $data['photo'] =
                        $getinfouser->photo;
                $data['value'] =
                        '1';
                echo json_encode($data);
            } else {
                $data['value'] =
                        '0';
                echo json_encode($data);
            }
        }
    }

    public function actionForgotsteptwo() {
        $model =
                new \frontend\models\LoginForm();
        if (isset($_POST['fhmail']) && !empty($_POST['fhmail'])) {
            $data =
                    array();
            $getinfouser =
                    LoginForm::find()->where(['email' => $_POST['fhmail']])->one();
            if ($getinfouser) {
                $uname =
                        $getinfouser->username;
                $data['email'] =
                        $getinfouser->email;
                $data['value'] =
                        '1';
                $frpass =
                        $model->forgotpass();
                if ($frpass) {
                    $forgotcode =
                            $frpass->forgotcode;
                    // echo 'yyy'.Yii::getAlias('@bower/travel/images/logo.png');die;//$logo = 
                    $forgotpassstatus =
                            $frpass->forgotpassstatus;
                    try {
                        $test =
                                Yii::$app->mailer->compose()
                                ->setFrom('no-reply@travbud.com')
                                ->setTo($data['email'])
                                ->setSubject('TravBud - Somebody requested a new password for your TravBud account.')
                                ->setHtmlBody('<html>
    <head>
        <meta charset="utf-8" />
        <title>TravBud</title>
    </head>
    
    <body style="margin:0;padding:0;background:#dfdfdf;">
        <div style="color: #353535; float:left; font-size: 13px;width:100%; font-family:Arial, Helvetica, sans-serif;text-align:center;padding:40px 0 0;">
            <div style="width:600px;display:inline-block;">
                <img src="http://www.travbud.com/frontend/web/assets/a8535972/images/logo.png" style="margin:0 0 10px;width:130px;float:left;"/>
                <div style="clear:both"></div>
                <div style="border:1px solid #ddd;margin:0 0 10px;">
                    <div style="background:#fff;padding:20px;border-top:10px solid #333;text-align:left;">
                        <div style="color: #333;font-size: 13px;margin: 0 0 20px;">Hi ' . $uname . '</div>
                        <div style="color: #333;font-size: 13px;">Somebody recently asked to reset your TravBud password.</div>
                        <ul style="display:inline-block;list-style:none;background:#fff;padding:0;margin:10px 0;width:100%;">
                            <li><span style="color: #333;display: inline-block;font-size: 13px;">You can enter the following password reset code:</span><span style="padding:0 5px;width:200px;color: #505050;font-size: 13px;font-weight: bold;">' . $forgotcode . '</span></li>
                        </ul>
                        <div style="font-size: 13px;">                          
                            <a href="www.travbud.com" style="background-color: #4083bf;color: #ffffff;display: inline-block;font-family: Arial,Helvetica,sans-serif;margin: 10px 0 0;padding: 8px 15px;text-decoration: none;">Click Here To Login</a></div>
                        </div>
                    </div>
                </div>
                <div style="clear:both"></div>
                <div style="width:600px;display:inline-block;font-size:11px;">
                   <div style="color: #777;text-align: left;">&copy;  www.travbud.com All rights reserved.</div>
                   <div style="text-align: left;width: 100%;margin:5px  0 0;color:#777;">For anything you can reach us directly at <a href="contact@travbud.com" style="color:#4083BF">contact@travbud.com</a></div>
               </div>
            </div>
        </div>
        
    </body>
</html>')
                                ->send();
                        $data['email'] =
                                $getinfouser->email;
                        $data['value'] =
                                '1';
                        echo json_encode($data);
                    } catch (ErrorException $e) {
                        echo 'Caught exception: ', $e->getMessage(), "\n";
                    }
                }
            } else {
                print false;
            }
        } else {
            print false;
        }
    }

    public function actionForgotstepthree() {
        //echo print_r($_POST);exit;
        $model =
                new \frontend\models\LoginForm();
        if (isset($_POST['fhhmail']) && !empty($_POST['fhhmail']) && isset($_POST['fhcode']) && !empty($_POST['fhcode'])) {
            $data =
                    array();
            $getinfouser =
                    LoginForm::find()->where(['email' => $_POST['fhhmail']])->one();
            if ($getinfouser) {
                $data['email'] =
                        $getinfouser->email;
                $data['forgotcode'] =
                        $getinfouser->forgotcode;
                $data['forgotpassstatus'] =
                        $getinfouser->forgotpassstatus;
                if (($data['forgotcode'] == $_POST['fhcode']) && $data['forgotpassstatus'] == 0) {
                    $value =
                            new LoginForm();
                    $value =
                            LoginForm::find()->where(['email' => $_POST['fhhmail']])->one();
                    $value->forgotpassstatus =
                            '1';
                    $value->update();
                    $data['value'] =
                            '1';
                    echo json_encode($data);
                } else {
                    $data['value'] =
                            '0';
                    echo json_encode($data);
                }
            } else {
                return false;
            }
        }
    }

    public function actionForgotstepfour() {
        //echo print_r($_POST);exit;
        $model =
                new \frontend\models\LoginForm();
        if (isset($_POST['finalmail']) && !empty($_POST['finalmail'])) {
            $data =
                    array();
            $getinfouser =
                    LoginForm::find()->where(['email' => $_POST['finalmail']])->one();
            if ($getinfouser) {
                $uname =
                        $getinfouser->username;
                $finalemail =
                        $getinfouser->email;
                $resettime =
                        date("l d F Y");
                if (isset($_POST['newpass']) && !empty($_POST['newpass'])) {
                    $value =
                            new LoginForm();
                    $value =
                            LoginForm::find()->where(['email' => $_POST['finalmail']])->one();
                    $value->password =
                            $_POST['newpass'];
                    $value->con_password =
                            $_POST['newpass'];
                    $value->update();
                    $data['value'] =
                            '1';

                    try {
                        $test =
                                Yii::$app->mailer->compose()
                                ->setFrom('no-reply@travbud.com')
                                ->setTo($finalemail)
                                ->setSubject('TravBud - Password Reset')
                                ->setHtmlBody('<html>
    <head>
        <meta charset="utf-8" />
        <title>TravBud</title>
    </head>
    
    <body style="margin:0;padding:0;">
        <div style="color: #353535; float:left; font-size: 13px;width:100%; font-family:Arial, Helvetica, sans-serif;text-align:center;padding:20px 0 0;">
            <div style="width:600px;display:inline-block;">
                <img src="http://travbud.com/frontend/web/assets/c2bb9cd8/images/logo.png" style="margin:0 0 20px;"/>
                <div style="clear:both"></div>
                <div style="padding:3px;border:1px solid #ddd;margin:0 0 20px;">
                    <div style="background:#f5f5f5;padding:30px;">
                                            <div style="text-align: center;font-size: 34px;font-weight: bold;color:#4083BF;margin:0 0 20px;">Hi ' . $uname . '</div>
                                            <div style="text-align: center;font-size: 16px;">Your Travbud password has been reset using the email address ' . $finalemail . ' on ' . $resettime . '.</div>
                    </div>
                </div>
                <div style="float: left;width: 100%;text-align: center">
                   <div style="color: #8f8f8f;text-align: center;">&copy;  www.travbud.com All rights reserved.</div>
                   <div style="text-align: center;font-weight: bold;width: 100%;margin:10px 0 20px;color:#505050;">For anything you can reach us directly at <a href="contact@travbud.com" style="color:#4083BF">contact@travbud.com</a></div>
               </div>
            </div>
        </div>
        
    </body>
</html>')
                                ->send();
                    } catch (ErrorException $e) {
                        echo 'Caught exception: ', $e->getMessage(), "\n";
                    }

                    $session =
                            Yii::$app->session;
                    $session->set('email',
                            $_POST['finalmail']);
                    $url =
                            Yii::$app->urlManager->createUrl(['site/index2']);
                    Yii::$app->getResponse()->redirect($url);
                    //echo json_encode($data);
                } else {
                    $data['value'] =
                            '0';
                    echo json_encode($data);
                }
            } else {
                return false;
            }
        }
    }

    public function actionForgot() {
        $session =
                Yii::$app->session;
        $model =
                new \frontend\models\LoginForm();
        if (isset($_POST['fmail']) && !empty($_POST['fmail'])) {

            if ($model->forgot()) {
                $email =
                        $model->forgot()->email;
                $password =
                        $model->forgot()->password;

                try {
                    $test =
                            Yii::$app->mailer->compose()
                            ->setFrom('no-reply@travbud.com')
                            ->setTo($email)
                            ->setSubject('TravBud- Reset Password.')
                            ->setHtmlBody('<html>
    <head>
        <meta charset="utf-8" />
        <title>TravBud</title>
    </head>
    
    <body style="margin:0;padding:0;">
        <div style="color: #353535; float:left; font-size: 13px;width:100%; font-family:Arial, Helvetica, sans-serif;text-align:center;padding:20px 0 0;">
            <div style="width:600px;display:inline-block;">
                <img src="http://travbud.com/frontend/web/assets/c2bb9cd8/images/logo.png" style="margin:0 0 20px;"/>
                <div style="clear:both"></div>
                <div style="padding:3px;border:1px solid #ddd;margin:0 0 20px;">
                    <div style="background:#f5f5f5;padding:30px;">
                        <div style="text-align: center;font-size: 34px;font-weight: bold;color:#4083BF;margin:0 0 20px;">Congratulations!!</div>
                        <div style="text-align: center;font-size: 16px;">Your New Password is Set</div>
                        <ul style="display:inline-block;text-align:center;list-style:none;background:#fff;padding:0;margin:20px 0 10px;width:100%;">
                            <li style="padding:15px 0;"><span style="display: inline-block;padding: 0 5px;color: #2BB254;font-size: 18px;font-weight: bold;">New Password</span><span style="padding:0 5px;width:200px;color: #505050;font-size: 18px;font-weight: bold;">' . $password . '</span></li>
                        </ul>
                        <div style="text-align: center;font-size: 14px;">                           
                            <a href="www.travbud.com" style="font-family: Arial, Helvetica, sans-serif;color: #ffffff;background-color:#4083BF;padding: 8px 20px;text-decoration: none;display:inline-block;margin: 10px 0 0;">Click Here To Login</a></div>
                        </div>
                    </div>
                </div>
                <div style="float: left;width: 100%;text-align: center">
                   <div style="color: #8f8f8f;text-align: center;">&copy;  www.travbud.com All rights reserved.</div>
                   <div style="text-align: center;font-weight: bold;width: 100%;margin:10px 0 20px;color:#505050;">For anything you can reach us directly at <a href="contact@travbud.com" style="color:#4083BF">contact@travbud.com</a></div>
               </div>
            </div>
        </div>
        
    </body>
</html>')
                            ->send();
                    print true;
                } catch (ErrorException $e) {
                    echo 'Caught exception: ', $e->getMessage(), "\n";
                }
            } else {
                print false;
            }
        } else {
            print false;
        }
    }

    /**
     * Post status.
     *
     */
    public function actionAjaxPost() {
        // setting layout
        $this->layout =
                'ajax_layout';

        // get session
        $session =
                Yii::$app->session;
        $email =
                $session->get('email');

        $id =
                UserForm::getLastInsertedRecord($email);
        $temp =
                (string) $id['_id'];

        if (isset($_POST['test'])) {
            // Filter input.
            $purifier =
                    new HtmlPurifier();
            $text =
                    HtmlPurifier::process($_POST['test']);

            $date =
                    time();
            $post =
                    new PostForm();
            $post->post_text =
                    $text;
            $post->post_status =
                    '1';
            $post->post_created_date =
                    "$date";
            $post->post_user_id =
                    $temp;
            $post->insert();
            $this->render('post_ajax',
                    array(
                'post' => $post));
        } else {
            //return false;
        }
    }

    /**
     * Get All Posts in descending order.
     *
     */
    public function actionAjaxStatus() {
        $this->layout =
                'ajax_layout';
        //$posts = PostForm::getAllPost();
        $posts =
                PostForm::getUserFriendsPosts();
        $result =
                PostForm::find()->with('user')->orderBy(['post_created_date' => SORT_DESC])->all();
        $this->render('ajax_status',
                array(
            'posts' => $posts));
    }

    /**
     * Upload Post.
     *
     */
    public function actionUpload() {
        
        $this->layout =
                'ajax_layout';

        $model =
                new PostForm();
        $session =
                Yii::$app->session;
        $email =
                $session->get('email');
        $userid =
                $user_id =
                $session->get('user_id');
        $result =
                LoginForm::find()->where(['_id' => $userid])->one();
        $date =
                time();
        if (empty($_POST['test'])) {
            $_POST['test'] =
                    '';
        }
        if (empty($_POST['title'])) {
            $_POST['title'] =
                    '';
        }
        $purifier =
                new HtmlPurifier();
        $text =
                HtmlPurifier::process($_POST['test']);

        if (!empty($_POST['link_description'])) {
            $title =
                    $_POST['link_title'];
            $description =
                    $_POST['link_description'];
            $image =
                    $_POST['link_image'];
            $url =
                    $_POST['link_url'];

            $post =
                    new PostForm();
            $post->post_type =
                    'link';
            $post->link_title =
                    $title;
            $post->image =
                    $image;
            $post->post_text =
                    $url;
            $post->link_description =
                    $description;
            $post->post_created_date =
                    "$date";
            $post->post_user_id =
                    "$user_id";
            $post->is_deleted =
                    '0';
            $post->post_status =
                    '1';
        } else {


            $post =
                    new PostForm();
            $post->post_status =
                    '1';
            $post->post_text =
                    $text;
            $post->post_type =
                    'text';
            $post->post_created_date =
                    "$date";
            $post->post_user_id =
                    "$user_id";



            if(isset($_POST['counter'])){$_POST['counter']=$_POST['counter'];}else{$_POST['counter']=0;}

            if (isset($_FILES) && !empty($_FILES)) {

                $imgcount =
                        count($_FILES["imageFile1"]["name"]);
                $img =
                        '';
                $im =
                        '';
                for ($i =
                $_POST['counter'];
                        $i < $imgcount;
                        $i++) {
                    if (isset($_FILES["imageFile1"]["name"][$i]) && $_FILES["imageFile1"]["name"][$i] != "") {
                        $url =
                                '../web/uploads/';
                        $urls =
                                '/uploads/';
                        move_uploaded_file($_FILES["imageFile1"]["tmp_name"][$i],
                                $url . $date . $_FILES["imageFile1"]["name"][$i]);
                        if ($text == '') {
                            $post->post_type =
                                    'image';
                        } else {
                            $post->post_type =
                                    'text and image';
                        }
                        $img =
                                $urls . $date . $_FILES["imageFile1"]["name"][$i] . ',';
                        $im =
                                $im . $img;
                    } else {
                        
                    }
                }
                $post->image =
                        $im;
            }
        }

        if(isset($_POST['current_location']) && !empty($_POST['current_location']) && $_POST['current_location']!='undefined')
        {
                $post->currentlocation = $_POST['current_location'];
        }

        //custom_share', 'custom_notshare', 'anyone_tag'
        $post->custom_share = (isset($_POST['sharewith']) && !empty($_POST['sharewith'])) ? $_POST['sharewith'] : '';
        $post->custom_notshare = (isset($_POST['sharenot']) && !empty($_POST['sharenot'])) ? $_POST['sharenot'] : '';
        $post->anyone_tag = (isset($_POST['customchk']) && !empty($_POST['customchk'])) ? $_POST['customchk'] : '';
        $post->post_tags = (isset($_POST['posttags']) && !empty($_POST['posttags'])) ? $_POST['posttags'] : '';
    
        $post->post_title = $_POST['title'];
        $post->share_setting = $_POST['share_setting'];
        $post->comment_setting = $_POST['comment_setting'];
        $post->post_privacy =
                $_POST['post_privacy'];
        $post->is_deleted =
                '0';
        $post->insert();

        $last_insert_id =  $post->_id;
        
        if($_POST['post_privacy'] != 'Private')
        {
            // Insert record in notification table also
             $notification =  new Notification();
             $notification->post_id =   "$last_insert_id";
             $notification->user_id = "$user_id";
             $notification->notification_type = 'post';
             $notification->is_deleted = '0';
             $notification->status = '1';
             $notification->created_date = "$date";
             $notification->updated_date = "$date";
             $notification->insert();
        }
        
        if($_POST['posttags'] != 'null')
        {
            // Insert record in notification table also
            //$tag_count = count($_POST['posttags']);
            $tag_friends = explode(',',$_POST['posttags']);
            //print_r($tag_friends);
            $tag_count = count($tag_friends);
            for ($i = 0; $i < $tag_count; $i++)
            {
                $result_security = SecuritySetting::find()->where(['user_id' => "$tag_friends[$i]"])->one();
                if ($result_security)
                {
                    $tag_review_setting = $result_security['review_posts'];
                }
                else
                {
                    $tag_review_setting = 'Disabled';
                }
                $notification =  new Notification();
                $notification->post_id =   "$last_insert_id";
                $notification->user_id = $tag_friends[$i];
                $notification->notification_type = 'tag_friend';
                $notification->review_setting = $tag_review_setting;
                $notification->is_deleted = '0';
                $notification->status = '1';
                $notification->created_date = "$date";
                $notification->updated_date = "$date";
                $notification->insert();
            }
        }
       //$this->display_last_post($last_insert_id,$_POST['newpost_flag']);
        $this->display_last_post($last_insert_id);
    }

    public function actionNewPost() {
        $posts = PostForm::getUserFriendsPosts('updates');
        $count_post = count($posts);
        return $count_post;
    }

    public function actionBasicinfo() {
        
        
        $model = new \frontend\models\UserSetting();
        $model1 = new \frontend\models\LoginForm();
        $model2 = new \frontend\models\Personalinfo();

        $model->scenario = 'basicinfo';

        $session = Yii::$app->session;
        $email = $session->get('email');

        $id = LoginForm::getLastInsertedRecord($email);
        $user_id = (string) $id['_id'];
        
        if(isset($_POST['birth_date']) && !empty($_POST['birth_date'])){
            $_POST['LoginForm']['birth_date'] = $_POST['birth_date']; 
            $_POST['birth_date_access'] = 'Private'; 
  
        }

        $record_user = LoginForm::find()->where(['_id' => $user_id])->one();
        $record_personal = Personalinfo::find()->where(['user_id' => $user_id])->one();
        $record_setting = UserSetting::find()->where(['user_id' => $user_id])->one();

        if (isset($_POST) && !empty($_POST)) {

            $data = array();

            if (isset($_POST['LoginForm']['fname']) && !empty($_POST['LoginForm']['fname'])) {


                $record_user->fname = $_POST['LoginForm']['fname'];
                $record_user->lname = $_POST['LoginForm']['lname'];
                $record_user->fullname = $_POST['LoginForm']['fname'] . " " . $_POST['LoginForm']['lname'];
                $record_user->update(); 

                $data[] = $record_user->fname;
                $data[] = $record_user->lname;

                $data = $data[0] . " " . $data[1];

                echo json_encode($data);
            }

            if (isset($_POST['LoginForm']['email']) && !empty($_POST['LoginForm']['email'])) {
                $eml2 = $_POST['LoginForm']['email'];
                $record = LoginForm::find()->select(['email'])->where(['email' => $eml2])->one();

                if (!empty($record_setting)) {
                    $record_setting->email_access = $_POST['email_access'];
                    $record_setting->update();
                } else {
                    $insert_setting = new UserSetting();
                    $insert_setting->user_id = $user_id;
                    $insert_setting->email_access = $_POST['email_access'];
                    $insert_setting->insert();
                }
                if ($email == $eml2) {
                    $data[] = '1';
                    echo json_encode($data);
                } else {
                    if (!empty($record)) {

                        $data[] = '0';
                        echo json_encode($data);
                    } else {

                        $record_user->email = $eml2;
                        $record_user->update();
                        $session->set('email',$eml2);

                        $data[] = $record_user->email;
                        echo json_encode($data);
                    }
                }
            }


            if (isset($_POST['LoginForm']['alternate_email']) && !empty($_POST['LoginForm']['alternate_email'])) {

                $record_user->alternate_email = $_POST['LoginForm']['alternate_email'];
                $record_user->update();

                if (!empty($record_setting)) {
                    $record_setting->alternate_email_access = $_POST['alternate_email_access'];
                    $record_setting->update();
                } else {
                    $insert_setting = new UserSetting();
                    $insert_setting->user_id = $user_id;
                    $insert_setting->alternate_email_access = $_POST['alternate_email_access'];
                    $insert_setting->insert();
                }

                $data[] = $record_user->alternate_email;
                echo json_encode($data);
            }
            if (isset($_POST['LoginForm']['password']) && !empty($_POST['LoginForm']['password'])) {

                $record_user->password = $_POST['LoginForm']['password'];
                $record_user->con_password = $_POST['LoginForm']['con_password'];
                $date = time();
                $record_user->pwd_changed_date = "$date";
                $record_user->update();
                
                $time = Yii::$app->EphocTime->time_pwd_changed(time(),$record_user['pwd_changed_date']);
                $data[] = 'Password updated '.$time;
                echo json_encode($data);
            }
            if (isset($_POST['LoginForm']['phone']) && !empty($_POST['LoginForm']['phone'])) {

                $record_user->phone = $_POST['LoginForm']['phone'];
                $record_user->update();

                if (!empty($record_setting)) {
                    $record_setting->mobile_access = $_POST['mobile_access'];
                    $record_setting->update();
                } else {
                    $insert_setting = new UserSetting();
                    $insert_setting->user_id = $user_id;
                    $insert_setting->mobile_access = $_POST['mobile_access'];
                    $insert_setting->insert();
                }

                $data[] = $record_user->phone;
                echo json_encode($data);
            }
            if (isset($_POST['LoginForm']['city']) && !empty($_POST['LoginForm']['city'])) {
                
                if (isset($_POST['country']) && !empty($_POST['country'])) {

                $record_user->country = $_POST['country'];
                $record_user->update();
                
            }
            
            if (isset($_POST['isd_code']) && !empty($_POST['isd_code'])) {
                    
                $record_user->isd_code = "+".$_POST['isd_code'];
                $record_user->update();
            }

                $record_user->city = $_POST['LoginForm']['city'];
                $record_user->update();

                $data[] = $record_user->city;
                $data[] = $record_user->country;
                $data[] = $record_user->isd_code;

                echo json_encode($data);
            }
            if (isset($_POST['LoginForm']['country']) && !empty($_POST['LoginForm']['country'])) {

                $record_user->country = $_POST['LoginForm']['country'];
                $record_user->update();

                $data[] =
                        $record_user->country;
                echo json_encode($data);
            }
            if (isset($_POST['LoginForm']['gender']) && !empty($_POST['LoginForm']['gender'])) {

                $record_user->gender = $_POST['LoginForm']['gender'];
                $record_user->update();

                $data[] = $record_user->gender;
                echo json_encode($data);

                if (!empty($record_setting)) {
                    $record_setting->gender_access = $_POST['gender_access'];
                    $record_setting->update();
                } else {
                    $insert_setting = new UserSetting();
                    $insert_setting->user_id = $user_id;
                    $insert_setting->gender_access = $_POST['gender_access'];
                    $insert_setting->insert();
                }
            }
            if (isset($_POST['LoginForm']['birth_date']) && !empty($_POST['LoginForm']['birth_date'])) {

                $record_user->birth_date = $_POST['LoginForm']['birth_date'];
                $record_user->update();

                $data[] = $record_user->birth_date;
                echo json_encode($data);


                if (!empty($record_setting)) {
                    $record_setting->birth_date_access = $_POST['birth_date_access'];
                    $record_setting->update();
                } else {
                    $insert_setting = new UserSetting();
                    $insert_setting->user_id = $user_id;
                    $insert_setting->birth_date_access = $_POST['birth_date_access'];
                    $insert_setting->insert();
                }
            }
            if (isset($_POST['Personalinfo']['current_city']) && !empty($_POST['Personalinfo']['current_city'])) {
                if (!empty($record_personal)) {
                    $record_personal->current_city = $_POST['Personalinfo']['current_city'];
                    $record_personal->update();

                    $data[] = $record_personal->current_city;
                    echo json_encode($data);
                } else {
                    $insert_personal = new Personalinfo();
                    $insert_personal->user_id = $user_id;
                    $insert_personal->current_city = $_POST['Personalinfo']['current_city'];
                    $insert_personal->insert();

                    $data[] = $insert_personal->current_city;
                    echo json_encode($data);
                }
            }
            if (isset($_POST['Personalinfo']['hometown']) && !empty($_POST['Personalinfo']['hometown'])) {
                if (!empty($record_personal)) {
                    $record_personal->hometown = $_POST['Personalinfo']['hometown'];
                    $record_personal->update();

                    $data[] = $record_personal->hometown;
                    echo json_encode($data);
                } else {
                    $insert_personal = new Personalinfo();
                    $insert_personal->user_id = $user_id;
                    $insert_personal->hometown = $_POST['Personalinfo']['hometown'];
                    $insert_personal->insert();

                    $data[] = $record_personal->hometown;
                    echo json_encode($data);
                }
            }
            if (isset($_POST['Personalinfo']['language']) && !empty($_POST['Personalinfo']['language'])) {
              
                $record_language = ArrayHelper::map(Language::find()->all(), 'name', 'name');
               
                $lang = $_POST['Personalinfo']['language'];
                $ans=array_diff($lang,$record_language);
                
                if(isset($ans) && !empty($ans)){
                    $insert_language = new Language();
                    foreach($ans AS $language_diff)                  
                    {
                        $insert_language->name = ucwords($language_diff);
                        $insert_language->insert();
                    }                  
                }
               
                $lang_list = implode(",",$lang);
                if (!empty($record_personal)) {
                    
                    $record_personal->language = $lang_list;
                    $record_personal->update();

                    $data[] = $record_personal->language;
                    echo json_encode($data);
                } else {
                    $insert_personal = new Personalinfo();
                    $insert_personal->user_id = $user_id;
                    $insert_personal->language = $lang_list;
                    $insert_personal->insert();

                    $data[] = $insert_personal->language;
                    echo json_encode($data);
                }
                if (isset($_POST['language_access']) && !empty($_POST['language_access'])) {    
                    if (!empty($record_setting)) {
                        $record_setting->language_access = $_POST['language_access'];
                        $record_setting->update();
                    } else {
                        $insert_setting = new UserSetting();
                        $insert_setting->user_id = $user_id;
                        $insert_setting->language_access = $_POST['language_access'];
                        $insert_setting->insert();
                    }
                }
            }


            if (isset($_POST['Personalinfo']['religion']) && !empty($_POST['Personalinfo']['religion'])) {
                if (!empty($record_personal)) {
                    $record_personal->religion = $_POST['Personalinfo']['religion'];
                    $record_personal->update();

                    $data[] = $record_personal->religion;
                    echo json_encode($data);
                } else {
                    $insert_personal = new Personalinfo();
                    $insert_personal->user_id = $user_id;
                    $insert_personal->religion =$_POST['Personalinfo']['religion'];
                    $insert_personal->insert();

                    $data[] = $record_personal->religion;
                    echo json_encode($data);
                }

                if (!empty($record_setting)) {
                    $record_setting->religion_access = $_POST['religion_access'];
                    $record_setting->update();
                } else {
                    $insert_setting = new UserSetting();
                    $insert_setting->user_id =$user_id;
                    $insert_setting->religion_access = $_POST['religion_access'];
                    $insert_setting->insert();
                }
            }
            if (isset($_POST['Personalinfo']['political_view']) && !empty($_POST['Personalinfo']['political_view'])) {

                if (!empty($record_personal)) {
                    $record_personal->political_view = $_POST['Personalinfo']['political_view'];
                    $record_personal->update();

                    $data[] = $record_personal->political_view;
                    echo json_encode($data);
                } else {
                    $insert_personal = new Personalinfo();
                    $insert_personal->user_id =
                            $user_id;
                    $insert_personal->political_view = $_POST['Personalinfo']['political_view'];
                    $insert_personal->insert();

                    $data[] = $insert_personal->political_view;
                    echo json_encode($data);
                }

                if (!empty($record_setting)) {
                    $record_setting->political_view_access = $_POST['political_view_access'];
                    $record_setting->update();
                } else {
                    $insert_setting = new UserSetting();
                    $insert_setting->user_id = $user_id;
                    $insert_setting->political_view_access = $_POST['political_view_access'];
                    $insert_setting->insert();
                }
            }

            if (isset($_POST['Personalinfo']['about']) && !empty($_POST['Personalinfo']['about'])) {
                if (!empty($record_personal)) {
                    $record_personal->about = $_POST['Personalinfo']['about'];
                    $record_personal->update();

                    $data[] = $record_personal->about;
                    echo json_encode($data);
                } else {
                    $insert_personal = new Personalinfo();
                    $insert_personal->user_id = $user_id;
                    $insert_personal->about = $_POST['Personalinfo']['about'];
                    $insert_personal->insert();

                    $data[] = $insert_personal->about;
                    echo json_encode($data);
                }
            }

            if (isset($_POST['Personalinfo']['education']) && !empty($_POST['Personalinfo']['education'])) {
                
                $record_education = ArrayHelper::map(Education::find()->all(), 'name', 'name');
               
                $edu =$_POST['Personalinfo']['education'];
                $edu_differance=array_diff($edu,$record_education);
                
                if(isset($edu_differance) && !empty($edu_differance)){
                    $insert_education = new Education();
                    foreach($edu_differance AS $edu_diff)                  
                    {
                        $insert_education->name = ucwords($edu_diff);
                        $insert_education->insert();
                    }                  
                }
                
                $edu_list =implode(",",$edu);
                if (!empty($record_personal)) {
                    
                    $record_personal->education =$edu_list;
                    $record_personal->update();

                    $data[] =$record_personal->education;
                    echo json_encode($data);
                } else {
                    $insert_personal = new Personalinfo();
                    $insert_personal->user_id =$user_id;
                    $insert_personal->education = $edu_list;
                    $insert_personal->insert();

                    $data[] = $insert_personal->education;
                    echo json_encode($data);
                }
            }

            if (isset($_POST['Personalinfo']['interests']) && !empty($_POST['Personalinfo']['interests'])) {
                
                $record_interests = ArrayHelper::map(Interests::find()->all(), 'name', 'name');
               
                $int = $_POST['Personalinfo']['interests'];
                $interests_differance=array_diff($int,$record_interests);
                
                if(isset($interests_differance) && !empty($interests_differance)){
                    $insert_interests = new Interests();
                    foreach($interests_differance AS $int_diff)                  
                    {
                        $insert_interests->name = ucwords($int_diff);
                        $insert_interests->insert();
                    }                  
                }
                
                $int_list = implode(",",$int);
                if (!empty($record_personal)) {
                    
                    $record_personal->interests = $int_list;
                    $record_personal->update();

                    $data[] = $record_personal->interests;
                    echo json_encode($data);
                } else {
                    $insert_personal = new Personalinfo();
                    $insert_personal->user_id = $user_id;
                    $insert_personal->interests = $int_list;
                    $insert_personal->insert();

                    $data[] = $insert_personal->interests;
                    echo json_encode($data);
                }
            }

            if (isset($_POST['Personalinfo']['occupation']) && !empty($_POST['Personalinfo']['occupation'])) {
             
                $record_occupation = ArrayHelper::map(Occupation::find()->all(), 'name', 'name');
               
                $ocu =$_POST['Personalinfo']['occupation'];
                $occupation_differance=array_diff($ocu,$record_occupation);
                
                if(isset($occupation_differance) && !empty($occupation_differance)){
                    $insert_occupation = new Occupation();
                    foreach($occupation_differance AS $ocu_diff)                  
                    {
                        $insert_occupation->name = ucwords($ocu_diff);
                        $insert_occupation->insert();
                    }                  
                }
                
                $ocu_list = implode(",",$ocu);
                if (!empty($record_personal)) {
                    
                    $record_personal->occupation = $ocu_list;
                    $record_personal->update();

                    $data[] = $record_personal->occupation;
                    echo json_encode($data);
                } else {
                    $insert_personal = new Personalinfo();
                    $insert_personal->user_id = $user_id;
                    $insert_personal->occupation = $ocu_list;
                    $insert_personal->insert();

                    $data[] = $insert_personal->occupation;
                    echo json_encode($data);
                }
            }
           
        } else {
            return $this->render('basicinfo',
                            [
                        'model' => $model,
                        'model1' => $model1,
                        'model2' => $model2,
            ]);
        }
    }
    
    public function actionSlider()
    {
            $model = new \frontend\models\Slider();
            $load = isset($_POST["load"]) ? $_POST["load"] : 1;
            $url = '../web/uploads/slider/';
            if(isset($_POST) && !empty($_POST)) {
                if(isset($_FILES["slider_image"]["name"]) && count($_FILES["slider_image"]["name"]) >0) {
                    $imgcount = count($_FILES["slider_image"]["name"]);
                    $img = '';
                    $slider = new \frontend\models\Slider();
                    $newData = array();
                    $date =  time();
                    
                    for ($i =0;$i < $imgcount;$i++) {
                    $rand = rand(0, 999999);
                        $name = $_FILES["slider_image"]["name"][$i];
                        
                        if($name != '') {
                            if($name != '') {
                                $tmp_name = $_FILES["slider_image"]["tmp_name"][$i];
                                if (isset($name) && $name != "") {
                                    move_uploaded_file($tmp_name, $url . $rand . $date . $name);
                                    $img = $rand . $date . $name;
                                } 
                                
                                if($load == 'edit') {
                                    $oimg = isset($_POST["oimg"]) ? $_POST["oimg"] : "";
                                    if($oimg != '') {
                                        if(unlink($url.$oimg)) {
                                            $slider = Slider::find()->where(['slider_image' => $oimg])->one();
                                            $slider->slider_image = $img;
                                            $slider->update();
                                        }
                                    }
                                } else {
                                    $slider->slider_image = $img;
                                    $slider->insert();
                                }
                                if ($load == 2 ) {
                                    $newData[] = $img;
                                } elseif($load == 'edit') {
                                    $newData[] = array($oimg => $img);
                                }
                            }
                        }
                    }
                }
                if($load == 1) {
                    $all_slider = Slider::find()->all();
                    $all_slider = ArrayHelper::map(Slider::find()->all(), 'slider_image', 'slider_image');
                    $newData = json_encode($all_slider);
                } elseif($load == 2 || $load == 'edit') {
                    $newData = json_encode($newData);
                }
                 return $newData;
                 exit;
            }
			if(isset($_GET['nm']) && !empty($_GET['nm'])) {
				$name = $_GET['nm'];
				
				$delete_image = new \frontend\models\Slider();
				$delete_image = Slider::find()->where(['slider_image' => $name])->one();
				if($delete_image->delete()){
                    unlink($url.$name);
					return "OK";
					exit();
				}
				exit();
				
			}	
        return $this->render('slider',
                            [
                        'model' => $model,
        ]);
    }
    
    
    public function actionCover()
    {
    $model = new \frontend\models\Cover();
        
        if (isset($_FILES) && !empty($_FILES)) {
 
    $imgcount = count($_FILES["Cover"]["name"]['cover_image']);
        $load = isset($_POST["load"]) ? $_POST["load"] : '1';
        $date =  time();
            $img = '';
            $cover = new Cover();
            $newData = array();
            for ($i =0;$i < $imgcount;$i++) {
                    $name = $_FILES["Cover"]["name"]['cover_image'][$i];
                
                if($name != '') {
                    $tmp_name = $_FILES["Cover"]["tmp_name"]['cover_image'][$i];

                   
                    if (isset($name) && $name != "") {
                            $url = '../web/uploads/cover/';
                            $urls = '/uploads/cover/';
                            move_uploaded_file($tmp_name, $url . $date . $name);
                            $img = $date . $name;
                            
                    } 
                    $cover->cover_image = $img;
                    $cover->insert();
                    if($load == 2) {
                        $newData[] = $img;
                        
                    }
                }
            }
           
            if($load == 1) {
                $all_cover = Cover::find()->all();
                $all_cover = ArrayHelper::map(Cover::find()->all(), 'cover_image', 'cover_image');
                
               
                $all_cover['status'] = true;
                $newData = json_encode($all_cover);
            }
            
            if($load == 2) {
                $newData = json_encode($newData);
            }
             return $newData;
             exit;
        }
        
        
        
        return $this->render('cover',
                            [
                        'model' => $model,
            ]);
        
        
        
    }

    public function actionProfilePicture()
    {

       $model = new \frontend\models\LoginForm();
       $post_model = new \frontend\models\PostForm();

       $model->scenario = 'profile_picture';

       if ($model->load(Yii::$app->request->post())) {

               $session = Yii::$app->session;
               $email = $session->get('email'); 
               $profile_picture = $_FILES['LoginForm']['name']['photo'];

               if(isset($_POST['web_cam_img']) && !empty($_POST['web_cam_img'])){

                       $test = $_POST['web_cam_img'];
                       $rest = array_pop(explode('/', $test));

                       $s3 = LoginForm::find()->where(['email' => $email])->one();

                       $s3->photo = $rest;
                       $s3->thumbnail = $rest;

                       $s3->update();

                       $url = Yii::$app->urlManager->createUrl(['site/profile-picture']);
                            Yii::$app->getResponse()->redirect($url); 

               }
               else{
                    if($model->validate()){

                            $chars = '0123456789';
                            $count = mb_strlen($chars);

                            for ($i = 0, $rand = ''; $i < 8; $i++) {
                            $index = rand(0, $count - 1);
                            $rand .= mb_substr($chars, $index, 1);
                            }

                            $profile_picture = $rand . $profile_picture;
                            $s3 = LoginForm::find()->where(['email' => $email])->one();

                       if(isset($s3['photo']) && !empty($s3['photo'])){

                               //unlink('profile/'.$s3['photo']);


                       }



                            $model->photo = UploadedFile::getInstance($model, 'photo');

                            $model->photo->saveAs('profile/'.$profile_picture);    


                            //$post_model->image = UploadedFile::getInstance($post_model, 'image');
                            //$post_model->image->saveAs('uploads/'.$profile_picture);
                            $data = str_replace('data:image/png;base64,', '', $_POST['imagevalue']);
                            $data = str_replace(' ', '+', $data);
                            $data = base64_decode($data);
                            $file = 'profile/thumb_'.$profile_picture;
                            $success = file_put_contents($file, $data);

                            $s3->thumbnail = 'thumb_'.$profile_picture;
                            $s3->photo = $profile_picture;
                            $s3->update();


                            $date = time();
                            $post = new PostForm();
                            $post->post_status = '1';
                            $post->post_type = 'profilepic';
                            $post->is_deleted = '0';

                            /*$secureity_model = new SecuritySetting();
                            $result_security = SecuritySetting::find()->where(['user_id' => (string)$s3['_id']])->one();
                            if($result_security){
                                     $post_status = $result_security['my_post_view_status'];
                            }
                            else
                            {
                                    $post_status = 'Public';
                            }*/

                            $post->post_privacy = 'Public';
                            $post->image = 'thumb_'.$profile_picture;
                            $post->post_created_date = "$date";
                            $post->post_user_id = (string)$s3['_id'];
                            $post->insert();

                            $url = Yii::$app->urlManager->createUrl(['site/profile-picture']);
                            Yii::$app->getResponse()->redirect($url); 
                    }
                    else{

                            return $this->render('profile_picture', [
                               'model' => $model,
                       ]);
                    }
               }
       }
       else
       {
                    return $this->render('profile_picture', [
                               'model' => $model,
                       ]);
       }
    }
    
    function ak_img_thumb($target, $newcopy, $w, $h, $ext) {
        list($w_orig, $h_orig) = getimagesize($target);
        $src_x = ($w_orig / 2) - ($w / 2);
        $src_y = ($h_orig / 2) - ($h / 2);
        $ext = strtolower($ext);
        $img = "";
        if ($ext == "gif"){ 
        $img = imagecreatefromgif($target);
        } else if($ext =="png"){ 
        $img = imagecreatefrompng($target);
        } else { 
        $img = imagecreatefromjpeg($target);
        }
        $tci = imagecreatetruecolor($w, $h);
        imagecopyresampled($tci, $img, 0, 0, $src_x, $src_y, $w, $h, $w, $h);
        if ($ext == "gif"){ 
            imagegif($tci, $newcopy);
        } else if($ext =="png"){ 
            imagepng($tci, $newcopy);
        } else { 
            imagejpeg($tci, $newcopy, 84);
        }
    }
    /* security Settings by markand */

    public function actionSecuritySetting() {
       
        $model =
                new \frontend\models\SecuritySetting();
        if ($model->load(Yii::$app->request->post()) && $model->security()) {
            $value =
                    $model->security();
            echo $value;
        } else {
            return $this->render('security_setting',
                            [
                        'model' => $model,
            ]);
        }
    }

    /* Blocking Settings by markand */

    public function actionBlocking() {
        $model =
                new \frontend\models\SecuritySetting();
        if ($model->load(Yii::$app->request->post()) && $model->blocking()) {
            $value =
                    $model->blocking();
            echo $value;
        } else {
            return $this->render('blocking',
                            [
                        'model' => $model,
            ]);
        }
    }

    public function actionNotificationSetting() {
        $model =
                new \frontend\models\NotificationSetting();
        if (isset($_POST) & !empty($_POST) && $model->notification()) {
            $value =
                    $model->notification();
            echo $value;
        } else {
            return $this->render('notification_setting',
                            [
                        'model' => $model,
            ]);
        }
    }

    /* public function actionLink()
      {
      echo "hi";
      die();

      } */

    public function actionCurl_fetch() {
        $session =
                Yii::$app->session;
        $user_id =
                (string) $session->get('user_id');

        $data =
                array();

        $url = trim($_POST['url']);
       
        // Assuming the above tags are at www.example.com

        $tags =
                get_meta_tags($url);

// Notice how the keys are all lowercase now, and
// how . was replaced by _ in the key.
        if (isset($tags['title']) && !empty($tags['title'])) {
            $data['title'] =
                    $tags['title'];
            $title =
                    $tags['title'];
        } else {

            function getBetween($url,
                    $start,
                    $end) {
                $r =
                        explode($start,
                        $url);
                if (isset($r[1])) {
                    $r =
                            explode($end,
                            $r[1]);
                    return $r[0];
                }
                return '';
            }

            $start = "www.";
            $end = ".";
            $title =getBetween($url,$start,$end);
            $data['title'] = $title;
            
            if($data['title'] == ''){
                $result = parse_url($url);
                $data['title'] =  $result['host'];
            }
        }
        if (isset($tags['description']) && !empty($tags['description'])) {
            $data['desc'] =
                    $tags['description'];
            $description =
                    $tags['description'];
        } else {
            $data['desc'] =
                    'Description';
            $description =
                    'Desc';
        }
        if (isset($tags['twitter:image']) && !empty($tags['twitter:image'])) {
            $data['image'] =
                    $tags['twitter:image'];
            $image =
                    $tags['twitter:image'];
        } else {
            $data['image'] =
                    "No Image";
            $image =
                    'default.png';
        }
        $data['url'] =
                $url;


        echo json_encode($data);
    }

    public function actionPhone() {

        $model =
                new LoginForm();
        $session =
                Yii::$app->session;
        $email =
                $session->get('email_id');


        if (!empty($_POST["phone"])) {
            $phone =
                    $_POST["phone"];
            $result =
                    LoginForm::find()->where(['phone' => $phone])->one();
            $count =
                    count($result);

            if ($count > 0) {
                $query =
                        LoginForm::find()->where(['phone' => $phone,
                            'email' => $email])->one();
                if (!empty($query)) {
                    echo "1";
                } else {
                    echo "0";
                }

                // echo "<span class='status-not-available' style='color: red;'> Phone Number Not Available.</span>";
            } else {
                echo "1";
                //echo "<span class='status-available' style='color: green;'> Phone Number Available.</span>";
            }
        }
    }
    
    public function actionPassword() {
        
        $model = new LoginForm();
        $session = Yii::$app->session;
        $email = $session->get('email');
        
            $result = LoginForm::find()->where(['email' => $email])->one();
            
            $password = $result['password'];
            
            $data = array();
            $data[] = $password;
            echo json_encode($data);
          
    }
    

    public function actionCheckLogin()
    {
    if (!empty($_POST["lemail"]) && !empty($_POST["lpassword"])) {
            
            $session = Yii::$app->session;
            $email = $session->set('email_id',$_POST['lemail']);
            
        $data = array();
        $lemail = $_POST["lemail"];
        $lpassword = $_POST["lpassword"];
        $model = new LoginForm();
        $query = LoginForm::find()->where(['email' => $lemail])->one();
        if ($query)
        {
            $querypass = LoginForm::find()->where(['email' => $lemail, 'password' => $lpassword])->one();
            if ($querypass)
            {
                            $querypassverify = LoginForm::find()->where(['email' => $lemail, 'password' => $lpassword])->one();
                            if ($querypassverify)
                            {
                                $data['value'] ='4';
                                echo json_encode($data);
                            }
                            else
                            {
                                $data['value'] ='5';
                                echo json_encode($data);
                            }
            }
            else
            {
                $data['value'] ='3';
                echo json_encode($data);
            }
        }
        else
        {
            $data['value'] ='2';
            echo json_encode($data);
        }
    }
    else
    {
        print true;
    }
    }

    public function actionCheckEmail() {
        if (isset($_POST["lemail"]) && !empty($_POST["lemail"])) {
            $lemail = $_POST["lemail"];
            $model = new LoginForm();
            $query = LoginForm::find()->where(['email' => $lemail])->one();
            if (!empty($query) && $query['status'] == '2') {
                print false;
            } else {
                if ($query) {
                    print true;
                } else {
                    print false;
                }
            }
        } else {
            print false;
        }
    }

    public function actionGetemail() {

        $model = new \frontend\models\LoginForm();
        $email = $_POST['search'];

        $eml_id = LoginForm::find()->where(['like','email',$email])->all();
        $listData = ArrayHelper::map($eml_id,'email','email');
        // echo "<pre>";print_r($listData); exit;
        //echo json_encode($listData);
        foreach ($listData as $email) {
            echo '
        <span class="display_box" align="left">
<span style="font-size:9px; color:#999999">"' . $email . '"</span></span>';
        }

        /* if(!empty($listData)) {
          ?>
          <ul id="country-list">
          <?php
          foreach($listData as $email) {
          ?>
          <li onClick="selectCountry('<?php echo $email; ?>');"><?php echo $email; ?></li>
          <?php } ?>
          </ul>
          <?php } */
    }

    public function actionDeletePost() {

        $session = Yii::$app->session;
        $post_user_id = $_POST['post_user_id'];
        $post_id = $_POST['pid'];
        $uid = $session->get('user_id');

        $delete = new PostForm();
        $date = time();
        $data = array();
        $delete = PostForm::find()->where(['_id' => $post_id])->one();

        $delete->is_deleted = '1';
        $delete->update();

        echo "1";
    }

    public function actionDeletePostComment() {
        if (!empty($_POST['comment_id']) && isset($_POST['comment_id'])) {
            $comment_id = (string) $_POST["comment_id"];
            $session = Yii::$app->session;
            $user_id = (string) $session->get('user_id');
            $model = new Comment();
            $query = Comment::find()->where(['_id' => $comment_id])->one();
            if ($query) {
                $deletecomment = new Comment();
                $deletecomment = Comment::find()->where(['_id' => $comment_id])->one();

                $deletecomment->status = '0';
                if ($deletecomment->update()) {
                    //print true;
                  //  print_r($deletecomment);echo $deletecomment->post_id;exit;
                     $totalcomment = Comment::find()->where(['post_id' => "$deletecomment->post_id",'status' => '1'])->all();
                     $data['ctr'] = count($totalcomment);
                     $data['post_id'] = "$deletecomment->post_id";
                     echo json_encode($data);
                } else {
                    print false;
                }
            } else {
                print false;
            }
        } else {
            print false;
        }
    }

    public function actionHidePostComment() {
        //echo '<pre>';print_r($_POST);exit;
        if (isset($_POST['comment_id']) && !empty($_POST['comment_id'])) {
            $comment_id = (string) $_POST["comment_id"];
            $session = Yii::$app->session;
            $user_id = (string) $session->get('user_id');
            $hidecomment = new HideComment();
            $userexist = HideComment::find()->where(['user_id' => $user_id])->one();
            if ($userexist) {
                if (strstr($userexist['comment_ids'],
                                $comment_id)) {
                    print true;
                } else {
                    $hidecomment =
                            HideComment::find()->where(['user_id' => $user_id])->one();
                    $hidecomment->comment_ids =
                            $userexist['comment_ids'] . ',' . $comment_id;
                    if ($hidecomment->update()) {
                        print true;
                    } else {
                        print false;
                    }
                }
            } else {
                $hidecomment->user_id =
                        $user_id;
                $hidecomment->comment_ids =
                        $comment_id;
                if ($hidecomment->insert()) {
                    print true;
                } else {
                    print false;
                }
            }
        }
    }

    public function actionSearch() {
        $session =
                Yii::$app->session;
        $suserid = (string)$session->get('user_id');
        $model =
                new \frontend\models\LoginForm();
        if (isset($_GET['key']) && !empty($_GET['key'])) {
            $email = $_GET['key'];
            $eml_id = LoginForm::find()->where(['like','email',$email])
                    ->orwhere(['like','fname',$email])
                    ->orwhere(['like','lname',$email])
                    ->orwhere(['like','photo',$email])
                    ->orwhere(['like','phone',$email])
                    ->orwhere(['like','fullname',$email])
                    ->andwhere(['status'=>'1'])
                    ->orderBy([
                        $email => SORT_ASC
                        
                        
                    ])
                    ->all();


            $json =
                    array();

            $i =
                    0;
            if (!empty($eml_id)) {
                ?>
                <ul><?php
                foreach ($eml_id as
                        $val) {
                    $data =
                            array();
                    $data[] =
                            $val->fname;
                    $data[] =
                            $val->email;
                    $data[] =
                            $val->lname;
                    $data[] =
                            $val->photo;
                    $data[] =
                            (string) $val->_id;
                    $data[] =
                            $val->gender;
                    $guserid = (string)$val->_id;
                    $result_security = SecuritySetting::find()->where(['user_id' => $guserid])->one();
                    if($result_security)
                    {
                        $lookup_settings = $result_security['my_view_status'];
                    }
                    else
                    {
                        $lookup_settings = 'Public';
                    }
                    $is_friend = Friend::find()->where(['from_id' => $guserid,'to_id' => $suserid,'status' => '1'])->one();
                    if(($lookup_settings == 'Public') || ($lookup_settings == 'Friends' && ($is_friend || $guserid == $suserid)) || ($lookup_settings == 'Private' && $guserid == $suserid)) {
                    ?>
                        <li>
                            <a href="index.php?r=userwall%2Findex&id=<?= $val->_id ?>" class="search-rlink"><span class="display_box" align="left">
                                    <span class="img-holder">

                    <?php
                    $dp = $this->getimage($val['_id'],'thumb');
                    ?>
                                        <img src="<?= $dp?>" alt="">
                                    </span>
                                    <span class="desc-holder">

                                        <p><?php echo $val->fname; ?>&nbsp;<?php echo $val->lname; ?></p>
                                        <span><?php echo $val->city; ?></span>

                                    </span>
                                </span></a>    
                        </li>
                    <?php }
                    }
                    
                    ?><li class="morelink"><a href="<?= Url::to(['site/search-friend-list', 'name' => "$email"]); ?>">See More Friends</a></li></ul><?php
            } else {
                ?>
                <div class="noresult"><p>Sorry, No Result Found!</p></div>
                <?php
            }
        }
    }

    /**
     * Get all user who are not in freind list in decsending order
     * @param  null
     * @return array
     * @Author Sonali Patel
     * @Date   08-04-2016 (dd-mm-yyyy)
     */
    public function actionViewpeople() {
        $session =
                Yii::$app->session;
        $uid =
                $session->get('user_id');
        $model =
                new \frontend\models\LoginForm();
        if ($session->get('email')) {
            $model_friend =
                    new Friend();
            $friends =
                    $model_friend->userlist();
            return $this->render('viewpeople',
                            array(
                        'friends' => $friends));
        } else {
            return $this->render('index',
                            [
                        'model' => $model,
            ]);
        }
    }
    
    public function actionViewPost() {

        $session =Yii::$app->session;
        $uid = $session->get('user_id');
       
        
        $postid = $_GET['postid'];
        
        $session->set('postid',$postid);
        
      
        
        $model = new \frontend\models\LoginForm();
        if ($session->get('email')) {
            /*if(isset($_POST['postid']) && !empty($_POST['postid']))
            {
                $this->display_last_post($postid);
            }
            else
            {*/
                return $this->render('view_post');
            //}
        } else {
            return $this->render('index',
                            [
                        'model' => $model,
            ]);
        }
    }
    
    public function actionViewVisitors() {
        $session =Yii::$app->session;
        $uid = $session->get('user_id');
        
        $model = new \frontend\models\LoginForm();
        if ($session->get('email')) {
                return $this->render('view_visitors');
        } else {
            return $this->render('index',
                            [
                        'model' => $model,
            ]);
        }
    }
    
    public function actionPhotography() {
        $session =Yii::$app->session;
        $uid = $session->get('user_id');
        
        $model = new \frontend\models\LoginForm();
        if ($session->get('email')) {
                return $this->render('photography');
        } else {
            return $this->render('index',
                            [
                        'model' => $model,
            ]);
        }
    }
    
    public function actionUploadphotographypics() {
        $session =Yii::$app->session;
        $uid = (string)$session->get('user_id');
        
        $model = new \frontend\models\LoginForm();
        if ($session->get('email'))
        {
            //print_r($_POST);print_r($_FILES);
            $date = time();
            $data = array();
            $imgcontent = '';
            $post = new PhotoForm();
            $post->photo_type = 'image';
            $post->photo_status = '1';
            $post->photo_created_date = "$date";
            $post->is_deleted = '0';
            $post->photo_user_id = $uid;
            $post->photo_privacy = 'Public';
            if(isset($_FILES) && !empty($_FILES))
            {
                $imgcount = count($_FILES["imagephoto"]["name"]);
                $img = '';
                $im = '';
                for($i=0; $i<$imgcount; $i++)
                {
                    if(isset($_FILES["imagephoto"]["name"][$i]) && $_FILES["imagephoto"]["name"][$i] != "") 
                    {
                        $urls = '/photographypics/';
                        $user_pics = '../web/photographypics/'.$uid.'';
                        if (!file_exists($user_pics))
                        {
                            mkdir($user_pics, 0777, true);
                        }
                        if(move_uploaded_file($_FILES["imagephoto"]["tmp_name"][$i], $user_pics.'/'.$date.$_FILES["imagephoto"]["name"][$i]))
                        {
                            $img = $urls.$date.$_FILES["imagephoto"]["name"][$i].',';
                            $im = $im . $img;
                        }
                    }
                }
                $post->image = $im;
            }
            $post->insert();
            $last_insert_id = $post->_id;
            if($last_insert_id)
            {
                $getphotodetails = PhotoForm::find()->where(['_id' => $last_insert_id])->one();
                if(isset($getphotodetails['image']) && !empty($getphotodetails['image']))
                {
                    $eximgs = explode(',',$getphotodetails['image'],-1);
                    $total_eximgs = count($eximgs);
                    $imgcontent .= '<div class="album-row">';
                    $imgcontent .= '<div class="album-col">';
                    $imgcontent .= '<div class="album-holder">';
                    $imgcontent .= '<div class="album-box">';
                    $imgcontent .= '<a href="javascript:void(0)" class="popup-modal addpic-box">Add photos to this album</a>';
                    $imgcontent .= '</div>';
                    $imgcontent .= '</div>';
                    $imgcontent .= '</div>';
                    foreach ($eximgs as $eximg)
                    {
                        //$eximg = str_replace("/photographics/", "/photographics/'.$uid.'/'", $eximg);
                        $iname = $this->getimagefilename($eximg);
                        $var  = explode("/",$eximg);
                        $eximgs = '/'.$var[1].'/'.$uid.'/'.$var[2];
                        $imgpath = Yii::$app->getUrlManager()->getBaseUrl().$eximgs;
                        $imgcontent .= '<div class="album-col" id="imgbox_'.$iname.'">';
                        $imgcontent .= '<div class="album-holder">';
                        $imgcontent .= '<div class="album-box">';
                        $imgcontent .= '<a href="javascript:void(0)" class="listalbum-box"><img src="'.$imgpath.'" alt=""></a>';
                        $imgcontent .= '<span><a href="javascript:void(0)" class="popup-imgdel"><i class="fa fa-close"></i></a></span>';
                        $imgcontent .= '</div>';
                        $imgcontent .= '</div>';
                        $imgcontent .= '</div>';
                    }
                    $imgcontent .= '</div>';
                }
                
                $data['previewphotographyimages'] = $imgcontent;
                $data['value'] = '1';
                echo json_encode($data);
            }
        }
        else
        {
            return $this->render('index',
                            [
                        'model' => $model,
            ]);
        }
    }
    
    public function actionSearchFriendList() {

        $session =Yii::$app->session;
        $uid = $session->get('user_id');
       
        
        $email = $_GET['name'];
        
        $session->set('name',$email);
        
      
        
        $model = new \frontend\models\LoginForm();
        if ($session->get('email')) {
            $model_friend = new Friend();
            $friends = $model_friend->searchfriend();
            return $this->render('search_friend_list',
                            array(
                        'friends' => $friends));
        } else {
            return $this->render('index',
                            [
                        'model' => $model,
            ]);
        }
    }

    public function actionCoverPhoto() {

        $model =
                new \frontend\models\LoginForm();
        $post_model =
                new \frontend\models\PostForm();

        // $model->scenario = 'profile_picture';

        if ($model->load(Yii::$app->request->post())) {

            $session =
                    Yii::$app->session;
            $email =
                    $session->get('email');
            $cover_photo =
                    $_FILES['LoginForm']['name']['cover_photo'];
            /*
              if(isset($_POST['web_cam_img']) && !empty($_POST['web_cam_img'])){

              $test = $_POST['web_cam_img'];
              $rest = array_pop(explode('/', $test));

              $s3 = LoginForm::find()->where(['email' => $email])->one();

              $s3->photo = $rest;

              $s3->update();

              $url = Yii::$app->urlManager->createUrl(['site/profile-picture']);
              Yii::$app->getResponse()->redirect($url);

              } */
            //else{


            $chars =
                    '0123456789';
            $count =
                    mb_strlen($chars);

            for ($i =
            0, $rand =
            '';
                    $i < 8;
                    $i++) {
                $index =
                        rand(0,
                        $count - 1);
                $rand .= mb_substr($chars,
                        $index,
                        1);
            }

            $cover_photo =
                    $rand . $cover_photo;
            $s3 =
                    LoginForm::find()->where(['email' => $email])->one();





            $model->cover_photo =
                    UploadedFile::getInstance($model,
                            'cover_photo');

            $model->cover_photo->saveAs('profile/' . $cover_photo);


            //$post_model->image = UploadedFile::getInstance($post_model, 'image');
            //$post_model->image->saveAs('uploads/'.$profile_picture);

            $s3->cover_photo =
                    $cover_photo;
            $s3->update();


            $date =
                    time();
            $post =
                    new PostForm();
            $post->post_status =
                    '1';
            $post->post_type =
                    'image';
            $post->is_deleted =
                    '0';

            /*$secureity_model =
                    new SecuritySetting();
            $result_security =
                    SecuritySetting::find()->where(['user_id' => (string) $s3['_id']])->one();
            if ($result_security) {
                $post_status =
                        $result_security['my_post_view_status'];
            } else {
                $post_status =
                        'Public';
            }*/

            $post->post_privacy =
                    'Public';
            $post->image =
                    $cover_photo;
            $post->post_created_date =
                    "$date";
            $post->post_user_id =
                    (string) $s3['_id'];
            $post->is_coverpic = '1';
            $post->insert();

            $url =
                    Yii::$app->urlManager->createUrl(['site/cover-photo']);
            Yii::$app->getResponse()->redirect($url);

            /*
              else{

              return $this->render('cover_photo', [
              'model' => $model,
              ]);
              } */
            //}
        } else {
            return $this->render('cover_photo',
                            [
                        'model' => $model,
            ]);
        }
    }

    public function actionIsdCode() {
        $model =
                new LoginForm();
        $session =
                Yii::$app->session;
        $email =
                $session->get('email_id');


        if (!empty($_POST["country"])) {
            $country =
                    strtoupper($_POST["country"]);
            $result =
                    CountryCode::find()->where(['country_name' => $country])->one();
            $result['isd_code'];


            if (!empty($result)) {
                $isd_code =
                        $result['isd_code'];
                echo $isd_code;
            }
        }
    }
	
	public function actionThemeChange() {
		
        $model = new UserSetting();
        $session = Yii::$app->session;
        $user_id = (string) $session->get('user_id');
		
	    $color = $_GET['color'];
		
		$userSetting = new UserSetting();
		
		$userSetting = UserSetting::find()->where(['user_id' => $user_id])->one();
		/*
		echo "<pre>";
		print_r($userSetting);
		die(); */
		if(!empty($userSetting)){
			
			$userSetting->user_theme = $color;
			if($userSetting->update()){
				echo "1";
				exit();
			}
			else{
				echo "0";
				exit();
			}
		}
		else{
			$userSetting2 = new UserSetting();
			$userSetting2->user_id =$user_id;
			$userSetting2->user_theme =$color;
			if($userSetting2->insert()){
				echo "2";
				exit();
			}
			else{
				echo "3";
				exit();
			}
			
		}
		
    }
    
    public function actionApprove()
    {
        $session = Yii::$app->session;
        $user_id = (string) $session->get('user_id');
        $post_id = $_POST['post_id'];
        $ntype = $_POST['ntype'];

        $notification = new Notification();
        if($ntype == 'timeline') {$fname = 'share_id';$ftype = 'sharepost';}
        elseif ($ntype == 'tagfriend') {$fname = 'post_id';$ftype = 'tag_friend';}
        
        $notification = Notification::find()->where(['notification_type' => $ftype,'user_id' => $user_id,$fname => $post_id])->one();

        if(!empty($notification))
        {
            $notification->review_setting = 'Disabled';
            if($notification->update())
            {
                echo "1";
                exit();
            }
            else
            {
                echo "0";
                exit();
            }
        }
        else
        {
            echo "0";
            exit();
        }
    }
    
     public function actionApprovetags()
    {
        $session = Yii::$app->session;
        $user_id = (string) $session->get('user_id');
        $post_id = $_POST['post_id'];
        

        $notification = new Notification();
        
        $post = PostForm::find()->where(['is_deleted' => '1','shared_from' => $user_id,'_id' => (string)$post_id])->one();

        $notification = Notification::find()->where(['notification_type' => 'tag_friend','is_deleted' => '1','post_id' => (string)$post_id])->one();

        if(!empty($notification) && !empty($post))
        {
            $notification->is_deleted = '0';
            $post->is_deleted = '0';
            if($notification->update() && $post->update())
            {
                echo "1";
                exit();
            }
            else
            {
                echo "0";
                exit();
            }
        }
        else
        {
            echo "0";
            exit();
        }
    }
	
}
