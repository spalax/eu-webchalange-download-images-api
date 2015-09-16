<?php
return [
    'routes' => [
        'application' => [
            'type' => 'literal',
            'options' => [
                'route' => '/',
                'defaults' => [
                    'controller' => 'Application-Controller-Index'
                ]
            ],
            'may_terminate' => true,
            'child_routes' => [
                'login' => [
                    'type' => 'literal',
                    'options' => [
                        'route' => 'login/success',
                        'defaults' => [
                            'controller' => 'Application-Controller-Login-Success'
                        ]
                    ]
                ],
                'logout' => [
                    'type' => 'literal',
                    'options' => [
                        'route' => 'logout',
                        'defaults' => [
                            'controller' => 'Application-Controller-Logout'
                        ]
                    ]
                ],
                'gallery' => [
                    'type' => 'literal',
                    'options' => [
                        'route' => 'gallery',
                    ],
                    'may_terminate' => false,
                    'child_routes' => [
                        'configure' => [
                            'type' => 'literal',
                            'options' => [
                                'route' => '/configure',
                                'defaults' => [
                                    'controller' => 'Application-Controller-Gallery-Configure'
                                ]
                            ]
                        ],
                        'preview' => [
                            'type' => 'method',
                            'options' => [
                                'verb' => 'get',
                                'defaults' => [
                                    'controller' => 'Application-Controller-Gallery-Preview'
                                ]
                            ]
                        ],
                        'collage' => [
                            'type' => 'method',
                            'options' => [
                                'verb' => 'post',
                                'defaults' => [
                                    'controller' => 'Application-Controller-Gallery-Collage'
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ]
];
