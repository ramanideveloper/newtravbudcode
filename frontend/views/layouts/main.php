<?php

/* @var $this \yii\web\View */
/* @var $asset string */

use yii\helpers\Html;
use frontend\assets\AppAsset; 
use yii\web\View;

$asset = frontend\assets\AppAsset::register($this);
$baseUrl = AppAsset::register($this)->baseUrl;
$session = Yii::$app->session;
$email = $session->get('email'); 
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
			<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon" />
	
<?php $cont = isset(Yii::$app->controller->id) ? Yii::$app->controller->id : '';
	$tit = isset($this->context->action->id) ? $this->context->action->id : '';
	if(isset($cont) && isset($tit)) {
	if($cont == 'site' && $tit != 'index3') {
?>
	
      
          <link href="<?= $baseUrl?>/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
          <link href="<?= $baseUrl?>/css/bootstrap-theme.min.css" rel="stylesheet" type="text/css" />
          <link href="<?= $baseUrl?>/css/template.css" rel="stylesheet" type="text/css" />  
		  <link href="<?= $baseUrl?>/css/flip.css" rel="stylesheet" type="text/css"/>
		  
          <link href="<?= $baseUrl?>/css/custome-responsive.css" rel="stylesheet" type="text/css" />  
          <link href="<?= $baseUrl?>/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css" />
          <link href="<?= $baseUrl?>/css/component.css" rel="stylesheet" type="text/css"/>
          <link href="<?= $baseUrl?>/css/font-awesome.css" rel="stylesheet" type="text/css"/>
          <link rel="stylesheet" type="text/css" href="<?= $baseUrl?>/css/stylesheet.css" />
        <link rel="stylesheet" type="text/css" href="<?= $baseUrl?>/css/linkPreview.css" />
		 <link rel="stylesheet" type="text/css" href="<?= $baseUrl?>/css/switch.css" />
		 <link rel="stylesheet" type="text/css" href="<?= $baseUrl?>/css/magnific-popup.css" />		 
		 <link rel="stylesheet" type="text/css" href="<?= $baseUrl?>/css/magnific-all.min.css" />		 
		 <!--<link rel="stylesheet" type="text/css" href="<?= $baseUrl?>/css/jquery.datepick.css" />-->
         <link rel="stylesheet" type="text/css" href="<?= $baseUrl?>/css/datepicker.css" />		 
		 <link rel="stylesheet" type="text/css" href="<?= $baseUrl?>/css/nprogress.css" />		 
		<!--<link href="<?= $baseUrl ?>/sliders/css/flexslider.css" rel="stylesheet" type="text/css" />-->

		<link href="<?= $baseUrl ?>/css/jquery.webui-popover.min.css" rel="stylesheet" type="text/css" />

		<link href="<?= $baseUrl ?>/css/bootstrap-dialog.min.css" rel="stylesheet" type="text/css" />		
		<link href="<?= $baseUrl ?>/css/prettyPhoto.css" rel="stylesheet" type="text/css" />
		
		<!-- contenxt menu css -->		
		<link href="<?= $baseUrl ?>/css/jquery.contextMenu.css" rel="stylesheet" type="text/css" />
		<!-- end contenxt menu css -->
		
		<link rel="stylesheet" type="text/css" href="<?= $baseUrl?>/css/full-slider.css" />
		<!--		  
		<link rel="stylesheet" type="text/css" href="<?= $baseUrl?>/css/demo-cover.css" />		 
		<link rel="stylesheet" type="text/css" href="<?= $baseUrl?>/css/flexslider-cover.css" />	-->	 
		
          <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
          <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
            <!--[if lt IE 9]>
             <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
             <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
            <![endif]-->
       
		<!--<script src="<?= $baseUrl ?>/sliders/js/jquery.flexslider-min.js" type="text/javascript"></script>-->
		<!-- JavaScript at the bottom for fast page loading -->
		<!--<script type="text/javascript" src="<?= $baseUrl ?>/sliders/js/plugins.js"></script> -->
		
        <!-- SCRIPT FOR HOME PAGE -->
        <!--<script src='http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.5/jquery-ui.min.js' /> 
        <script src='<?= $baseUrl?>/js/jquery-ui.min.js' /></script>-->
        <!--<script src='<?= $baseUrl?>/js/jquery-ui.min.1.8.5.js' /></script>		-->
		<script src="<?= $baseUrl?>/js/jquery-1.12.2.min.js" type="text/javascript"></script>
		<script src="<?= $baseUrl?>/js/jquery-ui.js"></script>
        <script src="<?= $baseUrl?>/js/modernizr.custom.js"></script> 
        <script src="<?= $baseUrl?>/js/classie.js"></script> 
        <!--<script src="<?= $baseUrl?>/js/uisearch.js"></script> -->
        <script src="<?= $baseUrl?>/js/webcam.js" type="text/javascript"></script>
        <script src="<?= $baseUrl?>/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="<?= $baseUrl?>/js/moment-with-locales.js" type="text/javascript"></script>
        <script src="<?= $baseUrl?>/js/bootstrap-datetimepicker.js" type="text/javascript"></script>
        <script src="<?= $baseUrl?>/js/modernizr.js" type="text/javascript"></script>
        <script src="<?= $baseUrl?>/js/cookie.js" type="text/javascript"></script>
       
        <link rel="stylesheet" type="text/css" href="<?= $baseUrl?>/css/select2.min.css" />
        <link rel="stylesheet" type="text/css" href="<?= $baseUrl?>/css/custom-plugin.css" />
		
		<script src="<?= $baseUrl?>/js/select2.full.min.js" type="text/javascript"></script>
		
		
		<script src="<?= $baseUrl?>/js/html5shiv.min.js" type="text/javascript"></script>
      
		<script src="<?= $baseUrl?>/js/jquery.magnific-popup.js" type="text/javascript"></script>
		
		<script src="<?= $baseUrl?>/js/jquery.plugin.js" type="text/javascript"></script>
		<script src="<?= $baseUrl?>/js/jquery.datepick.js" type="text/javascript"></script>

		<script src="<?= $baseUrl?>/js/prettify.min.js" type="text/javascript"></script>
		<script src="<?= $baseUrl?>/js/bootstrap-dialog.min.js" type="text/javascript"></script>
		
		<script src="<?= $baseUrl?>/js/jquery.slimscroll.min.js" type="text/javascript"></script>
		<script src="<?= $baseUrl?>/js/nprogress.js" type="text/javascript"></script>
		
		<script src="<?= $baseUrl?>/js/jquery.prettyPhoto.js" type="text/javascript"></script>
		
		<!--<script src="<?= $baseUrl?>/js/waterfall-light.js" type="text/javascript"></script>-->
		
		<!-- contenxt menu js -->
		<script src="<?= $baseUrl ?>/js/jquery.contextMenu.js" type="text/javascript"></script>
		<script src="<?= $baseUrl ?>/js/jquery.ui.position.min.js" type="text/javascript"></script>
		<!-- end contenxt menu js -->

		<!--<script src="<?= $baseUrl?>/js/jquery.flexslider.js" type="text/javascript"></script>-->
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="theme-dark-blue loaded">

<?php

$cont = isset(Yii::$app->controller->id) ? Yii::$app->controller->id : '';
$tit = isset($this->context->action->id) ? $this->context->action->id : '';
if(isset($cont) && isset($tit)) {
	if($cont == 'site' && ($tit == 'index' || $tit == 'index2')) { ?>
	<div id="loader-wrapper">
		<div class="loader-logo"></div>
		<div id="loader"></div>
		
		<div class="loader-section section-left"></div>
		<div class="loader-section section-right"></div>

	</div>
<?php } } ?>
		
	<?php } else {
		?>
		<link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="js/ie-emulation-modes-warning.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Custom styles for this template -->
    <link href="css/template.css" rel="stylesheet">
    <link href="css/custom-responsive.css" rel="stylesheet">
    <link href="css/font-awesome.css" rel="stylesheet">
    <link href="css/prettyPhoto.css" rel="stylesheet">
    <link href="css/magnific-popup.css" rel="stylesheet">
    <link href="css/bootstrap-dialog.min.css" rel="stylesheet">
    <link href="css/bootstrap-dialog.min.css" rel="stylesheet">
	
	<!-- tooltip library: http://iamceege.github.io/tooltipster/ -->
    <link href="css/tooltipster.bundle.min.css" rel="stylesheet">
    <link href="css/plugins/tooltipster/sideTip/themes/tooltipster-sideTip-borderless.min.css" rel="stylesheet">
	
    <link href="css/pace-loader.css" rel="stylesheet">
    <link href="css/select2.min.css" rel="stylesheet">
    <link href="css/custom-plugin.css" rel="stylesheet">
	<?php $this->head() ?>
</head>
<body class="theme-dark-blue loaded">
	
		<?php
	} } ?>
		



