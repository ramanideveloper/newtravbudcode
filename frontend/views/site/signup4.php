<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use frontend\assets\AppAsset;

use yii\widgets\ActiveForm;
use yii\mongodb\ActiveRecord;
use frontend\models\LoginForm;

use frontend\models\Education;
use frontend\models\Language;
use frontend\models\Interests;
use frontend\models\Occupation;

use yii\helpers\ArrayHelper;

use frontend\models\Personalinfo;
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
 
}
else{
     $url = Yii::$app->urlManager->createUrl(['site/index']);
            Yii::$app->getResponse()->redirect($url);   
}

$asset = frontend\assets\AppAsset::register($this);

$baseUrl = AppAsset::register($this)->baseUrl;
?>


<?php $this->beginBody() ?>

<link rel="stylesheet" type="text/css" href="<?= $baseUrl?>/css/select2.min.css" />
<script src="<?= $baseUrl?>/js/select2.min.js" type="text/javascript"></script>
    
<!-- header section -->
<div class="np-header01 sidepad">
  <!--<div class="container clearfix" >-->
    <div class="row">
      <div class="col-sm-4 signup-logo">
          <div class="logo"><a href="<?php echo Yii::$app->urlManager->createUrl(['site/index']); ?>" class="pageLoad" ><img src="<?= $baseUrl?>/images/logo-2.png"></a></div>
      </div>
      <div class="col-sm-8 clearfix tagline">
			
		<!--<h4>Travel Reviews, Blogs, Articles, Photos, Events &amp; More</h4>-->
		<p>Social Networking Community for Travellers!</p>
       
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
						<div class="grp current">
						

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
				<div class="form-step-bg clearfix steparrow-3 steparrow">
					<div class="form-topstuff">
						<div class="form-title">					
							<h5><span><?=$fname?>,</span> Tell us about you</h5>
						</div>
					</div>
					<div class="stepform-form">
						<?php $form = ActiveForm::begin(['action' => ['site/signup4'],'options' => ['method' => 'post']]) ?>
						<div class="row">
					
							<div class="col-lg-2 col-md-2 col-sm-3 col-xs-12">
								<label>About Yourself</label>
							</div>
							<div class="col-lg-10 col-md-10 col-sm-9 col-xs-12">
								<?= $form->field($model,'about')->textarea(array('class'=>'form-control','placeholder'=>'About Yourself'))->label(false)?>				   
							</div>
						
					
							<div class="col-lg-2 col-md-2 col-sm-3 col-xs-12">
								<label>Education</label>
							</div>
							<div class="col-lg-10 col-md-10 col-sm-9 col-xs-12">
								<div class="signup-linett">
			<?= $form->field($model,'education')->dropDownList(ArrayHelper::map(Education::find()->all(), 'name', 'name'),['class'=>'js-example-theme-multiple','style'=>'width: 100%','multiple'=>'multiple','id'=>'education'])->label(false)?>                    </div>                  
							</div>
								
							
							<div class="col-lg-2 col-md-2 col-sm-3 col-xs-12">
								<label>Interest</label>
							</div>
							<div class="col-lg-10 col-md-10 col-sm-9 col-xs-12">					
								<div class="signup-linett">
							<?= $form->field($model,'interests')->dropDownList(ArrayHelper::map(Interests::find()->all(), 'name', 'name'),['class'=>'js-example-theme-multiple','style'=>'width: 100%','multiple'=>'multiple','id'=>'interests'])->label(false)?>                                    
								</div>
							</div>
							
							<div class="col-lg-2 col-md-2 col-sm-3 col-xs-12">
								<label>Occupation</label>
							</div>
							<div class="col-lg-10 col-md-10 col-sm-9 col-xs-12">					
								<div class="signup-linett">
									<?= $form->field($model,'occupation')->dropDownList(ArrayHelper::map(Occupation::find()->all(), 'name', 'name'),['class'=>'js-example-theme-multiple','style'=>'width: 100%','multiple'=>'multiple','id'=>'occupation'])->label(false)?>                                 
								</div>
							</div>
											
							<?= $form->field($model,'email')->hiddenInput(array('class'=>'form-control','value'=>$email))->label(false)?>
							
							<div class="col-lg-2 col-md-2 col-sm-3 col-xs-12">
								<label>Language</label>
							</div>
							<div class="col-lg-10 col-md-10 col-sm-9 col-xs-12">					
								<div class="signup-linett">
									<?= $form->field($model,'language')->dropDownList(ArrayHelper::map(Language::find()->all(), 'name', 'name'),['class'=>'js-example-theme-multiple','style'=>'width: 100%','multiple'=>'multiple','id'=>'language'])->label(false)?>                               
								</div>
							</div>
											
							
							<div class="col-lg-2 col-md-2 col-sm-3 col-xs-12">
								<label>Can you host?</label>
							</div>
							<div class="col-lg-10 col-md-10 col-sm-9 col-xs-12">
								<div class="radio-holder">
									
									<label class="host-mr-fix"><input name="Personalinfo[is_host]" value="Yes" type="radio" class ='host'> Yes</label>
									<label><input name="Personalinfo[is_host]" value="No" type="radio" class ='host'> No</label>
									
								</div>
							</div>
					
							
							<div class="col-lg-2 col-md-2 col-sm-3 col-xs-12">
								<label>Host Services</label>
							</div>
							<div class="col-lg-10 col-md-10 col-sm-9 col-xs-12">
								<div class="check-holder services">						
								   <?php echo $form->field($model, 'host_services[]')->checkboxList(['Hang around' => 'Hang around', 'Dinning' => 'Dinning', 'Site touring' => 'Site touring'],array('class'=>'don')); ?>
								</div>
							</div>
						
							
							<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 clear">
								<div class="row">
									<div class="col-lg-4 col-md-4 col-sm-3 col-xs-12">
										<label>Host Gender</label>
									</div>
									<div class="col-lg-8 col-md-8 col-sm-4 col-xs-12">
										<div class="form-group">						
										   <div class="radio pull-left gender-radio kala">
											  <?php echo $form->field($model, 'gender')->dropDownList(['Male' => 'Male', 'Female' => 'Female', 'Both' => 'Both'])->label(false); ?>
							  
										  </div>
										</div>
									</div>
								</div>
								  
								
							</div>				
							
						</div>
						<div class="form-divider">
							
							<input type="submit" class="step-submit graybtn pull-right" value="Next">
						
							<a href="<?php echo Yii::$app->urlManager->createUrl(['site/verify']); ?>" class="skip-step pull-right">Skip Page</a>
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
<!-- end of footer section -->

 <script>
 
 $(document).ready(function(){ $(".host").click(function(){ 
	var a =  $(this).val();
	if(a == 'No'){
		
		$(".services input[type='checkbox']").attr('disabled','disabled');
		
		$("#personalinfo-gender").prop("disabled", true);
		
		
		
	}
	else {
		
		$(".services input[type='checkbox']").attr('disabled', false);
		$("#personalinfo-gender").prop("disabled", false);
		
		
		
	}
	
 })})
 
 /*
 function demo(){
	//var a =  $("input[name=Personalinfo[is_host]]").val();
	var a =  $(this).val();
	alert(a);
 }*/
 
 
 
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
			   
jq(document).ready(function(){
	

	jq(".js-example-theme-multiple").select2({
	  tags: true
	});   

	jq("#education").select2({
	  placeholder: "Your highest degree",
	  tags: true
	});
	jq("#interests").select2({
	  placeholder: "What are your current interest",
	  tags: true
	});
	jq("#occupation").select2({
	  placeholder: "Most recent occupation",
	  tags: true
	});
	jq("#language").select2({
	  placeholder: "languages you can read and write",
	  tags: true
	});
});

</script>