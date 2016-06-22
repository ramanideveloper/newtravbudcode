<?php
include('includes/header-wall.php');
use yii\widgets\ActiveForm;
use frontend\models\Friend;
use frontend\models\UserSetting;
use yii\helpers\Url;
use frontend\models\LoginForm;
use frontend\models\Occupation;
use frontend\models\Personalinfo;
use frontend\models\Language;
use frontend\models\Interests;
use frontend\models\ProfileVisitor;
use frontend\models\PostForm;
use frontend\models\SecuritySetting;
use frontend\models\Notification;
use yii\helpers\ArrayHelper;

$result_setting = UserSetting::find()->where(['user_id' => $_GET['id']])->one();
$email_access = $result_setting['email_access'];
$mobile_access = $result_setting['mobile_access'];
$birth_date_access = $result_setting['birth_date_access'];
$session = Yii::$app->session;
$email = $session->get('email');
$user = LoginForm::find()->where(['email' => $email])->one();
$suserid = (string) $result['_id'];
$guserid = $_GET['id'];
$model_pv = new ProfileVisitor();
$count_pv = ProfileVisitor::getAllVisitors($guserid);
$friends_city =Friend::getFriendsCity($guserid);
$friends_names =Friend::getFriendsNames($guserid);
$friends_images =Friend::getFriendsImages($guserid,'wall');
$locations = "'Mumbai','surat','kalkatta'";

$occupation = $user_data['occupation'];
$occu_str = '';
if(isset($occupation) && $occupation != '') {
	$occu_str .= '"';
	$occu_str .= str_replace(",", '","', $occupation);
	$occu_str .= '"';	
}

$interests = $user_data['interests'];
$inter_str = '';
if(isset($interests) && $interests != '') {
	$inter_str .= '"';
	$inter_str .= str_replace(",", '","', $interests);
	$inter_str .= '"';	
}

$language = $user_data['language'];

$lang_str = '';
if(isset($language) && $language != '') { 
	$lang_str .= '"';
	$lang_str .= str_replace(",", '","', $language);
	$lang_str .= '"';	
}

$result_security = SecuritySetting::find()->where(['user_id' => $guserid])->one();
if ($result_security)
{
    $photo_setting = $result_security['view_photos'];
    $add_post_on_wall = $result_security['add_public_wall'];
    $my_post_view_status = $result_security['my_post_view_status'];
    if ($my_post_view_status == 'Private') {
        $post_dropdown_class = 'lock';
    } else if ($my_post_view_status == 'Friends') {
        $post_dropdown_class = 'user';
    } else {
        $my_post_view_status = 'Public';
        $post_dropdown_class = 'globe';
    }
} else {
    $my_post_view_status = 'Public';
    $post_dropdown_class = 'globe';
    $photo_setting = 'Public';
    $add_post_on_wall = 'Public';
}
$is_friend = Friend::find()->where(['from_id' => "$guserid",'to_id' => "$suserid",'status' => '1'])->one();
?>

<!-- END doctoc generated TOC please keep comment here to allow auto update -->
<!-- <p><span class="context-menu-one btn btn-neutral">right click me</span></p> -->


