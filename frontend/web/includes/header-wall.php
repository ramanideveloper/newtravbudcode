<?php

include('includes/header.php');
  
use yii\widgets\ActiveForm;
use frontend\models\Friend;
use frontend\models\LoginForm;
use frontend\models\Like;
use frontend\models\ProfileVisitor;
use yii\helpers\Url;
use frontend\models\PostForm;

$session = Yii::$app->session;
$request = Yii::$app->request;
$user_id = (string) $session->get('user_id');  
$wall_user_id = (string) $request->get('id');

$result = LoginForm::find()->where(['email' => $email])->one();

$user_img = $this->context->getimage($wall_user_id,'photo');

if(isset($user_basicinfo['cover_photo']) && !empty($user_basicinfo['cover_photo']))
{
    $cover_photo = $user_basicinfo['cover_photo'];
}
else
{
    $cover_photo = 'cover.jpg';
}

$model_friend = new Friend();
$friends = $model_friend->userlist();
$userid = $session->get('user_id');

$visitors = ProfileVisitor::find()->with('user')->where(['user_id' => "$wall_user_id"])->all();


	

//echo '<pre>';print_r($visitors);exit;

?>

<!-- body section -->
<section class="inner-body-content fb-page inner-body-content-wall">
   <div class="page-wrapper no-lmenu">
    <div class="timeline-header-wrapper">

 <div class="cover-container">
        <div class="cover-wrapper">
<!--
			<section class="slider">
				<div id="slider" class="flexslider">
				  <ul class="slides">
						<li>
						<img src="uploads/cover/cover-1.jpg" />
						</li>
						<li>
						<img src="uploads/cover/cover-2.jpg" />
						</li>
						<li>
						<img src="uploads/cover/cover-3.jpg" />
						</li>
						<li>
						<img src="uploads/cover/cover-4.jpg" />
						</li>
						<li>
						<img src="uploads/cover/cover-5.jpg" />
						</li>
						<li>
						<img src="uploads/cover/cover-6.jpg" />
						</li>
						<li>
						<img src="uploads/cover/cover-7.jpg" />
						</li>
						<li>
						<img src="uploads/cover/cover-8.jpg" />
						</li>
						<li>
						<img src="uploads/cover/cover-9.jpg" />
						</li>
						<li>
						<img src="uploads/cover/cover-10.jpg" />
						</li>
					
				  </ul>
				</div>
				<div id="carousel" class="flexslider">
				  <ul class="slides">
						<li>
						<img src="uploads/cover/thumbs/cover-1-thumb.jpg" />
						</li>
						<li>
						<img src="uploads/cover/thumbs/cover-2-thumb.jpg" />
						</li>
						<li>
						<img src="uploads/cover/thumbs/cover-3-thumb.jpg" />
						</li>
						<li>
						<img src="uploads/cover/thumbs/cover-4-thumb.jpg" />
						</li>
						<li>
						<img src="uploads/cover/thumbs/cover-5-thumb.jpg" />
						</li>
						<li>
						<img src="uploads/cover/thumbs/cover-6-thumb.jpg" />
						</li>
						<li>
						<img src="uploads/cover/thumbs/cover-7-thumb.jpg" />
						</li>
						<li>
						<img src="uploads/cover/thumbs/cover-8-thumb.jpg" />
						</li>
						<li>
						<img src="uploads/cover/thumbs/cover-9-thumb.jpg" />
						</li>
						<li>
						<img src="uploads/cover/thumbs/cover-10-thumb.jpg" />
						</li>
				  </ul>
				</div>
			  </section>
			  -->
			
            <?php if($wall_user_id == $user_id){ ?>

            <!--<a href="javascript:void(0);" class="menu-profilepic">-->
			
			<!--
			<div class="cover-change-wrapper">
				<span class="cover-change btn btn-neutral"><i class="fa fa-camera"></i></span>
			</div>-->
            <?php }?>
            <?php     
               // if(isset($cover_photo) && !empty($cover_photo)){
            ?>    
            <!--<img src="profile/<?= $cover_photo?>" alt="">-->
                <?php //}
                //else {
                ?>
                <!--<img src="profile/cover.jpg" alt="">-->
                <?php
               // }
            ?>
            <?php //if($wall_user_id == $user_id){ ?> 
            <!-- </a> -->
            <?php //}?>
          <!--  <div class="cover-progress"></div>-->
        </div>
        
        <div class="cover-resize-wrapper">
            <img src="profile/original.jpg" alt="w3lessons.info">
            <div class="drag-div" align="center">Drag to reposition</div>
            <div class="cover-progress"></div>
        </div>

        <div class="avatar-wrapper">
			<a href="javascript:void(0);" class="menu-profilepic">
            <img class="avatar" src="<?php echo $user_img;?>" alt="User pic">
			<?php /*
			<div class="avatar-change-wrapper">
				<span class="pp-change btn btn-neutral"><i class="fa fa-camera"></i></span>
			</div>
			*/ ?>
			<div class="avatar-change-wrapper">
				<div class="tarrow">
					<a href="javascript:void(0)" class="alink">
						<i class="fa fa-camera"></i><span>Add Photo</span>
					</a>
					<div class="drawer">
						<div class="opt-box">
							<ul class="siconopt-ul">
								<li>
									<a href="/travbudcode/frontend/web/index.php?r=site%2Fbasicinfo"><span class="glyphicon glyphicon-cog"></span>Account Settings</a>
								</li>
								<li class="divider"></li>
								<li>
									<a href="#"><span class="glyphicon glyphicon-user"></span>VIP Member</a>
								</li>
								<li class="divider"></li>
								<li>
									<a href="#"><span class="glyphicon glyphicon-star"></span>Credits</a>
								</li>
								<li class="divider"></li>
								<li>
									<a href="#"><span class="glyphicon glyphicon-usd"></span>Donate</a>
								</li>
								<li class="divider"></li>
								<li>
									<a href="#"><span class="glyphicon glyphicon-saved"></span>Verification</a>
								</li>
								<li class="divider"></li>
								<li>
									<a href="#"><span class="glyphicon glyphicon-certificate"></span>Advertising Manager</a>
								</li>
								<li class="divider"></li>
								<li>
									<a href="/travbudcode/frontend/web/index.php?r=site%2Fsecurity-setting"><span class="glyphicon glyphicon-lock"></span>Security Settings</a>
								</li>
								<li class="divider"></li>
								<li>
									<a href="#"><span class="glyphicon glyphicon-check"></span>Billing Information</a>
								</li>
							</ul>			
						</div>
					</div>
				</div>
			</div>
		
            <?php 
			/*
                if($user_id == $wall_user_id){
            ?>
                  <a href="<?php echo Yii::$app->urlManager->createUrl(['site/profile-picture']); ?>">
				  <div class="avatar-change-wrapper">
					<i class="fa fa-camera"></i><span>Add Photo</span>
				</div>
            <?php
                }
            
                if($user_id == $wall_user_id){
                  echo '</a>';  
                }
			*/	
            ?>
            </a> 
			
      </div>
                    

