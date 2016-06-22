<?php 
use frontend\models\Like;
use frontend\models\LoginForm;
use frontend\models\UserForm;
use yii\helpers\Url;
use frontend\models\UnfollowFriend;
use frontend\models\HidePost;
use frontend\models\Comment;
use frontend\models\PostForm;
use frontend\models\SavePost;
use frontend\models\Personalinfo;
use frontend\models\HideComment;
use frontend\models\Friend;

$session = Yii::$app->session;
$email = $session->get('email'); 
$result = UserForm::find()->where(['email' => $email])->one();
$userid = $user_id =  $session->get('user_id');

     
$unfollow = new UnfollowFriend();
$unfollow = UnfollowFriend::find()->where(['user_id' => (string)$user_id])->one();
$profile_tip_counter = 1;
//$gallery_counter = 1;
//echo $_GET['wall'];exit;

foreach($posts as $post)
{
	$existing_posts = '1';
    $this->context->display_last_post($post['_id'],$existing_posts);
    //$this->context->display_last_post($post['_id'],$existing_posts,$gallery_counter);
	//$gallery_counter++;
}

?>
<script>

$(document).ready(function(){
        $('a.show-pop').each(function(i,item){
            var settings = {
                trigger:'hover',
                title:'',
                html: true,
                selector: '[rel="popover"]', //Sepcify the selector here
                content: function () {
                    var pid =  $(this).data('pid');
                    return $('#like_buddies_'+pid).html();
                },
                multi:true,						
                closeable:false,		
                delay:300,
                padding:true			
        };
            $('a.show-pop').webuiPopover('destroy').webuiPopover(settings);

        });
    });
   
</script>