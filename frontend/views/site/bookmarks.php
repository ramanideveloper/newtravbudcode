<?php

/* @var $this \yii\web\View */
/* @var $content string */
include('includes/header.php');
use yii\helpers\Html;
use frontend\assets\AppAsset;

use yii\widgets\ActiveForm;
use yii\mongodb\ActiveRecord;

use frontend\models\LoginForm;
use frontend\models\BookmarkForm;
use yii\web\Session;
$session = Yii::$app->session;


if($session->get('email'))
{
    $email = $session->get('email'); 
    $result = LoginForm::find()->where(['email' => $email])->one();
    $user = LoginForm::find()->where(['email' => $email])->one();
    
    $userid = (string) $result['_id'];
    $username = $result['username'];
    $lname = $result['lname'];
    $password = $result['password'];
    $con_password = $result['con_password'];
    $birth_date = $result['birth_date'];
    $gender = $result['gender'];
    $photo = $result['photo'];
    
    $bookmarks = BookmarkForm::find()->where(['user_id' => $userid,'is_deleted' => '0'])->orderBy(['modified_date'=>SORT_DESC])->all();
}
else{
     $url = Yii::$app->urlManager->createUrl(['site/index']);
            Yii::$app->getResponse()->redirect($url);   
}

//use vendor\bower\travel\assets\AppAsset;
//AppAsset::register($this);
//AppAsset::register($this);
$asset = frontend\assets\AppAsset::register($this);

$baseUrl = AppAsset::register($this)->baseUrl;
?>

<!-- body section -->
<section class="inner-body-content fb-page">
	<div class="fb-innerpage whitebg">
	 
		<?php include('includes/leftmenus.php');?>
		<div class="fbdetail-holder">
			    
			<div class="fb-formholder">		
				
				<h4>Add Bookmark</h4>
				
				<div class="notice">	
                                    <div id="suceess" class="form-successmsg">Bookmark List Updated Successfully.</div>
                                    <div id="fail" class="form-failuremsg">Oops..!! Something went wrong. Please try later.</div>
				</div>
				
				<ul class="setting-ul bookmarks addbm-ul">
					<li>
						<?php $form = ActiveForm::begin(['id' => 'frm-name','options'=>['onsubmit'=>'return false;',],]); ?>
							
						<div class="setting-group">							
							<div class="normal-mode">
								<div class="row">
									<div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">
										<label>Bookmark Title</label>
									</div>
									<div class="col-lg-5 col-md-7 col-sm-7 col-xs-12">										
										<div class="info">																   				   																			
											<?= $form->field($model,'blabel')->textInput(array('id'=>'blabel','placeholder'=>'Title should not be more than 20 chars'))->label(false)?>
										</div>
									</div>									
									<div class="clear"></div>
									<div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">
										<label>Bookmark Link</label>
									</div>
									<div class="col-lg-5 col-md-7 col-sm-7 col-xs-12">										
										<div class="info">																   				   								
											<?= $form->field($model,'blink')->textInput(array('id'=>'blink'))->label(false)?>
										</div>
									</div>
									<div class="col-lg-1 col-md-2 col-sm-2 col-xs-12 pull-right">										
										<div class="pull-right linkholder">
											<input type="button" class="btn btn-primary btn-sm" value="Add Bookmark" onClick="bookmark()">	
										</div>
									</div>
								</div>	
							</div>	
							
						</div>
						
						<?php ActiveForm::end() ?>
					</li>
				</ul>
				<div class="bmcontent" id="bmcontent">
					<?php foreach($bookmarks as $bookmark){
						$blabel = $bookmark['blabel'];
						$blink = $bookmark['blink'];
						$bids = $bookmark['_id'];
					?>
			
				<ul class="setting-ul bookmarks">
									
					<li>
                        <?php $form = ActiveForm::begin(['id' => 'frmm-name','options'=>['onsubmit'=>'return false;',],]); ?> 
						<div class="setting-group">							
							<div class="normal-mode">
								<div class="row">
									<div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">
										<label>Bookmark Title</label>
									</div>
									<div class="col-lg-8 col-md-7 col-sm-7 col-xs-12">										
										<div class="info">																   				   								
											<label><?= $blabel ?></label>
										</div>
									</div>
									<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">										
										<div class="pull-right linkholder">
											<a href="javascript:void(0)" onClick="open_edit(this)">Edit</a> | 
                                                                                        <a href="javascript:void(0)" onClick="deletebookmark('<?= $bids?>')">Delete</a>
										</div>
									</div>
									<div class="clear"></div>
									<div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">
										<label>Bookmark Link</label>
									</div>
									<div class="col-lg-8 col-md-7 col-sm-7 col-xs-12">										
										<div class="info">																   				   								
											<label>												

												<a href="http://<?= $blink ?>" target="_blank"><?= $blink ?></a>
                                            
											</label>
										</div>
									</div>
								</div>	
							</div>	
							<div class="edit-mode">
								<div class="row">
									<div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">
										<label>Bookmark Title</label>
									</div>									
									<div class="col-lg-4 col-md-6 col-sm-9 col-xs-12">
										<div class="form-group">						
										   <?= $form->field($model,'blabell')->textInput(array('value'=>$blabel,'id'=>'blabell'))->label(false)?>
										</div>																				
									</div>
									<div class="clear"></div>
									<div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">
										<label>Bookmark Link</label>
									</div>
									<div class="col-lg-4 col-md-6 col-sm-9 col-xs-12">
										<div class="form-group">						
										   <?= $form->field($model,'blinkk')->textInput(array('value'=>$blink,'id'=>'blinkk'))->label(false)?>				
										</div>																				
									</div>
									<div class="col-lg-4 col-md-12 col-sm-12 col-xs-12 pull-right">
										<div class="form-group">						
											<div class="pull-right fb-btnholder">																		
												<input type="submit" class="btn btn-primary btn-sm" value="Save" onClick="close_edit(this),editbookmark('<?= $bids?>')">
												<input type="button" class="btn btn-primary btn-sm" value="Cancel" onClick="close_edit(this)">							
											</div>										
										</div>										
									</div>
								</div>	
							</div>	
						</div>	
                        <?php ActiveForm::end() ?>
					</li>
                                       
				</ul>
													
				  <?php }?>
				  </div>
			</div>
		</div>
	</div>
    <?php include('includes/footer.php');?>

