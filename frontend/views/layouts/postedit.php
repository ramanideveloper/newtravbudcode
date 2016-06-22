<?php
/* @var $this \yii\web\View */
/* @var $content string */
use yii\helpers\Html;
use frontend\assets\AppAsset;
$baseUrl = Yii::getAlias('@web');
?>
<div id="editpost-popup" class="mfp-hide white-popup-block popup-area">
        <div class="popup-title">
            <!--<h3>Edit Post</h3>-->
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
                                            <li><a href="javascript:void(0)">Cancel Post</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    <div class="npost-content">
                        <div class="post-mcontent">                                                     
                            <div class="desc paddfix">
                                <textarea>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec sed varius risus. Duis rhoncus eros et pellentesque imperdiet. Praesent pharetra rutrum eros. In nec nulla id enim. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec sed varius risus. Duis rhoncus eros et pellentesque imperdiet. Praesent pharetra rutrum eros. In nec nulla id enim. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec sed varius risus. Duis rhoncus eros et pellentesque imperdiet. Praesent pharetra rutrum eros. In nec nulla id enim. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec sed varius risus. Duis rhoncus eros et pellentesque imperdiet. Praesent pharetra rutrum eros. In nec nulla id enim. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec sed varius risus. Duis rhoncus eros et pellentesque imperdiet. Praesent pharetra rutrum eros. In nec nulla id enim. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec sed varius risus. Duis rhoncus eros et pellentesque imperdiet. Praesent pharetra rutrum eros. In nec nulla id enim.</textarea>
                                <a href="javascript:void(0)" class="readmore">Read More</a>
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
                            <div class="post-photos">
                                <div class="img-row">
                                    <div class="img-box">
                                        <img src="<?= $baseUrl ?>/images/post-img2.jpg" class="thumb-image">
                                        <a data-code="1462788734000" class="removePhotoFile" href="javascript:void(0)"><i class="fa fa-close"></i></a>
                                    </div>
                                    <div class="img-box">
                                        <img src="<?= $baseUrl ?>/images/post-img3.jpg" class="thumb-image">
                                        <a data-code="1462788734010" class="removePhotoFile" href="javascript:void(0)"><i class="fa fa-close"></i></a>
                                    </div>
                                    <div class="img-box">
                                        <div class="custom-file addimg-box">
                                            <div class="addimg-icon"><span class="glyphicon glyphicon-plus"></span></div>
                                            <input type="file" multiple="true" data-class=".post-photos .img-row" required="" title="Choose a file to upload" class="upload custom-upload" name="upload">
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                            <div class="post-tag">
                                <div class="areatitle">With</div>
                                <div class="areadesc">
                                    <input type="text" class="ptag" placeholder="Who are you with?" value="tags goes here"/>
                                </div>
                            </div>
                            <div class="post-location">
                                <div class="areatitle">At</div>
                                <div class="areadesc">
                                    <input type="text" class="plocation" placeholder="Where are you?" value="Ahmedabad"/>
                                </div>
                            </div>
                        </div>
                        <div class="post-bcontent">
                            <div class="post-toolbox">
                                <a href="javascript:void(0)" class="add-photos">
                                    <div class="custom-file">
                                        <div class="title"><span class="glyphicon glyphicon-camera"></span></div>                                       
                                        <input type="file" name="upload" class="upload custom-upload" title="Choose a file to upload" required="" data-class=".popup-area .post-photos .img-row" multiple="true"/>
                                    </div>
                                </a>
                                <a href="javascript:void(0)" class="add-tag"><span class="glyphicon glyphicon-user"></span></a>
                                <a href="javascript:void(0)" class="add-location"><span class="glyphicon glyphicon-map-marker"></span></a>                          
                                <a href="javascript:void(0)" class="add-title"><span class="glyphicon glyphicon-text-size"></span></a>
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