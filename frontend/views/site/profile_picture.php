<?php

/* @var $this \yii\web\View */
/* @var $content string */
include('includes/header.php');
use yii\helpers\Html;
use frontend\assets\AppAsset; 

use yii\widgets\ActiveForm;
use yii\mongodb\ActiveRecord;

use frontend\models\LoginForm;
use  yii\web\Session;
$session = Yii::$app->session;
$session->set('pro_fb','profile_facebook');

if($session->get('email'))
{
    $email = $session->get('email'); 
    $result = LoginForm::find()->where(['email' => $email])->one();
	 $user = LoginForm::find()->where(['email' => $email])->one();

    $username = $result['username'];
    $lname = $result['lname'];
    $password = $result['password'];
    $con_password = $result['con_password'];
    $birth_date = $result['birth_date'];
    $gender = $result['gender'];
    $photo = $this->context->getimage($user_id,'photo');
}
else{
     $url = Yii::$app->urlManager->createUrl(['site/index']);
            Yii::$app->getResponse()->redirect($url);   
}

$asset = frontend\assets\AppAsset::register($this);

$baseUrl = AppAsset::register($this)->baseUrl;
?>
<script src="<?= $baseUrl?>/js/jquery-1.12.2.min.js"></script>
<script src="<?= $baseUrl?>/js/jquery.cropit.js"></script>


<script>
    $(document).ready(function()
    {
        webcam.set_api_url('upload.php');  
        webcam.set_quality( 90 ); // JPEG quality (1 - 100)
        webcam.set_shutter_sound( true ); // play shutter click sound
        webcam.set_hook( 'onComplete', 'my_completion_handler' );
        document.getElementById("camCode").innerHTML = webcam.get_html(240, 240);
    });
    function webcam111()
    {
        $("#main-picture").hide();
        $("#webcam-picture").show();    
    }
    function hidewebcam()
    {
        $("#main-picture").show();
        $("#webcam-picture").hide();
    }
    function take_snapshot()
    {
		
        // take snapshot and upload to server
        document.getElementById('upload_results').innerHTML = 'Snapshot<br>'+
        '<img src="uploading.gif">';
        webcam.snap();
		
		//e.preventDefault();
		//jq.magnificPopup.close();
    }

    function getDataUri(url, callback) {
	    var image = new Image();
		 image.onload = function () {
	        var canvas = document.createElement('canvas');
	        canvas.width = this.naturalWidth; // or 'width' if you want a special/scaled size
	        canvas.height = this.naturalHeight; // or 'height' if you want a special/scaled size

	        canvas.getContext('2d').drawImage(this, 0, 0);

	        // Get raw image data
	        callback(canvas.toDataURL('image/png').replace(/^data:image\/(png|jpg);base64,/, ''));

	        // ... or get as Data URI
	        callback(canvas.toDataURL('image/png'));
	    };

	    image.src = url;
	}

	function convertDataURLToImageData(dataURL, callback) {
    if (dataURL !== undefined && dataURL !== null) {
        var canvas, context, image;
        canvas = document.createElement('canvas');
        canvas.width = 470;
        canvas.height = 470;
        context = canvas.getContext('2d');
        image = new Image();
        image.addEventListener('load', function(){
            context.drawImage(image, 0, 0, canvas.width, canvas.height);
            callback(context.getImageData(0, 0, canvas.width, canvas.height));
        }, false);
        image.src = dataURL;
    	}
	}
	
	function my_completion_handler(msg)
    {
		
       // extract URL out of PHP output
       if (msg.match(/(http\:\/\/\S+)/))
       {
           var image_url = RegExp.$1;

			getDataUri(image_url, function(dataUri) {

				convertDataURLToImageData(
				    image_url,
				    function(imageData){
				         console.log(imageData);
				    }
				)

				/*

				jq('#profilepreview').cropit({
					exportZoom: 1.00,
					imageBackground: true,
					imageBackgroundBorderWidth: 200,
					imageBackgroundBorderHeight: 200,
					imageState: {
					  src: image_url,
					},
				});

				var imageData = jq('#profilepreview').cropit('export');
				jq('#profilepreview').attr('src', dataUri);
	            jq('.cropit-preview-background').attr('src', dataUri);
	            jq('.cropit-preview-image').attr('src', dataUri);

			
	            webcam.reset();
				//alert(image_url);
				readWebcamImage(image_url);
				*/
			});
       }
       else alert("PHP Error: " + msg);
   }
</script>

