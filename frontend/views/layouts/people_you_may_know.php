<?php
/* @var $this \yii\web\View */
/* @var $content string */
use yii\helpers\Html;
use frontend\assets\AppAsset;
$baseUrl = Yii::getAlias('@web');
?>

<div class="content-box bshadow">
                    <div class="cbox-title">
                        People you may know
                    </div>
                    <div class="cbox-desc">
                        <ul class="people-list">
                            <li>
                                <div class="people-box">
                                    <div class="img-holder"><img src="<?= $baseUrl ?>/images/people-1.png"/></div>
                                    <div class="desc-holder">
                                        <a href="javascript:void(0)" class="userlink">Ali Musa</a>
                                        <span class="info">23 mutual friends</span>
                                        <a href="javascript:void(0)" class="btn btn-default">Add</a>
                                        <a href="javascript:void(0)" class="close-btn"><i class="fa fa-close"></i></a>
                                    </div>                              
                                </div>                          
                            </li>
                            <li>
                                <div class="people-box">
                                    <div class="img-holder"><img src="<?= $baseUrl ?>/images/people-2.png"/></div>
                                    <div class="desc-holder">
                                        <a href="javascript:void(0)" class="userlink">Adel Google</a>
                                        <span class="info">5 mutual friends</span>
                                        <a href="javascript:void(0)" class="btn btn-default">Add</a>
                                        <a href="javascript:void(0)" class="close-btn"><i class="fa fa-close"></i></a>
                                    </div>                              
                                </div>                          
                            </li>
                            <li>
                                <div class="people-box">
                                    <div class="img-holder"><img src="<?= $baseUrl ?>/images/people-3.png"/></div>
                                    <div class="desc-holder">
                                        <a href="javascript:void(0)" class="userlink">Ada Hasanat</a>
                                        <span class="info">51 mutual friends</span>
                                        <a href="javascript:void(0)" class="btn btn-default disabled">Sent</a>
                                        <a href="javascript:void(0)" class="close-btn"><i class="fa fa-close"></i></a>
                                    </div>                              
                                </div>                          
                            </li>
                            <li>
                                <div class="pull-right"><a href="javascript:void(0)">View All</a></div>                     
                            </li>                       
                        </ul>
                    </div>
                </div>