<?php
namespace Invoices\Model;

use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\Form\Annotation\InputFilter;

class Invoices implements InputFilterAwareInterface
{
	
	public $id;
	
	public $company_id;
	
	public $imagePath;
	
	public $value;
	
	public $number;
	
	public $description;
	
	public $purchaseDate;
	
	public $validatedDate;
	
	public $currencyData_id;
	
	public $categories_id;
	
	public $user_id;
	
	public $originalImagePath;
	
	public $skip;
	
	protected $inputFilter;                      
	
	//check post data
	public function exchangeArray($data)
	{	

		
		$this->id     = (isset($data['id']))     ? $data['id']     : null;
		$this->company_id  = (isset($data['company_id']))  ? $data['company_id']  : 0;
		$this->imagePath = (isset($data['imagePath'])) ? $data['imagePath'] : null;
		$this->value = (isset($data['value'])) ? $data['value'] : null;
		$this->number = (isset($data['number'])) ? $data['number'] : null;
		$this->description = (isset($data['description'])) ? $data['description']: null;	
		$this->purchaseDate = (isset($data['purchaseDate'])) ? $data['purchaseDate']: null;
		$this->validatedDate = (isset($data['validatedDate'])) ? $data['validatedDate'] : null;
		$this->currencyData_id = (isset($data['currencyData_id'])) ? $data['currencyData_id'] : 0;
		$this->categories_id = (isset($data['categories_id'])) ? $data['categories_id'] : 0;
		$this->user_id = (isset($data['user_id'])) ? $data['user_id'] : 0;
		$this->originalImagePath = (isset($data['originalImagePath'])) ? $data['originalImagePath'] : null;
		$this->skip = (isset($data['skip'])) ? $data['skip'] : 0;
	}


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