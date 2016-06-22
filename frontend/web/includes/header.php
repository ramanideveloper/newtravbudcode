<?php

/* @var $this \yii\web\View */
/* @var $asset frontend\assets\AppAsset */
/* @var $result frontend\models\UserForm */
/* @var $session \yii\web\Session */

use frontend\assets\AppAsset;
use frontend\models\UserForm;
use frontend\models\PostForm;
use frontend\models\Friend;
use frontend\models\Notification;
use frontend\models\NotificationSetting;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\mongodb\ActiveRecord;
use frontend\models\SecuritySetting;
use frontend\models\UserSetting;


use frontend\models\LoginForm;
use frontend\models\BookmarkForm;


use yii\helpers\Url;
use frontend\models\Like;
use common\components\CDbCriteria;


$asset = frontend\assets\AppAsset::register($this);
$baseUrl = AppAsset::register($this)->baseUrl;
 

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
    
    <!-- theme button -->

<div class="theme-colorbox ani-1" id="theme-window" tabindex="-1">
  <div class="colorbox-button" id="theme-open-button"><span class="ani-1"><img src="<?= $baseUrl?>/images/theme-setting-icon.png"/></span> </div>
  <div class="colorbox-coloricon clearfix"> 
	<a href="javascript:void(0);" body-color="theme-dark-blue" onclick="theme_color('theme-dark-blue')" class="tm-dark-blue">&nbsp;</a>
	<a href="javascript:void(0);" body-color="theme-purple"  onclick="theme_color('theme-purple')" class="tm-purple">&nbsp;</a>
	<a href="javascript:void(0);" body-color="theme-light-blue" onclick="theme_color('theme-light-blue')"  class="tm-light-blue">&nbsp;</a>
	<a href="javascript:void(0);" body-color="theme-green" onclick="theme_color('theme-green')"  class="tm-green">&nbsp;</a>
	<a href="javascript:void(0);" body-color="theme-light-red" onclick="theme_color('theme-light-red')" class="tm-light-red">&nbsp;</a>
	<a href="javascript:void(0);" body-color="theme-light-purple" onclick="theme_color('theme-light-purple')" class="tm-light-purple">&nbsp;</a>
	<a href="javascript:void(0);" body-color="theme-dark-cyan" onclick="theme_color('theme-dark-cyan')" class="tm-dark-cyan">&nbsp;</a>
	<a href="javascript:void(0);" body-color="theme-bright-blue" onclick="theme_color('theme-bright-blue')" class="tm-bright-blue">&nbsp;</a>
	<a href="javascript:void(0);" body-color="theme-emerald" onclick="theme_color('theme-emerald')" class="tm-emerald">&nbsp;</a> </div>