<div class="container-fluid clearfix inner-fix01 userwall">
     
	<div class="left-container">
		 <div class="row fb-pagecontent sm-fix">
			 <div   class="user_data">         
				   <!--Left Part-->
			<div id="wall-wrapper">
				<div class="col-lg-5 col-md-6 col-sm-12 data-section-about wallhalf-left">
					<?php
					
					//if(!(empty($user_data['about']) && empty($user_data['interests']) && empty($user_data['language']) && empty($user_data['occupation']) && empty($user_data['birth_date']))){?> 
					 <div class="white-holderbox user_basicinfo panel-shadow">
						<ul class="uwall-detail">
							<?php if(!empty($user_data['about'])){?> 
								<li>
									
				<?php $form = ActiveForm::begin(['id' => 'frm-about','options'=>['onsubmit'=>'return false;',],]); ?>  
									
										<div class="normal-mode">
											<div class="row">
												<div class="col-lg-2 col-md-3 col-sm-3 u-title">About</div>
												<div class="col-lg-9 col-md-8 col-sm-8 u-data"><p id="about"><?php echo $user_data['about'];?></p></div>
												<div class="col-lg-1 col-md-1 col-sm-1 u-btn">										
													<div class="pull-right  linkholder">
													<?php if($wall_user_id == $user_id){ echo '
														<a href="javascript:void(0)" onClick="open_edit(this)"><i class="fa fa-pencil"></i></a>';
													}?>
													</div>
												</div>
											</div>	
										</div>	
										<div class="edit-mode">
											<div class="row">
												<div class="col-lg-1 col-md-3 col-sm-3 col-xs-12 u-title">
													About
												</div>
												<div class="col-lg-8 col-md-6 col-sm-6 col-xs-12 u-data">
													
		 <?= $form->field($model2,'about')->textInput(array('value'=>$user_data['about']))->label(false)?>											
												</div>									
												<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 pull-right u-btn">
													<div class="form-group pull-right">						
														<div class="pull-right fb-btnholder nbm">		
															<a class="btn btn-primary btn-sm" onClick="close_edit(this),about()">Save</a>
															<a class="btn btn-primary btn-sm" onClick="close_edit(this)">Cancel</a>												
														</div>										
													</div>										
												</div>
											</div>	
										</div>
									
									  <?php ActiveForm::end() ?>
									  
								</li>	
								
							<?php } ?>
							<?php if(!empty($user_data['interests'])){?>
								<li>
							<?php $form = ActiveForm::begin(['id' => 'frm-interests','options'=>['onsubmit'=>'return false;',],]); ?> 
									<div class="normal-mode">
										<div class="row">
											<div class="col-lg-2 col-md-3 col-sm-3 u-title">Interest</div>
											<div class="col-lg-9 col-md-8 col-sm-8 u-data"><p id="interests"><?php echo $user_data['interests'];?></p></div>
											<div class="col-lg-1 col-md-1 col-sm-1 u-btn">										
												<div class="pull-right  linkholder">
												<?php if($wall_user_id == $user_id){ echo '
													<a href="javascript:void(0)" onClick="open_edit(this)"><i class="fa fa-pencil"></i></a>';
												}?>
													
												</div>
											</div>
										</div>	
									</div>	
									<div class="edit-mode">
										<div class="row">
											<div class="col-lg-1 col-md-3 col-sm-3 col-xs-12 u-title">
												Interest
											</div>
											<div class="col-lg-8 col-md-6 col-sm-6 col-xs-12 u-data">
	<?= $form->field($model2,'interests')->dropDownList(ArrayHelper::map(Interests::find()->all(), 'name', 'name'),['class'=>'js-example-theme-multiple','style'=>'width: 100%','multiple'=>'multiple','id'=>'wallinterests'])->label(false)?> 										
											</div>									
											<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 pull-right u-btn">
												<div class="form-group pull-right nbm">						
													<div class="pull-right fb-btnholder nbm">		
														<a class="btn btn-primary btn-sm" onClick="close_edit(this),interests()">Save</a>
														<a class="btn btn-primary btn-sm" onClick="close_edit(this)">Cancel</a>												
													</div>										
												</div>										
											</div>
										</div>	
									</div>	
								 <?php ActiveForm::end() ?>
								</li>
								
												<?php } ?>
							  <?php if(!empty($user_data['language'])){?>
								<li>
								 <?php $form = ActiveForm::begin(['id' => 'frm-language','options'=>['onsubmit'=>'return false;',],]); ?> 
									<div class="normal-mode">
										<div class="row">
											<div class="col-lg-2 col-md-3 col-sm-3 u-title">Language</div>
											<div class="col-lg-9 col-md-8 col-sm-8 u-data"><p id="language"><?php echo $user_data['language'];?></p></div>
											<div class="col-lg-1 col-md-1 col-sm-1 u-btn">										
												<div class="pull-right  linkholder">
												<?php if($wall_user_id == $user_id){ echo '
													<a href="javascript:void(0)" onClick="open_edit(this)"><i class="fa fa-pencil"></i></a>';
												}?>
													
												</div>
											</div>
										</div>	
									</div>	
									<div class="edit-mode">
										<div class="row">
											<div class="col-lg-1 col-md-3 col-sm-3 col-xs-12 u-title">
												Language
											</div>
											<div class="col-lg-8 col-md-6 col-sm-6 col-xs-12 u-data">
	<?= $form->field($model2,'language')->dropDownList(ArrayHelper::map(Language::find()->all(), 'name', 'name'),['class'=>'js-example-theme-multiple','style'=>'width: 100%','multiple'=>'multiple','id'=>'walllanguage'])->label(false)?>   
											</div>									
											<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 pull-right u-btn">
												<div class="form-group pull-right nbm">						
													<div class="pull-right fb-btnholder nbm">		
														<a class="btn btn-primary btn-sm" onClick="close_edit(this),language()">Save</a>
														<a class="btn btn-primary btn-sm" onClick="close_edit(this)">Cancel</a>												
													</div>										
												</div>										
											</div>
										</div>	
									</div>
									<?php ActiveForm::end() ?>
								</li>
												 
							  <?php } ?>
							  
							   <?php if(!empty($user_data['occupation'])){?>
								<li>
								 <?php $form = ActiveForm::begin(['id' => 'frm-occupation','options'=>['onsubmit'=>'return false;',],]); ?> 
									<div class="normal-mode">
										<div class="row">
											<div class="col-lg-2 col-md-3 col-sm-3 u-title">Occupation</div>
											<div class="col-lg-9 col-md-8 col-sm-8 u-data"><p id="occupation"><?php echo $user_data['occupation'];?></p></div>
											<div class="col-lg-1 col-md-1 col-sm-1 u-btn">										
												<div class="pull-right  linkholder">
												<?php if($wall_user_id == $user_id){ echo '
													<a href="javascript:void(0)" onClick="open_edit(this)"><i class="fa fa-pencil"></i></a>';
												}?>
													
												</div>
											</div>
										</div>	
									</div>	
									<div class="edit-mode">
										<div class="row">
											<div class="col-lg-1 col-md-3 col-sm-3 col-xs-12 u-title">
												Occupation
											</div>
											<div class="col-lg-8 col-md-6 col-sm-6 col-xs-12  u-data">
			<?= $form->field($model2,'occupation')->dropDownList(ArrayHelper::map(Occupation::find()->all(), 'name', 'name'),['class'=>'js-example-theme-multiple', 'id'=>'walloccupation','style'=>'width: 100%','multiple'=>'multiple'])->label(false)?>  									
											</div>									
											<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 pull-right u-btn">
												<div class="form-group pull-right nbm">						
													<div class="pull-right fb-btnholder nbm">		
														<a class="btn btn-primary btn-sm" onClick="close_edit(this),occupation()">Save</a>
														<a class="btn btn-primary btn-sm" onClick="close_edit(this)">Cancel</a>												
													</div>										
												</div>										
											</div>
										</div>	
									</div>	
				<?php ActiveForm::end() ?>					
								</li>			
							  <?php } ?>
						  
						</ul>
					</div>
					<?php //} ?>
					   <div class="white-holderbox user_friends panel-shadow">
						  
							   
                                               <div class="boxlabel"><a href="javascript:void(0);" onclick="load_data('friends','<?php echo $wall_user_id;?>')">Friends</a></div>
						   <div class="clear"></div>
						   
							<ul class="nav nav-tabs">
							  <li class="active"><a data-toggle="tab" href="#umap">Map</a></li>
							  <li><a data-toggle="tab" href="#ulist">List</a></li>
							</ul>

							<div class="tab-content">
								<!--
								<div class="activity">
									<h6>Friends Activities</h6>							
									<div class="fsteps">
										<div class="row">
										<div class="col20per">
												<span class="tmute">
																						 <?php echo $count_pv;?></span>
												<p>Reviews</p>
											</div>
											<div class="col20per">
												<span class="tmute">12145</span>
												<p>Photos</p>
											</div>
											<div class="col20per">
												<span class="tmute">23</span>
												<p>Blogs</p>
											</div>
											<div class="col20per">
												<span class="tmute">75</span>
												<p>Videos</p>
											</div>
											
										</div>
									</div>
							   </div>
								-->
							  <div id="umap" class="tab-pane fade in active">							
								<div class="map_canvas" id="map_canvas"></div>
							  </div>
							  <div id="ulist" class="tab-pane fade">														
								<div class="wall-list">
                                                                    <?php if(count($user_friends) > 0){ ?>
									<ul class="friendlist">
										<?php foreach($user_friends as $user_friend){
											$lmodel = new \frontend\models\LoginForm();
											$friendinfo = LoginForm::find()->where(['_id' => $user_friend['from_id']])->one();
											$fmodel = new \frontend\models\Friend();
											$mutualcount = Friend::mutualfriendcount($friendinfo['_id']);
                                                                                        $countposts = count(PostForm::getUserPost($user_friend['from_id']));
										?>
										<li>
											<div class="friend-list">
												<div class="imgholder">
                                                                                                    <?php $frnd_img = $this->context->getimage($friendinfo['_id'],'photo'); ?>
                                                                                                    <img alt="user-photo" class="img-responsive" src="<?= $frnd_img?>">
												</div>										
												<div class="descholder">
													<a class="profilelink no-ul" href="<?php $id =  $friendinfo['_id']; echo Url::to(['userwall/index', 'id' => "$id"]); ?>"><?php echo $friendinfo['fname'].' '.$friendinfo['lname']?>
													<span class="online-status"><i class="fa fa-check-circle"></i></span>
													</a>
													
													<div class="clear"></div>
													<?php if($countposts > 0){?>
														<div class="user-post"><?= $countposts?> Posts</div>
													<?php }?>
												</div>
											</div>
										</li>
																			<?php }?>
									</ul>
                                                                    <?php } else {
                                                                            echo '<span class="no-listcontent">No Friends</span>';
                                                                        } ?>
								</div>
							  </div>
							</div>
						   
					   </div>
					 
					  <div class="white-holderbox user_photos panel-shadow">
						
                                              <div class="boxlabel"><a href="javascript:void(0);" onclick="load_data('photos','<?php echo $wall_user_id;?>')">Photos</a></div>
						   <div class="clear"></div>
                                                        <?php if(($photo_setting == 'Public') || ($photo_setting == 'Friends' && ($is_friend || (string)$guserid == $suserid)) || ($photo_setting == 'Private' && (string)$guserid == $suserid)) {?>
							<div class="photos">

								<?php 
								
										if(count($posts)>0){
												$ctr = 1;
												?>
												<div class="albums">
														<?php 
														foreach($posts as $post){
																if(isset($post['image']) && !empty($post['image'])){
																$eximgs = explode(',',$post['image'],-1);
																
																//if(!empty($post['image']) && empty($post['post_text'])) {
																//if(!empty($post['image']) && empty($post['post_text']) && $ctr < 14) {
																?>
																<?php foreach ($eximgs as $eximg) {
																	
																	if($ctr<9){																		
																		
																	$picsize = '';
																	$val = getimagesize('../web'.$eximg);
																	$picsize .= $val[0] .'x'. $val[1] .', ';
																	if($val[0] > $val[1]){$imgclass = 'himg';}else if($val[1] > $val[0]){$imgclass = 'vimg';}else{$imgclass = 'simg';}
																?>
																<div class="album-col">
																<div class="album-holder">
																	<div class="album-box">
																		<a href="javascript:void(0)" class="listalbum-box <?= $imgclass?>-box"><img src="<?= Yii::$app->getUrlManager()->getBaseUrl() ?><?= $eximg ?>" alt=""></a>
																	</div>
																</div>
																</div>
																<?php } $ctr++; }?>
																<?php
																
																}
														}
														?>
												</div>
												<?php
										}
                                                                                else {
                                                                                    echo '<span class="no-listcontent">No Photos</span>';
                                                                                }
								?>
							</div>
                                                   <?php } else {
                                                        echo '<span class="no-listcontent">User has kept security for Photo Section</span>';
                                                    } ?>

					   </div>
						
						<div class="user_destination white-holderbox panel-shadow">
						
                                                    <div class="boxlabel"><a href="javascript:void(0);" onclick="load_data('destinations','<?php echo $wall_user_id;?>')">Destination</a></div>
						   <div class="clear"></div>
						   
						   <div class="destination-info">									
								<div class="user-desinfo">
									<ul>
										<li><p><a href="javascript:void(0);">User Name</a> is currently in Chili</p></li>
										<li><p>has been to <b>32</b> countries</p></li>										
									</ul>
								</div>							
						   </div>
						   <ul class="nav nav-tabs">
							  <li class="active"><a data-toggle="tab" href="#dmap">Map</a></li>
							  <li><a data-toggle="tab" href="#dlist">Places</a></li>
							</ul>
							<div class="tab-content">								
							  <div id="dmap" class="tab-pane fade in active">							
								<div class="map_canvas" id="map_container"></div>
															
							  </div>
							  <div id="dlist" class="tab-pane fade">														
								<div class="wall-list">
									<ul>
										<li>
											<div class="ulist-holder">
												<div class="imgholder">
													<img class="img-responsive" src="<?= Yii::$app->getUrlManager()->getBaseUrl() ?>/profile/Male.jpg">
												</div>										
												<div class="descholder">
													<a href="javascript:void(0);">Place Name</a>																							
													<div class="clear"></div>
													<div class="user-address"> <span class="title">Country</span><div class="clear"></div><span>Place details goes here</span></div>											
												</div>
											</div>
										</li>
										<li>
											<div class="ulist-holder">
												<div class="imgholder">
													<img class="img-responsive" src="<?= Yii::$app->getUrlManager()->getBaseUrl() ?>/profile/Male.jpg">
												</div>										
												<div class="descholder">
													<a href="javascript:void(0);">Place Name</a>																							
													<div class="clear"></div>
													<div class="user-address"> <span class="title">Country</span><div class="clear"></div><span>Place details goes here</span></div>											
												</div>
											</div>
										</li>
										<li>
											<div class="ulist-holder">
												<div class="imgholder">
													<img class="img-responsive" src="<?= Yii::$app->getUrlManager()->getBaseUrl() ?>/profile/Male.jpg">
												</div>										
												<div class="descholder">
													<a href="javascript:void(0);">Place Name</a>																							
													<div class="clear"></div>
													<div class="user-address"> <span class="title">Country</span><div class="clear"></div><span>Place details goes here</span></div>											
												</div>
											</div>
										</li>
										<li>
											<div class="ulist-holder">
												<div class="imgholder">
													<img class="img-responsive" src="<?= Yii::$app->getUrlManager()->getBaseUrl() ?>/profile/Male.jpg">
												</div>										
												<div class="descholder">
													<a href="javascript:void(0);">Place Name</a>																							
													<div class="clear"></div>
													<div class="user-address"> <span class="title">Country</span><div class="clear"></div><span>Place details goes here</span></div>											
												</div>
											</div>
										</li>
										<li>
											<div class="ulist-holder">
												<div class="imgholder">
													<img class="img-responsive" src="<?= Yii::$app->getUrlManager()->getBaseUrl() ?>/profile/Male.jpg">
												</div>										
												<div class="descholder">
													<a href="javascript:void(0);">Place Name</a>																							
													<div class="clear"></div>
													<div class="user-address"> <span class="title">Country</span><div class="clear"></div><span>Place details goes here</span></div>											
												</div>
											</div>
										</li>
										<li>
											<div class="ulist-holder">
												<div class="imgholder">
													<img class="img-responsive" src="<?= Yii::$app->getUrlManager()->getBaseUrl() ?>/profile/Male.jpg">
												</div>										
												<div class="descholder">
													<a href="javascript:void(0);">Place Name</a>																							
													<div class="clear"></div>
													<div class="user-address"> <span class="title">Country</span><div class="clear"></div><span>Place details goes here</span></div>											
												</div>
											</div>
										</li>
									</ul>
								  </div>
							  </div>
							</div>					   
					   </div>
					   
						<div class="user_likes white-holderbox panel-shadow">
						
						   <div class="boxlabel">Likes</div>
						   <div class="clear"></div>
						 
							<div class="friends">
								<?php 
									if(count($likes)>0){
										$lctr = 0;
								?>
									<div class="likes-row">
										<?php 
											foreach($likes as $like){
                                                                                            $likeid = $like['post']['_id'];
												if($lctr < 14) {
										?>
												<div class="likes-col">
													<div class="user-likebox">
                                                                                                            <div class="col-lg-8 col-md-7 col-sm-7 col-xs-12">										
                                                                                                                    <div class="info">																   				   								
                                                                                                                        <a href="<?php echo Url::to(['site/view-post', 'postid' => "$likeid"]); ?>" target="_blank"><?php if(!empty($like['post']['post_text'])){ echo $like['post']['post_text'];}else {echo 'Liked Post';}?></a>
                                                                                                                    </div>
                                                                                                            </div>
<!--														<a href="javascript:void(0);">
															<span class="like-img">
																<img height="200" width="200" src="<?= Yii::$app->getUrlManager()->getBaseUrl() ?><?= $like['post']['image'] ?>" alt=""/>
															</span>
															<span class="caption"><?php if(!empty($like['post']['post_text'])){ echo $like['post']['post_text'];}else {echo 'Like Caption';}?></span>
														</a>-->
													</div>
												</div>
										<?php
												$lctr++;
												}
											}
										?>
									</div>
								<?php
									} else {
                                                                            echo '<span class="no-listcontent">No Liked Posts</span>';
                                                                        }
								?>
								
							</div>
					   </div>
					 
				 </div> 
			
				   
			<!--Left Part End -->
			<!--Right Part-->
			 <?php  //include('includes/people_you_may_know.php');
			 $login_img = $this->context->getimage($result['_id'],'thumb');
			 ?>			 
			 <div class="col-lg-7 col-md-6 col-sm-12 data-section-about wallhalf-right">


                                <div class="posts">
                                   <div class="tb-panel-box02 panel-shadow">
                                        <?php if(($add_post_on_wall == 'Public') || ($add_post_on_wall == 'Friends' && ($is_friend || (string)$guserid == $suserid)) || ($add_post_on_wall == 'Private' && (string)$guserid == $suserid)) {?>
							<div class="panel-body toolbox newpost">
							
	
					<!-- <p><span class="context-menu-one btn btn-neutral">right click me</span></p> -->



								<!--
								<div class="tb-inner-title01 clearfix"> 
									<span>Logged in as <a href="#" class="no-ul"><?= ucfirst($result['fname']) . ' ' . ucfirst($result['lname']); ?></a>  </span>
								</div>
								-->
								<div class="post_loadin_img"></div>
								<div class="newpost-topbar">
									<div class="post-title">
										<div class="tb-user-post-middle">											
											<div class="sliding-middle-out full-width">											
												<input type="text" placeholder="Title of this post" id="title"/>
											</div>
										</div>
									</div>
									<div class="tarrow dotarrow">
										<a href="javascript:void(0)" class="alink">
											<span class="more-option"><svg height="100%" width="100%" viewBox="0 0 50 50"><path class="Ce1Y1c" d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/></svg></a></span>&nbsp;
										<div class="drawer">

											<ul class="sort-ul">
                                                <li class="disable_share"><span class="dis_share check-option">✓</span><a href="javascript:void(0)">Disable Sharing</a></li>																		
												<li class="disable_comment"><span class="dis_comment check-option">✓</span><a href="javascript:void(0)">Disable Comments</a></li>																		
												<li class="cancel_post"><a href="javascript:void(0)" onclick="closeAllDrawers(this)">Cancel Post</a></li>																		
											</ul>	
										</div>
									</div>
								</div>
								
								<div class="post-holder">
								
									<div class="postarea">
										<div class="post-pic">
											<img src="<?= $login_img;?>" class="img-responsive" alt="user-photo">
										</div>
										<div class="post-desc">								
											<div class="tb-user-post-middle">												

												<textarea rows="2" title="Add a comment" class="form-control textInput" id="textInput" name="textInput" onclick="displaymenus()"   placeholder="<?= ucfirst($result['fname']); ?>, what's new?"></textarea>           

											</div>	
										</div>
										<div style="clear: both;"></div>
										<!--<div>
											<div class='tagcontent-area'>
										
											</div>
											<div style="clear: both;"></div>		
											<div class='taginpt-area'>
												<input type="text" id="taginput" name="taginput" class="js-example-theme-multiple" style="width: 100%;"/>
											</div>
										</div>-->										
									</div>
								
									<div id="userwall">
									<div id="image-holder">
										<div class="img-row">

										</div> 
									</div>
									</div>
									<div style="clear: both;"></div>
									
									<div class="tags-added"></div>
									<div id="tag" class="addpost-tag">
										
										<span class="ltitle">With</span>
										<div class="tag-holder">
											<select id="taginput" class="taginput" placeholder="Who are with you?" multiple="multiple"></select>		
										</div>
									</div>
									<div style="clear: both;"></div>
									<div id="area" class="addpost-location">
										<span class="ltitle">At</span>
										<div class="loc-holder">
											<input type="text" name="current_location" class="current_location getplacelocation" value="" id="cur_loc" onFocus="geolocate()" placeholder="Where are you?" />
											<input type="hidden" name="country" id="country" />
											<input type="hidden" name="isd_code" id="isd_code" />
										</div>
									</div>						
								</div>
								
								<input type="text" name="url" size="64" id="url"  style="display: none"/>
								<input type="button" name="attach" value="Attach" id="mark" style="display: none" />
								<form enctype="multipart/form-data" name="imageForm">
									
									<div class="tb-user-post-bottom clearfix">
										<div class="tb-user-icon" style="display:none"> 
											<label class="myLabel">
												<!-- uplaod image -->
	<input type="file" id="imageFile1" name="imageFile1[]" data-class="#userwall #image-holder .img-row" required="" multiple="true"/>
        <input type="hidden" id="counter" name="counter">
												<span aria-hidden="true" class="glyphicon glyphicon-camera tb-icon-fix001"></span>
											</label>
											<a href="javascript:void(0)" class="tb-icon-fix001 add-tag"><span aria-hidden="true"  class="glyphicon glyphicon-user"></span></a>

											<a href="javascript:void(0)" class="tb-icon-fix001 add-loc"><span aria-hidden="true" class="glyphicon glyphicon-map-marker"></span></a>
											<a href="javascript:void(0)" class="tb-icon-fix001 add-title"><span aria-hidden="true" class="glyphicon glyphicon-text-size"></span></a> 
										</div>
											
										<div class="user-post fb-btnholder">
											<div class="dropdown tb-user-post">

												<button aria-expanded="false" data-toggle="dropdown" type="button" class="btn btn-default dropdown-toggle btn-sm custom-select custom-select-posting-security"><span class="glyphicon glyphicon-<?= $post_dropdown_class ?>"></span> <?= $my_post_view_status ?> <span class="caret"></span></button>

												<ul class="dropdown-menu" id="posting-security">
													<li class="sel-private"><a href="javascript:void(0)" onClick="setSecuritySelect(this, 'Private')"><span class="glyphicon glyphicon-lock"></span> Private</a></li>
													<li class="divider"></li>
													<li class="sel-friends"><a href="javascript:void(0)" onClick="setSecuritySelect(this, 'Friends')"><span class="glyphicon glyphicon-user"></span> Friends</a></li>
													<li class="divider"></li>
													<li class="sel-public"><a href="javascript:void(0)" onClick="setSecuritySelect(this, 'Public')"><span class="glyphicon glyphicon-globe"></span> Public</a></li>
													<li class="divider"></li>
													<li class="sel-custom"><a href="#custom-share" class="popup-modal" onClick="sendSelId('posting-security')"><span class="glyphicon glyphicon-cog"></span> Custom</a></li>
												</ul>
                                                                                                <input type="hidden" name="imgfilecount" id="imgfilecount" value="0" />
                                                                                                <input type="hidden" name="post_privacy" id="post_privacy" value="<?= $my_post_view_status ?>"/>
                                                                                                <input type="hidden" name="share_setting" id="share_setting" value="Enable"/>
												<input type="hidden" name="comment_setting" id="comment_setting" value="Enable"/>
												<input type="hidden" name="link_title" id="link_title" />
												<input type="hidden" name="link_url" id="link_url" />
												<input type="hidden" name="link_description" id="link_description" />
												<input type="hidden" name="link_image" id="link_image" />

												<button class="btn btn-primary btn-sm ml-5"  onclick="postStatus();" type="button" name="post" id="post" disabled><span class="glyphicon glyphicon-send"></span>Post</button>

											</div>
										</div>
									</div>
								</form>
							
							</div>
                                        <?php } else {
                                            echo '<span class="no-listcontent">User don\'t want to you to post on the wall.</span>';
                                        } ?>
						</div>
                                          <!-- fetch all post--> 
                                          <div id="ajax-content"></div>

                        <div id="post-status-list">    <?php 
                        foreach($posts as $post)
                        {
                            $existing_posts = '1';
                            $result_security = SecuritySetting::find()->where(['user_id' => "$guserid"])->one();
                            
                            //echo $guserid;echo "<pre>";print_r($result_security);
                            
                            if ($result_security)
                            {
                                $view_posts_tagged_in = $result_security['view_posts_tagged_in'];
                                $view_others_posts_on_mywall = $result_security['view_others_posts_on_mywall'];   
                            }
                            else
                            {
                                $view_posts_tagged_in = 'Public';
                                $view_others_posts_on_mywall = 'Public';
                            }
                            if($post['is_timeline'] == '1')
                            {
                                $result_notification_security = Notification::find()->where(['share_id' => (string)$post['_id'],'user_id' => "$guserid",'is_deleted' => '0','status' => '1'])->one();
                            }
                            else
                            {
                                $result_notification_security = Notification::find()->where(['post_id' => (string)$post['_id'],'user_id' => "$guserid",'is_deleted' => '0','status' => '1'])->one();
                            }
                            if($result_notification_security)
                            {
                                if(empty($result_notification_security['review_setting']))
                                {
                                    $tag_review_setting = 'Disabled';
                                }
                                else
                                {
                                    $tag_review_setting = $result_notification_security['review_setting'];
                                }
                            }
                            else
                            {
                                $tag_review_setting = 'Disabled';
                            }

                            $is_friend = Friend::find()->where(['from_id' => $guserid,'to_id' => $suserid,'status' => '1'])->one();
                            
                            if($post['is_timeline'] == '1')
                            {
                                if($post['is_deleted'] == '0' && $tag_review_setting == 'Disabled' && (($view_others_posts_on_mywall == 'Public') || ($view_others_posts_on_mywall == 'Friends' && ($is_friend || $guserid == $suserid)) || ($view_others_posts_on_mywall == 'Private' && $guserid == $suserid)))
                                {
                                    $this->context->display_last_post($post['_id'],$existing_posts);
                                }
                            }
                            else
                            {
                                if($post['is_deleted'] == '0' && $tag_review_setting == 'Disabled' && (($view_posts_tagged_in == 'Public') || ($view_posts_tagged_in == 'Friends' && ($is_friend || $guserid == $suserid)) || ($view_posts_tagged_in == 'Private' && $guserid == $suserid)))
                                {
                                    $this->context->display_last_post($post['_id'],$existing_posts);
                                }
                            }
                        }
                        ?></div>
                                 </div>	 
                                 </div>
			
			</div>
			<!--Right Part End -->
			
			 </div>
		</div>
	</div>
	<?php include('includes/chat_section.php');?>
