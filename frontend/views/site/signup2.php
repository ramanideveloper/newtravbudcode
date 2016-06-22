<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use frontend\assets\AppAsset;

use yii\widgets\ActiveForm;
use yii\mongodb\ActiveRecord;
use yii\captcha\Captcha;

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
    $phone = $result['phone'];
    $isd_code = $result['isd_code'];
    $city = $result['city'];
    $country = $result['country'];
	
	$birth_date_access = 'Private';
        $photo = $this->context->getimage($result['_id'],'photo');
	
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
 <div class="notice">	
		<div id="suceess" class="form-successmsg">You Have Successfully Completed 1st Step</div>
		<div id="fail" class="form-failuremsg"></div>
		<div id="info" class="form-infomsg">Please Complete Your Signup Steps</div>
	</div>
	
	<div class="container np-form-fix01">
	
		<div class="stepform no-style">
			<div class="home-userinfo">
				<img src="<?= $photo?>" class="pull-right m-left">
				<h6><?= $email;?></h6>			
			</div>
		</div>
		<div class="stepform bpad">
			
			<div class="step-pannel">
					<div class="form-step">					
						
						<div class="steps">							
							<div class="grp current">

								
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
							<div class="grp last">
								

									<span class="info">Done</span>

									<span class="step">4</span>
								
							</div>
						</div>
					</div>
			</div>
			<div class="form-steps-holder clearfix">
				<div class="form-step-bg clearfix steparrow-1 steparrow">
					<div class="form-topstuff">
						
							<div class="form-title">					
								<h5><span><?=$fname?>,</span> Thank you for joining Travbud</h5>
							</div>
						
						
					</div>
					<div class="stepform-form">
						<?php $form = ActiveForm::begin(
							[
								'id' => 'signup2',
								'options'=>[
									'onsubmit'=>'return false;',
									],
							]
						); ?>
						<div class="row">
							<div class="col-lg-5 col-md-6 col-sm-6 col-xs-12 fcolumn">
								<div class="row">
									<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 sform-l">
										<label>First Name</label>
									</div>
									<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 sform-c">
										<div class="form-group">						

											<?= $form->field($model,'fname')->textInput(array('value'=>$fname,'onkeyup'=>'validate_fname()','onchange'=>'validate_fname()','id'=>'fname'))->label(false)?>				   

											 <div id='fnm-success' class="frm-validicon" style='display: none'><img src="<?= $baseUrl?>/images/frm-check.png"/></div>
											 <div id='fnm-fail' class="frm-validicon" style='display: none'><img src="<?= $baseUrl?>/images/frm-cross.png"/></div>
										</div>
															   
									</div>
								</div>					
							</div>
							<div class="col-lg-5 col-md-6 col-sm-6 col-xs-12 fcolumn pull-right">
								<div class="row">
									<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 sform-l">
										<label>Last Name</label>
									</div>
									<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 sform-c">
										<div class="form-group">						

										   <?= $form->field($model,'lname')->textInput(array('value'=>$lname,'onkeyup'=>'validate_lname()','onchange'=>'validate_lname()','id'=>'lname'))->label(false)?>					

										<div id='lnm-success' class="frm-validicon" style='display: none'><img src="<?= $baseUrl?>/images/frm-check.png"/></div>
															 <div id='lnm-fail' class="frm-validicon" style='display: none'><img src="<?= $baseUrl?>/images/frm-cross.png"/></div>
																	</div>
									</div>
								</div>					
							</div>
							
							<div class="col-lg-5 col-md-6 col-sm-6 col-xs-12 fcolumn">
								<div class="row">
									<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 sform-l">
										<label>Email</label>
									</div>
									<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 sform-c">
										<div class="form-group">						
											<?= $form->field($model,'email')->textInput(array('class'=>'form-control','value'=>$email,'onkeyup'=>'validate_email()','onchange'=>'validate_email()','id'=>'email'))->label(false)?>					
										<div id='eml-success' class="frm-validicon" style='display: none'><img src="<?= $baseUrl?>/images/frm-check.png"/></div>
															 <div id='eml-fail' class="frm-validicon" style='display: none'><img src="<?= $baseUrl?>/images/frm-cross.png"/></div>
																	</div>
											
									</div>
								</div>						
							</div>
							
							<div class="col-lg-5 col-md-6 col-sm-6 col-xs-12 fcolumn pull-right">
								<div class="row">
									<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 sform-l">
										<label>Password</label>
									</div>
									<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 sform-c">
										<div class="form-group">						

										   <?= $form->field($model,'password')->passwordInput(array('class'=>'form-control','value'=>$password,'onkeyup'=>'validate_spassword()','onchange'=>'validate_spassword()','id'=>'signup_password'))->label(false)?>

																		<div id='spwd-success' class="frm-validicon" style='display: none'><img src="<?= $baseUrl?>/images/frm-check.png"/></div>
															 <div id='spwd-fail' class="frm-validicon" style='display: none'><img src="<?= $baseUrl?>/images/frm-cross.png"/></div>
										</div>
									</div>
								</div>					
							</div>
							
							<div class="col-lg-5 col-md-6 col-sm-6 col-xs-12 fcolumn ">
								<div class="row">
									<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 sform-l">
										<label>City</label>
									</div>
									<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 sform-c">
										<div class="form-group">						
										   <?= $form->field($model,'city')->textInput(array('value'=>$city,'class' => 'form-control getplacelocation', 'id'=>'autocomplete','onFocus'=>'geolocate()'))->label(false)?>									
										</div>
									</div>
								</div>					
							</div>
							<div class="col-lg-5 col-md-6 col-sm-6 col-xs-12 fcolumn pull-right">
								<div class="row">
									<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 sform-l">
										<label>Country</label>
									</div>
									<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 sform-c">
										<div class="form-group">
										   <?= $form->field($model,'country')->textInput(array('value'=>$country,'id'=>'country','readonly' => true))->label(false)?>					
										</div>
									</div>
								</div>					
							</div>
							
							<div class="col-lg-5 col-md-6 col-sm-6 col-xs-12 fcolumn ">
								<div class="row">
									<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 sform-l">
										<label>Mobile</label>
									</div>
									<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 sform-c">
										<div class="two-comp">
											<div class="row">
												<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
													<div class="form-group"><input type="text" readonly="true" name="isd_code" value="<?= $isd_code?>" placeholder="isd code" id="isd_code" class="form-control"/></div>
												</div>
												<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
													<?= $form->field($model,'phone')->textInput(array('class'=>'form-control inputp-fix','id'=>'phone','value'=>$phone,'onkeyup'=>'checkAvailability()','onchange'=>'checkAvailability()'))->label(false)?>	
													<div id='phn-success' class="frm-validicon" style='display: none'><img src="<?= $baseUrl?>/images/frm-check.png"/></div>
													<div id='phn-fail' class="frm-validicon" style='display: none'><img src="<?= $baseUrl?>/images/frm-cross.png"/></div>
												</div>
											</div>
											<!--<span id="user-availability-status"></span>-->
										</div>
										
									</div>
								</div>
							</div>
							<div class="clear"></div>
							
							<div class="col-lg-5 col-md-6 col-sm-6 col-xs-12 fcolumn ">
								<div class="row">
									<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 sform-l">
										<label>Birth Date</label>
									</div>
									<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 sform-c">
										<?= $form->field($model,'birth_date')->textInput(array('placeholder'=>'Birthdate','id'=>'datepicker','onkeydown'=>'return false;','value'=>$birth_date))->label(false)?>
										<input type="hidden" name="birth_access" value="<?= $birth_date_access?>" />
										<div class="date-container">
										</div>
			<!--
										<div class='input-group date' id='datetimepicker2'>									
											<?= $form->field($model,'birth_date')->textInput(array('value'=>$birth_date))->label(false)?>
											<span class="input-group-addon"><i class="glyphicon glyphicon-calendar " style="color:#0071BD;"></i></span>
										</div>
										<div class="form-group">
											<?php
											
												$date = time();
															 
											?>
										</div>-->

									</div>
								</div>					
								
							</div>

							<div class="clear"></div>
					
							<div class="col-lg-5 col-md-6 col-sm-6 col-xs-12 fcolumn ">	
								<div class="row">
									<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 sform-l">
										<label>Gender</label>
									</div>
									<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 sform-c">

										 <div class="radio pull-left gender-radio">
								 
											<?php
												if($gender == "Male")
												{
											?>
											  
												<?php echo $form->field($model, 'gender')->dropDownList(['Male' => 'Male', 'Female' => 'Female'])->label(false); ?>
							  
													
											 <?php 
												}
												else
												{
												?> 
												 <?php echo $form->field($model, 'gender')->dropDownList(['Female' => 'Female', 'Male' => 'Male'])->label(false); ?>
											   
												<?php
												}
											 ?>
										  </div>

									</div>
								</div>
								
							</div>
							<div class="clear"></div>					
							<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
								<div class="captcha-holder">
															<p><span>Security Check :</span> To guard against automated systems and robotos, Please add the two numbers and enter your answer in the box.</p>
									<div class="captcha-box">
									
										<?= $form->field($model,'captcha')->widget(Captcha::className())?>
									
									</div>
								</div>
							</div>
							<div class="clear"></div>					
							<div class="col-lg-12">
								<div class="checkb-holder">
									<div class="h-checkbox">
										<input type="checkbox" id="test1">
										<label  for="test1"><a href="javascript:void(0)">Agreed to the terms and conditions</a></label>
									</div>
								</div>
							</div>
						
						</div>
						
						<div class="form-divider">
							<input type="submit" class="step-submit formbtn pull-right" value="Create Account" onclick="signup2()">
						
							<a href="<?php echo Yii::$app->urlManager->createUrl(['site/index']); ?>" class="skip-step pull-right">Cancel</a>
						</div>
							
						<?php ActiveForm::end() ?> 
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- footer section -->
<div class="np-fotter clearfix">
  <div class="container np-fotter-link"><a href="#">About</a> <span>|</span> <a href="#">Privacy</a> <span>|</span> <a href="#">Invite</a> <span>|</span> <a href="#">Terms</a> <span>|</span> <a href="#">Contact Us</a> <span>|</span> <a href="#">Features</a> <span>|</span> <a href="#">Mobile</a> <span>|</span> <a href="#">Developers</a></div>
