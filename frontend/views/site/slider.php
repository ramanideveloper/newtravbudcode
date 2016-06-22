<?php

/* @var $this \yii\web\View */
/* @var $content string */
include('includes/header.php');
use yii\helpers\Html;
use frontend\assets\AppAsset; 

use yii\widgets\ActiveForm;
use yii\mongodb\ActiveRecord;

use frontend\models\LoginForm;
use frontend\models\Slider;
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

<style>
.deleteimg{width: 12%; float: left;}
.fa-close{cursor: pointer;}
</style>

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
						<div class="formtitle"><h4>Slider Photo</h4></div>
						<ul class="setting-ul">
							<li>
							   <?php $form = ActiveForm::begin(['action' => ['site/slider'],'options' => ['method' => 'post','enctype'=>'multipart/form-data']]) ?>
								<div class="row profpic-settings profilepic-sec">
									<div id="main-picture">
										<div id="profilepreview">
										
											<div class="col-lg-7 col-md-6 col-sm-12 col-xs-12 form-ppoptions">					
												
												<div class="opt-div">
																		  
													<div class="fakeFileButton">
														<i class="fa fa-upload"></i>Upload a photo for Slider
														<?php echo $form->field($model,'slider_image[]')->fileInput(array('class'=>'cropit-image-input','id'=>'slider_image', 'accept'=>"image/*",'multiple'=>'multiple'))->label(false)?>
														
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
										<input type="button" class="btn btn-primary btn-sm" value="Save" id="uploadsave">
										<input type="button" class="btn btn-primary btn-sm" value="Cancel" onClick="hidewebcam();">						
									</div>										
								</div>
					
								<?php ActiveForm::end() ?>
	
								
													   
							</li>
							
						</ul>
						
							<div style="clear: both;"></div>
							<div class="form-group consider-div slider_image">
							</div>
													
					</div>
				
				</div>
				
			</div>
		 </div>
		 
		 
		
	</div>
	

	
	<!-- START Repace Slider Image Popup -->
		<div id="take-photo" class="white-popup-block mfp-hide takephoto" data-myval="">
			<div class="modal-title graytitle">       
				<a class="popup-modal-dismiss popup-modal-close" href="#"><i class="fa fa-close"></i></a>
			</div>
			<div class="modal-detail"> 
				<?php $form = ActiveForm::begin(['action' => ['site/slider'],'options' => ['method' => 'post','enctype'=>'multipart/form-data']]) ?>
				<div class="row profpic-settings profilepic-sec">
					<div id="main-picture">
						<div id="profilepreview">
							<div class="col-lg-7 col-md-6 col-sm-12 col-xs-12 form-ppoptions">					
								<div class="opt-div">
									<div class="fakeFileButton">
										<i class="fa fa-upload"></i>Upload a photo for Slider
										<?php echo $form->field($model,'slider_image[]')->fileInput(array('class'=>'cropit-image-input','id'=>'slider_image', 'accept'=>"image/*",'multiple'=>'multiple'))->label(false)?>
										<input type="hidden" name="load" value"edit">
										<input type="hidden" id="getreplaceboxidinput">
										<input type="hidden" id="parendiv">
									</div>	
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="form-group consider-div">						
					<div class="pull-right fb-btnholder">																		
						<input type="button" class="btn btn-primary btn-sm" value="Save" id="replaceslider">
						<input type="button" class="btn btn-primary btn-sm" value="Cancel" onClick="resetALL();">						
					</div>										
				</div>
				<?php ActiveForm::end() ?>
			</div>
		</div>
	<!-- END Repace Slider Image Popup -->

<script>
$(document.body).on("click", ".replaceimage", function(e) {
	var name = $(this).closest("div").attr("id");
	var getsequence = $(this).data("getsequence");
	if(name != '') {
		data = name+'|||'+getsequence;
		$('#parendiv').val(''); //setter
		$('#parendiv').val(data); //setter
	}
});


$(document.body).on("click", "#replaceslider", function() {
  replaceSlider();
});

$(document.body).on("click", "#uploadsave", function() {
  upload_slider(false);
});

$( window ).load(function() {
  upload_slider(true);
});


function getreplaceboxid(v) {
	if(v != '') {
		$("#getreplaceboxidinput").val(v);
	}
}
k=1;
function upload_slider(t) {
	
	var data = [];
	var data = new FormData();
    var filedata = document.getElementById("slider_image");
    var i = 0, len = filedata.files.length, img, reader, file;
    for (; i < len; i++) {
        file = filedata.files[i];
        data.append("slider_image[]", file);
    }

	if(t==true){
		data.append("load", 1); 

		
	} else {
		data.append("load", 2); 
	}
}	

