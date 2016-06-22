<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use frontend\assets\AppAsset;

use yii\widgets\ActiveForm;
use yii\mongodb\ActiveRecord;

use frontend\models\LoginForm;
use yii\validators\Validator;

$session = Yii::$app->session;
$error = $session->get('loginerror');
if(isset($error) && !empty($error))
{
    echo $error;
    $session->set('loginerror','');
}

if(isset($_GET['email']) && !empty($_GET['email']))
{
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
                   
            $url = Yii::$app->urlManager->createUrl(['site/index']);
            Yii::$app->getResponse()->redirect($url); 
            } 
            catch (ErrorException $e) 
            {
                    echo 'Caught exception: ',  $e->getMessage(), "\n";
            }
     
 
}

//use vendor\bower\travel\assets\AppAsset;
//AppAsset::register($this);
//AppAsset::register($this);
$asset = frontend\assets\AppAsset::register($this);

$baseUrl = AppAsset::register($this)->baseUrl;
?>


<?php $this->beginBody() ?>

<!-- header section -->
<div class="np-header01">
  <div class="container clearfix" >
    <div class="row">
      <div class="col-sm-4">
          <div class="logo"><a href="#" class="pageLoad" ><img src="<?= $baseUrl?>/images/logo.png" width="152" height="43"></a></div>
      </div>
      <div class="col-sm-8 clearfix" id="login">
         
         <!-- <div class="social-section clearfix"> 
		  <a href="#"><img src="<?= $baseUrl?>/images/facebook-icon.png" ></a> <a href="#"><img src="<?= $baseUrl?>/images/google-icon.png"></a> <a href="#"><img src="<?= $baseUrl?>/images/linkedin-icon.png"></a> 
		  </div> -->
        <?php $form = ActiveForm::begin(['action' => ['site/login'],'options' => ['method' => 'post','class' => 'top-form']]) ?>
        <div class="cus-login-section clearfix">
          <div class="cus-login-part1 clearfix">
            <div class="clearfix">
              <!--<input name="1" placeholder="Email Address" type="text" id="1" >-->
              <input type="hidden" name="login" value="1" />
               <?= $form->field($model,'email')->textInput(array('placeholder'=>'Email address'))->label(false)?>
            </div>
            <div class="checkbox has-js">
              <fieldset class="checkboxes">
                <label class="label_check" for="checkbox-01">
                  <input name="remember" id="checkbox-01" type="checkbox" checked="checked" />Remember Me</label>
             </fieldset>
            </div>
          </div>
          <div class="cus-login-part2 clearfix">
            <div class="clearfix">
               <?= $form->field($model,'password')->passwordInput(array('placeholder'=>'Password'))->label(false)?>
              <!--<input name="1" placeholder="Password" type="text" id="2" >-->
            </div>

            <!--<a href="#" id="m" class="fp-link"><span class="np-right-caret"></span>Forgot Password?</a> -->
            <a href="<?php echo Yii::$app->urlManager->createUrl(['site/forgotpassword'])?>"><span class="np-right-caret"></span>Forgot Password?</a>
          </div>
          <div class="login-link">
<!--<a class="np-mobile-fix loginbtn" href="#">Login</a>-->
<!--<input type="submit" class="np-mobile-fix loginbtn" name="Login" value="Login"/> -->
<div class="loginlink"><input type="submit" name="Login" value="Login"/></div>
</div>
		
		  <!--<div class="fp-box">
			<div class="fp-details">				
				<h6>Reset Password <a href="#" class="fp-close"><i class="glyphicon glyphicon-remove-circle " style="color:#0071BD;"></i></a></h6>				
				<div class="base">
					<div class="ex-m"></div>
					<div id="forgot_success" class="form-successmsg">Mail Sent Successfully</div>
					<div id="forgot_fail" class="form-failuremsg">Oops..!! You Have Entered Wrong Email Id </div>
					<input style="" type="email" name="fmail" placeholder="Enter Your Email Address" id="fmail">
					<button type="button" name="send" class="themebtn" onclick="forgot()">Send</button>
				</div>
			</div>
		  </div>-->
		
		</div>
      
       <?php ActiveForm::end() ?>
      </div>
        
       
        
    </div>
  </div>
</div>

    

<!--<script>
       $(document).ready(function(){
           $("#m").click(function(){
               $("#login").hide();
               $("#forgot").show();
               
           });
       });
