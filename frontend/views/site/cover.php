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
						<div class="formtitle"><h4>Cover Photo</h4></div>
						<ul class="setting-ul">
							<li>
							   <?php $form = ActiveForm::begin(['action' => ['site/cover'],'options' => ['method' => 'post','enctype'=>'multipart/form-data']]) ?>
								<div class="row profpic-settings profilepic-sec">
									<div id="main-picture">
										<div id="profilepreview">
										
											<div class="col-lg-7 col-md-6 col-sm-12 col-xs-12 form-ppoptions">					
												
												<div class="opt-div">
																		  
													<div class="fakeFileButton">
														<i class="fa fa-upload"></i>Upload a photo for Cover
														<?php echo $form->field($model,'cover_image[]')->fileInput(array('class'=>'cropit-image-input','id'=>'imgInp', 'accept'=>"image/*",'multiple'=>'multiple'))->label(false)?>
														
														<input type="hidden" name="load" id="load">
														
													</div>	
													<!--<span class="filename">yourfilename.jpg</span>-->						
													
												</div>
					
											</div>
										</div>
									</div>
									
								</div>
									
								<div class="form-group consider-div">						
									<div class="pull-right fb-btnholder">																		
										<input type="button" class="btn btn-primary btn-sm" value="Save" onClick="upload_cover();">
										<input type="button" class="btn btn-primary btn-sm" value="Cancel" onClick="hidewebcam();">						
									</div>										
								</div>
					
								<?php ActiveForm::end() ?>
													   
							</li>
							
						</ul>
						<div style="clear: both;"></div>
							<div class="form-group consider-div cover_image">
							</div>
													
					</div>
				</div>
			</div>
		 </div>
		
	</div>

        <?php include('includes/footer.php');?>


<script>

$( window ).load(function() {
	upload_cover(true);
});

	function upload_cover(t){
		
		$("#load").val('');
		formdata = new FormData($('form')[1]);
		
		for(var i=0, len=storedFiles.length; i<len; i++) {
            formdata.append('cover_image[]', storedFiles[i]); 
        }
		if(t==true){
			formdata.append('load', 1); 
			
		} else {
			formdata.append('load', 2); 
		}
		
		$.ajax({
			url: '<?php echo Yii::$app->urlManager->createUrl(['site/cover']); ?>',  //Server script to process data
			type: 'POST',
			data:formdata,
			async:false,        
			processData: false,
			contentType: false,
			success: function(data) {
				var result = $.parseJSON(data);
				$.each(result, function(i, v){
					$(".cover_image").append('<a href="javascript:void(0)" class="listalbum-box"><img style="height: 100px; width: 100px;" src="/travbudcode/frontend/web/uploads/cover/'+v+'" alt=""></a><a href="javascript:void(0)" class="edit-album"><i class="fa fa-pencil"></i></a>');
				});
				
				$("#imgInp").val('');
				newct = 0;
				lastModified = [];
				storedFiles = [];
				storedFiles.length = 0;
							
			}
		});
		
	}
	
	
	</script>

<script language="javascript"> 

   function DoPost(){
       $.post("<?php echo Yii::$app->urlManager->createUrl(['site/logout']); ?>");  
   }
   

</script>


