	include('includes/header.php');

	use yii\helpers\Html;
	use yii\widgets\ActiveForm;
	use yii\mongodb\ActiveRecord; 
	use frontend\models\LoginForm;
	use frontend\models\PostForm;
	use frontend\models\Friend; 
	use yii\helpers\Url;   
	use frontend\models\Like;
	use frontend\models\Comment;
	use frontend\models\UnfollowFriend;
	use frontend\models\HidePost; 
	use frontend\models\SavePost;
	use frontend\models\Personalinfo;
	use frontend\models\SecuritySetting;
	use yii\helpers\ArrayHelper;


	$count = count($posts);
	$session = Yii::$app->session;
	$user_id = (string) $session->get('user_id');
	$result_security = SecuritySetting::find()->where(['user_id' => $user_id])->one();
	if ($result_security) {
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
	}
	$login_img = $this->context->getimage($result['_id'],'thumb');
?>



<section class="inner-body-content fb-page">
<?php 
if(isset($status) && $status == '0'){ ?>
	<div class="unreg-modal unreg-notice notice">
		<div class="status-note"><div class="success-note">Confirmation link sent successfully!</div><div class="error-note">Error occured. Please try again!</div></div>
		
	  <div class="icon-holder"><i class="fa fa-info"></i></div>
	  <div class="desc-holder">		
		We're almost there! We just need you to confirm your email address.Check your <?= $email?> account or <a onclick="account_verify(1)" href="javascript:void(0)">request a new confirmation link</a>
	  </div>
	  <div class="post_loadin_img"></div>
	</div>
	<?php } ?>
	<div class="page-wrapper">		
		<div class="container-fluid clearfix inner-fix01 userwall">
			<div class="left-container"> 
			<?php if(isset($status) && $status == '0') {?>	
			
				<!--<span><a href="<?php echo Yii::$app->urlManager->createUrl(['site/verify']); ?>" class="confirm-link">confirmation set</a></span>-->
				
			<?php } ?>
				<div class="row fb-pagecontent sm-fix"> 