</script>-->
<!--<script>
    function forgot(){
        var fmail = $("#fmail").val();
        if(fmail != ''){ 
               $.ajax({
                   type: 'POST',
                   url: '<?php echo Yii::$app->urlManager->createUrl(['site/forgot']); ?>',
                   data: "fmail=" + fmail,
                   success: function(data){   
                        if(data == '1')
                        {
                             $('#forgot_success').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
                        }
                        else if(data == '0')
                        {
                           $('#forgot_fail').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
                        }
                        else
                        {
                            $('#forgot_fail').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
                        }
                    }
                   
               }); 
               }else{
                    $('#forgot_fail').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
               }
        }
</script>-->
<!-- end of header section  -->

<!-- slider section -->
<div id="slideshow">

	<div id="loader-wrapper">
		<div class="loader-logo"></div>
		<div id="loader"></div>
		
		<div class="loader-section section-left"></div>
		<div class="loader-section section-right"></div>

	</div>
	
	   <div class="clearfix">
		  <div class="flexslider showOnMouseover">
			<ul class="slides">
			  <li style="height:450px; background:url(<?= $baseUrl?>/images/1.jpg) center top no-repeat; background-size:cover;">&nbsp;
				<div class="flex-caption">
				  <div class="text-a">Travel Reviews, Blogs, Photos, Events and more</div>
				  <div class="text-b">Egypt</div>
				</div>
	</li>
			  <li style="height:450px; background:url(<?= $baseUrl?>/images/2.jpg) center top no-repeat; background-size:cover;">
						  <div class="flex-caption">
				  <div class="text-a">Social networking community for travelers</div>
				  <div class="text-b">Petra, Jordan</div>
				</div>

			  </li>
			  <li style="height:450px; background:url(<?= $baseUrl?>/images/3.jpg) center top no-repeat; background-size:cover;">            
			   <div class="flex-caption">
				  <div class="text-a">Check out destination, tour sites that interest you.</div>
				  <div class="text-b">Paris, France</div>
				</div>

			  </li>
			  <li style="height:450px; background:url(<?= $baseUrl?>/images/4.jpg) center top no-repeat; background-size:cover;">
			  
				<div class="flex-caption">
				  <div class="text-a">The best online place to meet travellers and friends</div>
				  <div class="text-b">Cathedral Cove Beach, New Zealand</div>
				</div>

				
			  </li>
			  <li style="height:450px; background:url(<?= $baseUrl?>/images/5.jpg) center top no-repeat; background-size:cover;">
				<div class="flex-caption">
				  <div class="text-a">Check out destination, tour sites and events that interest you.</div>
				  <div class="text-b">Beijing, China</div>
				</div>
				
			  </li>
			  <li style="height:450px; background:url(<?= $baseUrl?>/images/6.jpg) center top no-repeat; background-size:cover;">
				<div class="flex-caption">
				  <div class="text-a">Discover the beauty of places around the world</div>
				  <div class="text-b">Buck Island, USA</div>
				</div>
				
			  </li>
	   

			</ul>
		  </div>
		</div>

</div>
    <!-- Start Flex Slider code -->

    <script type="text/javascript">




(function($){


    $(window).load(function(){


        $(".flexslider").flexslider({


            animation: "fade",				//String: Select your animation type, "fade" or "slide"
            slideDirection: "horizontal",	//String: Select the sliding direction, "horizontal" or "vertical"
            slideshow: true,				//Boolean: Animate slider automatically
            slideshowSpeed: 7000,			//Integer: Set the speed of the slideshow cycling, in milliseconds
            animationDuration:25000,			//Integer: Set the speed of animations, in milliseconds
            directionNav: true,				//Boolean: Create navigation for previous/next navigation? (true/false)
            controlNav: true,				//Boolean: Create navigation for paging control of each clide? Note: Leave true for manualControls usage
            keyboardNav: true,				//Boolean: Allow slider navigating via keyboard left/right keys
            mousewheel: false,				//{UPDATED} Boolean: Requires jquery.mousewheel.js (https://github.com/brandonaaron/jquery-mousewheel) - Allows slider navigating via mousewheel
			smoothHeight: true,
            randomize: false,				//Boolean: Randomize slide order
            slideToStart: 0,				//Integer: The slide that the slider should start on. Array notation (0 = first slide)
            animationLoop: true,			//Boolean: Should the animation loop? If false, directionNav will received "disable" classes at either end
            pauseOnAction: true,			//Boolean: Pause the slideshow when interacting with control elements, highly recommended.
            pauseOnHover: false,			//Boolean: Pause the slideshow when hovering over slider, then resume when no longer hovering
			start: slideComplete,
			after: slideComplete



        }).addClass(""); // add shadow to the slider - choose between: lifted, curled, perspective, raised, curved, curved curved-vt-1, curved curved-vt-2, , curved curved-hz-1, curved curved-hz-2, lifted rotated; Demo here: http://nicolasgallagher.com/css-drop-shadows-without-images/demo/

 		function slideComplete(args) {
 			var caption = $(args.container).find('.flex-caption').attr('style', ''),
 				thisCaption = $('.flexslider .slides > li.flex-active-slide').find('.flex-caption');
 			thisCaption.animate({top:150, opacity:1}, 2500, 'easeOutQuint');
  		}
     })
    
 })(jQuery);



