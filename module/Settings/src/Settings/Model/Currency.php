<?php
namespace Settings\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Currency implements InputFilterAwareInterface
{
	
	public $id;
	
	public $company_id;
	
	public $currencyCode;
	
	public $contry;
	
	public $applicableYear;
	
	public $USDvalue;
	
	protected $inputFilter;                       // <-- Add this variable

	public function exchangeArray($data)
	{
		
		$this->id     = (isset($data['id']))     ? $data['id']     : null;
		$this->company_id  = (isset($data['company_id']))  ? $data['company_id']  : null;
		$this->currencyCode = (isset($data['currencyCode'])) ? $data['currencyCode'] : null;
		$this->contry = (isset($data['contry'])) ? $data['contry'] : null;
		$this->applicableYear = (isset($data['applicableYear'])) ? $data['applicableYear'] : null;
		$this->USDvalue = (isset($data['USDvalue'])) ? $data['USDvalue'] : null;
		
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
					'name'     => 'currencyCode',
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