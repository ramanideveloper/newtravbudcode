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
use frontend\models\SecuritySetting;
use frontend\models\PrivacySetting;
use  yii\web\Session;
use yii\helpers\ArrayHelper;


$session = Yii::$app->session;


if($session->get('email'))
{
    $email = $session->get('email'); 
    /* $result Query For The Fetching Data From The 'tbl_user'*/
    
    $result = LoginForm::find()->where(['email' => $email])->one();
    $user = LoginForm::find()->where(['email' => $email])->one();
   
        $user_id = (string) $result['_id'];
        $username = $result['username'];
        $lname = $result['lname'];
        $password = $result['password'];
        $con_password = $result['con_password'];
        $birth_date = $result['birth_date'];
        $gender = $result['gender'];
        $city = $result['city'];
        $country = $result['country'];
        $phone = $result['phone'];
        $alternate_email = $result['alternate_email'];
        
    /* $result_setting Query For The Fetching Data From The 'user_setting'*/
	$result_setting = UserSetting::find()->where(['user_id' => $user_id])->one();
   
        $email_access = $result_setting['email_access'];
        
    /* $result_personal Query For The Fetching Data From The 'personal_info'*/
        
     $result_personal = Personalinfo::find()->where(['user_id' => $user_id])->one();
	 
    /* $result_security Query For The Fetching Data From The 'SecuritySetting'*/
        
     $result_security = SecuritySetting::find()->where(['user_id' => $user_id])->one();
	 
	 $security_questions = $result_security['security_questions'];
	 $security_questions = $result_security['security_questions'];
	 $answer = $result_security['answer'];
	 $view_photos = $result_security['view_photos'];
	 $eml_ans = $result_security['eml_ans'];
	 $born_ans = $result_security['born_ans'];
	 $gf_ans = $result_security['gf_ans'];
	 $my_view_status = $result_security['my_view_status'];
	 $my_post_view_status = $result_security['my_post_view_status'];
	 $restricted_list = $result_security['restricted_list'];
	 $blocked_list = $result_security['blocked_list'];
	 $block_event_invites = $result_security['block_event_invites'];
	 $pair_social_actions = $result_security['pair_social_actions'];
	 $contact_me = $result_security['contact_me'];
	 $message_filtering = $result_security['message_filtering'];
	 $friend_request = $result_security['friend_request'];
	 $bothering_me = $result_security['bothering_me'];
	 $dashboard_view_status = $result_security['dashboard_view_status'];
	 $add_public_wall = $result_security['add_public_wall'];
	 $see_public_wall = $result_security['see_public_wall'];
	 $review_posts = $result_security['review_posts'];
	 $view_posts_tagged_in = $result_security['view_posts_tagged_in'];
	 $view_others_posts_on_mywall = $result_security['view_others_posts_on_mywall'];
	 $review_tags = $result_security['review_tags'];
	 $recent_activities = $result_security['recent_activities'];
         $friend_list = $result_security['friend_list'];
         
	
     
    
     
     $privacy = PrivacySetting::find()->all();
     
     $listData=ArrayHelper::map($privacy,'privacy_code','privacy_name');
   
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
<section class="inner-body-content fb-page">
	<div class="fb-innerpage whitebg">
		<div class="setting-content">	 
			<?php include('includes/leftmenus.php');?>
			<div class="fbdetail-holder">
				
				<?php $form = ActiveForm::begin(['id' => 'frm-setting','options'=>['onsubmit'=>'return false;',],]); ?>  
									
				<div class="fb-formholder">		
					<div class="fb-formholder-box">	
						<div class="formtitle"><h4>Security Settings</h4></div>
						<div class="notice">	
							<div id="suceess" class="form-successmsg">Your Settings updated successfully</div>
							<div id="fail" class="form-failuremsg">Oops..!! Something Went Wrong in Step 2</div>
							<div id="info" class="form-infomsg">Your Security Settings Saved Successfully</div>
						</div>

						<ul class="setting-ul desc-settings">
							
							<li>
							  
								<div class="setting-group">							
									<div class="normal-mode">
										<div class="row">
											<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
												<label>Security Question</label>
											</div>
											<div class="col-lg-8 col-md-5 col-sm-5 col-xs-12">										
												<div class="info">																   				   								
													<label>                                                                                          
														Set your security question
													</label>
												</div>
											</div>
											<div class="col-lg-1 col-md-4 col-sm-4 col-xs-12 pull-right editbtn">
												<div class="pull-right  linkholder">
													<a href="javascript:void(0)" onClick="open_edit(this)"><i class="fa fa-pencil"></i></a>
												</div>
											</div>
										</div>	
									</div>	
									<div class="edit-mode">
										<div class="row">
											<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
												<label>Security Question</label>
											</div>
											<div class="col-lg-6 col-md-7 col-sm-9 col-xs-12">
												<div class="row">
													<div class="col-lg-9 col-md-7 col-sm-12 col-xs-12">
														<div class="form-group bm">						
															<div class="dropholder">													
																<div class="dropdown que-drop">
																	<button aria-expanded="false" data-toggle="dropdown" type="button" class="btn btn-default dropdown-toggle btn-sm custom-select custom-select-quest">
																	<?php
																															   
																		if($security_questions == "What is your email on file?")
																		{
																			echo '<span class="glyphicon glyphicon-question-sign">
																			
																			</span>';
																																				echo 'What is your email on file?';
																			
																		}
																		else if($security_questions == "What city you born?"){
																			echo '<span class="glyphicon glyphicon-question-sign">
																			
																			</span>What city you born?';
																		}
																																		else{
																																			echo '<span class="glyphicon glyphicon-question-sign">
																			
																			</span>What is your girl friend name?';
																																		}
																	?>
																	
																	<span class="caret"></span></button>
																	<ul class="dropdown-menu" id="quest">
																		<li class="sel-phone"><a href="javascript:void(0)" onClick="setQuestionIcon(this)"><span class="glyphicon glyphicon-question-sign"></span>What is your email on file?</a></li>
																		<li class="sel-friend"><a href="javascript:void(0)" onClick="setQuestionIcon(this)"><span class="glyphicon glyphicon-question-sign"></span>What city you born?</a></li>	
																																		<li class="sel-friend"><a href="javascript:void(0)" onClick="setQuestionIcon(this)"><span class="glyphicon glyphicon-question-sign"></span>What is your girl friend name?</a></li>	
																	</ul>													  													
																</div>
															</div>																	 
														</div>
													</div>
													<div class="col-lg-9 col-md-5 col-sm-12 col-xs-12">
														<div class="form-group eyeicon">	
															<?php if($security_questions == "What is your email on file?")
															{
															?>	
																<?= $form->field($model,'answer')->passwordInput(array('placeholder'=>'Answer','value'=>$eml_ans,'id'=>'answer'))->label(false)?>
															<?php
															}else if($security_questions == "What city you born?"){?>
																<?= $form->field($model,'answer')->passwordInput(array('placeholder'=>'Answer','value'=>$born_ans,'id'=>'answer'))->label(false)?>
															<?php	
															}
															else if($security_questions == "What is your girl friend name?"){?>
																<?= $form->field($model,'answer')->passwordInput(array('placeholder'=>'Answer','value'=>$gf_ans,'id'=>'answer'))->label(false)?>
															<?php	
															}
															else{ ?>
																<?= $form->field($model,'answer')->passwordInput(array('placeholder'=>'Answer','id'=>'answer'))->label(false)?>
															<?php	
															}
															?>
														   <a href="javascript:void(0)" class="showPass">
															<i class="fa fa-eye"></i>
															</a>
														</div>
													</div>
												</div>										
											</div>									
											<div class="col-lg-3 col-md-2 col-sm-5 col-xs-12 pull-right">
												<div class="form-group pull-right">						
													<div class="pull-right fb-btnholder nbm">	
													<?php
													if($security_questions == "What is your email on file?")
													{
													?>
														<input type="hidden" name="security_questions" id="security_questions" value="What is your email on file?" />
													<?php 
													}
													else if($security_questions == "What city you born?"){
													?>
													<input type="hidden" name="security_questions" id="security_questions" value="What city you born?" />
													<?php 
													}
													else if($security_questions == "What is your girl friend name?"){
													?>
													<input type="hidden" name="security_questions" id="security_questions" value="What is your girl friend name?" />
													<?php 
													}
													else{
														?>
														<input type="hidden" name="security_questions" id="security_questions" value="What is your girl friend name?" />
													<?php 	
													}
													?>
														<a class="btn btn-primary btn-sm" onClick="close_edit(this),setting()">Save</a>
														<a class="btn btn-primary btn-sm" onClick="close_edit(this)">Cancel</a>				
													</div>										
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
											<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
												<label>Lookup Setting</label>
											</div>
											<div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">										
												<div class="info">																   				   								
													<label>                                                                                          
														Who can look me up?
													</label>
												</div>
											</div>
											<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 editbtn fullbtn">
												<span class="thisSecurity-look thisSecurity"><?php echo $my_view_status; ?></span>
												<div class="pull-right  linkholder">
													<a href="javascript:void(0)" onClick="open_edit(this)"><i class="fa fa-pencil"></i></a>
												</div>
											</div>
										</div>	
									</div>	
									<div class="edit-mode">
										<div class="row">
											<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
												<label>Lookup Setting</label>
											</div>
											<div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">										
												<div class="info">																   				   								
													<label>                                                                                          
														Who can look me up?
													</label>
												</div>
											</div>
											<div class="col-lg-4 col-md-4 col-sm-9 col-xs-12 pull-right">
												<div class="form-group pull-left">						
													<div class="dropholder">													
														<div class="dropdown">
															<button aria-expanded="false" data-toggle="dropdown" type="button" class="btn btn-default dropdown-toggle btn-sm custom-select custom-select-look">
															<?php
																if($my_view_status == "Private")
																{
																	echo '<span class="glyphicon glyphicon-lock"></span>Private';
																	
																}
																else if($my_view_status == "Friends")
																{
																	echo '<span class="glyphicon glyphicon-user"></span>Friends';
																	
																}
																else if($my_view_status == "Public")
																{
																	echo '<span class="glyphicon glyphicon-globe"></span>Public';
																	
																}
																else{
																	echo '<span class="glyphicon glyphicon-globe"></span>Public';
																}
															?>
															
															<span class="caret"></span></button>
															<ul class="dropdown-menu" id="look">

																<li class="sel-Private"><a href="javascript:void(0)" onClick="setSelectIcon(this)"><span class="glyphicon glyphicon-lock"></span>Private</a></li>
																<li class="sel-Friends"><a href="javascript:void(0)" onClick="setSelectIcon(this)"><span class="glyphicon glyphicon-user"></span>Friends</a></li>
																<li class="sel-Public"><a href="javascript:void(0)" onClick="setSelectIcon(this)"><span class="glyphicon glyphicon-globe"></span>Public</a></li>

															</ul>													  													
														</div>
													</div>
												</div>						
											
												<div class="form-group pull-right">						
													<div class="pull-right fb-btnholder nbm">
														 <input type="hidden" name="my_view_status" id="my_view_status" value="Private" />
														 <a class="btn btn-primary btn-sm" onClick="close_edit(this),setting()">Save</a>
														 <a class="btn btn-primary btn-sm" onClick="close_edit(this)">Cancel</a>
													</div>										
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
											<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
												<label>Post Security</label>
											</div>
											<div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">										
												<div class="info">																   				   								
													<label>                                                                                          
														Who can see my posts?
													</label>
												</div>
											</div>
											<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 editbtn fullbtn">										
												<span class="thisSecurity-post thisSecurity"><?php echo $my_post_view_status; ?></span>
												<div class="pull-right  linkholder">
													<a href="javascript:void(0)" onClick="open_edit(this)"><i class="fa fa-pencil"></i></a>
												</div>
											</div>
										</div>	
									</div>	
									<div class="edit-mode">
										<div class="row">
											<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
												<label>Post Security</label>
											</div>
											<div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">										
												<div class="info">																   				   								
													<label>                                                                                          
														Who can see my posts?
													</label>
												</div>
											</div>
											<div class="col-lg-4 col-md-4 col-sm-9 col-xs-12 pull-right">
												<div class="form-group pull-left">						
													
													<div class="dropholder">													
														<div class="dropdown">
															<button aria-expanded="false" data-toggle="dropdown" type="button" class="btn btn-default dropdown-toggle btn-sm custom-select custom-select-post">
															<?php
																if($my_post_view_status == "Private")
																{
																	echo '<span class="glyphicon glyphicon-lock"></span>Private';	
																}
																else if($my_post_view_status == "Friends")
																{
																	echo '<span class="glyphicon glyphicon-user"></span>Friends';	
																}
																else if($my_post_view_status == "Public")
																{
																	echo '<span class="glyphicon glyphicon-globe"></span>Public';	
																}
																else{
																	echo '<span class="glyphicon glyphicon-lock"></span>Private';
																}
															?>
															
															<span class="caret"></span></button>
															<ul class="dropdown-menu" id="post">
																<li class="sel-Private"><a href="javascript:void(0)" onClick="setSelectIcon(this)"><span class="glyphicon glyphicon-lock"></span>Private</a></li>
																<li class="sel-Friends"><a href="javascript:void(0)" onClick="setSelectIcon(this)"><span class="glyphicon glyphicon-user"></span>Friends</a></li>
																<li class="sel-Public"><a href="javascript:void(0)" onClick="setSelectIcon(this)"><span class="glyphicon glyphicon-globe"></span>Public</a></li>
															</ul>													  													
														</div>
													</div>
												</div>						
											
												<div class="form-group pull-right">						
													<div class="pull-right fb-btnholder nbm">
														<input type="hidden" name="my_post_view_status" id="my_post_view_status" value="Public" />
														<a class="btn btn-primary btn-sm" onClick="close_edit(this),setting()">Save</a>
														<a class="btn btn-primary btn-sm" onClick="close_edit(this)">Cancel</a>
													</div>										
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
											<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
												<label>Photo Security</label>
											</div>
											<div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">										
												<div class="info">																   				   								
													<label>                                                                                          
														Who can see my photos?
													</label>
												</div>
											</div>
											<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 editbtn fullbtn">										
												<span class="thisSecurity-photo thisSecurity"><?php echo $view_photos; ?></span>
												<div class="pull-right  linkholder">
													<a href="javascript:void(0)" onClick="open_edit(this)"><i class="fa fa-pencil"></i></a>
												</div>
											</div>
										</div>	
									</div>	
									<div class="edit-mode">
										<div class="row">
											<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
												<label>Photo Security</label>
											</div>
											<div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">										
												<div class="info">																   				   								
													<label>                                                                                          
														Who can see my photos?
													</label>
												</div>
											</div>
											<div class="col-lg-4 col-md-4 col-sm-9 col-xs-12 pull-right">
												<div class="form-group pull-left">						
													
													<div class="dropholder">													
														<div class="dropdown">
															<button aria-expanded="false" data-toggle="dropdown" type="button" class="btn btn-default dropdown-toggle btn-sm custom-select custom-select-photo">
															<?php
																if($view_photos == "Private")
																{
																	echo '<span class="glyphicon glyphicon-lock"></span>Private';	
																}
																else if($view_photos == "Friends")
																{
																	echo '<span class="glyphicon glyphicon-user"></span>Friends';	
																}
																else if($view_photos == "Public")
																{
																	echo '<span class="glyphicon glyphicon-globe"></span>Public';	
																}
																else{
																	echo '<span class="glyphicon glyphicon-lock"></span>Private';
																}
															?>
															
															<span class="caret"></span></button>
															<ul class="dropdown-menu" id="photo">
																<li class="sel-Private"><a href="javascript:void(0)" onClick="setSelectIcon(this)"><span class="glyphicon glyphicon-lock"></span>Private</a></li>
																<li class="sel-Friends"><a href="javascript:void(0)" onClick="setSelectIcon(this)"><span class="glyphicon glyphicon-user"></span>Friends</a></li>
																<li class="sel-Public"><a href="javascript:void(0)" onClick="setSelectIcon(this)"><span class="glyphicon glyphicon-globe"></span>Public</a></li>
															</ul>													  													
														</div>
													</div>
												</div>						
											
												<div class="form-group pull-right">						
													<div class="pull-right fb-btnholder nbm">
														<input type="hidden" name="view_photos" id="view_photos" value="Public" />
														<a class="btn btn-primary btn-sm" onClick="close_edit(this),setting()">Save</a>
														<a class="btn btn-primary btn-sm" onClick="close_edit(this)">Cancel</a>
													</div>										
												</div>										
											</div>
										</div>	
									</div>	
								</div>	
												 
							</li>
							
							
							<!--<li>                        
								<div class="setting-group">							
									<div class="normal-mode">
										<div class="row">
											<div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
												<label>Pair Ads</label>
											</div>									
											<div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">										
												<div class="pull-right" id="friend">	
													<div class="switch">
														<?php if ($pair_social_actions == 'No'){   
															 echo '<input id="pair_social_actions1" class="cmn-toggle cmn-toggle-round" type="checkbox">';
														}
														else{
															 echo '<input id="pair_social_actions1" class="cmn-toggle cmn-toggle-round" checked type="checkbox">';
														}
														?>
													   
														<label for="pair_social_actions1"></label>
													</div>
								
													<?php
														if(isset($pair_social_actions) && !empty($pair_social_actions)){
															echo '<input type="hidden" name="pair_social_actions" id="pair_social_actions" value="'.$pair_social_actions.'" />';
														}else{
															echo '<input type="hidden" name="pair_social_actions" id="pair_social_actions" value="Yes" />';	
														}
													?>
													
												</div>
											</div>
										</div>	
									</div>								
								</div>	                                               
							</li>-->
							
							
							<li>
								
								<div class="setting-group">							
									<div class="normal-mode">
										<div class="row">
											<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
												<label>Contact Settings</label>
											</div>
											<div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">										
												<div class="info">																   				   								
													<label>                                                                                          
														Who can contact me?
													</label>
												</div>
											</div>
											<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 editbtn fullbtn">										
												<span class="thisSecurity-contact thisSecurity"><?php echo $contact_me; ?></span>
												<div class="pull-right  linkholder">
													<a href="javascript:void(0)" onClick="open_edit(this)"><i class="fa fa-pencil"></i></a>
												</div>
											</div>
										</div>	
									</div>	
									<div class="edit-mode">
										<div class="row">
											<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
												<label>Contact Settings</label>
											</div>
											<div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">										
												<div class="info">																   				   								
													<label>                                                                                          
														Who can contact me?
													</label>
												</div>
											</div>
											<div class="col-lg-4 col-md-4 col-sm-9 col-xs-12 pull-right">
												<div class="form-group pull-left">					
													
													<div class="dropholder">													
														<div class="dropdown">
															<button aria-expanded="false" data-toggle="dropdown" type="button" class="btn btn-default dropdown-toggle btn-sm custom-select custom-select-contact">
															
															<?php 
														   
																if($contact_me == 'Private'){
																 
																	echo '<span class="glyphicon glyphicon-lock"></span>Private';
																	
																}
																else if($contact_me == 'Friends'){
																   
																	echo '<span class="glyphicon glyphicon-user"></span>Friends';
																}
																else if($contact_me == 'Public'){
																 
																	echo ' <span class="glyphicon glyphicon-globe"></span>Public';
																}
																														else{
																															echo '<span class="glyphicon glyphicon-lock"></span>';
																														}
																?>
															<span class="caret"></span></button>
															<ul class="dropdown-menu" id="contact">
																<li  id="Private" class="sel-Private"><a href="javascript:void(0)" onClick="setSelectIcon(this)"><span class="glyphicon glyphicon-lock"></span>Private</a></li>
																<li id="Friends" class="sel-Friends"><a href="javascript:void(0)" onClick="setSelectIcon(this)"><span class="glyphicon glyphicon-user"></span>Friends</a></li>
																<li id="Public" class="sel-Public"><a href="javascript:void(0)" onClick="setSelectIcon(this)"><span class="glyphicon glyphicon-globe"></span>Public</a></li>														
																													
																
															</ul>													  													
														</div>
													</div>
												</div>						
											
												<div class="form-group pull-right">						
													<div class="pull-right fb-btnholder nbm">
														<input type="hidden" name="contact_me" id="contact_me" value="Public" />
														<a class="btn btn-primary btn-sm" onClick="close_edit(this),setting()">Save</a>
														<a class="btn btn-primary btn-sm" onClick="close_edit(this)">Cancel</a>
													</div>										
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
											<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
												<label>Friend Request Settings</label>
											</div>
											<div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">										
												<div class="info">																   				   								
													<label>                                                                                          
														Who can send me friend requests?
													</label>
												</div>
											</div>
											<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 editbtn fullbtn">										
												<span class="thisSecurity-friend thisSecurity"><?php echo $friend_request; ?></span>
												<div class="pull-right  linkholder">
													<a href="javascript:void(0)" onClick="open_edit(this)"><i class="fa fa-pencil"></i></a>
												</div>
											</div>
										</div>	
									</div>	
									<div class="edit-mode">
										<div class="row">
											<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
												<label>Friend Request Settings</label>
											</div>
											<div class="col-lg-5 col-md-5 col-sm-9 col-xs-12">										
												<div class="info">																   				   								
													<label>                                                                                          
														Who can send me friend requests?
													</label>
												</div>
											</div>
											<div class="col-lg-4 col-md-4 col-sm-9 col-xs-12 pull-right fof">
												<div class="form-group pull-left">						
													 
													 <div class="dropholder">													
														<div class="dropdown">
															<button aria-expanded="false" data-toggle="dropdown" type="button" class="btn btn-default dropdown-toggle btn-sm custom-select custom-select-friend">
															
															<?php 
														   
																if($friend_request == 'Friends of Friends'){
																
																		echo '<span class="glyphicon glyphicon-record"></span>Friends of Friends';
																}
																else if($friend_request == "Public"){
																	
																	echo '<span class="glyphicon glyphicon-globe"></span>Public';
																}
																else{
																		echo '<span class="glyphicon glyphicon-record"></span>Friends of Friends';	
																}
																?>
															<span class="caret"></span></button>
															<ul class="dropdown-menu" id="friend">
																<li class="sel-Public"><a href="javascript:void(0)" onClick="setSelectIcon(this)"><span class="glyphicon glyphicon-globe"></span>Public</a></li>
																<li class="sel-Friends-of-Friends"><a href="javascript:void(0)" onClick="setSelectIcon(this)"><span class="glyphicon glyphicon-record"></span>Friends of Friends</a></li>
																
															</ul>													  													
														</div>
													</div>
												</div>						
											
												<div class="form-group pull-right">						
													<div class="pull-right fb-btnholder nbm">
														<input type="hidden" name="friend_request" id="friend_request" value="Friends of Friends" />
														<a class="btn btn-primary btn-sm" onClick="close_edit(this),setting()">Save</a>
														<a class="btn btn-primary btn-sm" onClick="close_edit(this)">Cancel</a>				
													</div>										
												</div>										
											</div>
										</div>	
									</div>	
								</div>	
												 
							</li>
					
							<!--<li>
								<div class="setting-group">							
									<div class="normal-mode">
										<div class="row">
											<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
												<label>Wall View Permission</label>
											</div>
											<div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">										
												<div class="info">			
													<label>                                                                                          
														Can search engine look my dashbroad up 
													</label>
												</div>
											</div>
											<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 editbtn fullbtn">										
												
												<span class="thisSecurity-dashboard thisSecurity"><?php echo $dashboard_view_status; ?></span>
												<div class="pull-right  linkholder">
													<a href="javascript:void(0)" onClick="open_edit(this)"><i class="fa fa-pencil"></i></a>
												</div>
											</div>
										</div>	
									</div>	
									<div class="edit-mode">
										<div class="row">
											<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
												<label>Wall View Permission</label>
											</div>
											<div class="col-lg-5 col-md-5 col-sm-9 col-xs-12">										
												<div class="info">				
													<label>                                                                                          
														Can search engine look my dashbroad up 
													</label>
												</div>
											</div>
											<div class="col-lg-4 col-md-4 col-sm-9 col-xs-12 pull-right">
												<div class="form-group  pull-left">
													<div class="dropholder">													
														<div class="dropdown">
															<button aria-expanded="false" data-toggle="dropdown" type="button" class="btn btn-default dropdown-toggle btn-sm custom-select custom-select-dashboard">
															
															<?php 
														   
																if($dashboard_view_status == 'Private'){
																
																		echo '<span class="glyphicon glyphicon-lock"></span>Private';
																}
																else if($dashboard_view_status == "Public"){
																	
																	echo '<span class="glyphicon glyphicon-globe"></span>Public';
																}
																else if($dashboard_view_status == "Friends"){
																	
																	echo '<span class="glyphicon glyphicon-user"></span>Friends';
																}
																else{
																		echo '<span class="glyphicon glyphicon-user"></span>Friends';	
																}
																?>
															<span class="caret"></span></button>
															<ul class="dropdown-menu" id="dashboard">														
																<li class="sel-Private"><a href="javascript:void(0)" onClick="setSelectIcon(this)"><span class="glyphicon glyphicon-lock"></span>Private</a></li>
																<li class="sel-Friends"><a href="javascript:void(0)" onClick="setSelectIcon(this)"><span class="glyphicon glyphicon-user"></span>Friends</a></li>
																<li class="sel-Public"><a href="javascript:void(0)" onClick="setSelectIcon(this)"><span class="glyphicon glyphicon-globe"></span>Public</a></li>
															</ul>													  													
														</div>
													</div>
												
												</div>						
											
												<div class="form-group pull-right">						
													<div class="pull-right fb-btnholder nbm">
														<input type="hidden" name="dashboard_view_status" id="dashboard_view" value="Friends" />
														<a class="btn btn-primary btn-sm" onClick="close_edit(this),setting()">Save</a>
														<a class="btn btn-primary btn-sm" onClick="close_edit(this)">Cancel</a>				
													</div>										
												</div>									
											</div>
										</div>	
									</div>	
								</div>	
								
							</li>-->
							<!--<li>                        
								<div class="setting-group">							
									<div class="normal-mode">
										<div class="row">
											<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
												<label>Dashboard View Permission</label>
											</div>					
											<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 editbtn fullbtn">										
												<span class="thisSecurity-dashboard thisSecurity"><?php echo $dashboard_view_status; ?></span>
												<div class="pull-right  linkholder">
													<a href="javascript:void(0)" onClick="open_edit(this)"><i class="fa fa-pencil"></i></a>
												</div>
											</div>									
										</div>	
									</div>	
									<div class="edit-mode">
										<div class="row">
											<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
												<label>Dashboard View Permission</label>
											</div>
											<div class="col-lg-9 col-md-9 col-sm-9 col-xs-12 pull-right">
												<div class="form-group pull-left">						
													 
													 <div class="dropholder">													
														<div class="dropdown">
															<button aria-expanded="false" data-toggle="dropdown" type="button" class="btn btn-default dropdown-toggle btn-sm custom-select custom-select-dashboard">
															
															<?php 
														   
																if($dashboard_view_status == 'Private'){
																
																		echo '<span class="glyphicon glyphicon-lock"></span>Private';
																}
																else if($dashboard_view_status == "Public"){
																	
																	echo '<span class="glyphicon glyphicon-globe"></span>Public';
																}
																else if($dashboard_view_status == "Friends"){
																	
																	echo '<span class="glyphicon glyphicon-user"></span>Friends';
																}
																else{
																		echo '<span class="glyphicon glyphicon-user"></span>Friends';	
																}
																?>
															<span class="caret"></span></button>
															<ul class="dropdown-menu" id="dashboard">														
																<li class="sel-Private"><a href="javascript:void(0)" onClick="setSelectIcon(this)"><span class="glyphicon glyphicon-lock"></span>Private</a></li>
																<li class="sel-Friends"><a href="javascript:void(0)" onClick="setSelectIcon(this)"><span class="glyphicon glyphicon-user"></span>Friends</a></li>
																<li class="sel-Public"><a href="javascript:void(0)" onClick="setSelectIcon(this)"><span class="glyphicon glyphicon-globe"></span>Public</a></li>
															</ul>													  													
														</div>
													</div>
												</div>						
											
												<div class="form-group pull-right">						
													<div class="pull-right fb-btnholder nbm">
														<input type="hidden" name="dashboard_view_status" id="dashboard_view" value="Friends" />
														<a class="btn btn-primary btn-sm" onClick="close_edit(this),setting()">Save</a>
														<a class="btn btn-primary btn-sm" onClick="close_edit(this)">Cancel</a>				
													</div>										
												</div>										
											</div>
										</div>	
									</div>	
								</div>	                                               
							</li>-->
							
							<li>
								
								<div class="setting-group">							
									<div class="normal-mode">
										<div class="row">
											<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
												<label>Posting Permission</label>
											</div>
											<div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">										
												<div class="info">																   				   								
													<label>                                                                                          
														Who can add stuff to my public Wall
													</label>
												</div>
											</div>
											<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 editbtn fullbtn">										
												<span class="thisSecurity-posting thisSecurity"><?php echo $add_public_wall; ?></span>
												<div class="pull-right  linkholder">
													<a href="javascript:void(0)" onClick="open_edit(this)"><i class="fa fa-pencil"></i></a>
												</div>
											</div>
										</div>	
									</div>	
									<div class="edit-mode">
										<div class="row">
											<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
												<label>Posting Permission</label>
											</div>
											<div class="col-lg-5 col-md-5 col-sm-9 col-xs-12">										
												<div class="info">																   				   								
													<label>                                                                                          
														Who can add stuff to my public Wall
													</label>
												</div>
											</div>
											<div class="col-lg-4 col-md-4 col-sm-9 col-xs-12 pull-right">
												<div class="form-group pull-left">
													<div class="dropholder">													
														<div class="dropdown">
															<button aria-expanded="false" data-toggle="dropdown" type="button" class="btn btn-default dropdown-toggle btn-sm custom-select custom-select-posting">
															
															<?php 
														   
																if($add_public_wall == 'Private'){
																 
																	echo '<span class="glyphicon glyphicon-lock"></span>Private';
																	
																}
																else if($add_public_wall == 'Friends'){
																   
																	echo '<span class="glyphicon glyphicon-user"></span>Friends';
																}
																else if($add_public_wall == 'Public'){
																 
																	echo ' <span class="glyphicon glyphicon-globe"></span>Public';
																}
																else{
																	echo '<span class="glyphicon glyphicon-lock"></span>Private';
																}
																														
																?>
															<span class="caret"></span></button>
															<ul class="dropdown-menu" id="posting">
																<li  id="Private" class="sel-Private"><a href="javascript:void(0)" onClick="setSelectIcon(this)"><span class="glyphicon glyphicon-lock"></span>Private</a></li>
																	<li id="Friends" class="sel-Friends"><a href="javascript:void(0)" onClick="setSelectIcon(this)"><span class="glyphicon glyphicon-user"></span>Friends</a></li>
																	<li id="Public" class="sel-Public"><a href="javascript:void(0)" onClick="setSelectIcon(this)"><span class="glyphicon glyphicon-globe"></span>Public</a></li>															
																
															</ul>													  													
														</div>
													</div>
												</div>						
											
												<div class="form-group pull-right">
													<div class="pull-right fb-btnholder nbm">	
														 <input type="hidden" name="add_public_wall" id="add_public_wall" value="Private" />
														 <a class="btn btn-primary btn-sm" onClick="close_edit(this),setting()">Save</a>
														<a class="btn btn-primary btn-sm" onClick="close_edit(this)">Cancel</a>
													</div>										
												</div>										
											</div>
										</div>	
									</div>	
								</div>	
												 
							</li>
							<!--<li>
								
								<div class="setting-group">							
									<div class="normal-mode">
										<div class="row">
											<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
												<label>View Permission</label>
											</div>
											<div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">										
												<div class="info">																   				   								
													<label>                                                                                          
														Who can see stuff on my public Wall
													</label>
												</div>
											</div>
											<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 editbtn fullbtn">										
												<span class="thisSecurity-view thisSecurity"><?php echo $see_public_wall; ?></span>
												<div class="pull-right  linkholder">
													<a href="javascript:void(0)" onClick="open_edit(this)"><i class="fa fa-pencil"></i></a>
												</div>
											</div>
										</div>	
									</div>	
									<div class="edit-mode">
										<div class="row">
											<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
												<label>View Permission</label>
											</div>
											<div class="col-lg-5 col-md-5 col-sm-9 col-xs-12">										
												<div class="info">																   				   								
													<label>                                                                                          
														Who can see stuff on my public Wall
													</label>
												</div>
											</div>
											<div class="col-lg-4 col-md-4 col-sm-9 col-xs-12 pull-right">
												<div class="form-group pull-left">
													<div class="dropholder">													
														<div class="dropdown">
															<button aria-expanded="false" data-toggle="dropdown" type="button" class="btn btn-default dropdown-toggle btn-sm custom-select custom-select-view">
														
																<?php 
														   
																if($see_public_wall == 'Private'){
																 
																	echo '<span class="glyphicon glyphicon-lock"></span>Private';
																	
																}
																else if($see_public_wall == 'Friends'){
																   
																	echo '<span class="glyphicon glyphicon-user"></span>Friends';
																}
																else if($see_public_wall == 'Public'){
																 
																	echo ' <span class="glyphicon glyphicon-globe"></span>Public';
																}
																else{
																	echo '<span class="glyphicon glyphicon-lock"></span>Private';
																}
																														
																?>
															<span class="caret"></span></button>
															<ul class="dropdown-menu" id="view">
																<li class="sel-Private"><a href="javascript:void(0)" onClick="setSelectIcon(this)"><span class="glyphicon glyphicon-lock"></span>Private</a></li>
																<li class="sel-Friends"><a href="javascript:void(0)" onClick="setSelectIcon(this)"><span class="glyphicon glyphicon-user"></span>Friends</a></li>
																<li class="sel-Public"><a href="javascript:void(0)" onClick="setSelectIcon(this)"><span class="glyphicon glyphicon-globe"></span>Public</a></li>														
																
															</ul>													  													
														</div>
													</div>
												</div>						
											
												<div class="form-group pull-right">
													<div class="pull-right fb-btnholder nbm">	
														<input type="hidden" name="see_public_wall" id="see_public_wall" value="Private" />
														<a class="btn btn-primary btn-sm" onClick="close_edit(this),setting()">Save</a>
														<a class="btn btn-primary btn-sm" onClick="close_edit(this)">Cancel</a>
													</div>										
												</div>										
											</div>
										</div>	
									</div>	
								</div>	
											   
							</li>-->
							<li>
								
								<div class="setting-group">							
									<div class="normal-mode">
										<div class="row">
											<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
												<label>Post Review</label>
											</div>
											<div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">										
												<div class="info">																   				   								
													<label>                                                                                          
														Review posts friends tag you in before they appear on your public wall
													</label>
												</div>
											</div>
											<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 editbtn fullbtn">										
												<span class="thisSecurity-review thisSecurity"><?php echo $review_posts; ?></span>
												<div class="pull-right  linkholder">
													<a href="javascript:void(0)" onClick="open_edit(this)"><i class="fa fa-pencil"></i></a>
												</div>
											</div>
										</div>	
									</div>	
									<div class="edit-mode">
										<div class="row">
											<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
												<label>Post Review</label>
											</div>
											<div class="col-lg-5 col-md-5 col-sm-9 col-xs-12">										
												<div class="info">																   				   								
													<label>                                                                                          
														Review posts friends tag you in before they appear on your public wall
													</label>
												</div>
											</div>
											<div class="col-lg-4 col-md-4 col-sm-9 col-xs-12 pull-right">
												<div class="form-group  pull-left">
													<div class="dropholder">													
														<div class="dropdown">
															<button aria-expanded="false" data-toggle="dropdown" type="button" class="btn btn-default dropdown-toggle btn-sm custom-select custom-select-review">
																
															<?php 
														   
																if($review_posts == 'Enabled'){
																 
																	echo '<span class="glyphicon glyphicon-ok-sign"></span>Enabled';
																	
																}
																else if($review_posts == 'Disabled'){
																 
																	echo '<span class="glyphicon glyphicon-remove-sign"></span>Disabled';
																}
																else{
																	echo '<span class="glyphicon glyphicon-ok-sign"></span>Enabled';
																}
																?>
															<span class="caret"></span></button>
															<ul class="dropdown-menu" id="review">
																<li class="sel-Enabled"><a href="javascript:void(0)" onClick="setSelectIcon(this)"><span class="glyphicon glyphicon-ok-sign"></span>Enabled</a></li>
																<li class="sel-Disabled"><a href="javascript:void(0)" onClick="setSelectIcon(this)"><span class="glyphicon glyphicon-remove-sign"></span>Disabled</a></li>											
																
															</ul>													  													
														</div>
													</div>
												</div>						
											
												<div class="form-group pull-right">						
													<div class="pull-right fb-btnholder nbm">
														 <input type="hidden" name="review_posts" id="review_posts" value="Disabled" />
														 <a class="btn btn-primary btn-sm" onClick="close_edit(this),setting()">Save</a>
														<a class="btn btn-primary btn-sm" onClick="close_edit(this)">Cancel</a>
													</div>										
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
											<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
												<label>Tagged Post View</label>
											</div>
											<div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">										
												<div class="info">																   				   								
													<label>                                                                                          
														Who can see posts you've been tagged in on your public Wall
													</label>
												</div>
											</div>
											<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 editbtn fullbtn">										
												<span class="thisSecurity-taged thisSecurity"><?php echo $view_posts_tagged_in; ?></span>
												<div class="pull-right  linkholder">
													<a href="javascript:void(0)" onClick="open_edit(this)"><i class="fa fa-pencil"></i></a>
												</div>
											</div>
										</div>	
									</div>	
									<div class="edit-mode">
										<div class="row">
											<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
												<label>Tagged Post View</label>
											</div>
											<div class="col-lg-5 col-md-5 col-sm-9 col-xs-12">										
												<div class="info">																   				   								
													<label>                                                                                          
														Who can see posts you've been tagged in on your public Wall
													</label>
												</div>
											</div>
											<div class="col-lg-4 col-md-4 col-sm-9 col-xs-12 pull-right">
												<div class="form-group pull-left">
													<div class="dropholder">													
														<div class="dropdown">
															<button aria-expanded="false" data-toggle="dropdown" type="button" class="btn btn-default dropdown-toggle btn-sm custom-select custom-select-taged">
															<?php 
														   
																if($view_posts_tagged_in == 'Private'){
																 
																	echo '<span class="glyphicon glyphicon-lock"></span>Private';
																	
																}
																else if($view_posts_tagged_in == 'Friends'){
																   
																	echo '<span class="glyphicon glyphicon-user"></span>Friends';
																}
																else if($view_posts_tagged_in == 'Public'){
																 
																	echo ' <span class="glyphicon glyphicon-globe"></span>Public';
																}
																else{
																	echo '<span class="glyphicon glyphicon-lock"></span>Private';
																}
																?>
																<span class="caret"></span></button>
															<ul class="dropdown-menu" id="taged">
																<li class="sel-Private"><a href="javascript:void(0)" onClick="setSelectIcon(this)"><span class="glyphicon glyphicon-lock"></span>Private</a></li>
																<li class="sel-Friends"><a href="javascript:void(0)" onClick="setSelectIcon(this)"><span class="glyphicon glyphicon-user"></span>Friends</a></li>
																<li class="sel-Public"><a href="javascript:void(0)" onClick="setSelectIcon(this)"><span class="glyphicon glyphicon-globe"></span>Public</a></li>														
																
															</ul>													  													
														</div>
													</div>
												</div>						
											
												<div class="form-group pull-right">						
													<div class="pull-right fb-btnholder nbm">	
														 <input type="hidden" name="view_posts_tagged_in" id="view_posts_tagged_in" value="Public" />
														 <a class="btn btn-primary btn-sm" onClick="close_edit(this),setting()">Save</a>
														<a class="btn btn-primary btn-sm" onClick="close_edit(this)">Cancel</a>
													</div>										
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
											<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
												<label>Other's Post View</label>
											</div>
											<div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">										
												<div class="info">																   				   								
													<label>                                                                                          
														Who can see what others post on your public Wall
													</label>
												</div>
											</div>
											<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 editbtn fullbtn">										
												<span class="thisSecurity-other_posts thisSecurity"><?php echo $view_others_posts_on_mywall; ?></span>
												<div class="pull-right  linkholder">
													<a href="javascript:void(0)" onClick="open_edit(this)"><i class="fa fa-pencil"></i></a>
												</div>
											</div>
										</div>	
									</div>	
									<div class="edit-mode">
										<div class="row">
											<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
												<label>Other's Post View</label>
											</div>
											<div class="col-lg-5 col-md-5 col-sm-9 col-xs-12">										
												<div class="info">																   				   								
													<label>                                                                                          
														Who can see what others post on your public Wall
													</label>
												</div>
											</div>
											<div class="col-lg-4 col-md-4 col-sm-9 col-xs-12 pull-right">
												<div class="form-group pull-left">																	
													<div class="dropholder">													
														<div class="dropdown">
															<button aria-expanded="false" data-toggle="dropdown" type="button" class="btn btn-default dropdown-toggle btn-sm custom-select custom-select-other_posts">
															
															<?php 
														   
																if($view_others_posts_on_mywall == 'Private'){
																 
																	echo '<span class="glyphicon glyphicon-lock"></span>Private';
																	
																}
																else if($view_others_posts_on_mywall == 'Friends'){
																   
																	echo '<span class="glyphicon glyphicon-user"></span>Friends';
																}
																else if($view_others_posts_on_mywall == 'Public'){
																 
																	echo ' <span class="glyphicon glyphicon-globe"></span>Public';
																}
																else{
																	echo '<span class="glyphicon glyphicon-lock"></span>Private';
																}
																?>
															<span class="caret"></span></button>
															<ul class="dropdown-menu" id="other_posts">
																<li class="sel-Private"><a href="javascript:void(0)" onClick="setSelectIcon(this)"><span class="glyphicon glyphicon-lock"></span>Private</a></li>
																<li class="sel-Friends"><a href="javascript:void(0)" onClick="setSelectIcon(this)"><span class="glyphicon glyphicon-user"></span>Friends</a></li>
																<li class="sel-Public"><a href="javascript:void(0)" onClick="setSelectIcon(this)"><span class="glyphicon glyphicon-globe"></span>Public</a></li>														
																
															</ul>													  													
														</div>
													</div>
												</div>
												
												<div class="form-group pull-right">
													<div class="pull-right fb-btnholder nbm">	
														<input type="hidden" name="view_others_posts_on_mywall" id="view_others_posts_on_mywall" value="Public" />
														<a class="btn btn-primary btn-sm" onClick="close_edit(this),setting()">Save</a>
														<a class="btn btn-primary btn-sm" onClick="close_edit(this)">Cancel</a>				
													</div>										
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
											<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
												<label>Tag Reviews</label>
											</div>
											<div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">										
												<div class="info">			
													<label>                                                                                          
														Review tags people add to your own posts before the tags appear on site
													</label>
												</div>
											</div>
											<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 editbtn fullbtn">										
												<span class="thisSecurity-reviewtags thisSecurity"><?php echo $review_tags; ?></span>
												<div class="pull-right  linkholder">
													<a href="javascript:void(0)" onClick="open_edit(this)"><i class="fa fa-pencil"></i></a>
												</div>
											</div>
										</div>	
									</div>	
									<div class="edit-mode">
										<div class="row">
											<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
												<label>Tag Reviews</label>
											</div>
											<div class="col-lg-5 col-md-5 col-sm-9 col-xs-12">										
												<div class="info">				
													<label>                                                                                          
														Review tags people add to your own posts before the tags appear on site
													</label>
												</div>
											</div>
											<div class="col-lg-4 col-md-4 col-sm-9 col-xs-12 pull-right">
												<div class="form-group  pull-left">
													<div class="dropholder">													
														<div class="dropdown">
															<button aria-expanded="false" data-toggle="dropdown" type="button" class="btn btn-default dropdown-toggle btn-sm custom-select custom-select-reviewtags">
															
															<?php 
														   
																if($review_tags == 'Enabled'){
																
																		echo '<span class="glyphicon glyphicon-user"></span>Enabled';
																}
																else if($review_tags == "Disabled"){
																	
																	echo '<span class="glyphicon glyphicon-lock"></span>Disabled';
																}
																
																else{
																		echo '<span class="glyphicon glyphicon-user"></span>Enabled';	
																}
																?>
															<span class="caret"></span></button>
															<ul class="dropdown-menu" id="reviewtags">														
																<li class="sel-Disabled"><a href="javascript:void(0)" onClick="setSelectIcon(this)"><span class="glyphicon glyphicon-lock"></span>Disabled</a></li>
																<li class="sel-Enabled"><a href="javascript:void(0)" onClick="setSelectIcon(this)"><span class="glyphicon glyphicon-user"></span>Enabled</a></li>
																
															</ul>													  													
														</div>
													</div>
												
												</div>						
											
												<div class="form-group pull-right">						
													<div class="pull-right fb-btnholder nbm">
														<input type="hidden" name="review_tags" id="review_tags" value="Enabled" />
														<a class="btn btn-primary btn-sm" onClick="close_edit(this),setting()">Save</a>
														<a class="btn btn-primary btn-sm" onClick="close_edit(this)">Cancel</a>				
													</div>										
												</div>																			
											</div>
										</div>	
									</div>	
								</div>	
								
							</li>
							
							<!--<li>                        
								<div class="setting-group">							
									
									<div class="normal-mode">
										<div class="row">
											<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
												<label>Tag Reviews</label>
											</div>	
						
											<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 editbtn fullbtn">										
												<span class="thisSecurity-reviewtags thisSecurity"><?php echo $review_tags; ?></span>
												<div class="pull-right  linkholder">
													<a href="javascript:void(0)" onClick="open_edit(this)"><i class="fa fa-pencil"></i></a>
												</div>
											</div>									
										</div>	
									</div>	
									<div class="edit-mode">
										<div class="row">
											<div class="col-lg-8 col-md-8 col-sm-3 col-xs-12">
												<label>Tag Reviews</label>
											</div>
											<div class="col-lg-4 col-md-5 col-sm-9 col-xs-12 pull-right">
												<div class="form-group pull-left">						
													 
													 <div class="dropholder">													
														<div class="dropdown">
															<button aria-expanded="false" data-toggle="dropdown" type="button" class="btn btn-default dropdown-toggle btn-sm custom-select custom-select-reviewtags">
															
															<?php 
														   
																if($dashboard_view_status == 'Private'){
																
																		echo '<span class="glyphicon glyphicon-lock"></span>Private';
																}
																else if($dashboard_view_status == "Public"){
																	
																	echo '<span class="glyphicon glyphicon-globe"></span>Public';
																}
																else if($dashboard_view_status == "Friends"){
																	
																	echo '<span class="glyphicon glyphicon-user"></span>Friends';
																}
																else{
																		echo '<span class="glyphicon glyphicon-user"></span>Friends';	
																}
																?>
															<span class="caret"></span></button>
															<ul class="dropdown-menu" id="reviewtags">														
																<li class="sel-Private"><a href="javascript:void(0)" onClick="setSelectIcon(this)"><span class="glyphicon glyphicon-lock"></span>Private</a></li>
																<li class="sel-Friends"><a href="javascript:void(0)" onClick="setSelectIcon(this)"><span class="glyphicon glyphicon-user"></span>Friends</a></li>
																<li class="sel-Public"><a href="javascript:void(0)" onClick="setSelectIcon(this)"><span class="glyphicon glyphicon-globe"></span>Public</a></li>
															</ul>													  													
														</div>
													</div>
												</div>						
											
												<div class="form-group pull-right">						
													<div class="pull-right fb-btnholder nbm">
														<input type="hidden" name="review_tags" id="review_tags" value="Friends" />
														<a class="btn btn-primary btn-sm" onClick="close_edit(this),setting()">Save</a>
														<a class="btn btn-primary btn-sm" onClick="close_edit(this)">Cancel</a>				
													</div>										
												</div>										
											</div>
										</div>	
									</div>
								</div>	                                               
							</li>-->
							
							
							<li>
								
								<div class="setting-group">							
									<div class="normal-mode">
										<div class="row">
											<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
												<label>Activity Permission</label>
											</div>
											<div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">										
												<div class="info">																   				   								
													<label>                                                                                          
														Who can see my Recent Activities on my public Wall?
													</label>
												</div>
											</div>
											<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 editbtn fullbtn">										
												<span class="thisSecurity-recent thisSecurity"><?php echo $recent_activities; ?></span>
												<div class="pull-right  linkholder">
													<a href="javascript:void(0)" onClick="open_edit(this)"><i class="fa fa-pencil"></i></a>
												</div>
											</div>
										</div>	
									</div>	
									<div class="edit-mode">
										<div class="row">
											<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
												<label>Activity Permission</label>
											</div>
											<div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">										
												<div class="info">																   				   								
													<label>                                                                                          
														Who can see my Recent Activities on my public Wall?
													</label>
												</div>
											</div>
											<div class="col-lg-4 col-md-4 col-sm-9 col-xs-12 pull-right">
												<div class="form-group pull-left">
													<div class="dropholder">													
														<div class="dropdown">
															<button aria-expanded="false" data-toggle="dropdown" type="button" class="btn btn-default dropdown-toggle btn-sm custom-select custom-select-recent">
															<?php 
														   
																if($recent_activities == 'Private'){
																 
																	echo '<span class="glyphicon glyphicon-lock"></span>Private';
																	
																}
																else if($recent_activities == 'Friends'){
																   
																	echo '<span class="glyphicon glyphicon-user"></span>Friends';
																}
																else if($recent_activities == 'Public'){
																 
																	echo ' <span class="glyphicon glyphicon-globe"></span>Public';
																}
																else{
																	echo '<span class="glyphicon glyphicon-lock"></span>Private' ;
																}
																?>
															<span class="caret"></span></button>
															<ul class="dropdown-menu" id="recent">
																<li class="sel-Private"><a href="javascript:void(0)" onClick="setSelectIcon(this)"><span class="glyphicon glyphicon-lock"></span>Private</a></li>
																<li class="sel-Friend"><a href="javascript:void(0)" onClick="setSelectIcon(this)"><span class="glyphicon glyphicon-user"></span>Friends</a></li>
																<li class="sel-Public"><a href="javascript:void(0)" onClick="setSelectIcon(this)"><span class="glyphicon glyphicon-globe"></span>Public</a></li>														
																
															</ul>													  													
														</div>
													</div>
												</div>
												
												<div class="form-group pull-right">
													<div class="pull-right fb-btnholder nbm">
														<input type="hidden" name="recent_activities" id="recent_activities" value="Private" />
														<a class="btn btn-primary btn-sm" onClick="close_edit(this),setting()">Save</a>
														<a class="btn btn-primary btn-sm" onClick="close_edit(this)">Cancel</a>
													</div>										
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
											<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
												<label>Friend List</label>
											</div>
											<div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">										
												<div class="info">																   				   								
													<label>                                                                                          
														Who should be to see my friend list?
													</label>
												</div>
											</div>
											<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 editbtn fullbtn">										
												<span class="thisSecurity-f_list thisSecurity"><?php echo $friend_list; ?></span>
												<div class="pull-right  linkholder">
													<a href="javascript:void(0)" onClick="open_edit(this)"><i class="fa fa-pencil"></i></a>
												</div>
											</div>
										</div>	
									</div>	
									<div class="edit-mode">
										<div class="row">
											<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
												<label>Friend List</label>
											</div>
											<div class="col-lg-5 col-md-5 col-sm-9 col-xs-12">										
												<div class="info">																   				   								
													<label>                                                                                          
														Who should be to see my friend list?
													</label>
												</div>
											</div>
											<div class="col-lg-4 col-md-4 col-sm-9 col-xs-12 pull-right">
												<div class="form-group pull-left">
													<div class="dropholder">													
														<div class="dropdown">
															<button aria-expanded="false" data-toggle="dropdown" type="button" class="btn btn-default dropdown-toggle btn-sm custom-select custom-select-f_list">
															<?php 
														   
																if($friend_list == 'Private'){
																 
																	echo '<span class="glyphicon glyphicon-lock"></span>Private';
																	
																}
																else if($friend_list == 'Friends'){
																   
																	echo '<span class="glyphicon glyphicon-user"></span>Friends';
																}
																else if($friend_list == 'Public'){
																 
																	echo '<span class="glyphicon glyphicon-globe"></span>Public';
																}
																else{
																	echo '<span class="glyphicon glyphicon-lock"></span>Private' ;
																}
																?>
															<span class="caret"></span></button>
															<ul class="dropdown-menu" id="f_list">
																<li class="sel-Private"><a href="javascript:void(0)" onClick="setSelectIcon(this)"><span class="glyphicon glyphicon-lock"></span>Private</a></li>
																<li class="sel-Friend"><a href="javascript:void(0)" onClick="setSelectIcon(this)"><span class="glyphicon glyphicon-user"></span>Friends</a></li>
																<li class="sel-Public"><a href="javascript:void(0)" onClick="setSelectIcon(this)"><span class="glyphicon glyphicon-globe"></span>Public</a></li>														
																
															</ul>													  													
														</div>
													</div>
												</div>
												
												<div class="form-group pull-right">
													<div class="pull-right fb-btnholder nbm">
														<input type="hidden" name="friend_list" id="friend_list" value="Private" />
														<a class="btn btn-primary btn-sm" onClick="close_edit(this),setting()">Save</a>
														<a class="btn btn-primary btn-sm" onClick="close_edit(this)">Cancel</a>
													</div>										
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

<script>
   $("#quest li").on("click", function(){
  var a = $(this).text();
	if(a == 'What is your email on file?'){
		
		$('#answer').val('<?php echo $eml_ans;?>');
	}
	else if(a == 'What city you born?'){
		$('#answer').val('<?php echo $born_ans;?>');
	}
	else if(a == 'What is your girl friend name?'){
		$('#answer').val('<?php echo $gf_ans;?>');
	}
	else{
		$('#answer').val('<?php echo $gf_ans;?>');
	}
  $('#security_questions').val(a);
});
  $("#look li").on("click", function(){
  var b = $(this).text();
  $('#my_view_status').val(b);
});
 $("#post li").on("click", function(){
  var c = $(this).text();
  $('#my_post_view_status').val(c);
});
 $("#photo li").on("click", function(){
  var x = $(this).text();
  $('#view_photos').val(x);
});
 $("#contact li").on("click", function(){
  var d = $(this).text();
  $('#contact_me').val(d);
});
$("#friend li").on("click", function(){
  var e = $(this).text();
  $('#friend_request').val(e);
});
$("#dashboard li").on("click", function(){
  var e = $(this).text();
  $('#dashboard_view_status').val(e);
});
$("#reviewtags li").on("click", function(){
  var e = $(this).text();
  $('#review_tags').val(e);
});
$("#bother li").on("click", function(){
  var f = $(this).text();
  $('#bothering_me').val(f);
});
$("#posting li").on("click", function(){
  var g = $(this).text();
  $('#add_public_wall').val(g);
});
$("#view li").on("click", function(){
  var h = $(this).text();
  $('#see_public_wall').val(h);
});
$("#review li").on("click", function(){
  var i = $(this).text();
  $('#review_posts').val(i);
});
$("#taged li").on("click", function(){
  var j = $(this).text();
  $('#view_posts_tagged_in').val(j);
});
$("#other_posts li").on("click", function(){
  var k = $(this).text();
  $('#view_others_posts_on_mywall').val(k);
});
$("#recent li").on("click", function(){
  var l = $(this).text();
  $('#recent_activities').val(l);
});
$("#f_list li").on("click", function(){
  var m = $(this).text();
  $('#friend_list').val(m);
});
$("[name='switchbtn']").change(function() {

 if ($("[name='switchbtn']").is(':checked')){ 
 
	$('#pair_social_actions').val('Yes');
  } 
  else { 

	$('#pair_social_actions').val('No');
  }
});
$("[name='switchbtn2']").change(function() {
 if ($("[name='switchbtn2']").is(':checked')){ 
  
	$('#review_tags').val('Yes');
	
  } 
  else { 
	$('#review_tags').val('No');
  }
});
$("[name='switchbtn3']").change(function() {

 if ($("[name='switchbtn3']").is(':checked')){  
	$('#dashboard_view_status').val('Yes');
	
  } 
  else { 
	$('#dashboard_view_status').val('No');
  }
});
</script>

</script>
<script>

function setting(){
            $.ajax({
                   type: 'POST',
                   url: '<?php echo Yii::$app->urlManager->createUrl(['site/security-setting']); ?>',
                   data: $("#frm-setting").serialize(),
                   success: function(data){
                       //alert(data); return false;
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

		var obj=$(test).parents(".setting-group");
		var emode=obj.children(".edit-mode");
		var nmode=obj.children(".normal-mode");
				
		var editmode=emode.css("display");

		if(editmode=="none"){
			nmode.slideUp(300);
			emode.slideDown(300);
		}
		else{
			nmode.slideDown(300);
			emode.slideUp(300);
		}
   }
   

</script>
<!-- Switch -->
<script src="<?= $baseUrl?>/js/bootstrap-switch.js" ></script>
<style>
    body{
        background-color:#f2f2f2;
    }
</style>
<script>

$(".js-example-theme-multiple").select2({
  tags: false
});

</script>
<?php include('includes/footer.php');?>