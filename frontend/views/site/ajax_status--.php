<?php //  $session = Yii::$app->session;
        // $id = $session->get('userName');
         //$profile = $session->get('profile');?>
 
              <!-- fetch all post-->
        <?php  foreach($posts as $post){ ?>
        <?php
                $time = Yii::$app->EphocTime->time_elapsed_A(time(),$post['post_created_date']);
               
         ?>
            <div class="tb-panel-box">
              <div class="panel-body">
                <div class="tb-panel-head clearfix">
                  <div class="tb-user-box"> <img alt="" class="img-responsive" src="<?php  echo $post['user']['photo']; ?>"> </div>
                  <div class="tb-user-desc"> <span><a class="profilelink" href="#"><?php echo $post['user']['username'] ;?></a> Updated <a href="#">Status</a></span>  <span><?php echo $time; ?> <i class="glyphicon glyphicon-globe "></i></span> </div>
                </div>
                <div>
                    <p class="tb-img-post" id="temp" name="temp" ><?= $post['post_text'] ?></p>
                </div>
              </div>
              <div class="panel-footer"> <a href="#">Like</a> <a href="#">Comment</a> <a href="#">Share</a> </div>
            </div>
        <?php } ?>