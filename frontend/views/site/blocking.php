<?php

/* @var $this \yii\web\View */
/* @var $content string */
include('includes/header.php');
use yii\helpers\Html;
use frontend\assets\AppAsset;

use yii\widgets\ActiveForm;
use yii\mongodb\ActiveRecord;
use yii\captcha\Captcha;

use frontend\models\LoginForm;
use frontend\models\UserSetting;
use frontend\models\Personalinfo;
use frontend\models\SecuritySetting;
use frontend\models\PrivacySetting;
use  yii\web\Session;
use yii\helpers\ArrayHelper;


$session = Yii::$app->session;


if($session->get('email'))
{
    $email = $session->get('email'); 
    /* $result Query For The Fetching Data From The 'tbl_user'*/
    
    $result = LoginForm::find()->where(['email' => $email])->one();
    $user = LoginForm::find()->where(['email' => $email])->one();
   
        $user_id = (string) $result['_id'];
        $username = $result['username'];
        $lname = $result['lname'];
        $password = $result['password'];
        $con_password = $result['con_password'];
        $birth_date = $result['birth_date'];
        $gender = $result['gender'];
        $city = $result['city'];
        $country = $result['country'];
        $phone = $result['phone'];
        $alternate_email = $result['alternate_email'];
        
    /* $result_setting Query For The Fetching Data From The 'user_setting'*/
		$result_setting = UserSetting::find()->where(['user_id' => $user_id])->one();
   
        $email_access = $result_setting['email_access'];
        
    /* $result_personal Query For The Fetching Data From The 'personal_info'*/
        
     $result_personal = Personalinfo::find()->where(['user_id' => $user_id])->one();
	 
	   /* $result_security Query For The Fetching Data From The 'SecuritySetting'*/
        
     $result_security = SecuritySetting::find()->where(['user_id' => $user_id])->one();
	 
	 $security_questions = $result_security['security_questions'];
	 $answer = $result_security['answer'];
	 $my_view_status = $result_security['my_view_status'];
	 $my_post_view_status = $result_security['my_post_view_status'];
	 $restricted_list = $result_security['restricted_list'];
	 $blocked_list = $result_security['blocked_list'];
	 $block_event_invites = $result_security['block_event_invites'];
	 $pair_social_actions = $result_security['pair_social_actions'];
	 $contact_me = $result_security['contact_me'];
	 $message_filtering = $result_security['message_filtering'];
	 $friend_request = $result_security['friend_request'];
	 $bothering_me = $result_security['bothering_me'];
	 $dashboard_view_status = $result_security['dashboard_view_status'];
	 $add_public_wall = $result_security['add_public_wall'];
	 $see_public_wall = $result_security['see_public_wall'];
	 $review_posts = $result_security['review_posts'];
	 $view_posts_tagged_in = $result_security['view_posts_tagged_in'];
	 $view_others_posts_on_mywall = $result_security['view_others_posts_on_mywall'];
	 $review_tags = $result_security['review_tags'];
	 $recent_activities = $result_security['recent_activities'];
	
     $restricted_str = '';
     if(isset($restricted_list) && $restricted_list != '') {
		$restricted_str .= '"';
		$restricted_str .= str_replace(",", '","', $restricted_list);
		$restricted_str .= '"';	
     }
    
	 $blocked_str = '';
     if(isset($blocked_list) && $blocked_list != '') {
		$blocked_str .= '"';
		$blocked_str .= str_replace(",", '","', $blocked_list);
		$blocked_str .= '"';	
     }	
	 
	 $blocked_event_str = '';
     if(isset($block_event_invites) && $block_event_invites != '') {
		$blocked_event_str .= '"';
		$blocked_event_str .= str_replace(",", '","', $block_event_invites);
		$blocked_event_str .= '"';	
     }	
	 
	 $message_filtering_str = '';
     if(isset($message_filtering) && $message_filtering != '') {
		$message_filtering_str .= '"';
		$message_filtering_str .= str_replace(",", '","', $message_filtering);
		$message_filtering_str .= '"';	
     }
	 
	 

     $privacy = PrivacySetting::find()->all();
     
     $listData=ArrayHelper::map($privacy,'privacy_code','privacy_name');
   
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
		<div class="setting-content">	 

			<?php include('includes/leftmenus.php');?>
			<div class="fbdetail-holder">
				
				<?php $form = ActiveForm::begin(['id' => 'frm-setting','options'=>['onsubmit'=>'return false;',],]); ?>  
									
				<div class="fb-formholder">		
					
					<div class="fb-formholder-box">
						<div class="formtitle"><h4>Manage Blocking</h4></div>
						<div class="notice">	
							<div id="suceess" class="form-successmsg">Your Settings updated successfully</div>
							<div id="fail" class="form-failuremsg">Oops..!! Somthing Went Wrong in Step 2</div>
							<div id="info" class="form-infomsg">Your Security Settings Saved Successfully</div>
						</div>
					
						<ul class="setting-ul desc-settings">
						
							<li>
								
								<div class="setting-group">							
									<div class="normal-mode">
										<div class="row">
											<div class="col-lg-3 col-md-4 col-sm-3 col-xs-12">
												<label>Restricted List</label>
											</div>
											<div class="col-lg-8 col-md-6 col-sm-7 col-xs-12">										
												<div class="info">																   				   								
													<label>                                                                                          
														People on this list cannot see my posts
													</label>
												</div>
											</div>
											<div class="col-lg-1 col-md-2 col-sm-2 col-xs-12">										
												<div class="pull-right  linkholder">
													<a href="javascript:void(0)" onClick="open_edit(this)"><i class="fa fa-pencil"></i></a>
												</div>
											</div>
										</div>	
									</div>	
									<div class="edit-mode">
										<div class="row">
											<div class="col-lg-3 col-md-4 col-sm-3 col-xs-12">
												<label>Restricted List</label>
											</div>
											<div class="col-lg-6 col-md-8 col-sm-9 col-xs-12">
												<div class="form-group">						


		<?= $form->field($model,'restricted_list')->dropDownList(ArrayHelper::map(LoginForm::find()->all(), 'email', 'email'),['class'=>'js-example-theme-multiple restricted-cls','style'=>'width: 100%','multiple'=>'multiple','id'=>'restricted_list1'])->label(false)?>
																						</div>						
											</div>
											<div class="col-lg-3 col-md-12 col-sm-12 col-xs-12 pull-right">
												<div class="form-group pull-right">						
													<div class="pull-right fb-btnholder">														
														<a class="btn btn-primary btn-sm" onClick="close_edit(this),setting()">Save</a>
														<a class="btn btn-primary btn-sm" onClick="close_edit(this)">Cancel</a>						
													</div>										
												</div>										
											</div>
										</div>	
									</div>	
								</div>	
												 
							</li>
							<li>
								
								<div class="setting-group">							
									<div class="normal-mode">
										<div class="row">
											<div class="col-lg-3 col-md-4 col-sm-3 col-xs-12">
												<label>Blocked List</label>
											</div>
											<div class="col-lg-8 col-md-6 col-sm-7 col-xs-12">										
												<div class="info">																   				   								
													<label>                                                                                          
														People on this list cannot interact with me
													</label>
												</div>
											</div>
											<div class="col-lg-1 col-md-2 col-sm-2 col-xs-12">										
												<div class="pull-right  linkholder">
													<a href="javascript:void(0)" onClick="open_edit(this)"><i class="fa fa-pencil"></i></a>
												</div>
											</div>
										</div>	
									</div>	
									<div class="edit-mode">
										<div class="row">
											<div class="col-lg-3 col-md-4 col-sm-3 col-xs-12">
												<label>Blocked List</label>
											</div>
											<div class="col-lg-6 col-md-8 col-sm-9 col-xs-12">
												<div class="form-group">						
													
											   
		 <?= $form->field($model,'blocked_list')->dropDownList(ArrayHelper::map(LoginForm::find()->all(), 'email', 'email'),['class'=>'js-example-theme-multiple blocked-cls','style'=>'width: 100%','multiple'=>'multiple','id'=>'blocked_list1'])->label(false)?>                                                                                  
												</div>						
											</div>
											<div class="col-lg-3 col-md-12 col-sm-12 col-xs-12 pull-right">
												<div class="form-group pull-right">						
													<div class="pull-right fb-btnholder">																		
														<a class="btn btn-primary btn-sm" onClick="close_edit(this),setting()">Save</a>
														<a class="btn btn-primary btn-sm" onClick="close_edit(this)">Cancel</a>
													</div>										
												</div>										
											</div>
										</div>	
									</div>	
								</div>	
												
							</li>
							<li>
								
								<div class="setting-group">							
									<div class="normal-mode">
										<div class="row">
											<div class="col-lg-3 col-md-4 col-sm-3 col-xs-12">
												<label>Blocked Event Invites</label>
											</div>
											<div class="col-lg-8 col-md-6 col-sm-7 col-xs-12">										
												<div class="info">																   				   								
													<label>                                                                                          
														People on this list can not send event invitation 
													</label>
												</div>
											</div>
											<div class="col-lg-1 col-md-2 col-sm-2 col-xs-12">										
												<div class="pull-right  linkholder">
													<a href="javascript:void(0)" onClick="open_edit(this)"><i class="fa fa-pencil"></i></a>
												</div>
											</div>
										</div>	
									</div>	
									<div class="edit-mode">
										<div class="row">
											<div class="col-lg-3 col-md-4 col-sm-3 col-xs-12">
												<label>Blocked Event Invites</label>
											</div>
											<div class="col-lg-6 col-md-8 col-sm-9 col-xs-12">
												<div class="form-group">						
													
																							 
		 <?= $form->field($model,'block_event_invites')->dropDownList(ArrayHelper::map(LoginForm::find()->all(), 'email', 'email'),['class'=>'js-example-theme-multiple event-cls','style'=>'width: 100%','multiple'=>'multiple','id'=>'block_event_invites1'])->label(false)?>                                                                                       
												</div>						
											</div>
											<div class="col-lg-3 col-md-12 col-sm-12 col-xs-12 pull-right">
												<div class="form-group pull-right">						
													<div class="pull-right fb-btnholder">																		
														<a class="btn btn-primary btn-sm" onClick="close_edit(this),setting()">Save</a>
														<a class="btn btn-primary btn-sm" onClick="close_edit(this)">Cancel</a>
													</div>										
												</div>										
											</div>
										</div>	
									</div>	
								</div>	
											   
							</li>
							
							
							<li>
								
								<div class="setting-group">							
									<div class="normal-mode">
										<div class="row">
											<div class="col-lg-3 col-md-4 col-sm-3 col-xs-12">
												<label>Message Filtering</label>
											</div>
											<div class="col-lg-8 col-md-6 col-sm-7 col-xs-12">										
												<div class="info">																   				   								
													<label>                                                                                          
														Whose messages do I want filtered into my Inbox?
													</label>
												</div>
											</div>
											<div class="col-lg-1 col-md-2 col-sm-2 col-xs-12">										
												<div class="pull-right  linkholder">
													<a href="javascript:void(0)" onClick="open_edit(this)"><i class="fa fa-pencil"></i></a>
												</div>
											</div>
										</div>	
									</div>	
									<div class="edit-mode">
										<div class="row">
											<div class="col-lg-3 col-md-4 col-sm-3 col-xs-12">
												<label>Message Filtering</label>
											</div>
											<div class="col-lg-6 col-md-8 col-sm-9 col-xs-12">
												<div class="form-group">						
		<?= $form->field($model,'message_filtering')->dropDownList(ArrayHelper::map(LoginForm::find()->all(), 'email', 'email'),['class'=>'js-example-theme-multiple filtering-cls','style'=>'width: 100%','multiple'=>'multiple','id'=>'message_filtering1'])->label(false)?>    											
													
												</div>						
											</div>
											<div class="col-lg-3 col-md-12 col-sm-12 col-xs-12 pull-right">
												<div class="form-group pull-right">						
													<div class="pull-right fb-btnholder">																		
														<a class="btn btn-primary btn-sm" onClick="close_edit(this),setting()">Save</a>
														<a class="btn btn-primary btn-sm" onClick="close_edit(this)">Cancel</a>
													</div>										
												</div>										
											</div>
										</div>	
									</div>	
								</div>	
											   
							</li>
							
							<!--<li>
								
								<div class="setting-group">							
									<div class="normal-mode">
										<div class="row">
											<div class="col-lg-3 col-md-4 col-sm-3 col-xs-12">
												<label>Restrict People</label>
											</div>
											<div class="col-lg-8 col-md-6 col-sm-7 col-xs-12">										
												<div class="info">																   				   								
													<label>                                                                                          
														Stop someone from bothering me
													</label>
												</div>
											</div>
											<div class="col-lg-1 col-md-2 col-sm-2 col-xs-12">										
												<div class="pull-right  linkholder">
													<a href="javascript:void(0)" onClick="open_edit(this)"><i class="fa fa-pencil"></i></a>
												</div>
											</div>
										</div>	
									</div>	
									<div class="edit-mode">
										<div class="row">
											<div class="col-lg-3 col-md-4 col-sm-3 col-xs-12">
												<label>Restrict People</label>
											</div>
											<div class="col-lg-4 col-md-5 col-sm-5 col-xs-12">
												<div class="form-group">						
													
													<div class="dropholder">													
														<div class="dropdown">
															<button aria-expanded="false" data-toggle="dropdown" type="button" class="btn btn-default dropdown-toggle btn-sm custom-select">
															
															<?php 
														   
																if($bothering_me == 'Block'){
																
																		echo '<span class="glyphicon glyphicon-ban-circle"></span>';														
																}
																else if($bothering_me == "Restrict"){
																	
																	echo '<span class="glyphicon glyphicon-ok-circle"></span>';
																}
																														else{
																															echo '<span class="glyphicon glyphicon-ban-circle"></span>';
																														}
																?>
															<span class="caret"></span></button>
															<ul class="dropdown-menu" id="bother">
																<li class="sel-Block"><a href="#" onClick="setSelectIcon(this)"><span class="glyphicon glyphicon-ban-circle"></span>Block</a></li>
																<li class="sel-Restrict"><a href="#" onClick="setSelectIcon(this)"><span class="glyphicon glyphicon-ok-circle"></span>Restrict</a></li>													
																
															</ul>													  													
														</div>
													</div>
												</div>						
											</div>
											<div class="col-lg-3 col-md-3 col-sm-4 col-xs-12 pull-right">
												<div class="form-group">						
													<div class="pull-right fb-btnholder">	
														<input type="hidden" name="bothering_me" id="bothering_me" value="Restrict" />
														<a class="btn btn-primary btn-sm" onClick="close_edit(this),setting()">Save</a>
														<a class="btn btn-primary btn-sm" onClick="close_edit(this)">Cancel</a>						
													</div>										
												</div>										
											</div>
										</div>	
									</div>	
								</div>	
										   
							</li>-->
							
								<?php ActiveForm::end() ?>                        
							

						</ul>
		 
					</div>
				</div>
				
			</div>
		
		</div>
	</div>

<script>
   $("#quest li").on("click", function(){
  var a = $(this).text();
  $('#security_questions').val(a);
});
  $("#look li").on("click", function(){
  var b = $(this).text();
  $('#my_view_status').val(b);
});
 $("#post li").on("click", function(){
  var c = $(this).text();
  $('#my_post_view_status').val(c);
});
 $("#contact li").on("click", function(){
  var d = $(this).text();
  $('#contact_me').val(d);
});
$("#friend li").on("click", function(){
  var e = $(this).text();
  $('#friend_request').val(e);
});
$("#bother li").on("click", function(){
  var f = $(this).text();
  $('#bothering_me').val(f);
});
$("#posting li").on("click", function(){
  var g = $(this).text();
  $('#add_public_wall').val(g);
});
$("#view li").on("click", function(){
  var h = $(this).text();
  $('#see_public_wall').val(h);
});
$("#review li").on("click", function(){
  var i = $(this).text();
  $('#review_posts').val(i);
});
$("#taged li").on("click", function(){
  var j = $(this).text();
  $('#view_posts_tagged_in').val(j);
});
$("#other_posts li").on("click", function(){
  var k = $(this).text();
  $('#view_others_posts_on_mywall').val(k);
});
$("#recent li").on("click", function(){
  var l = $(this).text();
  $('#recent_activities').val(l);
});
$("[name='switchbtn']").change(function() {

 if ($("[name='switchbtn']").is(':checked')){ 
 
	$('#pair_social_actions').val('Yes');
  } 
  else { 

	$('#pair_social_actions').val('No');
  }
});
$("[name='switchbtn2']").change(function() {
 if ($("[name='switchbtn2']").is(':checked')){ 
  
	$('#review_tags').val('Yes');
	
  } 
  else { 
	$('#review_tags').val('No');
  }
});
$("[name='switchbtn3']").change(function() {

 if ($("[name='switchbtn3']").is(':checked')){  
	$('#dashboard_view_status').val('Yes');
	
  } 
  else { 
	$('#dashboard_view_status').val('No');
  }
});
</script>

</script>
<script>

function setting(){
            $.ajax({
                   type: 'POST',
                   url: '<?php echo Yii::$app->urlManager->createUrl(['site/blocking']); ?>',
                   data: $("#frm-setting").serialize(),
                   success: function(data){
                       if(data == '1'){
                            $('#suceess').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
                       }
                       else{
                            $('#info').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
                       }
                   }
                   
               });
    }
	
$('#securitysetting-restricted_listt').keyup(function (){
	
        var vals = $('#securitysetting-restricted_list').val();
        
        if(vals != ''){
            vals = 'keys=' + vals;
             $.ajax({
                   type: 'POST',
                   url: "<?php echo Yii::$app->urlManager->createUrl(['site/get-email']); ?>",
                   data: vals,
                   success: function(data){
					   
					   alert(data);
					   
					   
                     /*
                    var states = ['Alabama', 'Alaska', 'Arizona', 'Arkansas', 'California',
                        'Colorado', 'Connecticut', 'Delaware', 'Florida', 'Georgia', 'Hawaii',
                        'Idaho', 'Illinois', 'Indiana', 'Iowa', 'Kansas', 'Kentucky', 'Louisiana',
                        'Maine', 'Maryland', 'Massachusetts', 'Michigan', 'Minnesota',
                        'Mississippi', 'Missouri', 'Montana', 'Nebraska', 'Nevada', 'New Hampshire',
                        'New Jersey', 'New Mexico', 'New York', 'North Carolina', 'North Dakota',
                        'Ohio', 'Oklahoma', 'Oregon', 'Pennsylvania', 'Rhode Island',
                        'South Carolina', 'South Dakota', 'Tennessee', 'Texas', 'Utah', 'Vermont',
                        'Virginia', 'Washington', 'West Virginia', 'Wisconsin', 'Wyoming'
                      ];

                    $('.typeahead').typeahead({
                      hint: true,
                      highlight: true,
                      minLength: 1
                    },
                    {
                      name: 'states',
                      source: substringMatcher(states)
                    });*/

            }
            }); 
        }
   });	
	
</script>
<script>
 function setSelectIcon(test){
	   
	  var pClass=$(test).parent().attr("class");
	  pClass=pClass.toLowerCase();
	  pClass=pClass.replace('selected','');
	  pClass=pClass.replace('sel-','');
	  
	  var ic=$(test).html().toLowerCase();
	  
	  ic = ic.replace(pClass,'');
	  
	  ic=ic +"<span class='caret'></span>"
	  
	  $(".custom-select").html(ic);
   }
</script>
	

<script language="javascript"> 

   function DoPost(){
       $.post("<?php echo Yii::$app->urlManager->createUrl(['site/logout']); ?>");  
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

		var obj=$(test).parents(".setting-group");
		var emode=obj.children(".edit-mode");
		var nmode=obj.children(".normal-mode");
				
		var editmode=emode.css("display");

		if(editmode=="none"){
			nmode.slideUp(300);
			emode.slideDown(300);
		}
		else{
			nmode.slideDown(300);
			emode.slideUp(300);
		}
   }

</script>
<!-- Switch -->
<script src="<?= $baseUrl?>/js/bootstrap-switch.js" ></script>
<script>
// Remember set you events before call bootstrapSwitch or they will fire after bootstrapSwitch's events

$("[name='switchbtn']").bootstrapSwitch({
	on: 'Yes',
	off: 'No',
	onLabel: 'Yes',
    offLabel: 'No',
	size: 'sm'
});
$("[name='switchbtn2']").bootstrapSwitch({
	on: 'Yes',
	off: 'No',
	onLabel: 'Yes',
    offLabel: 'No',
	size: 'sm'
});
$("[name='switchbtn3']").bootstrapSwitch({
	on: 'Yes',
	off: 'No',
	onLabel: 'Yes',
    offLabel: 'No',
	size: 'sm'
});

 function setSelectIcon(test){
	   
	  var pClass=$(test).parent().attr("class");
	  pClass=pClass.toLowerCase();
	  pClass=pClass.replace('selected','');
	  pClass=pClass.replace('sel-','');
	  
	  var ic=$(test).html().toLowerCase();
	  
	  ic = ic.replace(pClass,'');
	  
	  ic=ic +"<span class='caret'></span>"
	  
	  $(".custom-select").html(ic);
   }
</script>
<script>
$( document ).ready(function() {
  var a = $('#pair_social_actions').val();
  var b = $('#dashboard_view_status').val();
  var c = $('#review_tags').val();
  
  if(a == 'Yes')
  {

		$( "#soc div button:nth-child(1)" ).removeClass('btn btn-default  btn-sm');
		$( "#soc div button:nth-child(1)" ).addClass('btn active btn-primary btn-sm');
		$( "#soc div button:nth-child(2)" ).removeClass('btn active btn-default');
		$( "#soc div button:nth-child(2)" ).addClass('btn btn-default  btn-sm ');
	  
  }
  else{
		$( "#soc div button:nth-child(2)" ).removeClass('btn btn-default btn-sm');
		$( "#soc div button:nth-child(2)" ).addClass('btn active btn-default btn-sm');
		$( "#soc div button:nth-child(1)" ).removeClass('btn active btn-primary btn-sm');
		$( "#soc div button:nth-child(1)" ).addClass('btn btn-default  btn-sm');
	}
  if(b == 'Yes')
  {

		$( "#dash div button:nth-child(1)" ).removeClass('btn btn-default  btn-sm');
		$( "#dash div button:nth-child(1)" ).addClass('btn active btn-primary btn-sm');
		$( "#dash div button:nth-child(2)" ).removeClass('btn active btn-default');
		$( "#dash div button:nth-child(2)" ).addClass('btn btn-default  btn-sm ');
	  
  }
  else{
		$( "#dash div button:nth-child(2)" ).removeClass('btn btn-default btn-sm');
		$( "#dash div button:nth-child(2)" ).addClass('btn active btn-default btn-sm');
		$( "#dash div button:nth-child(1)" ).removeClass('btn active btn-primary btn-sm');
		$( "#dash div button:nth-child(1)" ).addClass('btn btn-default  btn-sm');
	}
	 if(c == 'Yes')
  {

		$( "#tg div button:nth-child(1)" ).removeClass('btn btn-default  btn-sm');
		$( "#tg div button:nth-child(1)" ).addClass('btn active btn-primary btn-sm');
		$( "#tg div button:nth-child(2)" ).removeClass('btn active btn-default');
		$( "#tg div button:nth-child(2)" ).addClass('btn btn-default  btn-sm ');
	  
  }
  else{
		$( "#tg div button:nth-child(2)" ).removeClass('btn btn-default btn-sm');
		$( "#tg div button:nth-child(2)" ).addClass('btn active btn-default btn-sm');
		$( "#tg div button:nth-child(1)" ).removeClass('btn active btn-primary btn-sm');
		$( "#tg div button:nth-child(1)" ).addClass('btn btn-default  btn-sm');
	}

});

</script>
<style>
    body{
        background-color:#f2f2f2;
    }
</style>
<script>

$(".js-example-theme-multiple").select2({
  tags: false
});

$(".restricted-cls").select2().val([<?php echo $restricted_str; ?>]).trigger("change");
$(".blocked-cls").select2().val([<?php echo $blocked_str; ?>]).trigger("change");
$(".event-cls").select2().val([<?php echo $blocked_event_str; ?>]).trigger("change");
$(".filtering-cls").select2().val([<?php echo $message_filtering_str; ?>]).trigger("change");

$("#restricted_list1").select2({
	  placeholder: "List of Restricted People",
	 // tags: true
	});

$("#blocked_list1").select2({
	  placeholder: "List of Blocked People",
	 // tags: true
	});
	
$("#block_event_invites1").select2({
	  placeholder: "Blocked Event Invites",
	 // tags: true
	});

$("#message_filtering1").select2({
	  placeholder: "Message Filtering",
	 // tags: true
	});	
	

</script>

<?php include('includes/footer.php');?>