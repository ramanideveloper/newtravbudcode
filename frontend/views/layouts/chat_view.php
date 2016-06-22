<?php
/* @var $this \yii\web\View */
/* @var $content string */
use yii\helpers\Html;
use frontend\assets\AppAsset;
$baseUrl = Yii::getAlias('@web');
?>

<div class="float-chat anim-side">
                <div class="chat-button float-icon"><span class="icon-holder">icon</span></div>
                <div class="chat-section">
                    <a href="javascript:void(0)" class="close-chat"><i class="fa fa-close"></i></a>
                    <span class="ctitle">Online</span>
                    <ul class="chat-online chat-ul">                
                        <li>
                            <div class="chat-summery">
                                <a href="javascript:void(0)">
                                    <span class="img-holder"><img src="<?= $baseUrl ?>/images/chat-1.png" /><span class="badge">3</span></span>
                                    
                                    <span class="desc-holder">
                                        Oliver Rogers
                                        <span class="info">Graphics Designer</span>                             
                                    </span>
                                </a>
                            </div>
                        </li>
                        <li>
                            <div class="chat-summery">
                                <a href="javascript:void(0)">
                                    <span class="img-holder"><img src="<?= $baseUrl ?>/images/chat-2.png" /><span class="badge">12</span></span>
                                    <span class="desc-holder">
                                        Joe Jones
                                        <span class="info">Web Designer</span>
                                    </span>
                                </a>
                            </div>
                        </li>
                        <li>
                            <div class="chat-summery">
                                <a href="javascript:void(0)">
                                    <span class="img-holder"><img src="<?= $baseUrl ?>/images/chat-3.png" /></span>
                                    <span class="desc-holder">
                                        Adman Cruz
                                        <span class="info">Writer</span>
                                    </span>
                                </a>
                            </div>
                        </li>               
                    </ul>
                    <span class="ctitle">Away</span>
                    <ul class="chat-away chat-ul">
                        <li>
                            <div class="chat-summery">
                                <a href="javascript:void(0)">
                                    <span class="img-holder"><img src="<?= $baseUrl ?>/images/chat-4.png" /></span>
                                    <span class="desc-holder">
                                        Elina Grey
                                        <span class="info">Cook</span>
                                    </span>
                                </a>
                            </div>
                        </li>
                        <li>
                            <div class="chat-summery">
                                <a href="javascript:void(0)">
                                    <span class="img-holder"><img src="<?= $baseUrl ?>/images/chat-5.png" /></span>
                                    <span class="desc-holder">
                                        Salma Musa
                                        <span class="info">Photographer</span>
                                    </span>
                                </a>
                            </div>
                        </li>           
                    </ul>
                    <span class="ctitle">Offline</span>
                    <ul class="chat-offline chat-ul">
                        <li>
                            <div class="chat-summery">
                                <a href="javascript:void(0)">
                                    <span class="img-holder"><img src="<?= $baseUrl ?>/images/chat-6.png" /></span>
                                    <span class="desc-holder">
                                        Nathan Moore
                                        <span class="info">UX Designer</span>
                                    </span>
                                </a>
                            </div>
                        </li>
                        <li>
                            <div class="chat-summery">
                                <a href="javascript:void(0)">
                                    <span class="img-holder"><img src="<?= $baseUrl ?>/images/chat-7.png" /></span>
                                    <span class="desc-holder">
                                        Kelly Mark
                                        <span class="info">Hair Stylist</span>
                                    </span>
                                </a>
                            </div>
                        </li>   
                        <li>
                            <div class="chat-summery">
                                <a href="javascript:void(0)">
                                    <span class="img-holder"><img src="<?= $baseUrl ?>/images/chat-8.png" /></span>
                                    <span class="desc-holder">
                                        Jason Gomez
                                        <span class="info">CEO</span>
                                    </span>
                                </a>
                            </div>
                        </li>                   
                    </ul>
                </div>
            
            </div>
            <div class="ad-section">
                <a href="javascript:void(0)"><img src="<?= $baseUrl ?>/images/ad-1.png"/></a>
                <a href="javascript:void(0)"><img src="<?= $baseUrl ?>/images/ad-2.png"/></a>
            </div>