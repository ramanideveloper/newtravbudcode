<?php

/* @var $this \yii\web\View */
/* @var $content string */
include('includes/header.php');
use yii\helpers\Html;
use frontend\assets\AppAsset;

use yii\widgets\ActiveForm;
use yii\mongodb\ActiveRecord;

use frontend\models\LoginForm;
use frontend\models\SavePost;
use frontend\models\PostForm;
use frontend\models\UserForm;

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
    
    //$savedposts = SavePost::find()->with('userDetail')->with('postData')->where(['user_id' => $userid,'is_saved' => '1'])->orderBy(['saved_date'=>SORT_DESC])->all();
    
    $savedposts =    UserForm::find()->with('savedPosts')->where(['_id' => "$userid",'is_saved' => '1'])->all();
    
   //echo '<pre>';print_r($savedposts);exit;
    
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
				
				<h4>Saved Posts</h4>
				
				<div class="notice">	
                                    <div id="suceess" class="form-successmsg">Saved List Updated Successfully.</div>
                                    <div id="fail" class="form-failuremsg">Oops..!! Something went wrong. Please try later.</div>
				</div>
				
				<div class="bmcontent" id="bmcontent">
                                    <?php foreach($savedposts as $savedpost){
                                       $postid = $savedpost['post_id'];
                                        $userid = $savedpost['user_id'];
                                        $posttype = $savedpost['post_type'];
                                        $postinfo = PostForm::find()->where(['_id' => $postid])->one();
                                        $post_user_id = $postinfo['post_user_id'];
                                        $userinfo = LoginForm::find()->where(['_id' => $post_user_id])->one();
                                        $userfname = $savedpost['userDetail']['fname'];//$userinfo['fname'];
                                        $userlname = $userinfo['lname'];
                                        $name = $userfname." ".$userlname;
                                        $userphoto = $userinfo['photo'];
                                        $save_value = 'Saved from '.$name.'\'s post';
                                    ?>
				<ul class="setting-ul bookmarks">
									
					<li>
                        <?php $form = ActiveForm::begin(['id' => 'frmm-name','options'=>['onsubmit'=>'return false;',],]); ?> 
						<div class="setting-group">							
							<div class="normal-mode">
								<div class="row">
									<div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">
										<label><img alt="user-photo" class="img-responsive" src="<?php if(isset($userinfo['fb_id']) && !empty($userinfo['photo'])){echo $userinfo['photo'];
 }
else if(isset($userinfo['photo']) && !empty($userinfo['photo'])){
echo 'profile/'.$userinfo['photo'];
}
else{

echo 'profile/'.$userinfo['gender'].'.jpg';
} ?>"></label>
									</div>
									<div class="col-lg-8 col-md-7 col-sm-7 col-xs-12">										
										<div class="info">																   				   								
											<label><?= $save_value ?></label>
                                                                                        
										</div>
									</div>
									<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">										
										<div class="pull-right linkholder">
                                                                                    <a href="javascript:void(0)" onclick='window.open("<?php echo Yii::$app->urlManager->createUrl(['site/shareoption']);?>&postid=<?php echo $postid?>","MyNewWindow")'>Share</a>
                                                                                     | <a href="javascript:void(0)" onClick="save_post('<?php echo $postid?>','<?php echo $posttype?>')">Unsaved</a>
										</div>
									</div>
									<div class="clear"></div>
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