function replaceSlider(){
	var data = [];
	var data = new FormData();
    var filedata = document.getElementById("slider_image");
    file = filedata.files[0];
    $("#slider_image").val('');
    if (file != '') {
        data.append("slider_image[]", file);
       	data.append("load","edit");
       	oimg = $("#getreplaceboxidinput").val();
       	data.append("oimg", oimg);
		$.ajax({
			url: '<?php echo Yii::$app->urlManager->createUrl(['site/slider']); ?>',  //Server script to process data
			type: 'POST',
			data: data,
			async:false,        
			processData: false,
			contentType: false,
				
			success: function(data) {
				var result = $.parseJSON(data);
				$.each(result[0], function(key, value){
					parentsdiv = $("#parendiv").val();
					parentsdiv = parentsdiv.toString().split("|||");
					$("#parentsdiv").html();
					replacesrc = value.replace(/\.[^/.]+$/, "");
					$("#"+parentsdiv[0]).html('<a href="javascript:void(0)" class="listalbum-box"><img style="height: 100px; width: 100px;" src="uploads/slider/'+value+'" alt="" id="'+replacesrc+'"></a><a href="#take-photo" onclick="getreplaceboxid(\''+value+'\')" class="popup-modal"><i class="fa fa-pencil replaceimage" data-getsequence='+parentsdiv[1]+'></i></a>&nbsp;&nbsp;&nbsp;<i class="fa fa-close" onclick="delslider(\''+replacesrc+'\', \''+parentsdiv[1]+'\')"></i></a>');
					k++;
				});
				resetALL();
			},
			 error: function(res) {
             }  
		});
	}
}

function upload_slider(t){
	
	var data = [];
	var data = new FormData();
    var filedata = document.getElementById("slider_image");
    var i = 0, len = filedata.files.length, img, reader, file;
    for (; i < len; i++) {
        file = filedata.files[i];
        data.append("slider_image[]", file);
    }

	if(t==true){
		data.append("load", 1); 

		
	} else {
		data.append("load", 2); 
	}	

	$.ajax({
		url: '<?php echo Yii::$app->urlManager->createUrl(['site/slider']); ?>',  //Server script to process data
		type: 'POST',
		data: data,
		async:false,        
		processData: false,
		contentType: false,
		success: function(data) {
			var result = $.parseJSON(data);
			k=1;
			$.each(result, function(index, v){
				replacesrc = v.replace(/\.[^/.]+$/, "");
				$(".slider_image").append('<div class="deleteimg" id="deleteimg'+k+'"><a href="javascript:void(0)" class="listalbum-box"><img style="height: 100px; width: 100px;" src="uploads/slider/'+v+'" alt="" id="'+replacesrc+'"></a><a href="#take-photo" onclick="getreplaceboxid(\''+v+'\')" class="popup-modal"><i class="fa fa-pencil replaceimage" data-getsequence='+k+'></i></a>&nbsp;&nbsp;&nbsp;<i class="fa fa-close" onclick="delslider(\''+v+'\', \''+k+'\')"></i></a></div>');
				k++;
			});
			resetALL();
			
						
		},
		 error: function(res) {
         }  
	});
	
}

 function delslider(v, k)
 {
    	console.log(v);
    	console.log(k);

        BootstrapDialog.show({
			size: BootstrapDialog.SIZE_SMALL,
            title: 'Delete Image',
            message: 'Are you sure to delete this image ?',
            buttons: [{
                        label: 'Yes',
                        action: function (dialogItself) {
                            if (v != '') {
                                $.ajax({
                                    type: 'GET',
                                    //url: '/travbudcode/frontend/web/index.php?r=userwall%2Fdelete-image',
                                    url: '<?php echo Yii::$app->urlManager->createUrl(['site/slider']); ?>',
                                    data: 'nm='+v,
                                    async:false,        
									processData: false,
									contentType: false,
                                    success: function (data)
                                    {
                                    	console.log(data);
                                    	if(data == "OK"){
											$("#deleteimg"+k).remove();
										}	
											dialogItself.close();
                                    }
                                });
                            }
                        }
                    }, {
                        label: 'No',
                        action: function (dialogItself)
                        {
                            dialogItself.close();
                        }
                    }]
        });
    }


function resetALL(){
	$("#slider_image").val('');
	$("#imgInp").val('');
	newct = 0;
	lastModified = [];
	storedFiles = [];
	storedFiles.length = 0;
	closepopup();
}

	
	/*
	function deleteimagename(name){
		var name = $(this).data("data-deleteimagename");
		alert(nmae)
		if(name != '') {
			$.ajax({
			url: '<?php echo Yii::$app->urlManager->createUrl(['site/slider']); ?>',  //Server script to process data
			type: 'POST',
			data:formdata,
			async:false,        
			processData: false,
			contentType: false,
			success: function(data) {
				
			}
		});
	}
	}
	
	
	
	
	*/

	</script>

<script language="javascript"> 

   function DoPost(){
       $.post("<?php echo Yii::$app->urlManager->createUrl(['site/logout']); ?>");  
   }
   

</script>
<script>
 var jq = $.noConflict();

 jq(document.body).on("click", ".popup-modal", function () {
  
        jq('.popup-modal').magnificPopup({
            type: 'inline',
            preloader: false,
            focus: '#username',
            modal: true
        });
 
 });
  jq(document.body).on('click', '.popup-modal-dismiss', function () {
           	closepopup();
        });

  function closepopup() {
  	 var getit = $(this).parents(".closepopup");
            if(getit.length > 0)
            {
                confirm_cancel_post();
            }
            else
            {
                jq.magnificPopup.close();
            }
  }
 
</script>
        <?php include('includes/footer.php');?>

