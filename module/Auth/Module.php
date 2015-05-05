<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Auth;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\EventManager\EventInterface;

class Module
{
	
	private $auth;
	public function init(\Zend\ModuleManager\ModuleManager $moduleManager)
	{
		$sharedEvents = $moduleManager
		->getEventManager()->getSharedManager();
	
	
		$sharedEvents->attach('Zend\Mvc\Application',MvcEvent::EVENT_ROUTE,[$this,'checkAuth']);
	}
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        $config =$this->getConfig();
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
    public function getServiceConfig(){
    	return array(
    			//配置一个工厂
    			'factories' => array(
    					'Auth\Service\AuthManager' =>  function($sm) {
    						//var_dump($sm);
    						$adapter = $sm->get('Zend\Db\Adapter\Adapter');    						   						
    						return $sm->get("Auth\Service\Auth")->setAdapter($adapter);
    					},
    			),
    			'invokables' => array(
    					'Auth\Service\Auth' => 	'Auth\Service\Auth',
    					'Auth\Service\MyAuthStorage' =>'Auth\Service\MyAuthStorage',
    			),
    	);
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
    
    
    public function checkAuth(EventInterface $e){
    	 
    	if($e->getRouteMatch()->getMatchedRouteName() == 'Login')return;		//只允许Login路由名称访问，不进行权限检查、
    	 
    	$sm = $e->getApplication()->getServiceManager();						//从Mvc下得Application对象中获得ServiceManager对象
    	 
    	$auth = $sm->get('Auth\Service\AuthManager');							//获取到注册到service服务中自己封装的auth服务
    	 
    	if(!$auth-> isAuth()){
    		$response = $e->getResponse();
    		$router = $e->getRouter();
    		$url = $router->assemble(array(),array('name'=>'Login'));
    		$response->getHeaders()->addHeaderLine('Location',$url);
    		$response->setStatusCode(302);
    		$response->sendHeaders();
    		exit;
    	}
    
    	// settings userInfo
    	 
    	$sm->setService('Global\UserInfo',$auth->getUserInfo());		//把用户信息对象 注册到ServiceManager中
       
    }
}
