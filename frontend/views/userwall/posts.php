<?php
include('includes/header-wall.php');
use yii\widgets\ActiveForm;
use frontend\models\Friend;
use yii\helpers\Url;
use frontend\models\LoginForm;
use frontend\models\PostForm;
use frontend\models\UnfollowFriend;
use frontend\models\HidePost;
use frontend\models\SavePost;
use frontend\models\Like;
use frontend\models\Comment;

 $session = Yii::$app->session;
 $user_id = $userid =  $session->get('user_id');  

$posts = PostForm::getUserPost($user_id);

?>
<div class="container-fluid clearfix inner-fix01 userwall">
    
     <div class="row fb-pagecontent sm-fix">
		 <div  class="user_data">
                     <div class="col-lg-8 col-md-8 col-sm-8 secondcol data-section-about">
			
                        
			<div class="posts">
                           <div class="tb-panel-box02 panel-shadow">
          <div class="panel-body toolbox">
              <div class="tb-inner-title01 clearfix" style="margin-bottom:0; padding-bottom:0; border-bottom:none;"> 


                 <span>Logged in as <a href="#" class="no-ul"><?= ucfirst($result['fname']).' '.ucfirst($result['lname']);?></a> <!-- <div class="post_loadin_img"></div>     --> </span>              

              </div>
                
				  <div class="tarrow">
					<a href="#" class="alink"><i class="fa fa-angle-down"></i></a>
					<div class="drawer">
						
						<ul class="sort-ul">
							<li><a href="#">By Friends</a></li>						
							<li><a href="#">By Followers</a></li>						
							<li><a href="#">Recent Posts</a></li>
						  </ul>	
					</div>
				  </div>
				<div class="tb-user-post-middle">
					  <textarea rows="2" title="Add a comment" class="form-control" id="textInput" name="textInput"></textarea>           
				</div>

				<input type="text" name="url" size="64" id="url"  style="display: none"/>
				<input type="button" name="attach" value="Attach" id="mark" style="display: none" />
				<form enctype="multipart/form-data" name="imageForm">
					<div id="image-holder"></div>
										
					<div class="tb-user-post-bottom clearfix">
						<div class="tb-user-icon"> <label class="myLabel">
								<!-- uplaod image -->
								
							<input type="file" id="imageFile1" name="imageFile1[]" required="" multiple="true"/>
				
							<span aria-hidden="true" class="glyphicon glyphicon-camera tb-icon-fix001"></span>
							</label><a href="#" class="tb-icon-fix001"><span aria-hidden="true" class="glyphicon glyphicon-user"></span></a> <a href="#" class="tb-icon-fix001"><span aria-hidden="true" class="glyphicon glyphicon-pencil"></span></a> <a href="#" class="tb-icon-fix001"><span aria-hidden="true" class="glyphicon glyphicon-map-marker"></span></a><a href="#" class="tb-icon-fix001"><span aria-hidden="true" class="glyphicon glyphicon-link"></span></a> </div>
						  <div class="user-post fb-btnholder">
							<div class="dropdown tb-user-post">
							  <button aria-expanded="false" data-toggle="dropdown" type="button" class="btn btn-default dropdown-toggle btn-sm custom-select custom-select-posting-security"><span class="glyphicon glyphicon-<?= $post_dropdown_class?>"></span> <?= $my_post_view_status?> <span class="caret"></span></button>
							  <ul class="dropdown-menu" id="posting-security">
								<li class="sel-private"><a href="javascript:void(0)" onClick="setSecuritySelect(this,'Private')"><span class="glyphicon glyphicon-lock"></span> Private</a></li>
								<li class="divider"></li>
								<li class="sel-friends selected"><a href="javascript:void(0)" onClick="setSecuritySelect(this,'Friends')"><span class="glyphicon glyphicon-user"></span> Friends</a></li>
								<li class="divider"></li>
								<li class="sel-public"><a href="javascript:void(0)" onClick="setSecuritySelect(this,'Public')"><span class="glyphicon glyphicon-globe"></span> Public</a></li>
								<li class="divider"></li>
								<li class="sel-custom"><a href="#custom-share" class="popup-modal" onClick="sendSelId('posting-security')"><span class="glyphicon glyphicon-cog"></span> Custom</a></li>
							  </ul>
                                                          <input type="hidden" name="post_privacy" id="post_privacy" value="<?= $my_post_view_status?>"/>
							<input type="hidden" name="link_title" id="link_title" />
							<input type="hidden" name="link_url" id="link_url" />
							<input type="hidden" name="link_description" id="link_description" />
							<input type="hidden" name="link_image" id="link_image" />
							  <button class="btn btn-primary btn-sm"  onclick="postStatus();" type="button" name="post" id="post"><span class="glyphicon glyphicon-send"></span>Post</button>
							</div>
						  </div>
					</div>
				</form>
		  </div>
	</div>
                                  <!-- fetch all post--> 
                                  <div id="ajax-content"></div>
                                  
                                  <div id="post-status-list">    <?php include('includes/ajax_status.php'); ?></div>
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



<script>
function sharenow(pid){
    if(pid != ''){
    $.ajax({
        type: 'POST',
        url: '<?php echo Yii::$app->urlManager->createUrl(['site/sharenowwithfriends']); ?>',
        data: "shareid=" + pid,
        success: function(data){
             if(data)
             {
                  $('#share_success').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
             }
             else
             {
                 $('#share_fail').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
             }
         }

    }); 
    }else{
         $('#share_fail').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
    }
}
</script>
<script>
	
	var jq = $.noConflict();
	jq(document).ready(function(){			
	
		jq('.popup-modal').magnificPopup({
			type: 'inline',
			preloader: false,
			focus: '#username',
			modal: true
		});	
	jq(document).on('click', '.popup-modal-dismiss', function (e) {
			e.preventDefault();
			jq.magnificPopup.close();
		});		
	});
	
</script>