<?php
/* @var $this \yii\web\View */
/* @var $content string */
use yii\helpers\Html;
use frontend\assets\AppAsset;
$baseUrl = Yii::getAlias('@web');
?>
<div id="custom-privacy-popup" class="mfp-hide white-popup-block popup-area privacy-popup">
        <div class="popup-title">
            <h3>Custom Privacy</h3>
            <a class="popup-modal-dismiss close-popup" href="javascript:void(0)"><span class="glyphicon glyphicon-remove"></span></a>
        </div>
        <div class="popup-content">
            <div class="content-holder">
                <div class="security-block sharewith-block">
                    <div class="icon-holder"><i class="fa fa-plus"></i></div>
                    <div class="desc-holder">
                        <h4>Share with</h4>
                        <div class="clear"></div>
                        <div class="block">
                            <div class="row">
                                <div class="col-lg-4 col-sm-12 col-xs-12">
                                    <p>These people or lists</p>
                                </div>
                                <div class="col-lg-8 col-sm-12 col-xs-12 pull-right">
                                    <div class="people-list">
                                        <select id='customin1' name='sharewith'>
                                            <option>person-1</option>
                                            <option>person-2</option>
                                            <option>person-3</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>      
                        <div class="block">
                            <div class="row">
                                <div class="col-lg-4 col-sm-12 col-xs-12">
                                    <p>Friends of Tagged</p>
                                </div>
                                <div class="col-lg-8 col-sm-12 col-xs-12 pull-right">
                                    <div class="people-list">
                                        <input type="checkbox" name='customchk' id='customchk' class='customchk'  style='display: none;'>
                                    </div>
                                </div>
                            </div>
                        </div>      
                        <div class="block">
                            <div class="row">                           
                                <div class="col-lg-8 col-sm-12 pull-right">                             
                                    <div class="people-list">
                                        <p>Anyone tagged will be able to see the post</p>
                                    </div>
                                </div>
                            </div>
                        </div>      
                    </div>
                </div>
                <div class="security-block dontshare-block">
                    <div class="icon-holder"><i class="fa fa-close"></i></div>
                    <div class="desc-holder">
                        <h4>Don't Share with</h4>
                        <div class="clear"></div>
                        <div class="block">
                            <div class="row">
                                <div class="col-lg-4 col-sm-12">
                                    <p>These people or lists</p>
                                </div>
                                <div class="col-lg-8 col-sm-12 pull-right">
                                    <div class="people-list">
                                        <select id="customin3" name="sharenot">
                                            <option>person-1</option>
                                            <option>person-2</option>
                                            <option>person-3</option>
                                        </select>
                                        <p>Anyone you include here or have on your restricted list won't be able to see this post unless you tag them. We don't let people know when you choose to not share something with them.</p>
                                    </div>
                                </div>                          
                            </div>
                        </div>      
                    </div>
                </div>
                <div class="pull-right tp10">
                    <a class="btn btn-primary" href="javascript:void(0)"><span class="glyphicon glyphicon-save"></span>Save Changes</a>
                    <a class="btn btn-primary" href="javascript:void(0)"><span class="glyphicon glyphicon-remove"></span>Cancel</a>
                </div>
            </div>  
        </div>
    </div>