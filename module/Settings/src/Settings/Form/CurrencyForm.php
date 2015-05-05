<?php
namespace Settings\Form;

use Zend\Form\Form;

class CurrencyForm extends Form{
	
	public function __construct($name = null){
		
		parent::__construct();
		
		$this->add(array(
				'name'=>'id',
				'type'=>'hidden'
		));
		
		$this->add(array(
				'name'=>'currencyCode',
				'type'=>'text',
		));
		
		$this->add(array(
				'name'=>'contry',
				'type'=>'text',
		));
		
		$this->add(array(
				'name'=>'applicableYear',
				'type'=>'text',
		));
		
		$this->add(array(
				'name'=>'USDvalue',
				'type'=>'text'
		));
		
		$this->add(array(
				'name'=>'company_id',
				'type'=>'text'
		));
		
	}
	
}