</div>    
   
 <!-- Left Navigation and Footer -->   
 <?php 
    //include('includes/left-menu.php');
    include('includes/footer.php');
?>
<a class="scrollup">
	<i class="fa fa-arrow-circle-up"></i>
</a>
<a href="#add-newpost" class="popup-modal addpost-sc">
	<i class="fa fa-plus"></i>
</a>

<div id="add-newpost" class="white-popup-block mfp-hide rounded fp-modal-popup newpost-popup">
    <?php /*
	<div class="modal-title graytitle clearfix">										
		<a href="javascript:void(0)" class="popup-modal-dismiss popup-modal-close close-top"><i class="fa fa-close"></i></a>
	</div>
	<div class="tb-panel-body01">
		<div class="modal-detail">		
			
			<div class="post_loadin_img"></div>
			<div class="newpost-topbar">
				<div class="post-title">
					<div class="tb-user-post-middle">											
						<div class="sliding-middle-out full-width">											
							<input type="text" placeholder="Title of this post" id="title"/>
						</div>
					</div>
				</div>
				<div class="tarrow dotarrow">
					<a href="#" class="alink">
						<span class="more-option"><svg height="100%" width="100%" viewBox="0 0 50 50"><path class="Ce1Y1c" d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/></svg></a></span>&nbsp;
					<div class="drawer">

						<ul class="sort-ul">
							<li class="disable_share"><span class="dis_share check-option">✓</span><a href="javascript:void(0)">Disable Sharing</a></li>																		
							<li class="disable_comment"><span class="dis_comment check-option">✓</span><a href="javascript:void(0)">Disable Comments</a></li>																		
							<li class="cancel_post"><a href="#" onclick="closeAllDrawers(this)">Cancel Post</a></li>																		
						</ul>	
					</div>
				</div>
			</div>
			
			<div class="post-holder">
			
				<div class="postarea">
					<div class="post-pic">
						<img src="<?= $login_img;?>" class="img-responsive" alt="user-photo">
					</div>
					<div class="post-desc">								
						<div class="tb-user-post-middle">												

							<textarea rows="2" title="Add a comment" class="form-control textInput" id="textInput" name="textInput" onclick="displaymenus()"   placeholder="<?= ucfirst($result['fname']); ?>, what's new?"></textarea>           

						</div>	
					</div>
					<div style="clear: both;"></div>
					<!--<div>
						<div class='tagcontent-area'>
					
						</div>
						<div style="clear: both;"></div>		
						<div class='taginpt-area'>
							<input type="text" id="taginput" name="taginput" class="js-example-theme-multiple" style="width: 100%;"/>
						</div>
					</div>-->										
				</div>
			
				<div id="image-holder">
					<div class="img-row">

					</div> 
				</div>
				<div style="clear: both;"></div>
				
				<div class="tags-added"></div>
				<div id="tag" class="addpost-tag">
					
					<span class="ltitle">With</span>
					<div class="tag-holder">
						<select id="taginput" placeholder="Who are with you?" multiple="multiple"></select>		
					</div>
				</div>
				<div style="clear: both;"></div>
				<div id="area" class="addpost-location">
					<span class="ltitle">At</span>
					<div class="loc-holder">
						<input type="text" name="current_location" class="current_location" value="" id="autocomplete" onFocus="geolocate()" placeholder="Where are you?" />
						<input type="hidden" name="country" id="country" />
						<input type="hidden" name="isd_code" id="isd_code" />
					</div>
				</div>						
			</div>
			
			<input type="text" name="url" size="64" id="url"  style="display: none"/>
			<input type="button" name="attach" value="Attach" id="mark" style="display: none" />
			<form enctype="multipart/form-data" name="imageForm">
				
				<div class="tb-user-post-bottom clearfix">
					<div class="tb-user-icon" style="display:none"> 
						<label class="myLabel">
							<!-- uplaod image -->
	<input type="file" id="imageFile1" name="imageFile1[]" required="" multiple="true"/>
	<input type="hidden" id="counter" name="counter">
							<span aria-hidden="true" class="glyphicon glyphicon-camera tb-icon-fix001"></span>
						</label>
						<a href="javascript:void(0)" class="tb-icon-fix001 add-tag"><span aria-hidden="true"  class="glyphicon glyphicon-user"></span></a>

						<a href="javascript:void(0)" class="tb-icon-fix001 add-loc"><span aria-hidden="true" class="glyphicon glyphicon-map-marker"></span></a>
						<a href="javascript:void(0)" class="tb-icon-fix001 add-title"><span aria-hidden="true" class="glyphicon glyphicon-text-size"></span></a> 
					</div>
						
					<div class="user-post fb-btnholder">
						<div class="dropdown tb-user-post">

							<button aria-expanded="false" data-toggle="dropdown" type="button" class="btn btn-default dropdown-toggle btn-sm custom-select custom-select-posting-security"><span class="glyphicon glyphicon-<?= $post_dropdown_class ?>"></span> <?= $my_post_view_status ?> <span class="caret"></span></button>

							<ul class="dropdown-menu" id="posting-security">
								<li class="sel-private"><a href="javascript:void(0)" onClick="setSecuritySelect(this, 'Private')"><span class="glyphicon glyphicon-lock"></span> Private</a></li>
								<li class="divider"></li>
								<li class="sel-friends"><a href="javascript:void(0)" onClick="setSecuritySelect(this, 'Friends')"><span class="glyphicon glyphicon-user"></span> Friends</a></li>
								<li class="divider"></li>
								<li class="sel-public"><a href="javascript:void(0)" onClick="setSecuritySelect(this, 'Public')"><span class="glyphicon glyphicon-globe"></span> Public</a></li>
								<li class="divider"></li>
								<li class="sel-custom"><a href="#custom-share" class="popup-modal" onClick="sendSelId('posting-security')"><span class="glyphicon glyphicon-cog"></span> Custom</a></li>
							</ul>
																			<input type="hidden" name="imgfilecount" id="imgfilecount" value="0" />
																			<input type="hidden" name="post_privacy" id="post_privacy" value="<?= $my_post_view_status ?>"/>
																			<input type="hidden" name="share_setting" id="share_setting" value="Enable"/>
							<input type="hidden" name="comment_setting" id="comment_setting" value="Enable"/>
							<input type="hidden" name="link_title" id="link_title" />
							<input type="hidden" name="link_url" id="link_url" />
							<input type="hidden" name="link_description" id="link_description" />
							<input type="hidden" name="link_image" id="link_image" />

							<button class="btn btn-primary btn-sm ml-5"  onclick="postStatus();" type="button" name="post" id="post" disabled><span class="glyphicon glyphicon-send"></span>Post</button>

						</div>
					</div>
				</div>
			</form>
		
			
		</div>

	</div>
    */?>
