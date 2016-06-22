<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use frontend\assets\AppAsset;

use yii\widgets\ActiveForm;
use yii\mongodb\ActiveRecord;

use frontend\models\LoginForm;
use frontend\models\PostForm;
use yii\validators\Validator;
use yii\helpers\Url;
$asset = frontend\assets\AppAsset::register($this);

$baseUrl = AppAsset::register($this)->baseUrl;
$postid = $_GET['postid'];
$postresult = PostForm::find()->where(['_id' => $postid])->one();
//echo '<pre>';print_r($postresult);exit;
?>

<div id="post-status-list">
<?php
    $time = Yii::$app->EphocTime->time_elapsed_A(time(),$postresult['post_created_date']);
    $post_privacy = $postresult['post_privacy'];
    if($post_privacy == 'Private') {$post_class = 'lock';}
    else if($post_privacy == 'Friends') {$post_class = 'user';}
    else {$post_privacy = 'Public'; $post_class = 'globe';}
?>
     <?php $form = ActiveForm::begin(['action' => ['site/editpost'],'options' => ['method' => 'post','enctype' => 'multipart/form-data']]) ?>
   <!-- <form id='edit_post' action="" method="post" enctype="multipart/form-data">-->
    <div class="tb-panel-box postbox">
        <input type="hidden" value="<?php echo $postid?>" id="pid" name="pid"/>
        <div class="tb-panel-body01" style="height:400px;width: 500px;margin: auto;overflow: scroll">
          <div id="share_success" class="form-successmsg">This Post has been shared now.</div>
          <div id="share_fail" class="form-failuremsg">Oops..!! Something went wrong. Please share later.</div>
          <!--<select id="test" name="form_select" onchange="showDiv(this)">
            <option value="0">Share on your own timeline</option>
            <option value ="1">Share on a friend's timeline</option>
         </select>
          <div class="frmSearch" id="friendlist" style="display:none">
            Friend:<input type="text" id="frnduname" placeholder="Friend's Name" />
            <div id="suggesstion-box"></div>
         </div>-->
          <div class="posttext">
              <textarea id="desc" name="desc" placeholder="Say Something about this..." rows="2" cols="62" style="border:none;resize: none;"></textarea>
          </div>
          <div id="image-holder"></div>
           <div class="posttext">
              <input type="file" id="imageFile1" name="img[]" multiple="true"/>
          </div>
        <div class="tb-panel-head clearfix">
            
            <div class="tb-user-box"> <a class="profilelink no-ul" href="<?php $id =  $postresult['user']['_id']; echo Url::to(['userwall/index', 'id' => "$id"]); ?>"><img alt="user-photo" class="img-responsive" src="<?php if($postresult['user']['fb_id'] == ''  && $postresult['user']['photo'] == ''){ echo 'profile/'.$postresult['user']['gender'].'.jpg'; }else if($postresult['user']['fb_id'] == ''){ echo 'profile/'.$postresult['user']['photo']; }else{echo $postresult['user']['photo']; } ?>"> </a></div>
            <div class="tb-user-desc"> <span><a class="profilelink no-ul" href="<?php $id =  $postresult['user']['_id']; echo Url::to(['userwall/index', 'id' => "$id"]); ?>"><?php echo $postresult['user']['fname'].' '.$postresult['user']['lname'] ;?></a></span><span><?php echo $time; ?> <i class="glyphicon glyphicon-<?= $post_class?> "></i></span> </div>
        </div>
        <?php
       if(isset($postresult['image']) && !empty($postresult['image'])){
        $eximgs = explode(',',$postresult['image'],-1);
        }
        ?>
        <?php if($postresult['post_type'] == 'image') { ?>
            <div class="tb-img-post" id="temp" name="temp"><?php foreach ($eximgs as $eximg) {?><img src="<?= Yii::$app->getUrlManager()->getBaseUrl() ?><?= $eximg ?>" style="height:50px;width:90px;margin-left:5px;" alt=""><?php }?></div>
          <?php }  else if($postresult['post_type'] == 'text') { ?>
            <div class="tb-text-post" id="temp" name="temp" ><?= $postresult['post_text'] ?></div>
           <?php } else if($postresult['post_type'] == 'link') { ?>
            <a href="<?= $postresult['post_text']?>" target="_blank"><div class="tb-img-post" id="temp" name="temp"><img src="<?= $postresult['image'] ?>" alt=""></div>
            <div class="tb-text-post" id="temp" name="temp" ><?= $postresult['link_title'] ?></div>
            <div class="tb-text-post" id="temp" name="temp" ><?= $postresult['link_description'] ?></div></a>
          <?php } 
         else if($postresult['post_type'] == 'text and image') { ?>
            <div class="tb-text-post" id="temp" name="temp" ><?= $postresult['post_text'] ?></div>
           <div class="tb-img-post" id="temp" name="temp"><?php foreach ($eximgs as $eximg) {?><img src="<?= Yii::$app->getUrlManager()->getBaseUrl() ?><?= $eximg ?>" style="height:50px;width:90px;margin-left:5px;" alt=""><?php }?></div>

          <?php } ?>
            <div class="tb-panel-head clearfix">
            <button class="btn btn-primary btn-sm" type="button" name="post">Cancel</button>
            
            <input class="btn btn-primary btn-sm" type = "submit" name="submit" value="save"> 
        </div>
      </div>
    </div>
    <!--</form>-->
     <?php ActiveForm::end() ?>
</div>
<script type="text/javascript">
   /* add by markand 
    function editpost(){
        
        $.ajax({
        type: "POST",
        url: "<?php echo \Yii::$app->getUrlManager()->createUrl('site/editpost') ?>",
        data: $('#edit_post').serialize(),
        success: function(data){
            alert(data);return false;
        }
        });
        
    } */

// AJAX call for autocomplete 
$(document).ready(function(){
	$("#frnduname").keyup(function(){
		
	});
});
//To select country name
function selectName(val)
{
    $("#frnduname").val(val);
    $("#suggesstion-box").hide();
}
$("#imageFile1").on('change', function () {

     //Get count of selected files
     var countFiles = $(this)[0].files.length;

     var imgPath = $(this)[0].value;
     var extn = imgPath.substring(imgPath.lastIndexOf('.') + 1).toLowerCase();
     var image_holder = $("#image-holder");
     image_holder.empty();

     if (extn == "gif" || extn == "png" || extn == "jpg" || extn == "jpeg") {
         if (typeof (FileReader) != "undefined") {

             //loop for each file selected for uploaded.
             for (var i = 0; i < countFiles; i++) {

                 var reader = new FileReader();
                 reader.onload = function (e) {
                     $("<img />", {
                         "src": e.target.result,
                             "class": "thumb-image"
                     }).appendTo(image_holder);
                 }

                 image_holder.show();
                 reader.readAsDataURL($(this)[0].files[i]);
             }

         } else {
             alert("This browser does not support FileReader.");
         }
     } else {
         alert("Please select only images");
     }
 });
</script>
<style>
    .thumb-image {
    float:left;
    width:50px;
    height:50px;
    position:relative;
    padding:5px;
}
</style>