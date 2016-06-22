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
$session->set('signup_fb','signup_facebook');
$session->set('signup_email',$session->get('email_id'));

if($session->get('email_id'))
{
    $email = $session->get('email_id'); 
    $result = LoginForm::find()->where(['email' => $email])->one();
    
    $user_id = $result['_id'];
    $username = $result['username'];
    $fname = $result['fname'];
    $lname = $result['lname'];
    $password = $result['password'];
    $con_password = $result['con_password'];
    $birth_date = $result['birth_date'];
    $gender = $result['gender'];
    $photo = $result['photo'];
    $fb_id = $result['fb_id'];
    /*if(isset($photo) && !empty($photo))
    {
        $photo = $result['photo'];
    }
    else
    {
        $photo = $gender.'.jpg';
    }*/
   $photo = $this->context->getimage($user_id,'photo');
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
<script src="<?= $baseUrl?>/js/jquery-1.12.2.min.js"></script>
<script src="<?= $baseUrl?>/js/jquery.cropit.js"></script>
<script>
    $(document).ready(function() {
        webcam.set_api_url('upload.php');  
        webcam.set_quality( 90 ); // JPEG quality (1 - 100)
        webcam.set_shutter_sound( true ); // play shutter click sound
        webcam.set_hook( 'onComplete', 'my_completion_handler' );
        document.getElementById("camCode").innerHTML = webcam.get_html(240, 240); 
    });
    function webcam111()
    {
        $(".form-divider").hide();
        $("#main-picture").hide();
        $(".consider-div").show();
        $("#webcam-picture").show();
    }
    function hidewebcam()
    {
        $(".form-divider").show();
        $("#main-picture").show();
        $(".consider-div").hide();
        $("#webcam-picture").hide();
    }
    function take_snapshot()
    {
        // take snapshot and upload to server
        document.getElementById('upload_results').innerHTML = 'Snapshot<br>'+
        '<img src="uploading.gif">';
        webcam.snap();
    }
    function my_completion_handler(msg) {
        // extract URL out of PHP output
        if (msg.match(/(http\:\/\/\S+)/))
        {
            var image_url = RegExp.$1;
            // show JPEG image in page
            document.getElementById('upload_results').innerHTML = 
                'Snapshot<br>' + 
                '<a href="'+image_url+'" target"_blank"><img src="' + image_url + '"></a>';
            $("#web_cam_img").val(image_url);
            // document.getElementById('web_cam_img').value(image_url);
            // reset camera for another shot
            webcam.reset();
        }
        else alert("PHP Error: " + msg);
    }
</script>
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
		<div class="stepform bpad profilepic-sec">
			
			<div class="step-pannel">
				<div class="form-step">					
					
					<div class="steps">							
						<div class="grp">

						
								<span class="info">Profile</span>

								<span class="step">1</span>			
								<span class="divider"></span>			
						
						</div>
						<div class="grp current">
						

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
				<div class="form-step-bg clearfix steparrow-2 steparrow">
					<div class="form-topstuff">
						<div class="form-title">					
							<h5><span><?=$fname?>,</span> Photo tell about you to other members</h5>
						</div>						
					</div>
					<div class="stepform-form">
						<?php $form = ActiveForm::begin(['action' => ['site/signup3'],'options' => ['method' => 'post','enctype'=>'multipart/form-data','onsubmit'=>'checkCoords()']]) ?>
						

						<div class="row">			
							<div id="main-picture">
								<div id="profilepreview">
									<div class="col-lg-5 col-md-6 col-sm-12 col-xs-12 pull-right form-ppholder">
										<div class="row">						
											<div class="piccrop-holder">
												<input type="hidden" name="profileimage" id="profileimage" value="<?= $photo?>">
												<input type="hidden" name="imagevalue" id="imagevalue" value="">
												<div class="crop-section">
													<div class="cropit-preview"></div>
													<input type="range" class="cropit-image-zoom-input">
													<div class="crop-bg"></div>
												</div>
											</div>
										</div>
									</div>
									<div class="col-lg-7 col-md-6 col-sm-12 col-xs-12 form-ppoptions">					
										<div class="opt-div">
											
											<p class="grayp">Your photo must be atleast 150x150 pixels with file format of JPEG, GIF or JPG.</p>
																		  
											<div class="fakeFileButton">
												<img src="<?= $baseUrl?>/images/upload-icon.png"/>Upload a photo from your computer
												<?php echo $form->field($model,'photo')->fileInput(array('class'=>'cropit-image-input','id'=>'imgInp', 'accept'=>"image/*"))->label(false)?>
											</div>	
											<!--<span class="filename">yourfilename.jpg</span>-->						
											<input type="hidden" name="web_cam_img" id="web_cam_img" />
										
										</div>
										
										<div class="opt-div ">										
											<div class="fakeFileButton">
												<img src="<?= $baseUrl?>/images/webcam.png"/>Take Photo
												<input type="button" class="step-submit graybtn" value="Take Photo" onclick="webcam111()">
											</div>	
										</div>

										<div class="opt-div nbb">
											
											<div class="fakeFileButton fbicon">
                                                                                                <a href="<?php echo Yii::$app->request->baseUrl.'?r=site/auth&authclient=facebook' ?>" class="fb-btn"><i class="fa fa-facebook"></i>Use a photo from Facebook</a>
											</div>	
										</div>
										
										
										<div class="note">
											<p>
												<span class="orange-text">Remember:</span>							
												<br />
												Your profile photo needs to be clear, your gender must match your photo and should not be obscene or contain nudity.
											</p>
											<!--
											<p><i class="fa fa-info"></i>Please follow this simple guidelines:</p>
											<ol>
												<li>Only you can be in the photo</li>
												<li>Your face must be recorgnisable</li>
												<li>No explicit nudity</li>
												<li>No copyright violations</li>
											</ol>
											<div class="subline">
												<p><i class="fa fa-lock"></i>Safe and Secure
											</div>
											-->
										</div>
															
									</div>
								</div>
							</div>
							<div id="webcam-picture" style="display: none">
								<div class="col-lg-12">

										<table class="main">
										<tr>
											<td valign="top">
													<div class="border">
												Live Webcam<br>
												<div id="camCode"></div>
												<!--<embed id="webcam_movie" src="webcam.swf" loop="false" menu="false" quality="best" bgcolor="#ffffff" width="320" height="240" name="webcam_movie" align="middle" allowScriptAccess="always" allowFullScreen="false" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" flashvars="shutter_enabled=1&shutter_url=shutter.mp3&width=320&height=240&server_width=320&server_height=240" />-->
												</div>
												<br/><input type="button" class="snap" value="SNAP IT" onClick="take_snapshot()">
											</td>
											<td width="50">&nbsp;</td>
											<td valign="top">
												<div id="upload_results" class="border">
													Snapshot<br>
													<img src="logo.jpg" />
												</div>
											</td>
										</tr>
									</table>
								</div>
							</div>
						</div>
						
						<div class="form-divider">
						
							<input type="submit" class="step-submit formbtn pull-right export" value="Next" onclick ="return validate(this.form);">
							
							<a href="<?php echo Yii::$app->urlManager->createUrl(['site/signup4']); ?>" class="skip-step pull-right">Skip Page</a>
						</div>
						<div class="consider-div" style="display:none">						
							<div class="pull-right fb-btnholder">																		
								<input type="submit" class="btn btn-primary btn-sm" value="Save" onClick="return validate(this.form);">
								<input type="button" class="btn btn-primary btn-sm" value="Cancel" onClick="hidewebcam();">						
							</div>										
						</div>		

						<?php ActiveForm::end() ?> 
						</div>
		
				</div>
			</div>
			
		</div>
	</div>
</div>
<!--=========================Form Validations=======================-->
<script type = "text/javascript">
	function validate(form)
	{
		var img = document.getElementById('imgInp').value;
		var img_petern = /^.*\.(jpg|gif|jpeg|png|tif|webp)$/i;
                
                var webcam = document.getElementById('web_cam_img').value;
		
		
		if(img === "" && webcam === "")
		{
                    BootstrapDialog.show({
						size: BootstrapDialog.SIZE_SMALL,
                        title: 'Upload image',
                        message: 'Please Upload Your Profile Picture.'
                    });
                    document.getElementById('imgInp').focus();
                    return false;
		}
		
		if(!img_petern.test(img) && img !== "")
		{
                        BootstrapDialog.show({
                            title: 'Upload image',
                            message: 'Please upload jpg, gif, png, tif and webp image.'
                        });
			document.getElementById("imgInp").focus();
			return false;
		}
		
                var imageData = jq('#profilepreview').cropit('export');
                jq("#imagevalue").val(imageData);
		return true;
	}
</script>
<!--=========================Form Validations=======================-->
<script>
	
	function readURL(input) {
           
		   var _URL = window.URL || window.webkitURL;
		   
            if (input.files && input.files[0]) {
				
					file = input.files[0];
					
                    var reader = new FileReader();

                    reader.onload = function (e) {
                        
                        $('#profilepreview').attr('src', e.target.result);
						 
						var img = new Image();
						img.onload = function() {
							
							var curr_cls='';
							
							//alert(this.width+" - "+this.height);
							if(this.width < 150 || this.height < 150){
								
								BootstrapDialog.show({
									title: 'Small Image!',
									message: 'Kindly upload the image having size of atleast 150x150 pixels.'
								});
                                                                document.getElementById('imgInp').value = '';
								return false;
							}
							
						};
						img.onerror = function() {
						   alert( "not a valid file: " + file.type);
						};
						img.src = _URL.createObjectURL(file);
                    }

                    reader.readAsDataURL(input.files[0]);
            }
        }
       
	
	 $("#imgInp").change(function(){
             
            var img = document.getElementById('imgInp').value;
            var img_petern = /^.*\.(jpg|gif|jpeg|png|tif|webp)$/i;

            if(!img_petern.test(img))
            {
                    BootstrapDialog.show({
                        title: 'Upload image',
                        message: 'Please upload jpg, gif, png, tif and webp image.'
                    });
                    document.getElementById("imgInp").focus();
                    return false;
            }
                        
	readURL(this);
});
	</script>
<!-- footer section -->
<div class="np-fotter clearfix">
  <div class="container np-fotter-link"><a href="#">About</a> <span>|</span> <a href="#">Privacy</a> <span>|</span> <a href="#">Invite</a> <span>|</span> <a href="#">Terms</a> <span>|</span> <a href="#">Contact Us</a> <span>|</span> <a href="#">Features</a> <span>|</span> <a href="#">Mobile</a> <span>|</span> <a href="#">Developers</a></div>
</div>
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


<script src="<?= $baseUrl?>/js/flip.js" type="text/javascript"></script>
<script type="text/javascript">
        var profileimage = $("#profileimage").val();
	var jq = $.noConflict();
        
         jq('#profilepreview').cropit({
            exportZoom: 1.00,
            imageBackground: true,
            imageBackgroundBorderWidth: 20,
            imageBackgroundBorderHeight: 20,
            imageState: {
              src: profileimage,
            },
        });
        
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