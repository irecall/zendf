<?php
namespace Settings\Service;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\Db\TableGateway\TableGateway;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\Db\Sql\Sql;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Adapter\Adapter;

class CompanyInfo implements ServiceLocatorAwareInterface{
	
	use ServiceLocatorAwareTrait;

	private $adapter;
	
	public function __construct(Adapter $adapter){
		$this->adapter = $adapter;
	}
	
	public function getCompanyCategorys($company_id){
		$sql = new Sql($this->adapter);
		$select = $sql->select();
		
		$select->from('categorys');
		$select->where(array('company_id'=>$company_id));
		$statement = $sql->prepareStatementForSqlObject($select);
		$result = $statement->execute();
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		return $resultSet->toArray();	
	}
	
	public function getCompanyCurrencys($company_id){
		$sql = new Sql($this->adapter);
		$select = $sql->select();		
		$select->from('currencyData');
		$select->where(array('company_id'=>$company_id));
		$statement = $sql->prepareStatementForSqlObject($select);
		$result = $statement->execute();
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		return $resultSet->toArray();
	}
	
	public function getCompanyUsers($company_id){
		$sql = new Sql($this->adapter);
		$select = $sql->select();
		$select->from('users');
		$select->where(array('company_id'=>$company_id));
		$statement = $sql->prepareStatementForSqlObject($select);
		$result = $statement->execute();
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		return $resultSet->toArray();
	}
	
	public function getCompanyInvoices($company_id){
		$sql = new Sql($this->adapter);
		$select = $sql->select();
		$select->from('invoices');
		$select->where(array('company_id'=>$company_id));
		$statement = $sql->prepareStatementForSqlObject($select);
		$result = $statement->execute();
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		return $resultSet->toArray();
	}
	
}