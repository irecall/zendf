<?php
namespace Settings\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\DB\Sql\Sql;
use Zend\Db\ResultSet\ResultSet;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

class CategoryTable implements ServiceLocatorAwareInterface{
	use ServiceLocatorAwareTrait;
	protected $tableGateway;
	
	public $lastInsertID;
	
	
	public function __construct(TableGateway $tableGateway){
		$this->tableGateway = $tableGateway;
	}
	
	/**
	 * 
	 * @param number $id
	 * @return Ambigous <multitype:, multitype:NULL Ambigous <\ArrayObject, unknown> >
	 */
	
	public function fetchAll( $company_id = 0){		
		$sql = new Sql($this->tableGateway->getAdapter());
		$selete = $sql->select();
		$selete->from('categorys');
		if($company_id>0)$selete->where(array('company_id'=>$company_id));
		$statement = $sql->prepareStatementForSqlObject($selete);
		$result = $statement->execute();
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		return $resultSet->toArray();
	}
	
	// get one data
	public function getCategory($id){

		$id = (int) $id;

		$rowset = $this->tableGateway->select(array('id'=>$id));
		$row = $rowset->current();
		if(!$row){
			throw new \Exception('in table find id not exist ');
		}
		return $row;
	}
	
	//add and edit Category
	public function saveCategory(Category $category){
		$data = array(
				'description' => $category->description,
				'company_id'=>$category->company_id,
		);
		$id = (int) $category->id;
		if($id == 0){
				$this->tableGateway ->insert($data);
				return $this->getCategory($this->tableGateway->getLastInsertValue());		
		}else{
			if($this->getCategory($id)){
				$this->tableGateway->update($data,array('id'=>$id));
			}else{
				throw new \Exception('param error');
			}
		}
	}
	
	//delete one category data
	public function deleteCategory($id, $editId){
		
		if($this->tableGateway->delete(array('id'=>$id))){
			$userInfo = $this->serviceLocator->get('Global\UserInfo');
			$company_id = $userInfo->company_id;
			$sql = new Sql($this->tableGateway->getAdapter());
			$update= $sql->update();
			$update->table('invoices');
			$update->set(array('categories_id'=>$editId));
			$update->where(array('categories_id'=>$id));
			if($company_id>0)$update->where(array('company_id'=>$company_id));
			
			$statement = $sql->prepareStatementForSqlObject($update);
			$result = $statement->execute();
			
			return true;
		}
		return false;
	}
	
}