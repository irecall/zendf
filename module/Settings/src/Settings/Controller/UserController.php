<?php
namespace Settings\Controller;


use  \Zend\Json\Json as json;
use Zend\Mvc\Controller\AbstractActionController;

class UserController extends AbstractActionController{
	
	protected $userTable;
	
	protected $template = array(
								"user"=>'<tr><td>{id}</td><td>{fullName}</td><td>{fullName}</td><td>{username}</td><td>
										<button type="button" class="btn btn-warning btn-circle userDelete" userid="{id}" data-toggle="modal" data-target="#userDeleteModal">
										<i class="fa fa-times"></i></button></td></tr>'
							);
		
	/**
	 * Add User Info
	 * @throws \Exception
	 * @return \Zend\Stdlib\ResponseInterface
	 */
	
	public function addAction(){
		$response = $this->getResponse();
		$request = $this->getRequest();
		if($request->isPost()){
			$user = $this->getServiceLocator()->get('Settings\Model\User');
			$userForm = $this->getServiceLocator()->get('Settings\Form\UserForm');
// 			$userForm->setInputFilter($user->getInputFilter());
			$userForm->setData($request->getPost());
			if($userForm->isValid()){

				try{
					$data = $userForm->getData();
					$userInfo = $this->getServiceLocator()->get('Global\UserInfo');				
					$data['company_id'] = empty($data['company_id'])?$userInfo->company_id:$data['company_id'];
					$user->exchangeArray($data);
					$db = $this->getServiceLocator()->get('Settings\Model\UserTable');
					if($data = $db->saveUser($user)){
						$response->setContent(json::encode(array(
								'errno' => 0,
								'message'=>'ok',
								'data'=>array(
										'user' => preg_replace(array('/{id}/','/{fullName}/','/{username}/'),array($data->id,$data->fullName,$data->username?$data->username:$data->email,),$this->template['user'])				
								),
						)));
					};
				}catch (\Exception $e){
					throw new \Exception("abnormal");
				}		
			}else{
				$response->setContent(json::encode('param error'));
			}
		}
		return $response;
	}
	
	/**
	 * Delete One User Info
	 * @return unknown
	 */
	
	public function deleteAction(){
		$response = $this->getResponse();
		
		try{
			$id = (int)$this->params()->fromRoute('id',0);
			$gateway = $this->getServiceLocator()->get('Settings\Model\UserTable');
			$gateway->deleteUser($id);	
			$response->setContent(json::encode(array(
					'errno' => 0,
					'message'=>'ok',
			)));
			return $response;
		}catch(\Exception $m){
			$response->setContent(json::encode(array(
					'errno' => 1,
					'message' => 'delete failed', //É¾³ıÊ§°Ü·µ»ØÏûÏ¢
			)));
			return $response;
		}
	}	
	
}