<!-- body section -->
<section class="inner-body-content fb-page">
	<div class="fb-innerpage whitebg">
		 <div class="setting-content">	
			<?php include('includes/leftmenus.php');?>
			<div class="fbdetail-holder">
				<!--
				<div class="notice">	
					<div id="suceess" class="form-successmsg">You Have Successfully Completed 1st Step</div>
					<div id="fail" class="form-failuremsg">Oops..!! Somthing Went Wrong in Step 2</div>
					<div id="info" class="form-infomsg">Please Complete Your Signup Steps</div>
				</div>
				<?php
			   
					if($session->get('step')== '1'){
						?>
					<script>$('#suceess').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);</script>
					<?php
				}
				else if($session->get('step')== '10'){
					?>
					<script>$('#info').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);</script>
					<?php
				}
				?>
				-->                
									
				<div class="fb-formholder">		
					
					<div class="fb-formholder-box">
						<div class="formtitle"><h4>Profile Photo</h4></div>
						<ul class="setting-ul">
							<li>
							   <?php $form = ActiveForm::begin(['action' => ['site/profile-picture'],'options' => ['method' => 'post','enctype'=>'multipart/form-data']]) ?>
								<div class="row profpic-settings profilepic-sec">
									<div id="main-picture">
										<div id="profilepreview">
											<div class="col-lg-5 col-md-6 col-sm-12 col-xs-12 form-ppholder">
												
													<div class="cropwin-holder">
														<input type="hidden" name="profileimage" id="profileimage" value="<?= $photo?>">
														<input type="hidden" name="imagevalue" id="imagevalue" value="">
														<div class="crop-section">
															<div class="cropit-preview"></div>
															<input type="range" class="cropit-image-zoom-input">
															<div class="crop-bg"></div>
														</div>
													</div>
												
											</div>
											<div class="col-lg-7 col-md-6 col-sm-12 col-xs-12 form-ppoptions">					
												
												<div class="opt-div">
													
													<p class="grayp">Your photo must be atleast 180x180 pixels with file format of JPEG, GIF or JPG.</p>
																				  
													<div class="fakeFileButton">
														<i class="fa fa-upload"></i>Upload a photo from your computer
														<?php echo $form->field($model,'photo')->fileInput(array('class'=>'cropit-image-input','id'=>'imgInp', 'accept'=>"image/*"))->label(false)?>
														<input type="hidden" name="web_cam_img" id="web_cam_img" />
													</div>	
													<!--<span class="filename">yourfilename.jpg</span>-->						
													
												</div>

												<div class="opt-div">
													
													<div class="fakeFileButton fbicon">
                                                                                                            <a href="<?php echo Yii::$app->request->baseUrl.'?r=site/auth&authclient=facebook' ?>" class="fb-btn"><i class="fa fa-facebook"></i>Use a photo from Facebook</a>
													</div>	
												</div>
												
												<div class="opt-div nbb">
												
													<!--<div class="fakeFileButton">
														<img src="<?= $baseUrl?>/images/webcam.png"/>Upload through webcam
														<input type="button" class="step-submit graybtn" value="Take Photo" onclick="webcam111()">
													</div>	-->
													<a href="#take-photo" class="popup-modal"><img src="<?= $baseUrl?>/images/webcam.png"/>Upload through webcam</a>
												</div>
												
												<div class="note">
													<p>
														<span class="orange-text">Remember:</span>							
													</p>
													<p>
														Your profile photo needs to be clear, your gender must match your photo and cannot contain any nudity. Your photo must be at least 180x180 pixels and with jpeg, gif or jpg format.
													</p>
													
												</div>
																	
											</div>
										</div>
									</div>
									
								</div>
									
								<div class="form-group consider-div">						
									<div class="pull-right fb-btnholder">																		
										<input type="submit" class="btn btn-primary btn-sm" value="Save" onClick="return validate(this.form);">
										<input type="button" class="btn btn-primary btn-sm" value="Cancel" onClick="hidewebcam();">						
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
<div id="take-photo" class="white-popup-block mfp-hide takephoto">
    <div class="modal-title graytitle">       
        <a class="popup-modal-dismiss popup-modal-close" href="#"><i class="fa fa-close"></i></a>
    </div>
    <div class="modal-detail"> 
		<div id="webcam-picture">
			<div class="live-webcam">
				<div id="camCode"></div>
				<input type="button" class="snap" value="SNAP IT" onClick="take_snapshot()">
				
				<div id="upload_results" class="border" style="display:none;">
					Snapshot<br>
					<img src="logo.jpg" />
				</div>
			</div>
			<?php /*
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
			*/ ?>
		</div>
	
	</div>
</div>

<script>
	
	 /*function readURL(input) {
	if (input.files && input.files[0]) {
		var reader = new FileReader();
		
		reader.onload = function (e) {
			$('#cropimg').attr('src', e.target.result);
		}
		
		reader.readAsDataURL(input.files[0]);
	}
}
	
	 $("#imgInp").change(function(){
	readURL(this);
});*/
	</script>

<script language="javascript"> 

   function DoPost(){
       $.post("<?php echo Yii::$app->urlManager->createUrl(['site/logout']); ?>");  
   }
   

</script>

<script>
	
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
							if(this.width < 180 || this.height < 180){
								
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
    

	function readWebcamImage(file) {
           
			/*
		   var _URL = window.URL || window.webkitURL;
		   
            if (file) {
									
                    var reader = new FileReader();

                    reader.onload = function (e) {
                        
                        $('#profilepreview').attr('src', e.target.result);
						 
						var img = new Image();
						img.onload = function() {
							
							var curr_cls='';
							
							//alert(this.width+" - "+this.height);
							/*
							if(this.width < 180 || this.height < 180){
								
								BootstrapDialog.show({
									title: 'Small Image!',
									message: 'Kindly upload the image having size of atleast 180x180 pixels.'
								});
                                                                document.getElementById('imgInp').value = '';
								return false;
							}
							//
							
						};
						img.onerror = function() {
						   alert( "not a valid file: " + file.type);
						};
						img.src = _URL.createObjectURL(file);
                    }

                    reader.readAsDataURL(file);
            }
			*/
        }
    
	
	
	var profileimage = $("#profileimage").val();
	//var jq2 = $.noConflict();
	//var jq = $.noConflict();
        
	jq('#profilepreview').cropit({
		exportZoom: 1.00,
		imageBackground: true,
		imageBackgroundBorderWidth: 20,
		imageBackgroundBorderHeight: 20,
		imageState: {
		  src: profileimage,
		},
	});

 var jq1 = $.noConflict();
 
 jq1(document).ready(function () {
  
        jq1('.popup-modal').magnificPopup({
            type: 'inline',
            preloader: false,
            focus: '#username',
            modal: true
        });
  jq1(document).on('click', '.popup-modal-dismiss', function (e) {
            var getit = $(this).parents(".closepopup");
            if(getit.length > 0)
            {
                confirm_cancel_post();
            }
            else
            {
                e.preventDefault();
                jq1.magnificPopup.close();
            }
        });
 });
</script>
