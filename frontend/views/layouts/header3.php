<?php
/* @var $this \yii\web\View */
/* @var $content string */
use yii\helpers\Html;
use frontend\assets\AppAsset;
$baseUrl = Yii::getAlias('@web');
?>

<div class="tab-whitebar">
                <div class="abs-search">
                    <form>
                        <input type="text" class="search-input" placeholder="Search..."/>
                        <span class="searchbtn" value="">
                            <a href="javascript:void(0)"><i class="fa fa-search"></i></a>
                        </span>
                    </form>
                </div>
                <div class="mobile-whitebar">
                    <div class="mobile-menu">
                        <a href="javascript:void(0)"><i class="fa fa-reorder"></i></a>
                    </div>
                    <div class="not-icons">
                        <div class="not-friends noticon">               
                            <div class="dropdown dropdown-custom ">
                                <a href="javascript:void(0)" class="dropdown-toggle"  data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                    <img src="<?= $baseUrl ?>/images/noticon-friend-mbl.png"/><span class="badge">3</span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <div class="fr-list">
                                            <span class="not-title">Friend Requests</span>
                                            <ul class="fr-listing">
                                                <li>
                                                   <form>                                               
                                                        <div class="fr-holder">
                                                            <div class="img-holder">
                                                                <a href="javascript:void(0)"><img class="img-responsive" src="<?= $baseUrl ?>/images/female.png"></a>
                                                            </div>
                                                            <div class="desc-holder">
                                                                <div class="desc">
                                                                    <a href="javascript:void(0)">Abc Def</a>
                                                                    <span class="mf-info"></span>
                                                                </div>
                                                                <div class="fr-btn-holder">
                                                                    <button class="btn btn-primary btn-sm">Confirm</button>
                                                                    <button class="btn btn-primary btn-sm">Delete Reqeust</button>
                                                                </div>
                                                            </div>                                                          
                                                        </div>
                                                   </form>
                                                </li>
                                                <li>
                                                   <form>                                               
                                                        <div class="fr-holder">
                                                            <div class="img-holder">
                                                                <a href="javascript:void(0)"><img class="img-responsive" src="<?= $baseUrl ?>/images/male.png"></a>
                                                            </div>
                                                            <div class="desc-holder">
                                                                <div class="desc">
                                                                    <a href="javascript:void(0)">Abc Def</a>
                                                                    <span class="mf-info"></span>
                                                                </div>
                                                                <div class="fr-btn-holder">
                                                                    <button class="btn btn-primary btn-sm">Confirm</button>
                                                                    <button class="btn btn-primary btn-sm">Delete Reqeust</button>
                                                                </div>
                                                            </div>                                                          
                                                        </div>
                                                   </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                  </ul>
                            </div>
                        </div>
                        <div class="not-messages noticon">              
                            <div class="dropdown dropdown-custom ">
                                <a href="javascript:void(0)" class="dropdown-toggle"  data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                    <img src="<?= $baseUrl ?>/images/noticon-message-mbl.png"/>
                                </a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <div class="msg-list">
                                            <span class="not-title">Messages</span>
                                            <div class="no-listcontent">
                                                No New Messages
                                            </div>
                                        </div>
                                    </li>
                                  </ul>
                            </div>
                        </div>
                        <div class="not-notification noticon">              
                            <div class="dropdown dropdown-custom ">
                                <a href="javascript:void(0)" class="dropdown-toggle"  data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                    <img src="<?= $baseUrl ?>/images/noticon-notification-mbl.png"/><span class="badge">10</span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <div class="noti-list">
                                            <span class="not-title">Notifications</span>
                                            <ul class="noti-listing">
                                                <li>
                                                    <div class="noti-holder">
                                                        <a href="javascript:void(0)">
                                                            <span class="img-holder">
                                                                <img class="img-responsive" src="<?= $baseUrl ?>/images/female.png">
                                                            </span>
                                                            <span class="desc-holder">
                                                                <span class="desc">
                                                                    <span class="btext">Abc Def</span> replied on your comment:
                                                                </span>
                                                                <span class="time-stamp">
                                                                    <i class="fa fa-globe"></i> Just Now
                                                                </span>
                                                            </span>
                                                        </a>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="noti-holder">
                                                        <a href="javascript:void(0)">
                                                            <span class="img-holder">
                                                                <img class="img-responsive" src="<?= $baseUrl ?>/images/female.png">
                                                            </span>
                                                            <span class="desc-holder">
                                                                <span class="desc">
                                                                    <span class="btext">Abc Def</span> added a photo
                                                                </span>
                                                                <span class="time-stamp">
                                                                    <i class="fa fa-user"></i> 20 mins ago
                                                                </span>
                                                            </span>
                                                        </a>
                                                    </div>
                                                </li>
                                            </ul>
                                            <!--
                                            <div class="no-listcontent">
                                                No New Notifications
                                            </div>
                                            -->
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        
                    </div>
                    <div class="chat-icon">
                        <a href="javascript:void(0)"><img src="<?= $baseUrl ?>/images/chat-icon-mbl.png"/></a>
                    </div>
                </div>
                
            </div>  