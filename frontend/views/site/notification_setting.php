<?php

/* @var $this \yii\web\View */
/* @var $content string */
include('includes/header.php');
use yii\helpers\Html;
use frontend\assets\AppAsset;

use yii\widgets\ActiveForm;
use yii\mongodb\ActiveRecord;
use yii\captcha\Captcha;

use frontend\models\LoginForm;
use frontend\models\UserSetting;
use frontend\models\Personalinfo;
use frontend\models\NotificationSetting;
use  yii\web\Session;
$session = Yii::$app->session;


if($session->get('email'))
{
    $email = $session->get('email'); 
    $user_id = (string) $session->get('user_id');
    $result = LoginForm::find()->where(['email' => $email])->one();
    $user = LoginForm::find()->where(['email' => $email])->one();
    $notification = NotificationSetting::find()->where(['user_id' => $user_id])->one();
    
        $friend_activity = $notification['friend_activity'];
    	$email_on_account_issues = $notification['email_on_account_issues'];
	$member_activity = $notification['member_activity'];
	$friend_activity_on_user_post = $notification['friend_activity_on_user_post'];
	$group_activity = $notification['group_activity'];
	$non_friend_activity = $notification['non_friend_activity'];
	$friend_request = $notification['friend_request'];
	$e_card = $notification['e_card'];
	$member_invite_on_meeting = $notification['member_invite_on_meeting'];
	$question_activity = $notification['question_activity'];
	$credit_activity = $notification['credit_activity'];
        $sound_on_notification = $notification['sound_on_notification'];
        $sound_on_message = $notification['sound_on_message'];
        
	
    
}
else{
     $url = Yii::$app->urlManager->createUrl(['site/index']);
            Yii::$app->getResponse()->redirect($url);   
}



//use vendor\bower\travel\assets\AppAsset;
//AppAsset::register($this);
//AppAsset::register($this);
$asset = frontend\assets\AppAsset::register($this);