</div>

    <div class="timeline-statistics-wrapper">
		<div class="dropdown tb-user-post mbl-tabs">
			  <button aria-expanded="false" data-toggle="dropdown" type="button" class="btn btn-default dropdown-toggle btn-sm">Wall <span class="caret"></span></button>
            <ul class="dropdown-menu">	
				<?php /*			
                <li <?php if($_GET['r'] == 'userwall/posts'){?>class=' selected'<?php }?>>
                   
                      <a href="javascript:void(0);" onclick="load_data('posts','<?php echo $wall_user_id;?>')">  Posts
                    </a>
                </li>					
				*/?>
                <li <?php if($_GET['r'] == 'userwall/gallery'){?>class='selected'<?php }?>>
                   
                     <a href="javascript:void(0);" onclick="load_data('gallery','<?php echo $wall_user_id;?>')">  Gallery
                    </a>
                </li>
                <li <?php if($_GET['r'] == 'userwall/contributions'){?>class='selected'<?php }?>>
                 
                     <a href="javascript:void(0);" onclick="load_data('contributions','<?php echo $wall_user_id;?>')">   Contributions
                    </a>
                </li>
                <?php if($userid == $user_basicinfo['_id']) {?>
                <li <?php if($_GET['r'] == 'userwall/saved'){?>class='selected'<?php }?>>
                  
                     <a href="javascript:void(0);" onclick="load_data('saved','<?php echo $wall_user_id;?>')">   Saved
                    </a>
                </li>
                <?php }?>
                <li <?php if($_GET['r'] == 'userwall/index'){?>class='selected'<?php }?>>
                    <a href="javascript:void(0);" onclick="load_data('index','<?php echo $wall_user_id;?>')">    Wall
                    </a>
                </li>
				
            </ul>
		</div>
        <div class="tabs">
            <ul class="tabrow">
				<?php /*
                <li id="posts" <?php if($_GET['r'] == 'userwall/posts'){?>class='selected'<?php }?>>
                    <a href="javascript:void(0);" onclick="load_data('posts','<?php echo $wall_user_id;?>')">Posts</a>
                </li>
				*/?>
                <li id="gallery" <?php if($_GET['r'] == 'userwall/gallery'){?>class='selected'<?php }?>>
                     <a href="javascript:void(0);" onclick="load_data('gallery','<?php echo $wall_user_id;?>')">
                        Gallery
                    </a>
                </li>
                <li id="contributions" <?php if($_GET['r'] == 'userwall/contributions'){?>class='selected'<?php }?>>
                     <a href="javascript:void(0);" onclick="load_data('contributions','<?php echo $wall_user_id;?>')">
                        Contributions
                    </a>
                </li>
                  <?php if($userid == $user_basicinfo['_id']) {?>
                <li id="saved" <?php if($_GET['r'] == 'userwall/saved'){?>class='selected'<?php }?>>
                   
                      <a href="javascript:void(0);" onclick="load_data('saved','<?php echo $wall_user_id;?>')">  Saved
                    </a>
                </li>
                <?php }?>
               
              
                <li id="index" <?php if($_GET['r'] == 'userwall/index'){?>class='selected'<?php }?>>
                   
                      <a href="javascript:void(0);" onclick="load_data('index','<?php echo $wall_user_id;?>')">  Wall
                    </a>
                </li>
                
            </ul>
        </div>
        <div class='user-info'>
            <div class="timeline-name-wrapper-wall">
                <div class="wall-name">
                        <a href="javascript:void(0);"><?php echo $user_basicinfo['fname'].' '.$user_basicinfo['lname'];?></a>
						<span class="online-status"><i class="fa fa-check-circle"></i></span>
                </div>
                <div class="address">   
                      <?php if(!empty($user_basicinfo['city'])){?> 
                      <div class="user-address"><span class="title">Live in :</span><span> <?php echo $user_basicinfo['city'].', '.$user_basicinfo['country']; ?></span></div><?php }?>
					  <div class="clear"></div>
                      <?php if(!empty($user_data['current_city'])){?> 
                    <div class="user-address"> <span class="title">Currently in :</span><span><?php echo $user_data['current_city'];?></span></div>
                      <?php }?>
                </div>				
            </div>
            <div class="extra-links fb-btnholder">
                <?php if(count($visitors)>0){?>
<!--                <a href=""><?php echo count($visitors);?> visitor</a><div class="clear"></div>-->
                
                <div> 
                <?php 
                    foreach ($visitors AS $user_visitor)
                    {
                        ?><!--       <a href=" <?php $id = $user_visitor['visitor_id'];echo Url::to(['userwall/index', 'id' => "$id"]); ?>"><?php echo $user_visitor['user']['fname'].' '.$user_visitor['user']['lname'];?></a></br>--><?php 

                    }
                ?></div>
                
                <?php }?>
                <?php if($user_id != $wall_user_id){
                    
                    $is_friends_or_tobe_friends = $model_friend->friends_or_tobe_friends($user_id,$wall_user_id);

                    if(!$is_friends_or_tobe_friends) {
                ?>
                
                 <a href="javascript:void(0);" id="addfriend_wall" class="ibtn" title="Add Friend" onclick="addfriend('<?php echo $wall_user_id;?>')"><i class="fa fa-user-plus"></i></a><span id="sendmsg_<?php echo $wall_user_id;?>" style="display:none" class="request-sent"></span>				
                 <?php  }?>
                <a href="javascript:void(0);" class="ibtn" title="Message"><i class="fa fa-envelope"></i></a>
                <?php }?>
            </div>
        </div>
      
    </div>


