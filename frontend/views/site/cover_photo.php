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
    $photo = $result['photo'];
    $cover_photo = $result['cover_photo'];
    $fb_id = $result['fb_id'];
 
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
        
<script>
    $(document).ready(function() {
   
           webcam.set_api_url('upload.php');  
        webcam.set_quality( 90 ); // JPEG quality (1 - 100)
        webcam.set_shutter_sound( true ); // play shutter click sound
        
        webcam.set_hook( 'onComplete', 'my_completion_handler' );
        document.getElementById("camCode").innerHTML = webcam.get_html(320, 240);
        
       
        
       
    
       
    });
        function webcam111(){
        $("#markand").hide();
        $("#manish").show();
        
    }
      function take_snapshot() {
           
            
            // take snapshot and upload to server
            document.getElementById('upload_results').innerHTML = 'Snapshot<br>'+
            '<img src="uploading.gif">';
            webcam.snap();
        }
         function my_completion_handler(msg) {
            
            // extract URL out of PHP output
            if (msg.match(/(http\:\/\/\S+)/)) {
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

<!-- body section -->
<section class="inner-body-content fb-page">
	<div class="fb-innerpage whitebg">
	 
		<?php include('includes/leftmenus.php');?>
		<div class="fbdetail-holder">
			
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
            
			<div class="fb-formholder">		
				

				<h4>Cover Photo</h4>
				<ul class="setting-ul">
					<li>
						<?php $form = ActiveForm::begin(['action' => ['site/cover-photo'],'options' => ['method' => 'post','enctype'=>'multipart/form-data']]) ?>
						
                    <div class="row profpic-settings profilepic-sec" id="markand">
				
				<div class="col-lg-5 col-md-6 col-sm-12 col-xs-12 form-ppholder">
					<div class="row">						
						<div class="col-lg-7 col-md-7 col-sm-4 col-xs-12 form-ppholder">
							<div class="crop-holder">

								<?php
								  if(isset($cover_photo) && !empty($cover_photo)){
								  ?>
									<img id="cropimg" src="profile/<?= $cover_photo;?>" alt="your image" class="previewimg"/>
								  <?php
								  }
														 
								  else{
								  ?>
									<img id="cropimg" src="profile/<?= $gender?>.jpg" alt="your image" class="previewimg"/>
								  <?php
								  }
								?>
								
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-7 col-md-6 col-sm-12 col-xs-12 form-ppoptions">
						
						<div class="opt-div">
							<div class="fakeFileButton">
								<i class="fa fa-upload"></i>Upload a photo from your computer
								<?= $form->field($model,'cover_photo')->fileInput(array('class'=>'form-control','id'=>'imgInp', 'accept'=>"image/*"))->label(false)?>	
                                                                <input type="hidden" name="web_cam_img" id="web_cam_img" />
							</div>	
							
							<p class="grayp">Your photo must be atleast 180x180 pixels with file format of JPEG, GIF or JPG.							
						</div>

						<div class="opt-div">
						
							<div class="fakeFileButton fbicon">
								<i class="fa fa-facebook"></i>Use a photo from Facebook							
							</div>	
						</div>
							
						<div class="opt-div">
					
							<div class="fakeFileButton">
								<img src="<?= $baseUrl?>/images/webcam.png"/>Take Photo
								<input type="button" class="step-submit graybtn" value="Take Photo" onclick="webcam111()">
							</div>	
						</div>
												
						<div class="note">
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
						</div>
					
				</div>
                                                           

			</div>
			<div class="col-lg-9 col-md-8 col-sm-9 col-xs-12" id="manish" style="display: none">

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
					
			<div class="form-group consider-div">						
				<div class="pull-right fb-btnholder">																		
					<input type="submit" class="btn btn-primary btn-sm" value="Save" onClick="return validate(this.form);">
					<input type="button" class="btn btn-primary btn-sm" value="Cancel">						
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

        <?php include('includes/footer.php');?>

<script>
	
	 function readURL(input) {
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
});
	</script>

<script language="javascript"> 

   function DoPost(){
       $.post("<?php echo Yii::$app->urlManager->createUrl(['site/logout']); ?>");  
   }
   

</script>

<style>
    body{
        background-color:#f2f2f2;
    }
</style>