$baseUrl = AppAsset::register($this)->baseUrl;
?>
<!-- body section -->
<section class="inner-body-content fb-page notify_text">
	<div class="fb-innerpage whitebg">
		<div class="setting-content">	 
			<?php include('includes/leftmenus.php');?>
			<div class="fbdetail-holder">
				
				
				<?php
			   
					if($session->get('step')== '1'){
						?>
					<script>$('#suceess').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);</script>
					<?php
				}
				else if($session->get('step')== '10'){
					?>
					<script>$('#info').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);</script>
					<?php
				}
				?>
									
									
				<div class="fb-formholder">		
					<div class="fb-formholder-box">
						<div class="formtitle"><h4>Notification Settings</h4></div>
						<div class="notice">	
							<div id="suceess" class="form-successmsg">Your Settings updated successfully</div>
							<div id="fail" class="form-failuremsg">Oops..!! Somthing Went Wrong in Step 2</div>
							<div id="info" class="form-infomsg">Your Security Settings Saved Successfully</div>
						</div>
						<ul class="setting-ul desc-settings">
							
							  <?php $form = ActiveForm::begin(['id' => 'frm-notification','options'=>['onsubmit'=>'return false;',],]); ?>  
							
							
							<li>                        
								<div class="setting-group">							
									<div class="normal-mode">
										<div class="row">
											<div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
												<label>Choose whether you want to get notifications about friends activities</label>
											</div>									
											<div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">										
												<div class="pull-right" id="friend">	
													<div class="switch">
														<?php if ($friend_activity == 'No'){   
															 echo '<input id="friend_activity1" class="cmn-toggle cmn-toggle-round" type="checkbox">';
														}
														else{
															 echo '<input id="friend_activity1" class="cmn-toggle cmn-toggle-round" checked type="checkbox">';
														}
														?>
													   
														<label for="friend_activity1"></label>
													</div>
								
													<?php
														if(isset($friend_activity) && !empty($friend_activity)){
															echo '<input type="hidden" name="friend_activity" id="friend_activity" value="'.$friend_activity.'" />';
														}else{
															echo '<input type="hidden" name="friend_activity" id="friend_activity" value="Yes" />';	
														}
													?>
													
												</div>
											</div>
										</div>	
									</div>								
								</div>	                                               
							</li>
							<li>                        
								<div class="setting-group">							
									<div class="normal-mode">
										<div class="row">
											<div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
												<label>Choose whether you want to get email notifications about your account security and privacy issues</label>
											</div>									
											<div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">										
												<div class="pull-right" id="email">
																							 <div class="switch">
																								 <?php if ($email_on_account_issues == 'No'){   
																									 echo '<input id="email_on_account_issues1" class="cmn-toggle cmn-toggle-round" type="checkbox">';
																								}
																								else{
																									 echo '<input id="email_on_account_issues1" class="cmn-toggle cmn-toggle-round" checked type="checkbox">';
																								}
																								?>
														
														<label for="email_on_account_issues1"></label>
													</div>
													
													<?php
														if(isset($email_on_account_issues) && !empty($email_on_account_issues)){
															echo '<input type="hidden" name="email_on_account_issues" id="email_on_account_issues" value="'.$email_on_account_issues.'" />';
														}else{
															echo '<input type="hidden" name="email_on_account_issues" id="email_on_account_issues" value="Yes" />';	
														}
													?>
													
												</div>
											</div>
										</div>	
									</div>								
								</div>	                                               
							</li>
							<li>                        
								<div class="setting-group">							
									<div class="normal-mode">
										<div class="row">
											<div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
												<label>Choose whether you want to get all notifications about activity that involves the member</label>
											</div>									
											<div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">										
												<div class="pull-right" id="member">
																							<div class="switch">
																								<?php if ($member_activity == 'No'){   
																									 echo '<input id="member_activity1" class="cmn-toggle cmn-toggle-round" type="checkbox">';
																								}
																								else{
																									 echo '<input id="member_activity1" class="cmn-toggle cmn-toggle-round" checked type="checkbox">';
																								}
																								?>
														<label for="member_activity1"></label>
													</div>
													
													
													<?php
														if(isset($member_activity) && !empty($member_activity)){
															echo '<input type="hidden" name="member_activity" id="member_activity" value="'.$member_activity.'" />';
														}else{
															echo '<input type="hidden" name="member_activity" id="member_activity" value="Yes" />';	
														}
													?>
												</div>
											</div>
										</div>	
									</div>								
								</div>	                                               
							</li>
							<li>                        
								<div class="setting-group">							
									<div class="normal-mode">
										<div class="row">
											<div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
												<label>Choose whether you want to get notifications when someone like or share or comments on member post or photo</label>
											</div>									
											<div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">										
												<div class="pull-right" id="activity_on_user">	
																							<div class="switch">
																								<?php if ($friend_activity_on_user_post == 'No'){   
																									 echo '<input id="friend_activity_on_user_post1" class="cmn-toggle cmn-toggle-round" type="checkbox">';
																								}
																								else{
																									 echo '<input id="friend_activity_on_user_post1" class="cmn-toggle cmn-toggle-round" checked type="checkbox">';
																								}
																								?>
														
														<label for="friend_activity_on_user_post1"></label>
													</div>
													
													<?php
														if(isset($friend_activity_on_user_post) && !empty($friend_activity_on_user_post)){
															echo '<input type="hidden" name="friend_activity_on_user_post" id="friend_activity_on_user_post" value="'.$friend_activity_on_user_post.'" />';
														}else{
															echo '<input type="hidden" name="friend_activity_on_user_post" id="friend_activity_on_user_post" value="Yes" />';	
														}
													?>
													
													
												</div>
											</div>
										</div>	
									</div>								
								</div>	                                               
							</li>
							<li>                        
								<div class="setting-group">							
									<div class="normal-mode">
										<div class="row">
											<div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
												<label>Choose whether you want to get all notifications about activity that involves your groups</label>
											</div>									
											<div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">										
												<div class="pull-right" id="group">	
																							<div class="switch">
																								 <?php if ($group_activity == 'No'){   
																									 echo '<input id="group_activity1" class="cmn-toggle cmn-toggle-round" type="checkbox">';
																								}
																								else{
																									 echo '<input id="group_activity1" class="cmn-toggle cmn-toggle-round" checked type="checkbox">';
																								}
																								?>
														<label for="group_activity1"></label>
													</div>
													
													<?php
														if(isset($group_activity) && !empty($group_activity)){
															echo '<input type="hidden" name="group_activity" id="group_activity" value="'.$group_activity.'" />';
														}else{
															echo '<input type="hidden" name="group_activity" id="group_activity" value="Yes" />';	
														}
													?>
													
												</div>
											</div>
										</div>	
									</div>								
								</div>	                                               
							</li>
							<li>                        
								<div class="setting-group">							
									<div class="normal-mode">
										<div class="row">
											<div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
												<label>Choose whether you want to get notifications when people who aren't your friends start following you and share or comment on your public posts</label>
											</div>									
											<div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">										
												<div class="pull-right" id="non_friend">
													<div class="switch">
														<?php if ($non_friend_activity == 'No'){   
															 echo '<input id="non_friend_activity1" class="cmn-toggle cmn-toggle-round" type="checkbox">';
														}
														else{
															 echo '<input id="non_friend_activity1" class="cmn-toggle cmn-toggle-round" checked type="checkbox">';
														}
														?>
															<label for="non_friend_activity1"></label>
													</div>
													
													<?php
														if(isset($non_friend_activity) && !empty($non_friend_activity)){
															echo '<input type="hidden" name="non_friend_activity" id="non_friend_activity" value="'.$non_friend_activity.'" />';
														}else{
															echo '<input type="hidden" name="non_friend_activity" id="non_friend_activity" value="Yes" />';	
														}
													?>
												</div>
											</div>
										</div>	
									</div>								
								</div>	                                               
							</li>
							<li>                        
								<div class="setting-group">							
									<div class="normal-mode">
										<div class="row">
											<div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
												<label>Choose whether you want to get notification when you get a friend request or confirm friendship</label>
											</div>									
											<div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">										
												<div class="pull-right" id="friend_req">
																							<div class="switch">
																								<?php if ($friend_request == 'No'){   
																									 echo '<input id="friend_request1" class="cmn-toggle cmn-toggle-round" type="checkbox">';
																								}
																								else{
																									 echo '<input id="friend_request1" class="cmn-toggle cmn-toggle-round" checked type="checkbox">';
																								}
																								?>
														<label for="friend_request1"></label>
													</div>
													
													<?php
														if(isset($friend_request) && !empty($friend_request)){
															echo '<input type="hidden" name="friend_request" id="friend_request" value="'.$friend_request.'" />';
														}else{
															echo '<input type="hidden" name="friend_request" id="friend_request" value="Yes" />';	
														}
													?>
												</div>
											</div>
										</div>	
									</div>								
								</div>	                                               
							</li>
							<li>                        
								<div class="setting-group">							
									<div class="normal-mode">
										<div class="row">
											<div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
												<label>Choose whether you want to get notification when you get eCard or postcard or eGift</label>
											</div>									
											<div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">										
												<div class="pull-right" id="e_c">
																							<div class="switch">
																								<?php if ($e_card == 'No'){   
																									 echo '<input id="e_card1" class="cmn-toggle cmn-toggle-round" type="checkbox">';
																								}
																								else{
																									 echo '<input id="e_card1" class="cmn-toggle cmn-toggle-round" checked type="checkbox">';
																								}
																								?>
														<label for="e_card1"></label>
													</div>
													
													<?php
														if(isset($e_card) && !empty($e_card)){
															echo '<input type="hidden" name="e_card" id="e_card" value="'.$e_card.'" />';
														}else{
															echo '<input type="hidden" name="e_card" id="e_card" value="Yes" />';	
														}
													?>
												</div>
											</div>
										</div>	
									</div>								
								</div>	                                               
							</li>
							<li>                        
								<div class="setting-group">							
									<div class="normal-mode">
										<div class="row">
											<div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
												<label>Choose whether you want to get notification when member invite me for a meeting</label>
											</div>									
											<div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">										
												<div class="pull-right" id="meeting">	
																							<div class="switch">
																								<?php if ($member_invite_on_meeting == 'No'){   
																									 echo '<input id="member_invite_on_meeting1" class="cmn-toggle cmn-toggle-round" type="checkbox">';
																								}
																								else{
																									 echo '<input id="member_invite_on_meeting1" class="cmn-toggle cmn-toggle-round" checked type="checkbox">';
																								}
																								?>
														<label for="member_invite_on_meeting1"></label>
													</div>
													
													<?php
														if(isset($member_invite_on_meeting) && !empty($member_invite_on_meeting)){
															echo '<input type="hidden" name="member_invite_on_meeting" id="member_invite_on_meeting" value="'.$member_invite_on_meeting.'" />';
														}else{
															echo '<input type="hidden" name="member_invite_on_meeting" id="member_invite_on_meeting" value="Yes" />';	
														}
													?>
												</div>
											</div>
										</div>	
									</div>								
								</div>	                                               
							</li>
							<li>                        
								<div class="setting-group">							
									<div class="normal-mode">
										<div class="row">
											<div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
												<label>Choose whether you want to get notification when a friend ask a question or responded to a question</label>
											</div>									
											<div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">										
												<div class="pull-right" id="question" >	
																							<div class="switch">
																								<?php if ($question_activity == 'No'){   
																									 echo '<input id="question_activity1" class="cmn-toggle cmn-toggle-round" type="checkbox">';
																								}
																								else{
																									 echo '<input id="question_activity1" class="cmn-toggle cmn-toggle-round" checked type="checkbox">';
																								}
																								?>
														<label for="question_activity1"></label>
													</div>
													
													<?php
														if(isset($question_activity) && !empty($question_activity)){
															echo '<input type="hidden" name="question_activity" id="question_activity" value="'.$question_activity.'" />';
														}else{
															echo '<input type="hidden" name="question_activity" id="question_activity" value="Yes" />';	
														}
													?>
													
												</div>
											</div>
										</div>	
									</div>								
								</div>	                                               
							</li>
							<li>                        
								<div class="setting-group">							
									<div class="normal-mode">
										<div class="row">
											<div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
												<label>Choose whether you want to get notification when your credits is near tipping point or tipped up</label>
											</div>									
											<div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">										
												<div class="pull-right" id="credit">	
																							<div class="switch">
																								<?php if ($credit_activity == 'No'){   
																									 echo '<input id="credit_activity1" class="cmn-toggle cmn-toggle-round" type="checkbox">';
																								}
																								else{
																									 echo '<input id="credit_activity1" class="cmn-toggle cmn-toggle-round" checked type="checkbox">';
																								}
																								?>
														<label for="credit_activity1"></label>
													</div>
													
													<?php
														if(isset($credit_activity) && !empty($credit_activity)){
															echo '<input type="hidden" name="credit_activity" id="credit_activity" value="'.$credit_activity.'" />';
														}else{
															echo '<input type="hidden" name="credit_activity" id="credit_activity" value="Yes" />';	
														}
													?>
												</div>
											</div>
										</div>	
									</div>								
								</div>	                                               
							</li>
												
												<li>                        
								<div class="setting-group">							
									<div class="normal-mode">
										<div class="row">
											<div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
												<label>Play a sound when new notification is received</label>
											</div>									
											<div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">										
												<div class="pull-right" id="sound-notification">	
																							<div class="switch">
																								<?php if ($sound_on_notification == 'No'){   
																									 echo '<input id="sound_on_notification1" class="cmn-toggle cmn-toggle-round" type="checkbox">';
																								}
																								else{
																									 echo '<input id="sound_on_notification1" class="cmn-toggle cmn-toggle-round" checked type="checkbox">';
																								}
																								?>
														<label for="sound_on_notification1"></label>
													</div>
													
													<?php
														if(isset($sound_on_notification) && !empty($sound_on_notification)){
															echo '<input type="hidden" name="sound_on_notification" id="sound_on_notification" value="'.$sound_on_notification.'" />';
														}else{
															echo '<input type="hidden" name="sound_on_notification" id="sound_on_notification" value="Yes" />';	
														}
													?>
												</div>
											</div>
										</div>	
									</div>								
								</div>	                                               
							</li>
												
												<li>                        
								<div class="setting-group">							
									<div class="normal-mode">
										<div class="row">
											<div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
												<label>Play a sound when a message is received </label>
											</div>									
											<div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">										
												<div class="pull-right" id="sound-message">	
																							<div class="switch">
																								<?php if ($sound_on_message == 'No'){   
																									 echo '<input id="sound_on_message1" class="cmn-toggle cmn-toggle-round" type="checkbox">';
																								}
																								else{
																									 echo '<input id="sound_on_message1" class="cmn-toggle cmn-toggle-round" checked type="checkbox">';
																								}
																								?>
														<label for="sound_on_message1"></label>
													</div>
													
													<?php
														if(isset($sound_on_message) && !empty($sound_on_message)){
															echo '<input type="hidden" name="sound_on_message" id="sound_on_message" value="'.$sound_on_message.'" />';
														}else{
															echo '<input type="hidden" name="sound_on_message" id="sound_on_message" value="Yes" />';	
														}
													?>
												</div>
											</div>
										</div>	
									</div>								
								</div>	                                               
							</li>
							
							<?php ActiveForm::end() ?>
							
							
						</ul>
						 
					</div>
				</div>
			</div>
		</div>
	</div>
    <?php include('includes/footer.php');?>