<?php include ('includes/post-popup.php');?>
</div>
<script>

		function about(){
     
                $.ajax({
                       type: 'POST',
                       url: '<?php echo Yii::$app->urlManager->createUrl(['site/basicinfo']); ?>',
                       data: $("#frm-about").serialize(),
                       success: function(data){  
                        var result = $.parseJSON(data);
                        $("#about").html(result[0]);
                        $('#suceess').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
                       }

                   });
            
                }
		
		function interests(){
     
                $.ajax({
                       type: 'POST',
                       url: '<?php echo Yii::$app->urlManager->createUrl(['site/basicinfo']); ?>',
                       data: $("#frm-interests").serialize(),
                       success: function(data){  
                            var result = $.parseJSON(data);
                             $("#interests").html(result[0]);
                              $('#suceess').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
                       }

                   });
            
			}	
			
			 function language(){
      
				$.ajax({
					   type: 'POST',
					   url: '<?php echo Yii::$app->urlManager->createUrl(['site/basicinfo']); ?>',
					   data: $("#frm-language").serialize(),
					   success: function(data){
						  // alert(data); return false;
									var result = $.parseJSON(data);
									$("#language").html(result[0]);
									$('#suceess').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
					   }
					   
				   });
			}
			
			function occupation(){
     
                $.ajax({
                       type: 'POST',
                       url: '<?php echo Yii::$app->urlManager->createUrl(['site/basicinfo']); ?>',
                       data: $("#frm-occupation").serialize(),
                       success: function(data){  
                            var result = $.parseJSON(data);
                             $("#occupation").html(result[0]);
                              $('#suceess').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
                       }

                   });
            
             }
			 
	  function birth_date(){
		  var a = $('.birth').val();
		 // alert(a);
		 // return false;
		  
		$.ajax({
			   type: 'POST',
			   url: '<?php echo Yii::$app->urlManager->createUrl(['site/basicinfo']); ?>',
			   data: {
				   birth_date: a
			   },
			   success: function(data){

				   var result = $.parseJSON(data);
					 $("#birth_date").html(result[0]);
					  $('#suceess').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
			   }
			   
		   });
    }


