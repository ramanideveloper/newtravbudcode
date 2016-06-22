<?php 
use yii\helpers\Url;
?>   
<div class="tb-box-body clearfix">
              <div class="tb-inner-title01">How often people viewed you</div>
               <?php if(count($visitors)>0){?> <div class="tb-inner-title01"><a href="<?php echo Url::to(['site/view-visitors']); ?>"><?php echo count($visitors);?> profile views</a></div>
               <div style="display: none"> 
                <?php 
                    foreach ($visitors AS $user_visitor)
                    {
                        ?><a href=" <?php $id = $user_visitor['visitor_id'];echo Url::to(['userwall/index', 'id' => "$id"]); ?>"><?php echo $user_visitor['user']['fname'].' '.$user_visitor['user']['lname'];?></a></br><?php 

                    }
                ?></div><?php } ?>
              <div class="tb-inner-imgbox"><img alt="viewed-map-img" src="<?= $baseUrl?>/images/chart.png"></div>
              
              
          </div>