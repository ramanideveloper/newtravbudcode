<?php
/* @var $this \yii\web\View */
/* @var $content string */
use yii\helpers\Html;
use frontend\assets\AppAsset;
use frontend\models\UserForm;
use frontend\models\PostForm;
use frontend\models\Friend;
use frontend\models\Notification;
use frontend\models\NotificationSetting;
use yii\widgets\ActiveForm;
use yii\mongodb\ActiveRecord;
use frontend\models\SecuritySetting;
use frontend\models\UserSetting;
use frontend\models\LoginForm;
use frontend\models\BookmarkForm;
use yii\helpers\Url;
use frontend\models\Like;
use common\components\CDbCriteria;

$baseUrl = Yii::getAlias('@web');

$session = Yii::$app->session;
$email = $session->get('email'); 
$user_id = $session->get('user_id');

$result = UserForm::find()->where(['email' => $email])->one();

$user = LoginForm::find()->where(['email' => $email])->one();

//$status = $user['status'];
$session->set('status',$user['status']);
$status = $session->get('status');

$userid = (string) $user['_id'];

$model_friend = new Friend();

$model_post = new PostForm();

$model_notification = new Notification();

$init_friends = $model_friend->userlistFirstfive();

$request_budge = $model_friend->friendRequestbadge();

$pending_requests = $model_friend->friendPendingRequests();


//$notification_budge = $model_post->getUserPostBudge();

$notification_budge = $model_notification->getUserPostBudge();

//$notifications = $model_post->getUserNotifications();

$notifications = $model_notification->getAllNotification();

$bookmarks = BookmarkForm::find()->where(['user_id' => $userid,'is_deleted' => '0'])->orderBy(['modified_date'=>SORT_DESC])->all();

$result_security = SecuritySetting::find()->where(['user_id' => $user_id])->one();
if($result_security)
{
    $my_post_view_status = $result_security['my_post_view_status'];
    if($my_post_view_status == 'Private') {$post_dropdown_class = 'lock';}
    else if($my_post_view_status == 'Friends') {$post_dropdown_class = 'user';}
    else {$my_post_view_status = 'Public'; $post_dropdown_class = 'globe';}
}
else
{
    $my_post_view_status = 'Public';
    $post_dropdown_class = 'globe';
}

$userSetting = UserSetting::find()->where(['user_id' => (string)$user_id])->one();
$theme_color = $userSetting['user_theme'];

$notification_settings = NotificationSetting::find()->where(['user_id' => (string)$user_id])->one();
if(!$notification_settings || $notification_settings['sound_on_notification'] == 'Yes')
{
?>
    <input type="hidden" id="notification_sound" value="Yes">
<?php
}
else
{
?>
    <input type="hidden" id="notification_sound" value="No">
<?php
}
?>

<?php $this->beginBody() ?>

<audio id="soundplay">
    <source src="sounds/new_notification.mp3" type="audio/mpeg">
</audio>

<script>
$( window ).load(function() {
var clsnm = "<?php echo $theme_color; ?>";
if(clsnm != '') {
	$("body").removeClass();
	$("body").addClass(clsnm);
	$("body").addClass('loaded');
} 
});
</script>

