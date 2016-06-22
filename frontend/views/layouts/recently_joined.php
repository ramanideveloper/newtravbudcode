<?php
/* @var $this \yii\web\View */
/* @var $content string */
use yii\helpers\Html;
use frontend\assets\AppAsset;
$baseUrl = Yii::getAlias('@web');
?>
<div class="content-box bshadow">
                    <div class="cbox-title">
                        Recently joined
                    </div>
                    <div class="cbox-desc">
                        <div class="recent-list">
                            <div class="row">
                                <div class="recent-col"><div class="recent-box"><a href="javascript:void(0)"><img src="<?= $baseUrl ?>/images/recent-1.png"/></a></div></div>
                                <div class="recent-col"><div class="recent-box"><a href="javascript:void(0)"><img src="<?= $baseUrl ?>/images/recent-2.png"/></a></div></div>
                                <div class="recent-col"><div class="recent-box"><a href="javascript:void(0)"><img src="<?= $baseUrl ?>/images/recent-3.png"/></a></div></div>
                                <div class="recent-col"><div class="recent-box"><a href="javascript:void(0)"><img src="<?= $baseUrl ?>/images/recent-4.png"/></a></div></div>
                                <div class="recent-col"><div class="recent-box"><a href="javascript:void(0)"><img src="<?= $baseUrl ?>/images/recent-5.png"/></a></div></div>
                                <div class="recent-col"><div class="recent-box"><a href="javascript:void(0)"><img src="<?= $baseUrl ?>/images/recent-6.png"/></a></div></div>
                            </div>
                        </div>
                    </div>
                </div>