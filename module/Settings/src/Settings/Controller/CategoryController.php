<?php
namespace Settings\Controller;

use  \Zend\Json\Json as json;
use Zend\Mvc\Controller\AbstractActionController;

class CategoryController extends AbstractActionController{
	
	protected $template = '<tr><td>{id}</td><td>{description}</td><td>
			<button type="button" class="btn btn-warning btn-circle" data-toggle="modal" data-target="#categoryDeleteModal">
			<i class="fa fa-times"></i></button></td></tr>';
	
	//Add Categories One Data
	public function addAction(){
		
		$request = $this->getRequest();
		$response = $this->getResponse();
		
		if($request->isPost()){
			$form = $this->getServiceLocator()->get('Settings\Form\CategoryForm');	
			$category = $this->getServiceLocator()->get('Settings\Model\Category');
			$form->setInputFilter($category->getInputFilter());
			$form->setData($request->getPost());
			
			if($form->isValid()){
				$data = $form->getData();
				$userInfo = $this->getServiceLocator()->get('Global\UserInfo');
				$data['company_id'] = empty($data['company_id'])?$userInfo->company_id:$data['company_id'];
				$category->exchangeArray($data);		
				$gateway = $this->getServiceLocator()->get('Settings\Model\CategoryTable');
				$res = $gateway->saveCategory($category);
				if($res){
					$response->setContent(json::encode(array(
							'errno' => 0,
							'message'=>'ok',
							'data'=>array('category'=>preg_replace(array("/{id}/","/{description}/"),
									array($res->id,$res->description),
									$this->template)),
					)));
				}else{
					$response->setContent(json::encode(array('errno'=>1,'msg'=>'abnormal error')));
				}
				
			}
			
		}
		
		return $response;
		
	}
	
	//Delete Category , invoices replace new category_id
	public function deleteAction(){
		
		$id = (int)$this->params()->fromRoute('id',0);	
		$response = $this->getResponse();
		
		if($id>0){
			$categoryTable = $this->getServiceLocator()->get('Settings\Model\CategoryTable');
			$data = $this->getRequest()->getPost();			
			$replaceId = (int) $data['udpId'];
			if($categoryTable->deleteCategory($id,$replaceId)){
				$result = array('errno'=>0,'message'=>'is ok');
			}else{
				$result = array('errno'=>2,'message'=>'delete company fail');
			}
		}else{
			$result = array('errno'=>1,'message'=>'param error');
		}
		
		return $response->setContent(json::encode($result));
	}
	
}