</script>
    
    <!-- End Flex Slider code --> 
    
    
 
<!-- Register Form --> 

<div class="container np-form-fix01">
	
	<div class="card-holder">
		<div id="card-2" class="card">
		  <div class="front">
			 <div class="btn-boxholder center-align">				
				<a href="<?php echo Yii::$app->request->baseUrl.'?r=site/auth&authclient=facebook' ?>" class="signup-fb"><img src="<?= $baseUrl?>/images/fb-icon.png">Connect With Facebook</a>
                                <a href="#" class="signup-google"><img src="<?= $baseUrl?>/images/gplus-icon.png">Connect With Google</a>
				<span class="or-holder"><span class="line"></span>OR<span class="line"></span></span>	
				<a href="#" class="signup-tb">Sign Up</a>
			</div>
	 
		  </div>
		  <div class="back">
			<div class="np-tb-formbox ppbox">
				<div class="close-btn">x</div>
				<div class="title-01"><span>Signup now, </span><span>its free</span></div>
					<div class="home-reg-form">

					
    <div id="suceess" style="display: none;" class="form-successmsg">Thanks for joining TravBud</div>
    <div id="fail" class="form-failuremsg" style="display: none;">Oops..!! Somthing Went Wrong </div>
    <div id="hold" class="form-failuremsg" style="display: none;">This Email Id already Exists </div>
            <?php $form = ActiveForm::begin(
                            [
                                'id' => 'frm',
                                'options'=>[
                                    'onsubmit'=>'return false;',
                                    'enableAjaxValidation' => true,
                                    ],
                            ]
        ); ?>

            <div class="form-group">
               <?= $form->field($model,'fname')->textInput(array('class'=>'form-control','placeholder'=>'First Name'))->label(false)?>
           <!--    <input type="text" class="form-control" placeholder="First Name"> -->
            </div>
            <div class="form-group">
               <?= $form->field($model,'lname')->textInput(array('class'=>'form-control','placeholder'=>'Last Name'))->label(false)?>
            <!--   <input type="text" class="form-control" placeholder="Last Name"> -->
            </div>
            <div class="form-group">
               <?= $form->field($model,'email')->textInput(array('class'=>'form-control','placeholder'=>'Email'))->label(false)?>
            <!--   <input type="text" class="form-control" placeholder="Email Address"> -->
            </div>
            <div class="form-group">
               <?= $form->field($model,'password')->passwordInput(array('class'=>'form-control','placeholder'=>'Password'))->label(false)?>
               
             <!--  <input type="text" class="form-control" placeholder="Password"> -->
            </div>
            <div class="form-group">
               <?= $form->field($model,'con_password')->passwordInput(array('class'=>'form-control','placeholder'=>'Confirm Password'))->label(false)?>
             <!--  <input type="text" class="form-control" placeholder="Confirm Password"> -->
            </div>
            <div class='input-group date' id='datetimepicker2'>
        <!-- <input type='text' class="form-control" placeholder="Birthdate" /> -->
         <?= $form->field($model,'birth_date')->textInput(array('class'=>'form-control','placeholder'=>'Birth Date','id'=>'datetimepicker2','onkeydown'=>'return false;'))->label(false)?>
                        <span class="input-group-addon"><i class="glyphicon glyphicon-calendar " style="color:#0071BD;"></i></span>
        </div>
            <div class="form-group clearfix" style="margin-top:10px;">
              <div class="radio pull-left" style="margin-right:10px;">
             
                <label style="margin-right:15px; color:#fff;">
                    <?= $form->field($model, 'gender')->radioList(array('Male'=>'Male','Female'=>'Female'))->label(false); ?>
                 
              </div>
            </div>
            <div class="form-group" style="margin-bottom:2px;">
                <?php
                
                    $date = time();
                                 
                ?>
    <?= $form->field($model,'created_date')->hiddenInput(array('class'=>'form-control','value'=>$date))->label(false)?>
    <?= $form->field($model,'updated_date')->hiddenInput(array('class'=>'form-control','value'=>$date))->label(false)?>
    <?= $form->field($model,'created_at')->hiddenInput(array('class'=>'form-control','value'=>$date))->label(false)?>
    <?= $form->field($model,'updated_at')->hiddenInput(array('class'=>'form-control','value'=>$date))->label(false)?>  
    <?= $form->field($model,'status')->hiddenInput(array('class'=>'form-control','value'=>'0'))->label(false)?>
                
              <!--<input type="submit" class="home-submit" value="SIGNUP NOW">-->
                <input type="submit" class="home-submit" value="SIGNUP NOW" onclick="signup()">
            </div>
        <?php ActiveForm::end() ?>
            
					</div>
				  </div>
			</div> 
		</div>
		
	</div>

