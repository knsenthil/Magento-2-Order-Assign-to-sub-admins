<?php
namespace Picturesalon\Assignorder\Ui\Component\Listing\Columns;


use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
 
class Assignpopup extends Column
{
    /**
     * @var UrlInterface
     */
    protected $urlBuilder;
	protected $helperData;
 
    /**
     * Constructor
     *
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
		\Magento\Backend\Model\Auth\Session $authSession,
		\Picturesalon\Assignorder\Helper\Data $helperData,
        array $components = [],
        array $data = []
    ) {
		$this->helperData = $helperData;
        $this->urlBuilder = $urlBuilder;
		if(!in_array(1,$authSession->getUser()->getRoles())){
            $data = [];
        }
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }
 
    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {	
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance(); // Instance of object manager
		
		$sql = $this->helperData->getAdminUsers();
		$resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
		$connection = $resource->getConnection();
		$result = $connection->fetchAll($sql);
		
		$key_form = $objectManager->get('Magento\Framework\Data\Form\FormKey');
		$form_Key = $key_form->getFormKey(); // this will give you from key
		
		
        if (isset($dataSource['data']['items'])) {
            $fieldName = $this->getData('name');
            foreach ($dataSource['data']['items'] as $key => & $item) { 
				$AssignedIds = explode(",",$item['assigned_user_id']);
			    $item[$fieldName . '_html'] = "<button class='button'><span>Assign</span></button>";
                $item[$fieldName . '_title'] = __('Assign Order To');
                $item[$fieldName . '_submitlabel'] = __('Assign');
                $item[$fieldName . '_cancellabel'] = __('Reset');
                $item[$fieldName . '_customerid'] = $item['entity_id'];
				$item[$fieldName . '_formkey'] = $form_Key;
				$item[$fieldName . '_admins'] = $this->_adminArray($result,$item['assigned_user_id']);
                $item[$fieldName . '_formaction'] = $this->urlBuilder->getUrl('service/assign/index');
            }
        }
 
        return $dataSource;
    }
	
	protected function _adminArray($result,$assigned_user_id) {
		$AssignedIds = explode(",",$assigned_user_id);
		foreach($result as $key => $_result) {
			if(in_array($_result['user_id'],$AssignedIds)) {
				$result[$key]['checked'] = 'checked';
			} else {
				$result[$key]['checked'] = '';
			}
		}
		return $result;
	}
}
?>