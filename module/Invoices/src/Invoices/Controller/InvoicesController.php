<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Invoices\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\XmlRpc\Value\String;


class InvoicesController extends AbstractActionController
{
	
	//upload one receipt image
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
    
    //edit invoices data
    public function editInvoicesAction(){
    	
    	$response = $this->getResponse();
    	$request = $this->getRequest();
    	
    	if($request->isPost()){
    		$invoices = $this->getServiceLocator()->get('Invoices\Model\Invoices');
    		$data = $request->getPost()->toArray();
    		//var_dump($data);exit;
    		$invoices->exchangeArray($data);
    		$invoicesTable = $this->getServiceLocator()->get('Invoices\Model\InvoicesTable');
    		$nowData = $invoicesTable->saveInvoices($invoices);
    		if($nowData){
				$result = array(
						'errno' => 0,
						'msg'	=> 'ok',
						'data'	=>	$nowData,
				);    			
    		}else{
    			$result = array(
    					'errno' => 1,
    					'msg' => 'Update failed, please try again',
    			);
    		}
    		
    	}

    	return $response->setContent(json_encode($result));
    	
    }
    
    //get once invoice data
    public function getOneInvoicesDataAction(){
    	$response = $this->getResponse();
    	$invoices_id = (int)$this->params()->fromRoute('id',0);
    	$invoicesTable = $this->getServiceLocator()->get('Invoices\Model\InvoicesTable');
    	
    	$result = $invoicesTable->getInvoices($invoices_id);
    	if($result){
    		$jsonData = array(
    				'status'=>true,
    				'data'=>$result,
    		);
    	}else{
    		$jsonData = array('status'=>false);
    	}
    	
    	return $response->setContent(json_encode($jsonData));
    }
    
    //get all invoice data
    public function getInvoicesDataAction(){
    	$response = $this->getResponse();
    	$sm = $this->getServiceLocator();
    	$company_id = $sm->get('Global\UserInfo')->company_id;
    	$invoices = $sm->get('Invoices\Model\InvoicesTable');
    	$data = $invoices->fetchAll($company_id);
    	return $response->setContent(json_encode(array(
    			'status'=>true,
    			'data'=>$data,
    			)));
    }
    
    public function cropImageAction(){
    	header('Content-Type: image/jpeg');
    	$request = $this->getRequest();
    	$response = $this->getResponse();
    	if($request->isPost()){
    		$data = $request->getPost();
    		$id = $data->id;
    		$gateway = $this->getServiceLocator()->get('Invoices\Model\InvoicesTable');
    		$res = $gateway->getInvoices($id);
    		$filename=ROOT_PATH.$res->imagePath;
    		list($width, $height) = getimagesize($filename);
    		$file_path = pathinfo($filename);
    		$type = $file_path['extension'];
    		$new_width = $data->endX - $data->startX;
    		$new_height = $data->endY - $data->startY;

    		
    		$newImage  = imagecreatetruecolor($new_width, $new_height);
    		$im  = imagecreatefromjpeg( ROOT_PATH.$res->imagePath);
    		if(imagecopyresampled($newImage ,  $im ,0,0 ,$data->startX ,$data->startY, $width,$height,$data->endX, $data->endY)){
    			$randName = '/upload/corp_'.$id.'_'.time().'.'.$type;
    			$uri = ROOT_PATH.$randName;
    			//echo $uri;
    			if(imagejpeg ($newImage, $uri)){
    				$result = $gateway->editImagePath($id,array('imagePath'=>$randName));
					
    			}
    			imagedestroy($newImage);
    		}
    		
    		
    		
    	}
    	return $response->setContent(json_encode($result));
    }
    public function  phpInfoAction(){
    	echo phpinfo();
    	return $this->getResponse();
    }
}
