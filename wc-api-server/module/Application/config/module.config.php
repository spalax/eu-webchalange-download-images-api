<?php
return [
    'router' => [
        'routes' => [
            'application.rest.pages' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/pages[/:page_id]',
                    'defaults' => [
                        'controller' => 'Application\\V1\\Rest\\Pages\\Controller',
                    ],
                ],
            ],
            'application.rest.images' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/pages/:page_id/images',
                    'defaults' => [
                        'controller' => 'Application\\V1\\Rest\\Images\\Controller',
                    ],
                ],
            ],
        ],
    ],
    'doctrine' => [
        'driver' => [
            'app_driver' => [
                'class' => 'Doctrine\\ORM\\Mapping\\Driver\\AnnotationDriver',
                'cache' => 'array',
                'paths' => [
                    0 => __DIR__ . '/../src/Application/V1/Entity'
                ],
            ],
            'orm_default' => [
                'drivers' => [
                    'Application\\V1\\Entity' => 'app_driver'
                ],
            ],
        ],
    ],
    'slm_queue' => [
        'queue_manager' => [
            'factories' => [
                'ParsePageQueue' => 'SlmQueueDoctrine\\Factory\\DoctrineQueueFactory',
                'GrabImageQueue' => 'SlmQueueDoctrine\\Factory\\DoctrineQueueFactory'
            ],
        ],
        'job_manager' => [
            'factories' => [
                'Application\\QueueJob\\ParsePage' =>
                    function (\Zend\ServiceManager\AbstractPluginManager $interface) {
                        $factory = new \Application\QueueJob\ParsePageFactory();
                        return $factory->createService($interface->getServiceLocator());
                    },
                'Application\\QueueJob\\GrabImage' =>
                    function (\Zend\ServiceManager\AbstractPluginManager $interface) {
                        $factory = new \Application\QueueJob\GrabImageFactory();
                        return $factory->createService($interface->getServiceLocator());
                    },
            ],
        ],
        'queues' => [
            'GrabImageQueue' => [
                'table_name' => 'queue',
            ],
            'ParsePageQueue' => [
                'table_name' => 'queue',
            ],
        ],
    ],
    'view_helpers' => [
        'invokables' => [],
    ],
    'application' => [
        'imageStorage' => [
            'type' => 'local',
            'options' => [
                'fsPath' => '/Users/oleksiimylotskyi/Sites/dev/wc-api/wc-api-server/public/uploads',
                'httpPath' => 'http://wc-api-server.my/uploads'
            ],
//            'type' => 'aws',
//            'options' => [ 'backet' => 'webchalange' ]
        ]
    ],
    'di' => [
        'allowed_controllers' => [],
        'instance' => [
            'alias' => [],
            'preference' => [],
        ],
    ],
    'service_manager' => [
        'factories' => [
            'Application\\V1\\Rest\\Pages\\PagesResource' => 'Application\\V1\\Rest\\Pages\\PagesResourceFactory',
            'Application\\V1\\Rest\\Images\\ImagesResource' => 'Application\\V1\\Rest\\Images\\ImagesResourceFactory',
            'Zend\\Http\\Client' => function () {
                return new \Zend\Http\Client(null, [
                                                'adapter'      => 'Zend\Http\Client\Adapter\Socket',
                                                'ssltransport' => 'tls',
                                                'sslverifypeer' => false,
                                                'maxredirects' => 5,
                                                'timeout'      => 30,
                                                'useragent'    => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/45.0.2454.93 Safari/537.36'
                                             ]);
            }
        ],
    ],
    'zf-versioning' => [
        'uri' => [
            0 => 'application.rest.pages',
            1 => 'application.rest.images',
        ],
    ],
    'zf-configuration' => [
        'enable_short_array' => true,
    ],
    'zf-rest' => [
        'Application\\V1\\Rest\\Pages\\Controller' => [
            'listener' => 'Application\\V1\\Rest\\Pages\\PagesResource',
            'route_name' => 'application.rest.pages',
            'route_identifier_name' => 'page_id',
            'collection_name' => 'pages',
            'entity_http_methods' => [
                0 => 'GET',
            ],
            'collection_http_methods' => [
                0 => 'GET',
                1 => 'POST',
            ],
            'collection_query_whitelist' => [],
            'page_size' => 15,
            'page_size_param' => 'limit',
            'entity_class' => 'Application\\V1\\Entity\\Pages',
            'collection_class' => null,
            'service_name' => 'pages',
        ],
        'Application\\V1\\Rest\\Images\\Controller' => [
            'listener' => 'Application\\V1\\Rest\\Images\\ImagesResource',
            'route_name' => 'application.rest.images',
            'route_identifier_name' => 'image_id',
            'collection_name' => 'images',
            'entity_http_methods' => [],
            'collection_http_methods' => [
                0 => 'GET',
            ],
            'collection_query_whitelist' => [],
            'page_size' => '15',
            'page_size_param' => 'limit',
            'entity_class' => 'Application\\V1\\Entity\\Images',
            'collection_class' => null,
            'service_name' => 'images',
        ],
    ],
    'zf-content-negotiation' => [
        'controllers' => [
            'Application\\V1\\Rest\\Pages\\Controller' => 'HalJson',
            'Application\\V1\\Rest\\Images\\Controller' => 'HalJson',
        ],
        'accept_whitelist' => [
            'Application\\V1\\Rest\\Pages\\Controller' => [
                0 => 'application/vnd.application.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ],
            'Application\\V1\\Rest\\Images\\Controller' => [
                0 => 'application/vnd.application.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ],
        ],
        'content_type_whitelist' => [
            'Application\\V1\\Rest\\Pages\\Controller' => [
                0 => 'application/vnd.application.v1+json',
                1 => 'application/json',
            ],
            'Application\\V1\\Rest\\Images\\Controller' => [
                0 => 'application/vnd.application.v1+json',
                1 => 'application/json',
            ],
        ],
    ],
    'zf-hal' => [
        'metadata_map' => [
            'Application\\V1\\Entity\\Pages' => [
                'entity_identifier_name' => 'uuid',
                'route_name' => 'application.rest.pages',
                'route_identifier_name' => 'page_id',
                'hydrator' => 'DoctrineModule\\Stdlib\\Hydrator\\DoctrineObject',
            ],
            'Application\\V1\\Entity\\Images' => [
                'entity_identifier_name' => 'id',
                'route_name' => 'application.rest.images',
                'route_identifier_name' => 'image_id',
                'hydrator' => 'DoctrineModule\\Stdlib\\Hydrator\\DoctrineObject',
            ]
        ],
    ],
    'controllers' => [
        'factories' => [],
    ],
    'zf-rpc' => [],
    'zf-content-validation' => [
        'Application\\V1\\Rest\\Pages\\Controller' => [
            'input_filter' => 'Application\\V1\\Rest\\Pages\\Validator',
        ],
    ],
    'input_filter_specs' => [
        'Application\\V1\\Rest\\Pages\\Validator' => [
            0 => [
                'required' => true,
                'validators' => [
                    0 => [
                        'name' => 'Uri',
                        'options' => [
                            'allowRelative' => false,
                        ],
                    ],
                ],
                'name' => 'site_url',
                'description' => 'Url where you want worker to download all DOM images',
                'error_message' => 'Please insert correct url',
            ],
        ],
    ],
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'doctype' => 'HTML5',
        'not_found_template' => 'error/404',
        'exception_template' => 'error/exception',
        'template_map' => [
            'layout/application' => __DIR__ . '/../view/layout/layout.phtml',
            'error/404' => __DIR__ . '/../view/error/404.phtml',
            'error/exception' => __DIR__ . '/../view/error/exception.phtml',
            'error/403' => __DIR__ . '/../view/error/403.phtml',
        ],
        'template_path_stack' => [
            'application' => __DIR__ . '/../view',
        ],
        'strategies' => [
            0 => 'ViewJsonStrategy',
        ],
    ],
];
