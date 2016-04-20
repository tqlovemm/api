<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);
return [
    'id' => 'app-api',
    'basePath' => dirname(__DIR__),    
    'bootstrap' => ['log'],
    'modules' => [
        'v2' => [
            'basePath' => '@app/modules/v2',
            'class' => 'api\modules\v2\Module'
        ],
        'v3' => [
            'basePath' => '@app/modules/v3',
            'class' => 'api\modules\v3\Module'
        ],
    ],
    'components' => [        
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => false,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            /*'enableStrictParsing' => true,*/
            'showScriptName' => false,
            'rules' => [
                [
                    'class' => 'yii\rest\UrlRule', 
                    'controller' => [
                      'v2/thread','v2/user','v2/user1','v2/post','v2/profile','v2/data','v2/mark','v2/ufollow','v2/note','v2/follow','v2/claims-thread',
                        'v2/flop','v2/flop-content','v2/flop-content-data',
                    ],
                    'tokens' => [
                        '{id}' => '<id:\\w+>',

                    ]
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => [
                      'v3/dating','v3/dating-content','v3/dating-comment','v3/date','v3/version','v3/push','v3/heartweek','v3/slide-content',
                    ],
                    'tokens' => [
                        '{id}' => '<id:\\w+>',

                    ]
                ]
            ],
        ]
    ],
    'params' => $params,
];