<script>

    function notify(){
		
		var friend_activity = $('#friend_activity').val();
		var email_on_account_issues = $('#email_on_account_issues').val();
		var member_activity = $('#member_activity').val();
		var friend_activity_on_user_post = $('#friend_activity_on_user_post').val();
		var group_activity = $('#group_activity').val();
		var non_friend_activity = $('#non_friend_activity').val();
		var friend_request = $('#friend_request').val();
		var e_card = $('#e_card').val();
		var member_invite_on_meeting = $('#member_invite_on_meeting').val();
		var question_activity = $('#question_activity').val();
		var credit_activity = $('#credit_activity').val();
                var sound_on_notification = $('#sound_on_notification').val();
                var sound_on_message = $('#sound_on_message').val();
                
                

            $.ajax({
                   type: 'POST',
                   url: '<?php echo Yii::$app->urlManager->createUrl(['site/notification-setting']); ?>',
                 //data: $("#frm-notification").serialize(),
				   data: "friend_activity="+friend_activity+"&email_on_account_issues="+email_on_account_issues+
				         "&member_activity="+member_activity+"&friend_activity_on_user_post="+friend_activity_on_user_post+
                                         "&group_activity="+group_activity+"&non_friend_activity="+non_friend_activity+
                                         "&friend_request="+friend_request+"&e_card="+e_card+"&member_invite_on_meeting="+member_invite_on_meeting+
                                         "&question_activity="+question_activity+"&credit_activity="+credit_activity+
                                         "&sound_on_notification="+sound_on_notification+"&sound_on_message="+sound_on_message,
                   success: function(data){
                      
						if(data == '1'){
                            $('#suceess').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
                       }
                       else{
                            $('#info').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
                       }
 
                   }
                   
               });
    }

