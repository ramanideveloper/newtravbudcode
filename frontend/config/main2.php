<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
        ],
        'EphocTime' => [
 
            'class' => 'common\components\EphocTime',
 
            ],
         'CDbCriteria' => [
 
            'class' => 'common\components\CDbCriteria',
 
            ],
       'assetManager' => [
       // 'linkAssets' => true,
	    'linkAssets' => false,
        ], 
        // Fcaebook App Details
        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
             'facebook' => [
                'class' => 'yii\authclient\clients\Facebook',
                'authUrl' => 'https://www.facebook.com/dialog/oauth?display=popup',
              //  'clientId' => '1175115125837425', /* payal's fb app credentials for payal's pc */
              //  'clientSecret' => 'f182ad3563409929a3ee7da1ff0cf502', /* payal's fb app credentials for payal's pc */
              //  'clientId' => '922311544542560', /* bhumi's fb app credentials for server pc */
              //  'clientSecret' => '566c4b453a46b21d7401693a7672516d', /* bhumi's fb app credentials for server pc */
                  'clientId' => '454389514767288', /* bhumi's fb app credentials for local pc */
                  'clientSecret' => '6dad47ca4b21390fa9202f0b30b2e040', /* bhumi's fb app credentials for local pc */
		  //'clientId' => '974478385976123', /* bhumi's fb app credentials for live site pc */
           //       'clientSecret' => 'f5aac5342ae1801ddaf0dbaeb1388093', /* bhumi's fb app credentials for live site pc */

              //    'clientId' => '454389514767288', /* bhumi's fb app credentials for local pc */
                //  'clientSecret' => '6dad47ca4b21390fa9202f0b30b2e040', /* bhumi's fb app credentials for local pc */
		 //'clientId' => '454389514767288', /* bhumi's fb app credentials for live site pc */
                  //'clientSecret' => '6dad47ca4b21390fa9202f0b30b2e040', /* bhumi's fb app credentials for live site pc */
              ],'google' => [
                'class' => 'yii\authclient\clients\GoogleOAuth',
                'returnUrl' => 'http://localhost/travbud/frontend/web/index.php?r=site/abc',
                'clientId' => '215771007590-u2qkpstdnlupna88ehp2sli3u0q82nvt.apps.googleusercontent.com',
                'clientSecret' => 'YCnTDmoPMgwgZcXj9glahSMg',
            ],
            ],
         ],
         'i18n' => [
            'translations' => [
                'eauth' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@eauth/messages',
                ],
            ],
        ],
        //google+ login settings start
         'eauth' => [
            'class' => 'nodge\eauth\EAuth',
            'popup' => true, // Use the popup window instead of redirecting.
            'cache' => false, // Cache component name or false to disable cache. Defaults to 'cache' on production environments.
            'cacheExpire' => 0, // Cache lifetime. Defaults to 0 - means unlimited.
            'httpClient' => [
                // uncomment this to use streams in safe_mode
                //'useStreamsFallback' => true,
            ],
            'services' => [ // You can change the providers and their classes.
                'google' => [
                    // register your app here: https://code.google.com/apis/console/
                    'class' => 'nodge\eauth\services\GoogleOAuth2Service',
                    'clientId' => '...',
                    'clientSecret' => '...',
                    'title' => 'Google',
                ],
                        //google+ login settings start
            ],
        ],
          //google+ login settings END
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'session' => [
            'name' => 'PHPFRONTSESSID',
            'savePath' => __DIR__ . '/../tmp',
        ],
    ],
    
    
//    'db'=> array(
//        'enableProfiling'=>true,
//        'enableParamLogging' => true,
//    ),
//    'log'=>  array(
//        'class'=>'CLogRouter',
//        'routes'=>array(
//
//        array(
//            'class'=>'CProfileLogRoute',
//            'levels'=>'profile',
//            'enabled'=>true,
//            ),
//        ),
//    ),
//    
    'params' => $params,
];
