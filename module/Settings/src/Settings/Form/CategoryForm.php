<?php
namespace Settings\Form;

use Zend\Form\Form;

class CategoryForm extends Form{
	
	public function __construct($name = null){
		
		parent::__construct();
		
		$this->add(array(
				'name'=>'description',
				'type'=>'text',
		));
		
		$this->add(array(
				'name'=>'company_id',
				'type'=>'text',
		));
	}
	
}