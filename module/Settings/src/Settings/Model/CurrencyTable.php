<?php
namespace Settings\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\DB\Sql\Sql;
use Zend\Db\ResultSet\ResultSet;

class CurrencyTable{
	
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
		$selete->from('currencyData');
		if($company_id>0)$selete->where(array('company_id'=>$company_id));
		$statement = $sql->prepareStatementForSqlObject($selete);
		$result = $statement->execute();
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		return $resultSet->toArray();
		
	}

	public function getCurrency($id){

		$id = (int) $id;

		$rowset = $this->tableGateway->select(array('id'=>$id));
		$row = $rowset->current();
		if(!$row){
			throw new \Exception('in table find id not exist ');
		}
		return $row;
		
	}
	
	public function saveCurrency(Currency $currency){
		
		$data = array(
				'currencyCode' => $currency->currencyCode,
				'company_id'=>$currency->company_id,
				'contry'=>$currency->contry,
				'applicableYear'=>$currency->applicableYear,
				'USDvalue'=>$currency->USDvalue,
		);
		$id = (int) $currency->id;
		if($id == 0){
				$this->tableGateway ->insert($data);
				return $this->getCurrency($this->tableGateway->getLastInsertValue());
		}else{
			if($this->getCurrency($id)){
				$sql = new Sql($this->tableGateway->getAdapter());
				$update = $sql->update();
				$update->table('currencyData');
				$update->where(array('id'=>$id));
				$update->set($data);
				if($data['company_id']>0)$update->where(array('company_id'=>$data['company_id']));
				//var_dump($update->getSqlString());exit;
				$statement = $sql->prepareStatementForSqlObject($update);
				$result = $statement->execute();				
				return $this->getCurrency($id);
			}else{
				throw new \Exception('param error');
			}
		}
	}

	public function deleteCurrency($id=0,$company_id=0){
		
		$sql = new Sql($this->tableGateway->getAdapter());
		
		$selete = $sql->delete();
		$selete->from('currencyData');
		$selete->where(array('id'=>$id));
		if($company_id>0)$selete->where(array('company_id'=>$company_id));
		$statement = $sql->prepareStatementForSqlObject($selete);
		$result = $statement->execute();
		return $result?true:false;
		
	}
}