<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonManages for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
//echo __DIR__ . '/../view/layout/layout.phtml';
return array(
    'router' => array(
        'routes' => array(
            
            'Manages' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/manages',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Manages\Controller',
                        'controller'    => 'Manages',
                        'action'        => 'index',
                    ),
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
    'controllers' => array(
        'invokables' => array(
            'Manages\Controller\Manages' => 'Manages\Controller\ManagesController'
        ),
    ),
    'view_manager' => array(
    	'template_map' => array(
    		'manages/layout'           => __DIR__ . '/../view/layout/layout.phtml',
 		),
 		//'layout'=>'manages/layout',
 		  
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
