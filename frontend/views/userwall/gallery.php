


<?php
include('includes/header-wall.php');
use yii\widgets\ActiveForm;
use frontend\models\Friend;
use yii\helpers\Url;
use frontend\models\PostForm;
 $session = Yii::$app->session;
 $user_id = $session->get('user_id');  

$posts = PostForm::getUserPost($user_id);

?>
<div class="container-fluid clearfix inner-fix01 userwall">
     <div class="row fb-pagecontent sm-fix">
		 <div  class="user_data">
                     <div class="col-lg-8 col-md-8 col-sm-8 secondcol data-section-about">
				
                       
			<div class="friends">
                              
                                        <?php 
                                        foreach($gallery as $gallery_item)
                             {
                                 if(!empty($gallery_item['image'])) {
                                 ?> 
                                 <?php if(!empty($gallery_item['image']) && empty($gallery_item['post_text'])) { ?>
                    <div class="tb-img-post" id="temp" name="temp"><img src="<?= Yii::$app->getUrlManager()->getBaseUrl() ?><?= $gallery_item['image'] ?>" alt=""></div>
                  <?php } ?>                  
                    <?php if(!empty($gallery_item['image']) && !empty($gallery_item['post_text'])) { ?>
                    <a href="<?= $gallery_item['post_text']?>" target="_blank"><div class="tb-img-post" id="temp" name="temp"><img src="<?= $gallery_item['image'] ?>" alt=""></div></a>
                    <?php } //image condition end?>
                         <?php if($gallery_item['post_type'] == 'profilepic')  { ?>
                    <a href="<?= $gallery_item['post_text']?>" target="_blank"><div class="tb-img-post" id="temp" name="temp"><img src="<?= 'profile/'.$gallery_item['image'] ?>" alt=""></div></a>
                    <?php } //image condition end?>
                    <?php
                             } //gallery condiiton end
                             } //foreach end
                           ?>
                         </div>	 
			 </div>
                     

                             
                     <?php include('includes/people_you_may_know.php');?>
		 
		<!--Right Part End -->
		
		 </div>  
	</div>
	<?php include('includes/chat_section.php');?>
</div>
<!-- Left Navigation and Footer -->   
 <?php 
    include('includes/left-menu.php');
    include('includes/footer.php');
?>



