</div>
<!-- header section -->
<div class="np-header02 fb-page">
  <div class="container-fluid clearfix">
    <div class="row">
      <div class="np-header-topbar">
		<ul class="top-menu">
				<li>
					<div class="droparea topbar-ul">
						<a href="javascript:void(0)" class="alink">Hotels</a>						
					</div>
				</li>
				<li>
					<div class="droparea topbar-ul">
						<a href="javascript:void(0)" class="alink">Tours</a>						
					</div>
				</li>
				<li>
					<div class="droparea topbar-ul">
						<a href="javascript:void(0)" class="alink">Places</a>
						<div class="drawer">
							<div class="opt-box">							
								<ul class="siconopt-ul">
									<li>
										<a href="#"><span class="glyphicon glyphicon-plus"></span>Add Destination</a>
									</li>
									<li class="divider"></li>
									<li>
										<a href="#"><span class="glyphicon glyphicon-globe"></span>Browse Destination</a>
									</li>					
									<li class="divider"></li>									
									<li>
										<a href="#"><span class="glyphicon glyphicon-heart-empty"></span>My Destination</a>
									</li>
									<li class="divider"></li>
									<li>
										<a href="#"><span class="glyphicon glyphicon-tower"></span>Top Places</a>
									</li>
								</ul>				
							</div>
						</div>
					</div>
				</li>
				<li>
					<div class="droparea topbar-ul">
						<a href="javascript:void(0)" class="alink">Write Review</a>						
					</div>
				</li>
				<li>
					<div class="droparea topbar-ul">
						<a href="javascript:void(0)" class="alink">Agent</a>						
					</div>
				</li>
				<li>					
					<div class="droparea topbar-ul">
						<a href="javascript:void(0)" class="alink">Business Services</a>						
						<div class="drawer">
							<div class="opt-box">							
								<ul class="siconopt-ul">
									<li>
										<a href="#"><span class="glyphicon glyphicon-plus"></span>Create Guide Profile</a>
									</li>
									<li class="divider"></li>
									<li>
										<a href="#"><span class="glyphicon glyphicon-globe"></span>Advertising Manager</a>
									</li>					
									<li class="divider"></li>									
									<li>
										<a href="#"><span class="glyphicon glyphicon-credit-card"></span>Travel Agent contact</a>
									</li>
									<li class="divider"></li>
									<li>
										<a href="#"><span class="glyphicon glyphicon-tower"></span>Hotel For Less</a>
									</li>
									<li class="divider"></li>
									<li>
										<a href="#"><span class="glyphicon glyphicon-shopping-cart"></span>Travel store</a>
									</li>
								</ul>				
							</div>
						</div>
					</div>
				</li>
			</ul>
	  </div>
    </div>
    <div class="row np-head-fix01">
      <div class="col-lg-3 col-md-2 col-sm-12 col-xs-12 logo-holder">
          <div class="logo"><a href="<?= Url::home();?>" class="pageLoad fbp-logo" ><img src="<?= $baseUrl?>/images/logo-2.png"></a>
        
        <div class="menu-handler" id="menuHandler" onClick="menuHendler();"><span class="glyphicon glyphicon-menu-hamburger"> </span>
        <span class="glyphicon glyphicon-remove-circle"> </span></div>
        
        </div>
      </div>
	  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 clearfix rtop">	  
	   <div class="tb-search-box">
          <div id="sb-search" class="sb-search">
            <form>
              <input class="sb-search-input search" placeholder="Enter your search term..." type="text" value="" name="search" id="search" data-id="searchfirst">
              
              <span class="sb-icon-search"><input class="sb-search-submit" type="text" value=""><i class="fa fa-search"></i></span>
              <div id="searchfirst" class="search-droparea">
			  
			  </div>
            </form>
          </div>
        </div>
      </div>
      <div class="col-lg-5 col-md-6 col-sm-8 col-xs-12 clearfix rbottom">
        <div class="tb-head-rightbox clearfix">
          <div class="tb-home-iconbox clearfix">
			<div class="droparea">
				<a href="javascript:void(0);" id="open_requests" class="alink"><i class="glyphicon glyphicon-user"></i><?php if($request_budge>0){?> <span class="badge badge-default" > <?php echo $request_budge;?> </span> <?php }?></a> 
			<a href="javascript:void(0);"><i class="glyphicon glyphicon-envelope"></i><!--	 <span class="badge badge-default"> 6 </span> </a> -->
				<a href="javascript:void(0);" id="open_notifications" onclick="view_notification()"  class="alink"><i class="glyphicon glyphicon-globe" id="glob_budge"></i>  <?php if($notification_budge>0){?><span class="badge badge-default" id="noti_budge"> <?php echo $notification_budge;?></span>   <?php }?> </a>
				
				<div class="drawer">
					<div id="pending" class="pending_list notification-content">
					  
						<div class="list-title">
							Friend Requests
						</div>
						<!--
						<ul class="fr-list">
							<li>
								<form>
									<div class="fr-holder">
										<div class="img-holder">
											<img class="img-responsive" src="profile/20160321121949.jpg">
										</div>
										<div class="desc-holder">
											<div class="desc">
												<a href="#">User Name</a>
												<span class="mf-info">2 Mutual Friends</span>
											</div>
											<div class="fr-btn-holder">
												<button class="btn btn-primary btn-sm bt-cfix">Confirm</button>
												<button class="btn btn-primary btn-sm bt-cfix">Delete Reqeust</button>
											</div>
										</div>
									</div>
								</form>
							</li>
						</ul>
						-->
						<?php if(!empty($pending_requests) && count($pending_requests)>0){?>
							 <ul class="fr-list">
						   <?php  foreach($pending_requests as $pending_request){ 
							   $mutual_ctr = $model_friend->mutualfriendcount($pending_request['userdata']['_id']);
							 ?>
                                                  <li id="request_<?php echo $pending_request['_id'];?>">
						   <?php $form = ActiveForm::begin(
									[
										'id' => 'view_friend_request',
										'options'=>[
											'onsubmit'=>'return false;',
										   // 'enableAjaxValidation' => true,
											],
									]
				); ?>
					  <div class="fr-holder">
						<div class="img-holder">
						<?php
                                                $frnd_img = $this->context->getimage($pending_request['userdata']['_id'],'thumb');
                                                ?>
                                                    <img src="<?= $frnd_img?>" class="img-responsive">
						</div>
                                              
                                              <div class="desc-holder">
											<div class="desc">
												<a href="#"> <?php echo ucfirst($pending_request['userdata']['fname']).' '.ucfirst($pending_request['userdata']['lname']);?> </a>
												<span class="mf-info"><?php if($mutual_ctr>0){ echo $mutual_ctr; ?> Mutule Friends<?php } ?></span>
											</div>
											<div id="accept_<?php echo $pending_request['from_id']; ?>" class="fr-btn-holder">
												<button class="btn btn-primary btn-sm bt-cfix fr-accept" onclick="acceptfriendrequest('<?php echo $pending_request['from_id'];?>')">Confirm</button>
                                                                                                <button class="btn btn-primary btn-sm bt-cfix" onclick="delete_request('<?php echo $pending_request['_id'];?>','<?php echo $pending_request['from_id'];?>','<?php echo $pending_request['to_id'];?>')">Delete Reqeust</button>
											</div>
                                                  <span id="acceptmsg_<?php echo $pending_request['from_id'];?>" style="display:none" class="request-accept"></span>
                                                  
										</div>
                                              
                                              
						  <input type="hidden" name="to_id" id="to_id" value="<?php echo $pending_request['_id'];?>">
											
                                          </div>
						   <?php ActiveForm::end() ;?></li><?php } ?>
						    </ul>
						  <?php } else{ ?>
							  
							  <div class="no-listcontent">
								No new requests
							  </div>
						  <?php } ?>
                                             
						  <?php // include('includes/people_you_may_know.php'); ?>
						  <?php //else{ ?>
		<!--<div class="norequest" style="background-color:white;margin-top: 30px">No requests are pending</div>--><?php //}?>

					</div>
					<div id="notifications" class="notifications_list notification-content">
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
                                                                                            $not_img = $this->context->getimage($notification['shared_by'],'thumb');
                                                                                        }
                                                                                        else
                                                                                        {
                                                                                            $not_img = $this->context->getimage($notification['user']['_id'],'thumb');
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
							  </div> <?php } ?>
						  
