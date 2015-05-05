<?php
namespace Settings\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Company implements InputFilterAwareInterface
{
	public $id;
	
	public $name;
	
	public $logoPath;
	
	protected $inputFilter;                       

	public function exchangeArray($data)
	{
		
		$this->id     = (isset($data['id']))     ? $data['id']     : null;
		$this->name = (isset($data['name'])) ? $data['name'] : null;
		$this->logoPath  = (isset($data['logoPath']))  ? $data['logoPath']  : null;
		
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
					'name'     => 'name',
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