</script>
<!--<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places"></script>-->
<script src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script>
	//var jq = $.noConflict();
    jq(document).ready(function () {
    var map;
    var elevator;
    var myOptions = {
        zoom: 1,
        center: new google.maps.LatLng(0, 0),
        mapTypeId: 'terrain'
    };
    map = new google.maps.Map($('#map_canvas')[0], myOptions);

   var addresses = [<?php echo $friends_city;?>];

   var friends_names = [<?php echo $friends_names;?>];
   
   var friends_images = [<?php echo $friends_images;?>];
     
    for (var x1 = 0; x1 < addresses.length; x1++) 
    {
        var content = '',city = '';
      
        city = addresses[x1];
        var imagedisplay = '<img height="18" width="18" src="' + friends_images[x1] + '" alt=""/> ';
        content = imagedisplay.concat(friends_names[x1]);
       
        infowindow = new google.maps.InfoWindow({content: content});
        $.getJSON('http://maps.googleapis.com/maps/api/geocode/json?address='+city+'&sensor=false', null, function (data) {
            var p = data.results[0].geometry.location
            var latlng = new google.maps.LatLng(p.lat, p.lng);
            var marker =  new google.maps.Marker({
                position: latlng,
                map: map
            }); 
         
        infowindow.open(map, marker);
            
            

            });
    }

});
    /*
var locations = [
      ['<h4>Bondi Beach</h4>', -33.890542, 151.274856],
      ['<h4>Coogee Beach</h4>', -33.923036, 151.259052],
      ['<h4>Cronulla Beach</h4>', -34.028249, 151.157507],
      ['<h4>Manly Beach</h4>', -33.80010128657071, 151.28747820854187],
      ['<h4>Maroubra Beach</h4>', -33.950198, 151.259302]
    ];
    
    // Setup the different icons and shadows
    var iconURLPrefix = 'http://maps.google.com/mapfiles/ms/icons/';
    
    var icons = [
      iconURLPrefix + 'red-dot.png',
      iconURLPrefix + 'green-dot.png',
      iconURLPrefix + 'blue-dot.png',
      iconURLPrefix + 'orange-dot.png',
      iconURLPrefix + 'purple-dot.png',
      iconURLPrefix + 'pink-dot.png',      
      iconURLPrefix + 'yellow-dot.png'
    ]
    var iconsLength = icons.length;

    var map = new google.maps.Map(document.getElementById('map_canvas'), {
      zoom: 10,
      center: new google.maps.LatLng(-37.92, 151.25),
      mapTypeId: google.maps.MapTypeId.ROADMAP,
      mapTypeControl: false,
      streetViewControl: false,
      panControl: false,
      zoomControlOptions: {
         position: google.maps.ControlPosition.LEFT_BOTTOM
      }
    });

    var infowindow = new google.maps.InfoWindow({
      maxWidth: 160
    });

    var markers = new Array();
    
    var iconCounter = 0;
    
    // Add the markers and infowindows to the map
    for (var i = 0; i < locations.length; i++) {  
      var marker = new google.maps.Marker({
        position: new google.maps.LatLng(locations[i][1], locations[i][2]),
        map: map,
        icon: icons[iconCounter]
      });

      markers.push(marker);

      google.maps.event.addListener(marker, 'click', (function(marker, i) {
        return function() {
          infowindow.setContent(locations[i][0]);
          infowindow.open(map, marker);
        }
      })(marker, i));
      
      iconCounter++;
      // We only have a limited number of possible icon colors, so we may have to restart the counter
      if(iconCounter >= iconsLength) {
      	iconCounter = 0;
      }
    }

    function autoCenter() {
      //  Create a new viewpoint bound
      var bounds = new google.maps.LatLngBounds();
      //  Go through each...
      for (var i = 0; i < markers.length; i++) {  
				bounds.extend(markers[i].position);
      }
      //  Fit these bounds to the map
      map.fitBounds(bounds);
    }
    autoCenter();*/

