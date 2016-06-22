<?php
/* @var $this \yii\web\View */
/* @var $content string */
use yii\helpers\Html;
use frontend\assets\AppAsset;
$baseUrl = Yii::getAlias('@web');
?>
                    <form action="">
                        <div class="top-stuff">
                            <div class="npost-title">
                                <div class="sliding-middle-out anim-area">
                                    <input type="text" class="title" placeholder="Title of this post" id="title">
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
                                <i class="fa fa-edit main-icon"></i>
                                <div class="desc">
                                    <textarea id="textInput" placeholder="What's new with you?"></textarea>                           
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
                                    </div>
                                </div>
                                <div class="post-tag">
                                    <div class="areatitle">With</div>
                                    <div class="areadesc">
                                        <div class="ptag select2-holder">
                                            <select id="taginput" class="select2" multiple="" tabindex="-1" aria-hidden="true">
                                                <option>abc</option>
                                                <option>def</option>
                                                <option>mno</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="post-location">
                                    <div class="areatitle">At</div>
                                    <div class="areadesc">
                                        <input type="text" id="cur_loc" class="plocation getplacelocation" placeholder="Where are you?"/>
                                    </div>
                                </div>
                            </div>
                            <div class="post-bcontent">
                                <div class="post-toolbox">
                                    <a href="javascript:void(0)" class="add-photos">
                                        <div class="custom-file">
                                            <div class="title"><span class="glyphicon glyphicon-camera"></span></div>                                       
                                            <input type="file" id="imageFile1" name="upload[]" class="upload custom-upload" title="Choose a file to upload" required="" data-class=".main-content .post-photos .img-row" multiple="true"/>
                                        </div>
                                    </a>
                                    <a href="javascript:void(0)" class="add-tag"><span class="glyphicon glyphicon-user"></span></a>
                                    <a href="javascript:void(0)" class="add-location"><span class="glyphicon glyphicon-map-marker"></span></a>                          
                                    <a href="javascript:void(0)" class="add-title"><span class="glyphicon glyphicon-text-size"></span></a>
                                </div>
                                <div class="post-bholder">
                                    <a href="javascript:void(0)" onclick="addNewPost()" class="btn btn-primary postbtn"><span class="glyphicon glyphicon-send"></span>Post</a>
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
									
									<!--<input type="hidden" name="imgfilecount" id="imgfilecount" value="0" />-->
									<input type="hidden" name="post_privacy" id="post_privacy" value="Public"/>
									<input type="hidden" name="share_setting" id="share_setting" value="Enable"/>
									<input type="hidden" name="comment_setting" id="comment_setting" value="Enable"/>
									<input type="hidden" name="link_title" id="link_title" />
									<input type="hidden" name="link_url" id="link_url" />
									<input type="hidden" name="link_description" id="link_description" />
									<input type="hidden" name="link_image" id="link_image" />
									<input type="hidden" id="hiddenCount" value="1">
									
                                    
                                </div>
                            </div>
                        </div>              
                        
                    </form>