</script>
	 

<script language="javascript"> 

   function close_all_edit(){
	   var count=0;
	   $(".fb-formholder .setting-ul .setting-group").each(function(){
			
		   var editmode=$(this).children(".edit-mode").css("display");		
		
		   if(editmode!="none"){
				
				$(this).children(".normal-mode").slideDown(300);
				$(this).children(".edit-mode").slideUp(300);
			}
	   });
   }
	function open_edit(test){
		
		close_all_edit();

		var obj=$(test).parent().parent().parent().parent().parent();
				
		var editmode=obj.children(".edit-mode").css("display");

		if(editmode=="none"){
			obj.children(".normal-mode").slideUp(300);
			obj.children(".edit-mode").slideDown(300);
		}
		else{
			obj.children(".normal-mode").slideDown(300);
			obj.children(".edit-mode").slideUp(300);
		}
   }
	function close_edit(test){

		var obj=$(test).parent().parent().parent().parent().parent().parent();
				
		var editmode=obj.children(".edit-mode").css("display");

		if(editmode=="none"){
			obj.children(".normal-mode").slideUp(300);
			obj.children(".edit-mode").slideDown(300);
		}
		else{
			obj.children(".normal-mode").slideDown(300);
			obj.children(".edit-mode").slideUp(300);
		}
   }
   function close_edit_btn(test){

		var obj=$(test).parent().parent().parent().parent().parent().parent().parent().parent();
						
		var editmode=obj.children(".edit-mode").css("display");

		if(editmode=="none"){
			obj.children(".normal-mode").slideUp(300);
			obj.children(".edit-mode").slideDown(300);
		}
		else{
			obj.children(".normal-mode").slideDown(300);
			obj.children(".edit-mode").slideUp(300);
		}
   }