</script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<script type="text/javascript">
google.charts.load('current', {'packages':['geochart']});
google.charts.setOnLoadCallback(drawRegionsMap);
function drawRegionsMap()
{
    // 0 for Current
    // 1 for I've been
    // 2 for I want to go
    var data = google.visualization.arrayToDataTable([
        ['Country', 'Code'],
        ['India', 0],
        ['Afghanistan', 1],
        ['Australia', 1],
        ['Bangladesh', 2],
        ['Brazil', 2],
        ['Cuba', 2],
        ['Denmark', 1],
        ['Egypt', 1],
        ['France', 2],
        ['Italy', 1],
        ['South Africa', 2],
        ['Mali', 1],
        ['Greece', 1],
        ['Hong Kong', 1],
        ['Israel', 1],
        ['Jordan', 1],
        ['United States', 2],
        ['Kazakhstan', 2],
        ['Kuwait', 1],
        ['Mexico', 1],
        ['Malaysia', 2],
        ['New Zealand', 1],
        ['Argentina', 2],
        ['Saudi Arabia', 1],
        ['Singapore', 1],
        ['Sweden', 2],
        ['Thailand', 1],
        ['United Arab Emirates', 1],
        ['United Kingdom', 2]
    ]);

    var options = {
        datalessRegionColor: 'white',
        backgroundColor: 'skyblue',
        legend: 'none',
        colors: ['green', 'blue', 'orange']
    };
    var geochart = new google.visualization.GeoChart(document.getElementById('map_container'));
    geochart.draw(data, options);
}
</script>
<script>

