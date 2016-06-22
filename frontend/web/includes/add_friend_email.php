<?php

use yii\widgets\ActiveForm;
use frontend\models\Friend;
use yii\helpers\Url;
?> 
<div class="col-lg-4 col-md-4 col-sm-4 thirdcol">
        <div class="tb-panel-box wall-right-section  panel-shadow">
          <div class="panel-body">
          
              
              <div class="tb-box-body clearfix">
				<div class="invite-form">
                  <input type="text" aria-label="Freind Email" size="45" placeholder="Freind Email" autocomplete="off" id="friend_email" name="friend_email" value="" class="inputtext ci_login form-control">
                  <button class="btn btn-primary btn-sm bt-cfix" type="submit" onclick="send_invitation()" class="" value="1">Send Invitation</button>
				</div>
              </div>
              
					<div class="tb-box-body clearfix">
						<div class="tb-inner-title01">Advertisement</div>
						<div class="tb-inner-imgbox"><img src="<?= $baseUrl ?>/images/add-1.png"></div>
						<!--<div class="tb-inner-imgbox"><img src="<?= $baseUrl ?>/images/add-2.png"></div>-->

					</div>
               </div>
             </div>
           </div>
		   