<!-- body section -->
					<!--Left Part-->
					<div class="col-lg-8 col-md-8 col-sm-7 secondcol">
					
						<div class="tb-panel-box02 panel-shadow">
							<div class="panel-body toolbox">
							
	
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
											<span class="more-option"><svg height="100%" width="100%" viewBox="0 0 50 50"><path class="Ce1Y1c" d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/></path></svg></span></a>&nbsp;
										<div class="drawer">

											<ul class="sort-ul">
                                                <li class="disable_share"><span class="dis_share check-option">✓</span><a href="javascript:void(0)">Disable Sharing</a></li>																		
												<li class="disable_comment"><span class="dis_comment check-option">✓</span><a href="javascript:void(0)">Disable Comments</a></li>																		
												<li class="cancel_post"><a href="javascript:void(0)" data-name="cancel_post" onclick="closeAllDrawers(this)">Cancel Post</a></li>																		
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
												<input type="text" id="taginput" name="taginput" class="js-example-theme-multiple" style="width: 	100%;"/>
											</div>
										</div>-->										
									</div>
									<div id="newpostgenerate">
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
											<input type="text" class="getplacelocation" value="" id="cur_loc" placeholder="Where are you?" />
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
		<input type="file" id="imageFile1" name="imageFile1[]" required="" data-class="#newpostgenerate #image-holder .img-row" multiple="true"/>
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

						<!-- Print Status Div start-->
						<div id="ajax-content"></div>
						
						<div class="newpost-update" style="display:none"><div id="newPost"></div></div>
						
						<div id="post-status-list"> <?php include('includes/ajax_status.php'); ?> </div>
						<!-- Print Status Div completed -->
					</div>

					<!--Right Part-->
					<div class="col-lg-4 col-md-4 col-sm-5 thirdcol">
						<div class="tb-panel-box panel-shadow">
							<div class="panel-body">

								<div class="tb-box-body clearfix">
									<div class="tb-inner-title01">Travel Stories</div>
									<div class="tb-ls-mainbox clearfix">
										<div class="tb-ls-userimg"><img src="<?= $baseUrl ?>/images/p-img-1.jpg" class="img-responsive" ></div>
										<div class="tb-ls-user-text"> 
											<a href="#">
												<span>Petra Jorder is Nice place.</span>
												<span class="glyphicon glyphicon-menu-right"></span>
											</a> 

										</div>

									</div>

									<div class="tb-ls-mainbox clearfix">
										<div class="tb-ls-userimg"><img src="<?= $baseUrl ?>/images/p-img-2.jpg" class="img-responsive" ></div>
										<div class="tb-ls-user-text"> <a href="#"><span>3 Day Holiday in Jordan Petra</span><span class="glyphicon glyphicon-menu-right"></span></a> </div>

									</div>
									<div class="tb-ls-mainbox clearfix">
										<div class="tb-ls-userimg"><img src="<?= $baseUrl ?>/images/p-img-1.jpg" class="img-responsive" ></div>
										<div class="tb-ls-user-text"> <a href="#"><span>Petra Jorder is Nice place.</span><span class="glyphicon glyphicon-menu-right"></span></a> </div>

									</div>

									<div class="tb-ls-mainbox clearfix">
										<div class="tb-ls-userimg"><img src="<?= $baseUrl ?>/images/p-img-2.jpg" class="img-responsive" ></div>
										<div class="tb-ls-user-text"> <a href="#"><span>3 Day Holiday in Jordan Petra</span><span class="glyphicon glyphicon-menu-right"></span></a> </div>

									</div>
									<div class="tb-inner-link01 clearfix"> <span><a href="javascript:void(0)">Read More</a></span><span><a href="javascript:void(0)">View All</a></span> </div>
								</div>

		<?php include('includes/people_you_may_know_content.php'); ?>

								<div class="tb-box-body clearfix">
									<div class="tb-inner-title01">Advertisement</div>
									<div class="tb-inner-imgbox"><img src="<?= $baseUrl ?>/images/add-1.png"></div>
									<div class="tb-inner-imgbox"><img src="<?= $baseUrl ?>/images/add-2.png"></div>

								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- Chat part --> 
			<div class="tb-chatbox-main fb-pagecontent">
				<div class="chatbox">
					<div class="chatbox-title"> Online<!-- <a href="javascript:void(0)"><span class="glyphicon glyphicon-cog"></span></a> --></div>
					<div class="chat-list onlinechat">
						<ul>
							<li> <a href="javascript:void(0)"> <span><img alt="" class="img-responsive" src="<?= $baseUrl ?>/images/ava_1.jpg">
										<span class="badge">5</span>
									</span>
									<div class="chatlist-title01">User Name</div><div class="chatlist-title02">User Discription</div>
								</a> </li>
							<li> <a href="javascript:void(0)"> <span><img alt="" class="img-responsive" src="<?= $baseUrl ?>/images/ava_2.jpg">
										<span class="badge">3</span>
									</span>
									<div class="chatlist-title01">User Name</div><div class="chatlist-title02">User Discription</div>
								</a> </li>
							<li> <a href="javascript:void(0)"> <span><img alt="" class="img-responsive" src="<?= $baseUrl ?>/images/ava_3.jpg">
										<span class="badge">2</span>
									</span>
									<div class="chatlist-title01">User Name</div><div class="chatlist-title02">User Discription</div>
								</a> </li>
							<li> <a href="javascript:void(0)"> <span><img alt="" class="img-responsive" src="<?= $baseUrl ?>/images/ava_1.jpg">

									</span>
									<div class="chatlist-title01">User Name</div><div class="chatlist-title02">User Discription</div>
								</a> </li>
							<li> <a href="javascript:void(0)"> <span><img alt="" class="img-responsive" src="<?= $baseUrl ?>/images/ava_2.jpg">

									</span>
									<div class="chatlist-title01">User Name</div><div class="chatlist-title02">User Discription</div>
								</a> </li>

						</ul>
					</div>
					<div class="chatbox-title"> Away</div>
					<div class="chat-list awaychat">
						<ul>
							<li> <a href="javascript:void(0)"> <span><img alt="" class="img-responsive" src="<?= $baseUrl ?>/images/ava_2.jpg"></span>
									<div class="chatlist-title01">User Name</div><div class="chatlist-title02">User Discription</div>
								</a> </li>
							<li> <a href="javascript:void(0)"> <span><img alt="" class="img-responsive" src="<?= $baseUrl ?>/images/ava_1.jpg"></span>
									<div class="chatlist-title01">User Name</div><div class="chatlist-title02">User Discription</div>
								</a> </li>
						</ul>
					</div>
					<div class="chatbox-title"> Offline</div>
					<div class="chat-list offlinechat">
						<ul>
							<li> <a href="#"> <span><img alt="" class="img-responsive" src="<?= $baseUrl ?>/images/ava_2.jpg"></span>
									<div class="chatlist-title01">User Name</div><div class="chatlist-title02">User Discription</div>
								</a> </li>
							<li> <a href="#"> <span><img alt="" class="img-responsive" src="<?= $baseUrl ?>/images/ava_1.jpg"></span>
									<div class="chatlist-title01">User Name</div><div class="chatlist-title02">User Discription</div>
								</a> </li>
							<li> <a href="#"> <span><img alt="" class="img-responsive" src="<?= $baseUrl ?>/images/ava_3.jpg"></span>
									<div class="chatlist-title01">User Name</div><div class="chatlist-title02">User Discription</div>
								</a> </li>
						</ul>
					</div>
				</div>
			</div>

		</div>
		<!-- Left Navigation -->   
		<?php
		include('includes/left-menu.php');
		?>
		<!-- footer section -->
		<?php include('includes/footer.php'); ?>
		<a class="scrollup">
			<i class="fa fa-arrow-circle-up"></i>
		</a>
		
		<a href="#add-newpost" class="popup-modal addpost-sc">
			<i class="fa fa-plus"></i>
		</a>
	</div>
