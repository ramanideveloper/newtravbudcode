<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */
 
namespace yii\web; 

use Yii;
use yii\base\InlineAction;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use frontend\models\BookmarkForm;
use frontend\models\UnfollowFriend;
use frontend\models\MuteFriend;
use frontend\models\SavePost;
use frontend\models\ReportPost; 
use frontend\models\CountryCode;
use frontend\models\Comment;
use frontend\models\HideComment;
use frontend\models\HidePost;
use frontend\models\Like;
use frontend\models\Notification;
use frontend\models\Language;
use frontend\models\Education;
use frontend\models\Interests;
use frontend\models\Occupation;
use frontend\models\UserForm;
use frontend\models\LoginForm;
use frontend\models\UserSetting;
use frontend\models\Personalinfo;
use frontend\models\SecuritySetting;
use frontend\models\NotificationSetting;
use frontend\models\PostForm;
use frontend\models\Friend;
/**
 * Controller is the base class of web controllers.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class Controller extends \yii\base\Controller
{
    /**
     * @var boolean whether to enable CSRF validation for the actions in this controller.
     * CSRF validation is enabled only when both this property and [[Request::enableCsrfValidation]] are true.
     */
    public $enableCsrfValidation = true;
    /**
     * @var array the parameters bound to the current action.
     */
    public $actionParams = [];


    /**
     * Renders a view in response to an AJAX request.
     *
     * This method is similar to [[renderPartial()]] except that it will inject into
     * the rendering result with JS/CSS scripts and files which are registered with the view.
     * For this reason, you should use this method instead of [[renderPartial()]] to render
     * a view to respond to an AJAX request.
     *
     * @param string $view the view name. Please refer to [[render()]] on how to specify a view name.
     * @param array $params the parameters (name-value pairs) that should be made available in the view.
     * @return string the rendering result.
     */
    public function renderAjax($view, $params = [])
    {
        return $this->getView()->renderAjax($view, $params, $this);
    }

    /**
     * Binds the parameters to the action.
     * This method is invoked by [[\yii\base\Action]] when it begins to run with the given parameters.
     * This method will check the parameter names that the action requires and return
     * the provided parameters according to the requirement. If there is any missing parameter,
     * an exception will be thrown.
     * @param \yii\base\Action $action the action to be bound with parameters
     * @param array $params the parameters to be bound to the action
     * @return array the valid parameters that the action can run with.
     * @throws BadRequestHttpException if there are missing or invalid parameters.
     */
    public function bindActionParams($action, $params)
    {
        if ($action instanceof InlineAction) {
            $method = new \ReflectionMethod($this, $action->actionMethod);
        } else {
            $method = new \ReflectionMethod($action, 'run');
        }

        $args = [];
        $missing = [];
        $actionParams = [];
        foreach ($method->getParameters() as $param) {
            $name = $param->getName();
            if (array_key_exists($name, $params)) {
                if ($param->isArray()) {
                    $args[] = $actionParams[$name] = (array) $params[$name];
                } elseif (!is_array($params[$name])) {
                    $args[] = $actionParams[$name] = $params[$name];
                } else {
                    throw new BadRequestHttpException(Yii::t('yii', 'Invalid data received for parameter "{param}".', [
                        'param' => $name,
                    ]));
                }
                unset($params[$name]);
            } elseif ($param->isDefaultValueAvailable()) {
                $args[] = $actionParams[$name] = $param->getDefaultValue();
            } else {
                $missing[] = $name;
            }
        }

        if (!empty($missing)) {
            throw new BadRequestHttpException(Yii::t('yii', 'Missing required parameters: {params}', [
                'params' => implode(', ', $missing),
            ]));
        }

        $this->actionParams = $actionParams;

        return $args;
    }

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            if ($this->enableCsrfValidation && Yii::$app->getErrorHandler()->exception === null && !Yii::$app->getRequest()->validateCsrfToken()) {
                throw new BadRequestHttpException(Yii::t('yii', 'Unable to verify your data submission.'));
            }
            return true;
        }
        
        return false;
    }

    /**
     * Redirects the browser to the specified URL.
     * This method is a shortcut to [[Response::redirect()]].
     *
     * You can use it in an action by returning the [[Response]] directly:
     *
     * ```php
     * // stop executing this action and redirect to login page
     * return $this->redirect(['login']);
     * ```
     *
     * @param string|array $url the URL to be redirected to. This can be in one of the following formats:
     *
     * - a string representing a URL (e.g. "http://example.com")
     * - a string representing a URL alias (e.g. "@example.com")
     * - an array in the format of `[$route, ...name-value pairs...]` (e.g. `['site/index', 'ref' => 1]`)
     *   [[Url::to()]] will be used to convert the array into a URL.
     *
     * Any relative URL will be converted into an absolute one by prepending it with the host info
     * of the current request.
     *
     * @param integer $statusCode the HTTP status code. Defaults to 302.
     * See <http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html>
     * for details about HTTP status code
     * @return Response the current response object
     */
    public function redirect($url, $statusCode = 302)
    {
        return Yii::$app->getResponse()->redirect(Url::to($url), $statusCode);
    }

    /**
     * Redirects the browser to the home page.
     *
     * You can use this method in an action by returning the [[Response]] directly:
     *
     * ```php
     * // stop executing this action and redirect to home page
     * return $this->goHome();
     * ```
     *
     * @return Response the current response object
     */
    public function goHome()
    {
        return Yii::$app->getResponse()->redirect(Yii::$app->getHomeUrl());
    }

    /**
     * Redirects the browser to the last visited page.
     *
     * You can use this method in an action by returning the [[Response]] directly:
     *
     * ```php
     * // stop executing this action and redirect to last visited page
     * return $this->goBack();
     * ```
     *
     * For this function to work you have to [[User::setReturnUrl()|set the return URL]] in appropriate places before.
     *
     * @param string|array $defaultUrl the default return URL in case it was not set previously.
     * If this is null and the return URL was not set previously, [[Application::homeUrl]] will be redirected to.
     * Please refer to [[User::setReturnUrl()]] on accepted format of the URL.
     * @return Response the current response object
     * @see User::getReturnUrl()
     */
    public function goBack($defaultUrl = null)
    {
        return Yii::$app->getResponse()->redirect(Yii::$app->getUser()->getReturnUrl($defaultUrl));
    }

    /**
     * Refreshes the current page.
     * This method is a shortcut to [[Response::refresh()]].
     *
     * You can use it in an action by returning the [[Response]] directly:
     *
     * ```php
     * // stop executing this action and refresh the current page
     * return $this->refresh();
     * ```
     *
     * @param string $anchor the anchor that should be appended to the redirection URL.
     * Defaults to empty. Make sure the anchor starts with '#' if you want to specify it.
     * @return Response the response object itself
     */
    public function refresh($anchor = '')
    {
        return Yii::$app->getResponse()->redirect(Yii::$app->getRequest()->getUrl() . $anchor);
    }
    
    public function getimage($userid,$type)
    {
        $resultimg = LoginForm::find()->where(['_id' => $userid])->one();
        if(substr($resultimg['photo'],0,4) == 'http')
        {
            if($type == 'photo')
            {
                $dp = $resultimg['photo'];
            }
            else
            {
                $dp = $resultimg['thumbnail'];
            }
        }
        else
        {
            if(isset($resultimg['thumbnail']) && !empty($resultimg['thumbnail']) && file_exists('profile/'.$resultimg['thumbnail']))
            {
                $dp = "profile/".$resultimg['thumbnail'];
            }
            else
            {
                $dp = "profile/".$resultimg['gender'].'.jpg';
            }
        }
        return $dp;
    }
    
    protected function getimagefilename($image)
    {
        $imgs = explode('/',$image);
        $img = explode('/',$imgs[2]);
        $name = explode('.',$img[0]);
        return $name[0];
    }
    protected function view_post_id($postid)
    {
        //echo $postid;
        $session = Yii::$app->session;
        $userid = $user_id = $session->get('user_id');
        $post = PostForm::find()->where(['_id' => $postid])->one();
        $originalpost = PostForm::find()->where(['parent_post_id' => $postid])->one();
        $time = Yii::$app->EphocTime->time_elapsed_A(time(),$post['post_created_date']);
        $post_privacy = $post['post_privacy'];
        if($post_privacy == 'Private') {$post_class = 'lock';}
        else if($post_privacy == 'Friends') {$post_class = 'user';}
        else {$post_privacy = 'Public'; $post_class = 'globe';}
        ?>
		<div class="shared-post">
			<div class="tb-panel-body01">
					<div class="tb-panel-head clearfix">
					   <?php
							$lmodel = new \frontend\models\LoginForm();
							$getsharename = LoginForm::find()->where(['_id' => $post['shared_from']])->one();
							$share_type = $post['post_type'];
							if($share_type == 'text' || $share_type == 'link' || $share_type == 'profilepic')
							{
									$sharetype = 'Post';
							}
							else
							{
									$sharetype = 'Photo';
							}
							$getshareid = $getsharename['_id'];
							$link = Url::to(['userwall/index', 'id' => $post['post_user_id']]);
							$getuserinfo = LoginForm::find()->where(['_id' => $post['post_user_id']])->one();
							$personalinfo = Personalinfo::find()->where(['user_id' => $post['post_user_id']])->one();
							?>
								<div class="tb-user-box">
									<a class="profilelink no-ul" href="<?php $id =  $post['user']['_id']; echo Url::to(['userwall/index', 'id' => "$id"]); ?>">
										<?php 
                                                                                $dpimg = $this->getimage($post['user']['_id'],'thumb');
										?>
										<?php /* <img onMouseOver="show('<?= $profile_tip_counter?>')" onMouseOut="hide('<?= $profile_tip_counter?>')" alt="user-photo" class="img-responsive" src="<?= $dpimg?>"> */ ?>
										<img alt="user-photo" class="img-responsive" src="<?= $dpimg?>"> 
									</a>
								</div>
							<div class="tb-user-desc">
					  <?php 

                       // START add tab content in post
                    $posttag = '';
                    if(isset($post['post_tags']) && !empty($post['post_tags'])) {
                      $posttag = explode(",", $post['post_tags']);
                    }

                    $taginfomatiom = ArrayHelper::map(UserForm::find()->where(['IN', '_id',  $posttag])->all(), 'fullname', (string)'_id');

                    $nkTag = array();
                    $nvTag = array();
                    $i=1;

                    $content = array();
                    foreach ($taginfomatiom as $key => $value) {
                        $nvTag[] = $key;
                        $nkTag[] = (string)$value; 
                        if($i != 1) {
                            $content[] = $value;
                        }
                        $i++;
                    }
                    
                    if(isset($content) && !empty($content)) {
                        $content = implode("<br/>", $nvTag); 
                    }

                    $tagstr = '';
                    if(!empty($taginfomatiom)) {
                        if(count($taginfomatiom) > 1) {
                             $tagstr =  " with <a href=".Url::to(['userwall/index', 'id' => $nkTag[0]]) .">" . $nvTag[0] . " </a> and <a href='#' class='show-pop right-bottom' data-placement='right-bottom' data-content='".$content."'>" . (count($nkTag) - 1) . " others</a>";
                        } else {
                            $tagstr =  " with <a href=".Url::to(['userwall/index', 'id' => $nkTag[0]]) .">" . $nvTag[0] . "</a>";
                        }
                    }
                    // END add tab content in post

							if(isset($post['currentlocation']) && !empty($post['currentlocation']))
							{ $location =  $tagstr  . ' at '.$post['currentlocation'].'.'; }
							else{ $location =  $tagstr . ''; }
							if($post['is_timeline']=='1'){?>
			<!--                    <span><?php //echo $sharedetails;?> &gt; <a class="profilelink no-ul" href="<?php //$id =  $post['user']['_id']; echo Url::to(['userwall/index', 'id' => "$id"]); ?>"><?php //echo ucfirst($post['user']['fname']).' '.ucfirst($post['user']['lname']) ;?></a></span>-->
							<span><a href="<?php $id =  $post['user']['_id']; echo Url::to(['userwall/index', 'id' => "$id"]); ?>"><?php echo ucfirst($post['user']['fname']).' '.ucfirst($post['user']['lname']) ;?></a></span>
					  <?php }
					  else if($post['is_album']=='1'){?>
							<span><a href="<?php $id =  $post['user']['_id']; echo Url::to(['userwall/index', 'id' => "$id"]); ?>"><?php echo ucfirst($post['user']['fname']).' '.ucfirst($post['user']['lname']) ;?></a> added album <a href="javascript:void(0)"><?= $post['album_title']?></a>.</span>
							<?php }
							else if($post['post_type']=='profilepic'){?>
							<span><a href="<?php $id =  $post['user']['_id']; echo Url::to(['userwall/index', 'id' => "$id"]); ?>"><?php echo ucfirst($post['user']['fname']).' '.ucfirst($post['user']['lname']) ;?></a> updated profile picture.</span>
							<?php }
                                                        else if($post['is_coverpic']=='1'){?>
                                                        <span><a href="<?php $id =  $post['user']['_id']; echo Url::to(['userwall/index', 'id' => "$id"]); ?>"><?php echo ucfirst($post['user']['fname']).' '.ucfirst($post['user']['lname']) ;?></a> updated cover picture.</span>
                                                        <?php }
							else{?>
							<span><a href="<?php $id =  $post['user']['_id']; echo Url::to(['userwall/index', 'id' => "$id"]); ?>"><?php echo ucfirst($post['user']['fname']).' '.ucfirst($post['user']['lname']) ;?></a><?php if(isset($post['shared_from']) && !empty($post['shared_from'])){echo $sharedetails;}?><?= $location?></span>
					  <?php }?>
							<span><?php echo $time; ?> <i class="glyphicon glyphicon-<?= $post_class?> "></i></span>
					   </div>
					</div>
					<?php
							if(isset($post['image']) && !empty($post['image'])){
									$eximgs = explode(',',$post['image'],-1);
									/*$picsize = '';
									foreach ($eximgs as $eximg)
									{
											$val = getimagesize('../web'.$eximg);
											$picsize .= $val[0] .'x'. $val[1] .', ';
									}*/
									//echo $picsize;
							}
					  ?>
                            <?php if($post['post_title'] != null) { ?>
                                <div class="tb-text-post post-title moretext" id="temp" name="temp" ><?= $post['post_title'] ?></div>
                            <?php }?>
					  <?php if($post['post_type'] == 'image' && $post['is_coverpic'] == null) {
							$cnt = 1;
						$eximgs = explode(',',$post['image'],-1);
						$totalimgs = count($eximgs);
                                                $imgcountcls="";
						if($totalimgs == '1'){$imgcountcls = 'one-img';}
						if($totalimgs == '2'){$imgcountcls = 'two-img';}
						if($totalimgs == '3'){$imgcountcls = 'three-img';}
						if($totalimgs == '4'){$imgcountcls = 'four-img';}
						if($totalimgs == '5'){$imgcountcls = 'five-img';}
						if($totalimgs > '5'){$imgcountcls = 'more-img';}
                                        ?>
<!--                                    <div class="tb-text-post" id="temp" name="temp" ><?= $post['post_text'] ?></div>-->
                                   <div class="tb-img-post" id="temp" name="temp">
						<div class="pimg-holder <?= $imgcountcls?> gallery">
							<?php foreach ($eximgs as $eximg) {
								if($cnt < 6){
                                if (file_exists('../web'.$eximg)) {    
    							$picsize = '';
    							$val = getimagesize('../web'.$eximg);
    							$picsize .= $val[0] .'x'. $val[1] .', ';
    							if($val[0] > $val[1]){$imgclass = 'himg';}else if($val[1] > $val[0]){$imgclass = 'vimg';}else{$imgclass = 'simg';}?>
    							<div class="pimg-box <?= $imgclass?>-box"><a href="<?= Yii::$app->getUrlManager()->getBaseUrl() ?><?= $eximg ?>" rel="prettyPhoto[gallery<?=$originalpost['_id']?>]"><img class="<?= $imgclass?>" src="<?= Yii::$app->getUrlManager()->getBaseUrl() ?><?= $eximg ?>" alt=""></a>
    							<?php if($cnt > 4){?>
    								<div class="more-box"><a href="#"><i class="fa fa-plus"></i><?= $totalimgs - $cnt +1;?></a></div>
    							<?php }?>
    							</div>
								<?php } } $cnt++;}?>
						</div>		
					</div>

					  <?php }  else if($post['post_type'] == 'text') { ?>
					  <?php if($post['post_text'] != '') {?>
					  <div class="tb-text-post moretext" id="temp" name="temp" ><?= $post['post_text'] ?></div><?php }?>
					   <?php } else if($post['post_type'] == 'link') { ?>						
							<?php /*
							<a href="<?= $post['post_text']?>" target="_blank"><div class="tb-img-post" id="temp" name="temp"><img src="<?= $post['image'] ?>" alt=""></div>
							<div class="tb-text-post" id="temp" name="temp" ><?= $post['link_title'] ?></div>
							<div class="tb-text-post" id="temp" name="temp" ><?= $post['link_description'] ?></div>
							</a>
							*/ ?>
							<div class="post-linkinfo">
								<div class="linkinfo-holder">
									<div class="previewImage">
										<a href="<?= $post['post_text']?>" target="_blank">
											<img src="<?= $post['image'] ?>" alt="">
										</a>
									</div>
									<div class="previewDesc">
										<div class="previewLoading">
											<a href="<?= $post['post_text']?>" target="_blank">
												<?= $post['link_title'] ?>
											</a>
										</div>
                                                                                <div class="tb-text-post moretext" id="temp" name="temp" ><?= $post['link_description'] ?></div>
									</div>
								</div>
							</div>
					  <?php } 
					 else if($post['post_type'] == 'text and image') { 
							$cnt = 1;
						$eximgs = explode(',',$post['image'],-1);
						$totalimgs = count($eximgs);
						if($totalimgs == '1'){$imgcountcls = 'one-img';}
						if($totalimgs == '2'){$imgcountcls = 'two-img';}
						if($totalimgs == '3'){$imgcountcls = 'three-img';}
						if($totalimgs == '4'){$imgcountcls = 'four-img';}
						if($totalimgs == '5'){$imgcountcls = 'five-img';}
						if($totalimgs > '5'){$imgcountcls = 'more-img';}
                                                ?>
                                                <div class="tb-text-post moretext" id="temp" name="temp" ><?= $post['post_text'] ?></div>

                                               <div class="tb-img-post" id="temp" name="temp">
                                                                            <div class="pimg-holder <?= $imgcountcls?> gallery">
                                                                                    <?php foreach ($eximgs as $eximg) {
                                                                                            if($cnt < 6){
                                                                                                if (file_exists('../web'.$eximg)) {  
                                                                                    $picsize = '';
                                                                                    $val = getimagesize('../web'.$eximg);
                                                                                    $picsize .= $val[0] .'x'. $val[1] .', ';
                                                                                    if($val[0] > $val[1]){$imgclass = 'himg';}else if($val[1] > $val[0]){$imgclass = 'vimg';}else{$imgclass = 'simg';}?>
                                                                                    <div class="pimg-box <?= $imgclass?>-box"><a href="<?= Yii::$app->getUrlManager()->getBaseUrl() ?><?= $eximg ?>" rel="prettyPhoto[gallery<?=$originalpost['_id']?>]"><img class="<?= $imgclass?>" src="<?= Yii::$app->getUrlManager()->getBaseUrl() ?><?= $eximg ?>" alt=""></a>
                                                                                    <?php if($cnt > 4){?>
                                                                                            <div class="more-box"><a href="#"><i class="fa fa-plus"></i><?= $totalimgs - $cnt +1;?></a></div>
                                                                                    <?php }?>
                                                                                    </div>
                                                                                            <?php } } $cnt++;}?>
                                                                            </div>		
                                                                    </div>
					  <?php }
					  else if($post['post_type'] == 'profilepic') { ?>
					  <div class="tb-img-post" id="temp" name="temp">
                                            <div class="pimg-holder one-img gallery">
                                            <?php
                                            if (file_exists('profile/'.$post['image'])) {  
                                            $picsize = '';
                                            $val = getimagesize('profile/'.$post['image']);
                                            $picsize .= $val[0] .'x'. $val[1] .', ';
                                            if($val[0] > $val[1]){$imgclass = 'himg';}else if($val[1] > $val[0]){$imgclass = 'vimg';}else{$imgclass = 'simg';}?>
                                                <div class="pimg-box <?= $imgclass?>-box">
                                                    <a href="<?= Yii::$app->getUrlManager()->getBaseUrl() ?><?= '/profile/'.$post['image'] ?>" rel="prettyPhoto[gallery<?=$post['_id']?>]">
                                                        <img class="<?= $imgclass?>" src="<?= Yii::$app->getUrlManager()->getBaseUrl() ?><?= '/profile/'.$post['image'] ?>" alt="">
                                                    </a>
                                                </div>
                                            <?php } ?>
                                            </div>
                                        </div>
					<?php }
                                        else if($post['is_coverpic'] == '1') { ?>
					  <div class="tb-img-post" id="temp" name="temp">
                                            <div class="pimg-holder one-img gallery">
                                            <?php
                                            if (file_exists('profile/'.$post['image'])) { 
                                            $picsize = '';
                                            $val = getimagesize('profile/'.$post['image']);
                                            $picsize .= $val[0] .'x'. $val[1] .', ';
                                            if($val[0] > $val[1]){$imgclass = 'himg';}else if($val[1] > $val[0]){$imgclass = 'vimg';}else{$imgclass = 'simg';}?>
                                                <div class="pimg-box <?= $imgclass?>-box">
                                                    <a href="<?= Yii::$app->getUrlManager()->getBaseUrl() ?><?= '/profile/'.$post['image'] ?>" rel="prettyPhoto[gallery<?=$post['_id']?>]">
                                                        <img class="<?= $imgclass?>" src="<?= Yii::$app->getUrlManager()->getBaseUrl() ?><?= '/profile/'.$post['image'] ?>" alt="">
                                                    </a>
                                                </div>
                                                <?php } ?>
                                            </div>
                                        </div>
					<?php }
                                        ?>
			  </div>
		</div>
		<?php 
    }
    
    //public function display_last_post($last_insert_id,$existing_posts = '',$gallery_counter)
    public function display_last_post($last_insert_id,$existing_posts = '', $imagefullarray = '')
	
	
    {
        $session =
                Yii::$app->session;
        $userid =
                $user_id =
                $session->get('user_id');
        $result =
                LoginForm::find()->where(['_id' => $userid])->one();
        $date = time();
         $post =
                PostForm::find()->where(['_id' => $last_insert_id])->one();

        $reportpost = ReportPost::find()->where(['post_id' => (string)$last_insert_id,'reporter_id' => (string)$user_id])->one();

        $profile_tip_counter =   1;
        $result_security =
        SecuritySetting::find()->where(['user_id' => (string) $user_id])->one();
        if ($result_security) {
            $my_post_view_status =
                    $result_security['my_post_view_status'];
            if ($my_post_view_status == 'Private') {
                $post_dropdown_class =
                        'lock';
            } else if ($my_post_view_status == 'Friends') {
                $post_dropdown_class =
                        'user';
            } else {
                $my_post_view_status =
                        'Public';
                $post_dropdown_class =
                        'globe';
            }
        } else {
            $my_post_view_status =
                    'Public';
            $post_dropdown_class =
                    'globe';
        }
        if(!($reportpost))
        {
            if($post['post_privacy'] != 'Private' && !strchr($post['custom_notshare'],(string)$user_id) || (($post['post_privacy'] == 'Private') && ($user_id == $post['post_user_id'])))
            {
                $savestatus = new SavePost();
                $savestatus = SavePost::find()->where(['user_id' => (string)$user_id,'post_id' => (string)$post['_id']])->one();
                $savestatuscalue = $savestatus['is_saved'];
                if($savestatuscalue == '1'){$savevar = 'Unsave';}else{$savevar = 'Save';}

                $time = Yii::$app->EphocTime->time_elapsed_A(time(),$post['post_created_date']);
                $like_count = Like::getLikeCount((string)$post['_id']);
                $comments = Comment::getAllPostLike($post['_id']);
             //   echo "<pre>"; print_r($comments);die;

                $init_comments = Comment::getFirstThreePostComments($post['_id']);
                $post_privacy = $post['post_privacy'];
                if($post_privacy == 'Private') {$post_class = 'lock';}
                else if($post_privacy == 'Friends') {$post_class = 'user';}
                else {$post_privacy = 'Public'; $post_class = 'globe';}
        ?>
        <style>
        .pac-container
        {
            z-index: 1051 !important;
        }
        </style>
            <div class="panel-shadow tb-panel-box postbox post_user_id_<?php echo $post['post_user_id'];?>" id="hide_<?php echo $post['_id'];?>">
              <div class="tb-panel-body01 trounded">
                <div class="tb-panel-head clearfix">
                   <?php
                    $lmodel = new \frontend\models\LoginForm();
                    $getsharename = LoginForm::find()->where(['_id' => $post['shared_by']])->one();
                    if($post['is_timeline']=='1')
                    {
                        $getshareid = $getsharename['_id'];
                        $sharedetails = "";
                        $sharedetails .= "<a href='";
                        $sharedetails .= Url::to(['userwall/index', 'id' => "$getshareid"]);
                        $sharedetails .= "'>";
                        $sharedetails .= $getsharename['fname'].' '.$getsharename['lname'];
                        $sharedetails .= "</a>";
                        /*$share_type = $post['post_type'];
                        if($share_type == 'text' || $share_type == 'link' || $share_type == 'profilepic')
                        {
                            $sharetype = 'Post';
                        }
                        else
                        {
                            $sharetype = 'Photo';
                        }
                        $getshareid = $getsharename['_id'];
                        $sharedetails = "";
                        $sharedetails .= " Shared ";
                        $sharedetails .= "<a class='no-ul' href='";
                        $sharedetails .= Url::to(['userwall/index', 'id' => "$getshareid"]);
                        $sharedetails .= "'>";
                        $sharedetails .= $getsharename['fname'].' '.$getsharename['lname'];
                        $sharedetails .= "</a>'s $sharetype.";*/
                    }
                    else
                    {
                        $getshareid = $getsharename['_id'];
                        $sharedetails = "";
                        $sharedetails .= " Shared ";
                        $sharedetails .= "<a class='profilelink no-ul' href='";
                        $sharedetails .= Url::to(['userwall/index', 'id' => "$getshareid"]);
                        $sharedetails .= "'>";
                        $sharedetails .= $getsharename['fname'].' '.$getsharename['lname'];
                        $sharedetails .= "</a>'s Post.";
                    }
                    $link = Url::to(['userwall/index', 'id' => $post['post_user_id']]);
                    $getuserinfo = LoginForm::find()->where(['_id' => $post['post_user_id']])->one();
                    $personalinfo = Personalinfo::find()->where(['user_id' => $post['post_user_id']])->one();
					
                    ?>
                    <!--<div class="msg_body" id="message<?//= $profile_tip_counter?>"></div>-->
                    <div class="profiletip-holder" id="profile_<?= $profile_tip_counter?>">
						<div class="profile-tip">
						
                        <?php
                            $dp = $this->getimage($getuserinfo['_id'],'photo');
                            if(isset($getuserinfo['cover_photo']) && !empty($getuserinfo['cover_photo']))
                            {
                                $cover = $getuserinfo['cover_photo'];
                            }
                            else
                            {
                                $cover = 'cover.jpg';
                            }
                            $dpforpopup = $this->getimage($post['user']['_id'],'thumb');
                            $mutualcount = Friend::mutualfriendcount($getuserinfo['_id']);
                        ?>
                        <div class="profile-tip-cover"><img src="profile/<?=$cover?>"></div>
                        <div class="profile-tip-avatar">
                             <a href="<?php echo $link;?>">
                                <img alt="user-photo" class="img-responsive" src="<?= $dp?>">
                              </a>
                        </div>
                        <div class="profile-tip-info">
                            
                            <div class="cover-username"><a href="<?php echo $link;?>"><?php echo ucfirst($getuserinfo['fname']).' '.ucfirst($getuserinfo['lname'])?></a></div>
							<?php if(!empty($personalinfo['occupation'])){?>
                            <div class="cover-headline">
								<span class="profile-tip-spn1"><i class="fa  fa-suitcase"></i></span>
								<span class="profile-tip-spn2"><?= $personalinfo['occupation']?></span>
							</div>
							<?php }?>
							<?php if(!empty($getuserinfo['city'])){?>
							 <div class="profile-tip-bio">
								<span class="profile-tip-spn1"><i class="fa fa-home"></i></span>
								<span class="profile-tip-spn2">Lives in : <span><?= $getuserinfo['city']?></span></span>
							</div>
							<?php }?>
							<?php if(!empty($personalinfo['current_city'])){?>
                            <div class="profile-tip-bio">
								<span class="profile-tip-spn1"><i class="fa fa-map-marker"></i></span>
								<span class="profile-tip-spn2">Currently in : <span><?= $personalinfo['current_city']?></span></span>
							</div>
							<?php }?>
							<?php if($getuserinfo['_id'] != $user_id){ ?>
                            <div class="profile-tip-bio mf-line "><?= $mutualcount?> Mutual Friends</div>
                            <?php }?>
                            <div class="profile-tip-divider"></div>
							<div class="profile-tip-btn">
								<a href="<?php echo $link;?>" class="btn btn-primary btn-sm"><i class="fa  fa-eye"></i>View Profile</a>
								<?php if($getuserinfo['_id'] != $user_id){ ?>
								<button class="btn btn-primary btn-sm"><i class="fa  fa-envelope"></i>Message</button>								
								<?php }?>
							</div>
                           
                        </div>
                        </div>
                    </div>
                        <div class="tb-user-box"> <a class="profilelink no-ul" href="<?php $id =  $post['user']['_id']; echo Url::to(['userwall/index', 'id' => "$id"]); ?>">
                                <?php 
                                    $dpimg = $this->getimage($post['user']['_id'],'photo');
                                ?>
                                <?php /* <img onMouseOver="show('<?= $profile_tip_counter?>')" onMouseOut="hide('<?= $profile_tip_counter?>')" alt="user-photo" class="img-responsive" src="<?= $dpimg?>"> */ ?>
                                <img alt="user-photo" class="img-responsive" src="<?= $dpimg?>"> 
                            </a>
                        </div>
                    <div class="tb-user-desc">
                  <?php 

                    // START add tab content in post
                    $posttag = '';
                    if(isset($post['post_tags']) && !empty($post['post_tags'])) {
                      $posttag = explode(",", $post['post_tags']);
                    }

                    $taginfomatiom = ArrayHelper::map(UserForm::find()->where(['IN', '_id',  $posttag])->all(), 'fullname', (string)'_id');

                    $nkTag = array();
                    $nvTag = array();

                    $i=1;
                    foreach ($taginfomatiom as $key => $value) {
                        $nkTag[] = (string)$value; 
                        $nvTag[] = $key;
                        if($i != 1) {
                            $content[] = $key;
                        }
                        $i++;
                    }
                    
                    if(isset($content) && !empty($content)) {

                        $content = implode("<br/>", $content); 
                    }

                    $tagstr = '';
                    if(!empty($taginfomatiom)) {
                        if(count($taginfomatiom) > 1) {
                            $tagstr =  " with <a href=".Url::to(['userwall/index', 'id' => $nkTag[0]]) .">" . $nvTag[0] . " </a> and <a href='#' class='show-pop right-bottom' data-placement='right-bottom' data-content='".$content."'>" . (count($nkTag) - 1) . " others</a>";
                        } else {
                            $tagstr =  " with <a href=".Url::to(['userwall/index', 'id' => $nkTag[0]]) .">" . $nvTag[0] . "</a>";
                        }
                    }

                    // END add tab content in post

                    $profile_tip_counter++;					
                    if(isset($post['currentlocation']) && !empty($post['currentlocation']))
                    { $location = $tagstr . ' at '.$post['currentlocation'].'.'; }
                    else{ $location = $tagstr . ''; }
                    if($post['is_timeline']=='1'){?>
                    <span><?php echo $sharedetails;?>  &gt; <a href="<?php $id =  $post['user']['_id']; echo Url::to(['userwall/index', 'id' => "$id"]); ?>"><?php echo ucfirst($post['user']['fname']).' '.ucfirst($post['user']['lname']) ;?></a></span>
                   <!--<span><a href="<?php //$id =  $post['user']['_id']; echo Url::to(['userwall/index', 'id' => "$id"]); ?>"><?php //echo ucfirst($post['user']['fname']).' '.ucfirst($post['user']['lname']) ;?></a><?php //if(isset($post['shared_from']) && !empty($post['shared_from'])){echo $sharedetails;}?></span>-->
                  <?php }
                  else if($post['is_album']=='1'){?>
                    <span><a href="<?php $id =  $post['user']['_id']; echo Url::to(['userwall/index', 'id' => "$id"]); ?>"><?php echo ucfirst($post['user']['fname']).' '.ucfirst($post['user']['lname']) ;?></a> added album <a href="javascript:void(0)"><?= $post['album_title']?></a>.</span>
                    <?php }
                    else if($post['post_type']=='profilepic'){?>
                    <span><a href="<?php $id =  $post['user']['_id']; echo Url::to(['userwall/index', 'id' => "$id"]); ?>"><?php echo ucfirst($post['user']['fname']).' '.ucfirst($post['user']['lname']) ;?></a> updated profile picture.</span>
                    <?php }
                    else if($post['is_coverpic']=='1'){?>
                    <span><a href="<?php $id =  $post['user']['_id']; echo Url::to(['userwall/index', 'id' => "$id"]); ?>"><?php echo ucfirst($post['user']['fname']).' '.ucfirst($post['user']['lname']) ;?></a> updated cover picture.</span>
                    <?php }
                    else{?>
                    <span><a href="<?php $id =  $post['user']['_id']; echo Url::to(['userwall/index', 'id' => "$id"]); ?>"><?php echo ucfirst($post['user']['fname']).' '.ucfirst($post['user']['lname']) ;?></a><?php if(isset($post['shared_from']) && !empty($post['shared_from'])){echo $sharedetails;}?><?= $location?></span>
                  <?php }?>
                    <span><?php echo $time; ?> <i class="glyphicon glyphicon-<?= $post_class?> "></i></span>
                   </div>
                </div>
                <?php
                if($post['is_timeline']=='1')
                {
                    if($post['post_text'] != ''){
                    ?>
                        <div class="tb-text-post moretext" id="temp" name="temp" ><?= $post['post_text'] ?></div>
                    <?php } 
                        if(isset($post['parent_post_id']) && !empty($post['parent_post_id']))
                        {
                            $this->view_post_id((string)$post['parent_post_id']);
                        }
                }
                else
                {
                ?>
                <?php
                    if(isset($post['image']) && !empty($post['image'])){
                        $eximgs = explode(',',$post['image'],-1);
						/*$totalimgs = count($eximgs);
						if($totalimgs == '1'){$imgcountcls = 'one-img';}
						if($totalimgs == '2'){$imgcountcls = 'two-img';}
						if($totalimgs == '3'){$imgcountcls = 'three-img';}
						if($totalimgs == '4'){$imgcountcls = 'four-img';}
						if($totalimgs == '5'){$imgcountcls = 'five-img';}
						if($totalimgs > '5'){$imgcountcls = 'more-img';}*/
                        /*$picsize = '';
                        foreach ($eximgs as $eximg)
                        {
                            $val = getimagesize('../web'.$eximg);
                            $picsize .= $val[0] .'x'. $val[1] .', ';
                        }
                        echo $picsize;*/
                    }
                  ?>
                <?php if($post['post_title'] != null) { ?>
                    <div class="tb-text-post post-title moretext" id="temp" name="temp" ><?= $post['post_title'] ?></div>
                <?php }?>
                  <?php if($post['post_type'] == 'image' && $post['is_coverpic'] == null) {
						$cnt = 1;
						$eximgs = explode(',',$post['image'],-1);
						$totalimgs = count($eximgs);
                                                $imgcountcls="";
						if($totalimgs == '1'){$imgcountcls = 'one-img';}
						if($totalimgs == '2'){$imgcountcls = 'two-img';}
						if($totalimgs == '3'){$imgcountcls = 'three-img';}
						if($totalimgs == '4'){$imgcountcls = 'four-img';}
						if($totalimgs == '5'){$imgcountcls = 'five-img';}
						if($totalimgs > '5'){$imgcountcls = 'more-img';}
                   ?>
<!--                    <div class="tb-text-post" id="temp" name="temp" ><?= $post['post_text'] ?></div>-->
                   <div class="tb-img-post" id="temp" name="temp">
						<div class="pimg-holder <?= $imgcountcls?> gallery">
							<?php foreach ($eximgs as $eximg) {
								if($cnt < 6){

                                    if (file_exists('../web'.$eximg)) {  
							$picsize = '';
							$val = getimagesize('../web'.$eximg);
							$picsize .= $val[0] .'x'. $val[1] .', ';
							if($val[0] > $val[1]){$imgclass = 'himg';}else if($val[1] > $val[0]){$imgclass = 'vimg';}else{$imgclass = 'simg';}?>
							<div class="pimg-box <?= $imgclass?>-box"><a href="<?= Yii::$app->getUrlManager()->getBaseUrl() ?><?= $eximg ?>" rel="prettyPhoto[gallery<?=$post['_id']?>]"><img class="<?= $imgclass?>" src="<?= Yii::$app->getUrlManager()->getBaseUrl() ?><?= $eximg ?>" alt=""></a>
							<?php if($cnt > 4){?>
								<div class="more-box"><a href="#"><i class="fa fa-plus"></i><?= $totalimgs - $cnt +1;?></a></div>
							<?php }?>
							</div>
								<?php } } $cnt++;}?>
						</div>		
					</div>

                  <?php }  else if($post['post_type'] == 'text') { ?>
				  <?php if($post['post_text'] != '') {?>
				  <div class="tb-text-post moretext" id="temp" name="temp" ><?= $post['post_text'] ?></div><?php }?>
                   <?php } else if($post['post_type'] == 'link') { ?>
					
					<?php /*
						<a href="<?= $post['post_text']?>" target="_blank"><div class="tb-img-post" id="temp" name="temp"><img src="<?= $post['image'] ?>" alt=""></div>
                        <div class="tb-text-post" id="temp" name="temp" ><?= $post['link_title'] ?></div>
                        <div class="tb-text-post" id="temp" name="temp" ><?= $post['link_description'] ?></div>
						</a>
						*/ ?>
						<div class="post-linkinfo">
							<div class="linkinfo-holder">
								<div class="previewImage">
									<a href="<?= $post['post_text']?>" target="_blank">
										<img src="<?= $post['image'] ?>" alt="">
									</a>
								</div>
								<div class="previewDesc">
									<div class="previewLoading">
										<a href="<?= $post['post_text']?>" target="_blank">
											<?= $post['link_title'] ?>
										</a>
									</div>
									<div class="tb-text-post moretext" id="temp" name="temp" ><?= $post['link_description'] ?></div>
								</div>
							</div>
						</div>
					
                  <?php } 
                 else if($post['post_type'] == 'text and image') {
					 
                     	$cnt = 1;
						$eximgs = explode(',',$post['image'],-1);
						$picsize = '';
                        foreach ($eximgs as $eximg)
                        {
                            if (file_exists('../web'.$eximg)) {  
                                $val = getimagesize('../web'.$eximg);
                                $picsize .= $val[0] .'x'. $val[1] .', ';
                            }
                        }
                        //echo $picsize;
						$totalimgs = count($eximgs);
						if($totalimgs == '1'){$imgcountcls = 'one-img';}
						if($totalimgs == '2'){$imgcountcls = 'two-img';}
						if($totalimgs == '3'){$imgcountcls = 'three-img';}
						if($totalimgs == '4'){$imgcountcls = 'four-img';}
						if($totalimgs == '5'){$imgcountcls = 'five-img';}
						if($totalimgs > '5'){$imgcountcls = 'more-img';}					 ?>
                    <div class="tb-text-post moretext" id="temp" name="temp" ><?= $post['post_text'] ?></div>
					
					<script>
						/*$(".<?= $imgcountcls?> .pimg-box").each(function(){
							
							//alert($(this).width()+" - "+$(this).height());
							$(this).parents(".pimg-holder").append("cont. dimentions : <?= $imgcountcls?>");
							$(this).parents(".pimg-holder").append($(this).width()+" - "+$(this).height()+",");
						});*/
						var icount=[];
						
						var l=$("#post-status-list .<?= $imgcountcls ?>").length;
						
						
						$("#post-status-list .panel-shadow").each(function(){
							
							var count=0;
							$(this).children(".four-img .pimg-box").each(function(){
								count++;
								//alert($(this).width()+" - "+$(this).height());
								//$(this).parents(".pimg-holder").append(count+"<br />");
								//$(this).parents(".pimg-holder").append("cont. dimentions : ");
								//$(this).parents(".pimg-holder").append($(this).width()+" - "+$(this).height()+",");
							});
						});
					</script>

                   <div class="tb-img-post" id="temp" name="temp">
						<div class="pimg-holder <?= $imgcountcls?> gallery">
							<?php foreach ($eximgs as $eximg) {
								if($cnt < 6){
                                    if (file_exists('../web'.$eximg)) {  
							$picsize = '';
							$val = getimagesize('../web'.$eximg);
							
							$picsize .= $val[0] .'x'. $val[1] .', ';
							if($val[0] > $val[1]){$imgclass = 'himg';}else if($val[1] > $val[0]){$imgclass = 'vimg';}else{$imgclass = 'simg';}?>
							<div class="pimg-box <?= $imgclass?>-box"><a href="<?= Yii::$app->getUrlManager()->getBaseUrl() ?><?= $eximg ?>" rel="prettyPhoto[gallery<?=$post['_id']?>]"><img class="<?= $imgclass?>" src="<?= Yii::$app->getUrlManager()->getBaseUrl() ?><?= $eximg ?>" alt=""></a>
							<?php if($cnt > 4){?>
								<div class="more-box"><a href="#"><i class="fa fa-plus"></i><?= $totalimgs - $cnt +1;?></a></div>
							<?php }?>
							</div>
								<?php } } $cnt++;}?>
								
							
						</div>		
					</div>

                    
                  <?php }
                  else if($post['post_type'] == 'profilepic') { ?>
                    <div class="tb-img-post" id="temp" name="temp">
                        <div class="pimg-holder one-img gallery">
                        <?php
                        $picsize = '';
                        $val = getimagesize('profile/'.$post['image']);
                        $picsize .= $val[0] .'x'. $val[1] .', ';
                        if($val[0] > $val[1]){$imgclass = 'himg';}else if($val[1] > $val[0]){$imgclass = 'vimg';}else{$imgclass = 'simg';}?>
                            <div class="pimg-box <?= $imgclass?>-box">
                                <a href="<?= Yii::$app->getUrlManager()->getBaseUrl() ?><?= '/profile/'.$post['image'] ?>" rel="prettyPhoto[gallery<?=$post['_id']?>]">
                                    <img class="<?= $imgclass?>" src="<?= Yii::$app->getUrlManager()->getBaseUrl() ?><?= '/profile/'.$post['image'] ?>" alt="">
                                </a>
                            </div>
                        </div>
                    </div>
                <?php }
                else if($post['is_coverpic'] == '1') { ?>
                    <div class="tb-img-post" id="temp" name="temp">
                        <div class="pimg-holder one-img gallery">
                        <?php
                        $picsize = '';
                        $val = getimagesize('profile/'.$post['image']);
                        $picsize .= $val[0] .'x'. $val[1] .', ';
                        if($val[0] > $val[1]){$imgclass = 'himg';}else if($val[1] > $val[0]){$imgclass = 'vimg';}else{$imgclass = 'simg';}?>
                            <div class="pimg-box <?= $imgclass?>-box">
                                <a href="<?= Yii::$app->getUrlManager()->getBaseUrl() ?><?= '/profile/'.$post['image'] ?>" rel="prettyPhoto[gallery<?=$post['_id']?>]">
                                    <img class="<?= $imgclass?>" src="<?= Yii::$app->getUrlManager()->getBaseUrl() ?><?= '/profile/'.$post['image'] ?>" alt="">
                                </a>
                            </div>
                        </div>
                    </div>
                <?php }
                        }?>
              </div>
        <?php
        if($userid != $post['post_user_id']){
        ?>
        <div id="report_post_<?php echo $post['_id'];?>" class="white-popup-block mfp-hide fp-modal-popup">	
            <input type="hidden" value="<?php echo $post['_id']?>" id="pid" name="pid"/>
            <div class="tb-panel-box postbox">
                <div class="modal-title graytitle">
                    <h4>Report Post</h4>
                    <a class="popup-modal-dismiss popup-modal-close" href="javascript:void(0)"><i class="fa fa-close"></i></a>
                </div>
                <div class="tb-panel-body01">
                  <div id="report_success" class="form-successmsg">This post has been reported now to user.</div>
                  <div id="report_fail" class="form-failuremsg">Oops..!! Something went wrong. Please share later.</div>
                  <div class="posttext">
                      <textarea id="desc" name="desc" placeholder="Reason for reporting this post..." rows="2" cols="62" style="border:none;resize: none;"></textarea>
                  </div>
                <div class="tb-panel-head clearfix">

                    <div class="tb-user-box"> <a class="profilelink no-ul" href="<?php $id =  $post['user']['_id']; echo Url::to(['userwall/index', 'id' => "$id"]); ?>"><img alt="user-photo" class="img-responsive" src="<?= $dpforpopup?>"> </a></div>
                    <div class="tb-user-desc"> <span><a class="profilelink no-ul" href="<?php $id =  $post['user']['_id']; echo Url::to(['userwall/index', 'id' => "$id"]); ?>"><?php echo $post['user']['fname'].' '.$post['user']['lname'] ;?></a></span><span><?php echo $time; ?> <i class="glyphicon glyphicon-<?= $post_class?> "></i></span> </div>
                </div>
                <?php
                  if(isset($post['image']) && !empty($post['image'])){
                  $eximgs = explode(',',$post['image'],-1);
                  }
                ?>
                <?php if($post['post_type'] == 'image') { ?>
                    <div class="tb-img-post popup-images" id="temp" name="temp">
						   <div class="albums">
						   <?php foreach ($eximgs as $eximg) {
							   $picsize = '';
								$val = getimagesize('../web'.$eximg);
								$picsize .= $val[0] .'x'. $val[1] .', ';
								if($val[0] > $val[1]){$imgclass = 'himg';}else if($val[1] > $val[0]){$imgclass = 'vimg';}else{$imgclass = 'simg';}
								?>
							   <div class="album-col">
								   <div class="album-holder">
									   <div class="album-box">
										   <a href="#" class="listalbum-box <?= $imgclass?>-box">
												<img src="<?= Yii::$app->getUrlManager()->getBaseUrl() ?><?= $eximg ?>" alt="">
										   </a>
									   </div>
									</div>
								</div>
						   <?php }?>
                           

						   </div>
					   </div>
                  <?php }  else if($post['post_type'] == 'text') { ?>
                    <div class="tb-text-post moretext" id="temp" name="temp" ><?= $post['post_text'] ?></div>
                   <?php } else if($post['post_type'] == 'link') { ?>
                    <a href="<?= $post['post_text']?>" target="_blank"><div class="tb-img-post" id="temp" name="temp"><img src="<?= $post['image'] ?>" alt=""></div>
                    <div class="tb-text-post" id="temp" name="temp" ><?= $post['link_title'] ?></div>
                    <div class="tb-text-post moretext" id="temp" name="temp" ><?= $post['link_description'] ?></div></a>
                  <?php } 
                 else if($post['post_type'] == 'text and image') { ?>
                    <div class="tb-text-post moretext" id="temp" name="temp" ><?= $post['post_text'] ?></div>
                   <div class="tb-img-post popup-images" id="temp" name="temp">
						   <div class="albums">
						   <?php foreach ($eximgs as $eximg) {
							   $picsize = '';
								$val = getimagesize('../web'.$eximg);
								$picsize .= $val[0] .'x'. $val[1] .', ';
								if($val[0] > $val[1]){$imgclass = 'himg';}else if($val[1] > $val[0]){$imgclass = 'vimg';}else{$imgclass = 'simg';}
								?>
							   <div class="album-col">
								   <div class="album-holder">
									   <div class="album-box">
										   <a href="#" class="listalbum-box <?= $imgclass?>-box">
												<img src="<?= Yii::$app->getUrlManager()->getBaseUrl() ?><?= $eximg ?>" alt="">
										   </a>
									   </div>
									</div>
								</div>
						   <?php }?>
                         

						   </div>
					   </div>

                  <?php } ?>
                    <div class="tb-panel-head clearfix">
                    <button class="btn btn-primary btn-sm" onclick="reportpost();" type="button" name="post" id="post">Report</button>
                </div>
              </div>
            </div>
        </div>
        <?php }?>
        <div id="share_post_<?php echo $post['_id'];?>" class="white-popup-block mfp-hide fp-modal-popup sharepopup closepopup">	
			<div class="tb-panel-box postbox">
				<div class="modal-title graytitle clearfix">										
					<a class="popup-modal-dismiss popup-modal-close close-top" href="javascript:void(0)"><i class="fa fa-close"></i></a>
				</div>
				<div class="tb-panel-body01">
					<div class="panel-body toolbox main-body">
					<div class="popup-row clear">
						<div class="popup-info popup-pd clearfix">
							<div class="full-width">
								<div class="userinfo">
									<div class="tb-user-info">
										<a href="#">
											<img alt="user-photo" class="img-responsive" src="<?= $dpforpopup?>">
										</a>
									</div>
									<div class="tb-user-name bold-hd">
										<?= $post['user']['fname'].' '.$post['user']['lname']?>
									</div>
									
									<div class="pull-right">													
										<div class="dropholder dropholder-mt">													
											<div class="dropdown que-drop">
												<button aria-expanded="false" data-toggle="dropdown" type="button" class="btn btn-default dropdown-toggle btn-sm custom-select custom-select-quest">
													<span class="glyphicon glyphicon-edit"></span> Share on your wall
													<span class="caret"></span>
												</button>
												<ul class="dropdown-menu" id="quest">
													<li class="sel-phone on-sel-phone"><a href="javascript:void(0)" onClick="setQuestionIcon(this)"><span class="glyphicon glyphicon-edit"></span>Share on your wall</a></li>
													<li class="sel-friends on-sel-friends"><a href="javascript:void(0)" onClick="setQuestionIcon(this)"><span class="glyphicon glyphicon-user"></span>Share on a friend's wall</a></li>	
													<li class="sel-friend on-sel-friend"><a href="javascript:void(0)" onClick="setQuestionIcon(this)"><span class="glyphicon glyphicon-globe"></span>Share on group wall</a></li>
													<li class="sel-friend-facebook on-sel-friend-facebook"><a href="javascript:void(0)" onClick="setQuestionIcon(this)"><i class="fa fa-facebook"></i>Share on Facebook</a></li>													
												</ul>													  													
											</div>
										</div>	
									</div>	
									<!--
									<select id="test" class="modal-select pull-right" name="form_select" onchange="showDiv(this)">

										<option value="0">Share on your wall</option>
										<option value ="1">Share on a friend's wall</option>
										<option value ="2">Share on group wall</option>

									 </select>
									 -->
								</div>
                                                            <div class="tarrow dotarrow">
                                                                <a href="javascript:void(0)" class="alink">
								<span class="more-option">
                                                                    <svg width="100%" height="100%" viewBox="0 0 50 50"><path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z" class="Ce1Y1c"/></svg>
								</span>
                                                                </a>
                                                                <div class="drawer">
                                                                    <ul class="sort-ul">
                                                                        <li class="disable_share disable_share1"><span class="dis_share check-option">✓</span><a href="javascript:void(0)">Disable Sharing</a></li>                                                                        
                                                                        <li class="disable_comment disable_comment1"><span class="dis_comment check-option">✓</span><a href="javascript:void(0)">Disable Comments</a></li>
                                                                    </ul>   
                                                                </div>
                                                            </div>
                                                            <input type="hidden" name="share_setting" id="share_setting" value="Enable"/>
                                                            <input type="hidden" name="comment_setting" id="comment_setting" value="Enable"/>
							</div>
						</div>
						</div>
						<div class="sec-row">
						<div class="frmSearch clearfix" id="friendlist" style="display:none;">
							<div class="f-hd">
								<span class="fn-sp">Friend:</span>
							</div>
							<div class="f-list">
								<input type="text" class="frnduname" placeholder="Friend's Name" />
							</div>
							<div class="suggesstion-box"></div>
						 </div>
						 </div>
						<input type="hidden" value="<?php echo $post['_id']?>" id="spid" name="spid"/>
						<div class="post-holder">		
							<div class="share-post">
								<div class="posttext sliding-middle-out full-width">									
									  <textarea id="desc" name="desc" placeholder="Say Something about this..." rows="1" cols="62" style="border:none;resize: none;"></textarea>
								  </div>

							</div>
							<?php
							  if(isset($post['image']) && !empty($post['image'])){
							  $eximgs = explode(',',$post['image'],-1);
							  }
							?>
							<?php if($post['post_type'] == 'image') { ?>
								<div class="tb-img-post popup-images" id="temp" name="temp">
									   <div class="albums">
									   <?php foreach ($eximgs as $eximg) {
                                        if (file_exists('../web'.$eximg)) {  
										   $picsize = '';
											$val = getimagesize('../web'.$eximg);
											$picsize .= $val[0] .'x'. $val[1] .', ';
											if($val[0] > $val[1]){$imgclass = 'himg';}else if($val[1] > $val[0]){$imgclass = 'vimg';}else{$imgclass = 'simg';}
											?>
										   <div class="album-col">
											   <div class="album-holder">
												   <div class="album-box">
													   <a href="#" class="listalbum-box <?= $imgclass?>-box">
															<img src="<?= Yii::$app->getUrlManager()->getBaseUrl() ?><?= $eximg ?>" alt="">
													   </a>
												   </div>
												</div>
											</div>
									   <?php } } ?>
                                       <div id="image-holder">
                                            <div class="img-row"></div>
                                        </div>

									   </div>
								   </div>
							  <?php }  else if($post['post_type'] == 'text') { ?>
								<div class="tb-text-post moretext" id="temp" name="temp" ><?= $post['post_text'] ?></div>
							   <?php } else if($post['post_type'] == 'link') { ?>
								<a href="<?= $post['post_text']?>" target="_blank"><div class="tb-img-post" id="temp" name="temp"><img src="<?= $post['image'] ?>" alt=""></div>
								<div class="tb-text-post" id="temp" name="temp" ><?= $post['link_title'] ?></div>
								<div class="tb-text-post moretext" id="temp" name="temp" ><?= $post['link_description'] ?></div></a>
							  <?php } 
							 else if($post['post_type'] == 'text and image') { ?>
								<div class="tb-text-post moretext" id="temp" name="temp" ><?= $post['post_text'] ?></div>
								   <div class="tb-img-post popup-images" id="temp" name="temp">
									   <div class="albums">
									   <?php foreach ($eximgs as $eximg) {
                                        if (file_exists('../web'.$eximg)) {  
										   $picsize = '';
											$val = getimagesize('../web'.$eximg);
											$picsize .= $val[0] .'x'. $val[1] .', ';
											if($val[0] > $val[1]){$imgclass = 'himg';}else if($val[1] > $val[0]){$imgclass = 'vimg';}else{$imgclass = 'simg';}
											?>
										   <div class="album-col">
											   <div class="album-holder">
												   <div class="album-box">
													   <a href="#" class="listalbum-box <?= $imgclass?>-box">
															<img src="<?= Yii::$app->getUrlManager()->getBaseUrl() ?><?= $eximg ?>" alt="">
													   </a>
												   </div>
												</div>
											</div>
									   <?php } } ?>
                                       <div id="image-holder">
                                <div class="img-row"></div>
                            </div>

									   </div>
								   </div>

							  <?php } ?>
							
						</div>
						<div class="toolbox">
							<div class="tags-added"></div>
								<div id="tag" class="addpost-tag">
									
									<span class="ltitle">With</span>
									<div class="tag-holder">
                                     <?php
                                        
                                         $sharetag = '';
                                         if(isset($post['post_tags']) && $post['post_tags'] != null && $post['post_tags'] != 'null') {
                                            $sharetag = $post['post_tags'];
                                         }

                                        ?>
                                        <select id="sharetag<?php echo $post["_id"];?>" class="taginput" placeholder="Who are with you?" multiple="multiple" data-taginput='<?php echo $sharetag; ?>'></select>
	
									</div>
								</div>
								<div style="clear: both;"></div>	
							<div id="area" class="addpost-location">
								<span class="ltitle">At</span>
								<div class="loc-holder">
                                  <?php $shareloc = isset($post['currentlocation']) ? $post['currentlocation'] : ''; ?>
									<input type="text" name="share_current_location" class="share_current_location getplacelocation" value="<?php echo $shareloc; ?>" id="share_current_location_<?=$post['_id']?>" placeholder="Where are you?" />
									<input type="hidden" name="country" id="country" />
									<input type="hidden" name="isd_code" id="isd_code" />
								</div>
							</div>								
		
										
							<div class="tb-user-post-bottom clearfix">
								<div class="tb-user-icon"> 									
									<a href="javascript:void(0)" class="tb-icon-fix001 add-tag add-tag1"><span aria-hidden="true" class="glyphicon glyphicon-user"></span></a>

									<a href="javascript:void(0)" class="tb-icon-fix001 add-loc add-loc1"><span aria-hidden="true" class="glyphicon glyphicon-map-marker"></span></a>

								</div>
									
								<div class="user-post fb-btnholder">
									<div class="dropdown tb-user-post">

										<button aria-expanded="false" data-toggle="dropdown" type="button" class="btn btn-default dropdown-toggle btn-sm custom-select custom-select-posting-security popupbtn-mr"><span class="glyphicon glyphicon-<?= $post_dropdown_class ?>"></span> <?= $my_post_view_status ?> <span class="caret"></span></button>

										<ul class="dropdown-menu" id="posting-security">
											<li class="sel-private"><a href="javascript:void(0)" onClick="setSecuritySelect(this, 'Private')"><span class="glyphicon glyphicon-lock"></span> Private</a></li>
											<li class="divider"></li>
											<li class="sel-friends"><a href="javascript:void(0)" onClick="setSecuritySelect(this, 'Friends')"><span class="glyphicon glyphicon-user"></span> Friends</a></li>
											<li class="divider"></li>
											<li class="sel-public"><a href="javascript:void(0)" onClick="setSecuritySelect(this, 'Public')"><span class="glyphicon glyphicon-globe"></span> Public</a></li>
											<li class="divider"></li>
											<li class="sel-custom"><a href="#custom-share" class="popup-modal" onClick="sendSelId('posting-security')"><span class="glyphicon glyphicon-cog"></span> Custom</a></li>
										</ul>
										<input type="hidden" name="post_privacy" id="post_privacy" value="<?= $my_post_view_status ?>"/>
										<input type="hidden" name="link_title" id="link_title" />
										<input type="hidden" name="link_url" id="link_url" />
										<input type="hidden" name="link_description" id="link_description" />
										<input type="hidden" name="link_image" id="link_image" />
										
										<div class="modalId"></div>
										
										<button class="btn btn-primary btn-sm share-post" onclick="spwFriend();" type="button" name="post" id="share_post_<?php echo $post['_id'];?>">Share</button>
										<!--<button class="btn btn-primary btn-sm share-post" onclick="confirm_cancel_post();" type="button" name="post"><i class="fa fa-close"></i>Cancel</button>-->
									</div>
								</div>
							</div>
							
						</div>
					
					</div>
				</div>
			</div>
		</div>
        <?php
        if($userid == $post['post_user_id']){
        ?>

        <div id="edit_post_<?php echo $post['_id'];?>"  class="white-popup-block mfp-hide fp-modal-popup closepopup edit-popup">
            <form id="frm_edit_post" enctype="multipart/form-data">
                <?php
                if(isset($post['post_title']) && !empty($post['post_title'])) {
                    $post_title = $post['post_title'];
                }
                else
                {
                    $post_title = '';
                }
                ?>
				<input type="hidden" value="<?php echo $post['_id']?>" id="pid" name="pid"/>
				<div class="tb-panel-box postbox">
					
					<div class="modal-title graytitle">						
						<a class="popup-modal-dismiss popup-modal-close close-top" href="javascript:void(0)"><i class="fa fa-close"></i></a>
					</div>
					
					<div class="tb-panel-body01 rounded edit-icon-holder">
						<div class="panel-body toolbox main-body modal-detail">
							
							<div class="newpost-topbar">
                                <div class="post-title">
                                    <div class="tb-user-post-middle">                                           
                                        <div class="sliding-middle-out full-width">                                         
                                            <input type="text" placeholder="Title of this post" id="title"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="userinfo">
                                        <div class="tb-user-info">
                                            <a href="#">
                                                <img alt="user-photo" class="img-responsive" src="<?= $dpforpopup?>">
                                            </a>
                                        </div>
                                        <div class="tb-user-name bold-hd">
                                            <?= $post['user']['fname'].' '.$post['user']['lname']?>
                                        </div>
                                    </div>
								<div class="tarrow dotarrow"> 
                                	
                                    <a href="javascript:void(0)" class="alink">
                                    <span class="more-option"><svg height="100%" width="100%" viewBox="0 0 50 50"><path class="Ce1Y1c" d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/></svg></a></span>&nbsp;
                                    <div class="drawer">
                                        <ul class="sort-ul">
                                            <li class="disable_share disable_share1"><span class="dis_share check-option">✓</span><a href="javascript:void(0)">Disable Sharing</a></li>
                                            <li class="disable_comment disable_comment1"><span class="dis_comment check-option">✓</span><a href="javascript:void(0)">Disable Comments</a></li>
                                        </ul>   
                                    </div>
								</div>
							</div>

                            <input type="hidden" name="share_setting" id="share_setting" value="Enable"/>
                            <input type="hidden" name="comment_setting" id="comment_setting" value="Enable"/>
                

                           
							<div class="post-holder nb">
								<div class="tb-user-post-middle ntp">																				
									<div class="row">																				
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">																				
											<div class="sliding-middle-out full-width">											

                                            <input type="text" placeholder="Title of this post" id="title" class="tb-text-post post-title moretext" value="<?= $post_title?>"/>
											</div>
										</div>
										<div class="clear"></div>
										<div class="col-lg-12">																				
											<div class="sliding-middle-out full-width">											
												<textarea id="desc" name="desc" rows="1" cols="62" placeholder="Edit Text..."><?= $post['post_text'] ?></textarea>
											</div>
                                            <div class="tb-img-post popup-images" id="temp" name="temp">
                                            <div class="albums">
                                                <div id="image-holder">
                                                <div class="img-row"></div>
                                                </div>
                                            </div>
                                            </div>
                                           
										</div>
									</div>
									<div id="image-holder-post" class="addpost-imgs"></div>

									<div class="tags-added"></div>
									<div id="tag" class="addpost-tag">
										<span class="ltitle">With</span>
										<div class="tag-holder">
                                        <?php
                                        
                                         $editposttag = '';
                                         if(isset($post['post_tags']) && $post['post_tags'] != null &&  $post['post_tags'] != 'null') {
                                            $editposttag = $post['post_tags'];
                                         }

                                        ?>
											<select id="edittaginput<?php echo $post["_id"];?>" class="taginput" placeholder="Who are with you?" multiple="multiple" data-taginput='<?php echo $editposttag; ?>'></select>
										</div>
									</div>
									<div style="clear: both;"></div>
									<div id="area" class="addpost-location">
										<span class="ltitle">At</span>
										<div class="loc-holder">
                                            <?php
                                                $editloc = isset($post['currentlocation']) ? $post['currentlocation'] : '';
                                                ?>
											<input type="text" name="edit_current_location" class="edit_current_location getplacelocation" value="<?php echo $editloc; ?>" id="edit_current_location_<?=$post['_id']?>" placeholder="Where are you?" />
											<input type="hidden" name="country" id="country" />
											<input type="hidden" name="isd_code" id="isd_code" />
										</div>					
									</div>
								</div>
							</div>
							<?php
							  if(isset($post['image']) && !empty($post['image'])){
							  $eximgs = explode(',',$post['image'],-1);
							  }
							?>
							<?php if($post['post_type'] == 'image') { ?>
								<div class="tb-img-post popup-images" id="temp" name="temp">
								   <div class="albums">
								   <?php foreach ($eximgs as $eximg) {
                                    if (file_exists('../web'.$eximg)) {  
										$iname = $this->getimagefilename($eximg);
										$picsize = '';
										$val = getimagesize('../web'.$eximg);
										$picsize .= $val[0] .'x'. $val[1] .', ';
										if($val[0] > $val[1]){$imgclass = 'himg';}else if($val[1] > $val[0]){$imgclass = 'vimg';}else{$imgclass = 'simg';}
										?>
									   <div class="album-col" id="imgbox_<?= $iname ?>">
										   <div class="album-holder">
											   <div class="album-box">
												   <a href="#" class="listalbum-box <?= $imgclass?>-box">
														<img src="<?= Yii::$app->getUrlManager()->getBaseUrl() ?><?= $eximg ?>" alt="">
														<a href="javascript:void(0)" class="popup-imgdel" onclick="delete_image('<?= $iname ?>','<?= $eximg ?>','<?= $post['_id'] ?>')"><i class="fa fa-close"></i></a>
												   </a>
											   </div>
											</div>
										</div>
								   <?php } } ?>
                                  

								   </div>
								</div>
							  <?php }  else if($post['post_type'] == 'text') { ?>
								
							   <?php } else if($post['post_type'] == 'link') { ?>
								<a href="<?= $post['post_text']?>" target="_blank"><div class="tb-img-post" id="temp" name="temp"><img src="<?= $post['image'] ?>" alt=""></div>
								<div class="tb-text-post" id="temp" name="temp" ><?= $post['link_title'] ?></div>
								<div class="tb-text-post moretext" id="temp" name="temp" ><?= $post['link_description'] ?></div></a>
							  <?php } 
							 else if($post['post_type'] == 'text and image') { ?>
								
								<div class="tb-img-post popup-images" id="temp" name="temp">
								   <div class="albums">
								   <?php foreach ($eximgs as $eximg) {
                                    if (file_exists('../web'.$eximg)) {  
										$iname = $this->getimagefilename($eximg);
										$picsize = '';
										$val = getimagesize('../web'.$eximg);
										$picsize .= $val[0] .'x'. $val[1] .', ';
										if($val[0] > $val[1]){$imgclass = 'himg';}else if($val[1] > $val[0]){$imgclass = 'vimg';}else{$imgclass = 'simg';}
										?>

                                       <div class="album-col" id="imgbox_<?= $iname ?>">
                                           <div class="album-holder">
                                               <div class="album-box">
                                                   <a href="#" class="listalbum-box <?= $imgclass?>-box">
                                                        <img src="<?= Yii::$app->getUrlManager()->getBaseUrl() ?><?= $eximg ?>" alt="">
                                                        <a href="javascript:void(0)" class="popup-imgdel" onclick="delete_image('<?= $iname ?>','<?= $eximg ?>','<?= $post['_id'] ?>')"><i class="fa fa-close"></i></a>
                                                   </a>
                                               </div>
                                            </div>
                                        </div>
                                   <?php } } ?>


                                   </div>
                                       
                                </div>

							  <?php } ?>
							<input type="text" name="url" size="64" id="url"  style="display: none"/>
							<input type="button" name="attach" value="Attach" id="mark" style="display: none" />
		
			
							<!--<div class="posttext"></div>-->

							<div class="tb-user-post-bottom clearfix">
								<div class="tb-user-icon"> 
									<div class="myLabel">
										<!-- uplaod image -->


										<input type="file" id="imageFilepost" name="imageFilepost[]" multiple="true" />

										<span aria-hidden="true" class="glyphicon glyphicon-camera edit_icons"></span>

										</div>

										<a href="javascript:void(0)" class="add-tag add-tag1"><span aria-hidden="true" class="glyphicon glyphicon-user edit_icons"></span></a> <a href="javascript:void(0)" class="add-loc add-loc1"><span aria-hidden="true" class="glyphicon glyphicon-map-marker edit_icons"></span></a>

								</div>

						
								<div class="user-post fb-btnholder">
									<div class="dropdown tb-user-post">

										<button aria-expanded="false" data-toggle="dropdown" type="button" class="btn btn-default dropdown-toggle btn-sm custom-select custom-select-posting-security popupbtn-mr"><span class="glyphicon glyphicon-<?= $post_dropdown_class ?>"></span> <?= $my_post_view_status ?> <span class="caret"></span></button>

										<ul class="dropdown-menu" id="posting-security">
											<li class="sel-private selected"><a href="javascript:void(0)" onClick="setSecuritySelect(this, 'Private')"><span class="glyphicon glyphicon-lock"></span> Private</a></li>
											<li class="divider"></li>
											<li class="sel-friends"><a href="javascript:void(0)" onClick="setSecuritySelect(this, 'Friends')"><span class="glyphicon glyphicon-user"></span> Friends</a></li>
											<li class="divider"></li>
											<li class="sel-public"><a href="javascript:void(0)" onClick="setSecuritySelect(this, 'Public')"><span class="glyphicon glyphicon-globe"></span> Public</a></li>
											<li class="divider"></li>
											<li class="sel-custom"><a href="#custom-share" class="popup-modal" onClick="sendSelId('posting-security')"><span class="glyphicon glyphicon-cog"></span> Custom</a></li>
										</ul>

																<button class="btn btn-primary btn-sm"  onclick="edit_post();" type="button" name="post" id="post"><span class="glyphicon glyphicon-check"></span>Save</button>

																<!--<button class="btn btn-primary btn-sm"  onclick="confirm_cancel_post();" type="button" name="cancel" id="post"><span class="glyphicon glyphicon-remove-sign"></span>Cancel</button>-->
									</div>
								</div>
							</div>
		
						</div>
					</div>
				</div>
						
             </form> 
        </div>
        <?php }?>
        <div class="tarrow <?php if($existing_posts == ''){echo "tarrow1";} ?>">
				<a href="javascript:void(0)" class="alink <?php if($existing_posts == ''){echo "alink1";} ?>""><i class="fa fa-angle-down"></i></a>
				<div class="drawer">
					<div class="opt-box">
                                            <?php
                                            if($userid != $post['post_user_id']){
                                            ?>
						<ul class="iconopt-ul">
							<li class="hidepost">
                                                            <a href="javascript:void(0)" onclick="hide_post('<?php echo $post['_id'];?>')">
                                                                <span class="title">Hide Post</span>
                                                                <span class="desc">See fewer posts like this</span>
                                                            </a>
							</li>
							<li class="unfollow">
                                                            <a href="javascript:void(0)" onclick="unfollow_friend('<?php echo $post['post_user_id']?>','<?php echo $post['_id'];?>')">
                                                                <span class="title">Unfollow Person</span>
                                                                <span class="desc">Stop seeing such posts but stay friends</span>
                                                            </a>
							</li>
						</ul>
						<div class="dline"></div>
                                            <?php }?>
						<ul class="siconopt-ul">
                                                        <?php
                                                        if($userid != $post['post_user_id']){
                                                        ?>
							<li class="reportpost">
								<a href="#report_post_<?php echo $post['_id'];?>" class="popup-modal">Report Post</a>
							</li>
                                                        <?php }?>
							<li class="savepost">
                                                            <a href="javascript:void(0)" id="save_post_<?php  echo $post['_id'];?>" data-pid="<?php  echo $post['_id'];?>" onclick="save_post('<?php echo $post['_id']?>','<?php echo $post['post_type']?>')"><?= $savevar ?> Post</a>
							</li>
							<li class="nicon">
								<a href="javascript:void(0)">Turn on notification for this post</a>
							</li>
                                                        <?php
                                                        if($userid != $post['post_user_id']){
                                                        ?>
                                                        <li class="nicon">
								<a href="javascript:void(0)" onclick="mute_friend('<?php echo $post['post_user_id']?>')">Mute Friend</a>
							</li>
                                                        <?php }?>
                                                        <?php
                                                        if($userid == $post['post_user_id'] && $post['post_type'] != 'profilepic' && $post['is_coverpic'] != '1'){
                                                        ?>
                                                         <li class="nicon">
                                                            <a href="javascript:void(0)" onclick="delete_post('<?php echo $post['post_user_id']?>','<?php echo $post['_id']?>')" >Delete Post</a>
							</li>
                                                        <?php
                                                        }
                                                      
                                                        if($userid == $post['post_user_id'] && $post['post_type'] != 'profilepic' && $post['is_coverpic'] != '1'){
                                                        ?>
                                                         <li class="nicon">
                                                            <a href="#edit_post_<?php echo $post['_id'];?>" data-editpostid="<?php echo $post['_id'];?>" class="popup-modal">Edit Post</a>
							</li>
                                                        <?php
                                                        }
                                                        ?>
                                                       
						</ul>				
					</div>
				</div>
			 </div>
                <?php 
                $session_user_id = (string)$userid;
                $result_security = SecuritySetting::find()->where(['user_id' => $session_user_id])->one();
                if($result_security)
                {
                    $tag_review_setting = $result_security['review_posts'];
                    $tag_review_tag = $result_security['review_tags'];
                }
                else
                {
                    $tag_review_setting = 'Disabled';
                    $tag_review_tag = 'Disabled';
                }
                //echo $tag_review_tag;
                ?>
              <div class="panel-footer">
                  <div id="share_success" class="form-successmsg">This post has been shared to your Timeline.</div>
                  <div id="share_fail" class="form-failuremsg">Oops..!! Something went wrong. Please share later.</div>
					<a href="javascript:void(0)" onclick="do_like('<?php echo $post['_id']?>')"><span class="glyphicon glyphicon-thumbs-up"></span>Like<span id='like-ctr-<?php echo $post['_id']?>'><?php if($like_count >0 )echo ' ('.$like_count.')';?></span></a>
                                    <?php if($post['comment_setting'] != 'Disable'){?>
                                        <a href="javascript:void(0)" onclick="do_comment('<?php echo $post['_id']?>')"><span class="glyphicon glyphicon-comment"></span>Comment<span id="comment_ctr_<?php echo $post['_id']; ?>"><?php if((count($comments)) > 0){ echo ' ('.count($comments).')';}?></span></a> 
                                    <?php } ?>
                                        <div class="tarrow">				  
                                            <?php if($post['share_setting'] != 'Disable'){?>
						<a href="#share_post_<?php echo $post['_id'];?>" class="popup-modal sharelnk" data-editpostid="<?php echo $post['_id'];?>" title="Send this to friends or post it on your Timeline."><span class="glyphicon glyphicon-share"></span>Share<?php if(!empty($post['share_by'])){$shares = substr_count( $post['share_by'], ","); echo '('.$shares.')';}?></a>				
                                            <?php } ?>
                                                <div class="drawer">
							<div class="opt-box" id="opt-box">							
								<ul class="siconopt-ul">
									<li>

										<a href="javascript:void(0)" onclick="sharenow('<?php echo $post['_id']?>')" >
											<span class="glyphicon glyphicon-share"></span>Share Now(Friends)
										</a>
									</li>
									<li>
                                                                                <a href="#share_post_<?php echo $post['_id'];?>" class="popup-modal"><span class="glyphicon glyphicon-share-alt"></span>Share</a>
										
										<!--
										<a href="#share-option" class="popup-modal"><span class="glyphicon glyphicon-cog"></span> 
											<span class="glyphicon glyphicon-share-alt"></span>Share
										</a>
										-->
									</li>
								</ul>											
							</div>
						</div>
					</div>
					<a href="javascript:void(0)"><span class="glyphicon glyphicon-envelope"></span>Message</a> 
                  <input type="hidden" name="pid" id="pid" value="<?php echo $post['_id'];?>" />
                  
                <?php
                if($post['is_timeline'] == '1')
                {
                    $notification = Notification::find()->where(['notification_type' => 'sharepost','user_id' => (string)$user_id,'share_id' => (string)$post['_id']])->one();
                    $ntype = 'timeline';
                }
                else
                {
                    $notification = Notification::find()->where(['notification_type' => 'tag_friend','user_id' => (string)$user_id,'post_id' => (string)$post['_id']])->one();
                    $ntype = 'tagfriend';
                }
                if($notification && $notification['review_setting'] == 'Enabled' && isset($_GET['r']) && $_GET['r'] == 'site/view-post')
                {
                ?>
                    <a href="javascript:void(0)" id="add_to_wall_<?= $post['_id']?>" onclick="add_to_wall('<?= $post['_id']?>','<?= $ntype?>')"><span class="glyphicon glyphicon-ok"></span>Add to Wall</a>
                <?php } ?>
                  
                <?php
                //echo $tag_review_tag;echo $post['is_deleted'];echo $post['shared_from'];
                if($tag_review_tag == 'Enabled' && $post['is_deleted'] == '1' && $post['shared_from'] == $session_user_id){ ?>
                    <a href="javascript:void(0)" id="approve_tag_<?= $post['_id']?>" onclick="approve_tag('<?= $post['_id']?>')"><span class="glyphicon glyphicon-ok"></span>Approve</a>
                <?php } ?>
                    
                    
              </div>
                 
               
				<div class="pextra-holder brounded">
					
					
						<?php 
						 $like_names = Like::getLikeUserNames((string)$post['_id']);
						 $like_buddies = Like::getLikeUser((string)$post['_id']);
                                                // print_r($like_buddies);
						?>
                                    <div class="count_summery" id="like_summery_<?php echo $post['_id'];?>" data-placement="vertical">
						  <?php  if($like_names['names'] != '' ){?>  
							
                                        <a href="javascript:void(0)" class="show-pop" data-pid="<?php echo $post['_id'];?>" id="like_data_<?php  echo $post['_id'];?>" data-placement="vertical"  >   
									<span id="like-name-count-<?php  echo $post['_id']; ?>">
										<?php 
                                                                                if($like_names['count']>1)
                                                                                    $other = ' and '. $like_names['count']. ' Others ';
                                                                                else
                                                                                    $other = '';
                                                                                if($like_names['like_ctr']>0 && $like_names['count']>0) { echo '<span class="glyphicon glyphicon-thumbs-up"></span>'.$like_names['names'] .$other; }else if($like_names['count']<=0){ echo '<span class="glyphicon glyphicon-thumbs-up"></span>'.$like_names['names'] ;}else { echo ucfirst($like_names['login_user_details']['user']['fname']).' '.ucfirst($like_names['login_user_details']['user']['lname']);                                             
                                                    ?><?php  } ?></span></a>
							
						<?php }?>
                                         </div>
					
                                    <div id="like_buddies_<?php echo $post['_id'];?>" data-pid="<?php echo $post['_id'];?>" style="display:none">
                                
  <?php 

                                            $like_peoples = '<ul>';
                                     
                                            foreach($like_buddies AS $like_buddy)
                                            { 
                                             $like_peoples .='<li>'. ucfirst($like_buddy['user']['fname']). ' '.ucfirst($like_buddy['user']['lname']).'</li>' ;
                                            }
                                            $like_peoples .= '</ul>';
                                            echo $like_peoples;
                                            ?>
                                          </div>
                                   
	

               <?php if(count($init_comments)>2){ ?><div class="view-prev"> <a href="javascript:void(0)" class="view-link-<?php echo $post['_id']; ?>" onclick="show_previous('<?php echo $post['_id'];?>')">View more comments</a><span class="comment-ctr" id="commment-ctr-<?php echo $post['_id']; ?>"><?php echo count($init_comments) .' of '.count($comments);?></span></div><?php } ?>
				
				
					
               <input type="hidden" name="from_ctr" id="from_ctr_<?php echo $post['_id'];?>" value="<?php echo count($init_comments);?>">		
               <input type="hidden" name="to_ctr" id="to_ctr_<?php echo $post['_id'];?>" value="<?php echo count($comments);?>">
               <div class="all-comments">
			   
			<div id='init_commmets_<?php echo $post['_id'];?>'   >
                            <div id="init_comment_display_<?php echo $post['_id'];?>"   >
                
                        <?php 
                     if(count($init_comments)>0){
                        foreach($init_comments as $init_comment)
                        { 
                            $comment_time = Yii::$app->EphocTime->time_elapsed_A(time(),$init_comment['updated_date']);
                            $hidecomments = new HideComment();
                            $hidecomments = HideComment::find()->where(['user_id' => (string)$user_id])->one();
                            $hide_comments_ids = explode(',',$hidecomments['comment_ids']); 
                            if(!(in_array($init_comment['_id'],$hide_comments_ids)))
                            {
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
                            $init_comment_img = $this->getimage($init_comment['user']['_id'],'thumb');
                            ?>
                             <img src="<?= $init_comment_img?>" class="img-responsive" height="35" width="35" alt="">
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
										<div class="tarrow">									
											<a class="alink" href="javascript:void(0)" class="close-comment" title="<?=$atool_post?>">
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
                                                    $comment_comment_img = $this->getimage($comment_reply['user']['_id'],'thumb');
                                                    ?>
                                                            <img src="<?= $comment_comment_img?>" class="img-responsive" height="35" width="35" alt="">
                                                   </a></div>

                                                    <div class="inner_desc comment_wrapper_<?php echo $comment_reply['_id'];?> comment_<?php echo $init_comment['_id']?>" id="comment-holder">
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
                                                                                                                                    <div class="tarrow">									
																		<a class="alink" href="javascript:void(0)" class="close-comment" title="<?=$atool_post?>">
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
							<?php
                                                            $comment_image = $this->getimage($result['_id'],'thumb');
                                                            ?>
                                                            <img src="<?= $comment_image?>" class="img-responsive" height="35" width="35" alt="">
							</a>
						</div>
                                         <form name="imageReplyForm" id="imageReplyForm" enctype="multipart/form-data">
						<div class="ac_ttarea">
							<textarea name="reply_txt" data-postid="<?php echo $post['_id'];?>" data-commentid="<?php echo $init_comment['_id']?>" id="reply_txt_<?php echo $init_comment['_id'];?>" placeholder="Write a Reply..." class="reply_class"></textarea>
							<div id="image-holder-reply-<?php echo $init_comment['_id'];?>" class="comment-imgpreview" ><img height="200" width="200" src="" id="replyimg_<?php echo $init_comment['_id'];?>" /><a href="javascript:void(0)" onClick="close_cimg_preview('<?php echo $init_comment['_id'];?>','reply')"><i class="fa fa-close"></i></a></div>			
                                                       <div class="myLabel">
                                                        
                                                            <!-- uplaod image -->

                                                            <input type="file" multiple="true" required="" name="imageReply[]" id="imageReply_<?php echo $init_comment['_id'];?>" class="imgReply" data-commentid="<?php echo $init_comment['_id']?>" data-postid="<?php echo $init_comment['_id'];?>">

	<!--<span class="glyphicon glyphicon-camera tb-icon-fix001" aria-hidden="true"></span>--> </div>
						</div>
                                         </form>
					</div>
                            </div>
							
                                                
                    </div>

                        <?php }} ?>
                        <?php }//}?>
                    </div>
              
                </div>
        
               
               </div> 
                            <?php if($post['comment_setting'] != 'Disable'){?>
				<div class="add_comment">
						<div class="ac_img" id="user_img_<?php echo $post['_id'];?>"> 
							<a class="profilelink no-ul" href="<?php $id =  $result['_id']; echo Url::to(['userwall/index', 'id' => "$id"]); ?>">
                                                            <?php
                                                            $comment_image = $this->getimage($result['_id'],'thumb');
                                                            ?>
                                                            <img src="<?= $comment_image?>" class="img-responsive" height="35" width="35" alt="">
							</a>
						</div>
                                    <form name="imageCommentForm" id="imageCommentForm" enctype="multipart/form-data">
						<div class="ac_ttarea">
<textarea name="comment_txt" data-postid="<?php echo $post['_id'];?>" id="comment_txt_<?php echo $post['_id'];?>" placeholder="Write a comment..." class="comment_class"></textarea>
							<div id="image-holder-comment-<?php echo $post['_id'];?>" class="comment-imgpreview" ><img src="" id="commentimg_<?php echo $post['_id'];?>"/><a href="javascript:void(0)" onClick="close_cimg_preview('<?php echo $post['_id'];?>','comment')"><i class="fa fa-close"></i></a></div>
							<div class="myLabel">
                                                            
                                                            <!-- uplaod image -->

                                                            <input type="file" multiple="true" required="" name="imageComment[]" id="imageComment_<?php echo $post['_id'];?>" class="imgComment" data-postid="<?php echo $post['_id'];?>">

											<!--<span class="glyphicon glyphicon-camera tb-icon-fix001" aria-hidden="true"></span>-->
										</div>
                                                        <?php if($existing_posts == ''){?>
                                                        <input type="hidden" id="last_inserted" name="last_inserted" value="<?php echo $post['_id']; ?>"/>
                                                        <?php }?>
						</div>
                                    </form>
					</div>
                                                       <?php } ?>
				
			</div>
            </div>
               <?php }
               
        }
            
    }
}
