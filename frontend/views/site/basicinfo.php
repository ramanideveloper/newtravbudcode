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
use frontend\models\Education;
use frontend\models\Language;
use frontend\models\Interests;
use frontend\models\Occupation;
use frontend\models\UserSetting;
use frontend\models\Personalinfo;
use yii\helpers\ArrayHelper;
use  yii\web\Session;
$session = Yii::$app->session;


if($session->get('email'))
{
    $email = $session->get('email'); 
    /* $result Query For The Fetching Data From The 'tbl_user'*/
    
    $result = LoginForm::find()->where(['email' => $email])->one();
    $user = LoginForm::find()->where(['email' => $email])->one();
	
    $user_id = (string) $result['_id'];
    $fname = $result['fname'];
    $lname = $result['lname'];
    $password = $result['password'];
    $con_password = $result['con_password'];
    $birth_date = $result['birth_date'];
    $gender = $result['gender'];
    $city = $result['city'];
    $country = $result['country'];
    $phone = $result['phone'];
	$isd_code = $result['isd_code'];
    $alternate_email = $result['alternate_email'];
    if(isset($result['pwd_changed_date']) && !empty($result['pwd_changed_date']))
    {
        $result['pwd_changed_date'] = $result['pwd_changed_date'];
    }
    else
    {
        $result['pwd_changed_date'] = $result['created_date'];
    }
    $pwd_changed_date = Yii::$app->EphocTime->time_pwd_changed(time(),$result['pwd_changed_date']);
        
	/* $result_setting Query For The Fetching Data From The 'user_setting'*/
	$result_setting = UserSetting::find()->where(['user_id' => $user_id])->one();
	$email_access = $result_setting['email_access'];
	$alternate_email_access = $result_setting['alternate_email_access'];
	$mobile_access = $result_setting['mobile_access'];
	$birth_date_access = $result_setting['birth_date_access'];
	$gender_access = $result_setting['gender_access'];
	$language_access = $result_setting['language_access'];
	$religion_access = $result_setting['religion_access'];
	$political_view_access = $result_setting['political_view_access'];
   
        
    /* $result_personal Query For The Fetching Data From The 'personal_info'*/
        
     $result_personal = Personalinfo::find()->where(['user_id' => $user_id])->one();
   
     
     $about = $result_personal['about'];
     $education = $result_personal['education'];
     $edu_str = '';
     if(isset($education) && $education != '') {
		$edu_str .= '"';
		$edu_str .= str_replace(",", '","', $education);
		$edu_str .= '"';	
     }

     $interests = $result_personal['interests'];
	 $inter_str = '';
     if(isset($interests) && $interests != '') {
		$inter_str .= '"';
		$inter_str .= str_replace(",", '","', $interests);
		$inter_str .= '"';	
     }     
     $occupation = $result_personal['occupation'];


     $occu_str = '';
     if(isset($occupation) && $occupation != '') {
		$occu_str .= '"';
		$occu_str .= str_replace(",", '","', $occupation);
		$occu_str .= '"';	
     }


     $current_city = $result_personal['current_city'];
     $hometown = $result_personal['hometown'];    
     $language = $result_personal['language'];
     
     $lang_str = '';
     if(isset($language) && $language != '') {
		$lang_str .= '"';
		$lang_str .= str_replace(",", '","', $language);
		$lang_str .= '"';	
     }

     //$language = implode(",", $result_personal['language']);
     
     $religion = $result_personal['religion'];
     $political_view = $result_personal['political_view'];
    
    
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




<style>
.js-example-theme-multiple{
	
	width: 100%;
}
</style>
<!-- body section -->
<section class="inner-body-content fb-page">
	<div class="fb-innerpage whitebg">
		<?php /*
		<div class="gray-belt"></div>
		
		<div class="setting-content">	
			<div class="settingpic-holder">
				<div class="setting-pic">
				<?php
					if($result['fb_id'] != '')
                    {
						if(substr($result['thumbnail'],0,4) == 'http')
						{
							$dp = $result['photo'];
						}
						else
						{
								$dp = "profile/".$result['thumbnail'];
						}
					}
					 else if($result['photo'] != ''){
                            if(isset($result['thumbnail']) && !empty($result['thumbnail']) && file_exists('profile/'.$result['thumbnail']))
                            {
                                    $dp = "profile/".$result['thumbnail'];
                            }
                            else
                            {
                                    $dp = "profile/".$result['photo'];
                            }
                    }
                    else
                    {
                       $dp = "profile/".$result['gender'].'.jpg';
                    }
				?>
					<img src="<?= $dp?>"/>
				</div>
			</div>
			
			<div class="settingsinfo-holder">
				<div class="settings-info">
					<div class="pull-left"><p><?= $result['fname']. " " .$result['lname']?></p></div>
					<div class="pull-right"><p>Member since 24 April, 2016</p></div>
				</div>					
			</div>				
				
		</div>
		*/ ?>
		<div class="setting-content">	
			<?php include('includes/leftmenus.php');?>
			<div class="fbdetail-holder">
				
				<div class="fb-formholder">		
					<div class="fb-formholder-box">

						<div class="formtitle"><h4>Basic Information</h4></div>
						<div class="notice">	
							<div id="suceess" class="form-successmsg">Successfully Changed!</div>
							<div id="fail" class="form-failuremsg">Oops..!! this email id is already in use</div>
							<div id="pwd_err" class="form-failuremsg">Oops..!! Password Mismatch!</div>
							<div id="pwd_err2" class="form-failuremsg">Oops..!! You Have Entered Wrong Old Password!</div>
							<div id="info" class="form-infomsg">Setting Changed Successfully</div>
						</div>
						<ul class="setting-ul basicinfo">
							<li>
												 <?php $form = ActiveForm::begin(['id' => 'frm-name','options'=>['onsubmit'=>'return false;',],]); ?>              
								<div class="setting-group">							
									<div class="normal-mode">
										<div class="row">
											<div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">
												<label>Name</label>
											</div>
											<div class="col-lg-9 col-md-7 col-sm-7 col-xs-12">										
												<div class="info">																   				   								
													<label id="name">                                                                                          
													<?= $fname ?>																							
													<?= $lname ?>															
													</label>
												</div>
											</div>
											<div class="col-lg-1 col-md-2 col-sm-2 col-xs-12 editbtn">
												<div class="pull-right  linkholder">
													<a href="javascript:void(0)" onClick="open_edit(this)"><i class="fa fa-pencil"></i></a>
												</div>
											</div>
										</div>	
									</div>	
									<div class="edit-mode">
										<div class="row">
											<div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">
												<label>Name</label>
											</div>
											<div class="col-lg-7 col-md-9 col-sm-7 col-xs-12">
												<div class="row">
													<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
														<div class="form-group">						
														   <?= $form->field($model1,'fname')->textInput(array('class'=>'tab-mb form-control','value'=>$fname,'id'=>'fname'))->label(false)?>
														</div>
													</div>
													<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
														<div class="form-group mb">																   				   								
														   <?= $form->field($model1,'lname')->textInput(array('value'=>$lname,'id'=>'lname'))->label(false)?>					
														</div>
													</div>
												</div>										
											</div>									
											<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 pull-right">
												<div class="form-group pull-right">						
													<div class="pull-right fb-btnholder nbm">		
														<a class="btn btn-primary btn-sm" onClick="close_edit(this),basicinfo()">Save</a>
														<a class="btn btn-primary btn-sm" onClick="close_edit(this)">Cancel</a>												
													</div>										
												</div>										
											</div>
										</div>	
									</div>	
								</div>	
													<?php ActiveForm::end() ?>
													   
							</li>
							<li>
													 <?php $form = ActiveForm::begin(['id' => 'frm-email','options'=>['onsubmit'=>'return false;',],]); ?> 
								<div class="setting-group">							
									<div class="normal-mode">
										<div class="row">
											<div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">
												<label>Email</label>
											</div>
											<div class="col-lg-6 col-md-7 col-sm-5 col-xs-12">										
												<div class="info">																   				   								
													<label id="email">
														<?= $email ?>	
													</label>
												</div>
											</div>
											<div class="col-lg-4 col-md-2 col-sm-4 col-xs-12 editbtn">										
												<!--<span class="thisSecurity-eml_access thisSecurity">$email_access;</span>-->
												<div class="pull-right linkholder">											
													<a href="javascript:void(0)" onClick="open_edit(this)"><i class="fa fa-pencil"></i></a>
												</div>
											</div>
										</div>	
									</div>	
									<div class="edit-mode">
										<div class="row">
											<div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">
												<label>Email</label>
											</div>
											<div class="col-lg-4 col-md-5 col-sm-9 col-xs-12">
												<div class="form-group">						
												   <?= $form->field($model1,'email')->textInput(array('class'=>'form-control tab-mb','value'=>$email,'id'=>'bemail'))->label(false)?>	
												</div>																				
											</div>
											<div class="col-lg-4 col-md-4 col-sm-5 col-xs-12 pull-right">
												<!--
												<div class="form-group pull-left">						
													<div class="pull-left fb-btnholder nbm">						
														<div class="dropholder">													
															<div class="dropdown tb-user-post">
																<button aria-expanded="false" data-toggle="dropdown" type="button" class="btn btn-default dropdown-toggle btn-sm custom-select custom-select-eml_access">
																<?php 
														   
																if($email_access == 'Private'){
																 
																	echo '<span class="glyphicon glyphicon-lock"></span>Private';
																	
																}
																else if($email_access == 'Friends'){
																   
																	echo '<span class="glyphicon glyphicon-user"></span>Friends';
																}
																else if($email_access == 'Public'){
																 
																	echo ' <span class="glyphicon glyphicon-globe"></span>Public';
																}
																else{
																   
																	echo '<span class="glyphicon glyphicon-user"></span>Friends';
																   
																}
																
																?>
																<span class="caret"></span></button>
																<ul class="dropdown-menu" id="eml_access">
																	<li  id="Private" class="sel-Private"><a href="javascript:void(0)" onClick="setSelectIcon(this)"><span class="glyphicon glyphicon-lock"></span>Private</a></li>
																	<li id="Friends" class="sel-Friends"><a href="javascript:void(0)" onClick="setSelectIcon(this)"><span class="glyphicon glyphicon-user"></span>Friends</a></li>
																	<li id="Public" class="sel-Public"><a href="javascript:void(0)" onClick="setSelectIcon(this)"><span class="glyphicon glyphicon-globe"></span>Public</a></li>
																</ul>       																	
															</div>
														</div>

													</div>
												</div>
												-->
												<div class="form-group pull-right">						
													<div class="pull-right fb-btnholder nbm">						
														<div class="dropholder">													
															<div class="dropdown tb-user-post">														      
																<input type="hidden" name="email_access" id="email_access" value="Private" />
																
																<a class="btn btn-primary btn-sm" onClick="close_edit(this),email_address()">Save</a>
																<a class="btn btn-primary btn-sm" onClick="close_edit(this)">Cancel</a>
															</div>
														</div>

													</div>											
												</div>										
											</div>
										</div>	
									</div>	
								</div>
													
													 <?php ActiveForm::end() ?>
							</li>
							<li>
											<?php $form = ActiveForm::begin(['id' => 'frm-alt-email','options'=>['onsubmit'=>'return false;',],]); ?>         
								<div class="setting-group">							
									<div class="normal-mode">
										<div class="row">
											<div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">
												<label>Alternate Email</label>
											</div>
											<div class="col-lg-6 col-md-7 col-sm-7 col-xs-12">										
												<div class="info">																   				   								
													<label id="alt-email">	
														<?php
														if($alternate_email == ""){
															echo 'No Alternate Email Set';
														}
														
														else{
															echo $alternate_email;
														}
														?>
														
													</label>
												</div>
											</div>
											<div class="col-lg-4 col-md-2 col-sm-2 col-xs-12 editbtn">										
												<!--<span class="thisSecurity-alt_eml_access thisSecurity"><?php echo $alternate_email_access; ?></span>-->
												<div class="pull-right linkholder">
													<a href="javascript:void(0)" onClick="open_edit(this)"><i class="fa fa-pencil"></i></a>
												</div>
											</div>
										</div>	
									</div>	
									<div class="edit-mode">
										<div class="row">
											<div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">
												<label>Alternate Email</label>
											</div>
											<div class="col-lg-4 col-md-5 col-sm-9 col-xs-12">
												<div class="form-group">						
												   <?= $form->field($model1,'alternate_email')->textInput(array('class'=>'form-control tab-mb','value'=>$alternate_email,'id'=>'alternate_email'))->label(false)?>
												</div>																				
											</div>
											<div class="col-lg-4 col-md-4 col-sm-5 col-xs-12 pull-right">
												<!--
												<div class="form-group  pull-left">						
													<div class="pull-left fb-btnholder nbm">						
														<div class="dropholder">
															<div class="dropdown tb-user-post">
																<button aria-expanded="false" data-toggle="dropdown" type="button" class="btn btn-default dropdown-toggle btn-sm custom-select custom-select-alt_eml_access">
																
																<?php 
														   
																if($alternate_email_access == 'Private'){
																 
																	echo '<span class="glyphicon glyphicon-lock"></span>Private';
																	
																}
																else if($alternate_email_access == 'Friends'){
																   
																	echo '<span class="glyphicon glyphicon-user"></span>Friends';
																}
																else if($alternate_email_access == 'Public'){
																 
																	echo ' <span class="glyphicon glyphicon-globe"></span>Public';
																}
																else{
																   
																	echo '<span class="glyphicon glyphicon-user"></span>Friends';
																   
																}
																
																?>
																<span class="caret"></span></button>
																<ul class="dropdown-menu" id="alt_eml_access">
																	<li  id="Private" class="sel-Private"><a href="javascript:void(0)" onClick="setSelectIcon(this)"><span class="glyphicon glyphicon-lock"></span>Private</a></li>
																	<li id="Friends" class="sel-Friends"><a href="javascript:void(0)" onClick="setSelectIcon(this)"><span class="glyphicon glyphicon-user"></span>Friends</a></li>
																	<li id="Public" class="sel-Public"><a href="javascript:void(0)" onClick="setSelectIcon(this)"><span class="glyphicon glyphicon-globe"></span>Public</a></li>														
																	
																</ul>															
															</div>
														</div>												
													</div>										
												</div>	
												-->
												<div class="form-group  pull-right">						
													<div class="pull-right fb-btnholder nbm">						
														<div class="dropholder">
															<div class="dropdown tb-user-post">															
																<input type="hidden" name="alternate_email_access" id="alternate_email_access" value="Private" />
																<a class="btn btn-primary btn-sm" onClick="close_edit(this),email2()">Save</a>
																<a class="btn btn-primary btn-sm" onClick="close_edit(this)">Cancel</a>						

															</div>
														</div>												
													</div>										
												</div>										
											
											</div>
										</div>	
									</div>	
								</div>	
												<?php ActiveForm::end() ?>
							</li>
							
							<?php
									if(isset($result['password']) && !empty($result['password'])){
							?>
							
							<li>
						   <?php $form = ActiveForm::begin(['id' => 'frm-pwd','options'=>['onsubmit'=>'return false;',],]); ?>    
								<div class="setting-group">							
									<div class="normal-mode">
										<div class="row">
											<div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">
												<label>Password</label>
											</div>
											<div class="col-lg-9 col-md-7 col-sm-7 col-xs-12">										
												<div class="info">																   				   								
													<label id="pwd-change">												
														Password updated <?= $pwd_changed_date?>
													</label>
												</div>
											</div>
											<div class="col-lg-1 col-md-2 col-sm-2 col-xs-12 editbtn">										
												<div class="pull-right linkholder">
													<a href="javascript:void(0)" onClick="open_edit(this)"><i class="fa fa-pencil"></i></a>
												</div>
											</div>
										</div>	
									</div>	
									<div class="edit-mode">
										<div class="row">
											<div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">
												<label>Old Password</label>
											</div>									
											<div class="col-lg-4 col-md-5 col-sm-9 col-xs-12">
												<div class="form-group bm eyeicon">						
													<input type="password" class="form-control" name="old_password" id="old_password"/>
													<input type="hidden" class="form-control" name="old_real_pwd" id="old_real_pwd" value="<?= $password?>"/>
													
												   <a href="javascript:void(0)" class="showPass"><i class="fa fa-eye"></i></a>
												</div>																				
											</div>
											<div class="clear"></div>
											<div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">
												<label>Password</label>
											</div>									
											<div class="col-lg-4 col-md-5 col-sm-9 col-xs-12">
												<div class="form-group bm eyeicon">						
												   <?= $form->field($model1,'password')->passwordInput(array('class'=>'form-control','id'=>'password'))->label(false)?>

												   <a href="javascript:void(0)" class="showPass"><i class="fa fa-eye"></i></a>
													
												</div>																				
											</div>
											<div class="clear"></div>
											<div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">
												<label>Retype Password</label>
											</div>									
											<div class="col-lg-4 col-md-5 col-sm-9 col-xs-12">
												<div class="form-group eyeicon">						
												   <?= $form->field($model1,'con_password')->passwordInput(array('class'=>'form-control','id'=>'con_password'))->label(false)?>					
													
													<a href="javascript:void(0)" class="showPass"><i class="fa fa-eye"></i></a>
												  
												</div>																				
											</div>
											<div class="clear"></div>
											<div class="col-lg-2 col-lg-3 col-sm-3 col-xs-12"></div>
											<div class="col-lg-4 col-md-5 col-sm-9 col-xs-12">
												<div class="h-checkbox">
																								<!--<label for="test1" ><input type="checkbox" id="test1" checked/>
																								Sign out of all sessions</label><br/>-->
																								
												</div>
											</div>
											<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 pull-right">
												<div class="form-group  pull-right">						
													<div class="pull-right fb-btnholder nbm">																
														<a class="btn btn-primary btn-sm" onClick="close_edit(this),pwd()">Save</a>
														<a class="btn btn-primary btn-sm" onClick="close_edit(this)">Cancel</a>
													</div>										
												</div>										
											</div>
										</div>	
									</div>	
								</div>	
													<?php ActiveForm::end() ?>
							</li>
									<?php } ?>
							
							<li>
													 <?php $form = ActiveForm::begin(['id' => 'frm-city','options'=>['onsubmit'=>'return false;',],]); ?>   
								<div class="setting-group">							
									<div class="normal-mode">
										<div class="row">
											<div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">
												<label>City</label>
											</div>
											<div class="col-lg-9 col-md-7 col-sm-7 col-xs-12">										
												<div class="info">																   				   								
													<label id="city">
																									<?php
																									if($city == ""){
																										echo 'No City Added';
																									}
																									
																									else{
																										echo $city;
																									}
																									?>
														
													</label>
												</div>
											</div>
											<div class="col-lg-1 col-md-2 col-sm-2 col-xs-12  editbtn">										
												<div class="pull-right linkholder">
													<a href="javascript:void(0)" onClick="open_edit(this)"><i class="fa fa-pencil"></i></a>
												</div>
											</div>
										</div>	
									</div>	
									<div class="edit-mode">
										<div class="row">
											<div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">
												<label>City</label>
											</div>
											<div class="col-lg-4 col-md-5 col-sm-7 col-xs-12">
												<div class="form-group mb">						
												   <?= $form->field($model1,'city')->textInput(array('class'=>'form-control getplacelocation','value'=>$city,'id'=>'autocomplete1','onFocus'=>'geolocate()'))->label(false)?>									
												</div>																				
											</div>									
											<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 pull-right">
												<div class="form-group pull-right">						
													<div class="pull-right fb-btnholder nbm">																		
														<a class="btn btn-primary btn-sm" onClick="close_edit(this),city()">Save</a>
														<a class="btn btn-primary btn-sm" onClick="close_edit(this)">Cancel</a>
													</div>										
												</div>										
											</div>
										</div>	
									</div>	
								</div>	
													<?php ActiveForm::end() ?>
							</li>
							<li>
													 <?php $form = ActiveForm::begin(['id' => 'frm-country','options'=>['onsubmit'=>'return false;',],]); ?>   
								<div class="setting-group">							
									<div class="normal-mode">
										<div class="row">
											<div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">
												<label>Country</label>
											</div>
											<div class="col-lg-9 col-md-7 col-sm-7 col-xs-12">										
												<div class="info">																   				   								
													<label id="country1">
																									  <?php
																									if($country == ""){
																										echo 'No Country Added';
																									}
																									
																									else{
																										echo $country;
																									}
																									?>
														
													</label>
												</div>
											</div>
											<div class="col-lg-1 col-md-2 col-sm-2 col-xs-12 editbtn">										
												<div class="pull-right linkholder">
													<a href="javascript:void(0)" onClick="open_edit(this)"><i class="fa fa-pencil"></i></a>
												</div>
											</div>
										</div>	
									</div>	
									<div class="edit-mode">
										<div class="row">
											<div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">
												<label>Country</label>
											</div>
											<div class="col-lg-4 col-md-5 col-sm-7 col-xs-12">
												<div class="form-group mb">						
												   <?= $form->field($model1,'country')->textInput(array('class'=>'form-control','id'=>'country','value'=>$country,'readonly' => true))->label(false)?>
												</div>																				
											</div>									
											<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 pull-right">
												<div class="form-group pull-right">						
													<div class="pull-right fb-btnholder nbm">																					
														<a class="btn btn-primary btn-sm" onClick="close_edit(this),country()">Save</a>
														<a class="btn btn-primary btn-sm" onClick="close_edit(this)">Cancel</a>																							
													</div>										
												</div>										
											</div>
										</div>	
									</div>	
								</div>	
								<?php ActiveForm::end() ?>
							</li>
							
												
							<li>
												 <?php $form = ActiveForm::begin(['id' => 'frm-phone','options'=>['onsubmit'=>'return false;',],]); ?>              
								<div class="setting-group">							
									<div class="normal-mode">
										<div class="row">
											<div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">
												<label>Mobile</label>
											</div>
											<div class="col-lg-6 col-md-7 col-sm-5 col-xs-12">										
												<div class="info">																   				   								
													<label id="phone1">                                                                                          
													<?= $phone ?>																							
																											
													</label>
												</div>
											</div>
											<div class="col-lg-4 col-md-2 col-sm-4 col-xs-12 editbtn">										
												<!--<span class="thisSecurity-mbl_access thisSecurity"><?php echo $mobile_access; ?></span>-->
												<div class="pull-right  linkholder">
													<a href="javascript:void(0)" onClick="open_edit(this)"><i class="fa fa-pencil"></i></a>
												</div>
											</div>
										</div>	
									</div>	
									<div class="edit-mode">
										<div class="row">
											<div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">
												<label>Mobile</label>
											</div>
											<div class="col-lg-5 col-md-6 col-sm-9 col-xs-12">	
												<div class="two-comp">
													<div class="row">
														<div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
															<div class="form-group">
																													<input type="text" name="isd_code" value="<?= $isd_code?>" placeholder="isd code" id="isd_code" class="form-control isd_text" readonly="true"/>
															</div>
														</div>
														<div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
															<div class="form-group valid-div">
																<?= $form->field($model1,'phone')->textInput(array('class'=>'tab-mb form-control','value'=>$phone,'id'=>'phone','onkeyup'=>'checkAvailability()'))->label(false)?>
																													<div id='phn-success' class="frm-validicon" style='display: none'><img src="<?= $baseUrl?>/images/frm-check.png"/></div>
																													<div id='phn-fail' class="frm-validicon" style='display: none'><img src="<?= $baseUrl?>/images/frm-cross.png"/></div>
															</div>
														</div>
													</div>
												</div>
											</div>									
											<div class="col-lg-4 col-md-4 col-sm-5 col-xs-12 pull-right">
												<!--
												<div class="form-group pull-left">						
													<div class="pull-left fb-btnholder nbm">	
														<div class="dropholder">
															<div class="dropdown tb-user-post">
																<button aria-expanded="false" data-toggle="dropdown" type="button" class="btn btn-default dropdown-toggle btn-sm custom-select custom-select-mbl_access">
																
																<?php 
														   
																if($mobile_access == 'Private'){
																 
																	echo '<span class="glyphicon glyphicon-lock"></span>Private';
																	
																}
																else if($mobile_access == 'Friends'){
																   
																	echo '<span class="glyphicon glyphicon-user"></span>Friends';
																}
																else if($mobile_access == 'Public'){
																 
																	echo ' <span class="glyphicon glyphicon-globe"></span>Public';
																}
																else{
																   
																	echo '<span class="glyphicon glyphicon-user"></span>Friends';
																   
																}
																
																?>
																<span class="caret"></span></button>
																<ul class="dropdown-menu" id="mbl_access">
																	<li  id="Private" class="sel-Private"><a href="javascript:void(0)" onClick="setSelectIcon(this)"><span class="glyphicon glyphicon-lock"></span>Private</a></li>
																	<li id="Friends" class="sel-Friends"><a href="javascript:void(0)" onClick="setSelectIcon(this)"><span class="glyphicon glyphicon-user"></span>Friends</a></li>
																	<li id="Public" class="sel-Public"><a href="javascript:void(0)" onClick="setSelectIcon(this)"><span class="glyphicon glyphicon-globe"></span>Public</a></li>
																</ul>
				
															</div>
														</div>
													</div>
												</div>	
												-->												
												<div class="form-group pull-right">						
													<div class="pull-right fb-btnholder nbm">	
														
														<input type="hidden" name="mobile_access" id="mobile_access" value="Private" />
														<a class="btn btn-primary btn-sm" onClick="close_edit(this),phone()" disabled="disabled" id="checckavail">Save</a>
														<a class="btn btn-primary btn-sm" onClick="close_edit(this)">Cancel</a>												
													
													</div>
												</div>										
											
											</div>
										</div>	
									</div>	
								</div>	
													<?php ActiveForm::end() ?>
													   
							</li>
												
								<li>
								<?php $form = ActiveForm::begin(['id' => 'frm-current-city','options'=>['onsubmit'=>'return false;',],]); ?> 
								<div class="setting-group">							
									<div class="normal-mode">
										<div class="row">
											<div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">
												<label>Current City</label>
											</div>
											<div class="col-lg-9 col-md-7 col-sm-7 col-xs-12">										
												<div class="info">																   				   								
													<label id="current_city">
																										<?php
																									if($current_city == ""){
																										echo 'No Current City Added';
																									}
																									
																									else{
																										echo $current_city;
																									}
																									?>
														
													</label>
												</div>
											</div>
											<div class="col-lg-1 col-md-2 col-sm-2 col-xs-12 editbtn">										
												<div class="pull-right linkholder">
													<a href="javascript:void(0)" onClick="open_edit(this)"><i class="fa fa-pencil"></i></a>
												</div>
											</div>
										</div>	
									</div>	
									<div class="edit-mode">
										<div class="row">
											<div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">
												<label>Current City</label>
											</div>
											<div class="col-lg-4 col-md-5 col-sm-6 col-xs-12">
												<div class="form-group">						

												   <?= $form->field($model2,'current_city')->textInput(array('class'=>'form-control getplacelocation', 'id' => 'city1','value'=>$current_city))->label(false)?>
												</div>																				
											</div>									
											<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 pull-right">
												<div class="form-group pull-right">						
													<div class="pull-right fb-btnholder nbm">																		
														<a class="btn btn-primary btn-sm" onClick="close_edit(this),current_city()">Save</a>
														<a class="btn btn-primary btn-sm" onClick="close_edit(this)">Cancel</a>			
													</div>										
												</div>										
											</div>
										</div>	
									</div>	
								</div>	
													 <?php ActiveForm::end() ?>
							</li>
						
							
							<!--					<li>
													<?php $form = ActiveForm::begin(['id' => 'frm-hometown','options'=>['onsubmit'=>'return false;',],]); ?> 
								<div class="setting-group">							
									<div class="normal-mode">
										<div class="row">
											<div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">
												<label>Home Town</label>
											</div>
											<div class="col-lg-9 col-md-7 col-sm-7 col-xs-12">										
												<div class="info">																   				   								
													<label id="hometown">
																							<?php
																									if($hometown == ""){
																										echo 'No Home Town Added';
																									}
																									
																									else{
																										echo $hometown;
																									}
																							?>
														
													</label>
												</div>
											</div>
											<div class="col-lg-1 col-md-2 col-sm-2 col-xs-12">										
												<div class="pull-right linkholder">
													<a href="javascript:void(0)" onClick="open_edit(this)"><i class="fa fa-pencil"></i></a>
												</div>
											</div>
										</div>	
									</div>	
									<div class="edit-mode">

										<div class="row">
											<div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">
												<label>Home Town</label>
											</div>
											<div class="col-lg-4 col-md-5 col-sm-9 col-xs-12">
												<div class="form-group">						
												   <?= $form->field($model2,'hometown')->textInput(array('class'=>'form-control','value'=>$hometown))->label(false)?>					
												</div>																				
											</div>									
											<div class="col-lg-4 col-md-3 col-sm-12 col-xs-12 pull-right">
												<div class="form-group pull-right">						
													<div class="pull-right fb-btnholder nbm">																		
														<a class="btn-icon" onClick="close_edit(this),hometown()"><i class="fa fa-check"></i></a>
														<a class="btn-icon btn-gray" onClick="close_edit(this)"><i class="fa fa-close"></i></a>					
													</div>										
												</div>										
											</div>
										</div>	
									</div>	
								</div>	
													 <?php ActiveForm::end() ?>
							</li>-->
							<li>
													<?php $form = ActiveForm::begin(['id' => 'frm-birth-date','options'=>['onsubmit'=>'return false;',],]); ?> 
								<div class="setting-group">							
									<div class="normal-mode">
										<div class="row">
											<div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">
												<label>Birth Date</label>
											</div>
											<div class="col-lg-6 col-md-7 col-sm-5 col-xs-12">										
												<div class="info">																   				   								
													<label id="birth_date">	
														<?php
																if($birth_date == ""){
																	echo 'No Birthdate Set';
																}
																
																else{
																	echo $birth_date;
																}
														?>
														
													</label>
												</div>
											</div>
											<div class="col-lg-4 col-md-2 col-sm-4 col-xs-12 editbtn fullbtn">										
												<span class="thisSecurity-birth_access thisSecurity"><?php echo $birth_date_access; ?></span>
												<div class="pull-right linkholder">
													<a href="javascript:void(0)" onClick="open_edit(this)"><i class="fa fa-pencil"></i></a>
												</div>
											</div>
										</div>	
									</div>	
									<div class="edit-mode">
										<div class="row">
											<div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">
												<label>Birth Date</label>
											</div>
											<div class="col-lg-4 col-md-5 col-sm-9 col-xs-12">
												<div class="date-holder">
													<?= $form->field($model1,'birth_date')->textInput(array('placeholder'=>'Birthdate','id'=>'popupDatepicker','onkeydown'=>'return false;','value'=>$birth_date))->label(false)?>
													<div class="date-container">
													</div>
												</div>
												<?php /*<div class='input-group date bdate' id='datetimepicker2'>									
														
		<?= $form->field($model1,'birth_date')->textInput(array('placeholder'=>'Birthdate','id'=>'popupDatepicker','onkeydown'=>'return false;','value'=>$birth_date))->label(false)?>											
													<span class="input-group-addon"><i class="glyphicon glyphicon-calendar " style="color:#0071BD;"></i></span>
												</div> */ ?>
											</div>									
											<div class="col-lg-4 col-md-4 col-sm-8 col-xs-12 pull-right">
												<div class="form-group bd-holder pull-left">						
													<div class="pull-left fb-btnholder nbm">																		
														<div class="dropholder">
															<div class="dropdown tb-user-post">
																<button aria-expanded="false" data-toggle="dropdown" type="button" class="btn btn-default dropdown-toggle btn-sm custom-select custom-select-birth_access">
																
																<?php 
														   
																if($birth_date_access == 'Private'){
																 
																	echo '<span class="glyphicon glyphicon-lock"></span>Private';
																	
																}
																else if($birth_date_access == 'Friends'){
																   
																	echo '<span class="glyphicon glyphicon-user"></span>Friends';
																}
																else if($birth_date_access == 'Public'){
																 
																	echo ' <span class="glyphicon glyphicon-globe"></span>Public';
																}
																else{
																   
																	echo '<span class="glyphicon glyphicon-user"></span>Friends';
																   
																}
																?>
																<span class="caret"></span></button>
																<ul class="dropdown-menu" id="birth_access">
																	<li  id="Private" class="sel-Private"><a href="javascript:void(0)" onClick="setSelectIcon(this)"><span class="glyphicon glyphicon-lock"></span>Private</a></li>
																	<li id="Friends" class="sel-Friends"><a href="javascript:void(0)" onClick="setSelectIcon(this)"><span class="glyphicon glyphicon-user"></span>Friends</a></li>
																	<li id="Public" class="sel-Public"><a href="javascript:void(0)" onClick="setSelectIcon(this)"><span class="glyphicon glyphicon-globe"></span>Public</a></li>														
																	
																</ul>                                                        				
															</div>
														</div>
														
													</div>								
												</div>										
												<div class="form-group bd-holder pull-right">						
													<div class="pull-right fb-btnholder nbm">																		
														<div class="dropholder">
															<div class="dropdown tb-user-post">														
																 <input type="hidden" name="birth_date_access" id="birth_date_access" value="Private" />														 
																<a class="btn btn-primary btn-sm" onClick="close_edit(this),birth_date()">Save</a>
																<a class="btn btn-primary btn-sm" onClick="close_edit(this)">Cancel</a>				
															</div>
														</div>
														
													</div>										
												</div>										
											
											</div>
										</div>	
									</div>	
								</div>	
													 <?php ActiveForm::end() ?>
							</li>
							<li>
													 <?php $form = ActiveForm::begin(['id' => 'frm-gender','options'=>['onsubmit'=>'return false;',],]); ?> 
								<div class="setting-group">							
									<div class="normal-mode">
										<div class="row">
											<div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">
												<label>Gender</label>
											</div>
											<div class="col-lg-6 col-md-7 col-sm-5 col-xs-12">										
												<div class="info">																   				   								
													<label id="gender">												
														<?= $gender ?>  
													</label>											
												</div>
											</div>
											<div class="col-lg-4 col-md-2 col-sm-4 col-xs-12 editbtn fullbtn">										
												<span class="thisSecurity-gen_access thisSecurity"><?php echo $gender_access; ?></span>
												<div class="pull-right linkholder">
													<a href="javascript:void(0)" onClick="open_edit(this)"><i class="fa fa-pencil"></i></a>
												</div>
											</div>
										</div>	
									</div>	
									<div class="edit-mode">
										<div class="row">
											<div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">
												<label>Gender</label>
											</div>
											<div class="col-lg-4 col-md-5 col-sm-6 col-xs-12">
												<div class="form-group mb">						
												  
														<div class="radio pull-left gender-radio">
																		   
														<?php
															if($gender == "Male")
															{
														?>
														  
															<?php echo $form->field($model1, 'gender')->dropDownList(['Male' => 'Male', 'Female' => 'Female'])->label(false); ?>
										  
																
														 <?php 
															}
															else
															{
															?> 
															 <?php echo $form->field($model1, 'gender')->dropDownList(['Female' => 'Female', 'Male' => 'Male'])->label(false); ?>
														   
															<?php
															}
														 ?>
																					  
													  </div>
													
												</div>																				
											</div>									
											<div class="col-lg-4 col-md-4 col-sm-8 col-xs-12 pull-right">
												<div class="form-group pull-left">						
													<div class="pull-left fb-btnholder nbm">																		
														<div class="dropholder">
															<div class="dropdown tb-user-post">
																<button aria-expanded="false" data-toggle="dropdown" type="button" class="btn btn-default dropdown-toggle btn-sm custom-select  custom-select-gen_access">
																
																<?php 
														   
																if($gender_access == 'Private'){
																 
																	echo '<span class="glyphicon glyphicon-lock"></span>Private';
																	
																}
																else if($gender_access == 'Friends'){
																   
																	echo '<span class="glyphicon glyphicon-user"></span>Friends';
																}
																else if($gender_access == 'Public'){
																 
																	echo ' <span class="glyphicon glyphicon-globe"></span>Public';
																}
																else{
																   
																	echo '<span class="glyphicon glyphicon-user"></span>Friends';
																   
																}
																?>
																<span class="caret"></span></button>
																<ul class="dropdown-menu" id="gen_access">
																	<li  id="Private" class="sel-Private"><a href="javascript:void(0)" onClick="setSelectIcon(this)"><span class="glyphicon glyphicon-lock"></span>Private</a></li>
																	<li id="Friends" class="sel-Friends"><a href="javascript:void(0)" onClick="setSelectIcon(this)"><span class="glyphicon glyphicon-user"></span>Friends</a></li>
																	<li id="Public" class="sel-Public"><a href="javascript:void(0)" onClick="setSelectIcon(this)"><span class="glyphicon glyphicon-globe"></span>Public</a></li>														
																	
																</ul>															
															</div>
														</div>	
													</div>										
												</div>										
												<div class="form-group pull-right">						
													<div class="pull-right fb-btnholder nbm">																		
														<div class="dropholder">
															<div class="dropdown tb-user-post">															
																 <input type="hidden" name="gender_access" id="gender_access" value="Private" />														 
																<a class="btn btn-primary btn-sm" onClick="close_edit(this),gender()">Save</a>
																<a class="btn btn-primary btn-sm" onClick="close_edit(this)">Cancel</a>
															</div>
														</div>	
													</div>										
												</div>										
											
											</div>
										</div>	
									</div>	
								</div>	
													<?php ActiveForm::end() ?>
							</li>
							<li>
													<?php $form = ActiveForm::begin(['id' => 'frm-language','options'=>['onsubmit'=>'return false;',],]); ?> 
								<div class="setting-group">							
									<div class="normal-mode">
										<div class="row">
											<div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">
												<label>Language</label>
											</div>
											<div class="col-lg-6 col-md-7 col-sm-5 col-xs-12">										
												<div class="info">																   				   								
													<label id="language">	
																							<?php
																									if($language == ""){
																										echo 'No Language Set';
																									}
																									
																									else{
																										echo $language;
																									}
																							?>
														
													</label>
												</div>
											</div>
											<div class="col-lg-4 col-md-2 col-sm-4 col-xs-12 editbtn">																				
												<div class="pull-right linkholder">
													<a href="javascript:void(0)" onClick="open_edit(this)"><i class="fa fa-pencil"></i></a>
												</div>
											</div>
										</div>	
									</div>	
									<div class="edit-mode">
										<div class="row">
											<div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">
												<label>Language</label>
											</div>
											<div class="col-lg-4 col-md-5 col-sm-9 col-xs-12">
												<div class="form-group tab-mb">						
		<?= $form->field($model2,'language')->dropDownList(ArrayHelper::map(Language::find()->all(), 'name', 'name'),['class'=>'js-example-theme-multiple language-cls','style'=>'width: 100%','multiple'=>'multiple','id'=>'language1'])->label(false)?>                                                                                 

												</div>																				
											</div>									
											<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 pull-right">
																					
												<div class="form-group pull-right">						
													<div class="pull-right fb-btnholder nbm">		
														<div class="dropholder">
															<div class="dropdown tb-user-post">															
																 <input type="hidden" name="language_access" id="language_access" value="Private" />
																<a class="btn btn-primary btn-sm" onClick="close_edit(this),language()">Save</a>
																<a class="btn btn-primary btn-sm" onClick="close_edit(this)">Cancel</a>				
															</div>
														</div>	
														
													</div>										
												</div>										
											
											</div>
										</div>	
									</div>	
								</div>
													<?php ActiveForm::end() ?>
							</li>
							<!--<li>
													<?php $form = ActiveForm::begin(['id' => 'frm-religion','options'=>['onsubmit'=>'return false;',],]); ?> 
								<div class="setting-group">							
									<div class="normal-mode">
										<div class="row">
											<div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">
												<label>Religion View</label>
											</div>
											<div class="col-lg-9 col-md-7 col-sm-7 col-xs-12">										
												<div class="info">																   				   								
													<label id="religion">
													  <?php
													if($religion == ""){ echo 'No Religion Set';}else{ echo $religion;}?>
														
													</label>
												</div>
											</div>
											<div class="col-lg-1 col-md-2 col-sm-2 col-xs-12 editbtn">										
												<div class="pull-right linkholder">
													<a href="javascript:void(0)" onClick="open_edit(this)"><i class="fa fa-pencil"></i></a>
												</div>
											</div>
										</div>	
									</div>	
									<div class="edit-mode">
										<div class="row">
											<div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">
												<label>Religion View</label>
											</div>
											<div class="col-lg-6 col-md-9 col-sm-9 col-xs-12">
												<div class="form-group">						
													<?= $form->field($model2,'religion')->textInput(array('class'=>'form-control','value'=>$religion))->label(false)?>					
												</div>																				
											</div>									
											<div class="col-lg-4 col-md-12 col-sm-12 col-xs-12 pull-right">
												<div class="form-group pull-right">						
													<div class="pull-right fb-btnholder nbm">																		
														<div class="dropholder">
															<div class="dropdown tb-user-post">
																<button aria-expanded="false" data-toggle="dropdown" type="button" class="btn btn-default dropdown-toggle btn-sm custom-select custom-select-rel_access">
																<?php 
														   
																if($religion_access == 'Private'){
																 
																	echo '<span class="glyphicon glyphicon-lock"></span>Private';
																	
																}
																else if($religion_access == 'Friends'){
																   
																	echo '<span class="glyphicon glyphicon-user"></span>Friends';
																}
																else if($religion_access == 'Public'){
																 
																	echo ' <span class="glyphicon glyphicon-globe"></span>Public';
																}
																else{
																   
																	echo '<span class="glyphicon glyphicon-user"></span>Friends';
																   
																}
																?>
																<span class="caret"></span></button>
																<ul class="dropdown-menu" id="rel_access">
																	<li  id="Private" class="sel-Private"><a href="javascript:void(0)" onClick="setSelectIcon(this)"><span class="glyphicon glyphicon-lock"></span>Private</a></li>
																	<li id="Friends" class="sel-Friends"><a href="javascript:void(0)" onClick="setSelectIcon(this)"><span class="glyphicon glyphicon-user"></span>Friends</a></li>
																	<li id="Public" class="sel-Public"><a href="javascript:void(0)" onClick="setSelectIcon(this)"><span class="glyphicon glyphicon-globe"></span>Public</a></li>															
																	
																</ul>	
																<input type="hidden" name="religion_access" id="religion_access" value="Private" />
																<a class="btn-icon" onClick="close_edit(this),religion()"><i class="fa fa-check"></i></a>
																<a class="btn-icon btn-gray" onClick="close_edit(this)"><i class="fa fa-close"></i></a>					
															</div>
														</div>	
														
													</div>										
												</div>										
											</div>
										</div>	
									</div>	
								</div>	
													 <?php ActiveForm::end() ?>
							</li>
							<li>
													<?php $form = ActiveForm::begin(['id' => 'frm-political-view','options'=>['onsubmit'=>'return false;',],]); ?> 
								<div class="setting-group">							
									<div class="normal-mode">
										<div class="row">
											<div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">
												<label>Political View</label>
											</div>
											<div class="col-lg-9 col-md-7 col-sm-7 col-xs-12">										
												<div class="info">																   				   								
													<label id="political_view">
																							<?php
																									if($political_view == ""){
																										echo 'No Political View Set';
																									}
																									
																									else{
																										echo $political_view;
																									}
																							?>
														
													</label>
												</div>
											</div>
											<div class="col-lg-1 col-md-2 col-sm-2 col-xs-12 editbtn">										
												<div class="pull-right linkholder">
													<a href="javascript:void(0)" onClick="open_edit(this)"><i class="fa fa-pencil"></i></a>
												</div>
											</div>
										</div>	
									</div>	
									<div class="edit-mode">
										<div class="row">
											<div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">
												<label>Political View</label>
											</div>
											<div class="col-lg-6 col-md-9 col-sm-9 col-xs-12">
												<div class="form-group">						
													<?= $form->field($model2,'political_view')->textInput(array('class'=>'form-control','value'=>$political_view))->label(false)?>					
												</div>																				
											</div>									
											<div class="col-lg-4 col-md-12 col-sm-12 col-xs-12 pull-right">
												<div class="form-group pull-right">						
													<div class="pull-right fb-btnholder nbm">																		
														<div class="dropholder">
															<div class="dropdown tb-user-post">
																<button aria-expanded="false" data-toggle="dropdown" type="button" class="btn btn-default dropdown-toggle btn-sm custom-select custom-select-pol_access">
																<?php 
														   
																if($political_view_access == 'Private'){
																 
																	echo '<span class="glyphicon glyphicon-lock"></span>Private';
																	
																}
																else if($political_view_access == 'Friends'){
																   
																	echo '<span class="glyphicon glyphicon-user"></span>Friends';
																}
																else if($political_view_access == 'Public'){
																 
																	echo ' <span class="glyphicon glyphicon-globe"></span>Public';
																}
																else{
																   
																	echo '<span class="glyphicon glyphicon-user"></span>Friends';
																   
																}
																?>
																<span class="caret"></span></button>
																<ul class="dropdown-menu" id="pol_access">
																	<li  id="Private" class="sel-Private"><a href="javascript:void(0)" onClick="setSelectIcon(this)"><span class="glyphicon glyphicon-lock"></span>Private</a></li>
																	<li id="Friends" class="sel-Friends"><a href="javascript:void(0)" onClick="setSelectIcon(this)"><span class="glyphicon glyphicon-user"></span>Friends</a></li>
																	<li id="Public" class="sel-Public"><a href="javascript:void(0)" onClick="setSelectIcon(this)"><span class="glyphicon glyphicon-globe"></span>Public</a></li>															
																	
																</ul>	
																 <input type="hidden" name="political_view_access" id="political_view_access" value="Private" />
																 <a class="btn-icon" onClick="close_edit(this),political()"><i class="fa fa-check"></i></a>
																<a class="btn-icon btn-gray" onClick="close_edit(this)"><i class="fa fa-close"></i></a>					
															</div>
														</div>												
													</div>										
												</div>										
											</div>
										</div>	
									</div>	
								</div>	
													 <?php ActiveForm::end() ?>
							</li>-->
												
												 <li>
												 <?php $form = ActiveForm::begin(['id' => 'frm-about','options'=>['onsubmit'=>'return false;',],]); ?>              
								<div class="setting-group">							
									<div class="normal-mode">
										<div class="row">
											<div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">
												<label>About Yourself</label>
											</div>
											<div class="col-lg-9 col-md-7 col-sm-7 col-xs-12">										
												<div class="info">																   				   								
													<label id="about">                                                                                          
													<?= $about ?>																							
																											
													</label>
												</div>
											</div>
											<div class="col-lg-1 col-md-2 col-sm-2 col-xs-12 editbtn">										
												<div class="pull-right  linkholder">
													<a href="javascript:void(0)" onClick="open_edit(this)"><i class="fa fa-pencil"></i></a>
												</div>
											</div>
										</div>	
									</div>	
									<div class="edit-mode">
										<div class="row">
											<div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">
												<label>About Yourself</label>
											</div>
											<div class="col-lg-7 col-md-9 col-sm-9 col-xs-12">
												<div class="form-group mb">						
												   <?= $form->field($model2,'about')->textInput(array('value'=>$about,'placeholder'=>''.$fname.', tel us about you'))->label(false)?>
												</div>
											</div>									
											<div class="col-lg-3 col-md-12 col-sm-6 col-xs-12 pull-right">
												<div class="form-group pull-right">						
													<div class="pull-right fb-btnholder nbm">																							
														<a class="btn btn-primary btn-sm" onClick="close_edit(this),about()">Save</a>
														<a class="btn btn-primary btn-sm" onClick="close_edit(this)">Cancel</a>
													</div>										
												</div>										
											</div>
										</div>	
									</div>	
								</div>	
													<?php ActiveForm::end() ?>
													   
							</li>
												
												 <li>
												 <?php $form = ActiveForm::begin(['id' => 'frm-education','options'=>['onsubmit'=>'return false;',],]); ?>              
								<div class="setting-group">							
									<div class="normal-mode">
										<div class="row">
											<div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">
												<label>Education</label>
											</div>
											<div class="col-lg-9 col-md-7 col-sm-7 col-xs-12">										
												<div class="info">																   				   								
													<label id="education">                                                                                          
													<?= $education ?>																							
																											
													</label>
												</div>
											</div>
											<div class="col-lg-1 col-md-2 col-sm-2 col-xs-12 editbtn">										
												<div class="pull-right  linkholder">
													<a href="javascript:void(0)" onClick="open_edit(this)"><i class="fa fa-pencil"></i></a>
												</div>
											</div>
										</div>	
									</div>	
									<div class="edit-mode">
										<div class="row">
											<div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">
												<label>Education</label>
											</div>
											<div class="col-lg-6 col-md-9 col-sm-9 col-xs-12">
												
												<div class="form-group mb">
		<?= $form->field($model2,'education')->dropDownList(ArrayHelper::map(Education::find()->all(), 'name', 'name'),['class'=>'js-example-theme-multiple education-cls','style'=>'width: 100%','multiple'=>'multiple','id'=>'education1'])->label(false)?>                      

												</div>
																						
											</div>									
											<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 pull-right">
												<div class="form-group pull-right">						
													<div class="pull-right fb-btnholder nbm">																														
														<a class="btn btn-primary btn-sm" onClick="close_edit(this),education()">Save</a>
														<a class="btn btn-primary btn-sm" onClick="close_edit(this)">Cancel</a>
													</div>										
												</div>										
											</div>
										</div>	
									</div>	
								</div>	
													<?php ActiveForm::end() ?>
													   
							</li>
												
												 <li>
												 <?php $form = ActiveForm::begin(['id' => 'frm-interests','options'=>['onsubmit'=>'return false;',],]); ?>              
								<div class="setting-group">							
									<div class="normal-mode">
										<div class="row">
											<div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">
												<label>Interests</label>
											</div>
											<div class="col-lg-9 col-md-7 col-sm-7 col-xs-12">										
												<div class="info">																   				   								
													<label id="interests">                                                                                          
													<?= $interests ?>																							
																											
													</label>
												</div>
											</div>
											<div class="col-lg-1 col-md-2 col-sm-2 col-xs-12 editbtn">										
												<div class="pull-right  linkholder">
													<a href="javascript:void(0)" onClick="open_edit(this)"><i class="fa fa-pencil"></i></a>
												</div>
											</div>
										</div>	
									</div>	
									<div class="edit-mode">
										<div class="row">
											<div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">
												<label>Interests</label>
											</div>
											<div class="col-lg-6 col-md-9 col-sm-9 col-xs-12">
												<div class="form-group mb">
		<?= $form->field($model2,'interests')->dropDownList(ArrayHelper::map(Interests::find()->all(), 'name', 'name'),['class'=>'js-example-theme-multiple interests-cls','style'=>'width: 100%','multiple'=>'multiple','id'=>'interests1'])->label(false)?>                                                                                  

												</div>
																					
											</div>									
											<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 pull-right">
												<div class="form-group pull-right">						
													<div class="pull-right fb-btnholder nbm">																		
														<a class="btn btn-primary btn-sm" onClick="close_edit(this),interests()">Save</a>
														<a class="btn btn-primary btn-sm" onClick="close_edit(this)">Cancel</a>
													</div>										
												</div>										
											</div>
										</div>	
									</div>	
								</div>	
													<?php ActiveForm::end() ?>
													   
							</li>
												
												 <li>
												 <?php $form = ActiveForm::begin(['id' => 'frm-occupation','options'=>['onsubmit'=>'return false;',],]); ?>              
								<div class="setting-group">							
									<div class="normal-mode">
										<div class="row">
											<div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">
												<label>Occupation</label>
											</div>
											<div class="col-lg-9 col-md-7 col-sm-7 col-xs-12">										
												<div class="info">																   				   								
													<label id="occupation">                                                                                          
													<?= $occupation ?>																							
																											
													</label>
												</div>
											</div>
											<div class="col-lg-1 col-md-2 col-sm-2 col-xs-12 editbtn">										
												<div class="pull-right  linkholder">
													<a href="javascript:void(0)" onClick="open_edit(this)"><i class="fa fa-pencil"></i></a>
												</div>
											</div>
										</div>	
									</div>	
									<div class="edit-mode">
										<div class="row">
											<div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">
												<label>Occupation</label>
											</div>
											<div class="col-lg-6 col-md-9 col-sm-9 col-xs-12">
												
														<div class="form-group mb">
		<?= $form->field($model2,'occupation')->dropDownList(ArrayHelper::map(Occupation::find()->all(), 'name', 'name'),['class'=>'js-example-theme-multiple occupation-cls','style'=>'width: 100%','multiple'=>'multiple','id'=>'occupation1'])->label(false)?>                                                                                                 

														</div>
												
											</div>									
											<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 pull-right">
												<div class="form-group pull-right">						
													<div class="pull-right fb-btnholder nbm">																		
														<a class="btn btn-primary btn-sm" onClick="close_edit(this),occupation()">Save</a>
														<a class="btn btn-primary btn-sm" onClick="close_edit(this)">Cancel</a>
													</div>										
												</div>										
											</div>
										</div>	
									</div>	
								</div>	
													<?php ActiveForm::end() ?>
													   
							</li>
						</ul>
						 
					</div>
				</div>
			
			</div>
			
		</div>
		
	</div>
    <?php include('includes/footer.php');?>

<script type="text/javascript">
	jq(document).ready(function(){
	
		var yr=new Date().getFullYear();
                jq("#datepicker").datepicker({
			dateFormat: "dd-mm-yy",
			changeMonth: true,
			changeYear: true,
			maxDate:  new Date(yr, 12-1, 31),
			minDate: new Date(1920, 1-1, 1),
			yearRange: "1920:"+yr+""
		});
		jq('#popupDatepicker').datepick({popupContainer:'.date-container',alignment:'top',autoSize: true,onSelect: changedate,maxDate:  new Date(yr, 12-1, 31),minDate: new Date(1920, 1-1, 1), yearRange: "1920:"+yr+""});
		//jq('#popupDatepicker').datepick({popupContainer:'.date-container',alignment:'top',autoSize: true,onSelect: changedate,maxDate: new Date,minDate: new Date(1920, 1-1, 1), yearRange: "1920:"+yr+""});
		jq('#inlineDatepicker').datepick({onSelect: showDate});
	
	});
	function changedate()
	{
		var adate = $('#popupDatepicker').val();
		jq('#birthdate').val(adate);
	}
	function showDate(date) {
		   
		alert('The date chosen is ' + date);
	}
</script>
	
<script type="text/javascript">
	
	(function () {
	  //  $("#test").hide();
	  jq('#datetimepicker2').datetimepicker({
			format: "DD-MM-YYYY",
			maxDate: new Date
		}); })(jQuery); 
	</script>  
	<script>
	   jq("#eml_access li").on("click", function(){
	  var a = $(this).text();
	  jq('#email_access').val(a);
	});
	  jq("#alt_eml_access li").on("click", function(){
	  var z = $(this).text();
	  jq('#alternate_email_access').val(z);
	});
	  jq("#birth_access li").on("click", function(){
	  var b = $(this).text();
	  jq('#birth_date_access').val(b);
	});
	jq("#gen_access li").on("click", function(){
	  var c = $(this).text();
	  jq('#gender_access').val(c);
	});
	jq("#lang_access li").on("click", function(){
	  var d = $(this).text();
	  jq('#language_access').val(d);
	});
	jq("#rel_access li").on("click", function(){
	  var e = $(this).text();
	  jq('#religion_access').val(e);
	});
	jq("#pol_access li").on("click", function(){
	  var f = $(this).text();
	  jq('#political_view_access').val(f);
	});
	jq("#mbl_access li").on("click", function(){
	  var m = $(this).text();
	  jq('#mobile_access').val(m);
	});
</script>
<script>
    
    function checkAvailability() {

    $("#checckavail").attr("disabled", true);
    var phone = $("#phone").val();
	var ptn = /([0-9\s\-]{1,})(?:\s*(?:#|x\.?|ext\.?|extension)\s*(\d+))?$/;
   
    if(phone =="")
    {
        $('#fail').html('Please Enter Mobile Number.');
        $("#phn-success").hide();
        $("#phn-fail").show();
        document.getElementById('phone').focus();
        return false;
		
    }
    else if(!ptn.test(phone))
    {
        $('#fail').html('Please Enter valid Number.');
        $("#phn-success").hide();
        $("#phn-fail").show();
        document.getElementById("phone").focus();
        return false;
    }
	else
	{
	$.ajax({
	url: "<?php echo Yii::$app->urlManager->createUrl(['site/phone']); ?>",
	data:'phone='+$("#phone").val(),
	type: "POST",
        //async: false,
	success:function(data){
            if(data == '1'){
            $("#phn-fail").hide();    
            //$("#phn-success").show();
			
			$("#checckavail").attr("disabled", false);
			$("#checckavail").css('pointer-events', 'auto');
             return true;
                
            } else {
               $("#checckavail").attr("disabled", true);
			   $("#checckavail").css('pointer-events', 'none');
                $('#fail').html('The Number which you have entered is already registered.');
                $("#phn-success").hide();
                $("#phn-fail").show();
			 
               return false;
			   
            
            }
			
	},

	//error:function (){}
	});
	}
}
    
    function firstToUpperCase(string)
    {
        return string.charAt(0).toUpperCase() + string.slice(1).toLowerCase();
    }
    function basicinfo(){
        var bfname = $("#fname").val();
        var blname = $("#lname").val();
        var fname = firstToUpperCase( bfname );
        var lname = firstToUpperCase( blname );
        $("#fname").val(fname);
        $("#lname").val(lname);
        var reg_nm = /^[a-zA-Z\s]+$/;

        if(fname == "")
        {
                $('#fail').html('Please Enter First Name.').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
                return false;
        }

        else if(fname.length < 2)
        {
                $('#fail').html('Please Enter minimum 2 characters in First Name.').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
                return false;
        }

        else if(!reg_nm.test(fname))
        {
                $('#fail').html('Please Enter Characters only in First Name.').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
                return false;
        }

        else if(lname == "")
        {
                $('#fail').html('Please Enter Last Name.').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
                return false;
        }

        else if(lname.length < 2)
        {
                $('#fail').html('Please Enter minimum 2 characters in Last Name.').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
                return false;
        }

        else if(!reg_nm.test(lname))
        {
                $('#fail').html('Please Enter Characters only in Last Name.').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
                return false;
        }
        else
        {
		$.ajax({
			   type: 'POST',
			   url: '<?php echo Yii::$app->urlManager->createUrl(['site/basicinfo']); ?>',
			   data: $("#frm-name").serialize(),
			   success: function(data){
				var result = $.parseJSON(data);

				var fname = result.substr(0,result.indexOf(' '));
				 $("#name").html(result);
				 $("#u_id").html(fname);
				  $('#suceess').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
			   }
			   
		   });
        }
    }
    function email_address(){
      
       // var data_save = $('#frm-email').serializeArray();
        //data_save.push({ name: "email_access", value: list }); 
     
        var email = $("#bemail").val();
        var pattern = /^([a-zA-Z0-9_.-])+@([a-zA-Z0-9_.-])+\.([a-zA-Z])+([a-zA-Z])+/;
        if(email =="")
        {
            $('#fail').html('Please Enter Email.').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
            return false;
        }

        else if(!pattern.test(email))
        {
            $('#fail').html('Please Enter valid Email.').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
            return false;
        }
        else
        {
		$.ajax({
			   type: 'POST',
			   url: '<?php echo Yii::$app->urlManager->createUrl(['site/basicinfo']); ?>',
			   data: $("#frm-email").serialize(),
				//data: data_save,
			   success: function(data){
				  var result = $.parseJSON(data);
				  var email = result[0];
				  if(email == '0'){
					   $('#fail').html('Oops..!! this email id is already in use.').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
				  }
				  else if(email == '1'){
					   $('#info').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
				  }
				  else{
					  $("#email").html(result[0]);
					  $('#suceess').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
				  }
			   
			   }   
		   });
        }
    }
    
    function email2(){
     var email = $("#alternate_email").val();
        var pattern = /^([a-zA-Z0-9_.-])+@([a-zA-Z0-9_.-])+\.([a-zA-Z])+([a-zA-Z])+/;
        if(email =="")
        {
            $('#fail').html('Please Enter slternate Email.').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
            return false;
        }

        else if(!pattern.test(email))
        {
            $('#fail').html('Please Enter valid alternate Email.').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
            return false;
        }
        else
        {
		$.ajax({
			   type: 'POST',
			   url: '<?php echo Yii::$app->urlManager->createUrl(['site/basicinfo']); ?>',
			   data: $("#frm-alt-email").serialize(),
			   success: function(data){
				  var result = $.parseJSON(data);
				 $("#alt-email").html(result[0]);
				  $('#suceess').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
				 
			   }
			   
		   });
        }
    }
	

	
      function pwd(){
		  
	  var password = $("#password").val();
      var con_password = $("#con_password").val();
      var old_password = $("#old_password").val();
		  
                if(old_password == "")
                {
                    $('#pwd_err').html('Oops..!! Please Enter Old Password.').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
                    return false;
                }
                else if(old_password.length < 6)
                {
                    $('#pwd_err').html('Oops..!! Please Enter Old Password of 6 length.').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
                    return false;
                }
                else if(password == "")
                {
                    $('#pwd_err').html('Oops..!! Please Enter Password.').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
                    return false;
                }
                else if(password.length < 6)
                {
                    $('#pwd_err').html('Oops..!! Please Enter Password of 6 length.').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
                    return false;
                }
                else if(con_password == "")
                {
                    $('#pwd_err').html('Oops..!! Please Enter Confirm Password.').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
                    return false;
                }
                else if(con_password.length < 6)
                {
                    $('#pwd_err').html('Oops..!! Please Enter Confirm Password of 6 length.').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
                    return false;
                }
                else
                {
		  $.ajax({
			   type: 'POST',
			   url: '<?php echo Yii::$app->urlManager->createUrl(['site/password']); ?>',
			   success: function(data){
				   var result = $.parseJSON(data);
					 if(result == old_password){
						 
						 if(password != con_password)
							{
								 $('#pwd_err').html('Oops..!! Password Mismatch.').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
								return false;
							}
							else{

								$.ajax({
										   type: 'POST',
										   url: '<?php echo Yii::$app->urlManager->createUrl(['site/basicinfo']); ?>',
										   data: $("#frm-pwd").serialize(),
										   success: function(data){
                                                                                       var result = $.parseJSON(data);
                                                                                        $("#pwd-change").html(result[0]);
											$('#suceess').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
											
												 if ($("#test1").is(':checked')){
													DoPost();
													
												}
												else{
													
												}
											
										   }

								   });
							}
					}
					else{
					 $('#pwd_err2').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
					 return false;
					
				}
					 
				   
			   }
			   
		   });
                }
    }	
    
     function phone(){

      
		$.ajax({
			   type: 'POST',
			   url: '<?php echo Yii::$app->urlManager->createUrl(['site/basicinfo']); ?>',
			   data: $("#frm-phone").serialize(),
			   success: function(data){
					var result = $.parseJSON(data);
					 $("#phone1").html(result[0]);
					  $('#suceess').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
			   }
			   
		   });
    }
    
    function city(){
    var cntry = $("#country").val();
    var isd = $("#isd_code").val();
		$.ajax({
			   type: 'POST',
			   url: '<?php echo Yii::$app->urlManager->createUrl(['site/basicinfo']); ?>',
			   data: $("#frm-city").serialize()+'&isd_code='+isd+'&country='+cntry,
			   success: function(data){
                                         var result = $.parseJSON(data);                                      
					 $("#city").html(result[0]);
                                         $("#country1").html(result[1]);
                                         $("#isd_code").html(result[2]);
                                         
					  $('#suceess').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
			   }
			   
		   });
    }
    function country(){
      
		$.ajax({
			   type: 'POST',
			   url: '<?php echo Yii::$app->urlManager->createUrl(['site/basicinfo']); ?>',
			   data: $("#frm-country").serialize(),
			   success: function(data){
				
				  var result = $.parseJSON(data);
					 $("#country1").html(result[0]);
					  $('#suceess').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
			   }
			   
		   });
    }
    function gender(){
     
		$.ajax({
			   type: 'POST',
			   url: '<?php echo Yii::$app->urlManager->createUrl(['site/basicinfo']); ?>',
			   data: $("#frm-gender").serialize(),
			   success: function(data){
			   
					var result = $.parseJSON(data);
					 $("#gender").html(result[0]);
					  $('#suceess').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
			   }
			   
		   });
    }
    function birth_date(){

		$.ajax({
			   type: 'POST',
			   url: '<?php echo Yii::$app->urlManager->createUrl(['site/basicinfo']); ?>',
			   data: $("#frm-birth-date").serialize(),
			   success: function(data){
			  
				   var result = $.parseJSON(data);
					 $("#birth_date").html(result[0]);
					  $('#suceess').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
			   }
			   
		   });
    }
     function current_city(){
		 
		 var isd_code = $("#isd_code").val();
		 
		$.ajax({
			   type: 'POST',
			   url: '<?php echo Yii::$app->urlManager->createUrl(['site/basicinfo']); ?>',
			   data: $("#frm-current-city").serialize(),
			   success: function(data){ 
				//alert(data);return false;
					var result = $.parseJSON(data);
					 $("#current_city").html(result[0]);
					  $('#suceess').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
			   }
			   
		   });
    }
     function hometown(){
      
		$.ajax({
			   type: 'POST',
			   url: '<?php echo Yii::$app->urlManager->createUrl(['site/basicinfo']); ?>',
			   data: $("#frm-hometown").serialize(),
			   success: function(data){
				   var result = $.parseJSON(data);
					 $("#hometown").html(result[0]);
					  $('#suceess').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
			   }
			   
		   });
    }
    function language(){
      
		$.ajax({
			   type: 'POST',
			   url: '<?php echo Yii::$app->urlManager->createUrl(['site/basicinfo']); ?>',
			   data: $("#frm-language").serialize(),
			   success: function(data){
                            var result = $.parseJSON(data);
                            $("#language").html(result[0]);
                            $('#suceess').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
			   }
			   
		   });
    }
    function religion(){
      
            $.ajax({
                   type: 'POST',
                   url: '<?php echo Yii::$app->urlManager->createUrl(['site/basicinfo']); ?>',
                   data: $("#frm-religion").serialize(),
                   success: function(data){  
                    var result = $.parseJSON(data);
                    $("#religion").html(result[0]);
                    $('#suceess').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
                   }
                   
               });
    }
    function political(){
      
		$.ajax({
			   type: 'POST',
			   url: '<?php echo Yii::$app->urlManager->createUrl(['site/basicinfo']); ?>',
			   data: $("#frm-political-view").serialize(),
			   success: function(data){                   
				   var result = $.parseJSON(data);
				   $("#political_view").html(result[0]);
				   $('#suceess').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
			   }
			   
		   });
	}
   function about(){

	$.ajax({
		   type: 'POST',
		   url: '<?php echo Yii::$app->urlManager->createUrl(['site/basicinfo']); ?>',
		   data: $("#frm-about").serialize(),
		   success: function(data){  
			var result = $.parseJSON(data);
			$("#about").html(result[0]);
			$('#suceess').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
		   }

	   });

	}
	
	function education(){

	$.ajax({
		   type: 'POST',
		   url: '<?php echo Yii::$app->urlManager->createUrl(['site/basicinfo']); ?>',
		   data: $("#frm-education").serialize(),
		   success: function(data){  
				var result = $.parseJSON(data);
				 $("#education").html(result[0]);
				  $('#suceess').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
		   }

	   });

	}
	
	function interests(){

	$.ajax({
		   type: 'POST',
		   url: '<?php echo Yii::$app->urlManager->createUrl(['site/basicinfo']); ?>',
		   data: $("#frm-interests").serialize(),
		   success: function(data){  
				var result = $.parseJSON(data);
				 $("#interests").html(result[0]);
				  $('#suceess').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
		   }

	   });

	}
	
	function occupation(){

	$.ajax({
		   type: 'POST',
		   url: '<?php echo Yii::$app->urlManager->createUrl(['site/basicinfo']); ?>',
		   data: $("#frm-occupation").serialize(),
		   success: function(data){  
				var result = $.parseJSON(data);
				 $("#occupation").html(result[0]);
				  $('#suceess').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
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
<script>


jq(document).ready(function () {

	jq("#language1").select2({
	  tags: true
	});

	jq(".occupation-cls").select2().val([<?php echo $occu_str; ?>]).trigger("change");
	jq(".language-cls").select2().val([<?php echo $lang_str; ?>]).trigger("change");
	jq(".interests-cls").select2().val([<?php echo $inter_str; ?>]).trigger("change");
	jq(".education-cls").select2().val([<?php echo $edu_str; ?>]).trigger("change");

	jq("#e1").select2("val", ["View" ,"Modify"]);

	jq("#education1").select2({
	  placeholder: "Your highest degree",
	  tags: true
	});
	jq("#interests1").select2({
	  placeholder: "What are your current interest",
	  tags: true
	});
	jq("#occupation1").select2({
	  placeholder: "Most recent occupation",
	  tags: true
	});
	jq("#language1").select2({
	  placeholder: "languages you can read and write",
	  tags: true
	});
});

</script>