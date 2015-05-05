<?php
namespace Settings\Form;

use Zend\Form\Form;

class CompanyForm extends Form{
	
	public function __construct($name = null){
		
		parent::__construct();
		
		$this->add(array(
				'name'=>'id',
				'type'=>'hidden'
		));
		$this->add(array(
				'name'=>'name',
				'type'=>'text',
		));
		$this->add(array(
				'name'=>'logoPath',
				'type'=>'text'
		));
		
	}
	
}