<?php
namespace Settings\Controller;

use Zend\Json\Json as json;
use Zend\Mvc\Controller\AbstractActionController;

class CurrencyController extends AbstractActionController{
	
	private $template = '<tr>
                             <td>{id}</td>
                             <td>{currencyCode}</td>
                             <td>{contry}</td>
                             <td>{USDvalue}</td>
                             <td>{applicableYear}</td>
                             <td>
                                 <button type="button" class="btn btn-primary btn-circle edit-currency" data-toggle="modal" data-target="#currencyEditModal"><i class="fa fa-list"></i></button>
                                 <button type="button" class="btn btn-warning btn-circle delete-currency" data-toggle="modal" data-target="#currencyDeleteModal"><i class="fa fa-times"></i></button>
							</td>
                         </tr>';
	
	/**
	 * Add Currency
	 * @return \Zend\Stdlib\ResponseInterface
	 */
	
	public function addAction(){
		
		$request = $this->getRequest();
		$response = $this->getResponse();
		if($request->isPost()){
			$form = $this->getServiceLocator()->get('Settings\Form\CurrencyForm');
			$currency = $this->getServiceLocator()->get('Settings\Model\Currency');
			$form->setInputFilter($currency->getInputFilter());
			$data = $request->getPost();	
			$company_id = $this->getServiceLocator()->get('Global\UserInfo')->company_id;
			$data['company_id'] = empty($data['company_id'])?$company_id:$data['company_id'];
			$form->setData($data);
			if($form->isValid()){
				$currency->exchangeArray($form->getData());
				$result = $this->getServiceLocator()->get("Settings\Model\CurrencyTable")->saveCurrency($currency);
				if($result){
					$response->setContent(json::encode(array(
							'errno' => 0,
							'message'=>'ok',
							'data'=>array('html'=>preg_replace(
										array('/{id}/','/{currencyCode}/','/{contry}/','/{applicableYear}/','/{USDvalue}/'),
										array($result->id,$result->currencyCode,$result->contry,$result->applicableYear,$result->USDvalue),
										$this->template
										)
									),
					)));
				}else{
					$response->setContent(json::encode(array(
							'errno' => 1,
							'message'=>'error param',
					)));
				}			
			} 
		}
		return $response;
	}
	
	public function editAction(){
	
		$request = $this->getRequest();
		$response = $this->getResponse();
		$id = (int)$this->params()->fromRoute('id',0);
		if($request->isPost() && $id>0){
			$form = $this->getServiceLocator()->get('Settings\Form\CurrencyForm');
			$currency = $this->getServiceLocator()->get('Settings\Model\Currency');
			$form->setInputFilter($currency->getInputFilter());
			$data = $request->getPost();
			$data['company_id'] = $this->getServiceLocator()->get('Global\UserInfo')->company_id;
			$data['id'] = $id;
			$form->setData($data);
			if($form->isValid()){
				$currency->exchangeArray($form->getData());
				$result = $this->getServiceLocator()->get("Settings\Model\CurrencyTable")->saveCurrency($currency);
				if($result){
					$response->setContent(json::encode(array(
							'errno' => 0,
							'message'=>'ok',
							'data'=>array('html'=>preg_replace(
									array('/{id}/','/{currencyCode}/','/{contry}/','/{applicableYear}/','/{USDvalue}/'),
									array($result->id,$result->currencyCode,$result->contry,$result->applicableYear,$result->USDvalue),
									$this->template
							)
							),
					)));
				}else{
					$response->setContent(json::encode(array(
							'errno' => 1,
							'message'=>'error param',
					)));
				}
			}
		}
		return $response;
	}
	
	/**
	 * delete Currency
	 * @param int GET id
	 * @return \Zend\Stdlib\mixed
	 */
	
	public function deleteAction(){
		
		$id = (int)$this->params()->fromRoute('id',0);	
		
		$response = $this->getResponse();
		$company_id = $this->getServiceLocator()->get('Global\UserInfo')->company_id;
		if($id>0){
			if($this->getServiceLocator()->get("Settings\Model\CurrencyTable")->deleteCurrency($id,$company_id)){
				$result = array('errno'=>0,'message'=>'is ok');
			}else{
				$result = array('errno'=>2,'message'=>'delete currency fail');
			}
		}else{
			$result = array('errno'=>1,'message'=>'param error');
		}
		return $response->setContent(json::encode($result));
		
	}
	
}