</div>
<script>
function validate_fname(){
     var fname = $("#fname").val();
    
     var reg_nm = /^[a-zA-Z\s]+$/;
     var spc = /^\s+$/;
		
                if(fname == "")
		{
                    $('#fail').html('Please Enter First Name.');
                         $("#fnm-success").hide();
			$("#fnm-fail").hide();
			document.getElementById('fname').focus();
			return false;
		}
                
		if(fname.length < 2)
		{
                        $('#fail').html('Please Enter minimum 2 characters in First Name.');
                        $("#fnm-success").hide();
			$("#fnm-fail").show();
			document.getElementById('fname').focus();
			return false;
		}
		
		if(!reg_nm.test(fname))
		{
                    $('#fail').html('Please Enter Characters only in First Name.');
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
                    $('#fail').html('Please Enter Last Name.');
                        $("#lnm-success").hide();
			$("#lnm-fail").show();
			document.getElementById('lname').focus();
			return false;
		}
                
                if(lname.length < 2)
		{
                    $('#fail').html('Please Enter minimum 2 characters in Last Name.');
                        $("#lnm-success").hide();
			$("#lnm-fail").show();
			document.getElementById('lname').focus();
			return false;
		}
		
		
		if(!reg_nm.test(lname))
		{
                    $('#fail').html('Please Enter Characters only in Last Name.');
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
        $('#fail').html('Please Enter Email.');
        $("#eml-success").hide();
        $("#eml-fail").hide();
        document.getElementById('email').focus();
        return false;
    }

    if(!pattern.test(email))
    {
        $('#fail').html('Please Enter valid Email.');
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
                if(spassword == "")
                {
                    $('#fail').html('Please Enter Password.');
                    $("#spwd-success").hide();
                    $("#spwd-fail").hide();
                    return false;
                }
                if(spassword.length < 6)
                {
                    $('#fail').html('Please Enter Password of 6 length.');
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
        
 function validate_city(){
     var city = $("#autocomplete").val();
    if(city == "")
    {
        $('#fail').html('Please Enter City.');
             $("#fnm-success").hide();
            $("#fnm-fail").hide();
            document.getElementById('autocomplete').focus();
            return false;
    }
    return true;
                
}        
        
</script>
<!-- end of footer section -->
 <script>
    var d = document;
    var safari = (navigator.userAgent.toLowerCase().indexOf('safari') != -1) ? true : false;
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
		
		  jq(".fp-link").click(function(){
			  		  
			  var disp = jq(".fp-details").css("display");
			  if(disp=="none"){				 
				  jq(".fp-details").fadeIn(300);
				  jq(".base").delay(200).slideDown(500);
			  }
			  else{
				 jq(".fp-details").delay(700).fadeOut(500);
				 jq(".base").slideUp(500);			  				 
			  }
		  });
		  jq(".fp-close").click(function(){
			  		  
			  var disp = jq(".fp-details").css("display");
			  if(disp=="none"){				 
				  jq(".fp-details").fadeIn(500);
				  jq(".base").delay(200).slideDown(500);
			  }
			  else{				  
				  jq(".fp-details").delay(700).fadeOut(300);
				  jq(".base").slideUp(500);			  
			  }
			 
		  });
		 
	
	});
        
function checkAvailability() {

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
        success:function(data){
            if(data == '1')
            {
                $("#phn-fail").hide();
                $("#phn-success").show();
                return true;
            }
            else
            {
                $("#phn-success").hide();
                $("#phn-fail").show();
                return false;
            }
            },
        });
    }
}

    function firstToUpperCase(string) {
            return string.charAt(0).toUpperCase() + string.slice(1).toLowerCase();
        }
        function signup2(){
        var bfname = $("#fname").val();
        var blname = $("#lname").val();
        var fname = firstToUpperCase( bfname );
        var lname = firstToUpperCase( blname );
        $("#fname").val(fname);
        $("#lname").val(lname);  
        var phone = $("#phone").val();
        var ptn = /([0-9\s\-]{1,})(?:\s*(?:#|x\.?|ext\.?|extension)\s*(\d+))?$/;
		/*
		alert(checkAvailability());
		
        if(checkAvailability()){
            alert("TRUE");
        }   
        else{
            alert("FALSE");
        }
		
        return false;*/
        
        if(!(validate_fname() && validate_lname() && validate_email() && validate_spassword() && validate_city()))
        {
            $('#fail').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
        }
        else if(phone =="")
        {
            $('#fail').html('Please Enter Mobile Number.');
            $('#fail').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
            $("#phn-success").hide();
            $("#phn-fail").show();
            document.getElementById('phone').focus();
            return false;

        }
        else if(!ptn.test(phone))
        {
            $('#fail').html('Please Enter valid Number.');
            $('#fail').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
            $("#phn-success").hide();
            $("#phn-fail").show();
            document.getElementById("phone").focus();
            return false;
        }
        else if(!document.getElementById("test1").checked)
        {
            $('#fail').html('Please agreed to the terms and conditions.');
            $('#fail').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
        }
          else
          {
        
               $.ajax({
                   type: 'POST',
                   url: '<?php echo Yii::$app->urlManager->createUrl(['site/signup2']); ?>',
                   data: $("#signup2").serialize(),
                   success: function(data){
                   //alert(data); return false;
                     if(data == '0')
                     {
                         $('#fail').html('Please click on current captcha image to generate new code.');
                         $('#fail').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
                     }
                     else if(data == '1')
                     {
                     $('#suceess').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
                     
                        window.location.href='<?php echo Yii::$app->urlManager->createUrl(['site/signup3']); ?>';
                       
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
          /*
          else {      
               $('#fail').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
          }*/
               
    }
	jq(document).ready(function(){
		//jq("body").addClass("home-page");	
	});
	

	function changedate()
	{
		var adate = $('#popupDatepicker').val();
		$('#birthdate').val(adate);
	}
		
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

	function showDate(date) {
		   
		alert('The date chosen is ' + date);
	}
	function removeSelOptions(e) {

        $("ul#" + e + " > li").each(function ()
        {
            $(this).removeClass("selected");
        });
    }

	function setSecurityIcon(test) {

        var ulId = $(test).parents("ul").attr("id");
		
        var pClass = $(test).parent().attr("class");
        pClass = pClass.toLowerCase();
        pClass = pClass.replace('selected', '');
        pClass = pClass.replace('sel-', '');

        var setOption = $(test).parent().attr("class");
        setOption = setOption.replace('selected', '');
        setOption = setOption.replace('sel-', '');

        removeSelOptions($(test).parent().parent().attr("id"));
        $(test).parent().addClass("selected");

        pClass = pClass.replace(/-/g, ' ');// remove hyphen
		pClass = pClass.trim();

        var ic = $(test).html().toLowerCase();
	
        ic = ic.replace(pClass, '');
		
        setOption = setOption.replace(/-/g, ' ');// remove hyphen
		
        ic = ic + setOption;

        ic = ic + "<span class='caret'></span>";
		
		ic = ic.replace(setOption,""); // remove word and keep icon
		
        var selSelector = ".custom-select-" + ulId;
        $(selSelector).html(ic);

        var selSpan = ".thisSecurity-" + ulId;
        $(selSpan).html(setOption);
		
    }
		
</script>