</section>
<div id="custom-share" class="white-popup-block mfp-hide">
    <div class="modal-title">
        <h4>Custom Privacy</h4>
        <a class="popup-modal-dismiss popup-modal-close" href="#"><i class="fa fa-close"></i></a>
    </div>
    <div class="modal-detail">	
    	<div class="sharewith_blk csecurity_blk">
            <div class="icon_holder">
                <i class="fa fa-plus"></i>
            </div>
            <div class="desc_holder">
                <h4>Share with</h4>
                <div class="row">				
                	<div>
	                    <div class="col-md-4 col-sm-12">
	                        <p>These people or lists</p>
	                    </div>
	                    <div class="col-md-8 col-sm-12">	
	                    <select id='customin1' name='sharewith' class="js-example-theme-multiple" multiple="multiple" style="width: 100%;"></select>				       
	                        <span id='fmsg'></span>
	                    </div>
	                </div>
	                <div style="clear: both;"></div>
                    <div>                   	
	                    <div class="col-md-4 col-sm-12 customchk" style="display: none;">
	                        <p>Friends of tagged</p>
	                    </div>
	                    <div class="col-md-8 col-sm-12">					       
	                        <input type="checkbox" name='customchk' id='customchk' class='customchk'  style='display: none;'>
	                    </div>
                    </div>
                    <div style="clear: both;"></div>
                    <div>
	                    <div class="col-md-4 col-sm-12"></div>
	                    <div class="col-md-8 col-sm-12">					       
	                        <p>Anyone tagged will be able to see the post</p>
	                    </div>
	                </div>
                </div>
            </div>
        </div>
        <div class="dontshare_blk csecurity_blk">	
            <div class="icon_holder">
                <i class="fa fa-close"></i>
            </div>
            <div class="desc_holder">
                <h4>Don't share with</h4>
                <div class="row">				
                    <div class="col-md-4 col-sm-12">
                        <p>These people or lists</p>
                    </div>
                    <div class="col-md-8 col-sm-12">					       
                    <select id="customin3" name="sharenot" class="js-example-theme-multiple" multiple="multiple" style="width: 100%;"></select>			
                        <p>
                            Anyone you include here or have on your restricted list won't be able to see this post unless you tag them. We don't let people know when you choose to not share something with them.
                        </p>
                    </div>
                </div>
            </div>
        </div>	
        <div class="fb-btnholder bottom_blk">
            <div class="pull-right">
                <div class="modalId"></div>
                <button class="btn btn-primary btn-sm popup-modal-dismiss custom-modal" type="submit">Save Changes</button>
                <button class="btn btn-primary btn-sm popup-modal-dismiss custom-modal" type="reset">Cancel</button>
            </div>
        </div>
    </div>

