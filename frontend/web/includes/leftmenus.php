<div class="fbmenu-holder">
    <?php /*
	<ul class="side-fbmenu">
            <li <?php if($_GET['r'] == 'site/basicinfo'){?>class='active'<?php }?>>
                <a href="<?php echo Yii::$app->urlManager->createUrl(['site/basicinfo']); ?>" class="menu-basicinfo">Basic Information</a>
            </li>
            <li <?php if($_GET['r'] == 'site/profile-picture'){?>class='active'<?php }?>>
                <a href="<?php echo Yii::$app->urlManager->createUrl(['site/profile-picture']); ?>" class="menu-profilepic">Profile Photo</a>
            </li>
            <li <?php if($_GET['r'] == 'site/security-setting'){?>class='active'<?php }?>>
                <a href="<?php echo Yii::$app->urlManager->createUrl(['site/security-setting']); ?>" class="menu-security">Security</a>
            </li>
            <li <?php if($_GET['r'] == 'site/notification-setting'){?>class='active'<?php }?>>
                <a href="<?php echo Yii::$app->urlManager->createUrl(['site/notification-setting']); ?>" class="menu-notification">Notifications</a>
            </li>
            <li <?php if($_GET['r'] == 'site/blocking'){?>class='active'<?php }?>>
               <a href="<?php echo Yii::$app->urlManager->createUrl(['site/blocking']); ?>" class="menu-block">Blocking</a>
            </li>

    </ul>
    <div class="menu-divider"></div>
    <ul class="side-fbmenu">
            <li><a href="#" class="menu-travel">Travel</a></li>
            <li><a href="#" class="menu-social">Social Life</a></li>
            <li><a href="#" class="menu-activities">Activities</a></li>
            <li><a href="#" class="menu-interest">Interests</a></li>
    </ul>
    <div class="menu-divider"></div>
    <ul class="side-fbmenu">
            <li><a href="#" class="menu-followers">Followers</a></li>
            <li><a href="#" class="menu-contact">Contact Information</a></li>
            <li><a href="#" class="menu-billing">Billing Information</a></li>
            <li><a href="#" class="menu-close">Close Account</a></li>
    </ul>
	 */ ?>
	
	<div id="sidebar-menu">
								
		<div class="settingpic-holder">
			<div class="setting-pic">
			<?php
				$dp = $this->context->getimage($user_id,'photo');
			?>
				<img src="<?= $dp?>"/>
			</div>
		</div>	
		 
		<ul>
			<li class="has_sub <?php if($_GET['r'] == 'site/basicinfo' || $_GET['r'] == 'site/profile-picture'){ ?>active opened<?php }?>">
				<a class="mainlink" href="javascript:void(0)"><span class="mbl-arrow"><i class="fa fa-caret-right"></i></span>Account</a>				
				<ul class="submenu side-fbmenu">
					<li <?php if($_GET['r'] == 'site/basicinfo'){?>class='active'<?php }?>>
						<a href="<?php echo Yii::$app->urlManager->createUrl(['site/basicinfo']); ?>" class="menu-basicinfo">Basic Information</a>
					</li>
					<li <?php if($_GET['r'] == 'site/profile-picture'){?>class='active'<?php }?>>
						<a href="<?php echo Yii::$app->urlManager->createUrl(['site/profile-picture']); ?>" class="menu-profilepic">Profile Photo</a>
					</li>
					<li <?php if($_GET['r'] == 'site/slider'){?>class='active'<?php }?>><a href="<?php echo Yii::$app->urlManager->createUrl(['site/slider']); ?>" class="menu-contact">Slider</a></li>
					<li <?php if($_GET['r'] == 'site/cover'){?>class='active'<?php }?>><a href="<?php echo Yii::$app->urlManager->createUrl(['site/cover']); ?>" class="menu-contact">Cover</a></li>
					<li><a href="#" class="menu-contact">Communication</a></li>
					<li><a href="#" class="menu-groups">Groups</a></li>
				</ul>
			</li>
			
			<li class="has_sub <?php if($_GET['r'] == 'site/security-setting' || $_GET['r'] == 'site/notification-setting' || $_GET['r'] == 'site/blocking'){?>active opened<?php }?>">
				<a class="mainlink" href="javascript:void(0)"><span class="mbl-arrow"><i class="fa fa-caret-right"></i></span>Profile Privacy</a>
				
				<ul class="submenu side-fbmenu">
					<li <?php if($_GET['r'] == 'site/security-setting'){?>class='active'<?php }?>>
						<a href="<?php echo Yii::$app->urlManager->createUrl(['site/security-setting']); ?>" class="menu-security">Security Setting</a>
					</li>				
					<li <?php if($_GET['r'] == 'site/notification-setting'){?>class='active'<?php }?>>
						<a href="<?php echo Yii::$app->urlManager->createUrl(['site/notification-setting']); ?>" class="menu-notification">Notifications</a>
					</li>
					<li <?php if($_GET['r'] == 'site/blocking'){?>class='active'<?php }?>>
					   <a href="<?php echo Yii::$app->urlManager->createUrl(['site/blocking']); ?>" class="menu-block">Blocking</a>
					</li>
				</ul>
			</li>
			
			<li class="has_sub">
				<a class="mainlink" href="javascript:void(0)"><span class="mbl-arrow"><i class="fa fa-caret-right"></i></span>Travel</a>
				<ul class="submenu side-fbmenu">
					<li><a href="#" class="menu-activities">Hangout</a></li>
					<li><a href="#" class="menu-hireguide">Hire A Guide</a></li>
					<li><a href="#" class="menu-social">Local Host</a></li>
					<li><a href="#" class="menu-travel">Travel Plan</a></li>					
					<!--<li><a href="#" class="menu-interest">Interests</a></li>-->
				</ul>
			</li>

			<li class="has_sub">
				<a class="mainlink" href="javascript:void(0)"><span class="mbl-arrow"><i class="fa fa-caret-right"></i></span>Basic</a>				
				<ul class="submenu side-fbmenu">
					<!--
					<li><a href="#" class="menu-followers">Followers</a></li>				
					<li><a href="#" class="menu-billing">Billing Information</a></li>
					-->
					<li><a href="#" class="menu-close">Close Account</a></li>
				</ul>
			</li>
		</ul>
		<div class="clearfix"></div>
	</div>
</div>
