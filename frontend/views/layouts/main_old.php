<?php

/* @var $this \yii\web\View */
/* @var $asset string */

use yii\helpers\Html;
use frontend\assets\AppAsset;

$asset = frontend\assets\AppAsset::register($this);
$baseUrl = AppAsset::register($this)->baseUrl;
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Travel Web</title>
    <link rel="shortcut icon" href="<?php echo Yii::$app->request->baseUrl; ?>/favicon.ico" type="image/x-icon" />
      
          <link href="<?= $baseUrl?>/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
          <link href="<?= $baseUrl?>/css/bootstrap-theme.min.css" rel="stylesheet" type="text/css" />
          <link href="<?= $baseUrl?>/css/template.css" rel="stylesheet" type="text/css" />  
		  <link href="<?= $baseUrl?>/css/flip.css" rel="stylesheet" type="text/css"/>
		  
          <link href="<?= $baseUrl?>/css/custome-responsive.css" rel="stylesheet" type="text/css" />  
          <link href="<?= $baseUrl?>/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css" />
          <link href="<?= $baseUrl?>/css/component.css" rel="stylesheet" type="text/css"/>
		  
          <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
          <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
            <!--[if lt IE 9]>
             <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
             <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
            <![endif]-->
          
          <script src="<?= $baseUrl?>/js/jquery-1.11.0.min.js" type="text/javascript"></script>
          <script src="<?= $baseUrl?>/js/bootstrap.min.js" type="text/javascript"></script>
          <script src="<?= $baseUrl?>/js/moment-with-locales.js" type="text/javascript"></script>
          <script src="<?= $baseUrl?>/js/bootstrap-datetimepicker.js" type="text/javascript"></script>
          <script src="<?= $baseUrl?>/js/modernizr.js" type="text/javascript"></script>
          <script src="<?= $baseUrl?>/js/cookie.js" type="text/javascript"></script>
         
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="theme-dark-blue">
<!-- loader ---------------------------------------------------------------------------------------------------------------------->
<!--
<div id="loader-wrapper">
	<div class="loader-logo"></div>
	<div id="loader"></div>
	
	<div class="loader-section section-left"></div>
	<div class="loader-section section-right"></div>

</div>
-->

<?php $this->beginBody() ?>
    
    <!-- theme button -->

<div class="theme-colorbox ani-1" id="theme-window" tabindex="-1">
  <div class="colorbox-button" id="theme-open-button"><span class="ani-1"><img src="<?= $baseUrl?>/images/theme-setting-icon.png" width="32" height="32"></span> </div>
  <div class="colorbox-coloricon clearfix"> <a href="javascript:void(0);" body-color="theme-dark-blue loaded" class="tm-dark-blue">&nbsp;</a> <a href="javascript:void(0);" body-color="theme-purple loaded" class="tm-purple">&nbsp;</a> <a href="javascript:void(0);" body-color="theme-light-blue loaded"  class="tm-light-blue">&nbsp;</a> <a href="javascript:void(0);" body-color="theme-brown loaded"  class="tm-brown">&nbsp;</a> <a href="javascript:void(0);" body-color="theme-green loaded"  class="tm-green">&nbsp;</a> <a href="javascript:void(0);" body-color="theme-light-red loaded"  class="tm-light-red">&nbsp;</a> </div>
</div>
<!-- content section -->
<!--<div class="container"> -->
       <?= $content ?>
<!--</div>-->
<!-- end of content section -->

<!-- footer section -->
<div class="np-fotter clearfix">
  <div class="container np-fotter-link"><a href="#">About</a> <span>|</span> <a href="#">Privacy</a> <span>|</span> <a href="#">Invite</a> <span>|</span> <a href="#">Terms</a> <span>|</span> <a href="#">Contact Us</a> <span>|</span> <a href="#">Features</a> <span>|</span> <a href="#">Mobile</a> <span>|</span> <a href="#">Developers</a></div>
</div>
<!-- end of footer section -->
 
<?php $this->endBody() ?>
</body>
<!-- SCRIPT FOR HOME PAGE -->
<!-- <script src='http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.5/jquery-ui.min.js' /> -->
<script src='<?= $baseUrl?>/js/jquery-ui.min.js' /></script>
  
<!-- SCRIPT FOR INNER PAGE 
<script src="<?= $baseUrl?>/js/jquery-1.11.0.min.js" type="text/javascript"></script>
<script src="<?= $baseUrl?>/js/bootstrap.min.js" type="text/javascript"></script>--> 
<!-- Animated Search --> 

<script src="<?= $baseUrl?>/js/modernizr.custom.js"></script> 
<script src="<?= $baseUrl?>/js/classie.js"></script> 
<script src="<?= $baseUrl?>/js/uisearch.js"></script> 
<script>
function menuHendler(){
		console.log('onclick called');
		if($('#menuHandler').hasClass('close-but')){
			$('#menuHandler').removeClass('close-but');	
			$('#leftNavigation').removeClass('open-nav');	
		}else{
			$('#menuHandler').addClass('close-but');	
			$('#leftNavigation').addClass('open-nav');
		}
}
new UISearch( document.getElementById( 'sb-search' ) );
</script>

<script type="text/javascript">

		$("#theme-open-button").click(function(){
			$("#theme-window").toggleClass("box-open");
		}); 
		$(document).click(function(event) { 
			if(!$(event.target).closest('#theme-window').length) {
				if($('#theme-window').is(":visible")) {
					$('#theme-window').removeClass('box-open');
				}
			}        
		})
	// togle body theme
	 $( ".colorbox-coloricon a" ).bind( "click", function() {
		var clickedClass = $(this).attr('body-color'); // or var clickedBtnID = this.id
	 	$("body").attr("class",clickedClass);
                setCookie('bodyClass',clickedClass);
	});
        checkCookie();
        </script>
        <script>

</script>
</html>
<?php $this->endPage() ?>