<!-- 3 Box --> 


<div class="clearfix np-mobile-fix01">
  <div class="row">
    <div class="col-sm-4 clearfix">
      <div class="tb-iconbox01"><img src="<?= $baseUrl?>/images/ico-1.png"></div>
      <div class="tb-title01"> <span>Meet traveling companions</span> <span>Post your trip plan and get social.</span> </div>
    </div>
    <div class="col-sm-4 clearfix">
      <div class="tb-iconbox01"><img src="<?= $baseUrl?>/images/ico-2.png"></div>
      <div class="tb-title01"> <span>Connect with locals</span> <span>Find a homestay or share a coffee.</span> </div>
    </div>
	<div class="col-sm-4 clearfix">
      <div class="tb-iconbox01"><img src="<?= $baseUrl?>/images/ico-3.png"></div>
      <div class="tb-title01"> <span>Near by Events</span> <span>Find nearest city event</span> </div>
    </div>
  </div>
</div>

 <script>
    var d = document;
    var safari = (navigator.userAgent.toLowerCase().indexOf('safari') != -1) ? true : false;
    var gebtn = function(parEl,child) { return parEl.getElementsByTagName(child); };
    onload = function() {
        
        var body = gebtn(d,'body')[0];
		console.log(body.className);
        body.className = body.className && body.className != '' ? body.className + ' has-js' : 'has-js';
        
        if (!d.getElementById || !d.createTextNode) return;
        var ls = gebtn(d,'label');
        for (var i = 0; i < ls.length; i++) {
            var l = ls[i];
            if (l.className.indexOf('label_') == -1) continue;
            var inp = gebtn(l,'input')[0];
            if (l.className == 'label_check') {
                l.className = (safari && inp.checked == true || inp.checked) ? 'label_check c_on' : 'label_check c_off';
                l.onclick = check_it;
            };
            if (l.className == 'label_radio') {
                l.className = (safari && inp.checked == true || inp.checked) ? 'label_radio r_on' : 'label_radio r_off';
                l.onclick = turn_radio;
            };
        };
    };
    var check_it = function() {
        var inp = gebtn(this,'input')[0];
        if (this.className == 'label_check c_off' || (!safari && inp.checked)) {
            this.className = 'label_check c_on';
            if (safari) inp.click();
        } else {
            this.className = 'label_check c_off';
            if (safari) inp.click();
        };
    };
    var turn_radio = function() {
        var inp = gebtn(this,'input')[0];
        if (this.className == 'label_radio r_off' || inp.checked) {
            var ls = gebtn(this.parentNode,'label');
            for (var i = 0; i < ls.length; i++) {
                var l = ls[i];
                if (l.className.indexOf('label_radio') == -1)  continue;
                l.className = 'label_radio r_off';
            };
            this.className = 'label_radio r_on';
            if (safari) inp.click();
        } else {
            this.className = 'label_radio r_off';
            if (safari) inp.click();
        };
    };