<!--							<li>
								<div class="not-holder">
									<div class="descbox">
										<div class="img-holder">
											<img class="img-responsive" src="profile/20160321121949.jpg">
										</div>
										<div class="desc-holder">									
											<div class="desc">
												<p>
													<a href="#" class="name-l">User name</a>
													 likes your post: " Something goes here ... "
												</p>
												<span class="timestamp">
													<i class="fa fa-thumbs-up"></i> 14 Apr at 6:08 pm
												</span>
											</div>										
										</div>
									</div>							
								</div>							
								
							</li>
							<li>
								<div class="not-holder">
									<div class="descbox">
										<div class="img-holder">
											<img class="img-responsive" src="profile/20160321121949.jpg">
										</div>
										<div class="desc-holder">									
											<div class="desc">
												<p>
													<a href="#" class="name-l">User name</a>
													 shared your post: " Lore ipsum ... "
												</p>
												<span class="timestamp">
													<i class="fa fa-share-alt"></i> 14 Apr at 6:08 pm
												</span>
											</div>										
										</div>
									</div>							
								</div>
							</li>							
							<li>
								<div class="not-holder">
									<div class="reqbox">
										<div class="img-holder">
											<img class="img-responsive" src="profile/20160321121949.jpg">
										</div>
										<div class="desc-holder">
											<div class="desc">
												<p>
													<a href="#" class="name-l">User name</a>
													 sent you a friend request. Respond now!
												</p>
											</div>		
											<div class="fr-btn-holder">
												<button class="btn btn-primary btn-sm bt-cfix">Confirm</button>
												<button class="btn btn-primary btn-sm bt-cfix">Delete Reqeust</button>
											</div>
										</div>
									</div>
								</div>							
							</li>
							<li>
								<div class="not-holder">
									<div class="descbox">
										<div class="img-holder">
											<img class="img-responsive" src="profile/20160321121949.jpg">
										</div>
										<div class="desc-holder">									
											<div class="desc">
												<p>
													<a href="#" class="name-l">User name</a>
													 commented on your photo : " Lorem ipsum goes here ..."
												</p>
												<span class="timestamp">
													<i class="fa fa-comment"></i> 14 Apr at 6:08 pm
												</span>
											</div>										
										</div>
									</div>							
								</div>
							</li>
							<li>
								<div class="not-holder">
									<div class="reqbox">
										<div class="img-holder">
											<img class="img-responsive" src="profile/20160321121949.jpg">
										</div>
										<div class="desc-holder">
											<div class="desc">
												<p>
													You received a eCard from <a href="#" class="name-l end">User name</a>													
												</p>
												<span class="timestamp">
													<i class="fa fa-gift"></i> 14 Apr at 6:08 pm
												</span>
											</div>		
											<div class="fr-btn-holder">
												<button class="btn btn-primary btn-sm bt-cfix">Accept</button>
												<button class="btn btn-primary btn-sm bt-cfix">Reject</button>
											</div>
										</div>
									</div>
								</div>							
							</li>
                                                        <li>
								<div class="not-holder">
									<div class="descbox">
										<div class="img-holder">
											<img class="img-responsive" src="profile/20160321121949.jpg">
										</div>
										<div class="desc-holder">									
											<div class="desc">
												<p>
													<a href="#" class="name-l">User name</a>
													started following you
												</p>
												<span class="timestamp">
													<i class="fa fa-check"></i> 14 Apr at 6:08 pm
												</span>
											</div>										
										</div>
									</div>							
								</div>
							</li>
							<li>
								<div class="not-holder">
									<div class="reqbox">
										<div class="img-holder">
											<img class="img-responsive" src="profile/20160321121949.jpg">
										</div>
										<div class="desc-holder">
											<div class="desc">
												<p>
													<a href="#" class="name-l">User name</a>
													invited you for the meeting
												</p>
											</div>		
											<div class="fr-btn-holder">
												<button class="btn btn-primary btn-sm bt-cfix">See Details</button>
											</div>
										</div>
									</div>
								</div>							
							</li>
							<li>
								<div class="not-holder">
									<div class="descbox">
										<div class="img-holder">
											<img class="img-responsive" src="profile/20160321121949.jpg">
										</div>
										<div class="desc-holder">									
											<div class="desc">
												<p>
													<a href="#" class="name-l">Group member</a>
													does some activity
												</p>
												<span class="timestamp">
													<i class="fa fa-globe"></i> 14 Apr at 6:08 pm
												</span>
											</div>										
										</div>
									</div>							
								</div>
							</li>-->
						</ul>
						
					  </div>
				  
				</div>
			</div>
		
          </div>
          <div class="tb-home-iconbox2">
            <ul class="nav nav-pills navbar-right">
              <li class="f-smenu"> 
			  
				<div class="profileinfo">
					<a href="<?php $uid = $result['_id']; echo Url::to(['userwall/index', 'id' => "$uid"]); ?>" class="tb-user-link">
										
						<span class="tb-user-img">
							<?php
                                                        $user_img = $this->context->getimage($result['_id'],'thumb');
							?>
                                                        <img src="<?= $user_img?>" alt="" width="250" height="249">
						</span>
						<span class="tb-user-text"><?= ucfirst($result['fname']);?></span>&nbsp;
					</a>
					<div class="droparea">
						<a href="#" class="alink setting-menu">						
							<!--<span class="caret"></span>-->
							<span class="as-icon">
								<img src="<?= $baseUrl?>/images/acc-setting-icon.png"/>								
							</span>
						</a>
						
						<div class="drawer">
							<div class="opt-box">							
								<ul class="siconopt-ul">
									<li>
										<a href="<?php echo Yii::$app->urlManager->createUrl(['site/basicinfo']); ?>"><span class="glyphicon glyphicon-cog"></span>Account Settings</a>
									</li>
									<li class="divider"></li>
									<li>
										<a href="#"><span class="glyphicon glyphicon-user"></span>VIP Member</a>
									</li>
									<li class="divider"></li>
									<li>
										<a href="#"><span class="glyphicon glyphicon-star"></span>Credits</a>
									</li>
									<li class="divider"></li>
									<li>
										<a href="#"><span class="glyphicon glyphicon-usd"></span>Donate</a>
									</li>
									<li class="divider"></li>
									<li>
										<a href="#"><span class="glyphicon glyphicon-saved"></span>Verification</a>
									</li>
									<li class="divider"></li>
									<li>
										<a href="#"><span class="glyphicon glyphicon-certificate"></span>Advertising Manager</a>
									</li>
									<li class="divider"></li>
									<li>
										<a href="<?php echo Yii::$app->urlManager->createUrl(['site/security-setting']); ?>"><span class="glyphicon glyphicon-lock"></span>Security Settings</a>
									</li>
									<li class="divider"></li>
									<li>
										<a href="#"><span class="glyphicon glyphicon-check"></span>Billing Information</a>
									</li>
								</ul>				
							</div>
						</div>
						
					</div>
				</div>
              </li>
			  <li class="s-smenu"> 
				<div class="droparea">
					<a href="#" class="alink"><img src="<?= $baseUrl?>/images/add-icon.png"/></a>
					<div class="drawer">
						<div class="opt-box" id="opt-box">							
							<ul class="siconopt-ul">
								<li>
									<a href="#"><span class="glyphicon glyphicon-plus"></span>Add Photos</a>
								</li>
								<li class="divider"></li>
								<li>
									<a href="#"><span class="glyphicon glyphicon-comment"></span>Share Your Openinion</a>
								</li>
								<li class="divider"></li>
								<li>
									<a href="#"><span class="glyphicon glyphicon-pencil"></span>Write A Tip</a>
								</li>
								<li class="divider"></li>
								<li>
									<a href="#"><span class="glyphicon glyphicon-plane"></span>Create A Trip</a>
								</li>
								<li class="divider"></li>
								<li>
									<a href="#"><span class="glyphicon glyphicon-question-sign"></span>Ask A Question</a>
								</li>
                                                                <?php /*foreach($bookmarks as $bookmark){
                                                                    $blabel = $bookmark['blabel'];
                                                                    $blink = $bookmark['blink'];*/
                                                                ?>
                                                                <!--<li>
									<a href="http:$blink" target="_blank"><span class="glyphicon glyphicon-pencil"></span></a>
								</li>
								<li class="divider"></li>-->
                                                                <?php //}?>
                                                                <li class="divider"></li>
								<li>
									<a href="<?php echo Yii::$app->urlManager->createUrl(['site/savedpost'])?>"><span class="glyphicon glyphicon-save"></span>Saved Posts</a>
								</li>
							</ul>				
						</div>
					</div>
				</div>
			  </li>
              <li><a href="javascript:DoPost();" class="dropdown-toggle logout-fix" title="Logout" style="pointer-events: auto !important; cursor: pointer;"> <i class="glyphicon glyphicon-log-out"></i> </a></li>
            </ul>
          </div>
        </div>
       </div>
	   
    </div>
  </div>

	<div id="notverifyemail" class="white-popup-block mfp-hide fp-modal-popup rounded abs-notice unreg-modal">	
		<div class="modal-title graytitle">
			<div class="mlogo"><img src="<?= $baseUrl?>/images/logo-2.png"/></div>
			<a class="popup-modal-dismiss popup-modal-close" href="#"><i class="fa fa-close"></i></a>
		</div>	
		<div class="fp-notice">
			<div class="fp-success" id="fp-notice">
				<i class="fa fa-check-circle"></i> Email Sent.
			</div>
			<div class="fp-error" id="fp-error">
				<i class="fa fa-check-circle"></i> Please Enter Valid Email Address!
			</div>
		</div>
		<div class="modal-detail text-center unreg-modal notice1">
			
			<div class="reset-hd-fix unreg-user">
				<p>Your account is pending verification. Please check your email to activate your TravBud account.</p>
				<a onclick="account_verify(2)" href="javascript:void(0)" class='confirm-link'>Please Click Here To Resend Mail</a>
					<!--<a onclick="account_verify()" href="javascript:void(0)" class="confirm-link">confirmation set</a>-->
			</div>
			<div class="post_loadin_img"></div>
			
		</div>
	</div>


</div>


