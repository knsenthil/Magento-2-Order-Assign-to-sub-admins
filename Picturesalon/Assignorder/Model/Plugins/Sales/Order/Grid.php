<?php
namespace Picturesalon\Assignorder\Model\Plugins\Sales\Order;

class Grid {

    public static $table = 'sales_order_grid';
	protected $_authSession;
    /**
     *
     */
	public function __construct(\Magento\Backend\Model\Auth\Session $authSession) {
		$this->_authSession = $authSession;
	}
	
    public function afterSearch($intercepter, $collection) {
		$writer = new \Zend\Log\Writer\Stream(BP . '/var/log/plugin.log');
		$logger = new \Zend\Log\Logger();
		$logger->addWriter($writer);
		$user = $this->_authSession->getUser();	
        if ($collection->getMainTable() === $collection->getConnection()->getTableName(self::$table)) {    
				
			if($user['user_id']!=1)
				$collection->addFieldToFilter('assigned_user_id',array('finset' =>$user['user_id']));
        }
        else if($collection->getMainTable() == 'sales_invoice_grid') {
			if($user['user_id']!=1)
				$collection->addFieldToFilter('assigned_user_id',array('finset' =>$user['user_id']));
				
		} else if($collection->getMainTable()=='sales_shipment_grid') {
			if($user['user_id']!=1) 
				$collection->addFieldToFilter('assigned_user_id',array('finset' =>$user['user_id']));
		}
		else if($collection->getMainTable()=='sales_creditmemo_grid') {
			if($user['user_id']!=1) 
				$collection->addFieldToFilter('assigned_user_id',array('finset' =>$user['user_id']));
		}
        return $collection;

    }

}