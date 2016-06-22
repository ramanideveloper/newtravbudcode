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
                             foreach($user_friends as $user_friend)
                             {
//echo '<pre>';print_r($user_friend);die;
                                 ?><a class="profilelink no-ul" href="<?php $id =  $user_friend['userdata']['_id']; echo Url::to(['userwall/index', 'id' => "$id"]); ?>"><?php echo $user_friend['userdata']['username'];?></a><?php if(isset($user_basicinfo['photo'])){?> <div class="tb-img-post" id="temp" name="temp"><img height="200" width="200" src="<?= Yii::$app->getUrlManager()->getBaseUrl() ?><?= '/profile/'.$user_friend['userdata']['photo'] ?>" alt=""></div><?php } ?>
                                     <?php
                             } ?>
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
