<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */

return array(
    'db' => array(
        'driver' => 'Pdo',
        'dsn' => 'mysql:dbname=clogger;host=192.168.1.200',
        'driver_options' => array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'Zend\Db\Adapter\Adapter'
            => 'Zend\Db\Adapter\AdapterServiceFactory',
        ),
    ),
    'session' => array(
        'cookie_lifetime' => 60 * 60, // 60 min
        'remember_me_seconds' => 60 * 60, // 60 min
        'gc_maxlifetime' => 60 * 60,
        'use_cookies' => true,
    ),
    'cache_adapter' => array(
        'adapter' => array(
            'name' => 'redis',
            'options' => array(
                'server' => array(
                    'host' => '127.0.0.1',
                    'port' => 6379,
                ),
            ),
        )
    )
);

