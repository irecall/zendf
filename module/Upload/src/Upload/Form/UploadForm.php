<?php
// File: UploadForm.php
namespace Upload\Form;

use Zend\InputFilter;
use Zend\Form\Element;
use Zend\Form\Form;

class UploadForm extends Form
{
	public function __construct($name = null, $options = array())
	{
		parent::__construct($name, $options);
		$this->addElements();
		$this->addInputFilter();
	}

	public function addElements()
	{
		// File Input
		$file = new Element\File('file');
		$file->setLabel('Avatar Image Upload')
		->setAttribute('id', 'file');   // That's it
		$this->add($file);
	}
	
	public function addInputFilter()
	{
		$inputFilter = new InputFilter\InputFilter();
	
		// File Input
		$fileInput = new InputFilter\FileInput('file');
		$fileInput->setRequired(true);
		

        $fileInput->getValidatorChain()
            ->attachByName('filesize',      array('max' => 1024000))
            ->attachByName('filemimetype',  array('mimeType' => 'image/png,image/jpeg'))
            ->attachByName('fileimagesize', array('maxWidth' => 1000, 'maxHeight' => 1000));
        
        $fileInput->getFilterChain()->attachByName(
            'filerenameupload',
            array(
                'target'    => ROOT_PATH.'/upload/avatar.png',
                'randomize' => true,
            )
        );

		
        $inputFilter->add($fileInput);
		
        $this->setInputFilter($inputFilter);
	}
	
}