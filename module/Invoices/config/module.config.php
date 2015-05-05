<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

return array(
    'router' => array(
        'routes' => array(
            'Invoices' => array(
        				'type' => 'segment',
        				'options' => array(
        						'route' => '/invoices[/][:action][/:id]',
        						'constraints' => array(
        								'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
        								'id' => '[0-9]+',
        						),
        						'defaults' => array(
        								'controller' => 'Invoices\Controller\Invoices',
        								'action' => 'index'
        						)
        				),
        	),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Invoices\Controller\Invoices' => 'Invoices\Controller\InvoicesController'
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
);
