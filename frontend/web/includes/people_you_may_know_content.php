<?php
use yii\widgets\ActiveForm;
use frontend\models\Friend;
use frontend\models\SecuritySetting;
use yii\helpers\Url;
?> 

          
            <div class="tb-box-body clearfix">
              <div class="tb-inner-title01">People you may know</div>
              <input type="hidden" name="login_id" id="login_id" value="<?php echo $session->get('user_id');?>">
                 <?php  
                 $counter = 0;
                 foreach($init_friends as $friend){ 
                  //  if($counter <2){
                     $requestexists = $model_friend->requestexists($friend['id']);
                     $alreadysend = $model_friend->requestalreadysend($friend['id']);
                    
                     //if( $requestexists < 1 && $alreadysend <1)
                         {
                         $counter++;
                          $ctr = $model_friend->mutualfriendcount($friend['id']);
                     ?>

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
                $dpimg = $this->context->getimage($friend['_id'],'photo');
              ?>
                    <img src="<?= $dpimg?>" class="img-responsive" >
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

                    <?php }}?>
            <?php //}?>
              
              <div class="tb-inner-link01 clearfix"> <span>&nbsp;</span><span><a href="<?php echo Url::to(['site/viewpeople']); ?>">View All</a></span> </div>
            </div>
          
       