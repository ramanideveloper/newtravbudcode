<?php
/* @var $this \yii\web\View */
/* @var $content string */
use yii\helpers\Html;
use frontend\assets\AppAsset;
use frontend\models\UserForm;

$baseUrl = Yii::getAlias('@web');

$session = Yii::$app->session;
$email = $session->get('email'); 
$user_id = $session->get('user_id');

$result = UserForm::find()->where(['email' => $email])->one();
?>

 <div class="sidemenu-holder">
            <div class="sidemenu">
                <div class="side-user">
                    <span class="img-holder"><img src="<?= $baseUrl ?>/images/demo-profile.jpg"/></span>
                    <span class="desc-holder">dfdf<?= ucfirst($result['fname']);?></span>
                </div>
                <div class="sidemenu-ul">
                    <ul>
                        <li class="lm-travfeed active"><span>icon</span><a href="javascript:void(0)">Travfeed</a></li>
                        <li class="lm-photography"><span>icon</span><a href="javascript:void(0)">Photography</a></li>
                        <li class="lm-travtube"><span>icon</span><a href="javascript:void(0)">Travtube</a></li>
                        <li class="lm-commeve"><span>icon</span><a href="javascript:void(0)">Community Events</a></li>
                        <li class="lm-divider"></li>
                        <li class="lm-waround"><span>icon</span><a href="javascript:void(0)">Who is around</a></li>
                        <li class="lm-tbuddy"><span>icon</span><a href="javascript:void(0)">Travel Buddy</a></li>
                        <li class="lm-lhosts"><span>icon</span><a href="javascript:void(0)">Local Hosts</a></li>
                        <li class="lm-gettouch"><span>icon</span><a href="javascript:void(0)">Get In Touch</a></li>
                        <li class="lm-divider"></li>
                        <li class="lm-hire"><span>icon</span><a href="javascript:void(0)">Hire A Guide</a></li>
                        <li class="lm-texp"><span>icon</span><a href="javascript:void(0)">Trip Experience</a></li>
                        <li class="lm-tstories"><span>icon</span><a href="javascript:void(0)">Travel Stories</a></li>
                        <li class="lm-thelpers"><span>icon</span><a href="javascript:void(0)">Travel Helpers</a></li>
                        <li class="lm-divider"></li>
                        <li class="lm-groups"><span>icon</span><a href="javascript:void(0)">Groups</a></li>
                        <!--<li class="lm-pages"><a href="javascript:void(0)">Pages</a></li>-->
                    </ul>
                </div>
            </div>
        </div>