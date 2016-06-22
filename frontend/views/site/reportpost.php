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
    <div class="tb-panel-box postbox">
        <input type="hidden" value="<?php echo $postid?>" id="pid" name="pid"/>
        <div class="tb-panel-body01" style="height:400px;width: 500px;margin: auto;overflow: scroll">
          <div id="report_success" class="form-successmsg">This post has been reported now to user.</div>
          <div id="report_fail" class="form-failuremsg">Oops..!! Something went wrong. Please share later.</div>
          <div class="posttext">
              <textarea id="desc" name="desc" placeholder="Reason for reporting this post..." rows="2" cols="62" style="border:none;resize: none;"></textarea>
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
            <button class="btn btn-primary btn-sm" onclick="reportpost();" type="button" name="post" id="post">Report</button>
        </div>
      </div>
    </div>
</div>
<script type="text/javascript">
function reportpost()
{
    var cnfrm_rspnse = confirm("Are you sure to report to the user ?");
    if(cnfrm_rspnse)
    {
        var pid = $("#pid").val();
        var desc = $("#desc").val();
        $.ajax({
            type: 'POST',
            url: '<?php echo Yii::$app->urlManager->createUrl(['site/reportpost']); ?>',
            data: "pid=" + pid +"&desc=" + desc,
            success: function(data){
                //alert(data);return false;
                 if(data)
                 {
                      $('#report_success').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
                 }
                 else
                 {
                     $('#report_fail').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
                 }
             }

        });
    }
}
</script>