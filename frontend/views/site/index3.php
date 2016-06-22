<?php
/* @var $this \yii\web\View */
/* @var $content string */
use yii\helpers\Html;
use frontend\assets\AppAsset;
$baseUrl = Yii::getAlias('@web');
$this->beginBody() ?>
    
    <div id="loader-wrapper">
        <div class="loader-logo"></div>
        <div id="loader"></div>
        
        <div class="loader-section section-left"></div>
        <div class="loader-section section-right"></div>

    </div>
    <div class="page-wrapper">
        <div class="header-section">
            <?= \Yii::$app->view->renderFile('@app/views/layouts/header1.php'); ?>
            <?= \Yii::$app->view->renderFile('@app/views/layouts/header2.php'); ?>
            <?= \Yii::$app->view->renderFile('@app/views/layouts/header3.php'); ?>
        </div>
            
        <div class="floating-icon">
        
            <div class="scrollup-btnbox anim-side btnbox scrollup-float">
                <div class="scrollup-button float-icon"><span class="icon-holder ispan"><i class="fa fa-arrow-circle-up"></i></span></div>          
            </div>
            <div class="newpost-btnbox anim-side btnbox newpost-float">
                <div class="newpost-button float-icon"><a class="icon-holder ispan popup-modal" href="#newpost-popup"><i class="fa fa-edit"></i></a></div>
            </div>
            <div class="theme-colorbox anim-side btnbox" id="theme-window" tabindex="-1">
              <div class="colorbox-button float-icon" id="theme-open-button"><span class="icon-holder"><img src="<?= $baseUrl ?>/images/float-theme.png"/></span></div>
              <div class="btnbox-drawer theme-drawer clearfix"> 
                <a href="javascript:void(0);" body-color="theme-dark-blue" class="tm-dark-blue">&nbsp;</a>
                <a href="javascript:void(0);" body-color="theme-purple" class="tm-purple">&nbsp;</a>
                <a href="javascript:void(0);" body-color="theme-light-blue"  class="tm-light-blue">&nbsp;</a>
                <a href="javascript:void(0);" body-color="theme-green"  class="tm-green">&nbsp;</a>
                <a href="javascript:void(0);" body-color="theme-light-red"  class="tm-light-red">&nbsp;</a>
                <a href="javascript:void(0);" body-color="theme-light-purple"  class="tm-light-purple">&nbsp;</a>
                <a href="javascript:void(0);" body-color="theme-dark-cyan"  class="tm-dark-cyan">&nbsp;</a>
                <a href="javascript:void(0);" body-color="theme-bright-blue"  class="tm-bright-blue">&nbsp;</a>
                <a href="javascript:void(0);" body-color="theme-emerald"  class="tm-emerald">&nbsp;</a> </div>
            </div>
        </div>
        <div class="clear"></div>
        <?= \Yii::$app->view->renderFile('@app/views/layouts/leftmenu.php'); ?>
        <div class="main-content with-lmenu">
            <div class="post-column">
                  <?= \Yii::$app->view->renderFile('@app/views/layouts/postwall.php'); ?>
            </div>
            
            <div class="scontent-column">
                <?= \Yii::$app->view->renderFile('@app/views/layouts/people_you_may_know.php'); ?>
                
                <?= \Yii::$app->view->renderFile('@app/views/layouts/people_view_you.php'); ?>
                
                <?= \Yii::$app->view->renderFile('@app/views/layouts/recently_joined.php'); ?>
            </div>
            <?= \Yii::$app->view->renderFile('@app/views/layouts/chat_view.php'); ?>
            
        </div>
        
          <?= \Yii::$app->view->renderFile('@app/views/layouts/footer.php'); ?>
    </div>  
    
    <div id="newpost-popup" class="mfp-hide white-popup-block popup-area">
        <div class="popup-title">
            <h3>Add New Post</h3>
            <a class="popup-modal-dismiss close-popup" href="javascript:void(0)"><span class="glyphicon glyphicon-remove"></span></a>
        </div>
        <div class="popup-content">
            <div class="new-post">
                <?= \Yii::$app->view->renderFile('@app/views/layouts/postblock.php'); ?>
            </div>
            
        </div>
    </div>
    <?= \Yii::$app->view->renderFile('@app/views/layouts/postedit.php'); ?>

    <?= \Yii::$app->view->renderFile('@app/views/layouts/postshare.php'); ?>
    
    <?= \Yii::$app->view->renderFile('@app/views/layouts/postcustompopup.php'); ?>
<?php $this->endBody() ?>