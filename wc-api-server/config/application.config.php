<?php
return [
    'modules' => [
        'Application',
        'ZF\Apigility',
        'ZF\Apigility\Provider',
        'ZF\Apigility\Documentation\Swagger',
        'ZF\Apigility\Documentation',
        'AssetManager',
        'ZF\ApiProblem',
        'ZF\MvcAuth',
        'ZF\OAuth2',
        'ZF\Hal',
        'ZF\ContentNegotiation',
        'ZF\ContentValidation',
        'ZF\Rest',
        'ZF\Rpc',
        'ZF\Versioning',
        'ZF\DevelopmentMode',
        'DoctrineModule',
        'DoctrineORMModule',
        'SlmQueue',
        'SlmQueueDoctrine',
        'AwsModule',
    ],
    'module_listener_options' => [
        'config_glob_paths'    => [
            'config/autoload/{,*.}{global,local}.php',
        ],
        'module_paths' => [
            './module',
            './vendor'
        ]
    ]
];