</div>
 <!-- user wall section start-->
     <!--sonali work start-->
    
   
    <div class="inner-tabs">
	
	<!-- <p><span class="context-menu-one btn btn-neutral">right click me</span></p> -->
	
	
        <ul class="wallsub">
            <li id="friends" class="selected">

                <a href="javascript:void(0);" onclick="load_data('friends','<?php echo $wall_user_id;?>')"><span class="tabnumber"><?php $user_friends =  Friend::getuserFriends($wall_user_id);?> <?php if(count($user_friends)>0){ echo count($user_friends);} ?></span>Friends

           

                </a>
            </li>
            <?php
            $total_pictures = PostForm::getPics($wall_user_id);
            $profile_albums = PostForm::getProfilePics($wall_user_id);
            $total_profile_albums = count($profile_albums);
            $cover_albums = PostForm::getCoverPics($wall_user_id);
            $total_cover_albums = count($cover_albums);
            $totalcounts = $total_pictures + $total_profile_albums + $total_cover_albums;
            ?>
            <li id="photos">
                <a href="javascript:void(0);" onclick="load_data('photos','<?php echo $wall_user_id;?>')"><span class="tabnumber"><?php if($totalcounts>0){ echo $totalcounts; }?></span>Photos
                </a>
            </li>
            <li id="destinations">
                <a href="javascript:void(0);" onclick="load_data('destinations','<?php echo $wall_user_id;?>')"><span class="tabnumber">3</span>Destinations
                </a>
            </li>
            <li id="likes">

                <a href="javascript:void(0);" onclick="load_data('likes','<?php echo $wall_user_id;?>')"><span class="tabnumber"><?php  $likes = Like::getUserPostLike($wall_user_id); if(count($likes)>0){ echo count($likes); }?></span>Likes
                </a>
            </li>
            <li id="refers">
                <a href="javascript:void(0);" onclick="load_data('refers','<?php echo $wall_user_id;?>')"><span class="tabnumber">30</span>Refers
                </a>
            </li>
            <li id="endorsements">
                <a href="javascript:void(0);" onclick="load_data('endorsements','<?php echo $wall_user_id;?>')"><span class="tabnumber">22</span>Endorsements
                </a>
            </li>
        </ul>
     </div>
     <script>
		$(function() {
			$(".li").click(function(e) {
			  e.preventDefault();
			  $("li").removeClass("selected");
			  $(this).addClass("selected");
			});
		});
	</script>
	
	<div id="change-modal-pp" class="white-popup-block mfp-hide">
		<div class="change-pp">
			<div class="section upload">
				<div class="modal-title">
					<h4>Upload A Pic</h4>
					<a class="popup-modal-dismiss popup-modal-close" href="#"><i class="fa fa-close"></i></a>
				</div>
				<div class="modal-detail">	
					
					<div class="fb-btnholder bottom_blk">
						<div class="pull-right">
							<div class="modalId"></div>
							<button class="btn btn-primary btn-sm popup-modal-dismiss custom-modal" type="submit">Save Changes</button>
							<button class="btn btn-primary btn-sm popup-modal-dismiss custom-modal" type="reset">Cancel</button>
						</div>
					</div>
				</div>
			</div>	

			<div class="section camera">
				<div class="modal-title">
					<h4>Take Photo</h4>
					<a class="popup-modal-dismiss popup-modal-close" href="#"><i class="fa fa-close"></i></a>
				</div>
				<div class="modal-detail">	
					
					<div class="fb-btnholder bottom_blk">
						<div class="pull-right">
							<div class="modalId"></div>
							<button class="btn btn-primary btn-sm popup-modal-dismiss custom-modal" type="submit">Save Changes</button>
							<button class="btn btn-primary btn-sm popup-modal-dismiss custom-modal" type="reset">Cancel</button>
						</div>
					</div>
				</div>
			</div>	
			
			<div class="section gallery">
				<div class="modal-title">
					<h4>Choose photo from my photos</h4>
					<a class="popup-modal-dismiss popup-modal-close" href="#"><i class="fa fa-close"></i></a>
				</div>
				<div class="modal-detail">	
					
					<div class="fb-btnholder bottom_blk">
						<div class="pull-right">
							<div class="modalId"></div>
							<button class="btn btn-primary btn-sm popup-modal-dismiss custom-modal" type="submit">Save Changes</button>
							<button class="btn btn-primary btn-sm popup-modal-dismiss custom-modal" type="reset">Cancel</button>
						</div>
					</div>
				</div>
			</div>	

		</div>
		
		<div class="change-cover">
			<div class="section upload">
				<div class="modal-title">
					<h4>Upload A Pic</h4>
					<a class="popup-modal-dismiss popup-modal-close" href="#"><i class="fa fa-close"></i></a>
				</div>
				<div class="modal-detail">	
					
					<div class="fb-btnholder bottom_blk">
						<div class="pull-right">
							<div class="modalId"></div>
							<button class="btn btn-primary btn-sm popup-modal-dismiss custom-modal" type="submit">Save Changes</button>
							<button class="btn btn-primary btn-sm popup-modal-dismiss custom-modal" type="reset">Cancel</button>
						</div>
					</div>
				</div>
			</div>	

			<div class="section camera">
				<div class="modal-title">
					<h4>Take Photo</h4>
					<a class="popup-modal-dismiss popup-modal-close" href="#"><i class="fa fa-close"></i></a>
				</div>
				<div class="modal-detail">	
					
					<div class="fb-btnholder bottom_blk">
						<div class="pull-right">
							<div class="modalId"></div>
							<button class="btn btn-primary btn-sm popup-modal-dismiss custom-modal" type="submit">Save Changes</button>
							<button class="btn btn-primary btn-sm popup-modal-dismiss custom-modal" type="reset">Cancel</button>
						</div>
					</div>
				</div>
			</div>	
			
			<div class="section gallery">
				<div class="modal-title">
					<h4>Choose photo from my photos</h4>
					<a class="popup-modal-dismiss popup-modal-close" href="#"><i class="fa fa-close"></i></a>
				</div>
				<div class="modal-detail">	
					
					<div class="fb-btnholder bottom_blk">
						<div class="pull-right">
							<div class="modalId"></div>
							<button class="btn btn-primary btn-sm popup-modal-dismiss custom-modal" type="submit">Save Changes</button>
							<button class="btn btn-primary btn-sm popup-modal-dismiss custom-modal" type="reset">Cancel</button>
						</div>
					</div>
				</div>
			</div>	

		</div>
	
	</div>
