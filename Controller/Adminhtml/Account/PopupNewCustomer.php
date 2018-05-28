<?php

namespace Tigren\CompanyAccount\Controller\Adminhtml\Account;


use Magento\Customer\Api\AccountManagementInterface;
use Magento\Customer\Model\CustomerExtractor;
use Magento\Framework\Exception\InputException;
use Magento\Newsletter\Model\SubscriberFactory;
use Magento\Customer\Api\CustomerRepositoryInterface;

class PopupNewCustomer extends \Magento\Backend\App\Action
{

    protected $resultForwardFactory;
    protected $_datetime;
    protected $_accountManagement;
    protected $_customerExtractor;
    protected $_subscriberFactory;
    protected $_helperCa;
    protected $_resultJsonFactory;
    protected $_customerRepository;
    protected $_resultPageFactory;
    protected $_customer;
    protected $_storeManager;
    protected $_connection;
    protected $_resource;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory,
        \Magento\Framework\Stdlib\DateTime\DateTime $dateTime,
        AccountManagementInterface $accountManagement,
        CustomerExtractor $customerExtractor,
        SubscriberFactory $subscriberFactory,
        \Tigren\CompanyAccount\Helper\Data $helperCa,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        CustomerRepositoryInterface $customerRepository,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Customer\Model\CustomerFactory $customerFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\ResourceConnection $resource
    )
    {
        $this->resultForwardFactory = $resultForwardFactory;
        $this->_datetime            = $dateTime;
        $this->_customerExtractor   = $customerExtractor;
        $this->_accountManagement   = $accountManagement;
        $this->_subscriberFactory   = $subscriberFactory;
        $this->_helperCa            = $helperCa;
        $this->_resultJsonFactory   = $resultJsonFactory;
        $this->_customerRepository  = $customerRepository;
        $this->_resultPageFactory   = $resultPageFactory;
        $this->_customer            = $customerFactory;
        $this->_storeManager        = $storeManager;
        $this->_resource            = $resource;
        $this->_connection          = $this->_resource->getConnection('core_write');
        parent::__construct($context);
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

                $customer = $this->_customerExtractor->extract('customer_account_create', $this->getRequest());
                $password = $this->getRequest()->getParam('password');
                $confirmation = $this->getRequest()->getParam('password_confirmation');
                $this->checkPasswordConfirmation($password, $confirmation);
                $redirectUrl = $this->getUrl('*/*/');
                $hasCustomer = false;
                $customersExist = $this->_customer->create()->getCollection();
                if($customersExist->getSize()){
                    foreach ($customersExist as $customerExist){
                        if($customerExist->getEmail() == $customer->getEmail())
                            $hasCustomer = true;
                        break;
                    }
                }
                if($hasCustomer){
                    $response = [
                        'status' => 0,
                        'message' => 'There is already an account with this email address.'
                    ];
                    return $this->_resultJsonFactory->create()->setData($response);
                }

                $customer = $this->_accountManagement->createAccount($customer, $password, $redirectUrl);

                if ($this->getRequest()->getParam('is_subscribed', false))
                    $this->_subscriberFactory->create()->subscribeCustomerById($customer->getId());
                if($data['account_id']){
                    $this->_helperCa->assignToAccount($data['account_id'], $customer->getId());
                    $isAdmin = $data['is_admin'];
                    if($isAdmin) {
                        $dataInsert = ['admin_id' => $customer->getId(), 'account_id' => $data['account_id']];
                        $this->_connection->insert('tigren_comaccount_account_admin', $dataInsert);
                    }
                }
                $gridCustomerBlock = $resultPage
                    ->getLayout()
                    ->createBlock(
                        'Tigren\CompanyAccount\Block\Adminhtml\Account\Edit\Tab\Customers',
                        'customer.company.account.tab',
                        []
                    );
                if($data['account_id'])
                    $gridCustomerBlock->setAccountId($data['account_id']);

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

    protected function checkPasswordConfirmation($password, $confirmation)
    {
        if ($password != $confirmation) {
            throw new InputException(__('Please make sure your passwords match.'));
        }
    }

}
