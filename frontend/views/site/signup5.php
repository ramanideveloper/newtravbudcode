<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use frontend\assets\AppAsset;

use yii\widgets\ActiveForm;
use yii\mongodb\ActiveRecord;

use frontend\models\LoginForm;
use  yii\web\Session;
$session = Yii::$app->session;

if($session->get('email_id'))
{
    $email = $session->get('email_id'); 
    $result = LoginForm::find()->where(['email' => $email])->one();

    $username = $result['username'];
	$fname = $result['fname'];
    $lname = $result['lname'];
    $password = $result['password'];
    $con_password = $result['con_password'];
    $birth_date = $result['birth_date'];
    $gender = $result['gender'];
    
    $email_redirect = substr(strrchr($email, "@"), 1);
    if($email_redirect == 'gmail.com') { $email_redirect = 'google.com'; }
 
}
else{
     $url = Yii::$app->urlManager->createUrl(['site/index']);
            Yii::$app->getResponse()->redirect($url);   
}


$asset = frontend\assets\AppAsset::register($this);

$baseUrl = AppAsset::register($this)->baseUrl;
?>


<?php $this->beginBody() ?>
    
<!-- header section -->
<div class="np-header01 sidepad">
  <!--<div class="container clearfix" >-->
    <div class="row">
      <div class="col-sm-4 signup-logo">
          <div class="logo"><a href="<?php echo Yii::$app->urlManager->createUrl(['site/index']); ?>" class="pageLoad" ><img src="<?= $baseUrl?>/images/logo-2.png"></a></div>
      </div>
      <div class="col-sm-8 clearfix tagline">
			
		<!--<h4>Travel Reviews, Blogs, Articles, Photos, Events &amp; More</h4>-->
		<p>Social Networking Community for Travelers!</p>
       
      </div>
        
    </div>
  <!--</div>-->
</div>
<!-- end of header section  -->

 <div class="stepform-holder">
	<!-- Register Form --> 
	
	<div class="container np-form-fix01">
		<div class="stepform no-style">
			<div class="home-userinfo">
			
			<?php
				$dp = $this->context->getimage($result['_id'],'thumb');
			?>
				<img src="<?= $dp?>" class="pull-right m-left"/>
			
			
				<!--<img src="<?= $baseUrl?>/images/Male.jpg" class="pull-right m-left">-->
				<h6><?= $email;?></h6>			
			</div>
		</div>	
		<div class="stepform bpad">
			<div class="step-pannel">
				<div class="form-step">					
						
					<div class="steps">						
						<div class="grp">

							
								<span class="info">Profile</span>

								<span class="step">1</span>			
								<span class="divider"></span>			
							
						</div>
						<div class="grp">
							

								<span class="info">Photo</span>

								<span class="step">2</span>			
								<span class="divider"></span>			
							
						</div>
						<div class="grp">
						

								<span class="info">Info</span>

								<span class="step">3</span>		
								<span class="divider"></span>			
							
						</div>
						<div class="grp current last">
							
							
								<span class="info">Done</span>

								<span class="step">4</span>
							
						</div>
					</div>
				
				</div>
		
			</div>
			<div class="form-steps-holder clearfix">
				<div class="form-step-bg clearfix steparrow-4 steparrow">
					<div class="form-topstuff">
						<div class="form-title">					
							<h5><span class="blk-text"><span class="big">Welcome</span> <?=$fname?> !!</span></h5>
						</div>
					</div>
					<div class="stepform-form">
						<?php $form = ActiveForm::begin(['action' => ['site/signup5'],'options' => ['method' => 'post','id'=>'resend_mail']]) ?>			
						<div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								
								<div class="text-left">
									<div class="info donestuff">
										
										<?php   /*

										if($session->get('step')== '4'){
										?>
											<p>An email has been sent to the email address you provided. Please follow the link in the email to verify your email address.</p>
										<?php 
											 }
										  else if($session->get('step')== '10'){
										  ?>
											<p>Please Verify Your Email address By Sending Verification Mail. Please Click on Update And Resend Button</p>
										  <?php    
										  } 
										  */ ?>                                                  
										
										<p>You are almost there, to get started, please check your email and confirm your email address.
											<br />
										Confirming your email ensures you always have full access to your account.</p>
										
										<img src="<?= $baseUrl?>/images/done-img.jpg" class="fullw"/>
									</div>
									
									<input type="hidden" value="<?=$email?>" name="LoginForm[email]" id="loginform-email">
									<div class="pull-left">
                                                                            <a href="http://mail.<?=$email_redirect?>" target="_blank"><input type="button" class="step-submit graybtn sbtn" value="Confirm Email"></a>
                                                                        </div>
                                                                        <div class="pull-right"><a class="graylink" onclick="resendlink()" style="cursor: pointer">Send me another email</a></div>
								</div>
								
							</div>
							
							
						</div>						
						<?php ActiveForm::end() ?> 
						
					</div>
				</div>
			</div>
			
		</div>
	</div>
</div>
<script>
    function home(){
        window.location.href='<?php echo Yii::$app->urlManager->createUrl(['site/index']); ?>';
    }
</script>
<!-- footer section -->
<div class="np-fotter clearfix">
  <div class="container np-fotter-link"><a href="#">About</a> <span>|</span> <a href="#">Privacy</a> <span>|</span> <a href="#">Invite</a> <span>|</span> <a href="#">Terms</a> <span>|</span> <a href="#">Contact Us</a> <span>|</span> <a href="#">Features</a> <span>|</span> <a href="#">Mobile</a> <span>|</span> <a href="#">Developers</a></div>
</div>
<!-- end of footer section -->

 <script>
     function resendlink(){
         document.getElementById("resend_mail").submit();
     }
    var gebtn = function(parEl,child) { return parEl.getElementsByTagName(child); };
    onload = function() {
        
        var body = gebtn(d,'body')[0];
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
        function signup2(){
               $.ajax({
                   type: 'POST',
                   url: '<?php echo Yii::$app->urlManager->createUrl(['site/signup2']); ?>',
                   data: $("#signup2").serialize(),
                   success: function(data){
                   //alert(data); return false;
                     if(data == '0')
                     {
                         $('#fail').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
                     }
                     else if(data == '1')
                     {
                     $('#suceess').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
                     
                        window.location.href='<?php echo Yii::$app->urlManager->createUrl(['site/signup3']); ?>';
                        // $("#frm")[0].reset();
                       // $('#suceess').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
                     }
                     else if(data == '2')
                     {
                          $("#hold").show().fadeIn(300).fadeOut(5000);
                     }
                    else{

                    }
                   }
                   
               }); 
               }
	$(document).ready(function(){
		//$("body").addClass("home-page");	
	});
</script>
