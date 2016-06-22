<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use frontend\assets\AppAsset;

use yii\widgets\ActiveForm;
use yii\mongodb\ActiveRecord;


//use vendor\bower\travel\assets\AppAsset;
//AppAsset::register($this);
//AppAsset::register($this);
$asset = frontend\assets\AppAsset::register($this);
$baseUrl = AppAsset::register($this)->baseUrl;
?>
<!-- header section -->
<div class="np-header01">
  <div class="container clearfix" >
    <div class="row">
      <div class="col-sm-4">
          <div class="logo"><a href="#" class="pageLoad" ><img src="<?= $baseUrl?>/images/logo.png" width="152" height="43"></a></div>
      </div>
      <div class="col-sm-8 clearfix" >
         
         <!-- <div class="social-section clearfix"> 
		  <a href="#"><img src="<?= $baseUrl?>/images/facebook-icon.png" ></a> <a href="#"><img src="<?= $baseUrl?>/images/google-icon.png"></a> <a href="#"><img src="<?= $baseUrl?>/images/linkedin-icon.png"></a> 
		  </div> -->
        <div class="cus-login-section clearfix">
          <div class="cus-login-part1 clearfix">
            <div class="clearfix">
              <input name="1" placeholder="Email Address" type="text" id="1" >
            </div>
            <div class="checkbox has-js">
              <fieldset class="checkboxes">
                <label class="label_check" for="checkbox-01"><input name="sample-checkbox-01" id="checkbox-01" value="1" type="checkbox" checked />Remember Me</label>
             </fieldset>
            </div>
          </div>
          <div class="cus-login-part2 clearfix">
            <div class="clearfix">
              <input name="1" placeholder="Password" type="text" id="2" >
            </div>
            <div > <a href="#"><span class="np-right-caret"></span>Forgot Password?</a>  <a href="#" class="np-mobile-fix"><span class="np-right-caret"></span>Login</a> </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
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
              <div class="text-b">Dead Sea, Jordan</div>
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
              <div class="text-b">Petra, Jordan</div>
            </div>

          </li>
          <li style="height:450px; background:url(<?= $baseUrl?>/images/4.jpg) center top no-repeat; background-size:cover;">
          
            <div class="flex-caption">
              <div class="text-a">The best online place to meet travellers and friends</div>
              <div class="text-b">Dead Sea, Jordan</div>
            </div>

            
          </li>
          <li style="height:450px; background:url(<?= $baseUrl?>/images/5.jpg) center top no-repeat; background-size:cover;">
            <div class="flex-caption">
              <div class="text-a">Check out destination, tour sites and events that interest you.</div>
              <div class="text-b">Beijing, China</div>
            </div>
            
          </li>
   

        </ul>
      </div>
    </div>
    </div>
    <!-- Start Flex Slider code -->
<link href="<?= $baseUrl ?>/sliders/css/flexslider.css" rel="stylesheet" type="text/css" />
    <script src="<?= $baseUrl ?>/sliders/js/jquery.flexslider-min.js" type="text/javascript"></script>   
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
    
    <!-- JavaScript at the bottom for fast page loading -->
    <script type="text/javascript" src="<?= $baseUrl ?>/sliders/js/plugins.js"></script> 
   
    
 
<!-- Register Form --> 

<div class="container np-form-fix01">
	
	<div class="card-holder">
		<div id="card-2" class="card">
		  <div class="front">
			 <div class="btn-boxholder center-align">
				<a href="#" class="signup-google"><img src="<?= $baseUrl?>/images/gplus-icon.png">Connect With Google</a>
				<a href="<?php echo Yii::$app->request->baseUrl.'?r=site/auth&authclient=facebook' ?>" class="signup-fb"><img src="<?= $baseUrl?>/images/fb-icon.png">Connect With Facebook</a>
				<span>OR</span>	
				<a href="#" class="signup-tb">Sign Up</a>
			</div>
	 
		  </div>
		  <div class="back">
			<div class="np-tb-formbox ppbox">
				<div class="close-btn">x</div>
				<div class="title-01"><span>Signup now, </span><span>its free</span></div>
					<div class="home-reg-form">

					  <!--<form role="form">
						<div class="form-group">
						  <input type="text" class="form-control" placeholder="First Name">
						</div>
						<div class="form-group">
						  <input type="text" class="form-control" placeholder="Last Name">
						</div>
						<div class="form-group">
						  <input type="text" class="form-control" placeholder="Email Address">
						</div>
						<div class="form-group">
						  <input type="text" class="form-control" placeholder="Password">
						</div>
						<div class="form-group">
						  <input type="text" class="form-control" placeholder="Confirm Password">
						</div>
						<div class='input-group date' id='datetimepicker2'>
							<input type='text' class="form-control" placeholder="Birthdate" />
											<span class="input-group-addon"><i class="glyphicon glyphicon-calendar " style="color:#0071BD;"></i></span>
						  </div>
						<div class="form-group clearfix" style="margin-top:10px;">
						  <div class="radio pull-left" style="margin-right:10px;">
							<label style="margin-right:15px; color:#fff;">
							  <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" >
							  Male </label>
							<label style="color:#fff;">
							  <input type="radio" name="optionsRadios" id="optionsRadios1ss" value="option1" >
							  Female </label>
						  </div>
						</div>
						<div class="form-group" style="margin-bottom:2px;">
						  <input type="submit" class="home-submit" value="SIGNUP NOW">
						</div>
					  </form>-->

            <?php $form = ActiveForm::begin(); ?>

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
         <?= $form->field($model,'birth_date')->textInput(array('class'=>'form-control','placeholder'=>'Birth Date'))->label(false)?>
                        <span class="input-group-addon"><i class="glyphicon glyphicon-calendar " style="color:#0071BD;"></i></span>
        </div>
            <div class="form-group clearfix" style="margin-top:10px;">
              <div class="radio pull-left" style="margin-right:10px;">
             
                <label style="margin-right:15px; color:#fff;">
                    <?= $form->field($model, 'gender')->radioList(array('Male'=>'Male','Female'=>'Female'))->label(false); ?>
                 
              </div>
            </div>
            <div class="form-group" style="margin-bottom:2px;">
              <input type="submit" class="home-submit" value="SIGNUP NOW">
            </div>
        <?php ActiveForm::end() ?>

            
					</div>
				  </div>
			</div> 
		</div>
		
	</div>

<!-- 3 Box --> 


<div class="container clearfix np-mobile-fix01">
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
        format: "DD-MM-YYYY"         
    }); })(jQuery); 
</script>

<!-- Popup itself -->
<div id="test-popup" class="sblack-popup mfp-with-anim mfp-hide">

	
</div>
	
</div>

<script src="<?= $baseUrl?>/js/flip.js" type="text/javascript"></script>
<script type="text/javascript">

	var jq = $.noConflict();
	function setVisibile(ff){
		
		console.log("ff - "+ff);
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
		
		setVisibile(frontface);
		//	$(function(){
		  
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
	//	});
	});
</script>