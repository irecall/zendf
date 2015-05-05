<?php
namespace Settings\Model;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilter;
use Zend\InputFIlter\InputFilterInterface;


class User implements InputFilterAwareInterface{
	
	public $id;
	
	public $username;
	
	public $password;
	
	public $fullName;
	
	public $email;
	
	public $company_id;
	
	public function exchangeArray($data){
		$this->id = isset($data['id']) ? $data['id'] : null;
		$this->username = isset($data['username']) ? $data['username']: null;
		$this->password = isset($data['password']) ? md5($data['password']): null;
		$this->fullName = isset($data['fullName']) ? $data['fullName'] : null;
		$this->email = isset($data['email']) ? $data['email']: null;
		$this->company_id = isset($data['company_id']) ? $data['company_id'] : null;
	}
	
	public function setInputFilter(InputFilterInterface $inputFilter){
		
		throw new \Exception(' not used');
		
	}
	
	public function getInputFilter(){
		
	}
}