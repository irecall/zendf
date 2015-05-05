<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Upload\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\XmlRpc\Value\String;


class FileUploadController extends AbstractActionController
{
	
    public function fileUploadAction()
    {
    	$form = $this->getServiceLocator()->get('Upload\Form\UploadForm');
		$response = $this->getResponse();
	    $request = $this->getRequest();
	    if ($request->isPost()) {
	        // Make certain to merge the files info!
	        $post = array_merge_recursive(
	            $request->getPost()->toArray(),
	            $request->getFiles()->toArray()
	        );
	        
	        $form->setData($post);
	      	

	        if ($form->isValid()) {
	        	
	        	$data = $form->getData();
				$path = '/upload/'.basename($data['file']['tmp_name']);
	        	$invoices = $this->getServiceLocator()->get('Invoices\Model\Invoices');
	        	
	        	$imageData = array(
	        		'imagePath'=>$path,
	        		'originalImagePath'=>$path	        			
	        	);
	        	$invoices->exchangeArray($imageData);
	        	
	        	$invoicesTableGateway = $this->getServiceLocator()->get('Invoices\Model\InvoicesTable');
	        	
	        	$invoicesTableGateway->saveInvoices($invoices);
	        	
	            // Form is valid, save the form!
                $result = array(
                    'status'   => true,
                );
	        }else{
			    	$result = array(
			    			'status'     => false,
			    			'formErrors' => $form->getMessages(),
			    	);
			    }
	    }
    	return $response->setContent(json_encode($result));
    }
    
}