<!-- content section -->
<!--<div class="container"> -->

       <?= $content ?>

<!--</div>-->
<!-- end of content section -->
 <?php $cont = isset(Yii::$app->controller->id) ? Yii::$app->controller->id : '';
	$tit = isset($this->context->action->id) ? $this->context->action->id : '';
	if(isset($cont) && isset($tit)) {
	if($cont == 'site' && ($tit != 'index3')) { ?>
<?php $this->endBody() ?>


</body>

<script>

	function menuHendler(){	
			if($('#menuHandler').hasClass('close-but')){
				$('#menuHandler').removeClass('close-but');	
				$('#leftNavigation').removeClass('open-nav');	
			}else{
				$('#menuHandler').addClass('close-but');	
				$('#leftNavigation').addClass('open-nav');
			}
	}
	//new UISearch( document.getElementById( 'sb-search' ) );


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
		$('body').removeClass("loaded");
		var clickedClass = $(this).attr('body-color'); // or var clickedBtnID = this.id
	 	$("body").attr("class",clickedClass);
        setCookie('bodyClass',clickedClass);
		$('body').addClass("loaded");
	});
        checkCookie();
		/*
		$(document).ready(function(){
			alert("hello");
			var win_h=$(window).height();
			$('body').slimScroll({
				height: win_h
			});
		});
		*/