<script>
function editbookmark(editid)
{
    var blabel = $("#blabell").val();
    var blink = $("#blinkk").val();
    if(editid != ''){
        $.ajax({
            type: 'POST',
            url: '<?php echo Yii::$app->urlManager->createUrl(['site/bookmarks']); ?>',
            data: "blabel=" + blabel +"&blink=" + blink +"&editid=" + editid,
            success: function(data){
                 //alert(data);return false;
                 if(data == '1')
                 {
                      $('#suceess').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
                      $("#bmcontent").load(location.href + " #bmcontent");
                      $("#opt-box").load(location.href + " #opt-box");
                 }
                 else if(data == '0')
                 {
                    $('#fail').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
                 }
                 else
                 {
                     $('#fail').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
                 }
             }

        }); 
    }
    else{
         $('#fail').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
    }
}
function deletebookmark(delid)
{
    if(delid != ''){
        $.ajax({
            type: 'POST',
            url: '<?php echo Yii::$app->urlManager->createUrl(['site/bookmarks']); ?>',
            data: "delid=" + delid,
            success: function(data){
                 //alert(data);return false;
                 if(data == '1')
                 {
                      $('#suceess').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
                      $("#bmcontent").load(location.href + " #bmcontent");
                      $("#opt-box").load(location.href + " #opt-box");
                 }
                 else if(data == '0')
                 {
                    $('#fail').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
                 }
                 else
                 {
                     $('#fail').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
                 }
             }

        }); 
    }
    else{
         $('#fail').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
    }
}
function bookmark(){
    var blabel = $("#blabel").val();
    var blink = $("#blink").val();
    if(blabel != '' && blink != ''){
        $.ajax({
            type: 'POST',
            url: '<?php echo Yii::$app->urlManager->createUrl(['site/bookmarks']); ?>',
            data: "blabel=" + blabel +"&blink=" + blink +"&addid=add",
            success: function(data){
                 //alert(data);return false;
                 if(data == '1')
                 {
                      $('#suceess').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
                      $("#bmcontent").load(location.href + " #bmcontent");
                      $("#opt-box").load(location.href + " #opt-box");
                      $('#blabel').val('');
                      $('#blink').val('');
                 }
                 else if(data == '0')
                 {
                    $('#fail').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
                 }
                 else
                 {
                     $('#fail').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
                 }
             }

        }); 
    }
    else{
         $('#fail').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
    }
}
function close_all_edit(){
    var count=0;
    $(".fb-formholder .setting-ul .setting-group").each(function(){

        var editmode=$(this).children(".edit-mode").css("display");		

        if(editmode!="none"){
                $(this).children(".normal-mode").slideDown(300);
                $(this).children(".edit-mode").slideUp(300);
             }
    });
}
function open_edit(test){
    close_all_edit();

    var obj=$(test).parent().parent().parent().parent().parent();

    var editmode=obj.children(".edit-mode").css("display");

    if(editmode=="none"){
            obj.children(".normal-mode").slideUp(300);
            obj.children(".edit-mode").slideDown(300);
    }
    else{
            obj.children(".normal-mode").slideDown(300);
            obj.children(".edit-mode").slideUp(300);
    }
}
function close_edit(test){
    var obj=$(test).parent().parent().parent().parent().parent().parent();

    var editmode=obj.children(".edit-mode").css("display");

    if(editmode=="none"){
            obj.children(".normal-mode").slideUp(300);
            obj.children(".edit-mode").slideDown(300);
    }
    else{
            obj.children(".normal-mode").slideDown(300);
            obj.children(".edit-mode").slideUp(300);
    }
}
function close_edit_btn(test){
    var obj=$(test).parent().parent().parent().parent().parent().parent().parent().parent();

    var editmode=obj.children(".edit-mode").css("display");

    if(editmode=="none"){
            obj.children(".normal-mode").slideUp(300);
            obj.children(".edit-mode").slideDown(300);
    }
    else{
            obj.children(".normal-mode").slideDown(300);
            obj.children(".edit-mode").slideUp(300);
    }
}
</script>