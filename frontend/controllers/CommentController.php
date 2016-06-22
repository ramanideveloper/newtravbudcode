<?php
namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\mongodb\ActiveRecord;
use frontend\models\UserForm;
use frontend\models\LoginForm;
use frontend\models\UserSetting;
use frontend\models\Personalinfo;
use frontend\models\SecuritySetting;
use frontend\models\NotificationSetting;
use frontend\models\PostForm;
use frontend\models\Like;
use yii\helpers\HtmlPurifier;
use yii\web\UploadedFile;
use yii\helpers\ArrayHelper;
use frontend\models\Comment;
use frontend\models\HideComment;
use yii\helpers\Url;
use frontend\models\Notification;
/**
 * Like controller
 */
class CommentController extends Controller
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
     * Ajax call - add comment to the post 
     * @param  post param 
     * @return html data
     * @Author Sonali Patel
     * @Date   30-03-2016 (dd-mm-yyyy)
     */  
  
    public function actionCommentPost()
    { 
		
        $session = Yii::$app->session;
        $post_id = $_POST['post_id'];
        $uid = $userid= $session->get('user_id');
        $comment = new Comment();
        $date = time();
        $data = array();
        $post = PostForm::find()->where(['_id' => $post_id])->one();

        $result = LoginForm::find()->where(['_id' => $uid])->one();
        $comment_exists = Comment::find()->where(['user_id' => "$uid",'comment'=>$_POST['comment']])->andWhere(['parent_comment_id'=> "0"])->all();

        if(empty($comment_exists) || count($comment_exists<=0)) 
        {
            if (isset($_FILES) && !empty($_FILES)) 
            {
            $imgcount =
                    count($_FILES["imageCommentpost"]["name"]);
            $img =
                    '';
            $im =
                    '';
           // for ($i =           0;                    $i < $imgcount;                    $i++) {
               
                if (isset($_FILES["imageCommentpost"]["name"]) && $_FILES["imageCommentpost"]["name"] != "") {
                    $url =
                            '../web/uploads/comment/';
                    $urls =
                            '/uploads/comment/';
                    move_uploaded_file($_FILES["imageCommentpost"]["tmp_name"],$url . $date . $_FILES["imageCommentpost"]["name"]);
                    
                    $img =  $urls . $date . $_FILES["imageCommentpost"]["name"];
                    $im =  $im . $img;
                } else {
                    //echo 'no';
                }
           // }
            $comment->image =             $im;
          }
            
     //  die;
            $comment->post_id = $_POST['post_id'];
            $comment->user_id = (string)$uid;

            $post_comment = str_replace(array("\n","\r\n","\r"), '', $_POST['comment']);
            $comment->comment = $post_comment;
            $comment->comment_type = 'post';
            $comment->status = '1';
            $comment->parent_comment_id = '0';
            $comment->created_date = $date;
            $comment->updated_date = $date;

            $comment->insert();
            $last_insert_id = $comment->_id;

            // Insert record in notification table also
             $notification =  new Notification();
             $notification->comment_id =   "$last_insert_id";
             $notification->user_id = "$userid";
             $notification->post_id = $_POST['post_id'];
             $notification->notification_type = 'comment';
             $notification->comment_content = $post_comment;
             $notification->is_deleted = '0';
             $notification->status = '1';
             $notification->created_date = "$date";
             $notification->updated_date = "$date";
             $post_details = PostForm::find()->where(['_id' => $_POST['post_id']])->one();
             $notification->post_owner_id = $post_details['post_user_id'];
            if($post_details['post_user_id'] != "$userid" && $post_details['post_privacy'] != "Private")
            {
                $notification->insert();
            }


           $last_comment_data =  Comment::find()->with('user')->with('post')->where(['_id' => "$last_insert_id",'status' => '1'])->one();

           $data['photo'] = $last_comment_data['user']['photo'];
           $data['username'] = $last_comment_data['user']['username'];
           $data['fb_id'] = $last_comment_data['user']['fb_id'];
           $data['gender'] = $last_comment_data['user']['gender'];
           $data['comment'] = $last_comment_data['comment'];
           $data['status'] = '1';
           $data['msg'] = 'inserted';
           if($last_comment_data['user']['fb_id'] == '' && $last_comment_data['user']['photo'] == '')
           {
               $photo = 'profile/'.$last_comment_data['user']['gender'].'.jpg';
           }
           else if($last_comment_data['user']['photo'] != '' && $last_comment_data['user']['fb_id'] == '')
           {
                $photo = 'profile/'.$last_comment_data['user']['photo'];
           }
           else
           {
               if(substr($last_comment_data['user']['photo'],0,4) == 'http')
                    $photo = $last_comment_data['user']['photo'];
              else
                  $photo = 'profile/'.$last_comment_data['user']['photo'];
           }
           $id = $last_comment_data['user']['_id'];
           $cid = "`".$last_comment_data['_id']."`";
           $pid = "`".$last_comment_data['post_id']."`";
           $status_comment = Like::getUserCommentLike($id,$last_comment_data['_id']);



            if($status_comment == '1' ) {  
              $flag = 'Unlike';
            } else { 
              $flag = 'Like'; 
            }

            $comment_time = Yii::$app->EphocTime->time_elapsed_A(time(),$last_comment_data['created_date']);    
            $url = Url::to(["userwall/index", "id" => "$uid"]);

              //   $data['html'] = '<div id="cwrapper_'.$last_comment_data['_id'].'" class="outer"><div class="inner" id="avatar"><img src='.$photo.' height="35" width="35" /></div>  <div class="inner comment_wrapper_'.$last_comment_data['_id'].'" id="comment-holder"><a href="'. Url::to(["userwall/index", "id" => "$id"]).'">'.ucfirst($last_comment_data['user']['fname']).' '.ucfirst($last_comment_data['user']['lname']).'</a><div id="text">'.$last_comment_data['comment'].'</div><div class="comment-like"><a href="javascript:void(0)" id="comment_cls_'.$last_comment_data['_id'].'" onclick="like_comment('.$cid.')">'.$flag.'</a> <span class="dot-span">·</span><a href="javascript:void(0)"  id="reply_comment'.$last_comment_data['_id'].'" onclick="reply_comment('.$cid.')"> Reply</a><div class="ago">'.$comment_time.'</div></div><a class="close-comment" href="#"><i class="fa fa-close"></i></a></div></div><div class="reply_comments_'.$last_comment_data['_id'].'"></div><div class="add_reply" id="display_reply_'.$last_comment_data['_id'].'" style="display:none"><div class="ac_img" id="user_img_'.$last_comment_data['post_id'].'"><a class="profilelink no-ul" href="'.$url.'"><img  width="35" height="35" alt="user-photo" class="img-responsive" src="'.$photo.'"></a></div><div class="ac_ttarea"><textarea name="reply_txt" data-postid="'.$last_comment_data['post_id'].'" data-commentid="'.$comment['_id'].'" id="reply_txt_'.$last_comment_data['_id'].'" placeholder="Write a Reply..." class="reply_class" ></textarea></div></div>';


                 //$data['html'] = '<div id="cwrapper_'.$last_comment_data['_id'].'"  class="outer"><div class="inner" id="avatar"><img src='.$photo.' height="35" width="35" /></div><div class="inner comment_wrapper_'.$last_comment_data['_id'].'" id="comment-holder"><a href="'. $url.'">'.ucfirst($last_comment_data['user']['fname']).' '.ucfirst($last_comment_data['user']['lname']).'</a><div id="text">'.$last_comment_data['comment'].'</div><div class="comment-like"><a href="javascript:void(0)" id="comment_cls_'.$last_comment_data['_id'].'" onclick="like_comment('.$cid.')">'.$flag.'</a> <span class="dot-span">·</span><a href="javascript:void(0)"  id="reply_comment'.$last_comment_data['_id'].'" onclick="reply_comment('.$cid.')"> Reply</a><div class="ago">'.$comment_time.'</div></div><div class="reply_comments reply_comments_'.$last_comment_data['_id'].'"></div><div class="add_reply" id="display_reply_'.$last_comment_data['_id'].'" style="display:none"><div class="ac_img" id="user_img_'.$last_comment_data['post_id'].'"><a class="profilelink no-ul" href="'.$url.'"><img  width="35" height="35" alt="user-photo" class="img-responsive" src="'.$photo.'"></a></div><div class="ac_ttarea"><textarea name="reply_txt" data-postid="'.$last_comment_data['post_id'].'" data-commentid="'.$comment['_id'].'" id="reply_txt_'.$last_comment_data['_id'].'" placeholder="Write a Reply..." class="reply_class" ></textarea></div></div><a title="Delete" class="close-comment" onclick="delete_post_comment('.$last_comment_data['_id'].')" href="javascript:void(0)"><i class="fa fa-close"></i></a></div></div>';

                $init_comment['_id'] = $last_comment_data['_id'];
                $init_comment =  $last_comment_data;


                $this->comment_html($post_id,$last_comment_data['_id']);
        }
           
        
        //$data['last_comment_id'] = (string)$last_insert_id;
       //$data['comment_count'] = count($comments);
       // echo json_encode($data);
        
    }
    
    
    
     /**
     * Ajax call - add comment to the post 
     * @param  post param 
     * @return html data
     * @Author Sonali Patel
     * @Date   30-03-2016 (dd-mm-yyyy)
     */  
  
    public function actionReplyComment()
    { 
		
        $session = Yii::$app->session;
        $post_id = $_POST['post_id'];
        $user_id = $userid = $uid = $session->get('user_id');
        $comment = new Comment();
        $date = time();
        $data = array();
       
        $post = PostForm::find()->where(['_id' => $post_id])->one();
        $reply_exists = Comment::find()->where(['user_id' => "$uid",'comment'=>$_POST['reply']])->andWhere(['not','parent_comment_id', "0"])->all();
       // print_r($reply_exists);
        if(empty($reply_exists) && count($reply_exists<=0))
        {
              if (isset($_FILES) && !empty($_FILES)) 
            {
            $imgcount =
                    count($_FILES["imageReplypost"]["name"]);
            $img =
                    '';
            $im =
                    '';
           // for ($i =           0;                    $i < $imgcount;                    $i++) 
           {
                if (isset($_FILES["imageReplypost"]["name"]) && $_FILES["imageReplypost"]["name"] != "") {
                    $url =
                            '../web/uploads/comment/';
                    $urls =
                            '/uploads/comment/';
                    move_uploaded_file($_FILES["imageReplypost"]["tmp_name"],$url . $date . $_FILES["imageReplypost"]["name"]);
                    
                    $img =  $urls . $date . $_FILES["imageReplypost"]["name"];
                    $im =  $im . $img;
                } else {
                    //echo 'no';
                }
            }
            $comment->image =             $im;
        }
        $comment->post_id = $_POST['post_id'];
        $comment->user_id = (string)$uid;
        
        $post_reply = str_replace(array("\n","\r\n","\r"), '', $_POST['reply']);
        $comment->comment = $post_reply;
        $comment->comment_type = 'post';
        $comment->status = '1';
        $comment->parent_comment_id = $_POST['comment_id'];
        $comment->created_date = $date;
        $comment->updated_date = $date;
        
       
        $comment->insert();
        $last_insert_id = $comment->_id;
        
        // Insert record in notification table also
        $notification =  new Notification();
        $notification->comment_id = $_POST['comment_id'];
        $notification->reply_comment_id = "$last_insert_id";
        $notification->user_id = "$userid";
        $notification->post_id = $_POST['post_id'];
        $notification->notification_type = 'commentreply';
        $notification->is_deleted = '0';
        $notification->status = '1';
        $notification->created_date = "$date";
        $notification->updated_date = "$date";
        $post_details = PostForm::find()->where(['_id' => $_POST['post_id']])->one();
        $comment_details = Comment::find()->where(['_id' => $_POST['comment_id']])->one();
        $notification->post_owner_id = $comment_details['user_id'];
        if($comment_details['user_id'] != "$userid" && $post_details['post_privacy'] != "Private")
        {
            $notification->insert();
        }
             
        $result = LoginForm::find()->where(['_id' => $userid])->one();
       $comment_reply = $last_comment_data =  Comment::find()->with('user')->with('post')->where(['_id' => "$last_insert_id",'status' => '1'])->one();
        if(($userid == $post['post_user_id']) || ($userid == $result['_id']))
            {
                    $afun_post = 'delete_post_comment';
                    $atool_post = 'Delete';
            }
            else
            {
                    $afun_post = 'hide_post_comment';
                    $atool_post = 'Hide';
            }
      
        if($last_comment_data['user']['fb_id'] == '' && $last_comment_data['user']['photo'] == '')
       {
           $photo = 'profile/'.$last_comment_data['user']['gender'].'.jpg';
       }
       else if($last_comment_data['user']['photo'] != '' && $last_comment_data['user']['fb_id'] == '')
       {
            $photo = 'profile/'.$last_comment_data['user']['photo'];
       }
       else
       {
           if(substr($last_comment_data['user']['photo'],0,4) == 'http')
                $photo = $last_comment_data['user']['photo'];
          else
              $photo = 'profile/'.$last_comment_data['user']['photo'];
       }
       $id = $last_comment_data['user']['_id'];
       $cid = "`".$last_comment_data['_id']."`";
       $status_comment = Like::getUserCommentLike($id,$last_comment_data['_id']);
        if($status_comment == '1' )
        { $flag = 'Unlike';}else { $flag = 'Like'; }
        
        $comment_time = Yii::$app->EphocTime->time_elapsed_A(time(),$last_comment_data['created_date']);  
        
     
        $hidecomment = new HideComment();
                                        $hidecomment = HideComment::find()->where(['user_id' => (string)$user_id])->one();
                                        $hide_comment_ids = explode(',',$hidecomment['comment_ids']); 
                                        if(!(in_array($comment_reply['_id'],$hide_comment_ids)))
                                        {
                                            if(($userid == $post['post_user_id']) || ($userid == $comment_reply['user']['_id']))
                                            {
                                                $bfun_post = 'delete_post_comment';
                                                $btool_post = 'Delete';
                                            }
                                            else
                                            {
                                                $bfun_post = 'hide_post_comment';
                                                $btool_post = 'Hide';
                                            }
                                                $comment_time = Yii::$app->EphocTime->time_elapsed_A(time(),$comment_reply['created_date']);
            ?>        <div id="cwrapper_<?php echo $comment_reply['_id']?>" class="outer comment_outer">
                                                 <div class="inner_avatar" id="avatar">
                                                        <a href="<?php $id = $comment_reply['user']['_id']; echo Url::to(['userwall/index', 'id' => "$id"]); ?>">
                                                     <?php
                                                            $comment_reply_img = $this->getimage($comment_reply['user']['_id'],'thumb');
                                                            ?>
                                                            <img src="<?= $comment_reply_img?>" class="img-responsive" height="35" width="35" alt="">
                                                   </a></div>

                                                    <div class="inner_desc comment_wrapper_<?php echo $comment_reply['_id'];?> comment_<?php echo $_POST['comment_id']?>" id="comment-holder">
                                                        <div style="display:none" class="edit_textarea" id="edit_textarea_<?php echo $comment_reply['_id'];?>"><textarea id="edit_textarea_value_<?php echo $comment_reply['_id'];?>"><?php echo $comment_reply['comment'];?></textarea><a href="javascript:void(0)" onclick="closeedit('<?php echo $comment_reply['_id'];?>')">Cancel</a></div>
														<div class="thisComment" id="editcomment_<?php echo $comment_reply['_id'];?>">
															<a href="<?php $id = $comment_reply['user']['_id']; echo Url::to(['userwall/index', 'id' => "$id"]); ?>"><?php echo ucfirst($comment_reply['user']['fname']).' '.ucfirst($comment_reply['user']['lname']);?></a>   
															<div class="comment_text" id="text_<?= $comment_reply['_id']?>"><?php echo $comment_reply['comment']?></div> 
     <?php if($comment_reply['image'] != ''){?>
<img src="<?= Yii::$app->getUrlManager()->getBaseUrl() ?><?php echo $comment_reply['image'];?>" class="img-responsive comment-photo" alt="comment-photo">
<?php }?>
															<div id="comment-like-<?php echo $comment_reply['_id']?>" class="comment-like">
																<a href="javascript:void(0)"  id="comment_cls_<?php echo $comment_reply['_id']?>" onclick="like_comment('<?php echo $comment_reply['_id']?>')">
																<?php $status_comment = Like::getUserCommentLike($result['_id'],$comment_reply['_id']);
																	if($status_comment == '1' )
																   { ?>Unlike<?php  }else {?>Like<?php }  ?>
																</a>
																<span class="dot-span">·</span>
																   <a href="javascript:void(0)"  id="reply_comment<?php echo $_POST['comment_id']?>" onclick="reply_comment('<?php echo $_POST['comment_id']?>')">Reply</a>
																<div class="ago"><?php echo $comment_time?></div>
															</div>
															<div class="action-comment">
                                                                                                                        <?php if(($userid != $post['post_user_id']) && ($userid != $comment_reply['user']['_id'])){?>
																<div class="own_comment">
																	<a href="javascript:void(0)" class="close-comment"  onclick="<?= $bfun_post?>('<?php echo $comment_reply['_id']?>')" title="<?=$btool_post?>">
																		<i class="fa fa-close"></i>
																	</a>
																</div>
                                                                                                                            <?php } else {?>
																<div class="other_comment">
                                                                                                                                    <div class="tarrow tarrow_<?php echo $comment_reply['_id'];?>">									
																		<a class="alink alink_<?php echo $comment_reply['_id'];?>" href="javascript:void(0)" class="close-comment" title="<?=$atool_post?>">
																			<i class="fa fa-close"></i>
																		</a>
																		<div class="drawer">
																			<div class="opt-box">
																				<ul class="siconopt-ul">
                                                                                                                                                                    <?php if($userid == $comment_reply['user']['_id']){?>
																					<li>
																						<a href="javascript:void(0)" onclick="replace('<?= $comment_reply['_id']?>','<?= $comment_reply['comment']?>')">Edit..</a>
																					</li>
                                                                                                                                                                    <?php }?>
																					<li>
																						<a href="javascript:void(0)" onclick="<?= $bfun_post?>('<?php echo $comment_reply['_id']?>')">Delete</a>
																					</li>                                                                          
																				</ul>
																			</div>
																		</div>
																	</div>
																</div>
																<?php }?>
															</div>
														</div>
                                                

                                                    </div>
                                                
                                            </div>
                
                                     <?php 
       //  $this->comment_html($post_id,$last_insert_id);
                                        }                        
        }
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
            
       if($session->get('email')){
               
            $user_data =  Personalinfo :: getPersonalInfo($user_id);
           
            $user_friends =  Friend::getuserFriends($user_id);
           
            $user_basicinfo = LoginForm::find()->where(['_id' => $user_id])->one();
      
            $posts = PostForm::getUserPost($user_id);

            $photos =  PostForm::getUserPostPhotos($user_id);
                    
            return $this->render('index',array('posts' => $posts,'user_friends' => $user_friends,'user_basicinfo' => $user_basicinfo,'user_data' => $user_data));
        }else{
                return $this->render('index', [
                   'model' => $model,
               ]);
        }
    }
    
    /**
     * Ajax call - add comment to the post 
     * @param  post param 
     * @return html data
     * @Author Sonali Patel
     * @Date   30-03-2016 (dd-mm-yyyy)
     */  
  
    public function actionLoadComment()
    { 
		
        $session = Yii::$app->session;
        $email = $session->get('email');
        $post['_id'] = $post_id = $_POST['pid'];
        $from_ctr = $_POST['from_ctr'];
        $to_ctr = $_POST['to_ctr'];
        $uid = $session->get('user_id');
        $user_id = $userid = (string)$session->get('user_id');
       $result = LoginForm::find()->where(['email' => $email])->one();
        $loaded_comments = Comment::find()->with('user')->with('post')->where(['post_id' => "$post_id",'status' => '1','parent_comment_id'=>'0'])->orderBy(['created_date'=>SORT_DESC])->offset($from_ctr)->limit($to_ctr)->all();
        
      //  echo 'sdsdfsdfsdfsdfs';exit;
        ?>
                       
                        <?php 
                     if(count($loaded_comments)>0){
                        foreach($loaded_comments as $init_comment)
                        { 
                            $post_user_id = new HideComment();
                            $post_user_id = PostForm::find()->where(['_id' => (string)$init_comment['post_id']])->one(); 
                            $post_user_id = $post_user_id['post_user_id'];
                            $comment_time = Yii::$app->EphocTime->time_elapsed_A(time(),$init_comment['updated_date']);
                            $hidecomments = new HideComment();
                            $hidecomments = HideComment::find()->where(['user_id' => (string)$user_id])->one();
                            $hide_comments_ids = explode(',',$hidecomments['comment_ids']); 
                            if(!(in_array($init_comment['_id'],$hide_comments_ids)))
                            {
								if(($userid == $post_user_id) || ($userid == $init_comment['user']['_id']))
								{
									$afun_post = 'delete_post_comment';
									$atool_post = 'Delete';
								}
								else
								{
									$afun_post = 'hide_post_comment';
									$atool_post = 'Hide';
								}
                            ?>

                    <div id="cwrapper_<?php echo $init_comment['_id']?>" class="outer">
                         <div class="inner_avatar" id="avatar">
                                
                            <?php
                                    $init_comment_image = $this->getimage($init_comment['user']['_id'],'thumb');
                                ?>
                                    <img src="<?= $init_comment_image?>" class="img-responsive" height="35" width="35" alt="">
                           </div>
                            <div class="inner_desc comment_wrapper_<?php echo $init_comment['_id'];?>" id="comment-holder">
                                <div style="display:none" class="edit_textarea" id="edit_textarea_<?php echo $init_comment['_id'];?>"><textarea id="edit_textarea_value_<?php echo $init_comment['_id'];?>"><?php echo $init_comment['comment'];?></textarea><a href="javascript:void(0)" onclick="closeedit('<?php echo $init_comment['_id'];?>')">Cancel</a></div>
								<div class="thisComment" id="editcomment_<?php echo $init_comment['_id'];?>">
									<a href="<?php $id = $init_comment['user']['_id']; echo Url::to(['userwall/index', 'id' => "$id"]); ?>"><?php echo ucfirst($init_comment['user']['fname']).' '.ucfirst($init_comment['user']['lname']);?></a>   
										<div class="comment_text" id="text_<?= $init_comment['_id']?>"><?php echo $init_comment['comment']?></div> 
		
									<div id="comment-like-<?php echo $init_comment['_id']?>" class="comment-like">
										<a href="javascript:void(0)"  id="comment_cls_<?php echo $init_comment['_id']?>" onclick="like_comment('<?php echo $init_comment['_id']?>')">
										<?php $status_comment = Like::getUserCommentLike($result['_id'],$init_comment['_id']);
											if($status_comment == '1' )
										   { ?>Unlike<?php  }else {?>Like<?php }  ?>
										</a>
										<span class="dot-span">·</span>
										 <a href="javascript:void(0)"  id="reply_comment<?php echo $init_comment['_id']?>" onclick="reply_comment('<?php echo $init_comment['_id']?>')">Reply</a>
										
										
										<div class="ago"><?php echo $comment_time?></div>
									</div>
									<?php //echo 'userid...'.$userid.'post_id...'.$post_user_id.'in...'.$init_comment['user']['_id'];?>
                                                                        <div class="action-comment">
									<?php if(($userid != $post_user_id) && ($userid != $init_comment['user']['_id'])){?>
									<div class="own_comment">
										<a href="javascript:void(0)" class="close-comment"  onclick="<?= $afun_post?>('<?php echo $init_comment['_id']?>')" title="<?=$atool_post?>">
											<i class="fa fa-close"></i>
										</a>
									</div>
                                                                        <?php } else {?>
									<div class="other_comment">
										<div class="tarrow tarrow1">									
											<a class="alink alink1" href="javascript:void(0)" class="close-comment" title="<?=$atool_post?>">
												<i class="fa fa-close"></i>
											</a>
											<div class="drawer">
												<div class="opt-box">
													<ul class="siconopt-ul">
                                                                                                                <?php if($userid == $init_comment['user']['_id']){?>
														<li>
															<a href="javascript:void(0)" onclick="replace('<?= $init_comment['_id']?>','<?= $init_comment['comment']?>')">Edit..</a>
														</li>
                                                                                                                <?php }?>
														<li>
															<a href="javascript:void(0)" onclick="<?= $afun_post?>('<?php echo $init_comment['_id']?>')">Delete</a>
														</li>                                                                          
													</ul>
												</div>
											</div>
										</div>
									</div>
                                                                        <?php }?>
								</div>
							</div>
								

                       <?php $comment_replies =Comment::getCommentReply($init_comment['_id']); 
                                    //echo '<pre>';print_r($comment_replies);
                                    
                                    ?>
                                        <div class="reply_comments reply_comments_<?php echo $init_comment['_id'];?>"><?php
                                    if(!empty($comment_replies))
                                    {
			?>
                                    <div class="reply_wrapper">
<?php
                                    foreach($comment_replies AS $comment_reply)
                                    {
                                        $hidecomment = new HideComment();
                                        $hidecomment = HideComment::find()->where(['user_id' => (string)$user_id])->one();
                                        $hide_comment_ids = explode(',',$hidecomment['comment_ids']); 
                                        if(!(in_array($comment_reply['_id'],$hide_comment_ids)))
                                        {
                                            if(($userid == $post_user_id) || ($userid == $comment_reply['user']['_id']))
                                            {
                                                $bfun_post = 'delete_post_comment';
                                                $btool_post = 'Delete';
                                            }
                                            else
                                            {
                                                $bfun_post = 'hide_post_comment';
                                                $btool_post = 'Hide';
                                            }
                                                $comment_time = Yii::$app->EphocTime->time_elapsed_A(time(),$comment_reply['updated_date']);
                                                    ?>

                                            <div id="cwrapper_<?php echo $comment_reply['_id']?>" class="outer comment_outer">
                                                 <div class="inner_avatar" id="avatar">
                                                        <a href="<?php $id = $comment_reply['user']['_id']; echo Url::to(['userwall/index', 'id' => "$id"]); ?>">
                                                        <?php
                                                            $comment_image = $this->getimage($comment_reply['user']['_id'],'thumb');
                                                            ?>
                                                            <img src="<?= $comment_image?>" class="img-responsive" height="35" width="35" alt="">
                                                   </a></div>

                                                    <div class="inner_desc comment_wrapper_<?php echo $comment_reply['_id'];?> comment_<?php echo $init_comment['_id']?>" id="comment-holder">
                                                        <div style="display:none" class="edit_textarea" id="edit_textarea_<?php echo $comment_reply['_id'];?>"><textarea id="edit_textarea_value_<?php echo $comment_reply['_id'];?>"><?php echo $comment_reply['comment'];?></textarea></div>
														<div class="thisComment" id="editcomment_<?php echo $comment_reply['_id'];?>">
															<a href="<?php $id = $comment_reply['user']['_id']; echo Url::to(['userwall/index', 'id' => "$id"]); ?>"><?php echo ucfirst($comment_reply['user']['fname']).' '.ucfirst($comment_reply['user']['lname']);?></a>   
															<div class="comment_text" id="text_<?= $comment_reply['_id']?>"><?php echo $comment_reply['comment']?></div> 
															<div id="comment-like-<?php echo $comment_reply['_id']?>" class="comment-like">
																<a href="javascript:void(0)"  id="comment_cls_<?php echo $comment_reply['_id']?>" onclick="like_comment('<?php echo $comment_reply['_id']?>')">
																<?php $status_comment = Like::getUserCommentLike($result['_id'],$comment_reply['_id']);
																	if($status_comment == '1' )
																   { ?>Unlike<?php  }else {?>Like<?php }  ?>
																</a>
																<span class="dot-span">·</span>
																   <a href="javascript:void(0)"  id="reply_comment<?php echo $init_comment['_id']?>" onclick="reply_comment('<?php echo $init_comment['_id']?>')">Reply</a>
																<div class="ago"><?php echo $comment_time?></div>
															</div>
                                                                                                                        <?php //echo in...?>
															<div class="action-comment">
                                                                                                                        <?php if(($userid != $post_user_id) && ($userid != $comment_reply['user']['_id'])){?>
																<div class="own_comment">
																	<a href="javascript:void(0)" class="close-comment"  onclick="<?= $bfun_post?>('<?php echo $comment_reply['_id']?>')" title="<?=$btool_post?>">
																		<i class="fa fa-close"></i>
																	</a>
																</div>
                                                                                                                            <?php } else {?>
																<div class="other_comment">
                                                                                                                                    <div class="tarrow tarrow1">									
																		<a class="alink alink1" href="javascript:void(0)" class="close-comment" title="<?=$atool_post?>">
																			<i class="fa fa-close"></i>
																		</a>
																		<div class="drawer">
																			<div class="opt-box">
																				<ul class="siconopt-ul">
                                                                                                                                                                    <?php if($userid == $comment_reply['user']['_id']){?>
																					<li>
																						<a href="javascript:void(0)" onclick="replace('<?= $comment_reply['_id']?>','<?= $comment_reply['comment']?>')">Edit..</a>
																					</li>
                                                                                                                                                                    <?php }?>
																					<li>
																						<a href="javascript:void(0)" onclick="<?= $bfun_post?>('<?php echo $comment_reply['_id']?>')">Delete</a>
																					</li>                                                                          
																				</ul>
																			</div>
																		</div>
																	</div>
																</div>
																<?php }?>
															</div>
														</div>
                                                

                                                    </div>
                                                
                                            </div>
                                                                    <?php
                                        } }?>
										</div>
										<?php
                                    }
                       ?></div>
                                    
                                    <div class="add_reply" id="display_reply_<?php echo $init_comment['_id'];?>" style="display:none">
						<div class="ac_img" id="user_img_<?php echo $post['_id'];?>"> 
							<a class="profilelink no-ul" href="<?php $id =  $result['_id']; echo Url::to(['userwall/index', 'id' => "$id"]); ?>">
								<img  width="35" height="35" alt="user-photo" class="img-responsive" src="<?php if($result['fb_id'] == ''  && $result['photo'] == ''){ echo 'profile/'.$result['gender'].'.jpg'; }else if($result['fb_id'] == ''){ echo 'profile/thumb_'.$result['photo']; }else{ if(substr($result['photo'],0,4) == 'http') echo $result['photo']; else echo 'profile/'.$result['photo']; } ?>"> 
							</a>
						</div>
						
                                          <form name="imageReplyForm" id="imageReplyForm" enctype="multipart/form-data">
						<div class="ac_ttarea">
							<textarea name="reply_txt" data-postid="<?php echo $post['_id'];?>" data-commentid="<?php echo $init_comment['_id']?>" id="reply_txt_<?php echo $init_comment['_id'];?>" placeholder="Write a Reply..." class="reply_class"></textarea>
							<div id="image-holder-reply-<?php echo $init_comment['_id'];?>"  class="comment-imgpreview"><img src="" id="replyimg_<?php echo $init_comment['_id'];?>" /><a href="javascript:void(0)" onClick="close_cimg_preview('<?php echo $init_comment['_id'];?>','reply')"><i class="fa fa-close"></i></a></div>			
                                                       <div class="myLabel">
                                                        
                                                            <!-- uplaod image -->

                                                            <input type="file" multiple="true" required="" name="imageReply[]" id="imageReply_<?php echo $init_comment['_id'];?>" class="imgReply" data-commentid="<?php echo $init_comment['_id']?>" data-postid="<?php echo $init_comment['_id'];?>">

	<!--<span class="glyphicon glyphicon-camera tb-icon-fix001" aria-hidden="true"></span>--> </div>
						</div>
                                         </form>
					</div>
                            </div>
							
                                                
                    </div>

                        <?php }
                     } 
                     }
                   
        //echo 'ctr'.count($loaded_comments);
        
        
    }
    
    function comment_html($post_id,$comment_id)
    {
        $session = Yii::$app->session;
        $post_id = $_POST['post_id'];
        $uid = $userid= $session->get('user_id');
        $init_comment['_id'] = $comment_id;
        $post = PostForm::find()->where(['_id' => $post_id])->one();
        $init_comment =  Comment::find()->with('user')->with('post')->where(['_id' => "$comment_id",'status' => '1'])->one();
        $result = LoginForm::find()->where(['_id' => $uid])->one();
        $comment_time = Yii::$app->EphocTime->time_elapsed_A(time(),$init_comment['created_date']);  
       //  $comments = Comment::find()->where(['post_id' => "$post_id" ,'status' => '1'])->all();
        $comments = Comment::find()->with('user')->with('post')->where(['post_id' => "$post_id",'status' => '1','parent_comment_id'=>'0'])->orderBy(['created_date'=>SORT_DESC])->all();
           if(($userid == $post['post_user_id']) || ($userid == $init_comment['user']['_id']))
            {
                $afun_post = 'delete_post_comment';
                $atool_post = 'Delete';
            }
            else
            {
                $afun_post = 'hide_post_comment';
                $atool_post = 'Hide';
            }
        ?>
               <div id="cwrapper_<?php echo $init_comment['_id']?>" class="outer">
                         <div class="inner_avatar" id="avatar">
                            <?php
                            $init_comment_image = $this->getimage($init_comment['user']['_id'],'thumb');
                            ?>
                            <img src="<?= $init_comment_image?>" class="img-responsive" height="35" width="35" alt="">
                           </div>
                            <div class="inner_desc comment_wrapper_<?php echo $init_comment['_id'];?>" id="comment-holder">
                                <div style="display:none" class="edit_textarea" id="edit_textarea_<?php echo $init_comment['_id'];?>"><textarea id="edit_textarea_value_<?php echo $init_comment['_id'];?>"><?php echo $init_comment['comment'];?></textarea><a href="javascript:void(0)" onclick="closeedit('<?php echo $init_comment['_id'];?>')">Cancel</a></div>
								<div class="thisComment" id="editcomment_<?php echo $init_comment['_id'];?>">
									<a href="<?php $id = $init_comment['user']['_id']; echo Url::to(['userwall/index', 'id' => "$id"]); ?>"><?php echo ucfirst($init_comment['user']['fname']).' '.ucfirst($init_comment['user']['lname']);?></a>   
										<div class="comment_text" id="text_<?= $init_comment['_id']?>"><?php echo $init_comment['comment']?></div> 
                                                                                
<?php if($init_comment['image'] != ''){?>
<img src="<?= Yii::$app->getUrlManager()->getBaseUrl() ?><?php echo $init_comment['image'];?>" class="img-responsive comment-photo" alt="comment-photo">
<?php }?>
		
									<div id="comment-like-<?php echo $init_comment['_id']?>" class="comment-like">
										<a href="javascript:void(0)"  id="comment_cls_<?php echo $init_comment['_id']?>" onclick="like_comment('<?php echo $init_comment['_id']?>')">
										<?php $status_comment = Like::getUserCommentLike($result['_id'],$init_comment['_id']);
											if($status_comment == '1' )
										   { ?>Unlike<?php  }else {?>Like<?php }  ?>
										</a>
										<span class="dot-span">·</span>
										 <a href="javascript:void(0)"  id="reply_comment<?php echo $init_comment['_id']?>" onclick="reply_comment('<?php echo $init_comment['_id']?>')">Reply</a>
										
										
										<div class="ago"><?php echo $comment_time?></div>
									</div>
									<div class="action-comment">
									<?php if(($userid != $post['post_user_id']) && ($userid != $init_comment['user']['_id'])){?>
									<div class="own_comment">
										<a href="javascript:void(0)" class="close-comment"  onclick="<?= $afun_post?>('<?php echo $init_comment['_id']?>')" title="<?=$atool_post?>">
											<i class="fa fa-close"></i>
										</a>
									</div>
                                                                        <?php } else {?>
									<div class="other_comment">
										<div class="tarrow tarrow_<?php echo $comment_id;?>">									
											<a class="alink alink_<?php echo $comment_id;?>" href="javascript:void(0)" class="close-comment" title="<?=$atool_post?>">
												<i class="fa fa-close"></i>
											</a>
											<div class="drawer">
												<div class="opt-box">
													<ul class="siconopt-ul">
                                                                                                                <?php if($userid == $init_comment['user']['_id']){?>
														<li>
															<a href="javascript:void(0)" onclick="replace('<?= $init_comment['_id']?>','<?= $init_comment['comment']?>')">Edit..</a>
														</li>
                                                                                                                <?php }?>
														<li>
															<a href="javascript:void(0)" onclick="<?= $afun_post?>('<?php echo $init_comment['_id']?>')">Delete</a>
														</li>                                                                          
													</ul>
												</div>
											</div>
										</div>
									</div>
                                                                        <?php }?>
								</div>
							</div>
								

                       <?php $comment_replies =Comment::getCommentReply($init_comment['_id']); 
                                    //echo '<pre>';print_r($comment_replies);
                                    
                                    ?>
                                        <div class="reply_comments reply_comments_<?php echo $init_comment['_id'];?>"><?php
                                    if(!empty($comment_replies))
                                    {
			?>
                                    
<?php
                                    foreach($comment_replies AS $comment_reply)
                                    {
                                        $hidecomment = new HideComment();
                                        $hidecomment = HideComment::find()->where(['user_id' => (string)$user_id])->one();
                                        $hide_comment_ids = explode(',',$hidecomment['comment_ids']); 
                                        if(!(in_array($comment_reply['_id'],$hide_comment_ids)))
                                        {
                                            if(($userid == $post['post_user_id']) || ($userid == $comment_reply['user']['_id']))
                                            {
                                                $bfun_post = 'delete_post_comment';
                                                $btool_post = 'Delete';
                                            }
                                            else
                                            {
                                                $bfun_post = 'hide_post_comment';
                                                $btool_post = 'Hide';
                                            }
                                                $comment_time = Yii::$app->EphocTime->time_elapsed_A(time(),$comment_reply['updated_date']);
                                                    ?>

                                            <div id="cwrapper_<?php echo $comment_reply['_id']?>" class="outer comment_outer">
                                                 <div class="inner_avatar" id="avatar">
                                                        <a href="<?php $id = $comment_reply['user']['_id']; echo Url::to(['userwall/index', 'id' => "$id"]); ?>">
                                                     <?php
                                                    $comment_image = $this->getimage($comment_reply['user']['_id'],'thumb');
                                                    ?>
                                                    <img src="<?= $comment_image?>" class="img-responsive" height="35" width="35" alt="">
                                                   </a></div>

                                                    <div class="inner_desc comment_wrapper_<?php echo $comment_reply['_id'];?> comment_<?php echo $init_comment['_id']?>" id="comment-holder">
                                                        <div style="display:none" class="edit_textarea" id="edit_textarea_<?php echo $comment_reply['_id'];?>"><textarea id="edit_textarea_value_<?php echo $comment_reply['_id'];?>"><?php echo $comment_reply['comment'];?></textarea><a href="javascript:void(0)" onclick="closeedit('<?php echo $comment_reply['_id'];?>')">Cancel</a></div>
														<div class="thisComment" id="editcomment_<?php echo $comment_reply['_id'];?>">
															<a href="<?php $id = $comment_reply['user']['_id']; echo Url::to(['userwall/index', 'id' => "$id"]); ?>"><?php echo ucfirst($comment_reply['user']['fname']).' '.ucfirst($comment_reply['user']['lname']);?></a>   
															<div class="comment_text" id="text_<?= $comment_reply['_id']?>"><?php echo $comment_reply['comment']?></div> 
															<div id="comment-like-<?php echo $comment_reply['_id']?>" class="comment-like">
																<a href="javascript:void(0)"  id="comment_cls_<?php echo $comment_reply['_id']?>" onclick="like_comment('<?php echo $comment_reply['_id']?>')">
																<?php $status_comment = Like::getUserCommentLike($result['_id'],$comment_reply['_id']);
																	if($status_comment == '1' )
																   { ?>Unlike<?php  }else {?>Like<?php }  ?>
																</a>
																<span class="dot-span">·</span>
																   <a href="javascript:void(0)"  id="reply_comment<?php echo $init_comment['_id']?>" onclick="reply_comment('<?php echo $init_comment['_id']?>')">Reply</a>
																<div class="ago"><?php echo $comment_time?></div>
															</div>
															<div class="action-comment">
                                                                                                                        <?php if(($userid != $post['post_user_id']) && ($userid != $comment_reply['user']['_id'])){?>
																<div class="own_comment">
																	<a href="javascript:void(0)" class="close-comment"  onclick="<?= $bfun_post?>('<?php echo $comment_reply['_id']?>')" title="<?=$btool_post?>">
																		<i class="fa fa-close"></i>
																	</a>
																</div>
                                                                                                                            <?php } else {?>
																<div class="other_comment">
                                                                                                                                    <div class="tarrow tarrow1">									
																		<a class="alink alink1" href="javascript:void(0)" class="close-comment" title="<?=$atool_post?>">
																			<i class="fa fa-close"></i>
																		</a>
																		<div class="drawer">
																			<div class="opt-box">
																				<ul class="siconopt-ul">
                                                                                                                                                                    <?php if($userid == $comment_reply['user']['_id']){?>
																					<li>
																						<a href="javascript:void(0)" onclick="replace('<?= $comment_reply['_id']?>','<?= $comment_reply['comment']?>')">Edit..</a>
																					</li>
                                                                                                                                                                    <?php }?>
																					<li>
																						<a href="javascript:void(0)" onclick="<?= $bfun_post?>('<?php echo $comment_reply['_id']?>')">Delete</a>
																					</li>                                                                          
																				</ul>
																			</div>
																		</div>
																	</div>
																</div>
																<?php }?>
															</div>
														</div>
                                                

                                                    </div>
                                                
                                            </div>
                                                                    <?php
                                        } }?><?php
                                    }
                       ?></div>
                                    
                                    <div class="add_reply" id="display_reply_<?php echo $init_comment['_id'];?>" style="display:none">
						<div class="ac_img" id="user_img_<?php echo $post['_id'];?>"> 
							<a class="profilelink no-ul" href="<?php $id =  $result['_id']; echo Url::to(['userwall/index', 'id' => "$id"]); ?>">
								<?php
                                                                $comment_image = $this->getimage($result['_id'],'thumb');
                                                                ?>
                                                                <img src="<?= $comment_image?>" class="img-responsive" height="35" width="35" alt="">
							</a>
						</div>
                                          <form name="imageReplyForm" id="imageReplyForm" enctype="multipart/form-data">
						<div class="ac_ttarea">
							<textarea name="reply_txt" data-postid="<?php echo $post['_id'];?>" data-commentid="<?php echo $init_comment['_id']?>" id="reply_txt_<?php echo $init_comment['_id'];?>" placeholder="Write a Reply..." class="reply_class"></textarea>
							<div id="image-holder-reply-<?php echo $init_comment['_id'];?>" class="comment-imgpreview" ><img src="" id="replyimg_<?php echo $init_comment['_id'];?>"/><a href="javascript:void(0)" onClick="close_cimg_preview('<?php echo $init_comment['_id'];?>','reply')"><i class="fa fa-close"></i></a></div>			
                                                       <div class="myLabel">
                                                        
                                                            <!-- uplaod image -->

                                                            <input type="file" multiple="true" required="" name="imageReply[]" id="imageReply_<?php echo $init_comment['_id'];?>" class="imgReply" data-commentid="<?php echo $init_comment['_id']?>" data-postid="<?php echo $init_comment['_id'];?>">

	<!--<span class="glyphicon glyphicon-camera tb-icon-fix001" aria-hidden="true"></span>--> </div>
						</div>
                                         </form>
						
                                         
					</div>
                            </div>
							
                          <input type="hidden" id="comment_count_<?php echo (string)$init_comment['post']['_id']; ?>" name="comment_count" value="<?php echo count($comments); ?>"/>
                          <input type="hidden" id="last_comment_id" name="last_comment_id" value="<?php echo (string)$init_comment['_id']; ?>"/>
                    </div>
        <?php
    }
    
    /**
    * Ajax call - edit comment to the post 
    * @param  post param 
    * @return html data
    * @Author Alap Shah
    * @Date   18-04-2016 (dd-mm-yyyy)
    */  

   public function actionEditReplyComment()
   {
        $comment_id = (string)$_POST['comment_id'];
        $edit_comment = str_replace(array("\n","\r\n","\r"), '', $_POST['edit_comment']);
        if(!empty($comment_id) && isset($comment_id) && !empty($edit_comment) && isset($edit_comment))
        {
                $session = Yii::$app->session;
                $user_id = (string)$session->get('user_id');
                $date = time();
                $model = new Comment();
                $query = Comment::find()->where(['_id' => $comment_id, 'user_id' => $user_id])->one();
                if($query)
                {
                        $editcomment = new Comment();
                        $editcomment = Comment::find()->where(['_id' => $comment_id, 'user_id' => $user_id])->one();

                        $editcomment->comment = $edit_comment;
                        $editcomment->updated_date = $date;
                        if($editcomment->update())
                        {
                            print true;
                        }
                        else
                        {
                            print false;
                        }
                }
                else
                {
                    print false;
                }
        }
        else
        {
            print false;
        }
   }
}
