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
use frontend\models\Notification;
use frontend\models\Friend;
use frontend\models\UserForm;
/**
 * Site controller
 */
class FriendController extends Controller
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
     /**
     * Add freind request
     *
     * @param Ajax call 
     * @return last inserted id
     */
    public function actionAddFriend()
    {
        $friend = new Friend();
        $date = time();
        $requestcheckone = Friend::find()->where(['from_id' => $_POST['from_id'] , 'to_id' => $_POST['to_id']])->one();
        $requestchecktwo = Friend::find()->where(['to_id' => $_POST['from_id'] , 'from_id' => $_POST['to_id']])->one();
        if(!$requestcheckone && !$requestchecktwo)
        {
            $friend->from_id = $_POST['from_id'];
            $friend->to_id = $_POST['to_id'];
            $friend->action_user_id = $_POST['from_id'];
            $friend->status = '0';
            $friend->created_date = $date;
            $friend->updated_date = $date;
            $friend->insert();
            if($friend->_id != '')
            {
                $friend1 = new Friend();
                $date = time();
                $friend1->from_id = $_POST['to_id'];
                $friend1->to_id = $_POST['from_id'];
                $friend1->action_user_id = $_POST['from_id'];
                $friend1->status = '0';
                $friend1->created_date = $date;
                $friend1->updated_date = $date;
                $friend1->insert();

            echo  'Freind request sent..!';
            }
        }
        else
        {
            $session = Yii::$app->session;
            $uid = (string)$session->get('user_id');
            if($requestcheckone && ($uid == $requestcheckone['action_user_id']))
            {
                echo  'Request already sent..!';
            }
            else
            {
                echo  'Accept Friend Request..!';
            }
        }
    }
    
     /**
     * Accept freind request
     *
     * @param Ajax call 
     * @return last updated id
     */
    public function actionAcceptFriend()
    {
        $friend = new Friend();
      
        $request = Friend::find()->where(['from_id' => $_POST['from_id'] , 'to_id' => $_POST['to_id']])->one();
             
        if(!empty($request))
        {
            $date = time();
            $request->action_user_id = $_POST['to_id'];
            $request->status = '1';
            $request->updated_date = $date;
            $request->update();
     
        }
        
        $request_second = Friend::find()->where(['from_id' => $_POST['to_id'] , 'to_id' => $_POST['from_id']])->one();
             
        if(!empty($request_second))
        {
            $date = time();
            $request_second->action_user_id = $_POST['to_id'];
            $request_second->status = '1';
            $request_second->updated_date = $date;
            $request_second->update();
            echo  'Freind request accepted..!';
         
        }
        
        if(!empty($request) && !empty($request_second))
        {
            // Insert record in notification table also
            $notification =  new Notification();
            $notification->from_friend_id =   $_POST['from_id'];
            $notification->user_id = $_POST['to_id'];
           
            $notification->notification_type = 'friendrequestaccepted';
            $notification->is_deleted = '0';
            $notification->status = '1';
            $notification->created_date = "$date";
            $notification->updated_date = "$date";
            $notification->insert();
        }

    }
   /**
     * Ajax call - Delete friend request from friends (we are insert two records or freinds so delelte both of them here)
     * @param  null
     * @return array
     * @Author Sonali Patel
     * @Date   08-04-2016 (dd-mm-yyyy)
     */  
    public function actionDeleteRequest()
    {
        $request_first = Friend::find()->where(['from_id' => $_POST['from_id'] , 'to_id' => $_POST['to_id']])->one();
        
        if(count($request_first)>0)
            $request_first->delete();
        
        $request_second = Friend::find()->where(['from_id' => $_POST['to_id'] , 'to_id' => $_POST['from_id']])->one();
        if(count($request_second)>0)
            $request_second->delete();
        
        $date = time();
        // Insert record in notification table also
        $notification =  new Notification();
        $notification->from_friend_id =   $_POST['from_id'];
        $notification->user_id = $_POST['to_id'];

        $notification->notification_type = 'friendrequestdenied';
        $notification->is_deleted = '0';
        $notification->status = '1';
        $notification->created_date = "$date";
        $notification->updated_date = "$date";
        $notification->insert();
        
    }
    /**
     * Ajax call - Send invitatio for using travbud site using friends email address 
     * @param  null
     * @return array
     * @Author Sonali Patel
     * @Date   11-04-2016 (dd-mm-yyyy)
     */  
    public function actionSendInvitation()
    {
        $email = isset($_POST['friend_email'])?$_POST['friend_email']:'';
        $session = Yii::$app->session;
        $uid = $session->get('user_id');
        $user_exists = UserForm::find()->where(['email' =>trim($email)])->one();
         $friend = new Friend();
        if(empty($user_exists)) 
        {
            $date = time();
            $user = new UserForm();
            $user->email = $email;
            $user->status = '2';
            $user->created_date = $date;
            $user->updated_date = $date;
            $user->reference_user_id = (string)$uid;
            $user->insert();
            $new_user_id = $user->_id;
            $request_second = UserForm::find()->where(['_id' =>"$new_user_id"])->one();
       
            $friend->from_id = (string)$uid;
            $friend->to_id = (string)$new_user_id;
            $friend->action_user_id = (string)$uid;
            $friend->status = '1';
            $friend->created_date = $date;
            $friend->updated_date = $date;
            $friend->insert();
            if($friend->_id != '')
            {
                $friend1 = new Friend();
                $date = time();
                $friend1->from_id = (string)$new_user_id;
                $friend1->to_id = (string)$uid;
                $friend1->action_user_id = (string)$uid;
                $friend1->status = '1';
                $friend1->created_date = $date;
                $friend1->updated_date = $date;
                $friend1->insert();

            
            }
            //echo $request_second->_id;
            echo '1';
           $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
           $resetlink = substr($actual_link, 0, strpos($actual_link, "?")).'?id='.$new_user_id;
           
          // exit;
           
             try {
                                            $send = Yii::$app->mailer->compose()
                                            ->setFrom('no-reply@travbud.com')
                                            ->setTo($email)
                                            ->setSubject('TravBud - Somebody send invitation to use travbud site.')
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
                                                    <div style="color: #333;font-size: 13px;margin: 0 0 20px;">Hello,</div>
                                                    <div style="color: #333;font-size: 13px;">Your friend requested you to use travbud site.</div>
                                                    <div style="color: #333;font-size: 13px;margin: 0 0 20px;">To complete signup process, <a href="'.$resetlink.'" target="_blank">click here</a>or paste the following link into your browser: '.$resetlink.'</div>
                                                    <div style="color: #333;font-size: 13px;">Thanks for using Travbud!</div>
                                                    <div style="color: #333;font-size: 13px;">The Travbud Team</div>
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
                   // print true;
                } 
                catch (ErrorException $e) 
                {
                    echo 'Caught exception: ',  $e->getMessage(), "\n";
                }
        }
        else
        {
            echo '0';
        }
    }
    
   
}
