<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Settings;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

use Zend\Db\ResultSet\ResultSet;
use Settings\Model\UserTable;
use Settings\Model\User;
use Settings\Model\CompanyTable;
use Settings\Model\Company;
use Settings\Model\Category;
use Settings\Model\CategoryTable;
use Settings\Model\Currency;
use Settings\Model\CurrencyTable;

use Zend\Db\TableGateway\TableGateway;
use Zend\EventManager\SharedEventManager;
use Zend\EventManager\EventInterface;

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
				//autoloader
				'invokables'=>array(
						'Settings\Model\Category'=>'Settings\Model\Category',
						'Settings\Form\CategoryForm'=>'Settings\Form\CategoryForm',
						'Settings\Model\Company'=>'Settings\Model\Company',
						'Settings\Form\CompanyForm'=>'Settings\Form\CompanyForm',
						'Settings\Model\User'=>'Settings\Model\User',
						'Settings\Form\UserForm'=>'Settings\Form\UserForm',
						'Settings\Model\Currency'=>'Settings\Model\Currency',
						'Settings\Form\CurrencyForm'=>'Settings\Form\CurrencyForm',
						'Settings\Service\MySendMail'=>'Settings\Service\MySendMail'	
				),
				//factories
				'factories' => array(
						'Settings\Model\UserTable' =>  function($sm) {
							$tableGateway = $sm->get('UserTableGateway');
							$table = new UserTable($tableGateway);
							return $table;
						},
						'UserTableGateway' => function ($sm) {
							$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
							$resultSetPrototype = new ResultSet();
							$resultSetPrototype->setArrayObjectPrototype(new User());
							return new TableGateway('users', $dbAdapter, null, $resultSetPrototype);
						},
						'Settings\Model\CompanyTable' =>  function($sm) {
							$tableGateway = $sm->get('CompanyTableGateway');
							$table = new CompanyTable($tableGateway);
							return $table;
						},
						'CompanyTableGateway' => function ($sm) {
							$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
							$resultSetPrototype = new ResultSet();
							$resultSetPrototype->setArrayObjectPrototype(new Company());
							return new TableGateway('company', $dbAdapter, null, $resultSetPrototype);
						},
						'Settings\Model\CategoryTable' =>  function($sm) {
							$tableGateway = $sm->get('CategoryTableGateway');
							$table = new CategoryTable($tableGateway);
							return $table;
						},
						'CategoryTableGateway' => function ($sm) {
							$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
							$resultSetPrototype = new ResultSet();
							$resultSetPrototype->setArrayObjectPrototype(new Category());
							return new TableGateway('categorys', $dbAdapter, null, $resultSetPrototype);
						},
						'Settings\Model\CurrencyTable' =>  function($sm) {
							$tableGateway = $sm->get('CurrencyTableGateway');
							$table = new CurrencyTable($tableGateway);
							return $table;
						},
						'CurrencyTableGateway' => function ($sm) {
							$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
							$resultSetPrototype = new ResultSet();
							$resultSetPrototype->setArrayObjectPrototype(new Currency());
							return new TableGateway('currencyData', $dbAdapter, null, $resultSetPrototype);
						},
						'Settings\Service\CompanyInfoTable' =>  function($sm) {
							$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');						
							return new \Settings\Service\CompanyInfo($dbAdapter);
						},
						
				),
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
