<?php
 
/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use frontend\assets\AppAsset;

use yii\widgets\ActiveForm;
use yii\mongodb\ActiveRecord;

use frontend\models\LoginForm;
use frontend\models\Slider;
use frontend\models\NotificationSetting;
use frontend\models\SecuritySetting;
use yii\validators\Validator;
use yii\helpers\ArrayHelper;

$session = Yii::$app->session;
$error = $session->get('loginerror');
$email = '';

$all_slider = ArrayHelper::map(Slider::find()->all(), 'slider_image', 'slider_image');
	
	//echo  "<pre>";print_r($all_slider); die();

if(isset($_GET['email']) && !empty($_GET['email']) )
{
	
$notify = new NotificationSetting();
$notify2 = $notify->notification2();
$security_settings = new SecuritySetting();
$security_settings2 = $security_settings->security2();
   
    $email = $_GET['email'];
    
    $update = LoginForm::find()->where(['email' => $email])->one();
    
    $update->status = '1';
    $update->update();
     
     $admin_email = 'markand.trivedi@scalsys.in';
           
     $user = LoginForm::find()->where(['email' => $email])->one();

	$username = $user->username;
        $fname = $user->fname;
	$lname = $user->lname;
	$phone = $user->phone;
	$gender = $user->gender;
	$city = $user->city;
	$country = $user->country;
	
	 /* Mail To Admin*/
                    try {
                    $test2 = Yii::$app->mailer->compose()
                    ->setFrom('no-reply@travbud.com')
                    ->setTo($admin_email)
                    ->setSubject('TravBud- Registerd User Information.')
                    ->setHtmlBody(' <html>
	<head>
		<meta charset="utf-8" />
		<title>TravBud</title>
	</head>
	
	<body style="margin:0;padding:0;">
		<div style="color: #353535; float:left; font-size: 13px;width:100%; font-family:Arial, Helvetica, sans-serif;text-align:center;padding:20px 0 0;">
			<div style="width:600px;display:inline-block;">
				<img src="http://travbud.com/frontend/web/assets/c2bb9cd8/images/logo.png" style="margin:0 0 20px;"/>
				<div style="clear:both"></div>
				<span style="font-size:18px;margin:0 0 10px;float:left;width:100%;text-transform:uppercase;font-weight:bold;color:#4083BF;">Registered User Information</span>
				<ul style="display:inline-block;text-align:left;list-style:none;border:1px solid #ddd;padding:0;padding:3px;width:430px;">
					<li style="border-bottom:1px solid #fff;background: #f5f5f5;"><span style="width: 100px;display: inline-block;padding: 15px 25px;background: #A2ADB5;color: #fff;">User Name</span style=""><span style="padding:15px 25px;width:200px;color: #4C4C4C;">'.$fname.'</span></li>
					<li style="border-bottom:1px solid #fff;background: #f5f5f5;"><span style="width: 100px;display: inline-block;padding: 15px 25px;background: #A2ADB5;color: #fff;">Last Name</span style=""><span style="padding:15px 25px;width:200px;color: #4C4C4C;">'.$lname.'</span></li>
					<li style="border-bottom:1px solid #fff;background: #f5f5f5;"><span style="width: 100px;display: inline-block;padding: 15px 25px;background: #A2ADB5;color: #fff;">Email</span style=""><span style="padding:15px 25px;width:200px;color: #4C4C4C;">'.$email.'</span></li>
					<li style="border-bottom:1px solid #fff;background: #f5f5f5;"><span style="width: 100px;display: inline-block;padding: 15px 25px;background: #A2ADB5;color: #fff;">Phone</span style=""><span style="padding:15px 25px;width:200px;color: #4C4C4C;">'.$phone.'</span></li>
					<li style="border-bottom:1px solid #fff;background: #f5f5f5;"><span style="width: 100px;display: inline-block;padding: 15px 25px;background: #A2ADB5;color: #fff;">Gender</span style=""><span style="padding:15px 25px;width:200px;color: #4C4C4C;">'.$gender.'</span></li>
					<li style="border-bottom:1px solid #fff;background: #f5f5f5;"><span style="width: 100px;display: inline-block;padding: 15px 25px;background: #A2ADB5;color: #fff;">City</span style=""><span style="padding:15px 25px;width:200px;color: #4C4C4C;">'.$city.'</span></li>
					<li style="border-bottom:1px solid #fff;background: #f5f5f5;"><span style="width: 100px;display: inline-block;padding: 15px 25px;background: #A2ADB5;color: #fff;">Country</span style=""><span style="padding:15px 25px;width:200px;color: #4C4C4C;">'.$country.'</span></li>						
				</ul>
			</div>
			<div style="float: left;width: 100%;text-align: center">
			   <div style="color: #8f8f8f;text-align: center;">&copy;  www.travbud.com All rights reserved.</div>
			   <div style="text-align: center;font-weight: bold;width: 100%;margin:10px 0 20px;color:#505050;">For anything you can reach us directly at <a href="contact@travbud.com" style="color:#4083BF">contact@travbud.com</a></div>
		   </div>
		</div>
		
	</body>
</html>')
                    ->send(); 
                    
$test3 = Yii::$app->mailer->compose()
                    ->setFrom('no-reply@travbud.com')
                    ->setTo($email)
                    ->setSubject('TravBud- Registerd User Information.')
                    ->setHtmlBody('<html>
	<head>
		<meta charset="utf-8" />
		<title>TravBud</title>
	</head>
	
	<body style="margin:0;padding:0;">
	
        <div style="text-align: left;font-size: 12px;margin:10px 0 0;color:#666;">
						
						
        Dear '.$fname.',<br/><br/>
        We are very happy to have you as a valued member of TravBud community. Our aim is to connect each other socially and have Travel benefits.<br/>

        Start enjoying being part of our community, share your experience with community and explore the world at finger tip.<br/>

        Note: Privacy is important to us; Therefore, we will not sell, rent or give any information from our community to anyone. <br/>
        To keep TravBud safe, fun and very respectable. Here are some social safety tips:
                <br/>	
                <ul style="padding:0 0 0 12px;margin:10px 0 5px;">
                        <li style="margin:0 0 5px;">
                                <b>Report abuse:</b>  Please report anything inappropriate, immediate action will be taken.
                        </li>
                        <li style="margin:0 0 5px;">
                                <b>Block people:</b> If someone is bothering you, block them! They wont be able to contact you again
                        </li>
                        <li style="margin:0 0 5px;">
                                <b>Public posts:</b> Dont post anything youll regret. Dont give out personal information.
                        </li>
                        <li style="margin:0 0 5px;">
                                <b>Post Decent Content:</b> Absolutely no adult or semi adult photos or videos are permitted on this site. Therefore, please do not try to upload, link and share any adult or semi-adult images. No gambling, drugs, adult or alcoholic ads are allowed. Harassment, inappropriate behaviors or solicitation are not permitted on this site.</br>
                           Software and the management monitor the site, any inappropriate activities </br>
                           will not be tolerated and will result in banning your name, email address </br>
                           and  IP address permanently. So please be social and thank you for your cooperation.
                        </li>														
                </ul>


       </br>
       <b> 	Have fun and stay safe!</b><br/>

       Thank you for registering.
        </br></br>
        <span style="font-size:10px;">If you have any question/suggestion, feel free to write us at <a href="#">helpdesk@travbud.com</a></span>

       </div>
		
	</body>
</html>')
    ->send();                    
            
            $url = Yii::$app->urlManager->createUrl(['site/index']);
            Yii::$app->getResponse()->redirect($url); 
            } 
            catch (ErrorException $e) 
            {
                    echo 'Caught exception: ',  $e->getMessage(), "\n";
            }
     

}
elseif(isset($_GET['id']) && !empty($_GET['id']))
{
    $userid = $_GET['id'];
    
    $update = LoginForm::find()->where(['_id' => $userid])->one();
    
    if(!empty($update))
    {
      $email = $update->email;
      $session = Yii::$app->session;
      $session->set('user_id',$userid);
       //$session->set('email',$email);
    }
    echo $email = $update->email;
   // print_r($update);exit;
}
//use vendor\bower\travel\assets\AppAsset;
//AppAsset::register($this);
//AppAsset::register($this);
$asset = frontend\assets\AppAsset::register($this);

$baseUrl = AppAsset::register($this)->baseUrl;
?>

<?php $this->beginBody() ?>
	<div class="home-wrapper">
		<!--
		<div class="bgImage">
			<div class="bgImage--blur"></div>
			<img class="pano desk-homebg" src="<?= $baseUrl?>/images/home-bg.jpg">
			<img class="pano mbl-homebg" src="<?= $baseUrl?>/images/home-bg.png">
		</div>	
		-->
		<div id="myCarousel" class="carousel slide carousel-fade">
        
			<!-- Wrapper for Slides -->
			<div class="carousel-inner">
			
			<?php
				
				$i=1;
				foreach($all_slider as $singleslider) {
					$cls = ($i==1) ? 'active' : ''; 
				?>
				<div class="item <?=$cls ?>">
				<!-- Set the first background image using inline CSS below. -->
				<div class="fill" style="background-image:url('../../uploads/slider/<?= $singleslider ?>');"></div>					
				</div>
				<?php
				$i++;
				} 
				
			?>
				<!--<div class="item active">
					
					<div class="fill" style="background-image:url('<?= $baseUrl?>/images/home-bg.jpg');"></div>					
				</div>
				<div class="item">
					
					<div class="fill" style="background-image:url('<?= $baseUrl?>/images/home-bg-2.jpg');"></div>					
				</div>
				<div class="item">
					
					<div class="fill" style="background-image:url('<?= $baseUrl?>/images/home-bg-3.jpg');"></div>					
				</div>-->
			</div>

		</div>
		<div class="abs-page-content">
			<div class="hcontent-holder">
				<header>
					<div class="home-container">
						<div class="hlogo-holder">
							<a href="<?php echo Yii::$app->urlManager->createUrl(['site/index']); ?>" class="home-logo"><img src="<?= $baseUrl?>/images/home-logo.png"/></a>            
						   <!-- <span class="tagline">Social Networking Community for Travel!</span>-->
						</div>
						<div class="head-right">
							<!-- <span class="webintro">
								Travel Reviews, Blogs, Articles, Photos, Events & More
							</span>-->
							<div class="signup-part">
								<a href="javascript:void(0)" onclick="flipSectionTo('login');" class="homebtn">Log in</a>
							</div>
							<div class="login-part">
								<a href="javascript:void(0)" onclick="flipSectionTo('signup');" class="homebtn">Sign Up</a>
							</div>
						</div>
					</div>
				</header>
				
				<div class="home-content">
					<div class="login-part homes-part">
						<div class="container">        	               
							<div class="row">
								<div class="home-divider">
									<span class="div-or">or</span>
								</div>

								<div class="col-sm-6 left-hcontent">
									<div class="sociallink-area">
										<a href="<?php echo Yii::$app->request->baseUrl.'?r=site/auth&authclient=facebook' ?>" class="fb-btn"><i class="fa fa-facebook-square"></i>Connect with Facebook</a>
										<p>(it's just faster login. We never post on Facebook)</p>
									</div>
								</div>
								<div class="col-sm-6 right-hcontent">
									<?php //if(isset($error) && !empty($error)) {
											//$session->set('loginerror','');
									?>
										<div class="frow">                              
											<div class="login-notice">
												<span class="success-note">Successfully Logged in!</span>
												<span class="info-note">Fill in the mandatory fields.</span>
												<span class="error-note">Please Login with Correct Credentials.</span>
											</div>
										</div>
									<?php //}?>
									<?php $form = ActiveForm::begin(['action' => ['site/login'],'options' => ['method' => 'post','class' => 'top-form','id'=>'frm-login']]) ?>
									<!--<form action="#">-->
																	<input type="hidden" name="lat" id="lat" value=""/>
																	<input type="hidden" name="long" id="long" value=""/>
										<div class="frow">                              
											<input type="hidden" name="login" value="1" />
											<?= $form->field($model,'email')->textInput(array('placeholder'=>'Email address','onkeyup'=>'validate_lemail()','id'=>'lemail','onchange'=>'validate_lemail()'))->label(false)?>
											<div id='leml-success' class="frm-validicon" style='display: none'><img src="<?= $baseUrl?>/images/frm-check.png"/></div>
															 <div id='leml-fail' class="frm-validicon" style='display: none'><img src="<?= $baseUrl?>/images/frm-cross.png"/></div>
											<!--<input type="email">-->
										</div>                            <div class="frow">
										   
											<!--<input type="password">-->
											 <?= $form->field($model,'password')->passwordInput(array('placeholder'=>'Password','onkeyup'=>'validate_lpassword()','id'=>'lpassword','onchange'=>'validate_lpassword()'))->label(false)?>
											<div id='lpwd-success' class="frm-validicon" style='display: none'><img src="<?= $baseUrl?>/images/frm-check.png"/></div>
											<div id='lpwd-fail' class="frm-validicon" style='display: none'><img src="<?= $baseUrl?>/images/frm-cross.png"/></div>
										</div>                            
										<div class="frow">
											<a href="javascript:void(0)" class="pull-right homebtn" onclick="login()">Log in</a>
										 <!-- <input class="pull-right homebtn" type="submit" name="Login" value="Login"/>-->
											<div class="go-sulink">
												Not a member?
												<a href="javascript:void(0)" onclick="flipSectionTo('signup');">Sign Up Here</a>
											</div>
											
										</div>
										<div class="frow nbm">
											<div class="row">
												<div class="col-sm-6 fhalf">
													<div class="h-checkbox">
														<input type="checkbox" id="test1" />
														<label for="test1">Remember Me</label>
													</div>
												</div>
												<div class="col-sm-6 fhalf">
													<!--<a href="<?php //echo Yii::$app->urlManager->createUrl(['site/forgotpassword'])?>" class="fp-link">Forgot Password?</a>-->
													<a href="#forgot-pass" class="popup-modal fp-link"><span class="np-right-caret"></span>Forgot Password?</a>
																								
												</div>
											</div>
										</div>
									<?php ActiveForm::end() ?>
									<!--</form>-->
								</div>
							</div>
						</div>
					</div>
					<div class="signup-part homel-part">
						<div class="container">        	
							
							<div class="row">
								<div class="home-divider">
									<span class="div-or">or</span>
								</div>
								<div class="col-sm-6 left-hcontent">
									<div class="sociallink-area">
										<a href="<?php echo Yii::$app->request->baseUrl.'?r=site/auth&authclient=facebook' ?>" class="fb-btn"><i class="fa fa-facebook-square"></i>Sign up with Facebook</a>
										<p>(it's just faster login. We never post on Facebook)</p>
									</div>
								</div>					
								<div class="col-sm-6 right-hcontent">
									<div id="suceess" style="display: none;" class="form-successmsg">Thanks for joining TravBud</div>
									<div id="fail" class="form-failuremsg" style="display: none;">Oops..!! Somthing Went Wrong </div>
									<div id="hold" class="form-failuremsg" style="display: none;">This Email Id already Exists </div>

									<div class="frow">                              
											<div class="signup-notice">
													<span class="success-note">Successfully Logged in!</span>
													<span class="error-note"></span>
													<span class="info-note">Fill in the mandatory fields.</span>
											</div>
									</div>
									<!--<form action="#">-->
									<?php $form = ActiveForm::begin(
										[
											'id' => 'frm',
											'options'=>[
												'onsubmit'=>'return false;',
												'enableAjaxValidation' => true,
												],
										]
									); ?>
										<div class="frow">                                
											 <?= $form->field($model,'fname')->textInput(array('placeholder'=>'First Name','onkeyup'=>'validate_fname()','id'=>'fname'))->label(false)?>
											 <div id='fnm-success' class="frm-validicon" style='display: none'><img src="<?= $baseUrl?>/images/frm-check.png"/></div>
															 <div id='fnm-fail' class="frm-validicon" style='display: none'><img src="<?= $baseUrl?>/images/frm-cross.png"/></div>
											<!--<input type="text">-->
										</div>
										<div class="frow">                               
											<?= $form->field($model,'lname')->textInput(array('placeholder'=>'Last Name','onkeyup'=>'validate_lname()','id'=>'lname'))->label(false)?>
											<div id='lnm-success' class="frm-validicon" style='display: none'><img src="<?= $baseUrl?>/images/frm-check.png"/></div>
															 <div id='lnm-fail' class="frm-validicon" style='display: none'><img src="<?= $baseUrl?>/images/frm-cross.png"/></div>
											<!--<input type="text">-->
										</div> 
										<div class="frow">                               
											<?= $form->field($model,'email')->textInput(array('placeholder'=>'Email','onkeyup'=>'validate_email()','id'=>'email','value'=>$email,'onchange'=>'validate_email()'))->label(false)?>
											<div id='eml-success' class="frm-validicon" style='display: none'><img src="<?= $baseUrl?>/images/frm-check.png"/></div>
															 <div id='eml-fail' class="frm-validicon" style='display: none'><img src="<?= $baseUrl?>/images/frm-cross.png"/></div>
											<!--<input type="email">-->
										</div>
										<div class="pass-holder">
												<div class="row">
														<div class="col-sm-6">
																<div class="frow">											
																		  <?= $form->field($model,'password')->passwordInput(array('placeholder'=>'Password','onkeyup'=>'validate_spassword()','id'=>'signup_password'))->label(false)?>
																	<div id='spwd-success' class="frm-validicon" style='display: none'><img src="<?= $baseUrl?>/images/frm-check.png"/></div>
															 <div id='spwd-fail' class="frm-validicon" style='display: none'><img src="<?= $baseUrl?>/images/frm-cross.png"/></div>
																		<!--<input type="password">-->
																</div>
														</div>
														<div class="col-sm-6">
																<div class="frow">										
																		<?= $form->field($model,'con_password')->passwordInput(array('placeholder'=>'Retype Password','onkeyup'=>'validate_conpassword()','id'=>'signup_con_password'))->label(false)?>
																	<div id='conpwd-success' class="frm-validicon" style='display: none'><img src="<?= $baseUrl?>/images/frm-check.png"/></div>
															 <div id='conpwd-fail' class="frm-validicon" style='display: none'><img src="<?= $baseUrl?>/images/frm-cross.png"/></div>
																		<!--<input type="password">-->
																</div>
														</div>
												</div> 
										</div>							
			<!--                            <div class="frow">                               
											<div class="hdate-holder">
												<div class="row">
													<div class="col-sm-3 dholder">
														<select>
															<?php
															for($m = 1; $m<=31; $m++){
																//echo '<option>'.$m.'</option>';
															}
															?>
														</select>
													</div>
													<div class="col-sm-4 mholder">
														<select>
															<option>Jan</option>
															<option>Feb</option>
															<option>Mar</option>
															<option>Apr</option>
															<option>May</option>
															<option>Jun</option>
															<option>Jul</option>
															<option>Aug</option>
															<option>Sep</option>
															<option>Oct</option>
															<option>Nov</option>
															<option>Dec</option>
														</select>
													</div>
													<div class="col-sm-5 yholder">
														<select>
															<?php
															for($i = 1984; $i<=2016; $i++){
																//echo '<option>'.$i.'</option>';
															}
															?>
														   
															
														</select>
													</div>
												</div>
											</div>
										</div>  -->
										<div class="frow">
			<!--                                                            <input type="text" id="popupDatepicker" name="birthdate" placeholder="Birthdate">-->
											<?= $form->field($model,'birth_date')->textInput(array('placeholder'=>'Birthdate','id'=>'datepicker','onkeydown'=>'return false;'))->label(false)?>
                                                                                        <!--<input type="text" id="datepicker" placeholder="Birthdate" />-->
											<div class="date-container">
											</div>
										</div>   
										<div class="clear"></div>
										
										<div class="frow">
											<div class="gender-holder">
											 <div class="row">
											  <div class="col-md-4 col-sm-4">
											   
												<div class="radio-holder">
													<label>
														<input type="radio" name="gender1" class="genderone" onclick="gender_status('Male')" value="Male" id="gendervaluemale"/>
														Male
													</label>
												</div>

											  </div>
											  <div class="col-md-4 col-sm-5">
					
												<div class="radio-holder">																				
													<label>
														<input type="radio" name="gender1" class="genderone" onclick="gender_status('Female')" value="Female" id="gendervaluefemale"/>
														Female
													</label>

												</div>
											   
											  </div>
											 </div>     
											</div>  
										</div>
										
										
										<!--<div class="row">
											<div class="col-sm-6">
												<div class="frow">
													<label>Gender</label>
													   <?//= $form->field($model, 'gender')->radioList(array('Male'=>'Male','Female'=>'Female'))->label(false); ?>
													<!--<input type="gender">
												</div>
											</div>
										</div>-->  
										
										<?php

												$date = time();

										?>
										<?= $form->field($model,'birth_date')->hiddenInput(array('value'=>'27-03-1991','id'=>'birthdate','name'=>'birthdate'))->label(false)?>
										<?= $form->field($model,'created_date')->hiddenInput(array('value'=>$date))->label(false)?>
										<?= $form->field($model,'updated_date')->hiddenInput(array('value'=>$date))->label(false)?>
										<?= $form->field($model,'created_at')->hiddenInput(array('value'=>$date))->label(false)?>
										<?= $form->field($model,'updated_at')->hiddenInput(array('value'=>$date))->label(false)?>  
										<?= $form->field($model,'status')->hiddenInput(array('value'=>'0'))->label(false)?>
																		<?= $form->field($model,'fullname')->hiddenInput(array('id'=>'fullname'))->label(false)?>
										<?= $form->field($model,'gender')->hiddenInput(array('value'=>'Male','id'=>'genderstatus'))->label(false)?>
							
										<div class="frow nbm">  
											<!-- <input type="submit" class="pull-right homebtn" value="Sign Up" onclick="signup()">-->
											<a href="javascript:void(0)" class="pull-right homebtn" onclick="signup()">Sign Up</a>
										</div>
									   <?php ActiveForm::end() ?>
									<!--</form>-->
								</div>
							</div>
						</div>
					</div>
				</div>
				
				<footer>
					<div class="home-container">
						<div class="row">
							<div class="col-lg-6 col-md-9 f-left">
								<div class="user-count">
								 
									<p>
										Social Networking Community for Travelers!
									 </p>
								</div>
								<ul>

									<li><a href="javascript:void(0)">About</a></li>
									<li><a href="javascript:void(0)">Privacy</a></li>
									<li><a href="javascript:void(0)">Terms</a></li>
									<li><a href="javascript:void(0)">Contact Us</a></li>


									<!--<li><a href="#">Features</a></li>-->
									
								</ul>
							</div>
							<div class="col-lg-6 col-md-3 f-right">
								<a href="javascript:void(0)"><img src="<?= $baseUrl?>/images/appstore.png" class="hfooter-icon"/></a>
								<a href="javascript:void(0)"><img src="<?= $baseUrl?>/images/googleplay.png" class="hfooter-icon"/></a>
							</div>
						</div>            
					</div>
				</footer>
					
			</div>
		</div>
		<!-- Controls -->
        <a class="left carousel-control" href="#myCarousel" data-slide="prev">
            <span class="icon-prev"></span>
        </a>
        <a class="right carousel-control" href="#myCarousel" data-slide="next">
            <span class="icon-next"></span>
        </a>
	</div>	
	<div id="forgot-pass" class="white-popup-block mfp-hide fp-modal-popup rounded abs-notice">	
		<div class="modal-title graytitle">
			<div class="mlogo"><img src="<?= $baseUrl?>/images/logo.png"/></div>
			<a class="popup-modal-dismiss popup-modal-close" href="#"><i class="fa fa-close"></i></a>
		</div>	
		<div class="fp-notice">
			<div class="fp-success">
				<i class="fa fa-check-circle"></i> Email Sent.
			</div>
					<div class="fp-error">
				<i class="fa fa-check-circle"></i> Please Enter Valid Email Address!
			</div>
		</div>
		<div class="modal-detail text-center">		
			<div class="fp-popup">			
				<div class="fp-email-holder">
                                    <input id="emaillink" name="emaillink" type="hidden" value="">
                                    <h5 class="reset-hd-fix">Reset Your Password</h5>
                                    <h6>Let's find your account</h6>
                                    <img src="<?= $baseUrl?>/images/fp-email.png"/>
                                    <input type='email' required="true" id="forgotemail" name="forgotemail" class="forgot_email" placeholder="Email address or alternate email"/>
                                    <button class="btn btn-primary btn-sm" name="sendone" onclick="forgotcontinue()" type="button">Continue</button>
				</div>
				<div class="fp-email-details" style="display:none">
					<h5 class="reset-hd-fix">We've sent you a link to change your password</h5>
					<p>We've sent you an email that will allow you to reset your password quickly and easily.</p>
                                        <span id="displayresetlink"></span>
				</div>
			</div>
			
		</div>
	</div>
        
	    
	</div>

	<script>
		$('.carousel').carousel({
			interval: 10000 //changes the speed
		})
    </script>
    <script>

	$("#lpassword").keypress(function (e) {
		chek_enter(this, e);
    });
	
	$("#lemail").keypress(function (e) {
		chek_enter(this, e);
    });
	
	function chek_enter(obj, e){
		if (e.which == 13)
        {
			login();
		}	
	}

		var visible_part="login";
		function flipSectionTo(which){
			
			visible_part=which;
			
			if(which=="login"){
				$(".signup-part").css("display","none");
				$(".login-part").css("display","inline-block");								
			}
			else{
				$(".signup-part").css("display","inline-block");
				$(".login-part").css("display","none");				
				
			}	
			setWinHeight(which);
		}
		$(document).ready(function(){
			
			//$("footer").hide();
			setTimeout(function(){
				setWinHeight(visible_part);
				//setTimeout(function(){$("footer").show();},10);
			},500);
			$("body").addClass("home-page");
			$("html").css("height","100%");
			<?php if(isset($_GET['id'])){ ?>
				 flipSectionTo('signup');
			<?php }?>
		});
		$(window).resize(function(){			
			//$("footer").hide();
			setTimeout(function(){},500);			
			
		});
		var resizeId;
		$(window).resize(function() {
			//$(".home-content").hide();
			clearTimeout(resizeId);
			resizeId = setTimeout(doneResizing, 300);
		});
		 
		 
		function doneResizing(){
			// resize completed
			
			setWinHeight(visible_part);
			//setTimeout(function(){$("footer").show();},10);
		}
		function setWinHeight(which){
			
			
			var win_h=$(window).height();
			var win_w=$(window).width();
			var content_h=$(".home-content").height();			
			
			if(which=="login"){
				content_h=$(".home-content .login-part").height();
				$("#lemail").focus();
			}
			else{
				content_h=$(".home-content .signup-part").height();
				$("#fname").focus();
			}
			
			var header_h=$("header").height();
			var footer_h=$("footer").height();
		
			var diff_h = win_h - ( header_h + footer_h + content_h + 100 );
			
			//var diff_h;
			
			if(win_w<700){				
				diff_h = 30;
			}
			else{				
				diff_h = diff_h / 2;
			}
			//alert((header_h + footer_h + content_h + 120));
			if(win_h > (header_h + footer_h + content_h + 120)){
				//alert(win_h + " > " + (header_h + footer_h + content_h));
				$("body").addClass("absfooter");
			}else{
				//alert(win_h + " <= " + (header_h + footer_h + content_h));
				if($("body").hasClass("absfooter")){$("body").removeClass("absfooter");}
			}
			//$(".home-content").show();
		}
		
		function signup(){
                    var bfname = $("#fname").val();
                    var blname = $("#lname").val();
                    var email = $("#email").val();
                    var password = $("#signup_password").val();
                    var passwordcon = $("#signup_con_password").val();
                    var fname = firstToUpperCase( bfname );
                    var lname = firstToUpperCase( blname );
                    var datepick = $("#datepicker").val();

                    $("#fullname").val(fname+ " "+lname);
                    
                    $("#fname").val(fname);
                    $("#lname").val(lname);
                    /*if(fname === '' || lname === '' || email === '' || password === '' || passwordcon === '')
                    {
                        $('.signup-notice .info-note').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
                    }*/
                    if(!(validate_fname() && validate_lname() && validate_email() && validate_spassword() && validate_conpassword()))
                    {
                        $('.signup-notice .error-note').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
                    }
                    else if(datepick == '')
                    {
                        $('.signup-notice .error-note').html('Please Select Birthdate.');
                        $('.signup-notice .error-note').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
                        $("#datepicker").focus();
                    }
                    else if(!(document.getElementById("gendervaluemale").checked) && !(document.getElementById("gendervaluefemale").checked))
                    {
                        $('.signup-notice .error-note').html('Please Select Gender.');
                        $('.signup-notice .error-note').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
                    }					
                    /*else if (password != passwordcon)
                    {
                        $('.signup-notice .error-note').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
                        return false;
                    }*/
                    else
                    {
                        $.ajax({
                           type: 'POST',
                           url: '<?php echo Yii::$app->urlManager->createUrl(['site/signup']); ?>',
                           data: $("#frm").serialize(),
                           success: function(data){
                     
                             if(data == '0')
                             {
                                     $('#fail').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
                             }
                             else if(data == '1')
                             {
                                 /*
                                  jq.magnificPopup.open({
                                    items: {
                                      src: '#notverifyemail'
                                    },
                                    type: 'inline'
                                  }); */

                              window.location.href='<?php echo Yii::$app->urlManager->createUrl(['site/signup2']); ?>';

                             }
                             else if(data == '2')
                             { /*
                                  jq.magnificPopup.open({
                                          items: {
                                            src: '#notverifyemail'
                                          },
                                          type: 'inline'
                                        });
    */

                              window.location.href='<?php echo Yii::$app->urlManager->createUrl(['site/signup2']); ?>';

                             }
                             else if(data == '3')
                             {
                                 jq.magnificPopup.open({
                                          items: {
                                            src: '#notverifyemail'
                                          },
                                          type: 'inline'
                                        }); 
                             // window.location.href='<?php echo Yii::$app->urlManager->createUrl(['site/signup3']); ?>';

                             }
                             else if(data == '4')
                             {
                                  jq.magnificPopup.open({
                                          items: {
                                            src: '#notverifyemail'
                                          },
                                          type: 'inline'
                                        });
                              //window.location.href='<?php echo Yii::$app->urlManager->createUrl(['site/signup4']); ?>';

                             }
                             else if(data == '5')
                             {
                                  jq.magnificPopup.open({
                                          items: {
                                            src: '#notverifyemail'
                                          },
                                          type: 'inline'
                                        });

                              //window.location.href='<?php echo Yii::$app->urlManager->createUrl(['site/signup5']); ?>';

                             }
                             else if(data == '6')
                             {
                                    
                                      $('.signup-notice .error-note').html('Please Login as you have already registered it.');
                                      $('.signup-notice .error-note').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
                             }
                            else{

                            }
                           }
                       }); 
                    }
		}                
		function login(){
                    var lemail = $("#lemail").val();
                    var lpassword = $("#lpassword").val();
                    var pattern = /^([a-zA-Z0-9_.-])+@([a-zA-Z0-9_.-])+\.([a-zA-Z])+([a-zA-Z])+/;
                    if(lemail === '' && lpassword === '')
                    {
                        $('.login-notice .error-note').html('Please Enter Email address and Password');
                        $('.login-notice .error-note').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
                        document.getElementById('lemail').focus();
                    }
                    else if(lemail === '')
                    {
                        $('.login-notice .error-note').html('Please Enter Email address');
                        $('.login-notice .error-note').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
                        document.getElementById('lemail').focus();
                    }
                    else if(!pattern.test(lemail))
                    {
                        $('.login-notice .error-note').html('Please Enter Valid Email address');
                        $('.login-notice .error-note').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
                        document.getElementById("lemail").focus();
                    }
                    else if(lemail !== '' && lpassword === '')
                    {
                        $('.login-notice .error-note').html('Please Enter Password');
                        $('.login-notice .error-note').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
                        document.getElementById('lpassword').focus();
                    }
                    else
                    {
                        $.ajax({
                        type: 'POST',
                        url: '<?php echo Yii::$app->urlManager->createUrl(['site/check-login']); ?>',
                        data:'lemail='+lemail+"&lpassword="+lpassword,
                        success: function(data){
                                if(data)
                                {
                                    var result = $.parseJSON(data);
                                    if(result['value'] == '2')
                                    {
                                       $('.login-notice .error-note').html('Please Login with Registered Email Id');
                                       $('.login-notice .error-note').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
                                    }
                                    else if(result['value'] == '3')
                                    {
                                       $('.login-notice .error-note').html('Please Enter Correct password');
                                       $('.login-notice .error-note').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
                                    }
                                    else if(result['value'] == '4')
                                    {
                                        document.getElementById("frm-login").submit();
                                    }
                                    else if(result['value'] == '5')
                                    {
                                        jq.magnificPopup.open({
                                          items: {
                                            src: '#notverifyemail'
                                          },
                                          type: 'inline'
                                        });
                                    }
                                    else
                                    {
                                        $('.login-notice .error-note').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
                                    }
                                }
                                else
                                {
                                    $('.login-notice .error-note').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
                                }
                            }
                        });
                    }
		}
	var jq = $.noConflict();
	
	jq(document).ready(function(){
		
		setTimeout(function(){
			
			jq("#loader-wrapper").fadeOut(400);
			
		},5000);
	});
    jq(document).ready(function(){			
	
		jq('.popup-modal').magnificPopup({
			type: 'inline',
			preloader: false,
			focus: '#username',
			modal: true
		});	
		jq(document).on('click', '.popup-modal-dismiss', function (e) {
				e.preventDefault();
				jq.magnificPopup.close();
			});		
	});
    function forgotcontinue()
    {
        var forgotemail = $("#forgotemail").val();
        var pattern = /^([a-zA-Z0-9_.-])+@([a-zA-Z0-9_.-])+\.([a-zA-Z])+([a-zA-Z])+/;
        if(forgotemail === '')
        {
            $('.fp-error').html('Please Enter Email address');
            $('.fp-error').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
        }
        else if(!pattern.test(forgotemail))
        {
            $('.fp-error').html('Please Enter Valid Email address');
            $('.fp-error').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
        }
        else
        {
            $.ajax({
                type: 'POST',
                url: '<?php echo Yii::$app->urlManager->createUrl(['site/forgotpassword']); ?>',
                data: "forgotemail=" + forgotemail,
                success: function(data){
                     if(data)
                     {
                         $(".fp-email-holder").hide();
                         $(".fp-email-details").show();
                         var domain = forgotemail.substring(forgotemail.lastIndexOf("@") +1);
                         if(domain === 'gmail.com'){domain = 'google.com';}
                         $("#displayresetlink").html('<a href="http://mail.'+domain+'/"><button class="btn btn-primary btn-sm btn-full chk-email-mt" type="button">Check Email</button></a>');
                         $('.fp-success').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
                     }
                     else
                     {
                         $('.fp-error').html('Please Enter with Registered Email Id');
                         $('.fp-error').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
                     }
                 }
            });
        }
    }
    
    
    function account_verify()
    {
     
    var email = '<?= $session->get('email_id')?>';
 
  
    if(email != '')
    {
        $.ajax({
            type: 'POST',
            url: '<?php echo Yii::$app->urlManager->createUrl(['site/verify']); ?>',
            data: "email=" + email,
            success: function(data){
               
                 if(data)
                 {
 
                     $('.fp-success').css('display','inline-block').html("Mail Sent Successfully").fadeIn(3000).delay(3000).fadeOut(3000);
                 }
                 else
                 {
                     $('.fp-error').css('display','inline-block').html("Oops..!! Somthing Went Wrong").fadeIn(3000).delay(3000).fadeOut(3000);
                 }
             }
        });
        }
    }

   	jq('#lemail').bind('input propertychange', function() {
		validate_lemail();
	});

    function validate_lemail(){
		var email = $("#lemail").val();
		var pattern = /^([a-zA-Z0-9_.-])+@([a-zA-Z0-9_.-])+\.([a-zA-Z])+([a-zA-Z])+/;
		if(email == "")
		{
                    $("#leml-success").hide();
                    $("#leml-fail").hide();
                    document.getElementById('lemail').focus();
                    return false;
		}

		if(!pattern.test(email))
		{
                    $("#leml-success").hide();
                    $("#leml-fail").show();
                    document.getElementById("lemail").focus();
                    return false;
		}
		else{
			$.ajax({
                        type: 'POST',
                        url: '<?php echo Yii::$app->urlManager->createUrl(['site/check-email']); ?>',
                        data:'lemail='+email,
                        success: function(data){
                                        if(data)
                                        {
                                           $("#leml-success").show();
                                           $("#leml-fail").hide();
                                        }
                                        else
                                        {
                                          
                                            $("#leml-success").show();
                                            $("#leml-fail").hide();
                                        }
                                }
                        });

		}   
	}
        function validate_lpassword(){
		var lpassword = $("#lpassword").val();
                if(lpassword == "")
		{
                    $("#lpwd-success").hide();
                    $("#lpwd-fail").hide();
                    document.getElementById('lpassword').focus();
                    return false;
		}
                if(lpassword.length < 6)
                {
                    $("#lpwd-success").hide();
                    $("#lpwd-fail").show();
                    document.getElementById('lpassword').focus();
                    return false;
                }
                else{
			$("#lpwd-fail").hide();
			$("#lpwd-success").show();
			 return true;
		}
		
	}
	function validate_fname(){
		var fname = $("#fname").val();

		var reg_nm = /^[a-zA-Z\s]+$/;
		var spc = /^\s+$/;	
		if(fname == "")
		{
                    $('.signup-notice .error-note').html('Please Enter First Name.');
                    $("#fnm-success").hide();
			$("#fnm-fail").hide();
			document.getElementById('fname').focus();
			return false;
		}
                
                if(fname.length < 2)
		{
                    $('.signup-notice .error-note').html('Please Enter minimum 2 characters in First Name.');
			$("#fnm-success").hide();
			$("#fnm-fail").show();
			document.getElementById('fname').focus();
			return false;
		}
	
		if(spc.test(fname))
		{
			$("#fnm-success").hide();
			$("#fnm-fail").show();
			document.getElementById('fname').focus();
			return false;
		}
	
		if(!reg_nm.test(fname))
		{
                    $('.signup-notice .error-note').html('Please Enter Characters only in First Name.');
						$("#fnm-success").hide();
			$("#fnm-fail").show();
			document.getElementById('fname').focus();
			return false;
		}
		else{
			$("#fnm-fail").hide();
			$("#fnm-success").show();
			 return true;
		}
	}          
	function validate_lname(){    
            var lname = $("#lname").val();
            var reg_nm = /^[a-zA-Z\s]+$/;
            var spc = /^\s+$/;	
            if(lname =="")
            {
                $('.signup-notice .error-note').html('Please Enter Last Name.');
                $("#lnm-success").hide();
                    $("#lnm-fail").hide();
              document.getElementById('lname').focus();
              return false;
            }

            if(lname.length < 2)
            {
                $('.signup-notice .error-note').html('Please Enter minimum 2 characters in Last Name.');
                    $("#lnm-success").hide();
                    $("#lnm-fail").show();
                    document.getElementById('lname').focus();
                    return false;
            }
                
            if(spc.test(lname))
            {
              $("#lnm-success").hide();
              $("#lnm-fail").show();
              document.getElementById('lname').focus();
              return false;
            }

            if(!reg_nm.test(lname))
            {
                $('.signup-notice .error-note').html('Please Enter characters only in Last Name.');
                                      $("#lnm-success").hide();
              $("#lnm-fail").show();
              document.getElementById('lname').focus();
              return false;
            }
            else{
              $("#lnm-fail").hide();
              $("#lnm-success").show();
              return true;
            }
		   
	}
	function validate_email(){
		var email = $("#email").val();
		var pattern = /^([a-zA-Z0-9_.-])+@([a-zA-Z0-9_.-])+\.([a-zA-Z])+([a-zA-Z])+/;
		if(email =="")
		{
                    $('.signup-notice .error-note').html('Please Enter Email.');
                    $("#eml-fail").hide();
			$("#eml-success").hide();
			document.getElementById('email').focus();
			return false;
		}

		if(!pattern.test(email))
		{
                    $('.signup-notice .error-note').html('Please Enter valid Email.');
			$("#eml-success").hide();
			$("#eml-fail").show();
			document.getElementById("email").focus();
			return false;
		}
		else{
			$("#eml-fail").hide();
			$("#eml-success").show();
			 return true;
		}   
	}
        function validate_spassword(){
		var spassword = $("#signup_password").val();
		var conpassword = $("#signup_con_password").val();
		if(conpassword !=""){

			if(spassword != conpassword)
					{
									
						$('.signup-notice .error-note').html('Mismatch password.');
						$("#conpwd-success").hide();
						$("#conpwd-fail").show();
						return false;
					}
					else
					{
									
						$("#conpwd-fail").hide();
						$("#conpwd-success").show();
						return true;
			}
		}
		else{
                if(spassword =="")
                {
                    $('.signup-notice .error-note').html('Please Enter Password.');
                    $("#spwd-fail").hide();
                    $("#spwd-success").hide();
                  document.getElementById('signup_password').focus();
                  return false;
                }
                if(spassword.length < 6)
                {
                    $('.signup-notice .error-note').html('Please Enter Password of 6 length.');
                    $("#spwd-success").hide();
                    $("#spwd-fail").show();
                    return false;
                }
                else{
                    $("#spwd-fail").hide();
                    $("#spwd-success").show();
                    return true;
		}
		}
		
	}
        function validate_conpassword(){
		var password = $("#signup_password").val();
                var conpassword = $("#signup_con_password").val();
                if(conpassword =="")
                {
                    $('.signup-notice .error-note').html('Please Enter Confirm Password.');
                    $("#conpwd-fail").hide();
                    $("#conpwd-success").hide();
                  document.getElementById('signup_con_password').focus();
                  return false;
                }
                if(conpassword.length < 6)
                {
                    $('.signup-notice .error-note').html('Please Enter Confirm Password of 6 length.');
                    $("#conpwd-success").hide();
                    $("#conpwd-fail").show();
                    return false;
                }
                else if(password != conpassword)
                {
                    $('.signup-notice .error-note').html('Mismatch password.');
                    $("#conpwd-success").hide();
                    $("#conpwd-fail").show();
                    return false;
                }
                else
                {
                    $("#conpwd-fail").hide();
                    $("#conpwd-success").show();
                    return true;
		}
		
	}
        function gender_status(gender){
            $('#genderstatus').val(gender);
        }
        function changedate()
        {
            var adate = $('#popupDatepicker').val();
            $('#birthdate').val(adate);
        }
        function firstToUpperCase(string) {
            return string.charAt(0).toUpperCase() + string.slice(1).toLowerCase();
        }
</script>
    
<script>

	jq(document).ready(function(){
		
		jq('body').removeClass("loaded");
		setTimeout(function(){jq('body').addClass("loaded");},1000);
		
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
		
		jq('#inlineDatepicker').datepick({onSelect: showDate});
	});
	
	function showDate(date) {
           
		alert('The date chosen is ' + date);
	}
        
        jq(document).ready(function(){
            if (navigator.geolocation)
            {
                navigator.geolocation.getCurrentPosition(showPosition);
            } 
            else
            {
                $("#lat").val('23.0295155');
                $("#lat").val('72.5085902');
            }
        });
        function showPosition(position) {
            $("#lat").val(position.coords.latitude);
            $("#long").val(position.coords.longitude);
        }
</script>