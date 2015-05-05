<?php
namespace Settings\Model;
use Zend\Db\TableGateway\TableGateway;
use Zend\DB\Sql\Sql;
use Zend\Db\ResultSet\ResultSet;

class CompanyTable{
	
	protected $tableGateway;
	
	public $lastInsertID;
	
	public function __construct(TableGateway $tableGateway){
		$this->tableGateway = $tableGateway;
	}

	public function fetchAll(){
		$sql = new Sql($this->tableGateway->getAdapter());
		$selete = $sql->select();
		$selete->from('company');
		$statement = $sql->prepareStatementForSqlObject($selete);
		$result = $statement->execute();
		$resultSet = new ResultSet();
		$resultSet->initialize($result);

		return $resultSet->toArray();
	}

	public function getCompany($id){

		$id = (int) $id;

		$rowset = $this->tableGateway->select(array('id'=>$id));
		$row = $rowset->current();
		if(!$row){
			throw new \Exception('in table find id not exist ');
		}
		return $row;
	}

	public function saveCompany(Company $company){
		$data = array(
				'name' => $company->name,
		);
		$id = (int) $company->id;
		if($id == 0){
				$this->tableGateway ->insert($data);
				$this->lastInsertID = $this->tableGateway->getLastInsertValue();
				return $this;		
		}else{
			if($this->getCompans($id)){
				$this->tableGateway->update($data,array('id'=>$id));
			}else{
				throw new \Exception('param error');
			}
		}
	}

	public function deleteCompany($id){
		return $this->tableGateway->delete(array('id'=>$id))?true:false;
	}
	
}