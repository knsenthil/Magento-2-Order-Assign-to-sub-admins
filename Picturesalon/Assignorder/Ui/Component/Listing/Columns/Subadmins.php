<?php
namespace Picturesalon\Assignorder\Ui\Component\Listing\Columns;

class Subadmins implements \Magento\Framework\Option\ArrayInterface
{
    //Here you can __construct Model

    public function toOptionArray()
    {
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance(); 
        $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection();
		//$sql = "select * from admin_user where user_id!=1";
		$sql = "SELECT a.username, b.user_id, c.resource_id FROM admin_user a, authorization_role b,authorization_rule c WHERE a.user_id = b.user_id and b.parent_id = c.role_id and c.resource_id = 'Magento_Sales::actions' and c.permission like '%allow%'";
		$sub_admin_name = $connection->fetchAll($sql);
		if(count($sub_admin_name)) {
			foreach($sub_admin_name as $_sub) {
				$options[] = ['label' => $_sub['username'], 'value' => $_sub['username']];
			}
		}
		return $options; 
    }
}