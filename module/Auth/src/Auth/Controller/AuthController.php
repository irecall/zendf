<?php
namespace Auth\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class AuthController extends AbstractActionController{
	
	public function indexAction(){
		return $this->redirect()->toRoute('Login');
	}
	
}