<?php

/**
 * ����
 * @author computer1
 *
 */

namespace Settings\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Auth\Service\Auth;
use Zend\Mvc\MvcEvent;

class SettingsBaseController extends AbstractActionController{
	
	static protected $dbModel = array(); //save create model
	
	protected $auth;						
	
	protected $userInfo;
	
	protected function checkAuth(){
		
		//new auth
		$adapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
		$this->auth = new Auth($adapter);
		
		// is login
		if(!$this->auth-> isAuth()){
			return $this->redirect()->toRoute('Login');
		}
		// settings userInfo
		$this->userInfo = $this->auth->getUserInfo();
		
	}
	
	public function onDispatch(MvcEvent $e){
		
		//checkAuth init
		$this->checkAuth();
		parent::onDispatch($e);
		
	}
	
	//��ȡtableGateway ����
	//@param $name Model.php�й����������ӵĺ�������
	protected function getTable($name){
		
		if(!array_key_exists($name,self::$dbModel)){
			$sm = $this->getServiceLocator();
			self::$dbModel[$name] = $sm->get($name);
		}
		return self::$dbModel[$name];
		
	}
	
	
}