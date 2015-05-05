<?php
namespace Settings\Controller;

use  Zend\Json\Json as json;
use Zend\Mvc\Controller\AbstractActionController;


class CompanyController extends AbstractActionController{
	
	//define list template tr
	
	protected $returnData = array(
			'company' => '<tr><td>{id}</td><td>{name}</td><td>1</td><td>
						<button type="button" class="btn btn-warning btn-circle" data-toggle="modal" data-target="#companyDeleteModal">
						<i class="fa fa-times"></i></button></td></tr>',
			
			'user' => '<tr><td>{id}</td><td>{fullName}</td><td>{fullName}</td><td>{email}</td><td>
					<button type="button" class="btn btn-warning btn-circle userDelete" userid="{id}" data-toggle="modal" data-target="#userDeleteModal">
					<i class="fa fa-times"></i></button></td></tr>',
	);
	
	/**
	 * 添加一个公司
	 * 并且默认创建一个账号，通过E-MAil告诉用 username：email address pssword XXX 尽快修改密码
	 *  
	 * @return \Zend\Stdlib\ResponseInterface
	 */
	
	public function addAction(){
		
		$request = $this->getRequest();
		$response = $this->getResponse();
		
		if($request->isPost()){
			
			$form = $this->getServiceLocator()->get('Settings\Form\CompanyForm');
			$company = $this->getServiceLocator()->get('Settings\Model\Company');
			$form->setInputFilter($company->getInputFilter());
			$postData = $request->getPost();
			$form->setData($postData);
			
			if($form->isValid()){
				
				$user = $this->getServiceLocator()->get('Settings\Model\UserTable');

				if($user->emailExists($postData['email'])){
					return $response->setContent(json_encode(array('errno'=>1,'message'=>'Email already exists, please change a try')));
				}
				$company->exchangeArray($form->getData());
				$gateway = $this->getServiceLocator()->get('Settings\Model\CompanyTable')->saveCompany($company);
				$Companydata = $gateway->getCompany($gateway->lastInsertID);

				if($gateway->lastInsertID){
					if($user = $this->addRandUserPass($gateway->lastInsertID)){
						$this->sendMail($user);
						$response->setContent(json::encode(array(
								'errno' => 0,
								'message'=>'ok',
								'data'=>array(
										'company'=>preg_replace(array('/{id}/','/{name}/'),array($Companydata->id,$Companydata->name), $this->returnData['company']),
										'user' => preg_replace(array('/{id}/','/{username}/','/{fullName}/','/{email}/'),array($user->id,$user->username?$user->username:$user->email,$user->fullName,$user->email),$this->returnData['user'])				
								),
						)));
					}
				}else{
					//Add failure
				}
				
			}else{
				$error = $form->getMessages();
				$response->setContent(json::encode(array(
						'errno' => -1,
						'message'=>$error['name']['stringLengthTooShort'],
				)));

			}
		}
		return $response;
	}
	
	/**
	 * Delete the company contain staff
	 * @return Json 
	 */
	
	public function deleteAction(){
		
		$id = (int)$this->params()->fromRoute('id',0);
		$response = $this->getResponse();
		
		if($id>0){
			$userTable = $this->getServiceLocator()->get("Settings\Model\UserTable");
			$userTable ->deleteCompanyUser($id);
			if($userTable->getCompanyIdUser($id) == 0){
				if($this->getServiceLocator()->get("Settings\Model\CompanyTable")->deleteCompany($id)){
					$result = array('errno'=>0,'message'=>'is ok');
				}else{
					$result = array('errno'=>2,'message'=>'delete company fail');
				}
			}else{
				$result = array('erron'=>3,'message'=>'The user is not completely deleted, please try again');
			}
		}else{
			$result = array('errno'=>1,'message'=>'param error');
		}
		
		return $response->setContent(json::encode($result));
		
	}
	
	/**
	 * create user rand password 
	 * 
	 * @param unknown $company_id
	 * @return 
	 */
	
	private function addRandUserPass($company_id){
		
		$tempData = $this->getRequest()->getPost();
		$randPwd = rand(100000,999999);
		$data = array(
				'fullName' => $tempData['firstName'].$tempData['lastName'],
				'email' => $tempData['email'],
				'password' => $randPwd,
				'company_id' => $company_id,
		);
		$user = $this->getServiceLocator()->get('Settings\Model\User');
		$user->exchangeArray($data);
		$user = $this->getServiceLocator()->get('Settings\Model\UserTable')->saveUser($user);
		$user->password = $randPwd;
		return $user;
		
	}
	
	/**
	 * 
	 * @param unknown $user
	 */
	private function sendMail($user){
		
		$mail = $this->getServiceLocator()->get('Settings\Service\MySendMail');
		
		$mail->setBody('hi '.$user->fullName.',<br>username:'.$user->email.'<br>'.'password:'.$user->password.'<br>You can login clogger directly<br><a href="http://fapiao.ted.imon.local">http://fapiao.ted.imon.local</a>')
			->subject('This is a mail inform you that your password')
			->addTo($user->email,$user->fullName)
			->setFrom('83008@163.com','clogger auto send')
			->send();
	}
	
}