</script>
<script>
    $( ".pageLoad" ).click(function() {
         location.reload();
});
</script>
<script type="text/javascript">
(function () {
  //  $("#test").hide();
  $('#datetimepicker2').datetimepicker({
        format: "DD-MM-YYYY",
        maxDate: new Date
    }); })(jQuery); 
</script>

<!-- Popup itself -->
<div id="test-popup" class="sblack-popup mfp-with-anim mfp-hide">

	
</div>
	
</div>

<!-- footer section -->
<div class="np-fotter clearfix">
  <div class="container np-fotter-link"><a href="#">About</a> <span>|</span> <a href="#">Privacy</a> <span>|</span> <a href="#">Invite</a> <span>|</span> <a href="#">Terms</a> <span>|</span> <a href="#">Contact Us</a> <span>|</span> <a href="#">Features</a> <span>|</span> <a href="#">Mobile</a> <span>|</span> <a href="#">Developers</a></div>
</div>
<!-- end of footer section -->

<script src="<?= $baseUrl?>/js/flip.js" type="text/javascript"></script>
<script type="text/javascript">

	var jq = $.noConflict();
	function setVisibile(ff){
		if(ff){
			jq("#card-2 .back").css("visibility","hidden");
			jq("#card-2 .front").css("visibility","visible");
		}
		else{
			jq("#card-2 .back").css("visibility","visible");
			jq("#card-2 .front").css("visibility","hidden");
		}
	}
	jq(document).ready(function(){
		
		setTimeout(function(){
			
			jq("#loader-wrapper").fadeOut(400);
			
		},5000);
		
		var frontface=true;
		setTimeout(function(){ jq(".back").css("display","block"); }, 1000);
		setVisibile(frontface);		
		  
		  jq("#card-2").flip({
			axis: "y", // y or x
			reverse: true,
			 trigger: 'manual'
		  });
		  jq(".signup-tb").click(function(){
				
			frontface=false;			
			setVisibile(frontface);
			jq("#card-2").flip(true);		
			
		  });
		  
			jq(".close-btn").click(function(){
				
				frontface=true;				
				setVisibile(frontface);
				jq("#card-2").flip(false);		
		  });
		
		  $(".fp-link").click(function(){
			  		  
			  var disp = $(".fp-details").css("display");
			  if(disp=="none"){				 
				  $(".fp-details").fadeIn(300);
				  $(".base").delay(200).slideDown(500);
			  }
			  else{
				 $(".fp-details").delay(700).fadeOut(500);
				 $(".base").slideUp(500);			  				 
			  }
		  });
		  $(".fp-close").click(function(){
			  		  
			  var disp = $(".fp-details").css("display");
			  if(disp=="none"){				 
				  $(".fp-details").fadeIn(500);
				  $(".base").delay(200).slideDown(500);
			  }
			  else{				  
				  $(".fp-details").delay(700).fadeOut(300);
				  $(".base").slideUp(500);			  
			  }
			 
		  });
		 
	
	});
        function signup(){
               $.ajax({
                   type: 'POST',
                   url: '<?php echo Yii::$app->urlManager->createUrl(['site/signup']); ?>',
                   data: $("#frm").serialize(),
                   success: function(data){
                 //alert(data); return false;
                     if(data == '0')
                     {
                         $('#fail').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
                     }
                     else if(data == '1')
                     {
                      
                      window.location.href='<?php echo Yii::$app->urlManager->createUrl(['site/signup2']); ?>';
                        
                     }
                     else if(data == '2')
                     {
                     
                      window.location.href='<?php echo Yii::$app->urlManager->createUrl(['site/signup2']); ?>';
                        
                     }
                     else if(data == '3')
                     {
                     
                      window.location.href='<?php echo Yii::$app->urlManager->createUrl(['site/signup3']); ?>';
                        
                     }
                     else if(data == '4')
                     {
                     
                      window.location.href='<?php echo Yii::$app->urlManager->createUrl(['site/signup4']); ?>';
                        
                     }
                     else if(data == '5')
                     {
                     
                      window.location.href='<?php echo Yii::$app->urlManager->createUrl(['site/signup5']); ?>';
                        
                     }
                     else if(data == '6')
                     {
                          $("#hold").show().fadeIn(300).fadeOut(5000);
                     }
                    else{

                    }
                   }
                   
               }); 
               }

	

</script>