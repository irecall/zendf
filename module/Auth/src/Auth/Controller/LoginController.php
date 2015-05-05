<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Auth\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\EventManager\EventManagerInterface;

//use Zend\Json\Json;


class LoginController extends AbstractActionController
{
	protected $auth;
	protected $storage;
	
	public function setEventManager(EventManagerInterface $events)     
	{         
		parent::setEventManager($events);      
		$this->init();     
	}
	
	public function init(){
		$this->auth = $this->getServiceLocator()->get('Auth\Service\AuthManager');
		
	}
	
	//login view
    public function indexAction()
    {

    	if($this->isLogin()){
    		return $this->redirect()->toRoute('application');
     	}
    	
    	$view = new ViewModel();
    	$view->setTerminal(true);
        return $view;
    }
    
    /**
     * login
     * @return unknown
     */
    
    public function authenticateAction(){
    	$request = $this->getRequest();
    	$response = $this->getResponse();
    	
    	if($request->isPost()){
    		//$this->auth->setStorage($this->getSessionStorage());
    		$tmpData = $request->getPost();
			$bool = $this->auth->
					setAuthInfo($this->is_email($tmpData->username))
					->setUserInfo($tmpData->username,md5($tmpData->password))
					->auth();
			
			if($bool){
				$this->getSessionStorage()->setRememberMe(1);
				$this->auth->setStorage($this->getSessionStorage());
				return $this->redirect()->toRoute('application');
			}else{
				return $this->redirect()->toRoute('Login');
 			}
    	}
    	return $response;
    }

    /**
     * logout
     */
    
    public function logoutAction(){	
	     	if($this->isLogin()){
	     		$this->auth->logout();
	     		return $this->redirect()->toRoute('Login');
	     	}
	     	return false;
    }
     
    // if login
	public function isLogin(){

 		return $this->auth->isAuth();
	}
	
	//if email   
    private function is_email($param){
    	return preg_match('/^([0-9A-Za-z\\-_\\.]+)@([0-9a-z]+\\.[a-z]{2,3}(\\.[a-z]{2})?)$/i',$param)?'email':'username';
    }
    
    private function getSessionStorage(){
    	if (! $this->storage) {
    		$this->storage = $this->getServiceLocator()
    		->get('Auth\Service\MyAuthStorage');
    	}
    	return $this->storage;
    }
    
    
}
