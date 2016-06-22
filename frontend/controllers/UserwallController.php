<?php
namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\mongodb\ActiveRecord;
use yii\helpers\HtmlPurifier;
use yii\web\UploadedFile;
use yii\helpers\ArrayHelper;
use frontend\models\PostForm;
use frontend\models\LoginForm;
use frontend\models\UserForm;
use frontend\models\Like;
use yii\helpers\Url;
use frontend\models\Friend;
use frontend\models\Personalinfo;
use frontend\models\ProfileVisitor;
use frontend\models\SecuritySetting;
use frontend\models\SavePost;
use yii\widgets\ActiveForm;
use frontend\models\Interests;
use frontend\models\Language;
use frontend\models\UserSetting;
use frontend\models\Occupation;

 $mark = Yii::$app->getUrlManager()->getBaseUrl();

/**
 * Userwall controller
 */
class UserwallController extends Controller
{
   public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }
    /**
     * Create auth action
     *
     * @return mixed
     */ 
      public function actions()
      {
            return [
            'auth' => [
              'class' => 'yii\authclient\AuthAction',
              'successCallback' => [$this, 'oAuthSuccess'],
 
            ],
                'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
            ],
                
         ];           
      }
    
  /**
     * Displays wallpage.
     *
     * @return mixed
    */    
  
    public function actionIndex()
    { 
     
       $session = Yii::$app->session;
       $request = Yii::$app->request;
       $user_id = $request->get('id');  

       $model = new \frontend\models\LoginForm();
       $model2 = new \frontend\models\Personalinfo();
       
       if($session->get('email')){

            $emails = $session->get('email');
            $s3 = LoginForm::find()->where(['email' => $emails])->one();
            $visitor_id = (string)$s3['_id'];
            
            if($user_id != $visitor_id)
            {
                $visitors = ProfileVisitor::find()->with('user')->where(['user_id' => "$user_id",'visitor_id' => $visitor_id])->one();
                $date = time();
                
                if($visitors)
                {
                    $visitors->visited_date = $date;
                    $visitors->update();
                }
                else
                {
                    $visitor = new ProfileVisitor();
                    $visitor->user_id = $user_id;
                    $visitor->visitor_id = $visitor_id;
                    $visitor->visited_date = $date;
                    $visitor->status = '1';
                    $visitor->ip = $_SERVER['REMOTE_ADDR'];
                    $visitor->insert();
                }
            }
               
            $user_data =  Personalinfo :: getPersonalInfo($user_id);
           
            $user_friends =  Friend::getuserFriends($user_id);
			
           
            $user_basicinfo = LoginForm::find()->where(['_id' => $user_id])->one();
      
            $posts = PostForm::getUserPost($user_id);

            $photos =  PostForm::getUserPostPhotos($user_id);
            
            $likes = Like::getUserPostLike($user_id);
			
			$path = 'profile/';
            $usrfrdlist = array();
            foreach($user_friends AS $ud) {
			
                $id = (string)$ud['userdata']['_id'];
                $fbid =  $ud['userdata']['fb_id'];
                $dp = $this->getimage($ud['userdata']['_id'],'thumb');

                //$nm =  $ud['userdata']['fullname'];
                $nm = (isset($ud['userdata']['fullname']) && !empty($ud['userdata']['fullname'])) ? $ud['userdata']['fullname'] : $ud['userdata']['fname'].' '.$ud['userdata']['lname'];
                $usrfrdlist[] = array('id' => $id, 'fbid' => $fbid, 'name' => $nm, 'text' => $nm, 'thumb' => $dp);
            }

            return $this->render('index',array('posts' => $posts,'user_friends' => $user_friends,'user_basicinfo' => $user_basicinfo,'user_data' => $user_data,'likes' => $likes,'model2' => $model2, 
                        'frdlist' => $usrfrdlist));
        }else{
              
                $url = Yii::$app->urlManager->createUrl(['site/index']);
                Yii::$app->getResponse()->redirect($url);
        }
    }
    
    
     /**
     * Displays wall posts page.
     *
     * @return mixed
    */    
  
    public function actionPosts()
    { 
       $session = Yii::$app->session;
       $request = Yii::$app->request;
       $user_id = $request->get('id');  
     
       $model = new \frontend\models\LoginForm();
       
       if($session->get('email')){
            $posts = PostForm::getUserPost($user_id);
            $user_basicinfo = LoginForm::find()->where(['_id' => $user_id])->one();
            $user_data =  Personalinfo :: getPersonalInfo($user_id);
             return $this->render('posts',array('posts' => $posts,'user_basicinfo'=>$user_basicinfo,'user_data'=>$user_data));   
           
        }else{
                return $this->render('index', [
                   'model' => $model,
               ]);
        }
    }
    
   /**
     * Displays wall gallery page.
     *
     * @return mixed
    */    
  
    public function actionGallery()
    { 
      // echo 'jsn';die;
       $session = Yii::$app->session;
       $request = Yii::$app->request;
       $user_id = $request->get('id');  
     
       $model = new \frontend\models\LoginForm();
       
       if($session->get('email')){
           
            $gallery = PostForm::getUserPost($user_id);
            $user_basicinfo = LoginForm::find()->where(['_id' => $user_id])->one();
            $user_data =  Personalinfo :: getPersonalInfo($user_id);
            return $this->render('gallery',array('gallery' => $gallery,'user_basicinfo'=>$user_basicinfo,'user_data'=>$user_data));    
           
        }else{
                return $this->render('index', [
                   'model' => $model,
               ]);
        }
    }
    
    /**
     * Displays wall contributions page.
     *
     * @return mixed
    */    
  
    public function actionContributions()
    { 
      // echo 'jsn';die;
       $session = Yii::$app->session;
       $request = Yii::$app->request;
       $user_id = $request->get('id');  
     
       $model = new \frontend\models\LoginForm();
       
       if($session->get('email')){
           
            $contributions = PostForm::getUserPost($user_id);
            $user_basicinfo = LoginForm::find()->where(['_id' => $user_id])->one();
            $user_data =  Personalinfo :: getPersonalInfo($user_id);
            return $this->render('contributions',array('contributions' => $contributions,'user_basicinfo'=>$user_basicinfo,'user_data'=>$user_data));    
           
        }else{
                return $this->render('index', [
                   'model' => $model,
               ]);
        }
    }
        
    /**
     * Displays wall saved page.
     *
     * @return mixed
    */    
  
    public function actionSaved()
    { 
      // echo 'jsn';die;
       $session = Yii::$app->session;
       $request = Yii::$app->request;
       $user_id = $request->get('id');  
     
       $model = new \frontend\models\LoginForm();
       
       if($session->get('email')){
            $user_basicinfo = LoginForm::find()->where(['_id' => $user_id])->one();
            $user_data =  Personalinfo :: getPersonalInfo($user_id);
            //$saved = PostForm::getUserPost($user_id);
            $savedposts = SavePost::find()->with('userDetail')->with('postData')->where(['user_id' => $user_id,'is_saved' => '1'])->orderBy(['saved_date'=>SORT_DESC])->all();
            
            return $this->render('saved',array('savedposts' => $savedposts,'user_basicinfo'=>$user_basicinfo,'user_data'=>$user_data));   
           
        }else{
                return $this->render('index', [
                   'model' => $model,
               ]);
        }
    }
    
      /**
     * Displays friends.
     *
     * @return mixed
    */    
  
    public function actionFriends()
    { 
       $session = Yii::$app->session;
       $request = Yii::$app->request;
       $user_id = $request->get('id');  
     
       $model = new \frontend\models\LoginForm();
       
       if($session->get('email')){
           
            $user_friends = Friend::getuserFriends($user_id);
            $user_basicinfo = LoginForm::find()->where(['_id' => $user_id])->one();
            $user_data =  Personalinfo :: getPersonalInfo($user_id);
            
            return $this->render('friends',array('user_friends' => $user_friends,'user_basicinfo'=>$user_basicinfo,'user_data'=>$user_data));  
           
        }else{
                return $this->render('index', [
                   'model' => $model,
               ]);
        }
    }
    
     /**
     * Displays wall saved page.
     *
     * @return mixed
    */
    public function actionPhotos()
    {
       $session = Yii::$app->session;
       $request = Yii::$app->request;
       $user_id = $request->get('id');  
       $userid = $session->get('user_id');
       $secureity_model = new SecuritySetting();
       $result_security = SecuritySetting::find()->where(['user_id' => (string)$userid])->one();
       if($result_security){
            $post_status = $result_security['my_post_view_status'];
       }
       else
       {
           $post_status = 'Public';
       }
    
       $model = new \frontend\models\LoginForm();
       $post = new PostForm();
       $date = time();
      
     
        if($session->get('email'))
        {
           
            $user_basicinfo = LoginForm::find()->where(['_id' => $user_id])->one();
            $user_data =  Personalinfo :: getPersonalInfo($user_id);
            $photos = PostForm::getUserPost($user_id);
            
             if(isset($_POST) && !empty($_POST)){
          
            $album_title = $_POST['title'];
            if(empty($album_title)){$album_title = 'Untitled Album';}
            $album_description = $_POST['description'];
            $album_place = $_POST['place'];
            //$album_img_date = $_POST['album_img_date'];
            $album_img_date = '';
            
            
            $post->post_status = '1'; 
            $post->album_title = $album_title;
            $post->post_text = $album_description;
            $post->album_place = $album_place;
            $post->album_img_date = $album_img_date;
            $post->post_type = 'text and image'; 
            $post->is_album = '1'; 
            $album_privacy = $_POST['post_privacy'];
            $post->post_privacy = $album_privacy;
            $post->post_created_date = "$date";
            $post->is_deleted = '0';
            $post->post_user_id = (string)$userid;
            
             if(isset($_FILES) && !empty($_FILES)){
                $imgcount = count($_FILES["imageFile1"]["name"]);
                $img = '';
                $im = '';
                for($i=0; $i<$imgcount; $i++)
                {
                        if(isset($_FILES["imageFile1"]["name"][$i]) && $_FILES["imageFile1"]["name"][$i] != "") 
                        {
                           $url = '../web/uploads/';
                           $urls = '/uploads/';
                           move_uploaded_file($_FILES["imageFile1"]["tmp_name"][$i], $url.$date.$_FILES["imageFile1"]["name"][$i]);
                          
                           $img = $urls.$date.$_FILES["imageFile1"]["name"][$i].',';
                           $im = $im . $img;
                        }
                }
              
                $post->image = $im;
            }
            $post->insert();
            $last_insert_id = $post->_id;
            if($last_insert_id)
            {
                $data = [];
                $imgcontent = '';
                $lastpost = PostForm::find()->where(['_id' => $last_insert_id])->one();
                if(isset($lastpost['image']) && !empty($lastpost['image'])){
                    $eximgs = explode(',',$lastpost['image'],-1);
                    $totalimages = count($eximgs);
                    $imgpath = Yii::$app->getUrlManager()->getBaseUrl().$eximgs[0];
                    $imgcontent .= '<div class="album-col">';
                    $imgcontent .= '<div class="album-holder">';
                    $imgcontent .= '<div class="album-box">';
                    $imgcontent .= '<a class="listalbum-box" href="javascript:void(0)" onclick="viewalbum(\''.$last_insert_id.'\')" title="'.$album_title.'"><img src="'.$imgpath.'" alt=""></a>';
                    $imgcontent .= '<a href="javascript:void(0)" class="edit-album"><i class="fa fa-pencil"></i></a>';
                    $imgcontent .= '</div>';
                    $imgcontent .= '</div>';
                    $imgcontent .= '<div class="album-detail">';
                    $imgcontent .= '<a href="javascript:void(0)" onclick="viewalbum(\''.$last_insert_id.'\')" title="'.$album_title.'">'.$album_title.'</a>';
                    $imgcontent .= '<span>'.$totalimages.' photos </span>';
                    $imgcontent .= '<span><i class="fa fa-'.$album_privacy.'"></i></span>';
                    $imgcontent .= '</div>';
                    $imgcontent .= '</div>';
                }
                $data['previewalbumimages'] = $imgcontent;
                echo json_encode($data);
            }
            //return $this->render('photos',array('photos' => $photos,'user_basicinfo'=>$user_basicinfo,'user_data'=>$user_data));   
            
            }
           else
           {
               
                //return $this->render('photos',array('photos' => $photos,'user_basicinfo'=>$user_basicinfo,'user_data'=>$user_data));   
           }
        
           
        }
        else
        {
            return $this->render('index', [
               'model' => $model,
           ]);
        }
    }
    
     /**
     * Displays wall destinations page.
     *
     * @return mixed
    */    
  
    public function actionDestinations()
    { 
      // echo 'jsn';die;
       $session = Yii::$app->session;
       $request = Yii::$app->request;
       $user_id = $request->get('id');  
     
       $model = new \frontend\models\LoginForm();
       
       if($session->get('email')){
             $user_basicinfo = LoginForm::find()->where(['_id' => $user_id])->one();
            $user_data =  Personalinfo :: getPersonalInfo($user_id);
            
            $destinations = PostForm::getUserPost($user_id);
            
            return $this->render('destinations',array('destinations' => $destinations,'user_basicinfo'=>$user_basicinfo,'user_data'=>$user_data));    
           
        }else{
                return $this->render('index', [
                   'model' => $model,
               ]);
        }
    }
    
     /**
     * Displays wall likes page.
     *
     * @return mixed
    */    
  
    public function actionLikes()
    { 
       $session = Yii::$app->session;
       $request = Yii::$app->request;
       $user_id = $request->get('id');  
     
       $model = new \frontend\models\LoginForm();
       
       if($session->get('email')){
             $user_basicinfo = LoginForm::find()->where(['_id' => $user_id])->one();
            $user_data =  Personalinfo :: getPersonalInfo($user_id);
            $likes = Like::getUserPostLike($user_id);
              
            return $this->render('likes',array('likes' => $likes,'user_basicinfo'=>$user_basicinfo,'user_data'=>$user_data));    
           
        }else{
                return $this->render('index', [
                   'model' => $model,
               ]);
        }
    }
    
     /**
     * Displays wall refers page.
     *
     * @return mixed
    */    
  
    public function actionRefers()
    { 
       $session = Yii::$app->session;
       $request = Yii::$app->request;
       $user_id = $request->get('id');  
     
       $model = new \frontend\models\LoginForm();
       
       if($session->get('email')){
             $user_basicinfo = LoginForm::find()->where(['_id' => $user_id])->one();
            $user_data =  Personalinfo :: getPersonalInfo($user_id);
            $refers = PostForm::getUserPost($user_id);
            return $this->render('refers',array('refers' => $refers,'user_basicinfo'=>$user_basicinfo,'user_data'=>$user_data));   
           
        }else{
                return $this->render('index', [
                   'model' => $model,
               ]);
        }
    }
    
     /**
     * Displays wall endorsements page.
     *
     * @return mixed
    */    
  
    public function actionEndorsements()
    { 
      // echo 'jsn';die;
       $session = Yii::$app->session;
       $request = Yii::$app->request;
       $user_id = $request->get('id');  
     
       $model = new \frontend\models\LoginForm();
       
       if($session->get('email')){
             $user_basicinfo = LoginForm::find()->where(['_id' => $user_id])->one();
            $user_data =  Personalinfo :: getPersonalInfo($user_id);
            $endorsements = PostForm::getUserPost($user_id);
            return $this->render('endorsements',array('endorsements' => $endorsements,'user_basicinfo'=>$user_basicinfo,'user_data'=>$user_data));    
           
        }else{
                return $this->render('index', [
                   'model' => $model,
               ]);
        }
    }
    
     public function actionGetdata()
     {
        $session = Yii::$app->session;
        $user_id = $userid =  $session->get('user_id');  
        $posts = PostForm::getUserPost($_POST['uid']);
        $result = UserForm::find()->where(['_id' => "$user_id"])->one();
        $uid = $_POST['uid'];
        $result_security = SecuritySetting::find()->where(['user_id' => "$uid"])->one();
        //print_r($result_security);exit;
        if($result_security)
        {
            $my_post_view_status = $result_security['my_post_view_status'];
            if($my_post_view_status == 'Private') {$post_dropdown_class = 'lock';}
            else if($my_post_view_status == 'Friends') {$post_dropdown_class = 'user';}
            else {$my_post_view_status = 'Public'; $post_dropdown_class = 'globe';}
        }
        else
        {
            $my_post_view_status = 'Public';
            $post_dropdown_class = 'globe';
        }
        ?>
       <?php if($_POST['page'] == 'posts') {     
	   $login_img = $this->getimage($result['_id'],'thumb');
           ?>
                             <div class="col-lg-8 col-md-8 col-sm-8 secondcol data-section-about">


                                <div class="posts">
                                   <div class="tb-panel-box02 panel-shadow">
							<div class="panel-body toolbox newpost">
							
	
					<!-- <p><span class="context-menu-one btn btn-neutral">right click me</span></p> -->



								<!--
								<div class="tb-inner-title01 clearfix"> 
									<span>Logged in as <a href="#" class="no-ul"><?= ucfirst($result['fname']) . ' ' . ucfirst($result['lname']); ?></a>  </span>
								</div>
								-->
								<div class="post_loadin_img"></div>
								<div class="newpost-topbar">
									<div class="post-title">
										<div class="tb-user-post-middle">											
											<div class="sliding-middle-out full-width">											
												<input type="text" placeholder="Title of this post" id="title"/>
											</div>
										</div>
									</div>
									<div class="tarrow dotarrow">
										<a href="javascript:void(0)" class="alink">
											<span class="more-option"><svg height="100%" width="100%" viewBox="0 0 50 50"><path class="Ce1Y1c" d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/></svg></a></span>&nbsp;
										<div class="drawer">

											<ul class="sort-ul">
                                                <li class="disable_share"><span class="dis_share check-option">✓</span><a href="javascript:void(0)">Disable Sharing</a></li>																		
												<li class="disable_comment"><span class="dis_comment check-option">✓</span><a href="javascript:void(0)">Disable Comments</a></li>																		
												<li class="cancel_post"><a href="javascript:void(0)" onclick="closeAllDrawers(this)">Cancel Post</a></li>																		
											</ul>	
										</div>
									</div>
								</div>
								
								<div class="post-holder">
								
									<div class="postarea">
										<div class="post-pic">
											<img src="<?= $login_img;?>" class="img-responsive" alt="user-photo">
										</div>
										<div class="post-desc">								
											<div class="tb-user-post-middle">												

												<textarea rows="2" title="Add a comment" class="form-control textInput" id="textInput" name="textInput" onclick="displaymenus()"   placeholder="<?= ucfirst($result['fname']); ?>, what's new?"></textarea>           

											</div>	
										</div>
										<div style="clear: both;"></div>
										<!--<div>
											<div class='tagcontent-area'>
										
											</div>
											<div style="clear: both;"></div>		
											<div class='taginpt-area'>
												<input type="text" id="taginput" name="taginput" class="js-example-theme-multiple" style="width: 100%;"/>
											</div>
										</div>-->										
									</div>
								
									<div id="image-holder">
										<div class="img-row">

										</div> 
									</div>
									<div style="clear: both;"></div>
									
									<div class="tags-added"></div>
									<div id="tag" class="addpost-tag">
										
										<span class="ltitle">With</span>
										<div class="tag-holder">
											<select id="taginput" placeholder="Who are with you?" multiple="multiple"></select>		
										</div>
									</div>
									<div style="clear: both;"></div>
									<div id="area" class="addpost-location">
										<span class="ltitle">At</span>
										<div class="loc-holder">
											<input type="text" name="current_location" class="current_location getplacelocation" value="" id="autocomplete" onFocus="geolocate()" placeholder="Where are you?" />
											<input type="hidden" name="country" id="country" />
											<input type="hidden" name="isd_code" id="isd_code" />
										</div>
									</div>						
								</div>
								
								<input type="text" name="url" size="64" id="url"  style="display: none"/>
								<input type="button" name="attach" value="Attach" id="mark" style="display: none" />
								<form enctype="multipart/form-data" name="imageForm">
									
									<div class="tb-user-post-bottom clearfix">
										<div class="tb-user-icon" style="display:none"> 
											<label class="myLabel">
												<!-- uplaod image -->
	<input type="file" id="imageFile1" name="imageFile1[]" required="" multiple="true"/>
        <input type="hidden" id="counter" name="counter">
												<span aria-hidden="true" class="glyphicon glyphicon-camera tb-icon-fix001"></span>
											</label>
											<a href="javascript:void(0)" class="tb-icon-fix001 add-tag"><span aria-hidden="true"  class="glyphicon glyphicon-user"></span></a>

											<a href="javascript:void(0)" class="tb-icon-fix001 add-loc"><span aria-hidden="true" class="glyphicon glyphicon-map-marker"></span></a>
											<a href="javascript:void(0)" class="tb-icon-fix001 add-title"><span aria-hidden="true" class="glyphicon glyphicon-text-size"></span></a> 
										</div>
											
										<div class="user-post fb-btnholder">
											<div class="dropdown tb-user-post">

												<button aria-expanded="false" data-toggle="dropdown" type="button" class="btn btn-default dropdown-toggle btn-sm custom-select custom-select-posting-security"><span class="glyphicon glyphicon-<?= $post_dropdown_class ?>"></span> <?= $my_post_view_status ?> <span class="caret"></span></button>

												<ul class="dropdown-menu" id="posting-security">
													<li class="sel-private"><a href="javascript:void(0)" onClick="setSecuritySelect(this, 'Private')"><span class="glyphicon glyphicon-lock"></span> Private</a></li>
													<li class="divider"></li>
													<li class="sel-friends"><a href="javascript:void(0)" onClick="setSecuritySelect(this, 'Friends')"><span class="glyphicon glyphicon-user"></span> Friends</a></li>
													<li class="divider"></li>
													<li class="sel-public"><a href="javascript:void(0)" onClick="setSecuritySelect(this, 'Public')"><span class="glyphicon glyphicon-globe"></span> Public</a></li>
													<li class="divider"></li>
													<li class="sel-custom"><a href="#custom-share" class="popup-modal" onClick="sendSelId('posting-security')"><span class="glyphicon glyphicon-cog"></span> Custom</a></li>
												</ul>
                                                                                                <input type="hidden" name="imgfilecount" id="imgfilecount" value="0" />
                                                                                                <input type="hidden" name="post_privacy" id="post_privacy" value="<?= $my_post_view_status ?>"/>
                                                                                                <input type="hidden" name="share_setting" id="share_setting" value="Enable"/>
												<input type="hidden" name="comment_setting" id="comment_setting" value="Enable"/>
												<input type="hidden" name="link_title" id="link_title" />
												<input type="hidden" name="link_url" id="link_url" />
												<input type="hidden" name="link_description" id="link_description" />
												<input type="hidden" name="link_image" id="link_image" />

												<button class="btn btn-primary btn-sm ml-5"  onclick="postStatus();" type="button" name="post" id="post" disabled><span class="glyphicon glyphicon-send"></span>Post</button>

											</div>
										</div>
									</div>
								</form>
							
							</div>
						</div>
                                          <!-- fetch all post--> 
                                          <div id="ajax-content"></div>

                        <div id="post-status-list">    <?php 
                        foreach($posts as $post)
                        {
                            $existing_posts = '1';
                            $this->display_last_post($post['_id'],$existing_posts);
                        }
                        ?></div>
                                 </div>	 
                                 </div>
			<script>
			$(".add-loc").click(function(e){
	
				var getParent=$(this).parents(".toolbox");		
				getParent.find('.addpost-location').show(300);
			});
			$(document).ready(function(){
		
				 $("body *").each(function(){
					
					if($(this).prop("rel")){
						
						var rel=$(this).prop("rel");
						
						if(rel.indexOf("prettyPhoto") != -1){
							//console.log($(this).prop("rel"));
							jq("area[rel^='prettyPhoto']").prettyPhoto();
							jq(".gallery a[rel^='prettyPhoto']").prettyPhoto({animation_speed:'normal',theme:'light_square',social_tools: ' '});
						}
						
					}
				 });
				 $('.profilelink').hover(
					
			   function () {
				  var pp=$(this).parents(".tb-panel-head").children(".profiletip-holder");								  
				  pp.show();				  
			   }, 
				
			   function (e) {
				
				 var pp=$(this).parents(".tb-panel-head").children(".profiletip-holder");				
				
				 if(!pp.hasClass("pp-hovered")){
					 $(".profiletip-holder").hide();
				 }
			   
			   }
			);
			 $('.profiletip-holder').hover(
						
				   function () {
					  $(this).addClass("pp-hovered");
					  $(this).show();				  
				   }, 
					
				   function () {				 				 
					 $(this).removeClass("pp-hovered");
					 $(this).hide();
				   }
			);
			
				
			});
			function displaymenus(fromWhere)
			{
				$(".tb-user-icon").show();
			}
			function link_preview() {
				
                var expression = /[-a-zA-Z0-9@:%_\+.~#?&//=]{2,256}\.[a-z]{2,4}\b(\/[-a-zA-Z0-9@:%_\+.~#?&//=]*)?/gi;
                var regex = new RegExp(expression);
                var url = $("#textInput").val();
                if (url.match(regex)) {
					toggleAbilityPostBtn(HIDE);
					

                    urls = "<?php echo \Yii::$app->getUrlManager()->createUrl('site/curl_fetch') ?>";
                    link = "url=" + $('#textInput').val();
                    $('#load').show();

                    $.ajax({
                        url: urls, //Server script to process data
                        type: 'POST',
                        data: link,
                        success: function (data) {
							if ($("div.preview_wrap").length)
							$("div.preview_wrap").remove();

                            var result = $.parseJSON(data);
							
                            $("#link_description").val(result['desc']);
                            $("#link_title").val(result['title']);
                            $("#link_image").val(result['image']);
                            $("#link_url").val(result['url']);
                            $("#newPost").hide();

                            selector = result['title'];
							
							if(result['image'] != 'No Image')
							{
								$("#textInput").after('<div class="preview_wrap"><div class="linkinfo-holder"><div class="previewImage"><img height="100" width="100" src="' + result['image'] + '"/></div> <div class="previewDesc"><div class="previewLoading">' + result['title'] + '</div>' + result['desc'] + '</div></div></div>');
							}
							else
							{
								
								$("#textInput").after('<div class="preview_wrap"><div class="previewLoading">' + result['title'] + '</div> <div class="previewDesc">' + result['desc'] + '</div></div>');
							
							}
                            
							// enable post button
							toggleAbilityPostBtn(SHOW);

                        }
                    });
                } else {
                    return false;

                }
            }
			</script>

        
        

       <?php } 
       elseif($_POST['page'] == 'gallery'){
             $gallery = PostForm::getUserPost($_POST['uid']);
             $galleryuser = LoginForm::find()->where(['_id' => $_POST['uid']])->one();
             $loginuser = LoginForm::find()->where(['_id' => "$user_id"])->one();
                $gallery_img = $this->getimage($loginuser['_id'],'photo');
                //echo Yii::getAlias('@bower');
           ?>    
            <!--<script src="http://localhost/travbudcode/frontend/web/assets/c8366951/js/waterfall-light.js" type="text/javascript"></script>-->
            <script src="http://www.travbud.com/frontend/web/assets/a8535972/js/waterfall-light.js" type="text/javascript"></script>
            <script>
                $(function(){
                    $('#box').waterfall({gridWidth : [0,400,800]});
                });
            </script>
            <script>
                $(document).ready(function(){

                         $("body *").each(function(){

                                if($(this).prop("rel")){

                                        var rel=$(this).prop("rel");

                                        if(rel.indexOf("prettyPhoto") != -1){
                                                //console.log($(this).prop("rel"));
                                                jq("area[rel^='prettyPhoto']").prettyPhoto();
                                                jq(".gallery a[rel^='prettyPhoto']").prettyPhoto({animation_speed:'normal',theme:'light_square',social_tools: ' '});
                                        }

                                }
                         });

                });
            </script>
            <div class="col-lg-8 col-md-8 col-sm-8 secondcol data-section-about gallery">
			
			<!--<div id="box" class="gallery">
				<div class="wcard">
					<div class="img-holder">
						<a href="/travbudcode/frontend/web/profile/02865234m4.jpg" rel="prettyPhoto[gallery3]"><img alt="" src="/travbudcode/frontend/web/profile/02865234m4.jpg"></a>
						
						<div class="ititle">
							<div class="ititle-icon"><i class="fa fa-camera"></i></div>
							<div class="ititle-text">
								Picture added Yesterday
								<span>Photo by Adel Hasanat</span>
							</div>
							<a class="ititle-extra" href="javascript:void(0)"><i class="fa fa-map-marker"></i></a>
						</div>
						<div class="ioption">
							<a class="iedit"><i class="fa fa-pencil"></i> Edit</a>
							<a class="idelete"><i class="fa fa-trash"></i></a>
						</div>
					</div>
					<div class="desc-holder">
						<div class="counter-section">
							<a href="javascript:void(0)"><i class="fa fa-comments"></i>2</a>
							<a href="javascript:void(0)"><i class="fa fa-thumbs-up"></i>45</a>
						</div>
						<div class="comments-section">
							<div class="img-area"><img src="/travbudcode/frontend/web/profile/thumb_02865234m4.jpg" class="img-responsive" alt="user-photo"></div>
							<div class="desc-area"><textarea placeholder="Type your comment"></textarea></div>
						</div>
					</div>
				</div>
				<div class="wcard">
					<div class="img-holder">
						<a href="/travbudcode/frontend/web/profile/thumb_01273936bcef3f8d9b4b90f2fa5216864c65a496.jpg" rel="prettyPhoto[gallery3]"><img alt="" src="/travbudcode/frontend/web/profile/thumb_01273936bcef3f8d9b4b90f2fa5216864c65a496.jpg"></a>
					</div>	
					<div class="desc-holder">
						<div class="counter-section">
							<a href="javascript:void(0)"><i class="fa fa-comments"></i>2</a>
							<a href="javascript:void(0)"><i class="fa fa-thumbs-up"></i>45</a>
						</div>
						<div class="comments-section">
							<div class="img-area"><img src="/travbudcode/frontend/web/profile/thumb_01273936bcef3f8d9b4b90f2fa5216864c65a496.jpg" class="img-responsive" alt="user-photo"></div>
							<div class="desc-area"><textarea placeholder="Type your comment"></textarea></div>
						</div>
					</div>

				</div>
				<div class="wcard">
					<div class="img-holder">
						<a href="/travbudcode/frontend/web/profile/12374702Beach-Abel-Tasman-National-Park-new-Zealand-1050x3360.jpg" rel="prettyPhoto[gallery3]"><img alt="" src="/travbudcode/frontend/web/profile/12374702Beach-Abel-Tasman-National-Park-new-Zealand-1050x3360.jpg"></a>
						<div class="ititle">
							<div class="ititle-icon"><i class="fa fa-camera"></i></div>
							<div class="ititle-text">
								Picture added 20 Feb
								<span>Photo by Adel Hasanat</span>
							</div>
							<a class="ititle-extra" href="javascript:void(0)"><i class="fa fa-star"></i></a>
						</div>
						<div class="ioption">
							<a class="iedit"><i class="fa fa-pencil"></i> Edit</a>
							<a class="idelete"><i class="fa fa-trash"></i></a>
						</div>
					</div>
					<div class="desc-holder">
						<div class="counter-section">
							<a href="javascript:void(0)"><i class="fa fa-comments"></i>2</a>
							<a href="javascript:void(0)"><i class="fa fa-thumbs-up"></i>45</a>
						</div>
						<div class="comments-section">
							<div class="img-area"><img src="/travbudcode/frontend/web/profile/thumb_01273936bcef3f8d9b4b90f2fa5216864c65a496.jpg" class="img-responsive" alt="user-photo"></div>
							<div class="desc-area"><textarea placeholder="Type your comment"></textarea></div>
						</div>
					</div>
				</div>
				<div class="wcard">
					<div class="img-holder">
						<a href="/travbudcode/frontend/web/profile/77997523demo.jpg" rel="prettyPhoto[gallery3]"><img alt="" src="/travbudcode/frontend/web/profile/77997523demo.jpg"></a>
						<div class="ititle">
							<div class="ititle-icon"><i class="fa fa-camera"></i></div>
							<div class="ititle-text">
								Picture added Today
								<span>Photo by Adel Hasanat</span>
							</div>
							<a class="ititle-extra" href="javascript:void(0)"><i class="fa fa-map-marker"></i></a>
						</div>
						<div class="ioption">
							<a class="iedit"><i class="fa fa-pencil"></i> Edit</a>
							<a class="idelete"><i class="fa fa-trash"></i></a>
						</div>
					</div>
					<div class="desc-holder">
						<div class="counter-section">
							<a href="javascript:void(0)"><i class="fa fa-comments"></i>2</a>
							<a href="javascript:void(0)"><i class="fa fa-thumbs-up"></i>45</a>
						</div>
						<div class="comments-section">
							<div class="img-area"><img src="/travbudcode/frontend/web/profile/thumb_01273936bcef3f8d9b4b90f2fa5216864c65a496.jpg" class="img-responsive" alt="user-photo"></div>
							<div class="desc-area"><textarea placeholder="Type your comment"></textarea></div>
						</div>
					</div>
				</div>
				<div class="wcard">
					<div class="img-holder"><a href="/travbudcode/frontend/web/profile/61863650t1.jpg"><img alt="" src="/travbudcode/frontend/web/profile/61863650t1.jpg"><a/></div>			
					<div class="desc-holder">
						<div class="counter-section">
							<a href="javascript:void(0)"><i class="fa fa-comments"></i>2</a>
							<a href="javascript:void(0)"><i class="fa fa-thumbs-up"></i>45</a>
						</div>
						<div class="comments-section">
							<div class="img-area"><img src="/travbudcode/frontend/web/profile/thumb_01273936bcef3f8d9b4b90f2fa5216864c65a496.jpg" class="img-responsive" alt="user-photo"></div>
							<div class="desc-area"><textarea placeholder="Type your comment"></textarea></div>
						</div>
					</div>
				</div>
				<div class="wcard">
					<div class="img-holder">
						<a href="/travbudcode/frontend/web/profile/thumb_01273936bcef3f8d9b4b90f2fa5216864c65a496.jpg" rel="prettyPhoto[gallery3]"><img alt="" src="/travbudcode/frontend/web/profile/thumb_01273936bcef3f8d9b4b90f2fa5216864c65a496.jpg"></a>
						<div class="ititle">
							<div class="ititle-icon"><i class="fa fa-user"></i></div>
							<div class="ititle-text">
								Picture added on 30 Apr
								<span>Photo by Adel Hasanat</span>
							</div>
							<a class="ititle-extra" href="javascript:void(0)"><i class="fa fa-star"></i></a>
						</div>
						<div class="ioption">
							<a class="iedit"><i class="fa fa-pencil"></i> Edit</a>
							<a class="idelete"><i class="fa fa-trash"></i></a>
						</div>
					</div>
					<div class="desc-holder">
						<div class="counter-section">
							<a href="javascript:void(0)"><i class="fa fa-comments"></i>2</a>
							<a href="javascript:void(0)"><i class="fa fa-thumbs-up"></i>45</a>
						</div>
						<div class="comments-section">
							<div class="img-area"><img src="/travbudcode/frontend/web/profile/thumb_01273936bcef3f8d9b4b90f2fa5216864c65a496.jpg" class="img-responsive" alt="user-photo"></div>
							<div class="desc-area"><textarea placeholder="Type your comment"></textarea></div>
						</div>
					</div>
				</div>
				<div class="wcard">
					<div class="img-holder"><a href="/travbudcode/frontend/web/profile/12374702Beach-Abel-Tasman-National-Park-new-Zealand-1050x3360.jpg" rel="prettyPhoto[gallery3]"><img alt="" src="/travbudcode/frontend/web/profile/12374702Beach-Abel-Tasman-National-Park-new-Zealand-1050x3360.jpg"></a></div>
					<div class="desc-holder">
						<div class="counter-section">
							<a href="javascript:void(0)"><i class="fa fa-comments"></i>2</a>
							<a href="javascript:void(0)"><i class="fa fa-thumbs-up"></i>45</a>
						</div>
						<div class="comments-section">
							<div class="img-area"><img src="/travbudcode/frontend/web/profile/thumb_01273936bcef3f8d9b4b90f2fa5216864c65a496.jpg" class="img-responsive" alt="user-photo"></div>
							<div class="desc-area"><textarea placeholder="Type your comment"></textarea></div>
						</div>
					</div>
				</div>
				<div class="wcard">
					<div class="img-holder">
						<a href="/travbudcode/frontend/web/profile/77997523demo.jpg" rel="prettyPhoto[gallery3]"><img alt="" src="/travbudcode/frontend/web/profile/77997523demo.jpg"></a>
						<div class="ititle">
							<div class="ititle-icon"><i class="fa fa-user"></i></div>
							<div class="ititle-text">
								Picture added 4 Jan
								<span>Photo by Adel Hasanat</span>
							</div>
							<a class="ititle-extra" href="javascript:void(0)"><i class="fa fa-star"></i></a>
						</div>
						<div class="ioption">
							<a class="iedit"><i class="fa fa-pencil"></i> Edit</a>
							<a class="idelete"><i class="fa fa-trash"></i></a>
						</div>
					</div>
					<div class="desc-holder">
						<div class="counter-section">
							<a href="javascript:void(0)"><i class="fa fa-comments"></i>2</a>
							<a href="javascript:void(0)"><i class="fa fa-thumbs-up"></i>45</a>
						</div>
						<div class="comments-section">
							<div class="img-area"><img src="/travbudcode/frontend/web/profile/thumb_01273936bcef3f8d9b4b90f2fa5216864c65a496.jpg" class="img-responsive" alt="user-photo"></div>
							<div class="desc-area"><textarea placeholder="Type your comment"></textarea></div>
						</div>
					</div>
				</div>
				<div class="wcard">
					<div class="img-holder"><a href="/travbudcode/frontend/web/profile/12374702Beach-Abel-Tasman-National-Park-new-Zealand-1050x3360.jpg" rel="prettyPhoto[gallery3]"><img alt="" src="/travbudcode/frontend/web/profile/12374702Beach-Abel-Tasman-National-Park-new-Zealand-1050x3360.jpg"></a></div>
					<div class="desc-holder">
						<div class="counter-section">
							<a href="javascript:void(0)"><i class="fa fa-comments"></i>2</a>
							<a href="javascript:void(0)"><i class="fa fa-thumbs-up"></i>45</a>
						</div>
						<div class="comments-section">
							<div class="img-area"><img src="/travbudcode/frontend/web/profile/thumb_01273936bcef3f8d9b4b90f2fa5216864c65a496.jpg" class="img-responsive" alt="user-photo"></div>
							<div class="desc-area"><textarea placeholder="Type your comment"></textarea></div>
						</div>
					</div>
				</div>
				<div class="wcard">
					<div class="img-holder"><a href="/travbudcode/frontend/web/profile/77997523demo.jpg" rel="prettyPhoto[gallery3]"><img alt="" src="/travbudcode/frontend/web/profile/77997523demo.jpg"></a></div>
					<div class="desc-holder">
						<div class="counter-section">
							<a href="javascript:void(0)"><i class="fa fa-comments"></i>2</a>
							<a href="javascript:void(0)"><i class="fa fa-thumbs-up"></i>45</a>
						</div>
						<div class="comments-section">
							<div class="img-area"><img src="/travbudcode/frontend/web/profile/thumb_01273936bcef3f8d9b4b90f2fa5216864c65a496.jpg" class="img-responsive" alt="user-photo"></div>
							<div class="desc-area"><textarea placeholder="Type your comment"></textarea></div>
						</div>
					</div>
				</div>
				<div class="wcard">
					<div class="img-holder"><a href="/travbudcode/frontend/web/profile/12374702Beach-Abel-Tasman-National-Park-new-Zealand-1050x3360.jpg" rel="prettyPhoto[gallery3]"><img alt="" src="/travbudcode/frontend/web/profile/12374702Beach-Abel-Tasman-National-Park-new-Zealand-1050x3360.jpg"></a></div>
					<div class="desc-holder">
						<div class="counter-section">
							<a href="javascript:void(0)"><i class="fa fa-comments"></i>2</a>
							<a href="javascript:void(0)"><i class="fa fa-thumbs-up"></i>45</a>
						</div>
						<div class="comments-section">
							<div class="img-area"><img src="/travbudcode/frontend/web/profile/thumb_01273936bcef3f8d9b4b90f2fa5216864c65a496.jpg" class="img-responsive" alt="user-photo"></div>
							<div class="desc-area"><textarea placeholder="Type your comment"></textarea></div>
						</div>
					</div>
				</div>
				<div class="wcard">
					<div class="img-holder"><a href="/travbudcode/frontend/web/profile/77997523demo.jpg" rel="prettyPhoto[gallery3]"><img alt="" src="/travbudcode/frontend/web/profile/77997523demo.jpg"></a></div>
					<div class="desc-holder">
						<div class="counter-section">
							<a href="javascript:void(0)"><i class="fa fa-comments"></i>2</a>
							<a href="javascript:void(0)"><i class="fa fa-thumbs-up"></i>45</a>
						</div>
						<div class="comments-section">
							<div class="img-area"><img src="/travbudcode/frontend/web/profile/thumb_01273936bcef3f8d9b4b90f2fa5216864c65a496.jpg" class="img-responsive" alt="user-photo"></div>
							<div class="desc-area"><textarea placeholder="Type your comment"></textarea></div>
						</div>
					</div>
					</div>
				</div>
			</div>-->
			
			
			<div id="box" class="gallery">			  
				<?php 
				foreach($gallery as $gallery_item)
				{
				   if(isset($gallery_item['image']) && !empty($gallery_item['image'])){
						   if($gallery_item['post_type'] == 'profilepic' || $gallery_item['is_coverpic'] == '1')
						   {
								$picsize = '';
								$val = getimagesize('profile/'.$gallery_item['image']);
								$picsize .= $val[0] .'x'. $val[1] .', ';
								if($val[0] > $val[1]){$imgclass = 'himg';}else if($val[1] > $val[0]){$imgclass = 'vimg';}else{$imgclass = 'simg';}
							   ?>
                                                                <div class="wcard">
                                                                    <div class="img-holder">
                                                                        <a href="<?= Yii::$app->getUrlManager()->getBaseUrl() ?><?= '/profile/'.$gallery_item['image'] ?>" rel="prettyPhoto[gallery1]" class="listalbum-box"><img src="<?= Yii::$app->getUrlManager()->getBaseUrl() ?><?= '/profile/'.$gallery_item['image'] ?>" alt=""></a>
                                                                        <div class="ititle">
                                                                                <div class="ititle-icon"><i class="fa fa-user"></i></div>
                                                                                <div class="ititle-text">
                                                                                    Picture added Yesterday
                                                                                    <span>Photo by <?= ucfirst($galleryuser['fname']) . ' ' . ucfirst($galleryuser['lname']); ?></span>
                                                                                </div>
                                                                                <a class="ititle-extra" href="javascript:void(0)"><i class="fa fa-star"></i></a>
                                                                        </div>
                                                                        <div class="ioption">
                                                                                <a class="iedit"><i class="fa fa-pencil"></i> Edit</a>
                                                                                <a class="idelete"><i class="fa fa-trash"></i></a>
                                                                        </div>
                                                                    </div>
                                                                    <!--<div class="desc-holder">
                                                                            <div class="counter-section">
                                                                                    <a href="javascript:void(0)"><i class="fa fa-comments"></i>2</a>
                                                                                    <a href="javascript:void(0)"><i class="fa fa-thumbs-up"></i>45</a>
                                                                            </div>
                                                                            <div class="comments-section">
                                                                                    <div class="img-area"><img src="<?= $gallery_img;?>" class="img-responsive" alt="user-photo"></div>
                                                                                    <div class="desc-area"><textarea placeholder="Type your comment"></textarea></div>
                                                                            </div>
                                                                    </div>-->
                                                                </div>
						   <?php
						   }
						   else
						   {
						   $eximgs = explode(',',$gallery_item['image'],-1);



						   ?>
						   <?php foreach ($eximgs as $eximg) {
								$picsize = '';
								$val = getimagesize('../web'.$eximg);
								$picsize .= $val[0] .'x'. $val[1] .', ';
								if($val[0] > $val[1]){$imgclass = 'himg';}else if($val[1] > $val[0]){$imgclass = 'vimg';}else{$imgclass = 'simg';}
							?>
								<div class="wcard">
                                                                    <div class="img-holder">
                                                                        <a href="<?= Yii::$app->getUrlManager()->getBaseUrl() ?><?= $eximg ?>" rel="prettyPhoto[gallery1]" class="listalbum-box"><img src="<?= Yii::$app->getUrlManager()->getBaseUrl() ?><?= $eximg ?>" alt=""></a>
                                                                        <div class="ititle">
                                                                                <div class="ititle-icon"><i class="fa fa-camera"></i></div>
                                                                                <div class="ititle-text">
                                                                                        Picture added Yesterday
                                                                                        <span>Photo by <?= ucfirst($galleryuser['fname']) . ' ' . ucfirst($galleryuser['lname']); ?></span>
                                                                                </div>
                                                                                <a class="ititle-extra" href="javascript:void(0)"><i class="fa fa-map-marker"></i></a>
                                                                        </div>
                                                                        <div class="ioption">
                                                                                <a class="iedit"><i class="fa fa-pencil"></i> Edit</a>
                                                                                <a class="idelete"><i class="fa fa-trash"></i></a>
                                                                        </div>
                                                                    </div>    
                                                                    <div class="desc-holder">
                                                                            <div class="counter-section">
                                                                                    <a href="javascript:void(0)"><i class="fa fa-comments"></i>2</a>
                                                                                    <a href="javascript:void(0)"><i class="fa fa-thumbs-up"></i>45</a>
                                                                            </div>
                                                                            <div class="comments-section">
                                                                                    <div class="img-area"><img src="<?= $gallery_img;?>" class="img-responsive" alt="user-photo"></div>
                                                                                    <div class="desc-area"><textarea placeholder="Type your comment"></textarea></div>
                                                                            </div>
                                                                    </div>
								</div>
						   <?php }?>
						   <?php
						   //$ctr++;
					   }
				   }
				} //foreach end
			   ?>
			</div>
			

			<!--<script>
			$(document).ready(function(){
		
				 $("body *").each(function(){
					
					if($(this).prop("rel")){
						
						var rel=$(this).prop("rel");
						
						if(rel.indexOf("prettyPhoto") != -1){
							//console.log($(this).prop("rel"));
							jq("area[rel^='prettyPhoto']").prettyPhoto();
							jq(".gallery a[rel^='prettyPhoto']").prettyPhoto({animation_speed:'normal',theme:'light_square',social_tools: ' '});
						}
						
					}
				 });

			});
			</script>

			<script src="http://localhost/travbudcode/frontend/web/assets/90fb5010/js/waterfall-light.js" type="text/javascript"></script>

			<script>

				$(function(){

					$('#box').waterfall({gridWidth : [0,500,800]});

				});
			</script>-->


		</div>

                <?php include('includes/userwall.php');
} 
       elseif($_POST['page'] == 'contributions'){?> <div class="col-lg-8 col-md-8 col-sm-8 secondcol data-section-about">
            <div class="tb-panel-box photos-pd-fix wall-right-section panel-shadow">
                <div class="boxlabel">Contributions
                </div>
                <span class="no-listcontent">Coming Soon</span>
            </div>
        </div>
			 

 <?php include('includes/userwall.php');
} elseif($_POST['page'] == 'saved'){
      
     //$savedposts = SavePost::find()->with('userDetail')->with('postData')->where(['user_id' => "$user_id",'is_saved' => '1'])->orderBy(['saved_date'=>SORT_DESC])->all();
     $savedposts = SavePost::find()->where(['user_id' => "$user_id",'is_saved' => '1'])->orderBy(['saved_date'=>SORT_DESC])->all();
     
     ?> 
       <div class="col-lg-8 col-md-8 col-sm-8 secondcol data-section-about">
				
                       <div class="tb-panel-box photos-pd-fix wall-right-section panel-shadow">
	 
		<?php //include('includes/leftmenus.php');?>
					    
		
				
				<div class="boxlabel">Saved Posts
                </div>
				
				<div class="bmcontent" id="bmcontent">
					<div class="notice">	
						<div id="suceess" class="form-successmsg">Saved List Updated Successfully.</div>
						<div id="fail" class="form-failuremsg">Oops..!! Something went wrong. Please try later.</div>
					</div>
					<ul class="listing-ul">
					<!--<li>
						<div class="saved-post">
							
							<div class="img-holder">
								<a href="#">
									<img src="profile/thumb_18219094demo.jpg" alt="" class="img-responsive">
									<span class="v-play"></span>
								</a>
							</div>
							<div class="desc-holder">
								<a href="javascript:void(0)" class="remove-link"><i class="fa fa-close"></i>Remove</a>
								<h4><a href="#">Post title goes here</a></h4>								
								<div class="desc">
									<p>Some info of post goes here</p>									
								</div>
								<div class="spost-info">
									<div class="btn-holder"><a href="#"><i class="fa fa-share"></i>Share</a></div>
									<div class="savedFrom">saved from <a href="javascript:void(0)">my post</a></div>
								</div>								
							</div>
						</div>
					</li>-->
                                    <?php 
                                    if(!empty($savedposts)){
                                    foreach($savedposts as $savedpost){
                                        //echo '<pre>';print_r($savedpost);
                                        $postid = $savedpost['post_id'];
                                        $userid = $savedpost['user_id'];
                                        $posttype = $savedpost['post_type'];
                                        $userinfo = LoginForm::find()->where(['_id' => $savedpost['postData']['post_user_id']])->one();
                                        $post_user_id = $userinfo['_id'];
                                        $userfname = $userinfo['fname'];
                                        $userlname = $userinfo['lname'];
                                        if($post_user_id != $userid)
                                        {
                                            $name = $userfname." ".$userlname."'s";
                                        }
                                        else
                                        {
                                            $name = 'your';
                                        }
                                        $save_value = $name.' post.';
                                        $postinfo = PostForm::find()->where(['_id' => $savedpost['post_id']])->one();
                                        $posttype = $postinfo['post_type'];
                                        if($posttype == 'image')
                                        {
                                                $eximgs = explode(',',$postinfo['image'],-1);
                                                $totalimgs = count($eximgs);
                                                $saveimageval = $totalimgs > 1 ? 's' : '';
                                                $savetitle = $totalimgs.' Photo'.$saveimageval;
                                        }
                                        else if($posttype == 'profilepic')
                                        {
                                                $savetitle = '1 Photo';
                                        }
                                        else if($posttype == 'link')
                                        {
                                                $savetitle = $postinfo['link_title'];
                                        }
                                        else if($posttype == 'text' || $posttype == 'text and image')
                                        {
                                                $savetitle = $postinfo['post_text'];
                                        }
                                        else
                                        {
                                                $savetitle = 'View Post';
                                        }
                                    ?>
				
					<li id="save_content_<?= $postid;?>">
						<div class="saved-post">
							
							<div class="img-holder">
								<?php
                                                                if($posttype == 'image' || $posttype == 'text and image' )
                                                                {
                                                                    $eximgs = explode(',',$postinfo['image'],-1);
                                                                    $display_image = substr($eximgs[0],1);
                                                                }
                                                                else
                                                                {
                                                                    $display_image = $this->getimage($post_user_id,'photo');
                                                                }
                                                                $picsize = '';
                                                                $val = getimagesize($display_image);
                                                                $picsize .= $val[0] .'x'. $val[1] .', ';
																$imgclass = "";
																$imgclassanc = "";
                                                                if($val[0] > $val[1]){$imgclass = 'himg';$imgclassanc = $imgclass.'-box';}
                                                                if($val[1] > $val[0]){$imgclass = 'vimg';$imgclassanc = $imgclass.'-box';}
								?>
                                                                <?php if($posttype == 'link'){ ?>
                                                                    <a href="<?php echo $postinfo['post_text']; ?>" class="<?= $imgclassanc?>">
                                                                <?php } else{ ?>
                                                                    <a href="<?php echo Url::to(['site/view-post', 'postid' => "$postid"]); ?>" class="<?= $imgclassanc?>">
                                                                <?php } ?>
                                                                <img src="<?= $display_image?>" alt="" class="img-responsive <?= $imgclass?>">
                                                                <?php if($posttype == 'link'){ ?>
                                                                    <span class="v-play"></span>
                                                                <?php }?>
                                                                </a>
							</div>
							<div class="desc-holder">
								<a href="javascript:void(0)" class="remove-link" onClick="save_post('<?php echo $postid?>','<?php echo $posttype?>')"><i class="fa fa-close"></i>Remove</a>
								<h4><a href="<?php /*echo Url::to(['site/view-post', 'postid' => "$postid"]); */ ?>javascript:void(0)"><?= $savetitle;?></a></h4>
								<div class="desc">
									<p>
                                                                            <?php if($posttype == 'link')
                                                                            {
                                                                                echo $postinfo['post_text'].' ·'.' Video';
                                                                            }
                                                                            else if($posttype == 'text and image')
                                                                            {
                                                                                    $eximgs = explode(',',$postinfo['image'],-1);
                                                                                    $totalimgs = count($eximgs);
                                                                                    $saveimageval = $totalimgs > 1 ? 's' : '';
                                                                                    $saveother = $totalimgs.' Photo'.$saveimageval;
                                                                                    echo $saveother.' · '.$saveother;
                                                                            }
                                                                            ?>
                                                                        </p>									
								</div>
								<div class="spost-info">
									<div class="btn-holder"><a href="javascript:void(0)" class="pshare"><i class="fa fa-share"></i>Share</a></div>
									<div class="savedFrom">Saved From <a href="<?php /*echo Url::to(['site/view-post', 'postid' => "$postid"]);*/ ?>javascript:void(0)"><?= $save_value ?></a></div>
								</div>								
							</div>
						</div>
						<div class="post-details" >
							<?php 
								$this->display_last_post($postid);
							?>
						</div>
					</li>
				
				<?php /*
				<ul class="setting-ul bookmarks" id="save_content_<?= $postid;?>">
					<li>
                        <?php $form = ActiveForm::begin(['id' => 'frmm-name','options'=>['onsubmit'=>'return false;',],]); ?> 
                           <div class="setting-group">							
							<div class="normal-mode">
								<div class="row">
									<div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">
                                                                            <label>
                                                                                <?php
                                                                                $display_image = $this->getimage($post_user_id,'photo');
                                                                                ?>
                                                                                <img src="<?= $display_image?>" alt="" class="img-responsive">
                                                                            </label>
									</div>
									<div class="col-lg-8 col-md-7 col-sm-7 col-xs-12">										
										<div class="info">																   				   								
                                                                                    <a href="<?php echo Url::to(['site/view-post', 'postid' => "$postid"]); ?>" target="_blank"><?= $save_value ?></a>
                                                                                        
										</div>
									</div>
									<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">										
										<div class="pull-right linkholder">
                                                                                    <!--<a href="javascript:void(0)" onclick='window.open("<?php echo Yii::$app->urlManager->createUrl(['site/shareoption']);?>&postid=<?php echo $postid?>","MyNewWindow")'>Share</a>
                                                                                     | --><a href="javascript:void(0)" onClick="save_post('<?php echo $postid?>','<?php echo $posttype?>')">Remove</a>
										</div>
									</div>
									<div class="clear"></div>
								</div>	
							</div>	
						</div>
                        <?php ActiveForm::end() ?>
					</li>
                                       
				</ul>
				*/ ?>									
                                    <?php } 
									?>
									</ul>									
									<?php } else { ?>
                                    <span class="no-listcontent">No Posts has been Saved by you yet.</span>
                                        <?php } ?>
							<script>
							/*
								$(".tarrow .alink").on("click", function (e) {
									closeAllDrawers(e);
									closeSearchSection(e);
									showDrawer(this, e);
								});
							*/
								function closeAllPost(pid){
									
									$(".listing-ul > li").each(function(){
										if($(this).attr("id")!=pid){
											$(this).find(".post-details").slideUp();
										}
									});
								}
								$(document).ready(function(){
		
									$(".saved-post a").click(function(){
										
										if(!$(this).attr("class")){
											//alert($(this).attr("class"));
											closeAllPost($(this).parents("li").attr("id"));
											var pdetail=$(this).parents("li").find(".post-details");
											
											if(pdetail.css("display")=="none")
												pdetail.slideDown();
											else
												pdetail.slideUp();
										}
									});
								});
								
							</script>
                            </div>
                           </div>
			 </div>
                     
 <?php  include('includes/userwall.php');}
 elseif($_POST['page'] == 'index'){
     
            $suserid = $session->get('user_id'); //(string) $result['_id'];
            $wall_user_id = $guserid = $_POST['uid'];
            $model_pv = new ProfileVisitor();
            $model2 = new \frontend\models\Personalinfo();
            $count_pv = ProfileVisitor::getAllVisitors($guserid);
            $friends_city =Friend::getFriendsCity($wall_user_id);
            $result_setting = UserSetting::find()->where(['user_id' => "$wall_user_id"])->one();
            $email_access = $result_setting['email_access'];
            $mobile_access = $result_setting['mobile_access'];
            $birth_date_access = $result_setting['birth_date_access'];
            $locations = "'Mumbai','surat','kalkatta'";
             $user_data =  Personalinfo :: getPersonalInfo($_POST['uid']);
           
            $user_friends =  Friend::getuserFriends($_POST['uid']);
           
            $user_basicinfo = LoginForm::find()->where(['_id' => $_POST['uid']])->one();
      
            $posts = PostForm::getUserPost($_POST['uid']);

            $savedposts = SavePost::find()->with('userDetail')->with('postData')->where(['user_id' => "$user_id",'is_saved' => '1'])->orderBy(['saved_date'=>SORT_DESC])->all();

            include('includes/userwall-index.php');exit;
            
            ?> 
            <div class="col-lg-8 col-md-8 col-sm-8 secondcol data-section-about">
        
				<?php
				
				if(!(empty($user_data['about']) && empty($user_data['interests']) && empty($user_data['language']) && empty($user_data['occupation']) && empty($user_data['birth_date']))){?> 
				 <div class="white-holderbox user_basicinfo panel-shadow">
					<ul class="uwall-detail">
						<?php if(!empty($user_data['about'])){?> 
							<li>
								
			<?php $form = ActiveForm::begin(['id' => 'frm-about','options'=>['onsubmit'=>'return false;',],]); ?>  
								
									<div class="normal-mode">
										<div class="row">
											<div class="col-lg-1 col-md-3 col-sm-3 u-title">About</div>
                                                                                        <div class="col-lg-10 col-md-8 col-sm-8 u-data"><p id="about"><?php echo $user_data['about'];?></p></div>
											<div class="col-lg-1 col-md-1 col-sm-1 col-xs-12 u-btn">										
												<div class="pull-right  linkholder">
												<?php if($wall_user_id == $user_id){ echo '
													<a href="javascript:void(0)" onClick="open_edit(this)"><i class="fa fa-pencil"></i></a>';
												}?>
												</div>
											</div>
										</div>	
									</div>	
									<div class="edit-mode">
										<div class="row">
											<div class="col-lg-1 col-md-3 col-sm-3 col-xs-12 u-title">
												About
											</div>
											<div class="col-lg-8 col-md-6 col-sm-6 col-xs-12 u-data">
												
	 <?= $form->field($model2,'about')->textInput(array('value'=>$user_data['about']))->label(false)?>											
											</div>									
											<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 pull-right u-btn">
												<div class="form-group pull-right">						
													<div class="pull-right fb-btnholder nbm">		
														<a class="btn btn-primary btn-sm" onClick="close_edit(this),about()">Save</a>
														<a class="btn btn-primary btn-sm" onClick="close_edit(this)">Cancel</a>												
													</div>										
												</div>										
											</div>
										</div>	
									</div>
								
								  <?php ActiveForm::end() ?>
								  
							</li>	
							
						<?php } ?>
						<?php if(!empty($user_data['interests'])){?>
							<li>
						<?php $form = ActiveForm::begin(['id' => 'frm-interests','options'=>['onsubmit'=>'return false;',],]); ?> 
								<div class="normal-mode">
									<div class="row">
										<div class="col-lg-1 col-md-3 col-sm-3 u-title">Interest</div>
                                                                                <div class="col-lg-10 col-md-8 col-sm-8 u-data"><p id="interests"><?php echo $user_data['interests'];?></p></div>
										<div class="col-lg-1 col-md-1 col-sm-1 col-xs-12 u-btn">										
											<div class="pull-right  linkholder">
											<?php if($wall_user_id == $user_id){ echo '
												<a href="javascript:void(0)" onClick="open_edit(this)"><i class="fa fa-pencil"></i></a>';
											}?>
												
											</div>
										</div>
									</div>	
								</div>	
								<div class="edit-mode">
									<div class="row">
										<div class="col-lg-1 col-md-3 col-sm-3 col-xs-12 u-title">
											Interest
										</div>
										<div class="col-lg-8 col-md-6 col-sm-6 col-xs-12 u-data">
<?= $form->field($model2,'interests')->dropDownList(ArrayHelper::map(Interests::find()->all(), 'name', 'name'),['class'=>'js-example-theme-multiple','style'=>'width: 100%','multiple'=>'multiple'])->label(false)?> 										
										</div>									
										<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 pull-right u-btn">
											<div class="form-group pull-right nbm">						
												<div class="pull-right fb-btnholder nbm">		
													<a class="btn btn-primary btn-sm" onClick="close_edit(this),interests()">Save</a>
													<a class="btn btn-primary btn-sm" onClick="close_edit(this)">Cancel</a>												
												</div>										
											</div>										
										</div>
									</div>	
								</div>	
							 <?php ActiveForm::end() ?>
							</li>
							
											<?php } ?>
						  <?php if(!empty($user_data['language'])){?>
							<li>
							 <?php $form = ActiveForm::begin(['id' => 'frm-language','options'=>['onsubmit'=>'return false;',],]); ?> 
								<div class="normal-mode">
									<div class="row">
										<div class="col-lg-1 col-md-3 col-sm-3 u-title">Language</div>
										<div class="col-lg-10 col-md-8 col-sm-8 u-data"><p id="language"><?php echo $user_data['language'];?></p></div>
										<div class="col-lg-1 col-md-1 col-sm-1 col-xs-12 u-btn">										
											<div class="pull-right  linkholder">
											<?php if($wall_user_id == $user_id){ echo '
												<a href="javascript:void(0)" onClick="open_edit(this)"><i class="fa fa-pencil"></i></a>';
											}?>
												
											</div>
										</div>
									</div>	
								</div>	
								<div class="edit-mode">
									<div class="row">
										<div class="col-lg-1 col-md-3 col-sm-3 col-xs-12 u-title">
											Language
										</div>
										<div class="col-lg-8 col-md-6 col-sm-6 col-xs-12 u-data">
<?= $form->field($model2,'language')->dropDownList(ArrayHelper::map(Language::find()->all(), 'name', 'name'),['class'=>'js-example-theme-multiple','style'=>'width: 100%','multiple'=>'multiple'])->label(false)?>   
										</div>									
										<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 pull-right u-btn">
											<div class="form-group pull-right nbm">						
												<div class="pull-right fb-btnholder nbm">		
													<a class="btn btn-primary btn-sm" onClick="close_edit(this),language()">Save</a>
													<a class="btn btn-primary btn-sm" onClick="close_edit(this)">Cancel</a>												
												</div>										
											</div>										
										</div>
									</div>	
								</div>
								<?php ActiveForm::end() ?>
							</li>
											 
						  <?php } ?>
						  
						   <?php if(!empty($user_data['occupation'])){?>
							<li>
							 <?php $form = ActiveForm::begin(['id' => 'frm-occupation','options'=>['onsubmit'=>'return false;',],]); ?> 
								<div class="normal-mode">
									<div class="row">
										<div class="col-lg-1 col-md-3 col-sm-3 u-title">Occupation</div>
                                                                                <div class="col-lg-10 col-md-8 col-sm-8 u-data"><p id="occupation"><?php echo $user_data['occupation'];?></p></div>
										<div class="col-lg-1 col-md-1 col-sm-1 col-xs-12 u-btn">										
											<div class="pull-right  linkholder">
											<?php if($wall_user_id == $user_id){ echo '
												<a href="javascript:void(0)" onClick="open_edit(this)"><i class="fa fa-pencil"></i></a>';
											}?>
												
											</div>
										</div>
									</div>	
								</div>	
								<div class="edit-mode">
									<div class="row">
										<div class="col-lg-1 col-md-3 col-sm-3 col-xs-12 u-title">
											Occupation
										</div>
										<div class="col-lg-8 col-md-6 col-sm-6 col-xs-12  u-data">
		<?= $form->field($model2,'occupation')->dropDownList(ArrayHelper::map(Occupation::find()->all(), 'name', 'name'),['class'=>'js-example-theme-multiple','style'=>'width: 100%','multiple'=>'multiple'])->label(false)?>  									
										</div>									
										<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 pull-right u-btn">
											<div class="form-group pull-right nbm">						
												<div class="pull-right fb-btnholder nbm">		
													<a class="btn btn-primary btn-sm" onClick="close_edit(this),occupation()">Save</a>
													<a class="btn btn-primary btn-sm" onClick="close_edit(this)">Cancel</a>												
												</div>										
											</div>										
										</div>
									</div>	
								</div>	
			<?php ActiveForm::end() ?>					
							</li>			
						  <?php } ?>
										 
								  
						  <?php if(($wall_user_id == $user_id) && !empty($user_basicinfo['birth_date'])){?>
							<li>
								<div class="normal-mode">
									<div class="row">
										<div class="col-lg-1 col-md-3 col-sm-3 u-title">Birthdate</div>
										<div class="col-lg-10 col-md-8 col-sm-8 u-data"><p  id="birth_date"><?php echo 'Born in '.$user_basicinfo['birth_date'];?></p></div>
										<div class="col-lg-1 col-md-1 col-sm-1 col-xs-12 u-btn">										
											<div class="pull-right  linkholder">
											<?php if($wall_user_id == $user_id){ echo '
												<a href="javascript:void(0)" onClick="open_edit(this)"><i class="fa fa-pencil"></i></a>';
											}?>
												
											</div>
										</div>
									</div>	
								</div>	
								<div class="edit-mode">
									<div class="row">
										<div class="col-lg-1 col-md-3 col-sm-3 col-xs-12 u-title">
											Birthdate
										</div>
										<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 u-data">
											<div class='input-group date bdate' id='datetimepicker2'>									
												<input type="text" id="popupDatepicker" class="form-control" name="birth_date" value="<?=$user_basicinfo['birth_date']?>" placeholder="Birthdate" onkeydown="return false;">
												<span class="input-group-addon"><i class="glyphicon glyphicon-calendar " style="color:#0071BD;"></i></span>
											</div>
										</div>									
										<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 pull-right u-btn">
											<div class="form-group pull-right nbm">						
												<div class="pull-right fb-btnholder nbm">		
													<a class="btn btn-primary btn-sm" onClick="close_edit(this),birth_date()">Save</a>
													<a class="btn btn-primary btn-sm" onClick="close_edit(this)">Cancel</a>												
												</div>										
											</div>										
										</div>
									</div>	
								</div>	
							</li>
								
						  <?php } ?>
						  
						  <?php 
						  if(!empty($user_basicinfo['birth_date']) && $birth_date_access=='Public' && ($wall_user_id != $user_id)){?> 
							<li>
								<div class="normal-mode">
									<div class="row">
										<div class="col-lg-1 col-md-3 col-sm-3 u-title">Birthdate</div>
										<div class="col-lg-10 col-md-8 col-sm-8 u-data"><p  id="birth_date"><?php echo 'Born in '.substr($user_basicinfo['birth_date'],6,9);?></p></div>
										<div class="col-lg-1 col-md-1 col-sm-1 col-xs-12 u-btn">										
											<div class="pull-right  linkholder">
											<?php if($wall_user_id == $user_id){ echo '
												<a href="javascript:void(0)" onClick="open_edit(this)"><i class="fa fa-pencil"></i></a>';
											}?>
												
											</div>
										</div>
									</div>	
								</div>	
								<div class="edit-mode">
									<div class="row">
										<div class="col-lg-1 col-md-3 col-sm-3 col-xs-12 u-title">
											Birthdate
										</div>
										<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 u-data">
											<div class='input-group date bdate' id='datetimepicker2'>									
												<input type="text" id="popupDatepicker" class="form-control" name="birth_date" value="<?=$user_basicinfo['birth_date']?>" placeholder="Birthdate" onkeydown="return false;">
												<span class="input-group-addon"><i class="glyphicon glyphicon-calendar " style="color:#0071BD;"></i></span>
											</div>
										</div>									
										<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 pull-right u-btn">
											<div class="form-group pull-right nbm">						
												<div class="pull-right fb-btnholder nbm">		
													<a class="btn btn-primary btn-sm" onClick="close_edit(this),birth_date()">Save</a>
													<a class="btn btn-primary btn-sm" onClick="close_edit(this)">Cancel</a>												
												</div>										
											</div>										
										</div>
									</div>	
								</div>	
							</li>
								<!--
								<div class="row">
									<div class="col-lg-1 col-md-8 col-sm-2 u-title">Birthdate</div>
									<div class="col-lg-11 col-md-4 col-sm-10 u-data"><p><?php echo 'Born in '.substr($user_basicinfo['birth_date'],6,9);?></p></div>
								</div>
								-->
						  <?php } ?>
					  
					</ul>
				</div>
				<?php } ?>
				   <div class="white-holderbox user_friends panel-shadow">
					  
						   
					   <div class="boxlabel"><a href="javascript:void(0);" onclick="load_data('friends','<?php echo $wall_user_id;?>')">Friends</a></div>
					   <div class="clear"></div>
					   
					    <ul class="nav nav-tabs">
						  <li class="active"><a data-toggle="tab" href="#umap">Map</a></li>
						  <li><a data-toggle="tab" href="#ulist">List</a></li>
						</ul>

						<div class="tab-content">
							<?php /*
							<div class="activity">
								<h6>Friends Activities</h6>							
								<div class="fsteps">
									<div class="row">
									<div class="col20per">
											<span class="tmute">
                                                                                     <?php echo $count_pv;?></span>
											<p>Reviews</p>
										</div>
										<div class="col20per">
											<span class="tmute">12145</span>
											<p>Photos</p>
										</div>
										<div class="col20per">
											<span class="tmute">23</span>
											<p>Blogs</p>
										</div>
										<div class="col20per">
											<span class="tmute">75</span>
											<p>Videos</p>
										</div>
										
									</div>
								</div>
						   </div>
							*/ ?>
						  <div id="umap" class="tab-pane fade in active">							
							<div class="map_canvas" id="map_canvas"></div>
						  </div>
						  <div id="ulist" class="tab-pane fade">														
							<div class="wall-list">
                                                            <?php if(count($user_friends) > 0){ ?>
								<ul class="friendlist">
									<?php foreach($user_friends as $user_friend){
                                                                            $lmodel = new \frontend\models\LoginForm();
                                                                            $friendinfo = LoginForm::find()->where(['_id' => $user_friend['from_id']])->one();
                                                                            $fmodel = new \frontend\models\Friend();
                                                                            $mutualcount = Friend::mutualfriendcount($friendinfo['_id']);
                                                                            $countposts = count(PostForm::getUserPost($user_friend['from_id']));
                                                                        ?>
                                                                            <li>
										<div class="friend-list">
											<div class="imgholder">
                                                                                            <?php
                                                                                            $friend_img = $this->getimage($friendinfo['_id'],'photo');
                                                                                            ?>
												<img alt="user-photo" class="img-responsive" src="<?= $friend_img?>">
											</div>										
											<div class="descholder">
												<a class="profilelink no-ul" href="<?php $id =  $friendinfo['_id']; echo Url::to(['userwall/index', 'id' => "$id"]); ?>"><?php echo $friendinfo['fname'].' '.$friendinfo['lname']?></a>											
												<span class="online-status"><i class="fa fa-check-circle"></i></span>
												<div class="clear"></div>
												<?php if($countposts > 0){?>
                                                                                                    <div class="user-post"><?= $countposts?> Posts</div>
                                                                                                <?php }?>
											</div>
										</div>
									</li>
                                                                        <?php }?>
								</ul>
                                                            <?php } else {
                                                                            echo '<span class="no-listcontent">No Friends</span>';
                                                                        } ?>
							</div>
						  </div>
						</div>
					   
				   </div>
				 
				  <div class="white-holderbox user_photos panel-shadow">
					
					   <div class="boxlabel"><a href="javascript:void(0);" onclick="load_data('photos','<?php echo $wall_user_id;?>')">Photos</a></div>
					   <div class="clear"></div>
					 
						<div class="photos">

							<?php 
							
									if(count($posts)>0){
											$ctr = 1;
											?>
											<div class="albums">
													<?php 
													foreach($posts as $post){
															if(isset($post['image']) && !empty($post['image'])){
															$eximgs = explode(',',$post['image'],-1);
															
															//if(!empty($post['image']) && empty($post['post_text'])) {
															//if(!empty($post['image']) && empty($post['post_text']) && $ctr < 14) {
															?>
															<?php foreach ($eximgs as $eximg) {
																
																if($ctr<9){																		
																	
																$picsize = '';
																$val = getimagesize('../web'.$eximg);
																$picsize .= $val[0] .'x'. $val[1] .', ';
																if($val[0] > $val[1]){$imgclass = 'himg';}else if($val[1] > $val[0]){$imgclass = 'vimg';}else{$imgclass = 'simg';}
															?>
															<div class="album-col">
															<div class="album-holder">
																<div class="album-box">
																	<a href="javascript:void(0)" class="listalbum-box <?= $imgclass?>-box"><img src="<?= Yii::$app->getUrlManager()->getBaseUrl() ?><?= $eximg ?>" alt=""></a>
																</div>
															</div>
															</div>
															<?php } $ctr++; }?>
															<?php
															
															}
													}
													?>
											</div>
											<?php
									}
							?>
						</div>

				   </div>
					
					<div class="user_destination white-holderbox panel-shadow">
					
					   <div class="boxlabel"><a href="javascript:void(0);" onclick="load_data('destinations','<?php echo $wall_user_id;?>')">Destination</a></div>
					   <div class="clear"></div>
					   
					   <ul class="nav nav-tabs">
						  <li class="active"><a data-toggle="tab" href="#dmap">Map</a></li>
						  <li><a data-toggle="tab" href="#dlist">Places</a></li>
						</ul>
						<div class="tab-content">
							<div class="destination-info">
								<div class="row">
									<div class="col-lg-6 col-sm-12">
										<div class="user-desinfo">
											<ul>
												<li><p><a href="javascript:void(0);">User Name</a> is currently in Chili</p></li>
												<li><p>has been to <b>32</b> countries</p></li>
												<li><p>has <b>10</b> travel plans</p></li>									
											</ul>
										</div>
									</div>
									<div class="col-lg-6 col-sm-12">
										<div class="infosum-box">
											<h5>70 of your friends have been to</h5>
											<div class="row">
												<div class="col-lg-4 col-md-4 col-sm-4">
													<span class="tmute">5</span>
													<p>Countries</p>
												</div>
												<div class="col-lg-4 col-md-4 col-sm-4">
													<span class="tmute">37</span>
													<p>States</p>
												</div>
												<div class="col-lg-4 col-md-4 col-sm-4">
													<span class="tmute">808</span>
													<p>Cities</p>
												</div>
											</div>
										</div>
									</div>
								</div>								
						   </div>
						  <div id="dmap" class="tab-pane fade in active">							
							<div class="map_canvas" id="map_container"></div>
                                                        
						  </div>
						  <div id="dlist" class="tab-pane fade">														
							<div class="wall-list">
								<ul>
									<li>
										<div class="ulist-holder">
											<div class="imgholder">
												<img class="img-responsive" src="<?= Yii::$app->getUrlManager()->getBaseUrl() ?>/profile/Male.jpg">
											</div>										
											<div class="descholder">
												<a href="javascript:void(0);">Place Name</a>																							
												<div class="clear"></div>
												<div class="user-address"> <span class="title">Country</span><div class="clear"></div><span>Place details goes here</span></div>											
											</div>
										</div>
									</li>
									<li>
										<div class="ulist-holder">
											<div class="imgholder">
												<img class="img-responsive" src="<?= Yii::$app->getUrlManager()->getBaseUrl() ?>/profile/Male.jpg">
											</div>										
											<div class="descholder">
												<a href="javascript:void(0);">Place Name</a>																							
												<div class="clear"></div>
												<div class="user-address"> <span class="title">Country</span><div class="clear"></div><span>Place details goes here</span></div>											
											</div>
										</div>
									</li>
									<li>
										<div class="ulist-holder">
											<div class="imgholder">
												<img class="img-responsive" src="<?= Yii::$app->getUrlManager()->getBaseUrl() ?>/profile/Male.jpg">
											</div>										
											<div class="descholder">
												<a href="javascript:void(0);">Place Name</a>																							
												<div class="clear"></div>
												<div class="user-address"> <span class="title">Country</span><div class="clear"></div><span>Place details goes here</span></div>											
											</div>
										</div>
									</li>
									<li>
										<div class="ulist-holder">
											<div class="imgholder">
												<img class="img-responsive" src="<?= Yii::$app->getUrlManager()->getBaseUrl() ?>/profile/Male.jpg">
											</div>										
											<div class="descholder">
												<a href="javascript:void(0);">Place Name</a>																							
												<div class="clear"></div>
												<div class="user-address"> <span class="title">Country</span><div class="clear"></div><span>Place details goes here</span></div>											
											</div>
										</div>
									</li>
									<li>
										<div class="ulist-holder">
											<div class="imgholder">
												<img class="img-responsive" src="<?= Yii::$app->getUrlManager()->getBaseUrl() ?>/profile/Male.jpg">
											</div>										
											<div class="descholder">
												<a href="javascript:void(0);">Place Name</a>																							
												<div class="clear"></div>
												<div class="user-address"> <span class="title">Country</span><div class="clear"></div><span>Place details goes here</span></div>											
											</div>
										</div>
									</li>
									<li>
										<div class="ulist-holder">
											<div class="imgholder">
												<img class="img-responsive" src="<?= Yii::$app->getUrlManager()->getBaseUrl() ?>/profile/Male.jpg">
											</div>										
											<div class="descholder">
												<a href="javascript:void(0);">Place Name</a>																							
												<div class="clear"></div>
												<div class="user-address"> <span class="title">Country</span><div class="clear"></div><span>Place details goes here</span></div>											
											</div>
										</div>
									</li>
								</ul>
							  </div>
						  </div>
						</div>					   
				   </div>
				   
					<div class="user_likes white-holderbox panel-shadow">
					
					   <div class="boxlabel">Likes</div>
					   <div class="clear"></div>
					 
						<div class="likes">
							<?php 
								if(count($likes)>0){
									$lctr = 0;
							?>
								<div class="likes-row">
									<?php 
										foreach($likes as $like){
											if($lctr < 14) {
									?>
											<div class="likes-col">
												<div class="user-likebox">
													<a href="javascript:void(0);">
														<span class="like-img">
															<img height="200" width="200" src="<?= Yii::$app->getUrlManager()->getBaseUrl() ?><?= $like['post']['image'] ?>" alt=""/>
														</span>
														<span class="caption"><?php if(!empty($like['post']['post_text'])){ echo $like['post']['post_text'];}else {echo 'Like Caption';}?></span>
													</a>
												</div>
											</div>
									<?php
											$lctr++;
											}
										}
									?>
								</div>
							<?php
								}
							?>
							
						</div>
				   </div>
				 
			 </div> 
                          



                               <div class="friends">



               <div class="fb-innerpage whitebg">

                          ?>
                            <div class="wall-list">
								<ul>
									<?php 
                                                                        if(count($user_friends)>0)
                                                                        {
                                                                        foreach($user_friends as $user_friend){
                                                                            $lmodel = new \frontend\models\LoginForm();
                                                                            $friendinfo = LoginForm::find()->where(['_id' => $user_friend['from_id']])->one();
                                                                            $fmodel = new \frontend\models\Friend();
                                                                            $mutualcount = Friend::mutualfriendcount($friendinfo['_id']);
                                                                        ?>
                                                                            <li>
										<div class="ulist-holder">
                                                                    <div class="imgholder">
                                                                            
                                                                             <?php
                                            $friendimg = $this->getimage($friendinfo['_id'],'photo');
                                            ?>
                                            <img src="<?= $friendimg?>" class="img-responsive" />
                                                                    </div>										
											<div class="descholder">
												<a class="profilelink no-ul" href="<?php $id =  $friendinfo['_id']; echo Url::to(['userwall/index', 'id' => "$id"]); ?>"><?php echo $friendinfo['fname'].' '.$friendinfo['lname']?></a>											
												<span class="online-status"><i class="fa fa-check-circle"></i></span>
												<div class="clear"></div>
												<?php if(isset($friendinfo['city']) && !empty($friendinfo['city'])){?>
                                                                                                    <div class="user-address"> <span class="title">Currently in : </span><div class="clear"></div><span><?php echo $friendinfo['city'].','.$friendinfo['country']?></span></div>
                                                                                                <?php }?>
											</div>
										</div>
									</li>
                        <?php }}else{?><div class="no-listcontent">User has no friend </div><?php }?>
								</ul>
							</div>
                     

                       <?php //include('includes/leftmenus.php');?>
                       <div class="fbdetail-holder">

                               <div class="fb-formholder">		

                                       <h4>Saved Posts</h4>

                                       <div class="notice">	
                                           <div id="suceess" class="form-successmsg">Saved List Updated Successfully.</div>
                                           <div id="fail" class="form-failuremsg">Oops..!! Something went wrong. Please try later.</div>
                                       </div>

                                       <div class="bmcontent" id="bmcontent">
                                           <?php 
                                           if(!empty($savedposts)){
                                           foreach($savedposts as $savedpost){
                                              $postid = $savedpost['post_id'];
                                               $userid = $savedpost['user_id'];
                                               $posttype = $savedpost['post_type'];
                                               $post_user_id = $savedpost['userDetail']['_id'];
                                               $userfname = $savedpost['userDetail']['fname'];
                                               $userlname = $savedpost['userDetail']['lname'];
                                               $name = $userfname." ".$userlname;
                                               $userphoto = $savedpost['userDetail']['photo'];
                                               $save_value = 'Saved from '.$name.'\'s post';
                                           ?>
                                       <ul class="setting-ul bookmarks">
                                               <li>
                               <?php $form = ActiveForm::begin(['id' => 'frmm-name','options'=>['onsubmit'=>'return false;',],]); ?> 
                                                       <div class="setting-group">							
                                                               <div class="normal-mode">
                                                                       <div class="row">
                                                                               <div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">
                                                                                       <label><img alt="user-photo" class="img-responsive" src="<?php if(isset($savedpost['userDetail']['fb_id']) && !empty($savedpost['userDetail']['photo'])){echo $savedpost['userDetail']['photo'];
        }
       else if(isset($savedpost['userDetail']['photo']) && !empty($savedpost['userDetail']['photo'])){
       echo 'profile/'.$savedpost['userDetail']['photo'];
       }
       else{

       echo 'profile/'.$savedpost['userDetail']['gender'].'.jpg';
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

                                           <?php } }else {?>
                                           <span class="no-listcontent">No Saved Posts</span>
                                               <?php } ?>
                                   </div>
                               </div>
                       </div>
               </div>
                                </div>	 
    </div>
        <?php include('includes/userwall.php'); } /*
        elseif($_POST['page'] == 'index'){

                   $suserid = $session->get('user_id'); //(string) $result['_id'];
                   $wall_user_id = $guserid = $_POST['uid'];
                   $model_pv = new ProfileVisitor();
                   $model2 = new \frontend\models\Personalinfo();
                   $count_pv = ProfileVisitor::getAllVisitors($guserid);
                   $friends_city =Friend::getFriendsCity($wall_user_id);
                   $result_setting = UserSetting::find()->where(['user_id' => "$wall_user_id"])->one();
                   $email_access = $result_setting['email_access'];
                   $mobile_access = $result_setting['mobile_access'];
                   $birth_date_access = $result_setting['birth_date_access'];
                   $locations = "'Mumbai','surat','kalkatta'";
                    $user_data =  Personalinfo :: getPersonalInfo($_POST['uid']);

                   $user_friends =  Friend::getuserFriends($_POST['uid']);

                   $user_basicinfo = LoginForm::find()->where(['_id' => $_POST['uid']])->one();

                   $posts = PostForm::getUserPost($_POST['uid']);

                   $photos =  PostForm::getUserPostPhotos($_POST['uid']);

                   $likes = Like::getUserPostLike($_POST['uid']);
            ?> 

                                <div class="col-lg-8 col-md-8 col-sm-8 secondcol data-section-about">
                                       <?php

                                       if(!(empty($user_data['about']) && empty($user_data['interests']) && empty($user_data['language']) && empty($user_data['occupation']) && empty($user_data['birth_date']))){?> 
                                        <div class="white-holderbox user_basicinfo panel-shadow">
                                               <ul class="uwall-detail">
                                                       <?php if(!empty($user_data['about'])){?> 
                                                               <li>

                               <?php $form = ActiveForm::begin(['id' => 'frm-about','options'=>['onsubmit'=>'return false;',],]); ?>  

                                                                               <div class="normal-mode">
                                                                                       <div class="row">
                                                                                               <div class="col-lg-1 col-md-3 col-sm-3 u-title">About</div>
                                                                                               <div class="col-lg-10 col-md-8 col-sm-8 u-data"><p id="about"><?php echo $user_data['about'];?></p></div>
                                                                                               <div class="col-lg-1 col-md-1 col-sm-1 col-xs-12 u-btn">										
                                                                                                       <div class="pull-right  linkholder">
                                                                                                       <?php if($wall_user_id == $user_id){ echo '
                                                                                                               <a href="javascript:void(0)" onClick="open_edit(this)"><i class="fa fa-pencil"></i></a>';
                                                                                                       }?>
                                                                                                       </div>
                                                                                               </div>
                                                                                       </div>	
                                                                               </div>	
                                                                               <div class="edit-mode">
                                                                                       <div class="row">
                                                                                               <div class="col-lg-1 col-md-3 col-sm-3 col-xs-12 u-title">
                                                                                                       About
                                                                                               </div>
                                                                                               <div class="col-lg-8 col-md-6 col-sm-6 col-xs-12 u-data">

                <?= $form->field($model2,'about')->textInput(array('value'=>$user_data['about']))->label(false)?>											
                                                                                               </div>									
                                                                                               <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 pull-right u-btn">
                                                                                                       <div class="form-group pull-right">						
                                                                                                               <div class="pull-right fb-btnholder nbm">		
                                                                                                                       <a class="btn btn-primary btn-sm" onClick="close_edit(this),about()">Save</a>
                                                                                                                       <a class="btn btn-primary btn-sm" onClick="close_edit(this)">Cancel</a>												
                                                                                                               </div>										
                                                                                                       </div>										
                                                                                               </div>
                                                                                       </div>	
                                                                               </div>

                                                                         <?php ActiveForm::end() ?>

                                                               </li>	

                                                       <?php } ?>
                                                       <?php if(!empty($user_data['interests'])){?>
                                                               <li>
                                                       <?php $form = ActiveForm::begin(['id' => 'frm-interests','options'=>['onsubmit'=>'return false;',],]); ?> 
                                                                       <div class="normal-mode">
                                                                               <div class="row">
                                                                                       <div class="col-lg-1 col-md-3 col-sm-3 u-title">Interest</div>
                                                                                       <div class="col-lg-10 col-md-8 col-sm-8 u-data"><p id="interests"><?php echo $user_data['interests'];?></p></div>
                                                                                       <div class="col-lg-1 col-md-1 col-sm-1 col-xs-12 u-btn">										
                                                                                               <div class="pull-right  linkholder">
                                                                                               <?php if($wall_user_id == $user_id){ echo '
                                                                                                       <a href="javascript:void(0)" onClick="open_edit(this)"><i class="fa fa-pencil"></i></a>';
                                                                                               }?>

                                                                                               </div>
                                                                                       </div>
                                                                               </div>	
                                                                       </div>	
                                                                       <div class="edit-mode">
                                                                               <div class="row">
                                                                                       <div class="col-lg-1 col-md-3 col-sm-3 col-xs-12 u-title">
                                                                                               Interest
                                                                                       </div>
                                                                                       <div class="col-lg-8 col-md-6 col-sm-6 col-xs-12 u-data">
       <?= $form->field($model2,'interests')->dropDownList(ArrayHelper::map(Interests::find()->all(), 'name', 'name'),['class'=>'js-example-theme-multiple','style'=>'width: 100%','multiple'=>'multiple'])->label(false)?> 										
                                                                                       </div>									
                                                                                       <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 pull-right u-btn">
                                                                                               <div class="form-group pull-right nbm">						
                                                                                                       <div class="pull-right fb-btnholder nbm">		
                                                                                                               <a class="btn btn-primary btn-sm" onClick="close_edit(this),interests()">Save</a>
                                                                                                               <a class="btn btn-primary btn-sm" onClick="close_edit(this)">Cancel</a>												
                                                                                                       </div>										
                                                                                               </div>										
                                                                                       </div>
                                                                               </div>	
                                                                       </div>	
                                                                <?php ActiveForm::end() ?>
                                                               </li>

                                                                                               <?php } ?>
                                                         <?php if(!empty($user_data['language'])){?>
                                                               <li>
                                                                <?php $form = ActiveForm::begin(['id' => 'frm-language','options'=>['onsubmit'=>'return false;',],]); ?> 
                                                                       <div class="normal-mode">
                                                                               <div class="row">
                                                                                       <div class="col-lg-1 col-md-3 col-sm-3 u-title">Language</div>
                                                                                       <div class="col-lg-10 col-md-8 col-sm-8 u-data"><p id="language"><?php echo $user_data['language'];?></p></div>
                                                                                       <div class="col-lg-1 col-md-1 col-sm-1 col-xs-12 u-btn">										
                                                                                               <div class="pull-right  linkholder">
                                                                                               <?php if($wall_user_id == $user_id){ echo '
                                                                                                       <a href="javascript:void(0)" onClick="open_edit(this)"><i class="fa fa-pencil"></i></a>';
                                                                                               }?>

                                                                                               </div>
                                                                                       </div>
                                                                               </div>	
                                                                       </div>	
                                                                       <div class="edit-mode">
                                                                               <div class="row">
                                                                                       <div class="col-lg-1 col-md-3 col-sm-3 col-xs-12 u-title">
                                                                                               Language
                                                                                       </div>
                                                                                       <div class="col-lg-8 col-md-6 col-sm-6 col-xs-12 u-data">
       <?= $form->field($model2,'language')->dropDownList(ArrayHelper::map(Language::find()->all(), 'name', 'name'),['class'=>'js-example-theme-multiple','style'=>'width: 100%','multiple'=>'multiple'])->label(false)?>   
                                                                                       </div>									
                                                                                       <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 pull-right u-btn">
                                                                                               <div class="form-group pull-right nbm">						
                                                                                                       <div class="pull-right fb-btnholder nbm">		
                                                                                                               <a class="btn btn-primary btn-sm" onClick="close_edit(this),language()">Save</a>
                                                                                                               <a class="btn btn-primary btn-sm" onClick="close_edit(this)">Cancel</a>												
                                                                                                       </div>										
                                                                                               </div>										
                                                                                       </div>
                                                                               </div>	
                                                                       </div>
                                                                       <?php ActiveForm::end() ?>
                                                               </li>

                                                         <?php } ?>

                                                          <?php if(!empty($user_data['occupation'])){?>
                                                               <li>
                                                                <?php $form = ActiveForm::begin(['id' => 'frm-occupation','options'=>['onsubmit'=>'return false;',],]); ?> 
                                                                       <div class="normal-mode">
                                                                               <div class="row">
                                                                                       <div class="col-lg-1 col-md-3 col-sm-3 u-title">Occupation</div>
                                                                                       <div class="col-lg-10 col-md-8 col-sm-8 u-data"><p id="occupation"><?php echo $user_data['occupation'];?></p></div>
                                                                                       <div class="col-lg-1 col-md-1 col-sm-1 col-xs-12 u-btn">										
                                                                                               <div class="pull-right  linkholder">
                                                                                               <?php if($wall_user_id == $user_id){ echo '
                                                                                                       <a href="javascript:void(0)" onClick="open_edit(this)"><i class="fa fa-pencil"></i></a>';
                                                                                               }?>

                                                                                               </div>
                                                                                       </div>
                                                                               </div>	
                                                                       </div>	
                                                                       <div class="edit-mode">
                                                                               <div class="row">
                                                                                       <div class="col-lg-1 col-md-3 col-sm-3 col-xs-12 u-title">
                                                                                               Occupation
                                                                                       </div>
                                                                                       <div class="col-lg-8 col-md-6 col-sm-6 col-xs-12  u-data">
                       <?= $form->field($model2,'occupation')->dropDownList(ArrayHelper::map(Occupation::find()->all(), 'name', 'name'),['class'=>'js-example-theme-multiple','style'=>'width: 100%','multiple'=>'multiple'])->label(false)?>  									
                                                                                       </div>									
                                                                                       <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 pull-right u-btn">
                                                                                               <div class="form-group pull-right nbm">						
                                                                                                       <div class="pull-right fb-btnholder nbm">		
                                                                                                               <a class="btn btn-primary btn-sm" onClick="close_edit(this),occupation()">Save</a>
                                                                                                               <a class="btn btn-primary btn-sm" onClick="close_edit(this)">Cancel</a>												
                                                                                                       </div>										
                                                                                               </div>										
                                                                                       </div>
                                                                               </div>	
                                                                       </div>	
                               <?php ActiveForm::end() ?>					
                                                               </li>			
                                                         <?php } ?>


                                                         <?php if(($wall_user_id == $user_id) && !empty($user_basicinfo['birth_date'])){?>
                                                               <li>
                                                                       <div class="normal-mode">
                                                                               <div class="row">
                                                                                       <div class="col-lg-1 col-md-3 col-sm-3 u-title">Birthdate</div>
                                                                                       <div class="col-lg-10 col-md-8 col-sm-8 u-data"><p  id="birth_date"><?php echo 'Born in '.$user_basicinfo['birth_date'];?></p></div>
                                                                                       <div class="col-lg-1 col-md-1 col-sm-1 col-xs-12 u-btn">										
                                                                                               <div class="pull-right  linkholder">
                                                                                               <?php if($wall_user_id == $user_id){ echo '
                                                                                                       <a href="javascript:void(0)" onClick="open_edit(this)"><i class="fa fa-pencil"></i></a>';
                                                                                               }?>

                                                                                               </div>
                                                                                       </div>
                                                                               </div>	
                                                                       </div>	
                                                                       <div class="edit-mode">
                                                                               <div class="row">
                                                                                       <div class="col-lg-1 col-md-3 col-sm-3 col-xs-12 u-title">
                                                                                               Birthdate
                                                                                       </div>
                                                                                       <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 u-data">
                                                                                               <div class='input-group date bdate' id='datetimepicker2'>									
                                                                                                       <input type="text" id="popupDatepicker" class="form-control" name="birth_date" value="<?=$user_basicinfo['birth_date']?>" placeholder="Birthdate" onkeydown="return false;">
                                                                                                       <span class="input-group-addon"><i class="glyphicon glyphicon-calendar " style="color:#0071BD;"></i></span>
                                                                                               </div>
                                                                                       </div>									
                                                                                       <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 pull-right u-btn">
                                                                                               <div class="form-group pull-right nbm">						
                                                                                                       <div class="pull-right fb-btnholder nbm">		
                                                                                                               <a class="btn btn-primary btn-sm" onClick="close_edit(this),birth_date()">Save</a>
                                                                                                               <a class="btn btn-primary btn-sm" onClick="close_edit(this)">Cancel</a>												
                                                                                                       </div>										
                                                                                               </div>										
                                                                                       </div>
                                                                               </div>	
                                                                       </div>	
                                                               </li>

                                                         <?php } ?>

                                                         <?php 
                                                         if(!empty($user_basicinfo['birth_date']) && $birth_date_access=='Public' && ($wall_user_id != $user_id)){?> 
                                                               <li>
                                                                       <div class="normal-mode">
                                                                               <div class="row">
                                                                                       <div class="col-lg-1 col-md-3 col-sm-3 u-title">Birthdate</div>
                                                                                       <div class="col-lg-10 col-md-8 col-sm-8 u-data"><p  id="birth_date"><?php echo 'Born in '.substr($user_basicinfo['birth_date'],6,9);?></p></div>
                                                                                       <div class="col-lg-1 col-md-1 col-sm-1 col-xs-12 u-btn">										
                                                                                               <div class="pull-right  linkholder">
                                                                                               <?php if($wall_user_id == $user_id){ echo '
                                                                                                       <a href="javascript:void(0)" onClick="open_edit(this)"><i class="fa fa-pencil"></i></a>';
                                                                                               }?>

                                                                                               </div>
                                                                                       </div>
                                                                               </div>	
                                                                       </div>	
                                                                       <div class="edit-mode">
                                                                               <div class="row">
                                                                                       <div class="col-lg-1 col-md-3 col-sm-3 col-xs-12 u-title">
                                                                                               Birthdate
                                                                                       </div>
                                                                                       <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 u-data">
                                                                                               <div class='input-group date bdate' id='datetimepicker2'>									
                                                                                                       <input type="text" id="popupDatepicker" class="form-control" name="birth_date" value="<?=$user_basicinfo['birth_date']?>" placeholder="Birthdate" onkeydown="return false;">
                                                                                                       <span class="input-group-addon"><i class="glyphicon glyphicon-calendar " style="color:#0071BD;"></i></span>
                                                                                               </div>
                                                                                       </div>									
                                                                                       <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 pull-right u-btn">
                                                                                               <div class="form-group pull-right nbm">						
                                                                                                       <div class="pull-right fb-btnholder nbm">		
                                                                                                               <a class="btn btn-primary btn-sm" onClick="close_edit(this),birth_date()">Save</a>
                                                                                                               <a class="btn btn-primary btn-sm" onClick="close_edit(this)">Cancel</a>												
                                                                                                       </div>										
                                                                                               </div>										
                                                                                       </div>
                                                                               </div>	
                                                                       </div>	
                                                               </li>
                                                                       <!--
                                                                       <div class="row">
                                                                               <div class="col-lg-1 col-md-8 col-sm-2 u-title">Birthdate</div>
                                                                               <div class="col-lg-11 col-md-4 col-sm-10 u-data"><p><?php echo 'Born in '.substr($user_basicinfo['birth_date'],6,9);?></p></div>
                                                                       </div>
                                                                       -->
                                                         <?php } ?>

                                               </ul>
                                       </div>
                                       <?php } ?>
                                          <div class="white-holderbox user_friends panel-shadow">


                                                  <div class="boxlabel">Friends</div>
                                                  <div class="clear"></div>

                                                   <ul class="nav nav-tabs">
                                                         <li class="active"><a data-toggle="tab" href="#umap">Map</a></li>
                                                         <li><a data-toggle="tab" href="#ulist">List</a></li>
                                                       </ul>

                                                       <div class="tab-content">
                                                               <div class="activity">
                                                                       <h6>Friends Activities</h6>							
                                                                       <div class="fsteps">
                                                                               <div class="row">
                                                                               <div class="col20per">
                                                                                               <span class="tmute">
                                                                                            <?php echo $count_pv;?></span>
                                                                                               <p>Reviews</p>
                                                                                       </div>
                                                                                       <div class="col20per">
                                                                                               <span class="tmute">12145</span>
                                                                                               <p>Gallery Photos</p>
                                                                                       </div>
                                                                                       <div class="col20per">
                                                                                               <span class="tmute">23</span>
                                                                                               <p>Blogs</p>
                                                                                       </div>
                                                                                       <div class="col20per">
                                                                                               <span class="tmute">75</span>
                                                                                               <p>Videos</p>
                                                                                       </div>

                                                                               </div>
                                                                       </div>
                                                          </div>
                                                         <div id="umap" class="tab-pane fade in active">							
                                                               <div class="map_canvas" id="map_canvas"></div>
                                                         </div>
                                                         <div id="ulist" class="tab-pane fade">														
                                                               <div class="wall-list">
                                                                       <ul>
                                                                               <?php foreach($user_friends as $user_friend){
                                                                                   $lmodel = new \frontend\models\LoginForm();
                                                                                   $friendinfo = LoginForm::find()->where(['_id' => $user_friend['from_id']])->one();
                                                                                   $fmodel = new \frontend\models\Friend();
                                                                                   $mutualcount = Friend::mutualfriendcount($friendinfo['_id']);
                                                                               ?>
                                                                                   <li>
                                                                                       <div class="ulist-holder">
                                                                                               <div class="imgholder">
                                                                                                       <img alt="user-photo" class="img-responsive" src="
                                                                                                       <?php if(isset($friendinfo['fb_id']) && !empty($friendinfo['photo']))
                                                                                                           {
                                                                                                               echo $friendinfo['photo'];
                                                                                                           }
                                                                                                           else if(isset($friendinfo['photo']) && !empty($friendinfo['photo']))
                                                                                                           {
                                                                                                               echo 'profile/'.$friendinfo['photo'];
                                                                                                           }
                                                                                                           else
                                                                                                           {
                                                                                                               echo 'profile/'.$friendinfo['gender'].'.jpg';
                                                                                                           } 
                                                                                                       ?>">
                                                                                               </div>										
                                                                                               <div class="descholder">
                                                                                                       <a class="profilelink no-ul" href="<?php $id =  $friendinfo['_id']; echo Url::to(['userwall/index', 'id' => "$id"]); ?>"><?php echo $friendinfo['fname'].' '.$friendinfo['lname']?></a>											
                                                                                                       <span class="online-status"><i class="fa fa-check-circle"></i></span>
                                                                                                       <div class="clear"></div>
                                                                                                       <?php if(isset($friendinfo['city']) && !empty($friendinfo['city'])){?>
                                                                                                           <div class="user-address"> <span class="title">Currently in : </span><div class="clear"></div><span><?php echo $friendinfo['city'].','.$friendinfo['country']?></span></div>
                                                                                                       <?php }?>
                                                                                               </div>
                                                                                       </div>
                                                                               </li>
                                                                               <?php }?>
                                                                       </ul>
                                                               </div>
                                                         </div>
                                                       </div>

                                          </div>

                                         <div class="white-holderbox user_photos panel-shadow">

                                                  <div class="boxlabel">Photos</div>
                                                  <div class="clear"></div>

                                                       <div class="photos">

                                                               <?php 

                                                                               if(count($posts)>0){
                                                                                               $ctr = 1;
                                                                                               ?>
                                                                                               <div class="albums">
                                                                                                               <?php 
                                                                                                               foreach($posts as $post){
                                                                                                                               if(isset($post['image']) && !empty($post['image'])){
                                                                                                                               $eximgs = explode(',',$post['image'],-1);

                                                                                                                               //if(!empty($post['image']) && empty($post['post_text'])) {
                                                                                                                               //if(!empty($post['image']) && empty($post['post_text']) && $ctr < 14) {
                                                                                                                               ?>
                                                                                                                               <?php foreach ($eximgs as $eximg) {

                                                                                                                                       if($ctr<9){																		

                                                                                                                                       $picsize = '';
                                                                                                                                       $val = getimagesize('../web'.$eximg);
                                                                                                                                       $picsize .= $val[0] .'x'. $val[1] .', ';
                                                                                                                                       if($val[0] > $val[1]){$imgclass = 'himg';}else if($val[1] > $val[0]){$imgclass = 'vimg';}else{$imgclass = 'simg';}
                                                                                                                               ?>
                                                                                                                               <div class="album-col">
                                                                                                                               <div class="album-holder">
                                                                                                                                       <div class="album-box">
                                                                                                                                               <a href="javascript:void(0)" class="listalbum-box <?= $imgclass?>-box"><img src="<?= Yii::$app->getUrlManager()->getBaseUrl() ?><?= $eximg ?>" alt=""></a>
                                                                                                                                       </div>
                                                                                                                               </div>
                                                                                                                               </div>
                                                                                                                               <?php } $ctr++; }?>
                                                                                                                               <?php

                                                                                                                               }
                                                                                                               }
                                                                                                               ?>
                                                                                               </div>
                                                                                               <?php
                                                                               }
                                                               ?>
                                                       </div>

                                          </div>

                                               <div class="user_destination white-holderbox panel-shadow">

                                                  <div class="boxlabel">Destination</div>
                                                  <div class="clear"></div>

                                                  <ul class="nav nav-tabs">
                                                         <li class="active"><a data-toggle="tab" href="#dmap">Map</a></li>
                                                         <li><a data-toggle="tab" href="#dlist">Places</a></li>
                                                       </ul>
                                                       <div class="tab-content">
                                                               <div class="destination-info">
                                                                       <div class="row">
                                                                               <div class="col-lg-6 col-sm-12">
                                                                                       <div class="user-desinfo">
                                                                                               <ul>
                                                                                                       <li><p><a href="javascript:void(0);">User Name</a> is currently in Chili</p></li>
                                                                                                       <li><p>has been to <b>32</b> countries</p></li>
                                                                                                       <li><p>has <b>10</b> travel plans</p></li>									
                                                                                               </ul>
                                                                                       </div>
                                                                               </div>
                                                                               <div class="col-lg-6 col-sm-12">
                                                                                       <div class="infosum-box">
                                                                                               <h5>70 of your friends have been to</h5>
                                                                                               <div class="row">
                                                                                                       <div class="col-lg-4 col-md-4 col-sm-4">
                                                                                                               <span class="tmute">5</span>
                                                                                                               <p>Countries</p>
                                                                                                       </div>
                                                                                                       <div class="col-lg-4 col-md-4 col-sm-4">
                                                                                                               <span class="tmute">37</span>
                                                                                                               <p>States</p>
                                                                                                       </div>
                                                                                                       <div class="col-lg-4 col-md-4 col-sm-4">
                                                                                                               <span class="tmute">808</span>
                                                                                                               <p>Cities</p>
                                                                                                       </div>
                                                                                               </div>
                                                                                       </div>
                                                                               </div>
                                                                       </div>								
                                                          </div>
                                                         <div id="dmap" class="tab-pane fade in active">							
                                                               <div class="map_canvas" id="map_container"></div>

                                                         </div>
                                                         <div id="dlist" class="tab-pane fade">														
                                                               <div class="wall-list">
                                                                       <ul>
                                                                               <li>
                                                                                       <div class="ulist-holder">
                                                                                               <div class="imgholder">
                                                                                                       <img class="img-responsive" src="<?= Yii::$app->getUrlManager()->getBaseUrl() ?>/profile/Male.jpg">
                                                                                               </div>										
                                                                                               <div class="descholder">
                                                                                                       <a href="javascript:void(0);">Place Name</a>																							
                                                                                                       <div class="clear"></div>
                                                                                                       <div class="user-address"> <span class="title">Country</span><div class="clear"></div><span>Place details goes here</span></div>											
                                                                                               </div>
                                                                                       </div>
                                                                               </li>
                                                                               <li>
                                                                                       <div class="ulist-holder">
                                                                                               <div class="imgholder">
                                                                                                       <img class="img-responsive" src="<?= Yii::$app->getUrlManager()->getBaseUrl() ?>/profile/Male.jpg">
                                                                                               </div>										
                                                                                               <div class="descholder">
                                                                                                       <a href="javascript:void(0);">Place Name</a>																							
                                                                                                       <div class="clear"></div>
                                                                                                       <div class="user-address"> <span class="title">Country</span><div class="clear"></div><span>Place details goes here</span></div>											
                                                                                               </div>
                                                                                       </div>
                                                                               </li>
                                                                               <li>
                                                                                       <div class="ulist-holder">
                                                                                               <div class="imgholder">
                                                                                                       <img class="img-responsive" src="<?= Yii::$app->getUrlManager()->getBaseUrl() ?>/profile/Male.jpg">
                                                                                               </div>										
                                                                                               <div class="descholder">
                                                                                                       <a href="javascript:void(0);">Place Name</a>																							
                                                                                                       <div class="clear"></div>
                                                                                                       <div class="user-address"> <span class="title">Country</span><div class="clear"></div><span>Place details goes here</span></div>											
                                                                                               </div>
                                                                                       </div>
                                                                               </li>
                                                                               <li>
                                                                                       <div class="ulist-holder">
                                                                                               <div class="imgholder">
                                                                                                       <img class="img-responsive" src="<?= Yii::$app->getUrlManager()->getBaseUrl() ?>/profile/Male.jpg">
                                                                                               </div>										
                                                                                               <div class="descholder">
                                                                                                       <a href="javascript:void(0);">Place Name</a>																							
                                                                                                       <div class="clear"></div>
                                                                                                       <div class="user-address"> <span class="title">Country</span><div class="clear"></div><span>Place details goes here</span></div>											
                                                                                               </div>
                                                                                       </div>
                                                                               </li>
                                                                               <li>
                                                                                       <div class="ulist-holder">
                                                                                               <div class="imgholder">
                                                                                                       <img class="img-responsive" src="<?= Yii::$app->getUrlManager()->getBaseUrl() ?>/profile/Male.jpg">
                                                                                               </div>										
                                                                                               <div class="descholder">
                                                                                                       <a href="javascript:void(0);">Place Name</a>																							
                                                                                                       <div class="clear"></div>
                                                                                                       <div class="user-address"> <span class="title">Country</span><div class="clear"></div><span>Place details goes here</span></div>											
                                                                                               </div>
                                                                                       </div>
                                                                               </li>
                                                                               <li>
                                                                                       <div class="ulist-holder">
                                                                                               <div class="imgholder">
                                                                                                       <img class="img-responsive" src="<?= Yii::$app->getUrlManager()->getBaseUrl() ?>/profile/Male.jpg">
                                                                                               </div>										
                                                                                               <div class="descholder">
                                                                                                       <a href="javascript:void(0);">Place Name</a>																							
                                                                                                       <div class="clear"></div>
                                                                                                       <div class="user-address"> <span class="title">Country</span><div class="clear"></div><span>Place details goes here</span></div>											
                                                                                               </div>
                                                                                       </div>
                                                                               </li>
                                                                       </ul>
                                                                 </div>
                                                         </div>
                                                       </div>					   
                                          </div>

                                               <div class="user_likes white-holderbox panel-shadow">

                                                  <div class="boxlabel">Likes</div>
                                                  <div class="clear"></div>

                                                       <div class="likes">
                                                               <?php 
                                                                       if(count($likes)>0){
                                                                               $lctr = 0;
                                                               ?>
                                                                       <div class="likes-row">
                                                                               <?php 
                                                                                       foreach($likes as $like){
                                                                                               if($lctr < 14) {
                                                                               ?>
                                                                                               <div class="likes-col">
                                                                                                       <div class="user-likebox">
                                                                                                               <a href="javascript:void(0);">
                                                                                                                       <span class="like-img">
                                                                                                                               <img height="200" width="200" src="<?= Yii::$app->getUrlManager()->getBaseUrl() ?><?= $like['post']['image'] ?>" alt=""/>
                                                                                                                       </span>
                                                                                                                       <span class="caption"><?php if(!empty($like['post']['post_text'])){ echo $like['post']['post_text'];}else {echo 'Like Caption';}?></span>
                                                                                                               </a>
                                                                                                       </div>
                                                                                               </div>
                                                                               <?php
                                                                                               $lctr++;
                                                                                               }
                                                                                       }
                                                                               ?>
                                                                       </div>
                                                               <?php
                                                                       }
                                                               ?>

                                                       </div>
                                          </div>

                                </div> 




        <?php } */
        elseif($_POST['page'] == 'friends'){
            $guserid = (string)$_POST['uid'];
            $session = Yii::$app->session;
            $suserid = (string)$session->get('user_id');
            $is_friend = Friend::find()->where(['from_id' => "$guserid",'to_id' => "$suserid",'status' => '1'])->one();
            $user_friends =  Friend::getuserFriends($_POST['uid']);
            $result_security = SecuritySetting::find()->where(['user_id' => $_POST['uid']])->one();
            if ($result_security) 
            {
                $my_friend_view_status = $result_security['friend_list'];
            }
            else
            {
                $my_friend_view_status = 'Public';
            }
        ?> 
        <div class="col-lg-8 col-md-8 col-sm-8 secondcol data-section-about">
			<div class="white-holderbox panel-shadow">
				<div class="boxlabel">Friends</div>
				<div class="clear"></div>
                                <?php if(($my_friend_view_status == 'Public') || ($my_friend_view_status == 'Friends' && ($is_friend || $guserid == $suserid)) || ($my_friend_view_status == 'Private' && $guserid == $suserid)) {?>
				<div class="friends">
					<div class="manage-btn">
						
							<div class="tarrow">
								<a href="javascript:void(0)" class="alink">
									<i class="fa fa-pencil"></i>
								</a>
								<div class="drawer">
									<h6>Who can see your friend list?</h6>
									<div class="opt-box">
										<ul class="siconopt-ul fullul">																															
											<li class="nicon">
												<a href="javascript:void(0)">Everyone</a>
											</li>
											<li class="divider"></li>
											<li class="nicon">
												<a href="javascript:void(0)">Friends of Friends</a>
											</li>					
											<li class="divider"></li>
											<li class="wicon">
												<a href="javascript:void(0)"><i class="fa fa-user"></i>Friends</a>
											</li>					
											<li class="divider"></li>
											<li class="wicon">
												<a href="javascript:void(0)"><i class="fa fa-lock"></i>Only Me</a>
											</li>					
											<li class="divider"></li>
											<li class="wicon">
												<a href="javascript:void(0)"><i class="fa fa-gear"></i>Custom</a>
											</li>								   
										</ul>				
									</div>
								</div>
							</div>
						
					</div>
					<ul class="nav nav-tabs">
					  <li class="active"><a href="#allfriends" data-toggle="tab" aria-expanded="true">All Friends <span>203</span></a></li>
					  <li class=""><a href="#recentlyadded" data-toggle="tab" aria-expanded="false">Recently Added</a></li>
					  <li class=""><a href="#birthdays" data-toggle="tab" aria-expanded="false">Birthdays</a></li>
					</ul>
					<div class="tab-content">
						 <div class="tab-pane fade active in" id="allfriends">	
							<div class="tab-search">
								<div class="searchbox">
									<input type="text" placeholder="Search for your friends" class="searchfriends" data-id="searchtwo"/>
									<div id="searchtwo" class="search-droparea"></div>
									<button type="submit"><i class="fa fa-search"></i></button>
                                                                        <input type="hidden" name="userwallid" id="userwallid" value="<?= $_POST['uid']?>"/>
								</div>
							</div>
							<?php 
							   //friend_list
							   //echo $_POST['uid'];
							   $user_friends =  Friend::getuserFriends($_POST['uid']);
							   $result_security = SecuritySetting::find()->where(['user_id' => $_POST['uid']])->one();
							   if ($result_security) 
							   {
								   $my_friend_view_status = $result_security['friend_list'];
							   }
							   else
							   {
								   $my_friend_view_status = 'Public';
							   }
							  
							 ?>
							   <div class="wall-list">
									<ul>
									   <?php 
									   if(count($user_friends)>0)
									   {
									   foreach($user_friends as $user_friend){
										   $lmodel = new \frontend\models\LoginForm();
										   $friendinfo = LoginForm::find()->where(['_id' => $user_friend['from_id']])->one();
										   $fmodel = new \frontend\models\Friend();
										   $mutualcount = Friend::mutualfriendcount($friendinfo['_id']);
									   ?>
										<li>
											<div class="ulist-holder flist-holder">
												<div class="imgholder">
													<?php
                                                                                                                $friendimg = $this->getimage($friendinfo['_id'],'photo');
													?>
													<img src="<?= $friendimg?>" class="img-responsive" />
													<span class="online-status"><i class="fa fa-check-circle"></i></span>
												</div>										
												<div class="descholder">
														   <a class="profilelink no-ul" href="<?php $id =  $friendinfo['_id']; echo Url::to(['userwall/index', 'id' => "$id"]); ?>"><?php echo $friendinfo['fname'].' '.$friendinfo['lname']?></a>
														<div class="user-address"> 														  
														   <?php if(isset($friendinfo['country']) && !empty($friendinfo['country'])){?>
															   <span class="title"><i class="fa fa-map-marker"></i></span><span><?php echo $friendinfo['country']?></span>
														   <?php }?>
														</div>
													<div class="add-friend-btn">
														<div class="tarrow">
															<a href="javascript:void(0)" class="alink">
																
																	<span class="already-friend">
																		<i class="fa fa-check"></i> Friends
																	</span>
																	<!--
																	<span class="add-friend">
																		<i class="fa fa-plus"></i> Add Friend
																	</span>
																	-->
																
															</a>
															<div class="drawer">
																<div class="opt-box">
																	<ul class="siconopt-ul fullul">																		
																		<li class="smenu-toggle checked">
																			<a href="javascript:void(0)">Get Notifications</a>
																		</li>
																		<li class="nicon">
																			<a onclick="mute_friend('<?= $friendinfo['_id']?>')" href="javascript:void(0)">Mute</a>
																		</li>				
																		<li class="nicon">
																			<a href="javascript:void(0)">Unfriend</a>
																		</li>								   
																		<li class="nicon">
																			<a href="javascript:void(0)">Restrict</a>
																		</li>								   
																		<li class="nicon">
																			<a href="javascript:void(0)">Block</a>
																		</li>								   
																	</ul>				
																</div>
															</div>
														</div>
													</div>
												</div>
										   </div>
										</li>
										<?php }}else{?><div class="no-listcontent">User has no friend </div><?php }?>
									</ul>
								</div>
						</div>
						
						<div class="tab-pane fade" id="recentlyadded">	
							<h5>Recently Added Friends By You</h5>
							
							<?php 
							   //friend_list
							   //echo $_POST['uid'];
							   $user_friends =  Friend::getuserFriends($_POST['uid']);
							   $result_security = SecuritySetting::find()->where(['user_id' => $_POST['uid']])->one();
							   if ($result_security) 
							   {
								   $my_friend_view_status = $result_security['friend_list'];
							   }
							   else
							   {
								   $my_friend_view_status = 'Public';
							   }
							 ?>
							   <div class="wall-list">
									<ul>
									   <?php 
									   if(count($user_friends)>0)
									   {
									   foreach($user_friends as $user_friend){
										   $lmodel = new \frontend\models\LoginForm();
										   $friendinfo = LoginForm::find()->where(['_id' => $user_friend['from_id']])->one();
										   $fmodel = new \frontend\models\Friend();
										   $mutualcount = Friend::mutualfriendcount($friendinfo['_id']);
									   ?>
										<li>
											<div class="ulist-holder flist-holder">
												<div class="imgholder">
													<?php
                                                                                                        $friendimg = $this->getimage($friendinfo['_id'],'photo');
													?>
													<img src="<?= $friendimg?>" class="img-responsive" />
													<span class="online-status"><i class="fa fa-check-circle"></i></span>
												</div>										
												<div class="descholder">
														   <a class="profilelink no-ul" href="<?php $id =  $friendinfo['_id']; echo Url::to(['userwall/index', 'id' => "$id"]); ?>"><?php echo $friendinfo['fname'].' '.$friendinfo['lname']?></a>
														  <div class="user-address"> 														  
														   <?php if(isset($friendinfo['country']) && !empty($friendinfo['country'])){?>
															   <span class="title"><i class="fa fa-map-marker"></i></span><span><?php echo $friendinfo['country']?></span>
														   <?php }?>
														</div>
													<div class="add-friend-btn">
														<div class="tarrow">
															<a href="javascript:void(0)" class="alink">
																
																	<span class="already-friend">
																		<i class="fa fa-check"></i> Friends
																	</span>
																	<!--
																	<span class="add-friend">
																		<i class="fa fa-plus"></i> Add Friend
																	</span>
																	-->
																
															</a>
															<div class="drawer">
																<div class="opt-box">
																	<ul class="siconopt-ul fullul">																		
																		<li class="smenu-toggle checked">
																			<a href="javascript:void(0)">Get Notifications</a>
																		</li>
																		<li class="nicon">
																			<a onclick="mute_friend('<?= $friendinfo['_id']?>')" href="javascript:void(0)">Mute</a>
																		</li>				
																		<li class="nicon">
																			<a href="javascript:void(0)">Unfriend</a>
																		</li>								   
																		<li class="nicon">
																			<a href="javascript:void(0)">Restrict</a>
																		</li>								   
																		<li class="nicon">
																			<a href="javascript:void(0)">Block</a>
																		</li>								   
																	</ul>				
																</div>
															</div>
														</div>
													</div>
												</div>
										   </div>
										</li>
										<?php }}else{?><div class="no-listcontent">User has no friend </div><?php }?>
									</ul>
								</div>
						</div>
						
						<div class="tab-pane fade" id="birthdays">	
							<h5>Friends with upcoming birthdays</h5>
							
							<?php 
							   //friend_list
							   //echo $_POST['uid'];
							   $user_friends =  Friend::getuserFriends($_POST['uid']);
							   $result_security = SecuritySetting::find()->where(['user_id' => $_POST['uid']])->one();
							   if ($result_security) 
							   {
								   $my_friend_view_status = $result_security['friend_list'];
							   }
							   else
							   {
								   $my_friend_view_status = 'Public';
							   }
							 ?>
							   <div class="wall-list">
									<ul>
									   <?php 
									   if(count($user_friends)>0)
									   {
									   foreach($user_friends as $user_friend){
										   $lmodel = new \frontend\models\LoginForm();
										   $friendinfo = LoginForm::find()->where(['_id' => $user_friend['from_id']])->one();
										   $fmodel = new \frontend\models\Friend();
										   $mutualcount = Friend::mutualfriendcount($friendinfo['_id']);
									   ?>
										<li>
											<div class="ulist-holder flist-holder">
												<div class="imgholder">
													<?php
                                                                                                                $friendimg = $this->getimage($friendinfo['_id'],'photo');
													?>
													<img src="<?= $friendimg?>" class="img-responsive" />
													<span class="online-status"><i class="fa fa-check-circle"></i></span>
												</div>										
												<div class="descholder">
														   <a class="profilelink no-ul" href="<?php $id =  $friendinfo['_id']; echo Url::to(['userwall/index', 'id' => "$id"]); ?>"><?php echo $friendinfo['fname'].' '.$friendinfo['lname']?></a>														  
														   <?php if(isset($friendinfo['city']) && !empty($friendinfo['city'])){?>
															   <div class="user-address"> <span class="title"></span><span>Birthdate : <?php echo $friendinfo['birth_date']?></span></div>
														   <?php }?>
													<div class="add-friend-btn">
														<div class="tarrow">
															<a href="<?php $id =  $friendinfo['_id']; echo Url::to(['userwall/index', 'id' => "$id"]); ?>">
																
																	<span class="already-friend">
																		<i class="fa fa-eye"></i> View profile
																	</span>
																	<!--
																	<span class="add-friend">
																		<i class="fa fa-plus"></i> Add Friend
																	</span>
																	-->
																
															</a>
															 
														</div>
													</div>
												</div>
										   </div>
										</li>
										<?php }}else{?><div class="no-listcontent">User has no friend </div><?php }?>
									</ul>
								</div>
					    </div>
					</div>
				   
				</div>	 
                            <?php } else {
                                echo '<span class="no-listcontent">User has kept security for Friend List</span>';
                            } ?>
				
			</div>
        </div>


        <?php include('includes/userwall.php');
} elseif($_POST['page'] == 'photos'){
               $albums = PostForm::getAlbums((string)$_POST['uid']);
               $total_albums = count($albums);
               $profile_albums = PostForm::getProfilePics((string)$_POST['uid']);
               $total_profile_albums = count($profile_albums);
               if($total_profile_albums>0){$total_profile_album=1;}else{$total_profile_album=0;}
               $cover_albums = PostForm::getCoverPics((string)$_POST['uid']);
               $total_cover_albums = count($cover_albums);
               if($total_cover_albums>0){$total_cover_album=1;}else{$total_cover_album=0;}
               $totalpics = PostForm::getPics((string)$_POST['uid']);
               $totalpictures = $totalpics + $total_profile_album + $total_cover_album;
               $totalcounts = $total_albums + $total_profile_album + $total_cover_album;
               $result = UserForm::find()->where(['_id' => (string)$_POST['uid']])->one();
               $fullname = $result['fname'].' '.$result['lname'];
                $profile_picture_image = $this->getimage($result['_id'],'thumb');
                if(isset($result['cover_photo']) && !empty($result['cover_photo']))
                {
                    $cover_picture_image = "/profile/".$result['cover_photo'];
                }
                else
                {
                    $cover_picture_image = '/profile/cover.jpg';
                }
           ?>

            <div class="col-lg-8 col-md-8 col-sm-8 secondcol data-section-about">

                               <div class="tb-panel-box photos-pd-fix wall-right-section panel-shadow">
									<div class="boxlabel">Photos</div>
									<div class="clear"></div>
									<div class="albums-page">
									   <div class="album-section">
										   <div class="album-row">
											
												<ul class="nav nav-tabs">
												  <li class="active"><a aria-expanded="true" data-toggle="tab" href="#allalbums">All Albums <span><?= $total_albums?></span></a></li>
												  <li class=""><a aria-expanded="false" data-toggle="tab" href="#allphotos">All Photos <span><?= $totalpictures?></span></a></li>											  
												</ul>
												<div class="tab-content">
													<div id="allalbums" class="tab-pane fade active in">	
														<div class="atitle">
														   <a href="javascript:void(0)"><?= $fullname?></a>
														   <span class="nav-arrow"><i class="fa fa-caret-right"></i></span>
														   <p>Albums (<?php echo $totalcounts;?>)</p>
													   </div>
													   <div class="albums">
														   <?php
														   if($user_id == $_POST['uid']){?>
															   <div class="album-col">
																	   <div class="album-holder">
																			   <div class="album-box">
																					<a href="#addalbum-popup" class="popup-modal addalbum-box"> 
																						<i class="fa fa-plus"></i>Create New Album
																					</a>
																			   </div>
																			   <div id="addalbum-popup" class="white-popup-block mfp-hide rounded fp-modal-popup">
																					<div class="modal-title graytitle clearfix">										
																						<a href="javascript:void(0)" class="popup-modal-dismiss popup-modal-close close-top"><i class="fa fa-close"></i></a>
																					</div>
																					<div class="tb-panel-body01">
																						<div class="modal-detail">	
																																								   
																						   <?php /* <div class="panel-body"> */ ?>
																								<div class="album-hd">
																									   <h4>Create an album</h4>
																							   </div>
																								   <?php $form = ActiveForm::begin(['options' => ['method' => 'post','enctype'=>'multipart/form-data','id' =>'albumdata']]) ?>

																										   <div class="form-group">

																											
																											<div class="tb-user-post-middle">
																												<div class="sliding-middle-out full-width">											
																													<input type="text" class="form-control" name="title" placeholder="Title of this Album" name="album_title" id="album_title">
																												</div>
																											</div>
																											<script>
																												$(document).ready(function(){
																													
																													$(".sliding-middle-out").click(function(){			
																														titleUnderline(this);			
																													});
																													
																												});
																											</script>
																										   </div>
																										
																										   <div class="form-group">

																									  <input type="text" class="form-control" name="description" placeholder="Say Somthing about this" name="album_description" id="album_description"> 
																										   </div>
																										   <div class="form-group">

																									  <input type="text" class="form-control" name="place" id="album_place" placeholder="Where were this taken?" name="album_place"> 
																										   </div>
																									<div id="image-holder"></div>
																										   <div class="form-group">          
																															<span class="choose-img">Choose the album images </span><input type="file" id="imageFile1" name="imageFile1[]" required="" multiple="true"/>
																										   </div>          
																										  
																				
																										   <div class="form-group fb-btnholder">
																												   
																												<div class="pull-right">
																													   <input type="button" class="btn btn-primary btn-sm nbm" value="Create" onclick="addalbum()">
																												</div>
																													<div class="pull-right mr">
																														<div class="dropdown tb-user-post">

																															<button aria-expanded="false" data-toggle="dropdown" type="button" class="btn btn-default dropdown-toggle btn-sm custom-select custom-select-posting-security"><span class="glyphicon glyphicon-<?= $post_dropdown_class ?>"></span> <?= $my_post_view_status ?> <span class="caret"></span></button>

																															<ul class="dropdown-menu" id="posting-security">
																																<li class="sel-private"><a href="javascript:void(0)" onClick="setSecuritySelect(this, 'Private')"><span class="glyphicon glyphicon-lock"></span> Private</a></li>
																																
																																<li class="sel-friends"><a href="javascript:void(0)" onClick="setSecuritySelect(this, 'Friends')"><span class="glyphicon glyphicon-user"></span> Friends</a></li>
																																
																																<li class="sel-public"><a href="javascript:void(0)" onClick="setSecuritySelect(this, 'Public')"><span class="glyphicon glyphicon-globe"></span> Public</a></li>
																																
																																<li class="sel-custom"><a href="#custom-share" class="popup-modal" onClick="sendSelId('posting-security')"><span class="glyphicon glyphicon-cog"></span> Custom</a></li>
																															</ul>
																															<input type="hidden" name="post_privacy" id="post_privacy" value="<?= $my_post_view_status ?>"/>
																														
																														</div>
																												   </div>
																										   </div>
																								   <?php ActiveForm::end() ?>
																						   <?php /* </div> */ ?>
																						
																						</div>
																					</div>

																			   </div> 

																	   </div>

															   </div>
															   <?php }?>
														   <?php
														   if($total_profile_albums > 0){?>
															   <div class="album-col">
																	<div class="album-holder">
																	   <div class="album-box">
																		   <a href="javascript:void(0)" class="listalbum-box" onclick="viewprofilepics('<?php echo (string)$_POST['uid']?>')" title="Profile Pictures">
																			   <img src="<?= Yii::$app->getUrlManager()->getBaseUrl() ?><?= '/'.$profile_picture_image ?>" alt="">
																		   </a>
																		   <a href="javascript:void(0)" class="edit-album">
																			   <i class="fa fa-pencil"></i>
																		   </a>
																	   </div>
																	</div>
																   <div class="album-detail">
																		  <a href="javascript:void(0)" onclick="viewprofilepics('<?php echo (string)$_POST['uid']?>')">Profile Pictures</a>
																		  <span><?= $total_profile_albums?> photos</span>
																		  <span><i class="fa fa-globe"></i></span>
																  </div>
																</div>
															<?php }?>
														   <?php
														   if($total_cover_albums > 0){?>
															   <div class="album-col">
																	<div class="album-holder">
																	   <div class="album-box">
																		   <a href="javascript:void(0)" class="listalbum-box" onclick="viewcoverpics('<?php echo (string)$_POST['uid']?>')" title="Profile Pictures">
																			   <img src="<?= Yii::$app->getUrlManager()->getBaseUrl() ?><?= '/'.$cover_picture_image ?>" alt="">
																		   </a>
																		   <a href="javascript:void(0)" class="edit-album">
																			   <i class="fa fa-pencil"></i>
																		   </a>
																	   </div>
																	</div>
																   <div class="album-detail">
																		  <a href="javascript:void(0)" onclick="viewcoverpics('<?php echo (string)$_POST['uid']?>')">Cover Pictures</a>
																		  <span><?= $total_cover_albums?> photos</span>
																		  <span><i class="fa fa-globe"></i></span>
																  </div>
																</div>
															<?php }?>

												<?php 
														if($total_albums>0){
																//$ctr = 0;
																?>

																		<?php 
																		foreach($albums as $album){
																				if(isset($album['image']) && !empty($album['image'])){
																				$eximgs = explode(',',$album['image'],-1);
																				$totalimages = count($eximgs);

																				//if(!empty($post['image']) && empty($post['post_text'])) {
																				//if(!empty($post['image']) && empty($post['post_text']) && $ctr < 14) {
																				?>
																				<?php //foreach ($eximgs as $eximg) {?>
																			   <div class="album-col">
																				<div class="album-holder">
																				   <div class="album-box">
																					   <a href="javascript:void(0)" class="listalbum-box" onclick="viewalbum('<?php echo $album['_id']?>')" title="<?php echo $album['album_title']?>">
																						   <img src="<?= Yii::$app->getUrlManager()->getBaseUrl() ?><?= $eximgs[0] ?>" alt="">
																					   </a>
																					   <a href="javascript:void(0)" class="edit-album">
																						   <i class="fa fa-pencil"></i>
																					   </a>
																				   </div>
																				</div>
																			   <div class="album-detail">
																					  <a href="javascript:void(0)" onclick="viewalbum('<?php echo $album['_id']?>')"><?= $album['album_title']?></a>
																					  <span><?= $totalimages?> photos</span>
																					  <?php
																						$my_post_view_status = $album['post_privacy'];
																						  if ($my_post_view_status == 'Private') {
																							  $post_dropdown_class = 'lock';
																						  } else if ($my_post_view_status == 'Friends') {
																							  $post_dropdown_class = 'user';
																						  } else {
																							  $my_post_view_status = 'Public';
																							  $post_dropdown_class = 'globe';
																						  }
																						?>
																					  <span><i class="fa fa-<?=$post_dropdown_class?>"></i></span>
																			  </div>
																			  </div>
																				<?php //}?>
																				<?php
																				//$ctr++;
																				}
																		}
																		?>
																<?php
														}
														else
														{
															?><!--<div class="no-listcontent" id="noalbum"><?php
															//echo 'No Album Added.';
															?></div>--><?php
														}
												?>


													   </div>
											   
													</div>
													<div id="allphotos" class="tab-pane fade">	
														<div class="atitle">
														   <a href="javascript:void(0)"><?= $fullname?></a>
														   <span class="nav-arrow"><i class="fa fa-caret-right"></i></span>
														   <p>Albums (<?php echo $totalcounts;?>)</p>
													   </div>
													   <div class="albums">
														   <?php
														   if($user_id == $_POST['uid']){?>
															   <div class="album-col">
																	   <div class="album-holder">
																			   <div class="album-box">
																					<a href="#addalbum-popup" class="popup-modal addalbum-box"> 
																						<i class="fa fa-plus"></i>Create New Album
																					</a>
																			   </div>
																			   <div id="addalbum-popup" class="white-popup-block mfp-hide">
																					   
																					   <div class="modal-detail">	
																						<div class="album-hd">
																							   <h4>Create an album</h4>
																							   <a class="popup-modal-dismiss popup-modal-close" href="javascript:void(0)"><i class="fa fa-close"></i></a>
																					   </div>																			   
																							   <div class="panel-body">

																									   <?php $form = ActiveForm::begin(['options' => ['method' => 'post','enctype'=>'multipart/form-data','id' =>'albumdata']]) ?>

																											   <div class="form-group">

																												<input type="text" class="form-control brb-aname" name="title" placeholder="Title of this Album" name="album_title" id="album_title"> 
																											   </div>
																											
																											   <div class="form-group">

																										  <input type="text" class="form-control" name="description" placeholder="Say Somthing about this" name="album_description" id="album_description"> 
																											   </div>
																											   <div class="form-group">

																										  <input type="text" class="form-control" name="place" id="album_place" placeholder="Where were this taken?" name="album_place"> 
																											   </div>
																										<div id="image-holder"></div>
																											   <div class="form-group">          
																																<span class="choose-img">Choose the album images </span><input type="file" id="imageFile1" name="imageFile1[]" required="" multiple="true"/>
																											   </div>          
																											   <!--<div class='input-group date' id='datetimepicker2' onkeydown = "return false;">
																										<input type='text' class="form-control" id='datetimepicker' placeholder="date of image"  name="album_img_date"/> 

																																	   <span class="input-group-addon"><i class="glyphicon glyphicon-calendar " style="color:#0071BD;"></i></span>
																									   </div>
																											   -->
																											   <div class="form-group fb-btnholder">
																													   
																													<div class="pull-right">
																														   <input type="button" class="btn btn-primary btn-sm" value="Create" onclick="addalbum()">
																													</div>
																														<div class="pull-right mr">
																															<div class="dropdown tb-user-post">

																																<button aria-expanded="false" data-toggle="dropdown" type="button" class="btn btn-default dropdown-toggle btn-sm custom-select custom-select-posting-security"><span class="glyphicon glyphicon-<?= $post_dropdown_class ?>"></span> <?= $my_post_view_status ?> <span class="caret"></span></button>

																																<ul class="dropdown-menu" id="posting-security">
																																	<li class="sel-private"><a href="javascript:void(0)" onClick="setSecuritySelect(this, 'Private')"><span class="glyphicon glyphicon-lock"></span> Private</a></li>
																																	
																																	<li class="sel-friends"><a href="javascript:void(0)" onClick="setSecuritySelect(this, 'Friends')"><span class="glyphicon glyphicon-user"></span> Friends</a></li>
																																	
																																	<li class="sel-public"><a href="javascript:void(0)" onClick="setSecuritySelect(this, 'Public')"><span class="glyphicon glyphicon-globe"></span> Public</a></li>
																																	
																																	<li class="sel-custom"><a href="#custom-share" class="popup-modal" onClick="sendSelId('posting-security')"><span class="glyphicon glyphicon-cog"></span> Custom</a></li>
																																</ul>
																																<input type="hidden" name="post_privacy" id="post_privacy" value="<?= $my_post_view_status ?>"/>
																															
																															</div>
																													   </div>
																											   </div>
																									   <?php ActiveForm::end() ?>
																							   </div>
																					   </div>
																			   </div> 

																	   </div>

															   </div>
															   <?php }?>
														   <?php
														   if($total_profile_albums > 0){?>
															   <div class="album-col">
																	<div class="album-holder">
																	   <div class="album-box">
																		   <a href="javascript:void(0)" class="listalbum-box" onclick="viewprofilepics('<?php echo (string)$_POST['uid']?>')" title="Profile Pictures">
																			   <img src="<?= Yii::$app->getUrlManager()->getBaseUrl() ?><?= '/'.$profile_picture_image ?>" alt="">
																		   </a>
																		   <a href="javascript:void(0)" class="edit-album">
																			   <i class="fa fa-pencil"></i>
																		   </a>
																	   </div>
																	</div>
																   <div class="album-detail">
																		  <a href="javascript:void(0)" onclick="viewprofilepics('<?php echo (string)$_POST['uid']?>')">Profile Pictures</a>
																		  <span><?= $total_profile_albums?> photos</span>
																		  <span><i class="fa fa-globe"></i></span>
																  </div>
																</div>
															<?php }?>
														   <?php
														   if($total_cover_albums > 0){?>
															   <div class="album-col">
																	<div class="album-holder">
																	   <div class="album-box">
																		   <a href="javascript:void(0)" class="listalbum-box" onclick="viewcoverpics('<?php echo (string)$_POST['uid']?>')" title="Profile Pictures">
																			   <img src="<?= Yii::$app->getUrlManager()->getBaseUrl() ?><?= '/'.$cover_picture_image ?>" alt="">
																		   </a>
																		   <a href="javascript:void(0)" class="edit-album">
																			   <i class="fa fa-pencil"></i>
																		   </a>
																	   </div>
																	</div>
																   <div class="album-detail">
																		  <a href="javascript:void(0)" onclick="viewcoverpics('<?php echo (string)$_POST['uid']?>')">Cover Pictures</a>
																		  <span><?= $total_cover_albums?> photos</span>
																		  <span><i class="fa fa-globe"></i></span>
																  </div>
																</div>
															<?php }?>

												<?php 
														if($total_albums>0){
																//$ctr = 0;
																?>

																		<?php 
																		foreach($albums as $album){
																				if(isset($album['image']) && !empty($album['image'])){
																				$eximgs = explode(',',$album['image'],-1);
																				$totalimages = count($eximgs);

																				//if(!empty($post['image']) && empty($post['post_text'])) {
																				//if(!empty($post['image']) && empty($post['post_text']) && $ctr < 14) {
																				?>
																				<?php //foreach ($eximgs as $eximg) {?>
																			   <div class="album-col">
																				<div class="album-holder">
																				   <div class="album-box">
																					   <a href="javascript:void(0)" class="listalbum-box" onclick="viewalbum('<?php echo $album['_id']?>')" title="<?php echo $album['album_title']?>">
																						   <img src="<?= Yii::$app->getUrlManager()->getBaseUrl() ?><?= $eximgs[0] ?>" alt="">
																					   </a>
																					   <a href="javascript:void(0)" class="edit-album">
																						   <i class="fa fa-pencil"></i>
																					   </a>
																				   </div>
																				</div>
																			   <div class="album-detail">
																					  <a href="javascript:void(0)" onclick="viewalbum('<?php echo $album['_id']?>')"><?= $album['album_title']?></a>
																					  <span><?= $totalimages?> photos</span>
																					  <?php
																						$my_post_view_status = $album['post_privacy'];
																						  if ($my_post_view_status == 'Private') {
																							  $post_dropdown_class = 'lock';
																						  } else if ($my_post_view_status == 'Friends') {
																							  $post_dropdown_class = 'user';
																						  } else {
																							  $my_post_view_status = 'Public';
																							  $post_dropdown_class = 'globe';
																						  }
																						?>
																					  <span><i class="fa fa-<?=$post_dropdown_class?>"></i></span>
																			  </div>
																			  </div>
																				<?php //}?>
																				<?php
																				//$ctr++;
																				}
																		}
																		?>
																<?php
														}
														else
														{
															?><!--<div class="no-listcontent" id="noalbum"><?php
															//echo 'No Album Added.';
															?></div>--><?php
														}
												?>


													   </div>
											   
													</div>
												</div>
												   
										   </div>
									   </div>
									   <div class="picture-section">                                       
									   </div>
									</div>

								</div>


                            </div>


        <?php include('includes/userwall.php');
		} 
        elseif($_POST['page'] == 'destinations'){?> 
        <div class="col-lg-8 col-md-8 col-sm-8 secondcol data-section-about">
            <div class="tb-panel-box photos-pd-fix wall-right-section panel-shadow">
                <div class="boxlabel">Destinations
                </div>
                <span class="no-listcontent">Coming Soon</span>
            </div>
        </div>

        <?php include('includes/userwall.php');
		} 
        elseif($_POST['page'] == 'likes'){

             $likes = Like::getUserPostLike($_POST['uid']);
             ?>
          <div class="col-lg-8 col-md-8 col-sm-8 secondcol data-section-about">

              <div class="tb-panel-box photos-pd-fix wall-right-section panel-shadow">
                  <div class="boxlabel">Likes
                </div>
                               <div class="friends">
                                                               <?php 
                                                                       if(count($likes)>0){
                                                                               $lctr = 0;
                                                               ?>
                                                                       <div class="fb-innerpage whitebg">
                                                                           <h4>Like Posts</h4>
                                                                               <?php 
                                                                                       foreach($likes as $like){
                                                                                           $likeid = $like['post']['_id'];
                                                                                               if($lctr < 14) {
                                                                               ?>
                                                                                               <div class="likes-col">
                                                                                                       <div class="user-likebox">
                                                                                                           <div class="col-lg-8 col-md-7 col-sm-7 col-xs-12">										
                                                                                                                    <div class="info">																   				   								
                                                                                                                        <a href="<?php echo Url::to(['site/view-post', 'postid' => "$likeid"]); ?>" target="_blank"><?php if(!empty($like['post']['post_text'])){ echo $like['post']['post_text'];}else {echo 'Liked Post';}?></a>
                                                                                                                    </div>
                                                                                                            </div>
                                                                                                               <!--<a href="javascript:void(0);">
                                                                                                                       <span class="like-img">
                                                                                                                               <img height="200" width="200" src="<?= Yii::$app->getUrlManager()->getBaseUrl() ?><?= $like['post']['image'] ?>" alt=""/>
                                                                                                                       </span>
                                                                                                                       <span class="caption"><?php if(!empty($like['post']['post_text'])){ echo $like['post']['post_text'];}else {echo 'Like Caption';}?></span>
                                                                                                               </a>-->
                                                                                                       </div>
                                                                                               </div>
                                                                               <?php
                                                                                               $lctr++;
                                                                                               }
                                                                                       }
                                                                               ?>
                                                                       </div>
                                                               <?php
                                                                       } else {
                                                                            echo '<span class="no-listcontent">No Liked Posts</span>';
                                                                        }
                                                               ?>


                                                       </div>
                                </div>
                </div>

        <?php include('includes/userwall.php');
		} 
        elseif($_POST['page'] == 'refers'){?> 
         <div class="col-lg-8 col-md-8 col-sm-8 secondcol data-section-about">
            <div class="tb-panel-box photos-pd-fix wall-right-section panel-shadow">
                <div class="boxlabel">Refers
                </div>
                <span class="no-listcontent">Coming Soon</span>
            </div>
        </div>

        <?php include('includes/userwall.php');
		} 
        elseif($_POST['page'] == 'endorsements'){?> 
 <div class="col-lg-8 col-md-8 col-sm-8 secondcol data-section-about">
            <div class="tb-panel-box photos-pd-fix wall-right-section panel-shadow">
                <div class="boxlabel">Endorsements
                </div>
                <span class="no-listcontent">Coming Soon</span>
            </div>
        </div>
 <?php include('includes/userwall.php'); }?>

            <?php
             } 
             
    public function actionViewalbumpics() {
        $data = array();
        if (isset($_POST['post_id']) && !empty($_POST['post_id']))
        {
            $getalbumdetails = PostForm::find()->where(['_id' => $_POST['post_id']])->one();
            $result = UserForm::find()->where(['_id' => (string)$getalbumdetails['post_user_id']])->one();
            $fullname = $result['fname'].' '.$result['lname'];
            if ($getalbumdetails)
            {
                $imgcontent = '';
                if(isset($getalbumdetails['image']) && !empty($getalbumdetails['image']))
                {
                    $session = Yii::$app->session;
                    $user_id = (string)$session->get('user_id');
                    $eximgs = explode(',',$getalbumdetails['image'],-1);
                    $total_eximgs = count($eximgs);
                    $imgcontent .= '<div class="album-row">';
                    $imgcontent .= '<div class="atitle">';
                    $imgcontent .= '<a href="javascript:void(0)">'.$fullname.'</a>';
                    $imgcontent .= '<span class="nav-arrow"><i class="fa fa-caret-right"></i></span>';
                    $imgcontent .= '<p>'.$getalbumdetails['album_title'].'('.$total_eximgs.')</p>';
                    $imgcontent .= '</div>';
                    $imgcontent .= '<div class="album-col">';
                    $imgcontent .= '<div class="album-holder">';
                    $imgcontent .= '<div class="album-box">';
                    $imgcontent .= '<a href="#addalbum-popup" class="popup-modal addpic-box">Add photos to this album</a>';
                    $imgcontent .= '</div>';
                    $imgcontent .= '</div>';
                    $imgcontent .= '</div>';
                    foreach ($eximgs as $eximg) {
                        $iname = $this->getimagefilename($eximg);
                        $imgpath = Yii::$app->getUrlManager()->getBaseUrl().$eximg;
                        $picsize = '';
                        $val = getimagesize('../web'.$eximg);
                        $picsize .= $val[0] .'x'. $val[1] .', ';
                        if($val[0] > $val[1]){$imgclass = 'himg';}else if($val[1] > $val[0]){$imgclass = 'vimg';}else{$imgclass = 'simg';}
                        $imgcontent .= '<div class="album-col" id="imgbox_'.$iname.'">';
                        $imgcontent .= '<div class="album-holder">';
                        $imgcontent .= '<div class="album-box">';
                        $imgcontent .= '<a href="javascript:void(0)" class="listalbum-box '.$imgclass.'-box"><img src="'.$imgpath.'" alt=""></a>';
                        if((string)$getalbumdetails['post_user_id'] == $user_id)
                        {
                            $imgcontent .= '<a href="javascript:void(0)" title="Delete Image" onclick="delete_image(\''.$iname.'\',\''.$eximg.'\',\''.$_POST['post_id'].'\')" class="edit-pic"><i class="fa fa-close"></i></a>';
                            $imgcontent .= '<div class="pic-data"><a  href="javascript:void(0)">Like</a><a  href="javascript:void(0)">Comment</a></div><div class="like-count"><a  href="javascript:void(0)" onclick="album_cover(\''.$user_id.'\',\''.$eximg.'\',\''.$_POST['post_id'].'\')"><i class="fa fa-camera-retro"></i> 8</a></div>';
                        }
                        $imgcontent .= '</div>';
                        $imgcontent .= '</div>';
                        $imgcontent .= '</div>';
                    }
                    $imgcontent .= '</div>';
                }
                $data['previewalbumimages'] = $imgcontent;
                $data['value'] = '1';
                echo json_encode($data);
            }
            else
            {
                $data['value'] = '2';
                echo json_encode($data);
            }
        }
        else
        {
            $data['value'] = '0';
            echo json_encode($data);
        }
    }
    
    public function actionViewprofilepics() {
        $data = array();
        if (isset($_POST['u_id']) && !empty($_POST['u_id']))
        {
            $profile_albums = PostForm::getProfilePics((string)$_POST['u_id']);
            $total_profile_albums = count($profile_albums);
            $result = UserForm::find()->where(['_id' => (string)$_POST['u_id']])->one();
            $fullname = $result['fname'].' '.$result['lname'];
            if ($profile_albums)
            {
                $imgcontent = '';
                $imgcontent .= '<div class="album-row">';
                $imgcontent .= '<div class="atitle">';
                $imgcontent .= '<a href="javascript:void(0)">'.$fullname.'</a>';
                $imgcontent .= '<span class="nav-arrow"><i class="fa fa-caret-right"></i></span>';
                $imgcontent .= '<p>Profile Pictures('.$total_profile_albums.')</p>';
                $imgcontent .= '</div>';
                foreach ($profile_albums as $eximg) {
                    $imgpath = Yii::$app->getUrlManager()->getBaseUrl().'/profile/'.$eximg['image'];
                    $picsize = '';
                    $val = getimagesize('../web/profile/'.$eximg['image']);
                    $picsize .= $val[0] .'x'. $val[1] .', ';
                    if($val[0] > $val[1]){$imgclass = 'himg';}else if($val[1] > $val[0]){$imgclass = 'vimg';}else{$imgclass = 'simg';}
                    $imgcontent .= '<div class="album-col">';
                    $imgcontent .= '<div class="album-holder">';
                    $imgcontent .= '<div class="album-box">';
                    $imgcontent .= '<a href="javascript:void(0)" class="listalbum-box '.$imgclass.'-box"><img src="'.$imgpath.'" alt=""></a>';
                    $imgcontent .= '<a href="javascript:void(0)" class="edit-pic"><i class="fa fa-pencil"></i></a></span><div class="pic-data"><a  href="javascript:void(0)">Like</a><a  href="javascript:void(0)">Comment</a></div><div class="like-count"><a  href="javascript:void(0)"><i class="fa fa-thumbs-up"></i> 8</a></div>';
                    $imgcontent .= '</div>';
                    $imgcontent .= '</div>';
                    $imgcontent .= '</div>';
                }
                $imgcontent .= '</div>';
                $data['previewalbumimages'] = $imgcontent;
                $data['value'] = '1';
                echo json_encode($data);
            }
            else
            {
                $data['value'] = '2';
                echo json_encode($data);
            }
        }
        else
        {
            $data['value'] = '0';
            echo json_encode($data);
        }
    }
    
    public function actionViewcoverpics() {
        $data = array();
        if (isset($_POST['u_id']) && !empty($_POST['u_id']))
        {
            $profile_albums = PostForm::getCoverPics((string)$_POST['u_id']);
            $total_profile_albums = count($profile_albums);
            $result = UserForm::find()->where(['_id' => (string)$_POST['u_id']])->one();
            $fullname = $result['fname'].' '.$result['lname'];
            if ($profile_albums)
            {
                $imgcontent = '';
                $imgcontent .= '<div class="album-row">';
                $imgcontent .= '<div class="atitle">';
                $imgcontent .= '<a href="javascript:void(0)">'.$fullname.'</a>';
                $imgcontent .= '<span class="nav-arrow"><i class="fa fa-caret-right"></i></span>';
                $imgcontent .= '<p>Cover Pictures('.$total_profile_albums.')</p>';
                $imgcontent .= '</div>';
                foreach ($profile_albums as $eximg) {
                    $imgpath = Yii::$app->getUrlManager()->getBaseUrl().'/profile/'.$eximg['image'];
                    $picsize = '';
                    $val = getimagesize('../web/profile/'.$eximg['image']);
                    $picsize .= $val[0] .'x'. $val[1] .', ';
                    if($val[0] > $val[1]){$imgclass = 'himg';}else if($val[1] > $val[0]){$imgclass = 'vimg';}else{$imgclass = 'simg';}
                    $imgcontent .= '<div class="album-col">';
                    $imgcontent .= '<div class="album-holder">';
                    $imgcontent .= '<div class="album-box">';
                    $imgcontent .= '<a href="javascript:void(0)" class="listalbum-box '.$imgclass.'-box"><img src="'.$imgpath.'" alt=""></a>';
                    $imgcontent .= '<a href="javascript:void(0)" class="edit-pic"><i class="fa fa-pencil"></i></a></span><div class="pic-data"><a  href="javascript:void(0)">Like</a><a  href="javascript:void(0)">Comment</a></div><div class="like-count"><a  href="javascript:void(0)"><i class="fa fa-thumbs-up"></i> 8</a></div>';
                    $imgcontent .= '</div>';
                    $imgcontent .= '</div>';
                    $imgcontent .= '</div>';
                }
                $imgcontent .= '</div>';
                $data['previewalbumimages'] = $imgcontent;
                $data['value'] = '1';
                echo json_encode($data);
            }
            else
            {
                $data['value'] = '2';
                echo json_encode($data);
            }
        }
        else
        {
            $data['value'] = '0';
            echo json_encode($data);
        }
    }
    
public function actionSearchfriends()
    {
        $session = Yii::$app->session;
        $uid = (string)$_GET['userwallid'];
        $model = new \frontend\models\LoginForm();
        if (isset($_GET['key']) && !empty($_GET['key']))
        {
            $email = $_GET['key'];
            $eml_id = LoginForm::find()->where(['like','fname',$email])
                    ->orwhere(['like','lname',$email])
                    ->orwhere(['like','fullname',$email])
                    ->andwhere(['status'=>'1'])
                    ->all();
            $json = array();
            $i = 0;
            if (!empty($eml_id)) {
                ?>
                <ul><?php
                foreach ($eml_id as $val) {
                    $data = array();
                    $data[] = $val->fname;
                    $data[] = $val->email;
                    $data[] = $val->lname;
                    $data[] = $val->photo;
                    $data[] = (string) $val->_id;
                    $data[] = $val->gender;
                    $valfre = (string)$val->_id;
                    $friend = Friend::find()->where(['from_id' => $valfre, 'to_id' => $uid, 'status' => '1'])->one();
                    if ($friend) {
                    ?>
                        <li>
                            <a href="index.php?r=userwall%2Findex&id=<?= $val->_id ?>" class="search-rlink"><span class="display_box" align="left">
                                    <span class="img-holder">
                    <?php
                    $dp = $this->getimage($val['_id'],'photo');
                    ?>
                                                <img src="<?= $dp?>" alt="">
                                    </span>
                                    <span class="desc-holder">
                                        <p><?php echo $val->fname; ?>&nbsp;<?php echo $val->lname; ?></p>
                                        <span><?php echo $val->email; ?></span>
                                    </span>
                                </span></a>    
                        </li>
                        <?php }
                    }
                    ?></ul><?php
            } else {
                ?>
                <div class="noresult"><p>Sorry, No Result Found!</p></div>
                <?php
            }
        }
    }
    
    public function actionDeleteImage()
    {
        $image_name = isset($_POST['image_name']) ? $_POST['image_name'] : '';
        $post_id =  isset($_POST['post_id']) ? $_POST['post_id'] : '';
        $data = array();
        if($image_name != '' && $post_id != '')
        {
            $delimage = new PostForm();
            $delimage = PostForm::find()->where(['_id' => $post_id])->one();
            if($delimage)
            {
                $imagevalue = $delimage['image'];
                $imagepath = $image_name.',';
                $updatedimagevalue = str_replace($imagepath,"",$imagevalue);
                if(strlen($updatedimagevalue) < 3)
                {
                    $delimage->post_type = 'text';
                    if($delimage->is_album == '1') { $delimage->is_album = '0'; $delimage->is_deleted = '1';}
                }
                $delimage->image = $updatedimagevalue;
                if($delimage->update())
                {
                    $data['value'] = '1';
                    echo json_encode($data);
                }
                else
                {
                    $data['value'] = '0';
                    echo json_encode($data);
                }
            }
            else
            {
                $data['value'] = '0';
                echo json_encode($data);
            }
        }
        else
        {
            $data['value'] = '0';
            echo json_encode($data);
        }
    }
    
    public function actionAlbumCover()
    {
        $image_name = $_POST['image_name'];
        $post_id = $_POST['post_id'];
        $data = array();
        if(isset($image_name) && !empty($image_name) && isset($post_id) && !empty($post_id))
        {
            $updatealbumcover = new PostForm();
            $updatealbumcover = PostForm::find()->where(['_id' => $post_id])->one();
            if($updatealbumcover)
            {
                $imagevalue = $updatealbumcover['image'];
                $eximgs = explode(',',$imagevalue,-1);
                $totalimgs = count($eximgs);
                if($totalimgs == '1')
                {
                    $data['value'] = '1';
                    echo json_encode($data);
                }
                else
                {
                    $imagepath = $image_name.',';
                    $updatedimagevalue = str_replace($imagepath,"",$imagevalue);
                    $updatealbumcover->image = $image_name.','.$updatedimagevalue;
                    if($updatealbumcover->update())
                    {
                        $data['value'] = '1';
                        echo json_encode($data);
                    }
                    else
                    {
                        $data['value'] = '0';
                        echo json_encode($data);
                    }
                }
            }
            else
            {
                $data['value'] = '0';
                echo json_encode($data);
            }
        }
        else
        {
            $data['value'] = '0';
            echo json_encode($data);
        }
    }
    
    public function actionMoveAlbumImage()
    {
        $image_name = $_POST['image_name'];
        $from_post_id = $_POST['from_post_id'];
        $to_post_id = $_POST['to_post_id'];
        $data = array();
        if(isset($image_name) && !empty($image_name) && isset($from_post_id) && !empty($from_post_id) && isset($to_post_id) && !empty($to_post_id))
        {
            $removealbumimage = new PostForm();
            $removealbumimage = PostForm::find()->where(['_id' => $from_post_id])->one();
            $imagevalue = $removealbumimage['image'];
            $imagepath = $image_name.',';
            $removealbumimage = str_replace($imagepath,"",$imagevalue);

            $addalbumimage = new PostForm();
            $addalbumimage = PostForm::find()->where(['_id' => $to_post_id])->one();
            $addimagevalue = $addalbumimage['image'];
            $addalbumimage->image = $addimagevalue.$image_name.',';

            if($removealbumimage->update() && $addalbumimage->update())
            {
                $data['value'] = '1';
                echo json_encode($data);
            }
            else
            {
                $data['value'] = '0';
                echo json_encode($data);
            }
        }
        else
        {
            $data['value'] = '0';
            echo json_encode($data);
        }
    }
}
?>

