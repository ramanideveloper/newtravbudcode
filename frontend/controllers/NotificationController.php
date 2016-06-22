<?php
namespace frontend\controllers;

use Yii;

use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use frontend\models\LoginForm;
use frontend\models\Like;
use frontend\models\HidePost;
use frontend\models\SavePost;
use frontend\models\Notification;
use yii\helpers\Url;
/**
 * Like controller
 */
class NotificationController extends Controller
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
    
    
   public function actionNewNotification()
    { 
       $budge = 0;
       $model_notification = new Notification();
       $budge = $model_notification->getUserPostBudge();
       $notifications = $model_notification->getAllNotification();
       $session = Yii::$app->session;
        $userid = (string)$session->get('user_id');
       ?>

<input type="hidden" name="new_budge" id= "new_budge" value="<?php echo $budge;?>"/>
          
						<div class="list-title">
							Notifications
						</div>
						<ul class="not-list">
                                                    
							
							<?php if(!empty($notifications) && count($notifications)>0){?>
						   <?php  foreach($notifications as $notification){ 
                                                     
                                                       $notification_time = Yii::$app->EphocTime->time_elapsed_A(time(),$notification['updated_date']);
							 ?>
                                                        <li>
								<div class="not-holder">
									<div class="descbox">
										<div class="img-holder">
											<?php
                                                                                        if($notification['notification_type'] == 'sharepost')
                                                                                        {
                                                                                            $not_img = $this->getimage($notification['shared_by'],'thumb');
                                                                                        }
                                                                                        else
                                                                                        {
                                                                                            $not_img = $this->getimage($notification['user']['_id'],'thumb');
                                                                                        }
                                                                                    ?>
                                                                                            <img src="<?= $not_img?>" class="img-responsive">
										</div>
										<div class="desc-holder">									
											<div class="desc">
												<p>
<?php
if(isset($notification['post']['post_text']) && !empty($notification['post']['post_text'])) {
if(strlen($notification['post']['post_text']) > 20){
	$notification['post']['post_text'] = substr($notification['post']['post_text'],0,20);
	$notification['post']['post_text'] = substr($notification['post']['post_text'], 0, strrpos($notification['post']['post_text'], ' '));
}
else{
	$notification['post']['post_text'] = $notification['post']['post_text'];
}
}
if(empty($notification['post']['post_text']) && $notification['notification_type'] != 'likereply') {
	if($notification['notification_type']!='friendrequestaccepted' && $notification['notification_type']!='friendrequestdenied')
	{
	 $notification['post']['post_text'] = 'View Post';
	}
}
if($notification['notification_type'] == 'tag_friend')
{
    $name = 'You';
}
else if($notification['notification_type'] == 'sharepost')
{
    $usershare = LoginForm::find()->where(['_id' => $notification['user_id']])->one();
    $usershare_id = $usershare['_id'];
    if($notification['user_id'] == $userid){$user_name = 'Your';}else{ $user_name = $usershare['fullname']; }
	
    $post_owner_id = LoginForm::find()->where(['_id' => $notification['post_owner_id']])->one();
    $post_owner_id_name_id = $post_owner_id['_id'];
    if($notification['post_owner_id'] == $userid){$post_owner_id_name = 'Your';}else{ $post_owner_id_name = $post_owner_id['fullname'].'\'s'; }

    $shared_by = LoginForm::find()->where(['_id' => $notification['shared_by']])->one();
    $shared_by_name_id = $shared_by['_id'];
    if($notification['shared_by'] == $userid){$shared_by_name = 'You';}else{ $shared_by_name = $shared_by['fullname']; }

    //$name = $shared_by_name.' Shared '.$post_owner_id_name.' Post on '.$user_name.' Wall: ';
    $name = "";
    $name .= "<a class='no-ul' href='";
    $name .= Url::to(['userwall/index', 'id' => "$shared_by_name_id"]);
    $name .= "'>";
    $name .= $shared_by_name;
    $name .= "</a> Shared ";
    $name .= "<a class='no-ul' href='";
    $name .= Url::to(['userwall/index', 'id' => "$post_owner_id_name_id"]);
    $name .= "'>";
    $name .= $post_owner_id_name;
    $name .= "</a> Post on ";
    $name .= "<a class='no-ul' href='";
    $name .= Url::to(['userwall/index', 'id' => "$usershare_id"]);
    $name .= "'>";
    $name .= $user_name;
    $name .= "</a> Wall: ";
}
else
{
    $name = ucfirst($notification['user']['fname']).' '.ucfirst($notification['user']['lname']);
}
?>												
<?php if($notification['notification_type'] != 'sharepost') { ?>											
<a href="<?php $nuid = $notification['user']['_id']; echo Url::to(['userwall/index', 'id' => "$nuid"]); ?>" class="name-l"><?php echo $name;?></a>
<?php } ?>
<?php if($notification['notification_type']=='likepost' || $notification['notification_type']== 'like'){ ?> Likes your post: <a href="<?php $npostid = $notification['post_id']; echo Url::to(['site/view-post', 'postid' => "$npostid"]); ?>"><?php echo $notification['post']['post_text'];?></a>
<?php } 
else if($notification['notification_type']=='likecomment'){ ?> Likes your comment: <a href="<?php $npostid = $notification['post_id']; echo Url::to(['site/view-post', 'postid' => "$npostid"]); ?>">View Post</a>
<?php } 
else if($notification['notification_type'] == 'sharepost'){ ?> <?php echo $name;?><a href="<?php $npostid = $notification['share_id']; echo Url::to(['site/view-post', 'postid' => "$npostid"]); ?>"><?php echo $notification['post']['post_text'];?></a>
<?php }  else if($notification['notification_type'] == 'comment'){
	?> Commented on your post:  <a href="<?php $npostid = $notification['post_id']; echo Url::to(['site/view-post', 'postid' => "$npostid"]); ?>">
	<?php echo $notification['post']['post_text'];?></a>
<?php } else if($notification['notification_type'] == 'tag_friend'){
	?> Tagged in the post:  <a href="<?php $npostid = $notification['post_id']; echo Url::to(['site/view-post', 'postid' => "$npostid"]); ?>">
	<?php echo $notification['post']['post_text'];?></a>
<?php }
 else if($notification['notification_type'] == 'post'){ ?> Added new post:  <a href="<?php $npostid = $notification['post_id']; echo Url::to(['site/view-post', 'postid' => "$npostid"]); ?>"><?php 

echo $notification['post']['post_text'];?></a>
<?php } else if($notification['notification_type'] == 'commentreply'){ ?> Replied on your comment:  <a href="<?php $npostid = $notification['post_id']; echo Url::to(['site/view-post', 'postid' => "$npostid"]); ?>"><?php echo $notification['post']['post_text'];?></a>
<?php } else if($notification['notification_type'] == 'friendrequestaccepted'){ ?> Accepted your friend request.
<?php } else if($notification['notification_type'] == 'friendrequestdenied'){ ?> Denied your friend request.
<?php } else{ ?> Likes post<?php } ?>
												
                                                                                                </p>
												<span class="timestamp">
                                                                                                        <?php if($notification['notification_type']=='likepost' || $notification['notification_type']== 'like'){ ?><i class="fa fa-thumbs-up"></i><?php } else if($notification['notification_type']== 'comment') {?> <i class="fa fa-comment"></i> <?php }else if($notification['notification_type'] == 'sharepost'){ ?><i class="fa fa-share-alt"></i> <?php }else { ?><i class="fa fa-globe"></i> <?php }?><?php echo $notification_time;?>
												</span>
											</div>										
										</div>
									</div>							
								</div>
							</li>
                                                         <?php }
                                                  }else {?>  <div class="no-listcontent">
								No new notifications
							  </div> <?php } ?></ul>
						
					           <?php
    }
    public function actionViewNotification()
    { 
        $session = Yii::$app->session;
        $uid = $session->get('user_id');
       
        $date = time();
        $data = array();
        $user_data = LoginForm::find()->where(['_id' => "$uid"])->one();
               
               
        if(!empty($user_data))
        {
            $date = time();
          
            $user_data->last_login_time = "$date";
            $user_data->update();
                   
           
           
        }
       
        echo 'done';
    }
   
        
}
