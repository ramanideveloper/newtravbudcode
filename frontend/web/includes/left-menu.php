   <!-- Left Navigation -->   
  
<div class="left-nav" id="leftNavigation">
<ul class="sidebar_menu">
      <?php 
        $user_img = $this->context->getimage($user_id,'thumb');
       ?>
    <li class="user-profile-pic"><a href="#"><i><img src="<?= $user_img?>" alt=""></i> <span><?= ucfirst($result['fname']);?></span> </a></li>
     
	  <li><a href="<?php echo Yii::$app->urlManager->createUrl(['site/index2']); ?>"><i><img src="<?= $baseUrl?>/images/icon_home.png" alt=""></i> <span> Travfeed </span> </a></li>
       <li><a href="#"><i><img src="<?= $baseUrl?>/images/icon_page.png" alt=""></i> <span> Pages </span> </a></li>
      <li><a href="<?php echo Yii::$app->urlManager->createUrl(['site/photography']); ?>"><i><img src="<?= $baseUrl?>/images/icon_photography.png" alt=""></i> <span> Photography </span> </a></li>
      <li><a href="#"><i><img src="<?= $baseUrl?>/images/icon_travtube.png" alt=""></i> <span> TravTube </span> </a></li>
      <li><a href="#"><i><img src="<?= $baseUrl?>/images/icon_event.png" alt=""></i> <span> Community Events </span> </a></li>
      <li><div class="blankspace"></div></li>
      <li><a href="#"><i><img src="<?= $baseUrl?>/images/icon_around.png" alt=""></i> <span> Who is around </span> </a></li>
      <li><a href="#"><i><img src="<?= $baseUrl?>/images/icon_travel.png" alt=""></i> <span> Travel Buddy </span> </a></li>
      <li><a href="#"><i><img src="<?= $baseUrl?>/images/icon_meet.png" alt=""></i> <span> Local Hosts </span> </a></li>
      <li><a href="#"><i><img src="<?= $baseUrl?>/images/icon_group.png" alt=""></i> <span> Get In Touch </span> </a></li>
      <li><div class="blankspace"></div></li>
      <li><a href="#"><i><img src="<?= $baseUrl?>/images/icon_travel.png" alt=""></i> <span> Hire a Guide </span> </a></li>
      <li><a href="#"><i><img src="<?= $baseUrl?>/images/icon_forum.png" alt=""></i> <span> Trip Experience </span> </a></li>
      <li><a href="#"><i><img src="<?= $baseUrl?>/images/icon_travstory.png" alt=""></i> <span> Travel Stories </span> </a></li>
      <li><a href="#"><i><img src="<?= $baseUrl?>/images/icon_faq.png" alt=""></i> <span> Travel Helpers </span> </a></li>
      <li><div class="blankspace"></div></li>
      <li><a href="#"><i><img src="<?= $baseUrl?>/images/icon_group.png" alt=""></i> <span> Groups </span> </a></li>
	  
	  <!--
	  <li><a href="#"><i><img src="<?= $baseUrl?>/images/test1_icon_home.png" alt=""></i> <span> Travfeed </span> </a></li>
       <li><a href="#"><i><img src="<?= $baseUrl?>/images/test1_icon_page.png" alt=""></i> <span> Pages </span> </a></li>
      <li><a href="#"><i><img src="<?= $baseUrl?>/images/test1_icon_photography.png" alt=""></i> <span> Photography </span> </a></li>
      <li><a href="#"><i><img src="<?= $baseUrl?>/images/test1_icon_travtube.png" alt=""></i> <span> TravTube </span> </a></li>
      <li><a href="#"><i><img src="<?= $baseUrl?>/images/test1_icon_event.png" alt=""></i> <span> Community Events </span> </a></li>
      <li><div class="blankspace"></div></li>
      <li><a href="#"><i><img src="<?= $baseUrl?>/images/test1_icon_around.png" alt=""></i> <span> Who is around </span> </a></li>
      <li><a href="#"><i><img src="<?= $baseUrl?>/images/test1_icon_travel.png" alt=""></i> <span> Travel Buddy </span> </a></li>
      <li><a href="#"><i><img src="<?= $baseUrl?>/images/test1_icon_meet.png" alt=""></i> <span> Local Hosts </span> </a></li>
      <li><a href="#"><i><img src="<?= $baseUrl?>/images/test1_icon_group.png" alt=""></i> <span> Get In Touch </span> </a></li>
     -->
   	 </ul>

</div>  