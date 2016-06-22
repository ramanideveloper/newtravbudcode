<?php

use yii\widgets\ActiveForm;
use frontend\models\Friend;
use yii\helpers\Url;
?> <!-- Chat part --> 
  <div class="tb-chatbox-main fb-pagecontent">
              <div class="chatbox">
                <div class="chatbox-title"> Online <a href="javascript:void(0)"><span class="glyphicon glyphicon-cog"></span></a> </div>
                <div class="chat-list onlinechat">
                  <ul>
                    <li> <a href="javascript:void(0)"> <span><img alt="" class="img-responsive" src="<?= $baseUrl?>/images/ava_1.jpg">
                    	<span class="badge">5</span>
                    </span>
                      <div class="chatlist-title01">User Name</div><div class="chatlist-title02">User Discription</div>
                      </a> </li>
                    <li> <a href="javascript:void(0)"> <span><img alt="" class="img-responsive" src="<?= $baseUrl?>/images/ava_2.jpg">
                    	<span class="badge">3</span>
                    </span>
                      <div class="chatlist-title01">User Name</div><div class="chatlist-title02">User Discription</div>
                      </a> </li>
                   <li> <a href="javascript:void(0)"> <span><img alt="" class="img-responsive" src="<?= $baseUrl?>/images/ava_3.jpg">
                    	<span class="badge">2</span>
                    </span>
                      <div class="chatlist-title01">User Name</div><div class="chatlist-title02">User Discription</div>
                      </a> </li>
                      <li> <a href="javascript:void(0)"> <span><img alt="" class="img-responsive" src="<?= $baseUrl?>/images/ava_1.jpg">
                    	
                    </span>
                      <div class="chatlist-title01">User Name</div><div class="chatlist-title02">User Discription</div>
                      </a> </li>
                      <li> <a href="javascript:void(0)"> <span><img alt="" class="img-responsive" src="<?= $baseUrl?>/images/ava_2.jpg">
                    	
                    </span>
                      <div class="chatlist-title01">User Name</div><div class="chatlist-title02">User Discription</div>
                      </a> </li>
                     
                  </ul>
                </div>
                <div class="chatbox-title"> Away <a href="javascript:void(0)"><span class="glyphicon glyphicon-cog"></span></a> </div>
                <div class="chat-list awaychat">
                  <ul>
                    <li> <a href="javascript:void(0)"> <span><img alt="" class="img-responsive" src="<?= $baseUrl?>/images/ava_2.jpg"></span>
                      <div class="chatlist-title01">User Name</div><div class="chatlist-title02">User Discription</div>
                      </a> </li>
                    <li> <a href="javascript:void(0)"> <span><img alt="" class="img-responsive" src="<?= $baseUrl?>/images/ava_1.jpg"></span>
                      <div class="chatlist-title01">User Name</div><div class="chatlist-title02">User Discription</div>
                      </a> </li>
                  </ul>
                </div>
                <div class="chatbox-title"> Offline <a href="javascript:void(0)"><span class="glyphicon glyphicon-cog"></span></a> </div>
                <div class="chat-list offlinechat">
                  <ul>
                    <li> <a href="#"> <span><img alt="" class="img-responsive" src="<?= $baseUrl?>/images/ava_2.jpg"></span>
                      <div class="chatlist-title01">User Name</div><div class="chatlist-title02">User Discription</div>
                      </a> </li>
                    <li> <a href="#"> <span><img alt="" class="img-responsive" src="<?= $baseUrl?>/images/ava_1.jpg"></span>
                      <div class="chatlist-title01">User Name</div><div class="chatlist-title02">User Discription</div>
                      </a> </li>
                    <li> <a href="#"> <span><img alt="" class="img-responsive" src="<?= $baseUrl?>/images/ava_3.jpg"></span>
                      <div class="chatlist-title01">User Name</div><div class="chatlist-title02">User Discription</div>
                      </a> </li>
                  </ul>
                </div>
              </div>
            </div>