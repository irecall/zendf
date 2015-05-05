<?php

namespace Invoices\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\DB\Sql\Sql;
use Zend\Db\ResultSet\ResultSet;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\Db\Sql\Predicate\Expression;

class InvoicesTable implements ServiceLocatorAwareInterface{
	use ServiceLocatorAwareTrait;
	
	protected $tableGateway;
	
	protected $userInfo = null;

	public $lastInsertID;

	public function __construct(TableGateway $tableGateway){
		$this->tableGateway = $tableGateway;
	}

	//fetchAll data  

	public function fetchAll( $company_id = 0){

		$sql = new Sql($this->tableGateway->getAdapter());
		$selete = $sql->select();
		$selete->from('invoices');
		if($company_id>0)$selete->where(array('company_id'=>$company_id));
		$statement = $sql->prepareStatementForSqlObject($selete);
		$result = $statement->execute();
		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		$data = $resultSet->toArray();
		foreach ($data as &$v){
			$this->getConpanyInfo($v);			
		}
		return $data;

	}
	
	//get one data
	public function getInvoices($id,$isExcute = true){

		$userInfo = $this->getUserInfo();
		$id = (int) $id;
		
		$rowset = $this->tableGateway->select(array('id'=>$id));
		$row = $rowset->current();

		
		if(!$row){
			throw new \Exception('in table find id not exist ');
		}
		if(!$isExcute){
			return $row;
		}
		if($row->company_id == $userInfo->company_id || $userInfo->company_id == 0){		
			$this->getConpanyInfo($row);
		}else{
			$row = null;
		}		
		return $row;

	}
	
	//add receipt data and edit receipt data 
	public function saveInvoices(Invoices $Invoices){
		$this->getUserInfo();
		$data = array(
				'categories_id' => $Invoices->categories_id,
				'imagePath' => $Invoices->imagePath,
				'value' => $Invoices->value,
				'number' => $Invoices->number,
				'description' => $Invoices->description,
				'purchaseDate' => $Invoices->purchaseDate,
				'validatedDate' => $Invoices->validatedDate,
				'currencyData_id' => $Invoices->currencyData_id,
				'company_id' => $this->userInfo->company_id,
				'users_id' => $this->userInfo->id,
				'originalImagePath' => $Invoices->originalImagePath,
				'skip' => $Invoices->skip,				
		);

		$id = (int) $Invoices->id;
		if($id == 0){
			$this->tableGateway ->insert($data);
			return $this->getInvoices($this->tableGateway->getLastInsertValue());
		}else{
			if($this->getInvoices($id,false)){
				
				//过滤未null得字段，防止数据库中得值被替换。
				foreach($data as $k=>$v){
					if(empty($v)){
						unset($data[$k]);
					}		
				}
				
				//编辑过后 skip 值变为1
				$data['skip'] = 1;
				//编辑时间
				$data['validatedDate'] = (string)date('Y-m-d H:i:s',time());
				$data['purchaseDate'] = date('Y-m-d',strtotime($data['purchaseDate']));
				$this->getUserInfo();
				$sql = new Sql($this->tableGateway->getAdapter());
				$update = $sql->update();
				$update->table('invoices');
				$update->where(array('id'=>$id));
				
				
				if($this->userInfo->company_id > 0)
					$update->where(array('company_id'=>$this->userInfo->company_id ));
				$update->set($data);
				

				$statement = $sql->prepareStatementForSqlObject($update);
				$result = $statement->execute();
				if($result){
					return $this->getInvoices($id);
				}else{
					return false;
				}
				
			}else{
				throw new \Exception('param error');
			}
		}
	}

	public function editImagePath($id,$imagePath){
		$userInfo = $this->getUserInfo();
		$id = (int) $id;
		
		$row = $this->tableGateway->update($imagePath,array('id'=>$id));
				
		if(!$row){
			throw new \Exception('in table find id not exist ');
		}
		$res = $this->getInvoices($id);
		return $res;
	}
	public function deleteInvoices($id=0,$company_id=0){

// 		$sql = new Sql($this->tableGateway->getAdapter());

// 		$selete = $sql->delete();
// 		$selete->from('InvoicesData');
// 		$selete->where(array('id'=>$id));
// 		if($company_id>0)$selete->where(array('company_id'=>$company_id));
// 		$statement = $sql->prepareStatementForSqlObject($selete);
// 		$result = $statement->execute();
// 		return $result?true:false;

	}
	
	//create receipt reports
	public function reportsInvoices($data){
		$this->getUserInfo();
		$company_id = $this->userInfo->company_id;
		$sql = new Sql($this->tableGateway->getAdapter());
		$data['categories_id'] = implode(',',$data['categories_id']);
		$select = $sql->select(array('i'=>'invoices'));
		$select->columns(array('date'=>'purchaseDate','sum'=> New Expression('sum(i.value)')));
		
		$select->join(array('c'=>'categorys'), 
				'i.categories_id = c.id',
				array('category_name'=>'description'),
				$select::JOIN_LEFT);

		$select->where(array("i.purchaseDate >= '{$data['start']}'","i.purchaseDate <= '{$data['end']}'"));
		$select->where(array('i.categories_id in ('.$data['categories_id'].')'));
		$select->group('i.categories_id');
		$select->group('i.purchaseDate');
		if($company_id>0)$select->where(array('i.company_id'=>$company_id));
		$statement = $sql->prepareStatementForSqlObject($select);
		$result = $statement->execute();
		

		$resultSet = new ResultSet();
		$resultSet->initialize($result);
		
		$data = $resultSet->toArray();
		
		return $this->parseMorrisArray($data);
	}
	
	//get user info
	private function getUserInfo(){
		if($this->userInfo == null){
			$this->userInfo = $this->serviceLocator->get('Global\UserInfo');
		}
		return $this->userInfo;
	}
	
	//get receipt of company info
	private function getConpanyInfo(&$row){
		$companyTable = $this->serviceLocator->get('Settings\Service\CompanyInfoTable');
		if(is_object($row)){
			$row->categorys= $companyTable->getCompanyCategorys($row->company_id);
			$row->currencys= $companyTable->getCompanyCurrencys($row->company_id);
		}else if(is_array($row)){
			$row['categorys']= $companyTable->getCompanyCategorys($row['company_id']);
			$row['currencys']= $companyTable->getCompanyCurrencys($row['company_id']);
		}else{
			
		}
		
		
	}
	
	//parse morris array data
	private function parseMorrisArray($data){
		$tmp = array(
				'element'=>'graph',
				'data'=>array(),
				'xkey'=>'date',
				'ymax'=>'auto 1000',
				'ykeys'=>array(),
				'labels'=>array(),
		);
		foreach($data as $key=>$val){
			if(!in_array($val['category_name'], $tmp['ykeys'])){
				$tmp['ykeys'][] = $val['category_name'];
			}
			if(!in_array($val['category_name'], $tmp['labels'])){
				$tmp['labels'][] = $val['category_name'];
			}
			$bool = false;	
			foreach($tmp['data'] as $i=>$value){
		
				if(in_array($val['date'],$value)){
		
					$bool = true;
				}
			}
			
			if(!$bool){
				$tmp['data'][] = array('date'=>$val['date'],$val['category_name']=>$val['sum']);
			}else{
		
				$tmp['data'][$i][$val['category_name']]=$val['sum'];
			}
			unset($data[$key]);		
		}
		return $tmp;
	}
}