jq(document).ready(function(){			

    jq('.popup-modal').magnificPopup({
        type: 'inline',
        preloader: false,
        focus: '#username',
        modal: true
    });	
    jq(document).on('click', '.popup-modal-dismiss', function (e) {
		clearUnderline();
        e.preventDefault();
        jq.magnificPopup.close();
    });		
});

jq(".tarrow .alink").click(function(e){
showDrawer(this,e);
});
</script>
<script language="javascript"> 

	var data1=<?php echo json_encode($frdlist); ?>;
	jq(document).ready(function () {
var $e1 = jq("#taginput");

	$e1.select2();
	// START Custom Code
	function formatRepoSelection (repo) {
	  return repo.text;
	}

	function formatRepo (repo) {
		var markup = "<div class='tag-suggestion'><div class='img-holder'><img style='width: 32px; height: 32px;' src=" + repo.thumb + "></div>";
		markup += "<div class='desc-holder'>" + repo.text + "</div></div>";
		return markup;
	}

	function customchk(e) {
		var selitm = $e1.val();

		if (selitm == undefined || selitm == null || selitm != '') {
			jq('#fmsg').html('');
			var term = selitm.toString().split(",");
			if(term != '') {
				if (!(jq.inArray('F', term) == -1 && jq.inArray('FOF', term) == -1) ) {
					if(term.length >= 2) {
						if(jq.inArray('FOF', term) == -1) {
							lb = "Friends";
						} else {
							lb = "Friends of Friends";	
						}

						if(lb) {
							lbl = "<p>You will share this with " + lb + " as your other choices are included within that audience.</p>";
						    jq('#fmsg').html(lbl);
						}
					}
					jq("#customchk").prop('checked', false);
				    jq(".customchk").show();
				} else {
				     jq(".customchk").hide();		    
				}
			}
		}
	}

	function compare(selval, e1) {
	  var newvals = [];
	  var logs = e1.val();

	  $.each(logs, function( index, value ) {
	  	//alert(value);
	    if(value != selval.id) {
	      newvals.push(value);
	    }
	  });
	  e1.select().val(newvals).trigger("change");  
	}
	

	$e1.on("select2:select", function (e) { compare(e.params.data, $e2); });
	

	$e1.on("change", function(e) { customchk(e); });

	$e1.select2({
		data: data1,
		tags: false,
		//minimumInputLength: false,
		escapeMarkup: function (markup) { return markup; },
		templateResult: formatRepo,
		templateSelection: formatRepoSelection
	});
  

   
	});
   function close_all_edit(){
	   
	   var count=0;
	   
	   $(".uwall-detail > li").each(function(){
			
		   var editmode=$(this).find(".edit-mode").css("display");		
		
		   if(editmode!="none"){
				
				$(this).find(".normal-mode").slideDown(300);
				$(this).find(".edit-mode").slideUp(300);
			}
	   });
   }
	function open_edit(test){
		
		close_all_edit();

		var obj=$(test).parent().parent().parent().parent().parent();
				
		var editmode=obj.children(".edit-mode").css("display");

		if(editmode=="none"){
			obj.children(".normal-mode").slideUp(300);
			obj.children(".edit-mode").slideDown(300);
		}
		else{
			obj.children(".normal-mode").slideDown(300);
			obj.children(".edit-mode").slideUp(300);
		}
   }
	function close_edit(test){

		var obj=$(test).parents(".edit-mode").parent();
		var emode=obj.children(".edit-mode");
		var nmode=obj.children(".normal-mode");
				
		var editmode=emode.css("display");
		
		if(editmode=="none"){
			nmode.slideUp(300);
			emode.slideDown(300);
		}
		else{
			nmode.slideDown(300);
			emode.slideUp(300);
		}
   }
   
	(function () {
	  //  $("#test").hide();
	  jq('#datetimepicker2').datetimepicker({
			format: "DD-MM-YYYY",
			maxDate: new Date
		}); })(jQuery); 
	
	jq(".js-example-theme-multiple").select2({
	  tags: false
	});

	jq("#walloccupation").select2().val([<?php echo $occu_str; ?>]).trigger("change");
	jq("#wallinterests").select2().val([<?php echo $inter_str; ?>]).trigger("change");
	jq("#walllanguage").select2().val([<?php echo $lang_str; ?>]).trigger("change");

	jq("#walloccupation").select2({
	  placeholder: "Most recent occupation",
	  tags: true
	});
	
	jq("#wallinterests").select2({
	  placeholder: "Most recent interests",
	  tags: true
	});
	
	jq("#walllanguage").select2({
	  placeholder: "Most recent Language",
	  tags: true
	});

