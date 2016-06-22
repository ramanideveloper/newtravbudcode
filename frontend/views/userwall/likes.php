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
				
                       
			<div class="likes">
							<?php 
								if(count($likes)>0){
									$lctr = 0;
							?>
								<div class="likes-row">
									<?php 
										foreach($likes as $like){
											if($lctr < 14) {
									?>
											<div class="likes-col">
												<div class="user-likebox">
													<a href="javascript:void(0);">
														<span class="like-img">
															<img height="200" width="200" src="<?= Yii::$app->getUrlManager()->getBaseUrl() ?><?= $like['post']['image'] ?>" alt=""/>
														</span>
														<span class="caption"><?php if(!empty($like['post']['post_text'])){ echo $like['post']['post_text'];}else {echo 'Like Caption';}?></span>
													</a>
												</div>
											</div>
									<?php
											$lctr++;
											}
										}
									?>
								</div>
							<?php
								}
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

