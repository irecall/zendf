<?php
namespace Settings\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Category implements InputFilterAwareInterface
{
	public $id;
	
	public $description;
	
	public $company_id;
	
	protected $inputFilter;                       // <-- Add this variable

	public function exchangeArray($data)
	{
		
		$this->id     = (isset($data['id']))     ? $data['id']     : null;
		$this->description = (isset($data['description'])) ? $data['description'] : null;
		$this->company_id  = (isset($data['company_id']))  ? $data['company_id']  : null;
		
	}

	// Add content to these methods:
	public function setInputFilter(InputFilterInterface $inputFilter)
	{
		throw new \Exception("Not used");
	}

	public function getInputFilter()
	{
		if (!$this->inputFilter) {
			$inputFilter = new InputFilter();



			$inputFilter->add(array(
					'name'     => 'description',
					'required' => true,
					'filters'  => array(
							array('name' => 'StripTags'),
							array('name' => 'StringTrim'),
					),
					'validators' => array(
							array(
									'name'    => 'StringLength',
									'options' => array(
											'encoding' => 'UTF-8',
											'min'      => 1,
											'max'      => 30,
									),
							),
					),
			));

			$this->inputFilter = $inputFilter;
		}

		return $this->inputFilter;
	}
	
}