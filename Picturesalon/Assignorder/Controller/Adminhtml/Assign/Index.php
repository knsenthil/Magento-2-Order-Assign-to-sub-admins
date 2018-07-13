<?php
namespace Picturesalon\Assignorder\Controller\Adminhtml\Assign;
use Magento\Backend\App\Action\Context;
use \Magento\Framework\Controller\ResultFactory;
 
class Index extends \Magento\Backend\App\Action
{


    //protected $_messageManager;
	protected $helperData;

    public function __construct(
        Context $context,
		\Picturesalon\Assignorder\Helper\Data $helperData
        //\Magento\Framework\Message\ManagerInterface $messageManager
    ) {
		$this->helperData = $helperData;
        parent::__construct($context);
        //$this->_messageManager = $messageManager;
    }

    /**
     * Hello test controller page.
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $data = $this->getRequest()->getParams();
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); 
        $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection();
		$sub_admin = '';
		$username = array();
		$sub_admin_id ='';
		$sub_admin_name = '';
		if(isset($data['sub-admin'])) {
			foreach($data['sub-admin'] as $sub) {
				$sql = $this->helperData->getAdminUserById($sub);
				$sub_admin_name = $connection->fetchCol($sql);
				$username[] = $sub_admin_name[0];
			}
			$admin_user_tbl = $resource->getTableName('admin_user');
			$sub_admin_id = implode(",",$data['sub-admin']);
			$sub_admin_name = implode(" | ",$username);
		}
		$sales_order_grid = $resource->getTableName('sales_order_grid'); 
        $sql = "Update " . $sales_order_grid . " set vendor_name = '".$sub_admin_name."',assigned_user_id='".$sub_admin_id."' where entity_id =".$data['entity_id'];
        $connection->query($sql);
		$sales_order = $resource->getTableName('sales_order');
		$sql = "Update " . $sales_order . " set vendor_name = '".$sub_admin_name."',assigned_user_id='".$sub_admin_id."' where entity_id =".$data['entity_id'];
        $connection->query($sql);
        $this->messageManager->addSuccessMessage('Order has been successfully assigned!');
        $this->_redirect('sales/order/');
    }
 
   
}