</script>
<script type="text/javascript">
	//var jq = $.noConflict();
	jq(document).ready(function(){
	
		var yr=new Date().getFullYear();
                jq("#datepicker").datepicker({
                    dateFormat: "dd-mm-yy",
                    changeMonth: true,
                    changeYear: true,
                    maxDate:  new Date(yr, 12-1, 31),
                    minDate: new Date(1920, 1-1, 1),
                    yearRange: "1920:"+yr+""
                });
		jq('#popupDatepicker').datepick({popupContainer:'.date-container',alignment:'top',autoSize: true,onSelect: changedate,maxDate:  new Date(yr, 12-1, 31),minDate: new Date(1920, 1-1, 1), yearRange: "1920:"+yr+""});
		//jq('#popupDatepicker').datepick({popupContainer:'.date-container',alignment:'top',autoSize: true,onSelect: changedate,maxDate: new Date,minDate: new Date(1920, 1-1, 1), yearRange: "1920:"+yr+""});
		jq('#inlineDatepicker').datepick({onSelect: showDate});
	
	});
	function changedate()
	{
		var adate = $('#popupDatepicker').val();
		jq('#birthdate').val(adate);
	}
	function showDate(date) {
		   
		alert('The date chosen is ' + date);
	}
        jq("#title").on('keyup', function ()
        {
                if(jq("#title").val().length > 0 || jq("#textInput").val().length > 0 || jq("#imageFile1").val().length > 0)
                {
                        toggleAbilityPostButton(SHOW);
                }
                else if(jq("#title").val().length == 0 && jq("#textInput").val().length == 0 && jq("#imageFile1").val().length == 0)
                {
                        toggleAbilityPostButton(HIDE);
                }
                else
                {
                        toggleAbilityPostButton(HIDE);
                }
        });
        jq("#textInput").on('keyup', function ()
        {
                if(jq("#title").val().length > 0 || jq("#textInput").val().length > 0 || jq("#imageFile1").val().length > 0)
                {
                        toggleAbilityPostButton(SHOW);
                }
                else if(jq("#title").val().length == 0 && jq("#textInput").val().length == 0 && jq("#imageFile1").val().length == 0)
                {
                        toggleAbilityPostButton(HIDE);
                }
                else
                {
                        toggleAbilityPostButton(HIDE);
                }
        });
	jq(document).ready(function(){
		jq(".scrollup").click(function(){ 
			jq('html,body').animate({ scrollTop: 0 }, 'slow');
			return false; 
		});
	});	
</script>
