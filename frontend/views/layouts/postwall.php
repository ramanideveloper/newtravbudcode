<?php
/* @var $this \yii\web\View */
/* @var $content string */
use yii\helpers\Html;
use frontend\assets\AppAsset;
$baseUrl = Yii::getAlias('@web');
?>
    <div class="new-post">
        <?= \Yii::$app->view->renderFile('@app/views/layouts/postblock.php'); ?>
    </div>  
                <div class="post-list">
                    <div class="post-holder bshadow">
                        <div class="post-topbar">
                            <div class="post-userinfo">
                                <div class="img-holder">
                                    <div id="profiletip-1" class="profiletipholder">
                                        <span class="profile-tooltip">
                                            <img src="<?= $baseUrl ?>/images/demo-profile.jpg"/>
                                        </span>
                                        <span class="profiletooltip_content">
                                            <div class="profile-tip">
                                                        
                                                <div class="profile-tip-cover"><img src="<?= $baseUrl ?>/images/cover.jpg"></div>
                                                <div class="profile-tip-avatar">
                                                    <a href="#">
                                                        <img alt="user-photo" class="img-responsive" src="<?= $baseUrl ?>/images/demo-profile.jpg">
                                                    </a>
                                                </div>
                                                <div class="profile-tip-info">
                                                    <div class="cover-username"><a href="#">Markand Trivedi</a></div>
                                                    <div class="cover-headline">
                                                        <span class="ptip-icon"><i class="fa  fa-suitcase"></i></span>
                                                        Web Designer, Cricketer
                                                    </div>
                                                    <div class="profiletip-bio">
                                                        <span class="ptip-icon"><i class="fa fa-home"></i></span>
                                                        Lives in : <span>Gariyadhar</span>
                                                    </div>
                                                    <div class="profiletip-bio">
                                                        <span class="ptip-icon"><i class="fa fa-map-marker"></i></span>
                                                        Currently in : <span>Gariyadhar, Gujarat, India</span>
                                                    </div>

                                                </div>
                                                <div class="profile-tip-divider"></div>
                                                <div class="profile-tip-btn">
                                                    <a href="#" class="btn btn-primary btn-sm"><i class="fa fa-eye"></i>View Profile</a>
                                                </div>
                                            </div>
                                        </span>
                                    </div>
                                    
                                </div>
                                <div class="desc-holder">
                                    <a href="javascript:void(0)">Nimish Parekh</a>
                                    <span class="timestamp">2 hrs<span class="glyphicon glyphicon-globe"></span></span>
                                </div>
                            </div>
                            <div class="settings-icon">
                                <div class="dropdown dropdown-custom dropdown-med resist">
                                    <a href="javascript:void(0)" class="dropdown-toggle" role="button" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-ellipsis-h"></i>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <ul class="post-licon-list">
                                                <li class="hidepost">
                                                    <a href="javascript:void(0)">
                                                        <span class="glyphicon glyphicon-eye-close"></span>
                                                        Hide Post
                                                        <span>See fewer posts like this</span>
                                                    </a>
                                                </li>
                                                <li class="unfollow">
                                                    <a href="javascript:void(0)">
                                                        <span class="glyphicon glyphicon-ban-circle"></span>
                                                        Unfollow Person
                                                        <span>Stop seeing such posts but stay friends</span>
                                                    </a>
                                                </li>
                                            </ul>
                                            <div class="li-divider"></div>
                                            <ul class="post-sicon-list">
                                                <li><a href="javascript:void(0)"><span class="glyphicon glyphicon-warning-sign"></span>Report Post</a></li>
                                                <li><a href="javascript:void(0)" class="savepost-link"><span class="glyphicon glyphicon-bookmark"></span>Save Post</a></li>
                                                <li class="nicon"><a href="javascript:void(0)">Turn off notification for this post</a></li>
                                                <li class="nicon"><a href="javascript:void(0)">Mute Friend</a></li>
                                            </ul>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
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
                        <div class="clear"></div>
                        <div class="post-data">
                            <div class="post-actions">
                                <!--<a href="javascript:void(0)" class="pa-like"><span>icon</span>Like</a>-->
                                <span id="likeholder-1" class="likeholder">
                                    <span class="like-tooltip">
                                        <a href="javascript:void(0)" class="pa-like"><span>icon</span>Like</a>
                                    </span>
                                    <span class="tooltip_content">
                                       <ul class="like-ul">
                                        <li>User Name</li>
                                        <li>User Name</li>
                                        <li>User Name</li>
                                        <li>User Name</li>
                                        <li>User Name</li>
                                        <li>User Name</li>
                                        <li>User Name</li>
                                       </ul>
                                    </span>
                                </span>                         
                                <a href="javascript:void(0)" class="pa-comment"><span>icon</span>Comment</a>
                                <a href="#sharepost-popup" class="popup-modal pa-share"><span>icon</span>Share</a>
                                <a href="javascript:void(0)" class="pa-message"><span>icon</span>Message</a>
                            </div>
                            <div class="post-more">
                                <a href="javascript:void(0)" class="view-morec">View more comments</a>
                                <span class="total-comments">3 of 7</span>
                            </div>
                            <div class="post-comments">                     
                                <div class="pcomments">
                                    <div class="pcomment-earlier">
                                        <div class="pcomment-holder">
                                            <div class="pcomment main-comment">
                                                <div class="img-holder">
                                                    
                                                    <div id="commentptip-1" class="profiletipholder">
                                                        <span class="profile-tooltip">
                                                            <img src="<?= $baseUrl ?>/images/demo-profile.jpg"/>
                                                        </span>
                                                        <span class="profiletooltip_content">
                                                            <div class="profile-tip">
                                                                        
                                                                <div class="profile-tip-cover"><img src="<?= $baseUrl ?>/images/cover.jpg"></div>
                                                                <div class="profile-tip-avatar">
                                                                    <a href="#">
                                                                        <img alt="user-photo" class="img-responsive" src="<?= $baseUrl ?>/images/demo-profile.jpg">
                                                                    </a>
                                                                </div>
                                                                <div class="profile-tip-info">
                                                                    <div class="cover-username"><a href="#">Markand Trivedi</a></div>
                                                                    <div class="cover-headline">
                                                                        <span class="ptip-icon"><i class="fa  fa-suitcase"></i></span>
                                                                        Web Designer, Cricketer
                                                                    </div>
                                                                    <div class="profiletip-bio">
                                                                        <span class="ptip-icon"><i class="fa fa-home"></i></span>
                                                                        Lives in : <span>Gariyadhar</span>
                                                                    </div>
                                                                    <div class="profiletip-bio">
                                                                        <span class="ptip-icon"><i class="fa fa-map-marker"></i></span>
                                                                        Currently in : <span>Gariyadhar, Gujarat, India</span>
                                                                    </div>

                                                                </div>
                                                                <div class="profile-tip-divider"></div>
                                                                <div class="profile-tip-btn">
                                                                    <a href="#" class="btn btn-primary btn-sm"><i class="fa fa-eye"></i>View Profile</a>
                                                                </div>
                                                            </div>
                                                        </span>
                                                    </div>
                                                
                                                    
                                                </div>
                                                <div class="desc-holder">
                                                    <div class="normal-mode">
                                                        <div class="desc">
                                                            <a href="javascript:void(0)" class="userlink">Adel Hasanat</a>
                                                            <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh.</p>
                                                            
                                                        </div>
                                                        <div class="comment-stuff">
                                                            <div class="more-opt">
                                                                <!--<a href="javascript:void(0)" class="pa-like"><span>icon</span></a>-->
                                                                <span id="likeholder-2" class="likeholder">
                                                                    <span class="like-tooltip">
                                                                        <a href="javascript:void(0)" class="pa-like"><span>icon</span></a>
                                                                    </span>
                                                                    <span class="tooltip_content">
                                                                       <ul class="like-ul">
                                                                        <li>User Name1</li>
                                                                        <li>User Name1</li>
                                                                        <li>User Name1</li>
                                                                       </ul>
                                                                    </span>
                                                                </span>
                                                                <a href="javascript:void(0)" class="pa-reply reply-comment"><span>icon</span></a>
                                                                
                                                                <div class="dropdown dropdown-custom dropdown-xxsmall">
                                                                    <a href="javascript:void(0)class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                                                        <i class="fa fa-ellipsis-v"></i>
                                                                    </a>
                                                                    <ul class="dropdown-menu">
                                                                        <li><a href="javascript:void(0)" class="edit-comment"><span class="glyphicon glyphicon-pencil"></span>Edit</a></li>
                                                                        <li><a href="javascript:void(0)" class="delete-comment"><span class="glyphicon glyphicon-trash"></span>Delete</a></li>                                                  
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                            <div class="less-opt"><div class="timestamp">8h</div></div>
                                                        </div>                              
                                                    </div>
                                                    <div class="edit-mode">
                                                        <div class="desc">
                                                            <textarea class="editcomment-tt">Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh.</textarea>
                                                            <a href="javascript:void(0)" class="btn btn-primary btn-sm editcomment-cancel">Cancel</a>
                                                        </div>                                                                          
                                                    </div>
                                                
                                                </div>                              
                                            </div>
                                            <div class="clear"></div>
                                            <div class="comment-reply-holder comment-addreply">                                 
                                                <div class="addnew-comment comment-reply">                          
                                                    <div class="img-holder"><a href="javascript:void(0)"><img src="<?= $baseUrl ?>/images/demo-profile.jpg"/></a></div>
                                                    <div class="desc-holder">                                   
                                                        <div class="sliding-middle-out anim-area">
                                                            <textarea>Write a reply...</textarea>
                                                        </div>
                                                    </div>  
                                                </div>
                                            </div>
                                        </div>
                                        <div class="pcomment-holder">
                                            <div class="pcomment main-comment">
                                                <div class="img-holder">
                                                    <div id="commentptip-2" class="profiletipholder">
                                                        <span class="profile-tooltip">
                                                            <img src="<?= $baseUrl ?>/images/demo-profile.jpg"/>
                                                        </span>
                                                        <span class="profiletooltip_content">
                                                            <div class="profile-tip">
                                                                        
                                                                <div class="profile-tip-cover"><img src="<?= $baseUrl ?>/images/cover.jpg"></div>
                                                                <div class="profile-tip-avatar">
                                                                    <a href="#">
                                                                        <img alt="user-photo" class="img-responsive" src="<?= $baseUrl ?>/images/demo-profile.jpg">
                                                                    </a>
                                                                </div>
                                                                <div class="profile-tip-info">
                                                                    <div class="cover-username"><a href="#">Markand Trivedi</a></div>
                                                                    <div class="cover-headline">
                                                                        <span class="ptip-icon"><i class="fa  fa-suitcase"></i></span>
                                                                        Web Designer, Cricketer
                                                                    </div>
                                                                    <div class="profiletip-bio">
                                                                        <span class="ptip-icon"><i class="fa fa-home"></i></span>
                                                                        Lives in : <span>Gariyadhar</span>
                                                                    </div>
                                                                    <div class="profiletip-bio">
                                                                        <span class="ptip-icon"><i class="fa fa-map-marker"></i></span>
                                                                        Currently in : <span>Gariyadhar, Gujarat, India</span>
                                                                    </div>

                                                                </div>
                                                                <div class="profile-tip-divider"></div>
                                                                <div class="profile-tip-btn">
                                                                    <a href="#" class="btn btn-primary btn-sm"><i class="fa fa-eye"></i>View Profile</a>
                                                                </div>
                                                            </div>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="desc-holder">
                                                    <div class="normal-mode">
                                                        <div class="desc">
                                                            <a href="javascript:void(0)" class="userlink">Adel Hasanat</a>
                                                            <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh.</p>
                                                            
                                                        </div>
                                                        <div class="comment-stuff">
                                                            <div class="more-opt">
                                                                <!--<a href="javascript:void(0)" class="pa-like"><span>icon</span></a>-->
                                                                <span id="likeholder-3" class="likeholder">
                                                                    <span class="like-tooltip">
                                                                        <a href="javascript:void(0)" class="pa-like"><span>icon</span></a>
                                                                    </span>
                                                                    <span class="tooltip_content">
                                                                       <ul class="like-ul">
                                                                        <li>User Name1</li>
                                                                        <li>User Name1</li>
                                                                        <li>User Name1</li>
                                                                       </ul>
                                                                    </span>
                                                                </span>
                                                                <a href="javascript:void(0)" class="pa-reply reply-comment"><span>icon</span></a>
                                                                
                                                                <div class="dropdown dropdown-custom dropdown-xxsmall">
                                                                    <a href="javascript:void(0)class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                                                        <i class="fa fa-ellipsis-v"></i>
                                                                    </a>
                                                                    <ul class="dropdown-menu">
                                                                        <li><a href="javascript:void(0)" class="edit-comment"><span class="glyphicon glyphicon-pencil"></span>Edit</a></li>
                                                                        <li><a href="javascript:void(0)" class="delete-comment"><span class="glyphicon glyphicon-trash"></span>Delete</a></li>                                                  
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                            <div class="less-opt"><div class="timestamp">8h</div></div>
                                                        </div>
                                                    </div>
                                                    <div class="edit-mode">
                                                        <div class="desc">
                                                            <textarea class="editcomment-tt">Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh.</textarea>
                                                            <a href="javascript:void(0)" class="btn btn-primary btn-sm editcomment-cancel">Cancel</a>
                                                        </div>                                                                          
                                                    </div>
                                                </div>                                          
                                            </div>                                          
                                            <div class="clear"></div>
                                            <div class="comment-reply-holder comment-addreply">                                 
                                                <div class="addnew-comment comment-reply">                          
                                                    <div class="img-holder"><a href="javascript:void(0)"><img src="<?= $baseUrl ?>/images/demo-profile.jpg"/></a></div>
                                                    <div class="desc-holder">                                   
                                                        <div class="sliding-middle-out anim-area">
                                                            <textarea>Write a reply...</textarea>
                                                        </div>
                                                    </div>  
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="pcomment-holder has-comments">
                                        <div class="pcomment main-comment">
                                            <div class="img-holder">
                                                <div id="commentptip-3" class="profiletipholder">
                                                    <span class="profile-tooltip">
                                                        <img src="<?= $baseUrl ?>/images/demo-profile.jpg"/>
                                                    </span>
                                                    <span class="profiletooltip_content">
                                                        <div class="profile-tip">
                                                                    
                                                            <div class="profile-tip-cover"><img src="<?= $baseUrl ?>/images/cover.jpg"></div>
                                                            <div class="profile-tip-avatar">
                                                                <a href="#">
                                                                    <img alt="user-photo" class="img-responsive" src="<?= $baseUrl ?>/images/demo-profile.jpg">
                                                                </a>
                                                            </div>
                                                            <div class="profile-tip-info">
                                                                <div class="cover-username"><a href="#">Markand Trivedi</a></div>
                                                                <div class="cover-headline">
                                                                    <span class="ptip-icon"><i class="fa  fa-suitcase"></i></span>
                                                                    Web Designer, Cricketer
                                                                </div>
                                                                <div class="profiletip-bio">
                                                                    <span class="ptip-icon"><i class="fa fa-home"></i></span>
                                                                    Lives in : <span>Gariyadhar</span>
                                                                </div>
                                                                <div class="profiletip-bio">
                                                                    <span class="ptip-icon"><i class="fa fa-map-marker"></i></span>
                                                                    Currently in : <span>Gariyadhar, Gujarat, India</span>
                                                                </div>

                                                            </div>
                                                            <div class="profile-tip-divider"></div>
                                                            <div class="profile-tip-btn">
                                                                <a href="#" class="btn btn-primary btn-sm"><i class="fa fa-eye"></i>View Profile</a>
                                                            </div>
                                                        </div>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="desc-holder">
                                                <div class="normal-mode">
                                                    <div class="desc">
                                                        <a href="javascript:void(0)" class="userlink">Adel Hasanat</a>
                                                        <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh.</p>
                                                        
                                                    </div>
                                                    <div class="comment-stuff">
                                                        <div class="more-opt">
                                                            <!--<a href="javascript:void(0)" class="pa-like"><span>icon</span></a>-->
                                                            <span id="likeholder-4" class="likeholder">
                                                                <span class="like-tooltip">
                                                                    <a href="javascript:void(0)" class="pa-like"><span>icon</span></a>
                                                                </span>
                                                                <span class="tooltip_content">
                                                                   <ul class="like-ul">
                                                                    <li>User Name1</li>
                                                                    <li>User Name1</li>
                                                                    <li>User Name1</li>
                                                                   </ul>
                                                                </span>
                                                            </span>
                                                            <a href="javascript:void(0)" class="pa-reply reply-comment"><span>icon</span></a>
                                                            
                                                            <div class="dropdown dropdown-custom dropdown-xxsmall">
                                                                <a href="javascript:void(0)class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                                                    <i class="fa fa-ellipsis-v"></i>
                                                                </a>
                                                                <ul class="dropdown-menu">
                                                                    <li><a href="javascript:void(0)" class="edit-comment"><span class="glyphicon glyphicon-pencil"></span>Edit</a></li>
                                                                    <li><a href="javascript:void(0)" class="delete-comment"><span class="glyphicon glyphicon-trash"></span>Delete</a></li>                                                      
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        <div class="less-opt"><div class="timestamp">8h</div></div>
                                                    </div>                              
                                                </div>
                                                <div class="edit-mode">
                                                    <div class="desc">
                                                        <textarea class="editcomment-tt">Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh.</textarea>
                                                        <a href="javascript:void(0)" class="btn btn-primary btn-sm editcomment-cancel">Cancel</a>
                                                    </div>                                                                          
                                                </div>
                                            </div>  
                                        </div>  
                                        <div class="clear"></div>
                                        <div class="comment-reply-holder">
                                            <div class="pcomment comment-reply">                                        
                                                <div class="img-holder">
                                                    <div id="commentptip-5" class="profiletipholder">
                                                        <span class="profile-tooltip">
                                                            <img src="<?= $baseUrl ?>/images/demo-profile.jpg"/>
                                                        </span>
                                                        <span class="profiletooltip_content">
                                                            <div class="profile-tip">
                                                                        
                                                                <div class="profile-tip-cover"><img src="<?= $baseUrl ?>/images/cover.jpg"></div>
                                                                <div class="profile-tip-avatar">
                                                                    <a href="#">
                                                                        <img alt="user-photo" class="img-responsive" src="<?= $baseUrl ?>/images/demo-profile.jpg">
                                                                    </a>
                                                                </div>
                                                                <div class="profile-tip-info">
                                                                    <div class="cover-username"><a href="#">Markand Trivedi</a></div>
                                                                    <div class="cover-headline">
                                                                        <span class="ptip-icon"><i class="fa  fa-suitcase"></i></span>
                                                                        Web Designer, Cricketer
                                                                    </div>
                                                                    <div class="profiletip-bio">
                                                                        <span class="ptip-icon"><i class="fa fa-home"></i></span>
                                                                        Lives in : <span>Gariyadhar</span>
                                                                    </div>
                                                                    <div class="profiletip-bio">
                                                                        <span class="ptip-icon"><i class="fa fa-map-marker"></i></span>
                                                                        Currently in : <span>Gariyadhar, Gujarat, India</span>
                                                                    </div>

                                                                </div>
                                                                <div class="profile-tip-divider"></div>
                                                                <div class="profile-tip-btn">
                                                                    <a href="#" class="btn btn-primary btn-sm"><i class="fa fa-eye"></i>View Profile</a>
                                                                </div>
                                                            </div>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="desc-holder">
                                                    <div class="normal-mode">
                                                        <div class="desc">
                                                            <a href="javascript:void(0)" class="userlink">Adel Hasanat</a>
                                                            <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh.</p>
                                                            
                                                        </div>
                                                        <div class="comment-stuff">
                                                            <div class="more-opt">
                                                                <!--<a href="javascript:void(0)" class="pa-like"><span>icon</span></a>-->
                                                                <span id="likeholder-5" class="likeholder">
                                                                    <span class="like-tooltip">
                                                                        <a href="javascript:void(0)" class="pa-like"><span>icon</span></a>
                                                                    </span>
                                                                    <span class="tooltip_content">
                                                                       <ul class="like-ul">
                                                                        <li>User Name1</li>
                                                                        <li>User Name1</li>
                                                                        <li>User Name1</li>
                                                                       </ul>
                                                                    </span>
                                                                </span>
                                                                <a href="javascript:void(0)" class="pa-reply reply-comment"><span>icon</span></a>
                                                                
                                                                <div class="dropdown dropdown-custom dropdown-xxsmall">
                                                                    <a href="javascript:void(0)class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                                                        <i class="fa fa-ellipsis-v"></i>
                                                                    </a>
                                                                    <ul class="dropdown-menu">
                                                                        <li><a href="javascript:void(0)" class="edit-comment"><span class="glyphicon glyphicon-pencil"></span>Edit</a></li>
                                                                        <li><a href="javascript:void(0)" class="delete-comment"><span class="glyphicon glyphicon-trash"></span>Delete</a></li>                                                  
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                            <div class="less-opt"><div class="timestamp">8h</div></div>
                                                        </div>      
                                                    </div>
                                                    <div class="edit-mode">
                                                        <div class="desc">
                                                            <textarea class="editcomment-tt">Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh.</textarea>
                                                            <a href="javascript:void(0)" class="btn btn-primary btn-sm editcomment-cancel">Cancel</a>
                                                        </div>                                                                          
                                                    </div>
                                                </div>  
                                            </div>
                                            <div class="pcomment comment-reply">
                                                <div class="img-holder">
                                                    <div id="commentptip-6" class="profiletipholder">
                                                        <span class="profile-tooltip">
                                                            <img src="<?= $baseUrl ?>/images/demo-profile.jpg"/>
                                                        </span>
                                                        <span class="profiletooltip_content">
                                                            <div class="profile-tip">
                                                                        
                                                                <div class="profile-tip-cover"><img src="<?= $baseUrl ?>/images/cover.jpg"></div>
                                                                <div class="profile-tip-avatar">
                                                                    <a href="#">
                                                                        <img alt="user-photo" class="img-responsive" src="<?= $baseUrl ?>/images/demo-profile.jpg">
                                                                    </a>
                                                                </div>
                                                                <div class="profile-tip-info">
                                                                    <div class="cover-username"><a href="#">Markand Trivedi</a></div>
                                                                    <div class="cover-headline">
                                                                        <span class="ptip-icon"><i class="fa  fa-suitcase"></i></span>
                                                                        Web Designer, Cricketer
                                                                    </div>
                                                                    <div class="profiletip-bio">
                                                                        <span class="ptip-icon"><i class="fa fa-home"></i></span>
                                                                        Lives in : <span>Gariyadhar</span>
                                                                    </div>
                                                                    <div class="profiletip-bio">
                                                                        <span class="ptip-icon"><i class="fa fa-map-marker"></i></span>
                                                                        Currently in : <span>Gariyadhar, Gujarat, India</span>
                                                                    </div>

                                                                </div>
                                                                <div class="profile-tip-divider"></div>
                                                                <div class="profile-tip-btn">
                                                                    <a href="#" class="btn btn-primary btn-sm"><i class="fa fa-eye"></i>View Profile</a>
                                                                </div>
                                                            </div>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="desc-holder">
                                                    <div class="normal-mode">
                                                        <div class="desc">
                                                            <a href="javascript:void(0)" class="userlink">Adel Hasanat</a>
                                                            <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit...</p>
                                                            
                                                        </div>
                                                        <div class="comment-stuff">
                                                            <div class="more-opt">
                                                                <!--<a href="javascript:void(0)" class="pa-like"><span>icon</span></a>-->
                                                                <span id="likeholder-6" class="likeholder">
                                                                    <span class="like-tooltip">
                                                                        <a href="javascript:void(0)" class="pa-like"><span>icon</span></a>
                                                                    </span>
                                                                    <span class="tooltip_content">
                                                                       <ul class="like-ul">
                                                                        <li>User Name1</li>
                                                                        <li>User Name1</li>
                                                                        <li>User Name1</li>
                                                                       </ul>
                                                                    </span>
                                                                </span>
                                                                <a href="javascript:void(0)" class="pa-reply reply-comment"><span>icon</span></a>
                                                                
                                                                <div class="dropdown dropdown-custom dropdown-xxsmall">
                                                                    <a href="javascript:void(0)class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                                                        <i class="fa fa-ellipsis-v"></i>
                                                                    </a>
                                                                    <ul class="dropdown-menu">
                                                                        <li><a href="javascript:void(0)" class="edit-comment"><span class="glyphicon glyphicon-pencil"></span>Edit</a></li>
                                                                        <li><a href="javascript:void(0)" class="delete-comment"><span class="glyphicon glyphicon-trash"></span>Delete</a></li>                                                  
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                            <div class="less-opt"><div class="timestamp">8h</div></div>
                                                        </div>  
                                                    </div>
                                                    <div class="edit-mode">
                                                        <div class="desc">
                                                            <textarea class="editcomment-tt">Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh.</textarea>
                                                            <a href="javascript:void(0)" class="btn btn-primary btn-sm editcomment-cancel">Cancel</a>
                                                        </div>                                                                          
                                                    </div>
                                                </div>  
                                            </div>
                                        </div>
                                        <div class="comment-reply-holder comment-addreply">
                                            <div class="addnew-comment comment-reply">                          
                                                <div class="img-holder"><a href="javascript:void(0)"><img src="<?= $baseUrl ?>/images/demo-profile.jpg"/></a></div>
                                                <div class="desc-holder">                                   
                                                    <div class="sliding-middle-out anim-area">
                                                        <textarea>Write a reply...</textarea>
                                                    </div>
                                                </div>  
                                            </div>                          
                                        </div>                                  
                                    </div>
                                    <div class="pcomment-holder">
                                        <div class="pcomment main-comment">
                                            <div class="img-holder">
                                                <div id="commentptip-4" class="profiletipholder">
                                                    <span class="profile-tooltip">
                                                        <img src="<?= $baseUrl ?>/images/demo-profile.jpg"/>
                                                    </span>
                                                    <span class="profiletooltip_content">
                                                        <div class="profile-tip">
                                                                    
                                                            <div class="profile-tip-cover"><img src="<?= $baseUrl ?>/images/cover.jpg"></div>
                                                            <div class="profile-tip-avatar">
                                                                <a href="#">
                                                                    <img alt="user-photo" class="img-responsive" src="<?= $baseUrl ?>/images/demo-profile.jpg">
                                                                </a>
                                                            </div>
                                                            <div class="profile-tip-info">
                                                                <div class="cover-username"><a href="#">Markand Trivedi</a></div>
                                                                <div class="cover-headline">
                                                                    <span class="ptip-icon"><i class="fa  fa-suitcase"></i></span>
                                                                    Web Designer, Cricketer
                                                                </div>
                                                                <div class="profiletip-bio">
                                                                    <span class="ptip-icon"><i class="fa fa-home"></i></span>
                                                                    Lives in : <span>Gariyadhar</span>
                                                                </div>
                                                                <div class="profiletip-bio">
                                                                    <span class="ptip-icon"><i class="fa fa-map-marker"></i></span>
                                                                    Currently in : <span>Gariyadhar, Gujarat, India</span>
                                                                </div>

                                                            </div>
                                                            <div class="profile-tip-divider"></div>
                                                            <div class="profile-tip-btn">
                                                                <a href="#" class="btn btn-primary btn-sm"><i class="fa fa-eye"></i>View Profile</a>
                                                            </div>
                                                        </div>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="desc-holder">
                                                <div class="normal-mode">
                                                    <div class="desc">
                                                        <a href="javascript:void(0)" class="userlink">Adel Hasanat</a>
                                                        <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh.Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh.Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh.</p>
                                                        
                                                    </div>
                                                    <div class="comment-stuff">
                                                        <div class="more-opt">
                                                            <!--<a href="javascript:void(0)" class="pa-like"><span>icon</span></a>-->
                                                            <span id="likeholder-7" class="likeholder">
                                                                <span class="like-tooltip">
                                                                    <a href="javascript:void(0)" class="pa-like"><span>icon</span></a>
                                                                </span>
                                                                <span class="tooltip_content">
                                                                   <ul class="like-ul">
                                                                    <li>User Name</li>
                                                                    <li>User Name</li>
                                                                    <li>User Name</li>
                                                                    <li>User Name</li>
                                                                    <li>User Name</li>
                                                                    <li>User Name</li>
                                                                    <li>User Name</li>
                                                                   </ul>
                                                                </span>
                                                            </span> 
                                                            <a href="javascript:void(0)" class="pa-reply reply-comment"><span>icon</span></a>
                                                            
                                                            <div class="dropdown dropdown-custom dropdown-xxsmall">
                                                                <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                                                    <i class="fa fa-ellipsis-v"></i>
                                                                </a>
                                                                <ul class="dropdown-menu">
                                                                    <li><a href="javascript:void(0)" class="edit-comment"><span class="glyphicon glyphicon-pencil"></span>Edit</a></li>
                                                                    <li><a href="javascript:void(0)" class="delete-comment"><span class="glyphicon glyphicon-trash"></span>Delete</a></li>                                                  
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        <div class="less-opt"><div class="timestamp">8h</div></div>
                                                    </div>
                                                </div>
                                                <div class="edit-mode">
                                                    <div class="desc">
                                                        <textarea class="editcomment-tt">Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh.</textarea>
                                                        <a href="javascript:void(0)" class="btn btn-primary btn-sm editcomment-cancel">Cancel</a>
                                                    </div>                                                                          
                                                </div>
                                            </div>                              
                                        </div>  
                                        <div class="clear"></div>
                                        <div class="comment-reply-holder comment-addreply">                                 
                                            <div class="addnew-comment comment-reply">                          
                                                <div class="img-holder"><a href="javascript:void(0)"><img src="<?= $baseUrl ?>/images/demo-profile.jpg"/></a></div>
                                                <div class="desc-holder">                                   
                                                    <div class="sliding-middle-out anim-area">
                                                        <textarea>Write a reply...</textarea>
                                                    </div>
                                                </div>  
                                            </div>
                                        </div>
                                    </div>
                                
                                </div>  
                                <div class="addnew-comment">                            
                                    <div class="img-holder"><a href="javascript:void(0)"><img src="<?= $baseUrl ?>/images/demo-profile.jpg"/></a></div>
                                    <div class="desc-holder">                                   
                                        <div class="sliding-middle-out anim-area">
                                            <textarea>Write a comment</textarea>
                                        </div>
                                    </div>  
                                </div>
                            </div>
                        </div>
                    </div>
                
                    <div class="post-holder bshadow">
                        <div class="post-topbar">
                            <div class="post-userinfo">
                                <div class="img-holder">
                                    <div id="profiletip-2" class="profiletipholder">
                                        <span class="profile-tooltip">
                                            <img src="<?= $baseUrl ?>/images/demo-profile.jpg"/>
                                        </span>
                                        <span class="profiletooltip_content">
                                            <div class="profile-tip">
                                                        
                                                <div class="profile-tip-cover"><img src="<?= $baseUrl ?>/images/cover.jpg"></div>
                                                <div class="profile-tip-avatar">
                                                    <a href="#">
                                                        <img alt="user-photo" class="img-responsive" src="<?= $baseUrl ?>/images/demo-profile.jpg">
                                                    </a>
                                                </div>
                                                <div class="profile-tip-info">
                                                    <div class="cover-username"><a href="#">Markand Trivedi</a></div>
                                                    <div class="cover-headline">
                                                        <span class="ptip-icon"><i class="fa  fa-suitcase"></i></span>
                                                        Web Designer, Cricketer
                                                    </div>
                                                    <div class="profiletip-bio">
                                                        <span class="ptip-icon"><i class="fa fa-home"></i></span>
                                                        Lives in : <span>Gariyadhar</span>
                                                    </div>
                                                    <div class="profiletip-bio">
                                                        <span class="ptip-icon"><i class="fa fa-map-marker"></i></span>
                                                        Currently in : <span>Gariyadhar, Gujarat, India</span>
                                                    </div>

                                                </div>
                                                <div class="profile-tip-divider"></div>
                                                <div class="profile-tip-btn">
                                                    <a href="#" class="btn btn-primary btn-sm"><i class="fa fa-eye"></i>View Profile</a>
                                                </div>
                                            </div>
                                        </span>
                                    </div>
                                
                                </div>
                                <div class="desc-holder">
                                    <a href="javascript:void(0)">Nimish Parekh</a>
                                    <span class="timestamp">1 hr<span class="glyphicon glyphicon-user"></span></span>
                                </div>
                            </div>
                            <div class="settings-icon">
                                <div class="dropdown dropdown-custom dropdown-med resist">
                                    <a href="javascript:void(0)" class="dropdown-toggle" role="button" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-ellipsis-h"></i>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <ul class="post-licon-list">
                                                <li class="hidepost">
                                                    <a href="javascript:void(0)">
                                                        <span class="glyphicon glyphicon-eye-close"></span>
                                                        Hide Post
                                                        <span>See fewer posts like this</span>
                                                    </a>
                                                </li>
                                                <li class="unfollow">
                                                    <a href="javascript:void(0)">
                                                        <span class="glyphicon glyphicon-ban-circle"></span>
                                                        Unfollow Person
                                                        <span>Stop seeing such posts but stay friends</span>
                                                    </a>
                                                </li>
                                            </ul>
                                            <div class="li-divider"></div>
                                            <ul class="post-sicon-list">
                                                <li><a href="javascript:void(0)"><span class="glyphicon glyphicon-warning-sign"></span>Report Post</a></li>
                                                <li><a href="javascript:void(0)" class="savepost-link"><span class="glyphicon glyphicon-bookmark"></span>Save Post</a></li>
                                                <li class="nicon"><a href="javascript:void(0)">Turn off notification for this post</a></li>
                                                <li class="nicon"><a href="javascript:void(0)">Mute Friend</a></li>
                                            </ul>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="post-content">
                            <div class="post-details">
                                <div class="post-title">Must Watch!</div>
                                <div class="post-desc">
                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec sed varius risus. Duis rhoncus eros et pellentesque imperdiet. Praesent pharetra rutrum eros. In nec nulla id enim faucibus pellentesque quis at dolor. Aenean a cursus quam, et fringilla tellus. Vivamus eu lorem est. Donec non urna sit amet arcu dictum dapibus.</p>                         
                                </div>
                            </div>
                            <div class="pvideo-holder">
                                <div class="img-holder"><img src="<?= $baseUrl ?>/images/vimg.jpg"/></div>
                                <div class="desc-holder">
                                    <h4><a href="javascript:void(0)">A simple way to break a bad habit | Judson Brewer</a></h4>
                                    <p>
                                    Can we break bad habits by being more curious about them? Psychiatrist Judson Brewer studies the relationship between mindfulness and addiction — from smokin...
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="clear"></div>
                        <div class="post-data">
                            <div class="post-actions">
                                <!--<a href="javascript:void(0)" class="pa-like"><span>icon</span>Like</a>-->
                                <span id="likeholder-8" class="likeholder">
                                    <span class="like-tooltip">
                                        <a href="javascript:void(0)" class="pa-like"><span>icon</span>Like</a>
                                    </span>
                                    <span class="tooltip_content">
                                       <ul class="like-ul">
                                        <li>User Name</li>
                                        <li>User Name</li>
                                        <li>User Name</li>
                                        <li>User Name</li>
                                        <li>User Name</li>
                                        <li>User Name</li>
                                        <li>User Name</li>
                                       </ul>
                                    </span>
                                </span> 
                                <a href="javascript:void(0)" class="pa-comment"><span>icon</span>Comment</a>
                                <a href="#sharepost-popup" class="popup-modal pa-share"><span>icon</span>Share</a>
                                <a href="javascript:void(0)" class="pa-message"><span>icon</span>Message</a>
                            </div>
                            <div class="post-more">
                                <a href="javascript:void(0)" class="view-morec">View more comments</a>
                                <span class="total-comments">3 of 7</span>
                            </div>
                            <div class="post-comments">                     
                                <div class="pcomments">
                                    <div class="pcomment-earlier">
                                        <div class="pcomment-holder">
                                            <div class="pcomment main-comment">
                                                <div class="img-holder">
                                                    <div id="commentptip-7" class="profiletipholder">
                                                        <span class="profile-tooltip">
                                                            <img src="<?= $baseUrl ?>/images/demo-profile.jpg"/>
                                                        </span>
                                                        <span class="profiletooltip_content">
                                                            <div class="profile-tip">
                                                                        
                                                                <div class="profile-tip-cover"><img src="<?= $baseUrl ?>/images/cover.jpg"></div>
                                                                <div class="profile-tip-avatar">
                                                                    <a href="#">
                                                                        <img alt="user-photo" class="img-responsive" src="<?= $baseUrl ?>/images/demo-profile.jpg">
                                                                    </a>
                                                                </div>
                                                                <div class="profile-tip-info">
                                                                    <div class="cover-username"><a href="#">Markand Trivedi</a></div>
                                                                    <div class="cover-headline">
                                                                        <span class="ptip-icon"><i class="fa  fa-suitcase"></i></span>
                                                                        Web Designer, Cricketer
                                                                    </div>
                                                                    <div class="profiletip-bio">
                                                                        <span class="ptip-icon"><i class="fa fa-home"></i></span>
                                                                        Lives in : <span>Gariyadhar</span>
                                                                    </div>
                                                                    <div class="profiletip-bio">
                                                                        <span class="ptip-icon"><i class="fa fa-map-marker"></i></span>
                                                                        Currently in : <span>Gariyadhar, Gujarat, India</span>
                                                                    </div>

                                                                </div>
                                                                <div class="profile-tip-divider"></div>
                                                                <div class="profile-tip-btn">
                                                                    <a href="#" class="btn btn-primary btn-sm"><i class="fa fa-eye"></i>View Profile</a>
                                                                </div>
                                                            </div>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="desc-holder">
                                                    <div class="normal-mode">
                                                        <div class="desc">
                                                            <a href="javascript:void(0)" class="userlink">Adel Hasanat</a>
                                                            <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh.</p>
                                                            
                                                        </div>
                                                        <div class="comment-stuff">
                                                            <div class="more-opt">
                                                                <!--a href="javascript:void(0)" class="pa-like"><span>icon</span></a>-->
                                                                <span id="likeholder-9" class="likeholder">
                                                                    <span class="like-tooltip">
                                                                        <a href="javascript:void(0)" class="pa-like"><span>icon</span></a>
                                                                    </span>
                                                                    <span class="tooltip_content">
                                                                       <ul class="like-ul">
                                                                        <li>User Name</li>
                                                                        <li>User Name</li>
                                                                        <li>User Name</li>
                                                                        <li>User Name</li>
                                                                        <li>User Name</li>
                                                                        <li>User Name</li>
                                                                        <li>User Name</li>
                                                                       </ul>
                                                                    </span>
                                                                </span> 
                                                                <a href="javascript:void(0)" class="pa-reply reply-comment"><span>icon</span></a>
                                                                
                                                                <div class="dropdown dropdown-custom dropdown-xxsmall">
                                                                    <a href="javascript:void(0)class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                                                        <i class="fa fa-ellipsis-v"></i>
                                                                    </a>
                                                                    <ul class="dropdown-menu">
                                                                        <li><a href="javascript:void(0)" class="edit-comment"><span class="glyphicon glyphicon-pencil"></span>Edit</a></li>
                                                                        <li><a href="javascript:void(0)" class="delete-comment"><span class="glyphicon glyphicon-trash"></span>Delete</a></li>                                                  
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                            <div class="less-opt"><div class="timestamp">8h</div></div>
                                                        </div>
                                                    </div>
                                                    <div class="edit-mode">
                                                        <div class="desc">
                                                            <textarea class="editcomment-tt">Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh.</textarea>
                                                            <a href="javascript:void(0)" class="btn btn-primary btn-sm editcomment-cancel">Cancel</a>
                                                        </div>                                                                          
                                                    </div>
                                                </div>                              
                                            </div>
                                            <div class="clear"></div>
                                            <div class="comment-reply-holder comment-addreply">                                 
                                                <div class="addnew-comment comment-reply">                          
                                                    <div class="img-holder"><a href="javascript:void(0)"><img src="<?= $baseUrl ?>/images/demo-profile.jpg"/></a></div>
                                                    <div class="desc-holder">                                   
                                                        <div class="sliding-middle-out anim-area">
                                                            <textarea>Write a reply...</textarea>
                                                        </div>
                                                    </div>  
                                                </div>
                                            </div>
                                        </div>
                                        <div class="pcomment-holder">
                                            <div class="pcomment main-comment">
                                                <div class="img-holder">
                                                    <div id="commentptip-8" class="profiletipholder">
                                                        <span class="profile-tooltip">
                                                            <img src="<?= $baseUrl ?>/images/demo-profile.jpg"/>
                                                        </span>
                                                        <span class="profiletooltip_content">
                                                            <div class="profile-tip">
                                                                        
                                                                <div class="profile-tip-cover"><img src="<?= $baseUrl ?>/images/cover.jpg"></div>
                                                                <div class="profile-tip-avatar">
                                                                    <a href="#">
                                                                        <img alt="user-photo" class="img-responsive" src="<?= $baseUrl ?>/images/demo-profile.jpg">
                                                                    </a>
                                                                </div>
                                                                <div class="profile-tip-info">
                                                                    <div class="cover-username"><a href="#">Markand Trivedi</a></div>
                                                                    <div class="cover-headline">
                                                                        <span class="ptip-icon"><i class="fa  fa-suitcase"></i></span>
                                                                        Web Designer, Cricketer
                                                                    </div>
                                                                    <div class="profiletip-bio">
                                                                        <span class="ptip-icon"><i class="fa fa-home"></i></span>
                                                                        Lives in : <span>Gariyadhar</span>
                                                                    </div>
                                                                    <div class="profiletip-bio">
                                                                        <span class="ptip-icon"><i class="fa fa-map-marker"></i></span>
                                                                        Currently in : <span>Gariyadhar, Gujarat, India</span>
                                                                    </div>

                                                                </div>
                                                                <div class="profile-tip-divider"></div>
                                                                <div class="profile-tip-btn">
                                                                    <a href="#" class="btn btn-primary btn-sm"><i class="fa fa-eye"></i>View Profile</a>
                                                                </div>
                                                            </div>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="desc-holder">
                                                    <div class="normal-mode">
                                                        <div class="desc">
                                                            <a href="javascript:void(0)" class="userlink">Adel Hasanat</a>
                                                            <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh.</p>
                                                            
                                                        </div>
                                                        <div class="comment-stuff">
                                                            <div class="more-opt">
                                                                <!--<a href="javascript:void(0)" class="pa-like"><span>icon</span></a>-->
                                                                <span id="likeholder-10" class="likeholder">
                                                                    <span class="like-tooltip">
                                                                        <a href="javascript:void(0)" class="pa-like"><span>icon</span></a>
                                                                    </span>
                                                                    <span class="tooltip_content">
                                                                       <ul class="like-ul">
                                                                        <li>User Name</li>
                                                                        <li>User Name</li>
                                                                        <li>User Name</li>
                                                                        <li>User Name</li>
                                                                        <li>User Name</li>
                                                                        <li>User Name</li>
                                                                        <li>User Name</li>
                                                                       </ul>
                                                                    </span>
                                                                </span> 
                                                                <a href="javascript:void(0)" class="pa-reply reply-comment"><span>icon</span></a>
                                                                
                                                                <div class="dropdown dropdown-custom dropdown-xxsmall">
                                                                    <a href="javascript:void(0)class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                                                        <i class="fa fa-ellipsis-v"></i>
                                                                    </a>
                                                                    <ul class="dropdown-menu">
                                                                        <li><a href="javascript:void(0)" class="edit-comment"><span class="glyphicon glyphicon-pencil"></span>Edit</a></li>
                                                                        <li><a href="javascript:void(0)" class="delete-comment"><span class="glyphicon glyphicon-trash"></span>Delete</a></li>                                                  
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                            <div class="less-opt"><div class="timestamp">8h</div></div>
                                                        </div>
                                                    </div>
                                                    <div class="edit-mode">
                                                        <div class="desc">
                                                            <textarea class="editcomment-tt">Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh.</textarea>
                                                            <a href="javascript:void(0)" class="btn btn-primary btn-sm editcomment-cancel">Cancel</a>
                                                        </div>                                                                          
                                                    </div>
                                                </div>                              
                                            </div>
                                            <div class="clear"></div>
                                            <div class="comment-reply-holder comment-addreply">                                 
                                                <div class="addnew-comment comment-reply">                          
                                                    <div class="img-holder"><a href="javascript:void(0)"><img src="<?= $baseUrl ?>/images/demo-profile.jpg"/></a></div>
                                                    <div class="desc-holder">                                   
                                                        <div class="sliding-middle-out anim-area">
                                                            <textarea>Write a reply...</textarea>
                                                        </div>
                                                    </div>  
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="pcomment-holder">
                                        <div class="pcomment main-comment">
                                            <div class="img-holder">
                                                <div id="commentptip-9" class="profiletipholder">
                                                    <span class="profile-tooltip">
                                                        <img src="<?= $baseUrl ?>/images/demo-profile.jpg"/>
                                                    </span>
                                                    <span class="profiletooltip_content">
                                                        <div class="profile-tip">
                                                                    
                                                            <div class="profile-tip-cover"><img src="<?= $baseUrl ?>/images/cover.jpg"></div>
                                                            <div class="profile-tip-avatar">
                                                                <a href="#">
                                                                    <img alt="user-photo" class="img-responsive" src="<?= $baseUrl ?>/images/demo-profile.jpg">
                                                                </a>
                                                            </div>
                                                            <div class="profile-tip-info">
                                                                <div class="cover-username"><a href="#">Markand Trivedi</a></div>
                                                                <div class="cover-headline">
                                                                    <span class="ptip-icon"><i class="fa  fa-suitcase"></i></span>
                                                                    Web Designer, Cricketer
                                                                </div>
                                                                <div class="profiletip-bio">
                                                                    <span class="ptip-icon"><i class="fa fa-home"></i></span>
                                                                    Lives in : <span>Gariyadhar</span>
                                                                </div>
                                                                <div class="profiletip-bio">
                                                                    <span class="ptip-icon"><i class="fa fa-map-marker"></i></span>
                                                                    Currently in : <span>Gariyadhar, Gujarat, India</span>
                                                                </div>

                                                            </div>
                                                            <div class="profile-tip-divider"></div>
                                                            <div class="profile-tip-btn">
                                                                <a href="#" class="btn btn-primary btn-sm"><i class="fa fa-eye"></i>View Profile</a>
                                                            </div>
                                                        </div>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="desc-holder">
                                                <div class="normal-mode">
                                                    <div class="desc">
                                                        <a href="javascript:void(0)" class="userlink">Adel Hasanat</a>
                                                        <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh.</p>
                                                        
                                                    </div>
                                                    <div class="comment-stuff">
                                                        <div class="more-opt">
                                                            <!--<a href="javascript:void(0)" class="pa-like"><span>icon</span></a>-->
                                                            <span id="likeholder-11" class="likeholder">
                                                                <span class="like-tooltip">
                                                                    <a href="javascript:void(0)" class="pa-like"><span>icon</span></a>
                                                                </span>
                                                                <span class="tooltip_content">
                                                                   <ul class="like-ul">
                                                                    <li>User Name</li>
                                                                    <li>User Name</li>
                                                                    <li>User Name</li>
                                                                    <li>User Name</li>
                                                                    <li>User Name</li>
                                                                    <li>User Name</li>
                                                                    <li>User Name</li>
                                                                   </ul>
                                                                </span>
                                                            </span> 
                                                            <a href="javascript:void(0)" class="pa-reply reply-comment"><span>icon</span></a>
                                                            
                                                            <div class="dropdown dropdown-custom dropdown-xxsmall">
                                                                <a href="javascript:void(0)class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                                                    <i class="fa fa-ellipsis-v"></i>
                                                                </a>
                                                                <ul class="dropdown-menu">
                                                                    <li><a href="javascript:void(0)" class="edit-comment"><span class="glyphicon glyphicon-pencil"></span>Edit</a></li>
                                                                    <li><a href="javascript:void(0)" class="delete-comment"><span class="glyphicon glyphicon-trash"></span>Delete</a></li>                                                  
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        <div class="less-opt"><div class="timestamp">8h</div></div>
                                                    </div>  
                                                </div>
                                                <div class="edit-mode">
                                                    <div class="desc">
                                                        <textarea class="editcomment-tt">Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh.</textarea>
                                                        <a href="javascript:void(0)" class="btn btn-primary btn-sm editcomment-cancel">Cancel</a>
                                                    </div>                                                                          
                                                </div>
                                            </div>                              
                                        </div>
                                        <div class="clear"></div>
                                        <div class="comment-reply-holder comment-addreply">                                 
                                            <div class="addnew-comment comment-reply">                          
                                                <div class="img-holder"><a href="javascript:void(0)"><img src="<?= $baseUrl ?>/images/demo-profile.jpg"/></a></div>
                                                <div class="desc-holder">                                   
                                                    <div class="sliding-middle-out anim-area">
                                                        <textarea>Write a reply...</textarea>
                                                    </div>
                                                </div>  
                                            </div>
                                        </div>
                                    </div>
                                    <div class="pcomment-holder">
                                        <div class="pcomment main-comment">
                                            <div class="img-holder">
                                                <div id="commentptip-10" class="profiletipholder">
                                                    <span class="profile-tooltip">
                                                        <img src="<?= $baseUrl ?>/images/demo-profile.jpg"/>
                                                    </span>
                                                    <span class="profiletooltip_content">
                                                        <div class="profile-tip">
                                                                    
                                                            <div class="profile-tip-cover"><img src="<?= $baseUrl ?>/images/cover.jpg"></div>
                                                            <div class="profile-tip-avatar">
                                                                <a href="#">
                                                                    <img alt="user-photo" class="img-responsive" src="<?= $baseUrl ?>/images/demo-profile.jpg">
                                                                </a>
                                                            </div>
                                                            <div class="profile-tip-info">
                                                                <div class="cover-username"><a href="#">Markand Trivedi</a></div>
                                                                <div class="cover-headline">
                                                                    <span class="ptip-icon"><i class="fa  fa-suitcase"></i></span>
                                                                    Web Designer, Cricketer
                                                                </div>
                                                                <div class="profiletip-bio">
                                                                    <span class="ptip-icon"><i class="fa fa-home"></i></span>
                                                                    Lives in : <span>Gariyadhar</span>
                                                                </div>
                                                                <div class="profiletip-bio">
                                                                    <span class="ptip-icon"><i class="fa fa-map-marker"></i></span>
                                                                    Currently in : <span>Gariyadhar, Gujarat, India</span>
                                                                </div>

                                                            </div>
                                                            <div class="profile-tip-divider"></div>
                                                            <div class="profile-tip-btn">
                                                                <a href="#" class="btn btn-primary btn-sm"><i class="fa fa-eye"></i>View Profile</a>
                                                            </div>
                                                        </div>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="desc-holder">
                                                <div class="normal-mode">
                                                    <div class="desc">
                                                        <a href="javascript:void(0)" class="userlink">Adel Hasanat</a>
                                                        <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh.Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh.Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh.</p>
                                                        
                                                    </div>
                                                    <div class="comment-stuff">
                                                        <div class="more-opt">
                                                            <!--<a href="javascript:void(0)" class="pa-like"><span>icon</span></a>-->
                                                            <span id="likeholder-12" class="likeholder">
                                                                <span class="like-tooltip">
                                                                    <a href="javascript:void(0)" class="pa-like"><span>icon</span></a>
                                                                </span>
                                                                <span class="tooltip_content">
                                                                   <ul class="like-ul">
                                                                    <li>User Name</li>
                                                                    <li>User Name</li>
                                                                    <li>User Name</li>
                                                                    <li>User Name</li>
                                                                    <li>User Name</li>
                                                                    <li>User Name</li>
                                                                    <li>User Name</li>
                                                                   </ul>
                                                                </span>
                                                            </span> 
                                                            <a href="javascript:void(0)" class="pa-reply reply-comment"><span>icon</span></a>
                                                            
                                                            <div class="dropdown dropdown-custom dropdown-xxsmall">
                                                                <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                                                    <i class="fa fa-ellipsis-v"></i>
                                                                </a>
                                                                <ul class="dropdown-menu">
                                                                    <li><a href="javascript:void(0)" class="edit-comment"><span class="glyphicon glyphicon-pencil"></span>Edit</a></li>
                                                                    <li><a href="javascript:void(0)" class="delete-comment"><span class="glyphicon glyphicon-trash"></span>Delete</a></li>                                                      
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        <div class="less-opt"><div class="timestamp">8h</div></div>
                                                    </div>  
                                                </div>
                                                <div class="edit-mode">
                                                    <div class="desc">
                                                        <textarea class="editcomment-tt">Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh.</textarea>
                                                        <a href="javascript:void(0)" class="btn btn-primary btn-sm editcomment-cancel">Cancel</a>
                                                    </div>                                                                          
                                                </div>
                                            </div>                              
                                        </div>
                                        <div class="clear"></div>
                                        <div class="comment-reply-holder comment-addreply">                                 
                                            <div class="addnew-comment comment-reply">                          
                                                <div class="img-holder"><a href="javascript:void(0)"><img src="<?= $baseUrl ?>/images/demo-profile.jpg"/></a></div>
                                                <div class="desc-holder">                                   
                                                    <div class="sliding-middle-out anim-area">
                                                        <textarea>Write a reply...</textarea>
                                                    </div>
                                                </div>  
                                            </div>
                                        </div>
                                    </div>
                                
                                </div>  
                                <div class="addnew-comment">
                                    <div class="img-holder"><a href="javascript:void(0)"><img src="<?= $baseUrl ?>/images/demo-profile.jpg"/></a></div>
                                    <div class="desc-holder">                                   
                                        <div class="sliding-middle-out anim-area">
                                            <textarea>Write a comment</textarea>
                                        </div>
                                    </div>  
                                </div>
                            </div>
                        </div>
                    </div>
                
                
                    <div class="post-holder bshadow">
                        <div class="post-topbar">
                            <div class="post-userinfo">
                                <div class="img-holder">
                                    <div id="profiletip-3" class="profiletipholder">
                                        <span class="profile-tooltip">
                                            <img src="<?= $baseUrl ?>/images/demo-profile.jpg"/>
                                        </span>
                                        <span class="profiletooltip_content">
                                            <div class="profile-tip">
                                                        
                                                <div class="profile-tip-cover"><img src="<?= $baseUrl ?>/images/cover.jpg"></div>
                                                <div class="profile-tip-avatar">
                                                    <a href="#">
                                                        <img alt="user-photo" class="img-responsive" src="<?= $baseUrl ?>/images/demo-profile.jpg">
                                                    </a>
                                                </div>
                                                <div class="profile-tip-info">
                                                    <div class="cover-username"><a href="#">Markand Trivedi</a></div>
                                                    <div class="cover-headline">
                                                        <span class="ptip-icon"><i class="fa  fa-suitcase"></i></span>
                                                        Web Designer, Cricketer
                                                    </div>
                                                    <div class="profiletip-bio">
                                                        <span class="ptip-icon"><i class="fa fa-home"></i></span>
                                                        Lives in : <span>Gariyadhar</span>
                                                    </div>
                                                    <div class="profiletip-bio">
                                                        <span class="ptip-icon"><i class="fa fa-map-marker"></i></span>
                                                        Currently in : <span>Gariyadhar, Gujarat, India</span>
                                                    </div>

                                                </div>
                                                <div class="profile-tip-divider"></div>
                                                <div class="profile-tip-btn">
                                                    <a href="#" class="btn btn-primary btn-sm"><i class="fa fa-eye"></i>View Profile</a>
                                                </div>
                                            </div>
                                        </span>
                                    </div>
                                </div>
                                <div class="desc-holder">
                                    <a href="javascript:void(0)">Nimish Parekh</a> with <a href="javascript:void(0)" class="sub-link">Smith Patel</a> and
                                    <span id="likeholder-1" class="likeholder">
                                        <span class="like-tooltip">
                                            <a href="javascript:void(0)" class="pa-like sub-link">3 others</a>
                                        </span>
                                        <span class="tooltip_content">
                                           <ul class="like-ul">
                                            <li>User Name</li>
                                            <li>User Name</li>
                                            <li>User Name</li>
                                           </ul>
                                        </span>
                                    </span>     
                                    at Ahmedabad
                                    <span class="timestamp">2 hrs<span class="glyphicon glyphicon-globe"></span></span>
                                </div>
                            </div>
                            <div class="settings-icon">
                                <div class="dropdown dropdown-custom dropdown-med resist">
                                    <a href="javascript:void(0)" class="dropdown-toggle" role="button" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-ellipsis-h"></i>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <ul class="post-sicon-list">
                                                <li><a href="javascript:void(0)" class="savepost-link"><span class="glyphicon glyphicon-bookmark"></span>Save Post</a></li>
                                                <li class="nicon"><a href="javascript:void(0)">Turn off notification for this post</a></li>
                                                <li class="nicon"><a href="#editpost-popup" class="popup-modal editpost-link">Edit Post</a></li>                                        
                                                <li class="nicon"><a href="javascript:void(0)" class="deletepost-link">Delete Post</a></li>
                                            </ul>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="post-content">
                            <div class="post-details">
                                <div class="post-title"></div>
                                <div class="post-desc">
                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec sed varius risus. Duis rhoncus eros et pellentesque imperdiet. Praesent pharetra rutrum eros. In nec nulla id enim.</p>                           
                                </div>
                            </div>
                            <div class="post-img-holder">
                                <div class="post-img two-img gallery">
                                    <div class="pimg-holder himg-box"><a href="images/post-img2.jpg" rel="prettyPhoto[gallery2]"><img src="<?= $baseUrl ?>/images/post-img2.jpg" class="himg"/></a></div>
                                    <div class="pimg-holder vimg-box"><a href="images/post-img3.jpg" rel="prettyPhoto[gallery2]"><img src="<?= $baseUrl ?>/images/post-img3.jpg" class="vimg"/></a></div>
                                </div>
                            </div>
                        </div>
                        <div class="clear"></div>
                        <div class="post-data">
                            <div class="post-actions">
                                <!--<a href="javascript:void(0)" class="pa-like"><span>icon</span>Like</a>-->
                                <span id="likeholder-13" class="likeholder">
                                    <span class="like-tooltip">
                                        <a href="javascript:void(0)" class="pa-like"><span>icon</span>Like</a>
                                    </span>
                                    <span class="tooltip_content">
                                       <ul class="like-ul">
                                        <li>User Name</li>
                                        <li>User Name</li>
                                        <li>User Name</li>
                                        <li>User Name</li>
                                        <li>User Name</li>
                                        <li>User Name</li>
                                        <li>User Name</li>
                                       </ul>
                                    </span>
                                </span> 
                                <a href="javascript:void(0)" class="pa-comment"><span>icon</span>Comment</a>
                                <a href="#sharepost-popup" class="popup-modal pa-share"><span>icon</span>Share</a>
                                <a href="javascript:void(0)" class="pa-message"><span>icon</span>Message</a>
                            </div>
                            <div class="post-more">
                                
                                <span class="total-comments">1 of 1</span>
                            </div>
                            <div class="post-comments">
                                <div class="pcomments">
                                    <div class="pcomment-holder">
                                        <div class="pcomment main-comment">
                                            <div class="img-holder">
                                                <div id="commentptip-11" class="profiletipholder">
                                                    <span class="profile-tooltip">
                                                        <img src="<?= $baseUrl ?>/images/demo-profile.jpg"/>
                                                    </span>
                                                    <span class="profiletooltip_content">
                                                        <div class="profile-tip">
                                                                    
                                                            <div class="profile-tip-cover"><img src="<?= $baseUrl ?>/images/cover.jpg"></div>
                                                            <div class="profile-tip-avatar">
                                                                <a href="#">
                                                                    <img alt="user-photo" class="img-responsive" src="<?= $baseUrl ?>/images/demo-profile.jpg">
                                                                </a>
                                                            </div>
                                                            <div class="profile-tip-info">
                                                                <div class="cover-username"><a href="#">Markand Trivedi</a></div>
                                                                <div class="cover-headline">
                                                                    <span class="ptip-icon"><i class="fa  fa-suitcase"></i></span>
                                                                    Web Designer, Cricketer
                                                                </div>
                                                                <div class="profiletip-bio">
                                                                    <span class="ptip-icon"><i class="fa fa-home"></i></span>
                                                                    Lives in : <span>Gariyadhar</span>
                                                                </div>
                                                                <div class="profiletip-bio">
                                                                    <span class="ptip-icon"><i class="fa fa-map-marker"></i></span>
                                                                    Currently in : <span>Gariyadhar, Gujarat, India</span>
                                                                </div>

                                                            </div>
                                                            <div class="profile-tip-divider"></div>
                                                            <div class="profile-tip-btn">
                                                                <a href="#" class="btn btn-primary btn-sm"><i class="fa fa-eye"></i>View Profile</a>
                                                            </div>
                                                        </div>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="desc-holder">
                                                <div class="normal-mode">
                                                    <div class="desc">
                                                        <a href="javascript:void(0)" class="userlink">Adel Hasanat</a>
                                                        <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh.</p>
                                                        
                                                    </div>
                                                    <div class="comment-stuff">
                                                        <div class="more-opt">
                                                            <!--<a href="javascript:void(0)" class="pa-like"><span>icon</span></a>-->
                                                            <span id="likeholder-14" class="likeholder">
                                                                <span class="like-tooltip">
                                                                    <a href="javascript:void(0)" class="pa-like"><span>icon</span></a>
                                                                </span>
                                                                <span class="tooltip_content">
                                                                   <ul class="like-ul">
                                                                    <li>User Name</li>
                                                                    <li>User Name</li>
                                                                    <li>User Name</li>
                                                                    <li>User Name</li>
                                                                    <li>User Name</li>
                                                                    <li>User Name</li>
                                                                    <li>User Name</li>
                                                                   </ul>
                                                                </span>
                                                            </span> 
                                                            <a href="javascript:void(0)" class="pa-reply reply-comment"><span>icon</span></a>
                                                            
                                                            <div class="dropdown dropdown-custom dropdown-xxsmall">
                                                                <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                                                    <i class="fa fa-ellipsis-v"></i>
                                                                </a>
                                                                <ul class="dropdown-menu">
                                                                    <li><a href="javascript:void(0)" class="edit-comment"><span class="glyphicon glyphicon-pencil"></span>Edit</a></li>
                                                                    <li><a href="javascript:void(0)" class="delete-comment"><span class="glyphicon glyphicon-trash"></span>Delete</a></li>                                                      
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        <div class="less-opt"><div class="timestamp">8h</div></div>
                                                    </div>
                                                </div>
                                                <div class="edit-mode">
                                                    <div class="desc">
                                                        <textarea class="editcomment-tt">Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh.</textarea>
                                                        <a href="javascript:void(0)" class="btn btn-primary btn-sm editcomment-cancel">Cancel</a>
                                                    </div>                                                                          
                                                </div>
                                            </div>                              
                                        </div>
                                        <div class="clear"></div>
                                        <div class="comment-reply-holder comment-addreply">                                 
                                            <div class="addnew-comment comment-reply">                          
                                                <div class="img-holder"><a href="javascript:void(0)"><img src="<?= $baseUrl ?>/images/demo-profile.jpg"/></a></div>
                                                <div class="desc-holder">                                   
                                                    <div class="sliding-middle-out anim-area">
                                                        <textarea>Write a reply...</textarea>
                                                    </div>
                                                </div>  
                                            </div>
                                        </div>
                                    </div>
                                </div>                              
                                
                                <div class="addnew-comment">
                                    <div class="img-holder"><a href="javascript:void(0)"><img src="<?= $baseUrl ?>/images/demo-profile.jpg"/></a></div>
                                    <div class="desc-holder">                                   
                                        <div class="sliding-middle-out anim-area">
                                            <textarea>Write a comment</textarea>
                                        </div>
                                    </div>  
                                </div>
                            </div>
                        </div>
                    </div>
                
                    <div class="post-holder bshadow">
                        <div class="post-topbar">
                            <div class="post-userinfo">
                                <div class="img-holder">
                                    <div id="profiletip-4" class="profiletipholder">
                                        <span class="profile-tooltip">
                                            <img src="<?= $baseUrl ?>/images/demo-profile.jpg"/>
                                        </span>
                                        <span class="profiletooltip_content">
                                            <div class="profile-tip">
                                                        
                                                <div class="profile-tip-cover"><img src="<?= $baseUrl ?>/images/cover.jpg"></div>
                                                <div class="profile-tip-avatar">
                                                    <a href="#">
                                                        <img alt="user-photo" class="img-responsive" src="<?= $baseUrl ?>/images/demo-profile.jpg">
                                                    </a>
                                                </div>
                                                <div class="profile-tip-info">
                                                    <div class="cover-username"><a href="#">Markand Trivedi</a></div>
                                                    <div class="cover-headline">
                                                        <span class="ptip-icon"><i class="fa  fa-suitcase"></i></span>
                                                        Web Designer, Cricketer
                                                    </div>
                                                    <div class="profiletip-bio">
                                                        <span class="ptip-icon"><i class="fa fa-home"></i></span>
                                                        Lives in : <span>Gariyadhar</span>
                                                    </div>
                                                    <div class="profiletip-bio">
                                                        <span class="ptip-icon"><i class="fa fa-map-marker"></i></span>
                                                        Currently in : <span>Gariyadhar, Gujarat, India</span>
                                                    </div>

                                                </div>
                                                <div class="profile-tip-divider"></div>
                                                <div class="profile-tip-btn">
                                                    <a href="#" class="btn btn-primary btn-sm"><i class="fa fa-eye"></i>View Profile</a>
                                                </div>
                                            </div>
                                        </span>
                                    </div>
                                </div>
                                <div class="desc-holder">
                                    <a href="javascript:void(0)">Nimish Parekh</a>
                                    <span class="timestamp">2 hrs<span class="glyphicon glyphicon-globe"></span></span>
                                </div>
                            </div>
                            <div class="settings-icon">
                                <div class="dropdown dropdown-custom dropdown-med resist">
                                    <a href="javascript:void(0)" class="dropdown-toggle" role="button" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-ellipsis-h"></i>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <ul class="post-sicon-list">
                                                <li><a href="javascript:void(0)" class="savepost-link"><span class="glyphicon glyphicon-bookmark"></span>Save Post</a></li>
                                                <li class="nicon"><a href="javascript:void(0)">Turn off notification for this post</a></li>
                                                <li class="nicon"><a href="#editpost-popup" class="popup-modal editpost-link">Edit Post</a></li>
                                                <li class="nicon"><a href="javascript:void(0)" class="deletepost-link">Delete Post</a></li>
                                            </ul>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="post-content">                      
                            <div class="post-img-holder">
                                <div class="post-img three-img gallery">
                                    <div class="pimg-holder himg-box"><a href="images/post-img2.jpg" rel="prettyPhoto[gallery3]"><img src="<?= $baseUrl ?>/images/post-img2.jpg" class="himg"/></a></div>
                                    <div class="pimg-holder vimg-box"><a href="images/post-img3.jpg" rel="prettyPhoto[gallery3]"><img src="<?= $baseUrl ?>/images/post-img3.jpg" class="vimg"/></a></div>
                                    <div class="pimg-holder himg-box"><a href="images/post-img1.jpg" rel="prettyPhoto[gallery3]"><img src="<?= $baseUrl ?>/images/post-img1.jpg" class="himg"/></a></div>
                                </div>
                            </div>
                        </div>
                        <div class="clear"></div>
                        <div class="post-data">
                            <div class="post-actions">
                                <!--a href="javascript:void(0)" class="pa-like"><span>icon</span>Like</a>-->
                                <span id="likeholder-15" class="likeholder">
                                    <span class="like-tooltip">
                                        <a href="javascript:void(0)" class="pa-like"><span>icon</span>Like</a>
                                    </span>
                                    <span class="tooltip_content">
                                       <ul class="like-ul">
                                        <li>User Name</li>
                                        <li>User Name</li>
                                        <li>User Name</li>
                                        <li>User Name</li>
                                        <li>User Name</li>
                                        <li>User Name</li>
                                        <li>User Name</li>
                                       </ul>
                                    </span>
                                </span> 
                                <a href="javascript:void(0)" class="pa-comment"><span>icon</span>Comment</a>
                                <a href="#sharepost-popup" class="popup-modal pa-share"><span>icon</span>Share</a>
                                <a href="javascript:void(0)" class="pa-message"><span>icon</span>Message</a>
                            </div>                      
                            <div class="post-comments">
                                <div class="addnew-comment">
                                    <div class="img-holder"><a href="javascript:void(0)"><img src="<?= $baseUrl ?>/images/demo-profile.jpg"/></a></div>
                                    <div class="desc-holder">                                   
                                        <div class="sliding-middle-out anim-area">
                                            <textarea>Write a comment</textarea>
                                        </div>
                                    </div>  
                                </div>
                            </div>
                        </div>
                    </div>
                
                    <div class="post-holder bshadow">
                        <div class="post-topbar">
                            <div class="post-userinfo">
                                <div class="img-holder">
                                    <div id="profiletip-5" class="profiletipholder">
                                        <span class="profile-tooltip">
                                            <img src="<?= $baseUrl ?>/images/demo-profile.jpg"/>
                                        </span>
                                        <span class="profiletooltip_content">
                                            <div class="profile-tip">
                                                        
                                                <div class="profile-tip-cover"><img src="<?= $baseUrl ?>/images/cover.jpg"></div>
                                                <div class="profile-tip-avatar">
                                                    <a href="#">
                                                        <img alt="user-photo" class="img-responsive" src="<?= $baseUrl ?>/images/demo-profile.jpg">
                                                    </a>
                                                </div>
                                                <div class="profile-tip-info">
                                                    <div class="cover-username"><a href="#">Markand Trivedi</a></div>
                                                    <div class="cover-headline">
                                                        <span class="ptip-icon"><i class="fa  fa-suitcase"></i></span>
                                                        Web Designer, Cricketer
                                                    </div>
                                                    <div class="profiletip-bio">
                                                        <span class="ptip-icon"><i class="fa fa-home"></i></span>
                                                        Lives in : <span>Gariyadhar</span>
                                                    </div>
                                                    <div class="profiletip-bio">
                                                        <span class="ptip-icon"><i class="fa fa-map-marker"></i></span>
                                                        Currently in : <span>Gariyadhar, Gujarat, India</span>
                                                    </div>

                                                </div>
                                                <div class="profile-tip-divider"></div>
                                                <div class="profile-tip-btn">
                                                    <a href="#" class="btn btn-primary btn-sm"><i class="fa fa-eye"></i>View Profile</a>
                                                </div>
                                            </div>
                                        </span>
                                    </div>
                                </div>
                                <div class="desc-holder">
                                    <a href="javascript:void(0)">Nimish Parekh</a>
                                    <span class="timestamp">2 hrs<span class="glyphicon glyphicon-globe"></span></span>
                                </div>
                            </div>
                            <div class="settings-icon">
                                <div class="dropdown dropdown-custom dropdown-med resist">
                                    <a href="javascript:void(0)" class="dropdown-toggle" role="button" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-ellipsis-h"></i>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <ul class="post-sicon-list">
                                                <li><a href="javascript:void(0)" class="savepost-link"><span class="glyphicon glyphicon-bookmark"></span>Save Post</a></li>
                                                <li class="nicon"><a href="javascript:void(0)">Turn off notification for this post</a></li>
                                                <li class="nicon"><a href="#editpost-popup" class="popup-modal editpost-link">Edit Post</a></li>
                                                <li class="nicon"><a href="javascript:void(0)" class="deletepost-link">Delete Post</a></li>
                                            </ul>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="post-content">                      
                            <div class="post-img-holder">
                                <div class="post-img four-img gallery">
                                    <div class="pimg-holder himg-box"><a href="images/post-img1.jpg" rel="prettyPhoto[gallery4]"><img src="<?= $baseUrl ?>/images/post-img1.jpg" class="himg"/></a></div>
                                    <div class="pimg-holder himg-box"><a href="images/post-img2.jpg" rel="prettyPhoto[gallery4]"><img src="<?= $baseUrl ?>/images/post-img2.jpg" class="himg"/></a></div>
                                    <div class="pimg-holder himg-box"><a href="images/post-img.jpg" rel="prettyPhoto[gallery4]"><img src="<?= $baseUrl ?>/images/post-img.jpg" class="himg"/></a></div>
                                    <div class="pimg-holder vimg-box"><a href="images/post-img3.jpg" rel="prettyPhoto[gallery4]"><img src="<?= $baseUrl ?>/images/post-img3.jpg" class="vimg"/></a></div>
                                </div>
                            </div>
                        </div>
                        <div class="clear"></div>
                        <div class="post-data">
                            <div class="post-actions">
                                <!--<a href="javascript:void(0)" class="pa-like"><span>icon</span>Like</a>-->
                                <span id="likeholder-16" class="likeholder">
                                    <span class="like-tooltip">
                                        <a href="javascript:void(0)" class="pa-like"><span>icon</span>Like</a>
                                    </span>
                                    <span class="tooltip_content">
                                       <ul class="like-ul">
                                        <li>User Name</li>
                                        <li>User Name</li>
                                        <li>User Name</li>
                                        <li>User Name</li>
                                        <li>User Name</li>
                                        <li>User Name</li>
                                        <li>User Name</li>
                                       </ul>
                                    </span>
                                </span> 
                                <a href="javascript:void(0)" class="pa-comment"><span>icon</span>Comment</a>
                                <a href="#sharepost-popup" class="popup-modal pa-share"><span>icon</span>Share</a>
                                <a href="javascript:void(0)" class="pa-message"><span>icon</span>Message</a>
                            </div>                      
                            <div class="post-comments">
                                <div class="addnew-comment">
                                    <div class="img-holder"><a href="javascript:void(0)"><img src="<?= $baseUrl ?>/images/demo-profile.jpg"/></a></div>
                                    <div class="desc-holder">                                   
                                        <div class="sliding-middle-out anim-area">
                                            <textarea>Write a comment</textarea>
                                        </div>
                                    </div>  
                                </div>
                            </div>
                        </div>
                    </div>
                
                    <div class="post-holder bshadow">
                        <div class="post-topbar">
                            <div class="post-userinfo">
                                <div class="img-holder">
                                    <div id="profiletip-6" class="profiletipholder">
                                        <span class="profile-tooltip">
                                            <img src="<?= $baseUrl ?>/images/demo-profile.jpg"/>
                                        </span>
                                        <span class="profiletooltip_content">
                                            <div class="profile-tip">
                                                        
                                                <div class="profile-tip-cover"><img src="<?= $baseUrl ?>/images/cover.jpg"></div>
                                                <div class="profile-tip-avatar">
                                                    <a href="#">
                                                        <img alt="user-photo" class="img-responsive" src="<?= $baseUrl ?>/images/demo-profile.jpg">
                                                    </a>
                                                </div>
                                                <div class="profile-tip-info">
                                                    <div class="cover-username"><a href="#">Markand Trivedi</a></div>
                                                    <div class="cover-headline">
                                                        <span class="ptip-icon"><i class="fa  fa-suitcase"></i></span>
                                                        Web Designer, Cricketer
                                                    </div>
                                                    <div class="profiletip-bio">
                                                        <span class="ptip-icon"><i class="fa fa-home"></i></span>
                                                        Lives in : <span>Gariyadhar</span>
                                                    </div>
                                                    <div class="profiletip-bio">
                                                        <span class="ptip-icon"><i class="fa fa-map-marker"></i></span>
                                                        Currently in : <span>Gariyadhar, Gujarat, India</span>
                                                    </div>

                                                </div>
                                                <div class="profile-tip-divider"></div>
                                                <div class="profile-tip-btn">
                                                    <a href="#" class="btn btn-primary btn-sm"><i class="fa fa-eye"></i>View Profile</a>
                                                </div>
                                            </div>
                                        </span>
                                    </div>
                                </div>
                                <div class="desc-holder">
                                    <a href="javascript:void(0)">Nimish Parekh</a>
                                    <span class="timestamp">2 hrs<span class="glyphicon glyphicon-globe"></span></span>
                                </div>
                            </div>
                            <div class="settings-icon">
                                <div class="dropdown dropdown-custom dropdown-med resist">
                                    <a href="javascript:void(0)" class="dropdown-toggle" role="button" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-ellipsis-h"></i>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <ul class="post-sicon-list">
                                                <li><a href="javascript:void(0)" class="savepost-link"><span class="glyphicon glyphicon-bookmark"></span>Save Post</a></li>
                                                <li class="nicon"><a href="javascript:void(0)">Turn off notification for this post</a></li>
                                                <li class="nicon"><a href="#editpost-popup" class="popup-modal editpost-link">Edit Post</a></li>
                                                <li class="nicon"><a href="javascript:void(0)" class="deletepost-link">Delete Post</a></li>
                                            </ul>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="post-content">                      
                            <div class="post-img-holder">
                                <div class="post-img five-img gallery">
                                    <div class="pimg-holder himg-box"><a href="images/post-img1.jpg" rel="prettyPhoto[gallery5]"><img src="<?= $baseUrl ?>/images/post-img1.jpg" class="himg"/></a></div>
                                    <div class="pimg-holder himg-box"><a href="images/post-img2.jpg" rel="prettyPhoto[gallery5]"><img src="<?= $baseUrl ?>/images/post-img2.jpg" class="himg"/></a></div>
                                    <div class="pimg-holder himg-box"><a href="images/post-img.jpg" rel="prettyPhoto[gallery5]"><img src="<?= $baseUrl ?>/images/post-img.jpg" class="himg"/></a></div>
                                    <div class="pimg-holder vimg-box"><a href="images/post-img3.jpg" rel="prettyPhoto[gallery5]"><img src="<?= $baseUrl ?>/images/post-img3.jpg" class="vimg"/></a></div>
                                    <div class="pimg-holder himg-box"><a href="images/post-img4.jpg" rel="prettyPhoto[gallery5]"><img src="<?= $baseUrl ?>/images/post-img4.jpg" class="himg"/></a></div>
                                </div>
                            </div>
                        </div>
                        <div class="clear"></div>
                        <div class="post-data">
                            <div class="post-actions">
                                <!--<a href="javascript:void(0)" class="pa-like"><span>icon</span>Like</a>-->
                                <span id="likeholder-17" class="likeholder">
                                    <span class="like-tooltip">
                                        <a href="javascript:void(0)" class="pa-like"><span>icon</span>Like</a>
                                    </span>
                                    <span class="tooltip_content">
                                       <ul class="like-ul">
                                        <li>User Name</li>
                                        <li>User Name</li>
                                        <li>User Name</li>
                                        <li>User Name</li>
                                        <li>User Name</li>
                                        <li>User Name</li>
                                        <li>User Name</li>
                                       </ul>
                                    </span>
                                </span> 
                                <a href="javascript:void(0)" class="pa-comment"><span>icon</span>Comment</a>
                                <a href="#sharepost-popup" class="popup-modal pa-share"><span>icon</span>Share</a>
                                <a href="javascript:void(0)" class="pa-message"><span>icon</span>Message</a>
                            </div>                      
                            <div class="post-comments">
                                <div class="addnew-comment">
                                    <div class="img-holder"><a href="javascript:void(0)"><img src="<?= $baseUrl ?>/images/demo-profile.jpg"/></a></div>
                                    <div class="desc-holder">                                   
                                        <div class="sliding-middle-out anim-area">
                                            <textarea>Write a comment</textarea>
                                        </div>
                                    </div>  
                                </div>
                            </div>
                        </div>
                    </div>
                
                    <div class="post-holder bshadow">
                        <div class="post-topbar">
                            <div class="post-userinfo">
                                <div class="img-holder">
                                    <div id="profiletip-7" class="profiletipholder">
                                        <span class="profile-tooltip">
                                            <img src="<?= $baseUrl ?>/images/demo-profile.jpg"/>
                                        </span>
                                        <span class="profiletooltip_content">
                                            <div class="profile-tip">
                                                        
                                                <div class="profile-tip-cover"><img src="<?= $baseUrl ?>/images/cover.jpg"></div>
                                                <div class="profile-tip-avatar">
                                                    <a href="#">
                                                        <img alt="user-photo" class="img-responsive" src="<?= $baseUrl ?>/images/demo-profile.jpg">
                                                    </a>
                                                </div>
                                                <div class="profile-tip-info">
                                                    <div class="cover-username"><a href="#">Markand Trivedi</a></div>
                                                    <div class="cover-headline">
                                                        <span class="ptip-icon"><i class="fa  fa-suitcase"></i></span>
                                                        Web Designer, Cricketer
                                                    </div>
                                                    <div class="profiletip-bio">
                                                        <span class="ptip-icon"><i class="fa fa-home"></i></span>
                                                        Lives in : <span>Gariyadhar</span>
                                                    </div>
                                                    <div class="profiletip-bio">
                                                        <span class="ptip-icon"><i class="fa fa-map-marker"></i></span>
                                                        Currently in : <span>Gariyadhar, Gujarat, India</span>
                                                    </div>

                                                </div>
                                                <div class="profile-tip-divider"></div>
                                                <div class="profile-tip-btn">
                                                    <a href="#" class="btn btn-primary btn-sm"><i class="fa fa-eye"></i>View Profile</a>
                                                </div>
                                            </div>
                                        </span>
                                    </div>
                                </div>
                                <div class="desc-holder">
                                    <a href="javascript:void(0)">Nimish Parekh</a>
                                    <span class="timestamp">2 hrs<span class="glyphicon glyphicon-globe"></span></span>
                                </div>
                            </div>
                            <div class="settings-icon">
                                <div class="dropdown dropdown-custom dropdown-med resist">
                                    <a href="javascript:void(0)" class="dropdown-toggle" role="button" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-ellipsis-h"></i>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <ul class="post-sicon-list">
                                                <li><a href="javascript:void(0)" class="savepost-link"><span class="glyphicon glyphicon-bookmark"></span>Save Post</a></li>
                                                <li class="nicon"><a href="javascript:void(0)">Turn off notification for this post</a></li>
                                                <li class="nicon"><a href="#editpost-popup" class="popup-modal editpost-link">Edit Post</a></li>
                                                <li class="nicon"><a href="javascript:void(0)" class="deletepost-link">Delete Post</a></li>
                                            </ul>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="post-content">                      
                            <div class="post-img-holder">
                                <div class="post-img more-img gallery">
                                    <div class="pimg-holder himg-box"><a href="images/post-img1.jpg" rel="prettyPhoto[gallery6]"><img src="<?= $baseUrl ?>/images/post-img1.jpg" class="himg"/></a></div>
                                    <div class="pimg-holder himg-box"><a href="images/post-img2.jpg" rel="prettyPhoto[gallery6]"><img src="<?= $baseUrl ?>/images/post-img2.jpg" class="himg"/></a></div>
                                    <div class="pimg-holder himg-box"><a href="images/post-img.jpg" rel="prettyPhoto[gallery6]"><img src="<?= $baseUrl ?>/images/post-img.jpg" class="himg"/></a></div>
                                    <div class="pimg-holder vimg-box"><a href="images/post-img3.jpg" rel="prettyPhoto[gallery6]"><img src="<?= $baseUrl ?>/images/post-img3.jpg" class="vimg"/></a></div>
                                    <div class="pimg-holder himg-box"><a href="images/post-img4.jpg" rel="prettyPhoto[gallery6]">
                                        <img src="<?= $baseUrl ?>/images/post-img4.jpg" class="himg"/></a>
                                        <div class="more-box"><a href="javascript:void(0)"><i class="fa fa-plus"></i>2</a></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="clear"></div>
                        <div class="post-data">
                            <div class="post-actions">
                                <!--<a href="javascript:void(0)" class="pa-like"><span>icon</span>Like</a>-->
                                <span id="likeholder-18" class="likeholder">
                                    <span class="like-tooltip">
                                        <a href="javascript:void(0)" class="pa-like"><span>icon</span>Like</a>
                                    </span>
                                    <span class="tooltip_content">
                                       <ul class="like-ul">
                                        <li>User Name</li>
                                        <li>User Name</li>
                                        <li>User Name</li>
                                        <li>User Name</li>
                                        <li>User Name</li>
                                        <li>User Name</li>
                                        <li>User Name</li>
                                       </ul>
                                    </span>
                                </span> 
                                <a href="javascript:void(0)" class="pa-comment"><span>icon</span>Comment</a>
                                <a href="#sharepost-popup" class="popup-modal pa-share"><span>icon</span>Share</a>
                                <a href="javascript:void(0)" class="pa-message"><span>icon</span>Message</a>
                            </div>                      
                            <div class="post-comments">
                                <div class="addnew-comment">
                                    <div class="img-holder"><a href="javascript:void(0)"><img src="<?= $baseUrl ?>/images/demo-profile.jpg"/></a></div>
                                    <div class="desc-holder">                                   
                                        <div class="sliding-middle-out anim-area">
                                            <textarea>Write a comment</textarea>
                                        </div>
                                    </div>  
                                </div>
                            </div>
                        </div>
                    </div>
                
                
                </div>