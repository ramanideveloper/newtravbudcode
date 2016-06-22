<?php
include('includes/header.php');
use yii\widgets\ActiveForm;
use frontend\models\Friend;
use yii\helpers\Url;
use frontend\models\PhotoForm;
use frontend\models\ProfileVisitor;
use frontend\models\UserForm;
use yii\helpers\ArrayHelper;

$session = Yii::$app->session;
$user_id = (string) $session->get('user_id');

$photopics = PhotoForm::getAllpics();
//echo '<pre>';print_r($photopics);
$photocount = count($photopics);
?>

<div class="inner-body-content fb-page">
    <div class="page-wrapper">
	<div class="container-fluid clearfix inner-fix01 userwall"> 
            <div class="left-container">
                <div class="row fb-pagecontent sm-fix">
                    <div class="col-lg-8 col-md-8 col-sm-8 secondcol">
                        <div class="tb-panel-box  panel-shadow">				
                            <div class="tb-inner-title01 section-title">Explore your Photography !!!</div>
                            <div class="ppl-box pad-box">
                                <div id="image-holder">
                                    <div class="img-row">
                                    </div>
                                </div>
                                <form enctype="multipart/form-data" name="imageForm">
                                    <div class="uploadimages">
                                        <input type="file" id="imagephoto" name="imagephoto[]" required="" multiple="true"/>
                                    </div>
                                    <div class="imagespreview">
                                    </div>
                                </form>
                            </div>
                            <?php if($photocount > 0){ ?>
                            <div class="white-holderbox user_photos panel-shadow">
                                <div class="boxlabel">Photography Pictures</div>
                                <div class="clear"></div>
                                <div class="photos">
                                    <div class="albums gallery">
                                        <?php foreach($photopics as $photopic)
                                            {
                                                $eximgs = explode(',',$photopic['image'],-1);
                                                foreach ($eximgs as $eximg)
                                                {
                                                    $var  = explode("/",$eximg);
                                                    $eximgs = '/'.$var[1].'/'.$photopic['photo_user_id'].'/'.$var[2];
                                                    $imgpath = Yii::$app->getUrlManager()->getBaseUrl().$eximgs;
                                        ?>
                                        <div class="album-col">
                                            <div class="album-holder">
                                                <div class="album-box">
                                                    <a class="listalbum-box" href="<?= $imgpath ?>" rel="prettyPhoto[gallery1]"><img alt="" src="<?= $imgpath ?>"></a>
                                                </div>
                                            </div>
                                        </div>
                                        <?php }
                                            } ?>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                    <?php include('includes/add_friend_email.php');?>
               </div>
            </div>
            <?php include('includes/chat_section.php');?>		
	</div>
	<!-- Left Navigation and Footer -->   
	<?php include('includes/left-menu.php'); ?>
    <?php     
		include('includes/footer.php');
	?>
    </div>
</div>