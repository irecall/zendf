<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonSettings for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

return array(
	'controllers' => array(
			'invokables' => array(
					'Settings\Controller\Settings' => 'Settings\Controller\SettingsController',
					'Settings\Controller\User' => 'Settings\Controller\UserController',
					'Settings\Controller\Company' => 'Settings\Controller\CompanyController',
					'Settings\Controller\Category' =>'Settings\Controller\CategoryController',
					'Settings\Controller\Currency' => 'Settings\Controller\CurrencyController'
			),
	),
    'router' => array(
        'routes' => array(
        		'User' => array(
        				'type' => 'segment',
        				'options' => array(
        						'route' => '/settings/user[/][:action][/:id]',
        						'constraints' => array(
        								'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
        								'id' => '[0-9]+',
        						),
        						'defaults' => array(
        								'controller' => 'Settings\Controller\User',
        								'action' => 'index'
        						)
        				),
        				'may_terminate' => true,
        		),
        		'Company' => array(
        				'type' => 'segment',
        				'options' => array(
        						'route' => '/settings/company[/][:action][/:id]',
        						'constraints' => array(
        								'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
        								'id' => '[0-9]+',
        						),
        						'defaults' => array(
        								'controller' => 'Settings\Controller\Company',
        								'action' => 'index'
        						)
        				),
        				'may_terminate' => true,
        		),
        		'Category' => array(
        				'type' => 'segment',
        				'options' => array(
        						'route' => '/settings/category[/][:action][/:id]',
        						'constraints' => array(
        								'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
        								'id' => '[0-9]+',
        						),
        						'defaults' => array(
        								'controller' => 'Settings\Controller\Category',
        								'action' => 'index'
        						)
        				),
        				'may_terminate' => true,
        		),
        		'Currency' => array(
        				'type' => 'segment',
        				'options' => array(
        						'route' => '/settings/currency[/][:action][/:id]',
        						'constraints' => array(
        								'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
        								'id' => '[0-9]+',
        						),
        						'defaults' => array(
        								'controller' => 'Settings\Controller\Currency',
        								'action' => 'index'
        						)
        				),
        				'may_terminate' => true,
        		),
            'Settings' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/settings[/][:action][/:id]',
                    'constraints' => array(
                    		'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    		'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                    		'controller' => 'Settings\Controller\Settings',
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
        'template_map'=> array(
        	'settings/layout'           => __DIR__ . '/../view/layout/layout.phtml',
    	),
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
