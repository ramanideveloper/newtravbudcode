<?php
include('includes/header.php');
use yii\widgets\ActiveForm;
use frontend\models\Friend;
use yii\helpers\Url;
use frontend\models\PostForm;
use frontend\models\ProfileVisitor;
use frontend\models\UserForm;
use yii\helpers\ArrayHelper;
$session = Yii::$app->session;
$user_id = (string) $session->get('user_id');  
 
$model_profile = new ProfileVisitor();
$profile_lists = ProfileVisitor::getAllProfileVisitors($user_id);
$frnd_list = ArrayHelper::map(Friend::find()->where(['status' => '1'])->all(), 'to_id', 'to_id');

?>
<div class="inner-body-content fb-page">
	<div class="page-wrapper">
	<div class="container-fluid clearfix inner-fix01 userwall">
			 
		<div class="left-container">
			 <div class="row fb-pagecontent sm-fix">
				 
						   <div class="col-lg-8 col-md-8 col-sm-8 secondcol">
						
					  
					  <input type="hidden" name="login_id" id="login_id" value="<?php echo $session->get('user_id');?>">
					  <div class="tb-panel-box  panel-shadow">				
						<div class="tb-inner-title01 section-title">Profile Visitors</div>
						<div class="ppl-box pad-box">
						
						 <?php 
							if(!empty($profile_lists) && count($profile_lists)>0){?><ul><?php
						 foreach($profile_lists as $profile_list){
								
							//echo '<pre>';print_r($profile_list);exit;
								  $ctr = $model_friend->mutualfriendcount($profile_list['_id']);
						
	?><li>
							<?php $form = ActiveForm::begin(
									[
										'id' => 'add_friend',
										'options'=>[
											'onsubmit'=>'return false;',
										],
									]
				); ?>
					  <div class="tb-pyk-mainbox clearfix" id='remove_<?php echo $profile_list['_id'];?>'>
						<div class="tb-pyk-userimg">
						<?php 
                                                $model_user = new UserForm();
                                                $profile_list = UserForm::find()->where(['_id' => $profile_list['visitor_id']])->one();
                                                $dp = $this->context->getimage($profile_list['_id'],'photo');
		?>
						   <img src="<?= $dp?>" alt="" class="img-responsive">
						</div>
						  <input type="hidden" name="to_id" id="to_id" value="<?php echo $profile_list['_id'];?>">
						  
						  <div class="tb-pyk-user-text"> <span><a href="<?php $id = $profile_list['_id']; echo Url::to(['userwall/index', 'id' => "$id"]); ?>"><?php echo $profile_list['fname']." ".$profile_list['lname'];?></a></span> <span><?php if($ctr > 0){?><?php echo $ctr;?> Mutule Friends<?php }else{echo "No Mutual Friend";} ?></span> 
						<?php 
						if(!($user_id == $profile_list['_id'])){
                                                    
                                                       if(in_array($profile_list['_id'], $frnd_list)) {
                                                        ?>
                                                      <button class="btn btn-primary btn-sm bt-cfix" ><i class="fa fa-user"></i>Already Friend</button>
                                                      <?php
                                                    } else {
                                                        ?>
                                                        <button class="btn btn-primary btn-sm bt-cfix" id="people_<?php echo $profile_list['_id'];?>"  onclick="addfriend('<?php echo $profile_list['_id'];?>')"><i class="fa fa-plus"></i>Add Friend</button>
                                                <span id="sendmsg_<?php echo $profile_list['_id'];?>" style="display:none" class="request-sent"></span>
                                                <?php
                                                    }
                                                 
                                                    
                                                    

						?>	
                                                
						<?php 
                                                }
?>
<a href="javascript:void(0)" onclick="remove_tobe_friend_listing('<?php echo $profile_list['_id'];?>')" class="tb-pyk-remove"><span class="glyphicon glyphicon-remove"></span></a> </div>
						
					  </div>
		 <?php ActiveForm::end() ?>
	</li>
					<?php }
					?></ul><?php }?>
						</div>
					  </div>
					 
					
					 </div>
					  <?php include('includes/add_friend_email.php');?>
						  
				<!--Right Part End -->
				
				
			</div>
		</div>
			
		<?php include('includes/chat_section.php');?>		
	</div>
	<!-- Left Navigation and Footer -->   
	<?php include('includes/left-menu.php'); ?>
    <?php     
		include('includes/footer.php');
	?>
	</div>
</div>