</div>

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
						<select id="taginput" class="taginput" placeholder="Who are with you?" multiple="multiple"></select>		
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


	

	var data1=<?php echo json_encode($nrusrfrdlist); ?>;
	var data2=<?php echo json_encode($frdlist); ?>;
		
	//var jq = $.noConflict();
	
	jq(function() {
        jq.contextMenu({
            selector: '.context-menu-one', 
            callback: function(key, options) {
                var m = "clicked: " + key;
                window.console && console.log(m) || alert(m); 
            },
            items: {
                "edit": {name: "Edit", icon: "edit"},
                "cut": {name: "Cut", icon: "cut"},
               copy: {name: "Copy", icon: "copy"},
                "paste": {name: "Paste", icon: "paste"},
                "delete": {name: "Delete", icon: "delete"},
                "sep1": "---------",
                "quit": {name: "Quit", icon: function(){
                    return 'context-menu-icon context-menu-icon-quit';
                }}
            }
        });

        jq('.context-menu-one').on('click', function(e){
            console.log('clicked', this);
        })    
    });

	jq(document.body).on('mouseover', 'a.newshow-pop' ,function() {
		jq('.webui-popover').css("display", "none");
		$('a.newshow-pop').webuiPopover('destroy');
		
		var settings = {
				trigger:'hover',
				title:'WebUI Popover ',
				content:'<p>This is wesdbui popover demo.</p><p>just enjoy it and have fun !</p>',
				//width:auto,						
				multi:true,						
				closeable:false,
				style:'',
				delay:300,
				padding:true,
				backdrop:false
		};

		$('a.newshow-pop').webuiPopover('destroy').webuiPopover(settings);
	});

	jq(document.body).on('mouseout', 'a.newshow-pop' ,function(){
		jq('.webui-popover').css("display", "none");
	});



	jq(document).ready(function () {

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

	var $e1 = jq("#customin1");
	var $e2 = jq("#customin3");

	$e1.select2();
	$e2.select2();

	$e1.on("select2:select", function (e) { compare(e.params.data, $e2); });
	$e2.on("select2:select", function (e) { compare(e.params.data, $e1); });


	$e1.on("change", function(e) { customchk(e); });

	$e1.select2({
		data: data2,
		tags: false,
		//minimumInputLength: false,
		escapeMarkup: function (markup) { return markup; },
		templateResult: formatRepo,
		templateSelection: formatRepoSelection
	});

	$e2.select2({
		data: data1,
		tags: false,
		//minimumInputLength: 2,
		escapeMarkup: function (markup) { return markup; },
		templateResult: formatRepo,
		templateSelection: formatRepoSelection

	});


	// End Custom Code

	// Tag Code

	jq('.taginput').select2();

	jq('.taginput').select2({
		data: data1,
		tags: false,
		//minimumInputLength: 1,
		escapeMarkup: function (markup) { return markup; },
		templateResult: formatRepo,
		templateSelection: formatRepoSelection
	});

	function tagareacontent() {	
		var tarray = [];
		jq('.tag-holder .select2-selection__choice').each(function() {
		    var text = $(this).clone().children().remove().end().text();
		    text = text.trim();
		    if(text != '') {
		    	tarray.push(text.trim());
		    }
		});

		str = '-- with ';
		if(tarray.length == 1) {
			str += '<a class="add-tag" href="javascript:void(0)">' + tarray[0] + '</a>.';
		} else if(tarray.length == 2) {
			str += '<a class="add-tag" href="javascript:void(0)">' + tarray[0] + '</a> and ' + "<a href='javascript:void(0)'>" + tarray[1] + '</a>.';
		} else if(tarray.length > 2) {
			var citrus = tarray.slice(1);
			var dcont = citrus.join("<br/>");
			str += '<a class="add-tag" href="javascript:void(0)">' + tarray[0] + '</a> and ' + '<a href="#" class="newshow-pop right-bottom" data-placement="right-bottom" data-content="'+dcont+'">' + (tarray.length - 1) + ' others</a><span id="auto" class="content"></span>.';
		} else {
			str = '';
		}

		$(".tags-added").html(str).trigger("change");
	}

	jq("#taginput").on("select2:open", function (e) { tagareacontent("select2:open", e)	; });
	jq("#taginput").on("select2:close", function (e) { tagareacontent("select2:open", e); });
	jq("#taginput").on("select2:select", function (e) { tagareacontent("select2:open", e); });

	// End Tag Code
	});


	// START EDit Post Link for tag 
	jq(document.body).on('click', '.popup-modal', function() {
		var editpostid = jq(this).data('editpostid');
		if(editpostid) {
			var taginput = jq("#edittaginput"+editpostid).data("taginput");

			if (taginput != undefined || taginput != null || taginput != '') {
				var newtaginput = taginput.split(',');
				jq("#edittaginput"+editpostid).select2().val(newtaginput).trigger("change");
			}
			 
		}
	});
	// END Edit Post Link for tag 


	// START Share Post Link for tag 
	jq(document.body).on('click', '.sharelnk', function() {
		var editpostid = jq(this).data('editpostid');
		if(editpostid) {
			var taginput = jq("#sharetag"+editpostid).data("taginput");
			if (taginput != undefined || taginput != null || taginput != '') {
				var newtaginput = taginput.split(',');
				jq("#sharetag"+editpostid).select2().val(newtaginput).trigger("change");
			}
			 
		}
	});
	// END Share Post Link for tag 

	
	// END Custom Privacy select2

