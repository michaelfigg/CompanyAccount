<?php

namespace Tigren\CompanyAccount\Controller\Adminhtml\Account;

class PopupAssignCustomer extends \Magento\Backend\App\Action
{
    protected $resultForwardFactory;
    protected $_datetime;
    protected $_helperCa;
    protected $_resultJsonFactory;
    protected $_resultPageFactory;
    protected $_customer;
    protected $_storeManager;
    protected $_connection;
    protected $_resource;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory,
        \Magento\Framework\Stdlib\DateTime\DateTime $dateTime,
        \Tigren\CompanyAccount\Helper\Data $helperCa,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Customer\Model\CustomerFactory $customerFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\ResourceConnection $resource
    ){
        parent::__construct($context);
        $this->resultForwardFactory = $resultForwardFactory;
        $this->_datetime            = $dateTime;
        $this->_helperCa            = $helperCa;
        $this->_resultJsonFactory   = $resultJsonFactory;
        $this->_resultPageFactory   = $resultPageFactory;
        $this->_customer            = $customerFactory;
        $this->_storeManager        = $storeManager;
        $this->_resource            = $resource;
        $this->_connection          = $this->_resource->getConnection('core_write');
    }


    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $resultPage = $this->_resultPageFactory->create();
        $response = [];
        $data = $this->getRequest()->getParams();

        if($data){
            try{
                if(count($data['selected_in_assign_customers'])){
                    foreach ($data['selected_in_assign_customers'] as $customerId){
                        $this->_helperCa->assignToAccount($data['account_id'], $customerId);
                    }
                }
                $gridCustomerBlock = $resultPage
                    ->getLayout()
                    ->createBlock(
                        'Tigren\CompanyAccount\Block\Adminhtml\Account\Edit\Tab\Customers',
                        'customer.company.account.tab',
                        []
                    )
                    ->setAccountId($data['account_id']);
                $response = [
                    'status' => 1,
                    'message' => 'User was added successfully',
                    'html' => $gridCustomerBlock->toHtml()
                ];
            }
            catch (\Exception $e){
                $response = [
                    'status' => 0,
                    'message' => $e->getMessage()
                ];
            }
        }

        return $this->_resultJsonFactory->create()->setData($response);
    }

}
