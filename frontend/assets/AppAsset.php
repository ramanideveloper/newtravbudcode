<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
   /* public $basePath = '@webroot';
    public $baseUrl = '@web'; */
    public $sourcePath = '@bower/travel/';
    public $css = [
     //   'sliders/css/flexslider.css',
       /*'css/bootstrap.min.css',
        'css/bootstrap-theme.min.css',
        'css/template.css',
        'css/custome-responsive.css',
        'css/bootstrap-datetimepicker.css',*/
        //'css/flexslider.css',
        
       // 'css/component.css',
        
    ];
    public $js = [
      //  'sliders/js/plugins.js',
      //  'sliders/js/jquery.flexslider-min.js',
   /*  'js/jquery-1.11.0.min.js',
        'js/bootstrap.min.js',
        'js/moment-with-locales.js',
        'js/bootstrap-datetimepicker.js',
        'js/modernizr.js',*/
       // 'js/jquery.flexslider-min.js',
        //'js/plugins.js',
       // 'js/jquery-1.11.0.min.js',
        //'js/classie.js',
        //'js/modernizr.custom.js',
       // 'js/uisearch.js',
       
    ];
    
//    public $sliders = [
//        'sliders/css/flexslider.css',
//        'sliders/js/plugins.js',
//        'sliders/js/jquery.flexslider-min.js',
//        
  //  ];
    public $depends = [
        'yii\web\YiiAsset',
       // 'yii\bootstrap\BootstrapAsset',
    ];
}