</script>

<script>
	
   
    jq(document).ready(function () {
  
        jq('.popup-modal').magnificPopup({
            type: 'inline',
            preloader: false,
            focus: '#username',
            modal: true
        });
		jq(document).on('click', '.popup-modal-dismiss', function (e) {
			
            var getit = $(this).parents(".closepopup");
            if(getit.length > 0)
            {
                confirm_cancel_post();
            }
            else
            {
                e.preventDefault();
                jq.magnificPopup.close();
            }
        });
        toggleAbilityPostButton(HIDE);
        $("#title").on('keyup', function ()
        {
            if($("#title").val().length > 0 || $("#textInput").val().length > 0 || $("#imageFile1").val().length > 0)
            {
                toggleAbilityPostButton(SHOW);
            }
            else if($("#title").val().length == 0 && $("#textInput").val().length == 0 && $("#imageFile1").val().length == 0)
            {
                toggleAbilityPostButton(HIDE);
            }
            else
            {
                toggleAbilityPostButton(HIDE);
            }
        });
        $("#textInput").on('keyup', function ()
        {
            if($("#title").val().length > 0 || $("#textInput").val().length > 0 || $("#imageFile1").val().length > 0)
            {
                toggleAbilityPostButton(SHOW);
            }
            else if($("#title").val().length == 0 && $("#textInput").val().length == 0 && $("#imageFile1").val().length == 0)
            {
                toggleAbilityPostButton(HIDE);
            }
            else
            {
                toggleAbilityPostButton(HIDE);
            }
        });
 /*
        jq(document).on('click', '.popup-modal-dismiss', function (e) {
            e.preventDefault();
            jq.magnificPopup.close();
        });
 */

        /*  
         jq("body").css("left",'50%');
         jq("body").css("right",'50%');
         jq("body").animate({width: "100%",left:"0",right:"0"},{duration: 1000});
         */
    });
</script>

<script>
	jq(document).ready(function(){
		jq(".scrollup").click(function(){ 
			jq('html,body').animate({ scrollTop: 0 }, 'slow');
			return false; 
		});
	});	
	
</script>
