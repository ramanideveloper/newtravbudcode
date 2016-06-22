<?php
include('includes/header.php');
use yii\widgets\ActiveForm;
use frontend\models\Friend;
use frontend\models\SecuritySetting;
use yii\helpers\Url;
use frontend\models\PostForm;
 $session = Yii::$app->session;
 $user_id = $session->get('user_id');  

$posts = PostForm::getUserPost($user_id);

?>
<div class="inner-body-content fb-page">
	<div class="page-wrapper">
	<div class="container-fluid clearfix inner-fix01 userwall">
			 
		<div class="left-container">
			 <div class="row fb-pagecontent sm-fix">
				 
						   <div class="col-lg-8 col-md-8 col-sm-8 secondcol">
						
					  
					  <input type="hidden" name="login_id" id="login_id" value="<?php echo $session->get('user_id');?>">
					  <div class="tb-panel-box  panel-shadow">				
						<div class="tb-inner-title01 section-title">People you may know</div>
						<div class="ppl-box pad-box">
						
						 <?php 
							if(!empty($friends) && count($friends)>0){?><ul><?php
						 foreach($friends as $friend){
								
							
							 $requestexists = $model_friend->requestexists($friend['id']);
							 $alreadysend = $model_friend->requestalreadysend($friend['id']);
							
							 if( $requestexists < 1 && $alreadysend <1)
								 {
								  $ctr = $model_friend->mutualfriendcount($friend['id']);
						
	?><li>
							<?php $form = ActiveForm::begin(
									[
										'id' => 'add_friend',
										'options'=>[
											'onsubmit'=>'return false;',
										],
									]
				); ?>
					  <div class="tb-pyk-mainbox clearfix" id='remove_<?php echo $friend['id'];?>'>
						<div class="tb-pyk-userimg">
						<?php 
						$dp = $this->context->getimage($friend['_id'],'photo');
		?>
						   <img src="<?= $dp?>" alt="" class="img-responsive">
						</div>
						  <input type="hidden" name="to_id" id="to_id" value="<?php echo $friend['id'];?>">
						  
						  <div class="tb-pyk-user-text"> <span><a href="<?php $id = $friend['id']; echo Url::to(['userwall/index', 'id' => "$id"]); ?>"><?php echo $friend['fname']." ".$friend['lname'];?></a></span> <span><?php if($ctr > 0){?><?php echo $ctr;?> Mutule Friends<?php }else{echo "No Mutual Friend";} ?></span> 
                                                    <?php 
                                                    $result_security = SecuritySetting::find()->where(['user_id' => "$id"])->one();
                                                    if ($result_security)
                                                    {
                                                        $request_setting = $result_security['friend_request'];
                                                    }
                                                    else
                                                    {
                                                        $request_setting = 'Public';
                                                    }
                                                    if(($request_setting == 'Public') || ($request_setting == 'Friends of Friends' && $ctr > 0)){ ?>
                                                    <button class="btn btn-primary btn-sm bt-cfix" id="people_<?php echo $friend['id'];?>"  onclick="addfriend('<?php echo $friend['id'];?>')">
                                                    <i class="fa fa-plus"></i>Add Friend</button>
                                                    <span id="sendmsg_<?php echo $friend['id'];?>" style="display:none" class="request-sent"></span>
                                                    <a href="javascript:void(0)" onclick="remove_tobe_friend_listing('<?php echo $friend['id'];?>')" class="tb-pyk-remove"><span class="glyphicon glyphicon-remove"></span></a>
                                                    <?php } else { ?>
                                                    <button class="btn btn-primary btn-sm bt-cfix"><i class="fa fa-plus"></i>Don't want request</button>
                                                    <?php } ?>
                                                  </div>
					  </div>
		 <?php ActiveForm::end() ?>
	</li>
						 <?php }?>
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

