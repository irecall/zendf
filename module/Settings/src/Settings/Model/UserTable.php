<?php
namespace Settings\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Sql;
use Zend\Debug\Debug;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

class UserTable implements ServiceLocatorAwareInterface{
	use ServiceLocatorAwareTrait;
	protected $tableGateway;
	
	public function __construct(TableGateway $tableGateway){
		$this->tableGateway = $tableGateway;
	}
	
	public function fetchAll($company_id = null){

		$company_id = (int) $company_id;
		$where = array();
		if($company_id>0){
			$where = array("company_id"=>$company_id);
		}
		return $this->tableGateway->select($where);
	}
	
	public function getUser($id){
		
		$id = (int) $id;

		$rowset = $this->tableGateway->select(array('id'=>$id));
		$row = $rowset->current();
		if(!$row){
			throw new \Exception('in table find id not exist ');
		}
		return $row;
	}
	
	// @param $companyId 
	// @return int countUser
	
	public function getCompanyIdUser($companyId){
		$adapter = $this->tableGateway->getAdapter();
		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->from('users')->where(array('company_id' => $companyId));
		$statement = $sql->prepareStatementForSqlObject($select);
		$row = $statement->execute()->count();
		return $row;
	}
	
	// @param user prototype
	
	public function saveUser(User $user){
	
		$data = array(
				'username' => $user->username,
				'password' => $user->password,
				'fullName' => $user->fullName,
				'email' => $user->email,
				'company_id' => $user->company_id,
		);
		$id = (int) $user->id;
		if($id == 0){
			$this->tableGateway->insert($data);
			return $this->getUser($this->tableGateway->getLastInsertValue()); // return insert dataAll
		}else{
			if($this->getUser($id)){
				return $this->tableGateway->update($data,array('id'=>$id));
			}else{
				throw new \Exception('param error');
			}
		}
	}
	
	/**
	 * 
	 * @param int $id  userid
	 *
	 */
	
	public function deleteUser($id){
		return $this->tableGateway->delete(array('id'=>$id));
	}
	
	/**	
	 * 删除某个公司下的所有用户
	 * @param int $company_id
	 */
	
	public function deleteCompanyUser($company_id){
		try{
			return $this->tableGateway->delete(array('company_id'=>$company_id));
		}catch(\Exception $s){
			return 0;
		}
		
	}
	
	public function emailExists($email){
		return $this->tableGateway->select(array('email'=>$email))->current();
	}
	
}