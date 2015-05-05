<?php
namespace Reports\Form;

use Zend\Form\Form;

class ReportsForm extends Form{

	public function __construct($name = null){
		parent::__construct();

		$this->add(array(
				'name'=>'categories_id',
				'type'=>'text',
		));

		$this->add(array(
				'name'=>'start',
				'type'=>'text'
		));

		$this->add(array(
				'name'=>'end',
				'type'=>'text'
		));

	}

}