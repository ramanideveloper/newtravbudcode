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
                              
                                     
	<div class="fb-innerpage whitebg">
	 
		<?php //include('includes/leftmenus.php');?>
		<div class="fbdetail-holder">
			    
			<div class="fb-formholder">		
				
				<h4>Saved Posts</h4>
				
				<div class="notice">	
                                    <div id="suceess" class="form-successmsg">Saved List Updated Successfully.</div>
                                    <div id="fail" class="form-failuremsg">Oops..!! Something went wrong. Please try later.</div>
				</div>
				
				<div class="bmcontent" id="bmcontent">
                                    <?php 
                                    if(!empty($savedposts)){
                                    foreach($savedposts as $savedpost){
                                       $postid = $savedpost['post_id'];
                                        $userid = $savedpost['user_id'];
                                        $posttype = $savedpost['post_type'];
                                        $post_user_id = $savedpost['userDetail']['_id'];
                                        $userfname = $savedpost['userDetail']['fname'];
                                        $userlname = $savedpost['userDetail']['lname'];
                                        $name = $userfname." ".$userlname;
                                        $userphoto = $savedpost['userDetail']['photo'];
                                        $save_value = 'Saved from '.$name.'\'s post';
                                    ?>
				<ul class="setting-ul bookmarks">
					<li>
                        <?php $form = ActiveForm::begin(['id' => 'frmm-name','options'=>['onsubmit'=>'return false;',],]); ?> 
						<div class="setting-group">							
							<div class="normal-mode">
								<div class="row">
									<div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">
										<label><img alt="user-photo" class="img-responsive" src="<?php if(isset($savedpost['userDetail']['fb_id']) && !empty($savedpost['userDetail']['photo'])){echo $savedpost['userDetail']['photo'];
 }
else if(isset($savedpost['userDetail']['photo']) && !empty($savedpost['userDetail']['photo'])){
echo 'profile/'.$savedpost['userDetail']['photo'];
}
else{

echo 'profile/'.$savedpost['userDetail']['gender'].'.jpg';
} ?>"></label>
									</div>
									<div class="col-lg-8 col-md-7 col-sm-7 col-xs-12">										
										<div class="info">																   				   								
											<label><?= $save_value ?></label>
                                                                                        
										</div>
									</div>
									<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">										
										<div class="pull-right linkholder">
                                                                                    <a href="javascript:void(0)" onclick='window.open("<?php echo Yii::$app->urlManager->createUrl(['site/shareoption']);?>&postid=<?php echo $postid?>","MyNewWindow")'>Share</a>
                                                                                     | <a href="javascript:void(0)" onClick="save_post('<?php echo $postid?>','<?php echo $posttype?>')">Unsaved</a>
										</div>
									</div>
									<div class="clear"></div>
								</div>	
							</div>	
						</div>	
                        <?php ActiveForm::end() ?>
					</li>
                                       
				</ul>
													
                                    <?php } }else {?>
                                    <span class="nopost">No Saved Posts</span>
                                        <?php } ?>
                            </div>
			</div>
		</div>
	</div>
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

