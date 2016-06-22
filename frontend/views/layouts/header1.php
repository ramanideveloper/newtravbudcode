<?php
/* @var $this \yii\web\View */
/* @var $content string */
use yii\helpers\Html;
use frontend\assets\AppAsset;
$baseUrl = Yii::getAlias('@web');
?>

<div class="topbar">
                <ul class="topbar-menu">
                    <li><a href="javascript:void(0)">Hotels</a></li>
                    <li><a href="javascript:void(0)">Tours</a></li>             
                    <li class="dropdown dropdown-custom ">
                      <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Places</a>
                      <ul class="dropdown-menu">
                        <li><a href="javascript:void(0)"><span class="glyphicon glyphicon-plus"></span>Add Destination</a></li>
                        <li><a href="javascript:void(0)"><span class="glyphicon glyphicon-globe"></span>Browse Destination</a></li>
                        <li><a href="javascript:void(0)"><span class="glyphicon glyphicon-heart-empty"></span>My Destination</a></li>
                        <li><a href="javascript:void(0)"><span class="glyphicon glyphicon-tower"></span>Top Places</a></li>
                      </ul>
                    </li>
                    <li><a href="javascript:void(0)">Write Review</a></li>
                    <li><a href="javascript:void(0)">Agent</a></li>
                
                    <li class="dropdown dropdown-custom ">
                      <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Business Services</a>
                      <ul class="dropdown-menu">
                        <li><a href="javascript:void(0)"><span class="glyphicon glyphicon-plus"></span>Create Guide Profile</a></li>                    
                        <li><a href="javascript:void(0)"><span class="glyphicon glyphicon-globe"></span>Advertising Manager</a></li>                                        
                        <li><a href="javascript:void(0)"><span class="glyphicon glyphicon-credit-card"></span>Travel Agent contact</a></li>                 
                        <li><a href="javascript:void(0)"><span class="glyphicon glyphicon-tower"></span>Hotel For Less</a></li>
                        <li><a href="javascript:void(0)"><span class="glyphicon glyphicon-shopping-cart"></span>Travel store</a></li>
                      </ul>
                    </li>
                </ul>
            </div>