<div class="header-themebar">
                <div class="logo-holder">
                    <a href="<?php echo Yii::$app->urlManager->createUrl(['site/index3']); ?>" class="desk-logo"><img src="<?= $baseUrl ?>/images/white-logo.png"/></a>
                    <a href="<?php echo Yii::$app->urlManager->createUrl(['site/index3']); ?>" class="mbl-logo"><img src="<?= $baseUrl ?>/images/mobile-logo.png"/></a>
                </div>
                <div class="page-name">Travfeed</div>
                
                <div class="logout">                
                    <a href="javascript:openBootstrapPopup('logout');"><img src="<?= $baseUrl ?>/images/logout-icon.png"/></a>          
                </div>
                
                <div class="top-plus">
                    <div class="dropdown dropdown-custom ">
                        <a href="javascript:void(0)" class="dropdown-toggle"  data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><img src="<?= $baseUrl ?>/images/plus-icon.png"/></a>
                        <ul class="dropdown-menu">
                            <li><a href="javascript:void(0)"><span class="glyphicon glyphicon-plus"></span>Add Photos</a></li>                  
                            <li><a href="javascript:void(0)"><span class="glyphicon glyphicon-comment"></span>Share Your Openion</a></li>
                            <li><a href="javascript:void(0)"><span class="glyphicon glyphicon-pencil"></span>Write A Tip</a></li>
                            <li><a href="javascript:void(0)"><span class="glyphicon glyphicon-plane"></span>Create A Trip</a></li>
                            <li><a href="javascript:void(0)"><span class="glyphicon glyphicon-question-sign"></span>Ask A Question</a></li>
                            <li><a href="javascript:void(0)"><span class="glyphicon glyphicon-save"></span>Saved Post</a></li>
                          </ul>
                    </div>
                </div>
                
                <div class="profile-top">
                    <a href="javascript:void(0)" class="profile-info">
                        <img src="<?= $baseUrl ?>/images/demo-profile.jpg"/>
                        <span class="user-name">Nimish</span>
                    </a>
                    <div class="dropdown dropdown-custom ">
                        <a href="javascript:void(0)" class="dropdown-toggle"  data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><img src="<?= $baseUrl ?>/images/profile-menu.png"/></a>
                        <ul class="dropdown-menu">
                            <li><a href="javascript:void(0)"><span class="glyphicon glyphicon-cog"></span>Account Settings</a></li>                 
                            <li><a href="javascript:void(0)"><span class="glyphicon glyphicon-user"></span>VIP Member</a></li>                                      
                            <li><a href="javascript:void(0)"><span class="glyphicon glyphicon-star"></span>Credits</a></li>                 
                            <li><a href="javascript:void(0)"><span class="glyphicon glyphicon-usd"></span>Donate</a></li>
                            <li><a href="javascript:void(0)"><span class="glyphicon glyphicon-saved"></span>Verification</a></li>
                            <li><a href="javascript:void(0)"><span class="glyphicon glyphicon-certificate"></span>Advertising Manager</a></li>
                            <li><a href="javascript:void(0)"><span class="glyphicon glyphicon-lock"></span>Security Settings</a></li>
                            <li><a href="javascript:void(0)"><span class="glyphicon glyphicon-edit"></span>Billing Information</a></li>
                            <li><a href="javascript:openBootstrapPopup('logout');"><span class="glyphicon glyphicon-log-out"></span>Logout</a></li>
                          </ul>
                    </div>
                </div>
                
                <div class="not-icons desktop">
                    <div class="not-friends noticon">               
                        <div class="dropdown dropdown-custom ">
                            <a href="javascript:void(0)" class="dropdown-toggle"  data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                <img src="<?= $baseUrl ?>/images/noticon-friend.png"/><span class="badge">3</span>
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <div class="fr-list">
                                        <span class="not-title">Friend Requests</span>
                                        <ul class="fr-listing">
                                            <li>
                                               <form>                                               
                                                    <div class="fr-holder">
                                                        <div class="img-holder">
                                                            <a href="javascript:void(0)"><img class="img-responsive" src="<?= $baseUrl ?>/images/female.png"></a>
                                                        </div>
                                                        <div class="desc-holder">
                                                            <div class="desc">
                                                                <a href="javascript:void(0)">Abc Def</a>
                                                                <span class="mf-info"></span>
                                                            </div>
                                                            <div class="fr-btn-holder">
                                                                <button class="btn btn-primary btn-sm">Confirm</button>
                                                                <button class="btn btn-primary btn-sm">Delete Reqeust</button>
                                                            </div>
                                                        </div>                                                          
                                                    </div>
                                               </form>
                                            </li>
                                            <li>
                                               <form>                                               
                                                    <div class="fr-holder">
                                                        <div class="img-holder">
                                                            <a href="javascript:void(0)"><img class="img-responsive" src="<?= $baseUrl ?>/images/male.png"></a>
                                                        </div>
                                                        <div class="desc-holder">
                                                            <div class="desc">
                                                                <a href="javascript:void(0)">Abc Def</a>
                                                                <span class="mf-info"></span>
                                                            </div>
                                                            <div class="fr-btn-holder">
                                                                <button class="btn btn-primary btn-sm">Confirm</button>
                                                                <button class="btn btn-primary btn-sm">Delete Reqeust</button>
                                                            </div>
                                                        </div>                                                          
                                                    </div>
                                               </form>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                              </ul>
                        </div>
                    </div>
                    <div class="not-messages noticon">              
                        <div class="dropdown dropdown-custom ">
                            <a href="javascript:void(0)" class="dropdown-toggle"  data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                <img src="<?= $baseUrl ?>/images/noticon-message.png"/>
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <div class="msg-list">
                                        <span class="not-title">Messages</span>
                                        <div class="no-listcontent">
                                            No New Messages
                                        </div>
                                    </div>
                                </li>
                              </ul>
                        </div>
                    </div>
                    <div class="not-notification noticon">              
                        <div class="dropdown dropdown-custom ">
                            <a href="javascript:void(0)" class="dropdown-toggle"  data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                <img src="<?= $baseUrl ?>/images/noticon-notification.png"/><span class="badge">10</span>
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <div class="noti-list">
                                        <span class="not-title">Notifications</span>
                                        <ul class="noti-listing">
                                            <li>
                                                <div class="noti-holder">
                                                    <a href="javascript:void(0)">
                                                        <span class="img-holder">
                                                            <img class="img-responsive" src="<?= $baseUrl ?>/images/female.png">
                                                        </span>
                                                        <span class="desc-holder">
                                                            <span class="desc">
                                                                <span class="btext">Abc Def</span> replied on your comment:
                                                            </span>
                                                            <span class="time-stamp">
                                                                <i class="fa fa-globe"></i> Just Now
                                                            </span>
                                                        </span>
                                                    </a>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="noti-holder">
                                                    <a href="javascript:void(0)">
                                                        <span class="img-holder">
                                                            <img class="img-responsive" src="<?= $baseUrl ?>/images/female.png">
                                                        </span>
                                                        <span class="desc-holder">
                                                            <span class="desc">
                                                                <span class="btext">Abc Def</span> added a photo
                                                            </span>
                                                            <span class="time-stamp">
                                                                <i class="fa fa-user"></i> 20 mins ago
                                                            </span>
                                                        </span>
                                                    </a>
                                                </div>
                                            </li>
                                        </ul>
                                        <!--
                                        <div class="no-listcontent">
                                            No New Notifications
                                        </div>
                                        -->
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    
                </div>
                
                <div class="search-holder">
                    <div class="search-section">
                        <form>
                            <span class="search-input-span"><input placeholder="Enter your search term..." type="text" value="" name="search" id="search" class="search-input" data-id="searchfirst"/></span>                        
                            <span class="search-btn" value="">
                                <a href="javascript:void(0)"><i class="fa fa-search"></i></a>
                            </span>
                            <div class="search-result" id="searchfirst">
                            </div>
                        </form>
                    </div>
                </div>          
            </div>