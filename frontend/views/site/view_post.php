<?php
include('includes/header.php');

use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
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

$post_id = $session->get('postid');

$session = Yii::$app->session;
$user_id = (string)$session->get('user_id');

$postdetails = PostForm::find()->where(['_id' => $post_id,'is_deleted'=>'0'])->one();
$postuser = $postdetails['post_user_id'];

$friend = Friend::find()->where(['from_id' => $user_id,'to_id' => $postuser,'status' => '1'])->one();

$unfollow = new UnfollowFriend();
$unfollow = UnfollowFriend::find()->where(['user_id' => $user_id])->one();

$hidepost = new HidePost();
$hidepost = HidePost::find()->where(['user_id' => $user_id])->one();
?>

<!-- body section -->
<section class="inner-body-content fb-page">
	<div class="page-wrapper">
		<div class="container-fluid clearfix inner-fix01 userwall">
			<div class="left-container"> 
				<div class="row fb-pagecontent sm-fix"> 

					<!--Left Part-->
					<div class="col-lg-8 col-md-8 col-sm-7 secondcol">
						<?php
                                                
                                                    if(!(strchr($unfollow['unfollow_ids'],$postuser)))
                                                    {
                                                        if(!(strchr($hidepost['post_ids'],$post_id)))
                                                        {
                                                            if(!strchr($postdetails['custom_notshare'],$user_id))
                                                            {
                                                                if(($postdetails['post_privacy'] != 'Private') || (($postdetails['post_privacy'] == 'Private') && ($user_id == $postuser)))
                                                                {
                                                                ?>
                                                                    <div id="post-status-list"> <?php $this->context->display_last_post($post_id); ?> </div>
                                                                <?php
                                                                }
                                                                else
                                                                {
                                                                    echo '<span class="no-listcontent">As post owner has kept this post Private, you will be not able to see this post !!!</span>';
                                                                }
                                                            }
                                                            else
                                                            {
                                                                echo '<span class="no-listcontent">As post owner don\'t want to share this post with you, you will be not able to see this post !!!</span>';
                                                            }
                                                        }
                                                        else
                                                        {
                                                            echo '<span class="no-listcontent">As you have hide this post, you will be not able to see this post !!!</span>';
                                                        }
                                                    }
                                                    else
                                                    {
                                                        echo '<span class="no-listcontent">As you are unfollow of this post owner, you will be not able to see this post !!!</span>';
                                                    }
                                                ?>
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
                    <div class="col-md-4 col-sm-12">
                        <p>These people or lists</p>
                    </div>
                    <div class="col-md-8 col-sm-12">					       
                        <input type="text" class="js-example-theme-multiple" multiple="multiple"/>
                    </div>
                    <div class="clear"></div>
                    <div class="col-md-4 col-sm-12">
                        <p>Friends of tagged</p>
                    </div>
                    <div class="col-md-8 col-sm-12">					       
                        <input type="checkbox"/>
                        <p>Anyone tagged will be able to see the post</p>
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
                        <input type="text" class="js-example-theme-multiple" multiple="multiple"/>				
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
                <button class="btn btn-primary btn-sm popup-modal-dismiss custom-modal" onClick="setCustomSecurity(this)" type="button">Save Changes</button>
                <button class="btn btn-primary btn-sm popup-modal-dismiss custom-modal" type="button">Cancel</button>
            </div>
        </div>
    </div>
</div>

<script>
function add_to_wall(pid,ntype)
{
    BootstrapDialog.show({
        size: BootstrapDialog.SIZE_SMALL,
        title: 'Add to Wall',
        message: 'Are you sure to add this post to your wall ?',
        buttons: [{
                label: 'Yes',
                action: function (dialogItself) {
                    if (pid != '')
                    {
                        $.ajax({
                        url: '<?php echo Yii::$app->urlManager->createUrl(['site/approve']); ?>',  //Server script to process data
                        type: 'POST',
                        data: 'post_id=' + pid + '&ntype=' + ntype,
                        success: function(data) {
                            if(data == '1')
                            {
                                $("#add_to_wall_"+pid).hide();
                                dialogItself.close();
                            }				
                        },
                    });
                    } else {
                    }
                }
            }, {
                label: 'No',
                action: function (dialogItself) {
                    dialogItself.close();
                }
            }]
    });
}

function approve_tag(pid)
{
    BootstrapDialog.show({
        size: BootstrapDialog.SIZE_SMALL,
        title: 'Give Appoval',
        message: 'Are you sure to approve this post ?',
        buttons: [{
                label: 'Yes',
                action: function (dialogItself) {
                    if (pid != '')
                    {
                        $.ajax({
                        url: '<?php echo Yii::$app->urlManager->createUrl(['site/approvetags']); ?>',  //Server script to process data
                        type: 'POST',
                        data: 'post_id=' + pid,
                        success: function(data) {
                           
                            if(data == '1')
                            {
                                $("#approve_tag_"+pid).hide();
                                dialogItself.close();
                            }				
                        },
                    });
                    } else {
                    }
                }
            }, {
                label: 'No',
                action: function (dialogItself) {
                    dialogItself.close();
                }
            }]
    });
}
    var jq = $.noConflict();
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