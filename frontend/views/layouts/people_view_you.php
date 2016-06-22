<?php
/* @var $this \yii\web\View */
/* @var $content string */
use yii\helpers\Html;
use frontend\assets\AppAsset;
$baseUrl = Yii::getAlias('@web');
?>

<div class="content-box bshadow">
                    <div class="cbox-title">
                        How often people viewed you
                    </div>
                    <div class="cbox-desc">
                        <p>16 Profile Views</p>
                        <img src="<?= $baseUrl ?>/images/graph.png"/>
                    </div>
                </div>