<?php
/* @var $this \yii\web\View */
/* @var $content string */
use yii\helpers\Html;
use frontend\assets\AppAsset;
$baseUrl = Yii::getAlias('@web');
?>
<div id="sharepost-popup" class="mfp-hide white-popup-block popup-area">
        <div class="popup-title">
            <!--<h3>Share Post</h3>-->
            <a class="popup-modal-dismiss close-popup" href="javascript:void(0)"><span class="glyphicon glyphicon-remove"></span></a>
        </div>
        <div class="popup-content">
            <div class="new-post active">
                <form action="">
                    <div class="top-stuff">
                        <div class="postuser-info">
                            <div class="img-holder"><img src="<?= $baseUrl ?>/images/demo-profile.jpg"/></div>
                            <div class="desc-holder"><a href="javascript:void(0)">Nimish Parekh</a></div>
                        </div>
                        <div class="npost-title">
                            <div class="sliding-middle-out anim-area">
                                <input type="text" class="title" placeholder="Title of this post" value="Title of Post">
                            </div>
                        </div>
                        <div class="sharing-option">
                            <div class="custom-drop share-drop">
                                <div class="dropdown dropdown-custom">
                                    <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                        <span class="glyphicon glyphicon-edit"></span>Share on your wall <span class="caret"></span>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li><a href="javascript:void(0)"><span class="glyphicon glyphicon-edit"></span>Share on your wall</a></li>
                                        <li><a href="javascript:void(0)"><span class="glyphicon glyphicon-user"></span>Share on a friend's wall</a></li>
                                        <li><a href="javascript:void(0)"><span class="glyphicon glyphicon-globe"></span>Share on group wall</a></li>
                                        <li><a href="javascript:void(0)"><span class="glyphicon glyphicon-cog"></span>Share on Facebook</a></li>
                                    </ul>
                                </div>
                            </div>  
                        </div>
                        <div class="settings-icon">                     
                            <div class="dropdown dropdown-custom dropdown-small resist">
                                <a href="javascript:void(0)" class="dropdown-toggle" role="button" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-ellipsis-h"></i>
                                </a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <ul class="echeck-list">
                                            <li><a href="javascript:void(0)"><i class="fa fa-check"></i>Disable Sharing</a></li>
                                            <li><a href="javascript:void(0)"><i class="fa fa-check"></i>Disable Comments</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    <div class="npost-content">
                        <div class="post-mcontent">                         
                            <div class="desc paddfix auto-tt">
                                <textarea>Say something about this...</textarea>
                            </div>
                            <!-- /* video preview */
                            <div class="pvideo-holder">
                                <div class="img-holder"><img src="<?= $baseUrl ?>/images/vimg.jpg"/></div>
                                <div class="desc-holder">
                                    <h4><a href="javascript:void(0)">A simple way to break a bad habit | Judson Brewer</a></h4>
                                    <p>
                                    Can we break bad habits by being more curious about them? Psychiatrist Judson Brewer studies the relationship between mindfulness and addiction — from smokin...
                                    </p>
                                </div>
                                <a href="javascript:void(0)" class="remove-vlink"><span class="glyphicon glyphicon-remove"></span></a>
                            </div>
                            -->
                            
                            <div class="org-post">                          
                                <div class="post-holder">                               
                                    <div class="post-content">
                                        <div class="post-details">
                                            <div class="post-title">Petra is Nice</div>
                                            <div class="post-desc">
                                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec sed varius risus. Duis rhoncus eros et pellentesque imperdiet. Praesent pharetra rutrum eros. In nec nulla id enim faucibus pellentesque quis at dolor. Aenean a cursus quam, et fringilla tellus. Vivamus eu lorem est. Donec non urna sit amet arcu dictum dapibus.</p>                         
                                            </div>
                                        </div>
                                        <div class="post-img-holder">
                                            <div class="post-img one-img gallery">
                                                <div class="pimg-holder"><a href="images/post-img.jpg" rel="prettyPhoto[gallery1]"><img src="<?= $baseUrl ?>/images/post-img.jpg"/></a></div>
                                            </div>
                                        </div>
                                    </div>                              
                                    <div class="sharepost-info">
                                        <p><a href="javascript:void(0)">Nimish Parekh</a> with <a href="javascript:void(0)">Markand Trivedi</a> and <a href="javascript:void(0)">3 others</a> at <span class="sharepost-location">Ahmedabad</span></p>
                                        <span class="timestamp">4 Apr 2011<span class="glyphicon glyphicon-globe"></span></span>                                        
                                    </div>
                                </div>  
                                <div class="show-fullpost-holder">
                                    <a href="javascript:void(0)" class="show-fullpost">Show All <span class="glyphicon glyphicon-arrow-down"></span></a>
                                </div>
                            </div>
                            
                            <div class="post-tag">
                                <div class="areatitle">With</div>
                                <div class="areadesc">
                                    <input type="text" class="ptag" placeholder="Who are you with?"/>
                                </div>
                            </div>
                            <div class="post-location">
                                <div class="areatitle">At</div>
                                <div class="areadesc">
                                    <input type="text" class="plocation" placeholder="Where are you?"/>
                                </div>
                            </div>
                        </div>
                        
                        <div class="post-bcontent">
                            <div class="post-toolbox">                              
                                <a href="javascript:void(0)" class="add-tag"><span class="glyphicon glyphicon-user"></span></a>
                                <a href="javascript:void(0)" class="add-location"><span class="glyphicon glyphicon-map-marker"></span></a>                              
                            </div>
                            <div class="post-bholder">
                                <a href="javascript:void(0)" class="btn btn-primary postbtn"><span class="glyphicon glyphicon-send"></span>Post</a>
                                <div class="custom-drop">
                                    <div class="dropdown dropdown-custom dropdown-xsmall">
                                        <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                            <span class="glyphicon glyphicon-lock"></span>Private <span class="caret"></span>
                                        </a>
                                        <ul class="dropdown-menu">
                                            <li><a href="javascript:void(0)"><span class="glyphicon glyphicon-lock"></span>Private</a></li>
                                            <li><a href="javascript:void(0)"><span class="glyphicon glyphicon-user"></span>Friends</a></li>
                                            <li><a href="javascript:void(0)"><span class="glyphicon glyphicon-globe"></span>Public</a></li>
                                            <li><a href="#custom-privacy-popup" class="popup-modal"><span class="glyphicon glyphicon-cog"></span>Custom</a></li>
                                        </ul>
                                    </div>
                                </div>  
                                <div class="post-loader"><img src="<?= $baseUrl ?>/images/post-loader.gif"/></div>
                                <input type="hidden" class="imgfile-count" value="0" />
                                <input type="hidden" class="counter">
                                
                            </div>
                        </div>
                    </div>              
                    
                </form>
            </div>
            
        </div>
    </div>