<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return [
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'Знакомства',
    'language' => 'ru',

    // preloading 'log' component
    'preload' => ['log', 'booster'],

    // autoloading model and component classes
    'import' => [
        'application.models.*',
        'application.models.forms.*',
        'application.models.validators.*',
        'application.components.*',
        'application.extensions.YiiBooster.src.widgets.*',
    ],

    'modules' => [
        'admin',
        'gii' => [
            'class' => 'system.gii.GiiModule',
            'password' => '1234',
            // If removed, Gii defaults to localhost only. Edit carefully to taste.
            'ipFilters' => ['127.0.0.1', '::1'],
        ],

    ],

    // application components
    'components' => [

        'user' => [
            // enable cookie-based authentication
            'class' => 'WebUser',
            'allowAutoLogin' => true,
        ],

        // uncomment the following to enable URLs in path-format

        'urlManager' => [
            'urlFormat' => 'path',
            'urlSuffix' => '.html',
            'showScriptName' => false,
            'rules' => [
                ''=>'site/index',
                'login'=>'site/login',
                'register'=>'site/register',
                'profile'=>'site/profile',
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ],
        ],


        // database settings are configured in database.php
//        'db' => require(dirname(__FILE__) . '/database.php'),

        'db'=>array(
            'connectionString' => 'mysql:host=localhost;dbname=project',
            'emulatePrepare' => true,
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
        ),

        'errorHandler' => [
            // use 'site/error' action to display errors
            'errorAction' => 'site/error',
        ],

        'log' => [
            'class' => 'CLogRouter',
            'routes' => [
                [
                    'class' => 'ext.yii-debug-toolbar.YiiDebugToolbarRoute',
                    'ipFilters' => ['127.0.0.1', '192.168.1.215'],
                ],
                // uncomment the following to show log messages on web pages
                /*
                array(
                    'class'=>'CWebLogRoute',
                ),
                */
            ],
        ],
        'booster' => [
            'class' => 'application.extensions.YiiBooster.src.components.Booster',
            'fontAwesomeCss' => true,
            'responsiveCss' => true,
        ],
        'assetManager' => [
            'class' => 'AssetManager',
        ],
        'authManager' => [
            'class' => 'AuthManager',
            'defaultRoles' => ['guest'],
        ],

    ],

    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params' => include (__DIR__) . '/params.php',
];
