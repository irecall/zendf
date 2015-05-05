<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

return array(
	'controllers' => array(
			'invokables' => array(
					
					'Auth\Controller\Auth' => 'Auth\Controller\AuthController',
					'Auth\Controller\Login' => 'Auth\Controller\LoginController',
			),
	),
    'router' => array(
        'routes' => array(
        		'Auth' => array(
        				'type'    => 'segment',
        				'options' => array(
        						'route'    => '/auth[/][:action][/:id]',
        						'constraints' => array(
        								'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
        								'id' => '[0-9]+',
        						),
        						'defaults' => array(
        								'controller' => 'Auth\Controller\Auth',
        								'action' => 'index'
        						)
        				),
        				//'may_terminate' => true, //如果auth 路由匹配成功将立即停止向下继续匹配
        		),
        		
        		'Login' => array(
        				'type'    => 'segment',
        				'options' => array(
        						'route'    => '/auth/login[/][:action][/:id]',
        						'constraints' => array(
        								'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
        								'id' => '[0-9]+',
        						),
        						'defaults' => array(
        								'controller' => 'Auth\Controller\Login',
        								'action' => 'index'
        						)
        				),
        		),
        ),
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'aliases' => array(
            'translator' => 'MvcTranslator',
        ),
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),

    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    // Placeholder for console routes
    'console' => array(
        'router' => array(
            'routes' => array(
            ),
        ),
    ),
    
    
);
