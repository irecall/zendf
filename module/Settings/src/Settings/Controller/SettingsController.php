<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Settings\Controller;

use Zend\View\Model\ViewModel;
use Zend\Mvc\Controller\AbstractActionController;


class SettingsController extends AbstractActionController

{
	
    public function indexAction()
    
    { 	
    	
    	$userInfo = $this->getServiceLocator()->get('Global\UserInfo');
		$company_id = $userInfo->company_id;
		$sm = $this->getServiceLocator();
    	$companyies = $sm->get('Settings\Model\CompanyTable')->fetchAll();
    	$category = $sm->get('Settings\Model\CategoryTable')->fetchAll($company_id);
    	$currency = $sm->get('Settings\Model\CurrencyTable')->fetchAll($company_id);
    	$user = $sm->get('Settings\Model\UserTable')->fetchAll($company_id);
    	foreach($companyies as &$value){
    		$value['companyCount'] =$this->getServiceLocator()->
    										get('Settings\Model\UserTable')->
    										getCompanyIdUser($value['id']);
    	}
    	
    	$view = new ViewModel(array(
    		'users'=> $user,
      		'companyies'=> $companyies,
    		'categorys' => $category,
    		'currencys' => $currency,
			'userInfo' => $userInfo,
    	));
    	
        return $view;
    }
    
}	
