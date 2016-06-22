<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use frontend\assets\AppAsset;

use yii\widgets\ActiveForm;
use yii\mongodb\ActiveRecord;

use frontend\models\LoginForm;
use yii\validators\Validator;

$asset = frontend\assets\AppAsset::register($this);

$baseUrl = AppAsset::register($this)->baseUrl;
?>
<div class="home-wrapper reset-pass">
		<div class="bgImage">
			<div class="bgImage--blur"></div>
			<img class="pano desk-homebg" src="<?= $baseUrl?>/images/home-bg.jpg">
			<img class="pano mbl-homebg" src="<?= $baseUrl?>/images/home-bg.png">
		</div>	
        <header>
            <div class="home-container">
                    <div class="hlogo-holder">
                    <a href="<?php echo Yii::$app->urlManager->createUrl(['site/index']); ?>" class="home-logo"><img src="<?= $baseUrl?>/images/home-logo.png"/></a>            
                   
                    </div>
               
            </div>
        </header>
		
		<div class="home-content">
			<?php
			 if(isset($_GET['enc']) && !empty($_GET['enc']))
            {
                $enc = $_GET['enc'];
                $trav_id = $enc;
                $travid =  base64_decode(strrev($trav_id));
                $postresult = LoginForm::find()->where(['_id' => $travid])->one();
                if($postresult){
            ?>
			<div class="container">        	               					
				<div class="fp-wrapper">				
					<div class="login-part resetpass-popup">					
						<div class="row">
							
							<div class="home-title reset-password-hd">
								<img src="<?= $baseUrl?>/images/logo.png"/>
							</div>
							<input type="hidden" name="travid" id="travid" value="<?= $travid?>">
							<h4>Choose a new password</h4>
							<div class="fp-notice">
								<div class="fp-error">
									<i class="fa fa-check-circle"></i> Mismatch Password.
								</div>
							</div>
							<div class="fp-form">
								<div class="frow">									
									<input type="password" required="true" id="password" class="form-control" name="password" placeholder="New password" onkeyup="validate_spassword()">
																	<div id='spwd-success' class="frm-validicon" style='display: none'><img src="<?= $baseUrl?>/images/frm-check.png"/></div>
														 <div id='spwd-fail' class="frm-validicon" style='display: none'><img src="<?= $baseUrl?>/images/frm-cross.png"/></div>
									<!--<input type="email">-->
								</div>
								<div class="frow">									
									<!--<input type="password">-->
									<input type="password" required="true" id="passwordcon" class="form-control" name="passwordcon" placeholder="Re-enter new password" onkeyup="validate_conpassword()">
																	<div id='conpwd-success' class="frm-validicon" style='display: none'><img src="<?= $baseUrl?>/images/frm-check.png"/></div>
														 <div id='conpwd-fail' class="frm-validicon" style='display: none'><img src="<?= $baseUrl?>/images/frm-cross.png"/></div>
								</div>                            
<!--								<div class="frow">
									<div class="h-checkbox">
                                                                            <input type="checkbox" id="test1" checked="true"/>
										<label for="test1">Sign out of all active sessions</label>
									</div>
								</div>-->
								<div class="frow">
									<a href="javascript:void(0)" class="pull-center homebtn" onclick="resetpassword()">Continue</a>
								 <!-- <input class="pull-right homebtn" type="submit" name="Login" value="Login"/>-->
									<div class="note">
										<p>Passwords are case-sensitive and must be atleast 6 characters long. A good password should contain a mix of capital and lower-case letters,numbers and symbols.</p>
									</div>
								</div>
							</div>						
						</div>
					</div>
				</div>
			</div>
			<?php } else {?>
				  <div class="login-part">
					<div class="container">        	
						<div class="home-title">
							<h4>No user found !!!</h4>
						</div>
					</div>
				</div>
				<?php }}else {?>
				  <div class="login-part">
					<div class="container">        	
						<div class="home-title">
							<h4>Please Paste URL from your email !!!</h4>
						</div>
					</div>
				</div>
			  <?php }?>
		</div>
		
		<?php /*
        <div class="home-content">
            <?php
            if(isset($_GET['enc']) && !empty($_GET['enc']))
            {
                $enc = $_GET['enc'];
                $trav_id = $enc;
                $travid =  base64_decode(strrev($trav_id));
                $postresult = LoginForm::find()->where(['_id' => $travid])->one();
                if($postresult){
            ?>
                <div class="login-part">
                    <div class="container">        	
                        <div class="home-title">
                            <h4>Reset Password</h4>
                        </div>
                        <div class="row">
                            <div class="col-sm-6 right-hcontent">
                                <input type="hidden" name="travid" id="travid" value="<?= $travid?>">
                                <div class="fp-notice">
                                    <div class="fp-error">
                                            <i class="fa fa-check-circle"></i> Something went wrong. Please try again!
                                    </div>
                                </div>
                                <!--<form action="#">-->
                                    <div class="frow">
                                        <label>Password</label>
                                        <input type="password" required="true" id="password" class="form-control" name="password" placeholder="Password">
                                        <!--<input type="email">-->
                                    </div>
                                    <div class="frow">
                                        <label>Confirm Password</label>
                                        <!--<input type="password">-->
                                        <input type="password" required="true" id="passwordcon" class="form-control" name="passwordcon" placeholder="Confirm Password">
                                    </div>                            
                                    <div class="frow">
                                        <a href="javascript:void(0)" class="pull-center homebtn" onclick="resetpassword()">Reset Password</a>
                                     <!-- <input class="pull-right homebtn" type="submit" name="Login" value="Login"/>-->

                                    </div>
                                <!--</form>-->
                            </div>
                        </div>
                    </div>
                </div>
          <?php } else {?>
              <div class="login-part">
                <div class="container">        	
                    <div class="home-title">
                        <h4>No user found !!!</h4>
                    </div>
                </div>
            </div>
            <?php }}else {?>
              <div class="login-part">
                <div class="container">        	
                    <div class="home-title">
                        <h4>Please Paste URL from your email !!!</h4>
                    </div>
                </div>
            </div>
          <?php }?>
        </div>
		*/?>
		
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
<script>
    var visible_part="login";
    
    $(document).ready(function(){
		setTimeout(function(){
			setWinHeight(visible_part);
		},500);
		
		$("body").addClass("home-page");
		$("html").css("height","100%");
					<?php if(isset($_GET['id'])){ ?>
						 flipSectionTo('signup');
					<?php }?>
	});
	
	$(window).resize(function(){			
		setTimeout(function(){setWinHeight(visible_part);},1000);			
		
	});

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
		/*
		alert(content_h);
		alert(header_h);
		alert(footer_h);
		*/
		
		var diff_h = win_h - ( header_h + footer_h + content_h + 100 );
		
		//var diff_h;
		
		if(win_w<700){				
			diff_h = 30;
		}
		else{				
			diff_h = diff_h / 2;
		}
		
		if(win_h > (header_h + footer_h + content_h + 120)){
			//alert(win_h + " > " + (header_h + footer_h + content_h));
			$("body").addClass("absfooter");
		}else{
			//alert(win_h + " <= " + (header_h + footer_h + content_h));
			if($("body").hasClass("absfooter")){$("body").removeClass("absfooter");}
		}
		/*
		if(win_w > 700){
			$("body").addClass("absfooter");
		}
		else{
			if($("body").hasClass("absfooter")){$("body").removeClass("absfooter");}
		}
		*/
		
		$(".home-content").css("padding-top",diff_h);	
		
	}
	
    function resetpassword()
    {
		var password = $("#password").val();
		var passwordcon = $("#passwordcon").val();
		var travid = $("#travid").val();
		//if (password != passwordcon)
                if(!(validate_spassword() && validate_conpassword()))
		{
                    if (password.length < 6)
                    {
                        $('.fp-notice .fp-error').html('Please Enter Password of 6 length.');
                        $('.fp-notice .fp-error').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
                        $("#password").focus();
                        return false;
                    }
                    else if (passwordcon.length < 6)
                    {
                        $('.fp-notice .fp-error').html('Please Enter Confirm Password of 6 length.');
                        $('.fp-notice .fp-error').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
                        $("#passwordcon").focus();
                        return false;
                    }
                    else if(password != passwordcon)
                    {
                        $('.fp-notice .fp-error').html('Mismatch Passwords.');
                        $('.fp-notice .fp-error').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
                        $("#passwordcon").focus();
                        return false;
                    }
                    else
                    {
                        $('.fp-notice .fp-error').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
                        return false;
                    }
		}
		else
		{
			if(password != '' && passwordcon != ''){
				   $.ajax({
					   type: 'POST',
					   url: '<?php echo Yii::$app->urlManager->createUrl(['site/resetpassworddone']); ?>',
					   data: "travid=" + travid +"&password=" + password,
					   success: function(data){
							if(data)
							{
							}
							else
							{
								$('.fp-error').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
							}
						}

				   }); 
			}
		}
	}
        
        function validate_spassword(){
		var spassword = $("#password").val();
                if(spassword =="")
                {
                    $("#spwd-fail").hide();
                    $("#spwd-success").hide();
                  document.getElementById('password').focus();
                  return false;
                }
                if(spassword.length < 6)
                {
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
        function validate_conpassword(){
		var password = $("#password").val();
                var conpassword = $("#passwordcon").val();
                if(conpassword =="")
                {
                    $("#conpwd-fail").hide();
                    $("#conpwd-success").hide();
                  document.getElementById('passwordcon').focus();
                  return false;
                }
                if(conpassword.length < 6)
                {
                    $("#conpwd-success").hide();
                    $("#conpwd-fail").show();
                    return false;
                }
                else if(password != conpassword)
                {
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
</script>