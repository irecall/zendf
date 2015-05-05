<?php
namespace Auth\Service;

use Zend\Authentication\Adapter\DbTable  as AuthAdapter;

use Zend\Authentication\AuthenticationService;

class Auth {
	
	protected $dbAdapter;
	
	protected $authAdapter;
	
	protected $authAuthenticationService;
	
	protected $licenseFields = 'username';
	
	protected $checkFields = 'password';
	
	protected $table = 'users';
	
	protected $username;
	
	protected $password;
	
	protected $storage;
	
	protected $authService;
	
	public function setAdapter(\Zend\Db\Adapter\Adapter $adapter){
						
			$this->dbAdapter = $adapter;		
			$this->getAuthAdapter();
			return $this;
			
	}
	
	
	
	public function setAuthInfo($licenseFields=null,$table=null,$checkFields = null){

		//set table 认证的数据表
		if(!is_null($table)){
			$this->table = $table;
		}
		//set license 认证字段
		if(!is_null($licenseFields)){
			$this->licenseFields = $licenseFields;
		}
		//set checkFields  校验字段
		if(!is_null($checkFields)){
			$this->checkFields = $checkFields;
		}
		
		return $this;
	}
	
	public function setUserInfo($username = null,$password = null){
		// set username
	
		if(!is_null($username)){
			$this->username =$username;
		}
		//set password
		if(!is_null($password)){
			$this->password = $password;
		}
		return $this;
	}

	//
	public function auth(){
		
		$authAdapter = $this->getAuthAdapter();
		
		$authAdapter
		
		->setTableName($this->table)             // 认证的数据表
		
		->setIdentityColumn($this->licenseFields)     // 认证字段
		
		->setCredentialColumn($this->checkFields);  // 校验字段
		
		$authAdapter
		
		->setIdentity($this->username)  // 认证值
		
		->setCredential($this->password);// 校验值
		//echo $this->table,'<br>',$this->checkFields,'<br>',$this->username,'<br>'.$this->password;exit;

		$auth = $this->getAuthService();
		
		$result = $auth->authenticate($authAdapter);
		
		if($result->isValid()){
			
			$auth->getStorage()->write($authAdapter->getResultRowObject());
			
			return true;
		
		}
		
		
		return false;
	}
		
	public function isAuth(){	
		return $this->getAuthService()->hasIdentity();
	}
	/**
	 * Get UserInfo
	 * @return Ambigous <\Zend\Authentication\mixed, NULL, \Zend\Authentication\Storage\mixed>
	 */
	public function getUserInfo(){

		return $this->getAuthService()->getIdentity();
	}
	/**
	 * user log out
	 */
	public function logout(){
		return $this->getAuthService()->clearIdentity();
	}
	
	public function setStorage($storage){
		$this->getAuthService()->setStorage($storage);
	}
	
	public function getAuthService(){
		if(!$this->authService){
			$this->authService = new AuthenticationService();
		}
		return $this->authService;
	}
	
	public function getAuthAdapter(){
	
		if(!$this->authAdapter){
			$this->authAdapter = new AuthAdapter($this->dbAdapter);
		}
	
		return $this->authAdapter;
	}
}