</script>

<!-- Switch -->
<script src="<?= $baseUrl?>/js/bootstrap-switch.js" ></script>
<script>
// Remember set you events before call bootstrapSwitch or they will fire after bootstrapSwitch's events

jq("[class='select_option']").bootstrapSwitch({
	on: 'Yes',
	off: 'No',
	onLabel: 'Yes',
    offLabel: 'No',
	size: 'sm'
});
</script>

<style>
    body{
        background-color:#f2f2f2;
    }
</style>
<script>
    jq("#friend_activity1").click(function(){
            console.clear();
            if ($("#friend_activity1").is(':checked')){
                $('#friend_activity').val('Yes');
            }
            else{
                 $('#friend_activity').val('No');
            }
            notify();
    });
     jq("#email_on_account_issues1").click(function(){
            console.clear();
            if ($("#email_on_account_issues1").is(':checked')){
                $('#email_on_account_issues').val('Yes');
            }
            else{
                 $('#email_on_account_issues').val('No');
            }
            notify();
    });
     jq("#member_activity1").click(function(){
            console.clear();
            if ($("#member_activity1").is(':checked')){
                $('#member_activity').val('Yes');
            }
            else{
                 $('#member_activity').val('No');
            }
            notify();
    });
     jq("#friend_activity_on_user_post1").click(function(){
            console.clear();
            if ($("#friend_activity_on_user_post1").is(':checked')){
                $('#friend_activity_on_user_post').val('Yes');
            }
            else{
                 $('#friend_activity_on_user_post').val('No');
            }
            notify();
    });
     jq("#group_activity1").click(function(){
            console.clear();
            if ($("#group_activity1").is(':checked')){
                $('#group_activity').val('Yes');
            }
            else{
                 $('#group_activity').val('No');
            }
            notify();
    });
     jq("#non_friend_activity1").click(function(){
            console.clear();
            if ($("#non_friend_activity1").is(':checked')){
                $('#non_friend_activity').val('Yes');
            }
            else{
                 $('#non_friend_activity').val('No');
            }
            notify();
    });
      jq("#friend_request1").click(function(){
            console.clear();
            if ($("#friend_request1").is(':checked')){
                $('#friend_request').val('Yes');
            }
            else{
                 $('#friend_request').val('No');
            }
            notify();
    });
    jq("#e_card1").click(function(){
            console.clear();
            if ($("#e_card1").is(':checked')){
                $('#e_card').val('Yes');
            }
            else{
                 $('#e_card').val('No');
            }
            notify();
    });
     jq("#member_invite_on_meeting1").click(function(){
            console.clear();
            if ($("#member_invite_on_meeting1").is(':checked')){
                $('#member_invite_on_meeting').val('Yes');
            }
            else{
                 $('#member_invite_on_meeting').val('No');
            }
            notify();
    });
      jq("#question_activity1").click(function(){
            console.clear();
            if ($("#question_activity1").is(':checked')){
                $('#question_activity').val('Yes');
            }
            else{
                 $('#question_activity').val('No');
            }
            notify();
    });
    jq("#credit_activity1").click(function(){
            console.clear();
            if ($("#credit_activity1").is(':checked')){
                $('#credit_activity').val('Yes');
            }
            else{
                 $('#credit_activity').val('No');
            }
            notify();
    });
    jq("#sound_on_notification1").click(function(){
            console.clear();
            if ($("#sound_on_notification1").is(':checked')){
                $('#sound_on_notification').val('Yes');
                $('#notification_sound').val('Yes');
            }
            else{
                 $('#sound_on_notification').val('No');
                 $('#notification_sound').val('No');
            }
            notify();
    });
    jq("#sound_on_message1").click(function(){
            console.clear();
            if ($("#sound_on_message1").is(':checked')){
                $('#sound_on_message').val('Yes');
            }
            else{
                 $('#sound_on_message').val('No');
            }
            notify();
    });
    
</script>