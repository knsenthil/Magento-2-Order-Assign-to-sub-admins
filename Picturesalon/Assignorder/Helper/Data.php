<?php
namespace Picturesalon\Assignorder\Helper;
use \Magento\Framework\App\Helper\AbstractHelper;
class Data extends AbstractHelper
{
	protected $authSession;
	private $_objectManager;

	public function __construct(\Magento\Backend\Model\Auth\Session $authSession,
	\Magento\Framework\ObjectManagerInterface $objectmanager) {
		$this->_objectManager = $objectmanager;
	    $this->authSession = $authSession;
	}

	public function getCurrentUser()
	{
	    return $this->authSession->getUser();
	}

	public function getAdminUsers() {
		$query = "SELECT a.username, b.user_id, c.resource_id FROM admin_user a, authorization_role b,authorization_rule c WHERE a.user_id = b.user_id and b.parent_id = c.role_id and c.resource_id = 'Magento_Sales::actions' and c.permission like '%allow%' ";
		return $query;
	}

	public function getAssignedStatusByOrderId($order_id) {
		return $sql = "select vendor_name,assigned_user_id from sales_order_grid where entity_id= $order_id";
	}
	
	public function getAdminUserById($id) {
		return $sql = "select username from admin_user where user_id= $id";
	}

	public function UpdateInvoice($orderId,$order) {
		$resource = $this->_objectManager->get('Magento\Framework\App\ResourceConnection');
		$connection = $resource->getConnection();
		$sales_invoice_grid = $resource->getTableName('sales_invoice_grid'); 
		$sql = "Update " . $sales_invoice_grid . " set vendor_name = '".$order->getData('vendor_name')."',assigned_user_id='".$order->getData('assigned_user_id')."' where order_id =".$orderId;
		$connection->query($sql);
		
		$sales_invoice_grid = $resource->getTableName('sales_invoice'); 
		$sql = "Update " . $sales_invoice_grid . " set vendor_name = '".$order->getData('vendor_name')."',assigned_user_id='".$order->getData('assigned_user_id')."' where order_id =".$orderId;
		$connection->query($sql);
	}
	
	public function UpdateShipment($orderId,$order) {
		$resource = $this->_objectManager->get('Magento\Framework\App\ResourceConnection');
		$connection = $resource->getConnection();
		$sales_shipment_grid = $resource->getTableName('sales_shipment_grid'); 
		$sql = "Update " . $sales_shipment_grid . " set vendor_name = '".$order->getData('vendor_name')."',assigned_user_id='".$order->getData('assigned_user_id')."' where order_id =".$orderId;
		$connection->query($sql);
		
		$sales_shipment_grid = $resource->getTableName('sales_shipment'); 
		$sql = "Update " . $sales_shipment_grid . " set vendor_name = '".$order->getData('vendor_name')."',assigned_user_id='".$order->getData('assigned_user_id')."' where order_id =".$orderId;
		$connection->query($sql);
	}
	
	public function UpdateCrditMemo($orderId,$order) {
		$resource = $this->_objectManager->get('Magento\Framework\App\ResourceConnection');
		$connection = $resource->getConnection();
		$sales_creditmemo_grid = $resource->getTableName('sales_creditmemo_grid'); 
		$sql = "Update " . $sales_creditmemo_grid . " set vendor_name = '".$order->getData('vendor_name')."',assigned_user_id='".$order->getData('assigned_user_id')."' where order_id =".$orderId;
		$connection->query($sql);
		
		$sales_creditmemo_grid = $resource->getTableName('sales_creditmemo'); 
		$sql = "Update " . $sales_creditmemo_grid . " set vendor_name = '".$order->getData('vendor_name')."',assigned_user_id='".$order->getData('assigned_user_id')."' where order_id =".$orderId;
		$connection->query($sql);
	}

}