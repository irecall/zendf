<?php
namespace Settings\Form;

use Zend\Form\Form;

class UserForm extends Form{

	public function __construct($name = null){
		parent::__construct();

		$this->add(array(
				'name'=>'username',
				'type'=>'text',
		));
		
		$this->add(array(
				'name'=>'password',
				'type'=>'text'
		));
		
		$this->add(array(
				'name'=>'fullName',
				'type'=>'text'
		));
		
		$this->add(array(
				'name'=>'company_id',
				'type'=>'text'
		));
		
	}
	
}