<?php
namespace Reports\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ReportsController extends AbstractActionController
{
	
    public function indexAction()
    {
        $sm = $this->getServiceLocator();
    	$user = $sm->get('Global\UserInfo');

    	$category = $sm -> get('Settings\Model\CategoryTable')->fetchAll($user->company_id);
    	$result = array(
    			'category'=>$category,
    	);
    	$view = new ViewModel($result);
    	//$view->setTerminal(true);
    	return $view;
    }
    
    // count reports
    public function searchAction(){
    	$request = $this->getRequest();
    	$response = $this->getResponse();
    	$sm = $this->getServiceLocator();
    	$form = $sm->get('Reports\Form\Reports');
    	if($request->isPost()){
    		$data = $request->getPost();
    		$form->setData($data);
    		if($form->isValid()){
    			$data = $form->getData();
    			$result = $sm->get('Invoices\Model\InvoicesTable')->reportsInvoices($data);
    			$response->setContent(json_encode($result));    			
    		}
    		
    	}
    	return $response;
    	
    }
    
}
