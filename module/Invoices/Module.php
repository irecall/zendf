<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Invoices;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

class Module
{

    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }
	
    public function getServiceConfig(){
    	return array(
    			'invokables'=>array(
    					'Invoices\Model\Invoices'=>'Invoices\Model\Invoices',		
    			),
    			'factories'=>array(
    					'Invoices\Model\InvoicesTable' =>  function($sm) {
							$tableGateway = $sm->get('InvoicesTableGateway');
							$table = new \Invoices\Model\InvoicesTable($tableGateway);
							return $table;
						},
						'InvoicesTableGateway' => function ($sm) {
							$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
							$resultSetPrototype = new ResultSet();
							$invoices = $sm->get('Invoices\Model\Invoices');
							$resultSetPrototype->setArrayObjectPrototype($invoices);
							return new TableGateway('invoices', $dbAdapter, null, $resultSetPrototype);
						},
    			)
    	);
    }
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
}