</script>

<script src="<?= $baseUrl?>/js/jquery.webui-popover.min.js" type="text/javascript"></script>
<script type="text/javascript">

	

	/* Likes popover script */
//	var settings = {
//			trigger:'hover',
//			title:'',
//			content:'<?php //if(isset($like_peoples) )echo $like_peoples;?>',			
//			multi:true,						
//			closeable:false,
//			style:'',
//			delay:300,
//			padding:true,
//			backdrop:false
//	};
//	
//	function initPopover(){					
//		
//		$('a.show-pop').webuiPopover('destroy').webuiPopover(settings);				
//		
//	}
//	$(document).ready(function(){
//		initPopover();				
//	});
	/*
	var settings = {
			trigger:'hover',
			title:'WebUI Popover ',
			content:'<p>This is webui popover demo.</p><p>just enjoy it and have fun !</p>',			
			multi:true,						
			closeable:false,
			style:'',
			delay:300,
			padding:true,
			backdrop:false
	};

	function initPopover(){					
		
		$('a.show-pop').webuiPopover('destroy').webuiPopover(settings);				
		
	}
	$(document).ready(function(){
		initPopover();				
	});
	*/
	/* End Likes popover script */
      
</script>
<!-- START Google Map Function And js -->
		<script>
      // This example displays an address form, using the autocomplete feature
      // of the Google Places API to help users fill in the information.

      // This example requires the Places library. Include the libraries=places
      // parameter when you first load the API. For example:
      // <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">

      $(document.body).on("click", ".getplacelocation", function(e){
          id = $(this).attr('id');
          initAutocomplete(id);
      });
 
      function initAutocomplete(id) {
        // Create the autocomplete object, restricting the search to geographical
        // location types.
        autocomplete = new google.maps.places.Autocomplete(
            /** @type {!HTMLInputElement} */(document.getElementById(id)),
            {types: ['geocode']});



        placeSearch = new google.maps.places.Autocomplete(
            /** @type {!HTMLInputElement} */(document.getElementById('placeSearch')),
            {types: ['geocode']});
        // When the user selects an address from the dropdown, populate the address
        // fields in the form.
        autocomplete.addListener('place_changed', fillInAddress);
        placeSearch.addListener('place_changed', fillInAddress);
      }

      function fillInAddress() {
        // Get the place details from the autocomplete object.
        var place = autocomplete.getPlace();

      
        // Get each component of the address from the place details
        // and fill the corresponding field on the form.
        for (var i = 0; i < place.address_components.length; i++) {
          var addressType = place.address_components[i].types[0];
	  if (addressType == "country") {
            var val = place.address_components[i]["long_name"];
            document.getElementById("country").value = val;
            
            $.ajax({
                url: "<?php echo Yii::$app->urlManager->createUrl(['site/isd-code']); ?>",
                data:'country='+$("#country").val(),
                type: "POST",
                success:function(data){
                   document.getElementById("isd_code").value = data;
                },
                error:function (){}
                });
            
          }
        }
      }

      // Bias the autocomplete object to the user's geographical location,
      // as supplied by the browser's 'navigator.geolocation' object.
      function geolocate() {
        if (navigator.geolocation) {
          navigator.geolocation.getCurrentPosition(function(position) {
            var geolocation = {
              lat: position.coords.latitude,
              lng: position.coords.longitude
            };
            var circle = new google.maps.Circle({
              center: geolocation,
              radius: position.coords.accuracy
            });
            autocomplete.setBounds(circle.getBounds());
          });
        }
      }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDXUMeeP9NCsULSFZubP3YdTKoThAFivPM&libraries=places&callback=initAutocomplete"
        async defer></script>

        <!-- END Google Map Function And js -->

</html>
	<?php $this->endPage(); }

	if(isset($cont) && isset($tit)) {
	if($cont == 'site' && $tit == 'index3') {
		?>
	<!-- Placed at the end of the document so the pages load faster -->
    <script src="js/jquery.min.js"></script>    
    <script src="js/bootstrap.min.js"></script>
    <!-- Just to make our placeholder images work. Don't actually copy the next line! -->
    <script src="js/vendor/holder.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="js/ie10-viewport-bug-workaround.js"></script>
    
	<script src="js/cookie.js"></script>
	<script src="js/jquery.prettyPhoto.js" type="text/javascript" charset="utf-8"></script>
	<script src="js/jquery.magnific-popup.js" type="text/javascript" charset="utf-8"></script>
	<script src="js/bootstrap-dialog.min.js" type="text/javascript" charset="utf-8"></script>
	<!-- tooltip library: http://iamceege.github.io/tooltipster/ -->
	<script src="js/tooltipster.bundle.min.js" type="text/javascript" charset="utf-8"></script>
	<script src="js/pace.min.js" type="text/javascript" charset="utf-8"></script>
	<script src="js/select2.full.min.js" type="text/javascript" charset="utf-8"></script>
	
	<script src="js/custom-functions.js"></script>
	<script src="js/custom-js.js"></script>

	<?php } } } ?>
