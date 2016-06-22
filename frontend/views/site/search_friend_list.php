<?php
include('includes/header.php');
use yii\widgets\ActiveForm;
use frontend\models\Friend;
use yii\helpers\Url;
use frontend\models\PostForm;
use frontend\models\SecuritySetting;
use yii\helpers\ArrayHelper;
 $session = Yii::$app->session;
 $user_id = (string) $session->get('user_id');  
 $suserid = $user_id;
//$frnd_list = Friend::find()->where(['status' => '1','to_id'=>$user_id])->all();
$frnd_list = ArrayHelper::map(Friend::find()->where(['from_id' => $user_id])->all(), 'to_id', 'status');

//$frnd_list2 = array_keys($frnd_list);


/*
echo "<pre>";
print_r($frnd_list);
die();      */

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
						<div class="tb-inner-title01 section-title">Search Result</div>
						<div class="ppl-box pad-box">
						
						 <?php 
							if(!empty($friends) && count($friends)>0){?><ul><?php
						 foreach($friends as $friend){
							 
							 $requestexists = $model_friend->requestexists($friend['id']);
							 $alreadysend = $model_friend->requestalreadysend($friend['id']);
							
							 /*if( $requestexists < 1 && $alreadysend <1)
								 {*/
								  $ctr = $model_friend->mutualfriendcount($friend['id']);
                                                                  $guserid = (string)$friend['id'];
                    $result_security = SecuritySetting::find()->where(['user_id' => $guserid])->one();
                    if($result_security)
                    {
                        $lookup_settings = $result_security['my_view_status'];
                    }
                    else
                    {
                        $lookup_settings = 'Public';
                    }
                    $is_friend = Friend::find()->where(['from_id' => $guserid,'to_id' => $suserid,'status' => '1'])->one();
			if(($lookup_settings == 'Public') || ($lookup_settings == 'Friends' && ($is_friend || $guserid == $suserid)) || ($lookup_settings == 'Private' && $guserid == $suserid)) {			
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
						if(!($user_id == $friend['_id'])){
							
													
																$status = (string)$friend['id'];
														 if(array_key_exists($status, $frnd_list)) {
															 $status = $frnd_list[$status];
															 if($status == 1) {
																 
																  echo '<button class="btn btn-primary btn-sm bt-cfix" ><i class="fa fa-user"></i>Already Friend</button>';
															 } else {
																 
																  echo '<button class="btn btn-primary btn-sm bt-cfix" ><i class="fa fa-user"></i> Friend Request Sent</button>';
															 }
														 
							 
                                                    } else {
                                                        ?>
                                                        <button class="btn btn-primary btn-sm bt-cfix" id="people_<?php echo $friend['id'];?>"  onclick="addfriend('<?php echo $friend['id'];?>')"><i class="fa fa-plus"></i>Add Friend</button>
                                                <span id="sendmsg_<?php echo $friend['id'];?>" style="display:none" class="request-sent"></span>
                                                <?php
                                                    }
                                                 
                                                    
                                                    

						?>	
                                                
						<?php 
                                                }
?>
<a href="javascript:void(0)" onclick="remove_tobe_friend_listing('<?php echo $friend['id'];?>')" class="tb-pyk-remove"><span class="glyphicon glyphicon-remove"></span></a> </div>
						
					  </div>
		 <?php ActiveForm::end() ?>
	</li>
                        <?php } ?>
						 <?php //}?>
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

