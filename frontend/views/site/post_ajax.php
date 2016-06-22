<div id="post-status-list">  
  <?php
      $time = Yii::$app->EphocTime->time_elapsed_A(time(),$post['post_created_date']);
  ?>
  <div class="tb-panel-box postbox">
     <div class="tb-panel-body01">
        <div class="tb-panel-head clearfix">
           <div class="tb-user-box"> <img alt="" class="img-responsive" src="<?php  echo $post['user']['photo']; ?>"> </div>
           <div class="tb-user-desc"> <span><a class="profilelink" href="#"><?php echo $post['user']['username'] ;?></a></span><span><?php echo $time ;?> <i class="glyphicon glyphicon-globe "></i></span>  </div>
        </div>
        	<?php
                    if(!empty($post['image'])){
                    $eximgs = explode(',',$post['image'],-1);
                    }
                  ?>
                  <?php if($post['post_type'] == 'image') { ?>
                    <div class="tb-img-post" id="temp" name="temp"><?php foreach ($eximgs as $eximg) {?><img src="<?= Yii::$app->getUrlManager()->getBaseUrl() ?><?= $eximg ?>" style="height:100px;width:194px;margin-left:10px;" alt=""><?php }?></div>
                  <?php }  else if($post['post_type'] == 'text') { ?>
                    <div class="tb-text-post" id="temp" name="temp" ><?= $post['post_text'] ?></div>
                   <?php } else if($post['post_type'] == 'link') { ?>
                    <a href="<?= $post['post_text']?>" target="_blank"><div class="tb-img-post" id="temp" name="temp"><img src="<?= $post['image'] ?>" alt=""></div>
                    <div class="tb-text-post" id="temp" name="temp" ><?= $post['link_title'] ?></div>
                    <div class="tb-text-post" id="temp" name="temp" ><?= $post['link_description'] ?></div></a>
                  <?php } 
                 else if($post['post_type'] == 'text and image') { ?>
                    <div class="tb-text-post" id="temp" name="temp" ><?= $post['post_text'] ?></div>
                   <div class="tb-img-post" id="temp" name="temp"><?php foreach ($eximgs as $eximg) {?><img src="<?= Yii::$app->getUrlManager()->getBaseUrl() ?><?= $eximg ?>" style="height:100px;width:194px;margin-left:10px;" alt=""><?php }?></div>
                    
                  <?php } ?>
     </div>
	 <div class="tarrow">
		<a href="#" class="alink"><i class="fa fa-angle-down"></i></a>
		<div class="drawer">
			<div class="opt-box">
				<ul class="iconopt-ul">
					<li class="hidepost">
						<a href="#">
							<span class="title">Hide Post</span>
							<span class="desc">See fewer posts like this</span>
						</a>
					</li>
					<li class="unfollow">
						<a href="#">
							<span class="title">Unfollow Person</span>
							<span class="desc">Stop seeing such posts but stay friends</span>
						</a>
					</li>
				</ul>
				<div class="dline"></div>
				<ul class="siconopt-ul">
					<li class="reportpost">
						<a href="#">Report Photo</a>
					</li>
					<li class="savepost">
						<a href="#">Save Post</a>
					</li>
					<li class="nicon">
						<a href="#">Turn on notification for this post</a>
					</li>
				</ul>				
			</div>
		</div>
	  </div>
           <div class="panel-footer"> <a href="#">Like</a> <a href="#">Comment</a> <a href="#">Share</a> </div>
  </div>
</div>
	   
	