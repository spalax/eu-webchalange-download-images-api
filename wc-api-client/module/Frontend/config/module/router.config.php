<?php
return [
    'routes' => [
        'frontend' => [
            'type' => 'segment',
            'options' => [
                'route' => '/',
                'defaults' => [
                    'controller' => 'Frontend-Controller-Index',
                    'page' => 1
                ]
            ],
            'may_terminate' => true,
            'child_routes' => [
                'pagination' => [
                    'type' => 'segment',
                    'options' => [
                        'route' => 'page/:page',
                        'constraints' => [
                            'page' => '[0-9]+'
                        ],
                        'defaults' => [
                            'controller' => 'Frontend-Controller-Index',
                            'page' => 1
                        ]
                    ],
                ],
                'pages' => [
                    'type' => 'literal',
                    'options' => [
                        'route' => 'pages'
                    ],
                    'may_terminate' => false,
                    'child_routes' => [
                        'add' => [
                            'type' => 'method',
                            'options' => [
                                'verb' => 'post',
                                'defaults' => [
                                    'controller' => 'Frontend-Controller-Pages-Add'
                                ],
                            ],
                        ],
                        'images' => [
                            'type' => 'segment',
                            'options' => [
                                'route' => '/:page_id',
                                'constraints' => [
                                    'page_id' => '[a-z0-9A-Z\-]+'
                                ],
                                'defaults' => [
                                    'controller' => 'Frontend-Controller-Pages-Images',
                                ]
                            ],
                            'may_terminate' => true,
                            'child_routes' => [
                                'pagination' => [
                                    'type' => 'segment',
                                    'options' => [
                                        'route' => '/page/:page',
                                        'constraints' => [
                                            'page' => '[0-9]+'
                                        ],
                                        'defaults' => [
                                            'controller' => 'Frontend-Controller-Pages-Images',
                                            'page' => 1
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ]
        ]
    ]
];
