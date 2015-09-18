<?php
return [
    'doctrine' => [
            'connection' => [
                // default connection name
                    'orm_default' => [
                            'driverClass' => 'Doctrine\DBAL\Driver\PDOMySql\Driver',
                            'params' => [
                                    'host'     => 'localhost',
                                    'port'     => '3306',
                                    'user'     => 'root',
                                    'password' => 'spalax',
                                    'dbname'   => 'wc_api_server',
                                    'charset'  => 'utf8',
                                    'driverOptions' => [
                                            PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES utf8'
                                    ]
                            ],
                            'doctrineTypeMappings' => ['enum'=>'string']
                    ]
            ],
            'configuration' => [
                // Configuration for service `doctrine.configuration.orm_default` service
                    'orm_default' => [
                        // Generate proxies automatically (turn off for production]
                            'generate_proxies'  => true,
                            'types' => ['enum'=>'\Doctrine\DBAL\Types\StringType']
                    ]
            ],
    ]
];
