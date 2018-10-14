<?php

namespace Tigren\CompanyAccount\Controller\Account;

use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Json\Helper\Data;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Checkout\Model\Session;
use Psr\Log\LoggerInterface;
use Magento\Framework\Controller\Result\RedirectFactory;

class AddressCreatePost extends Action
{
    protected $checkoutSession;
    protected $jsonFactory;
    protected $jsonHelper;
    protected $logger;
    protected $_currentCustomer;
    protected $_datetime;
    protected $_cart;
    protected $redirectFactory;
    protected $_companyAccountHelper;
    protected $_accountAddressManagement;
    protected $_formKeyValidator;

    public function __construct(
        Context $context,
        Session $session,
        JsonFactory $jsonFactory,
        Data $jsonHelper,
        LoggerInterface $logger,
        \Magento\Customer\Helper\Session\CurrentCustomer $currentCustomer,
        \Magento\Framework\Stdlib\DateTime\DateTime $dateTime,
        \Magento\Checkout\Model\Cart $cart,
        RedirectFactory $redirectFactory,
        \Tigren\CompanyAccount\Helper\Data $companyAccountHelper,
        \Tigren\CompanyAccount\Api\AccountAddressManagementInterface $accountAddressManagement,
        \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator
    ){
        parent::__construct($context);
        $this->checkoutSession = $session;
        $this->jsonFactory = $jsonFactory;
        $this->jsonHelper = $jsonHelper;
        $this->logger = $logger;
        $this->_currentCustomer = $currentCustomer;
        $this->_datetime = $dateTime;
        $this->_cart = $cart;
        $this->redirectFactory = $redirectFactory;
        $this->_companyAccountHelper = $companyAccountHelper;
        $this->_accountAddressManagement = $accountAddressManagement;
        $this->_formKeyValidator = $formKeyValidator;
    }

    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        if (!$this->_formKeyValidator->validate($this->getRequest())) {
            return $this->resultRedirect->setPath('*/*/');
        }
        $data = $this->getRequest()->getParams();
        if($data){
            try{
                $customerId = $this->_currentCustomer->getCustomerId();
                $accountId = $this->_companyAccountHelper->getAccountIdByCustomer($customerId);
                //TODO: Don't use object manager
                $model = $this->_objectManager->create('\Tigren\CompanyAccount\Model\AccountAddress');

                $model->setData($this->getRequest()->getParams());
                $model->setStreet($data['street'][0]. ' ' .$data['street'][1]);
                $model->setAccountId($accountId);
                $model->setCreatedAt($this->_datetime->gmtDate());
                $model->setUpdatedAt($this->_datetime->gmtDate());
                $model->setId(NULL)->save();

                if(!empty($data['is_billing'])){
                    $this->_accountAddressManagement->changeToBillingAddress($model->getId());
                }
                if(!empty($data['is_shipping_default'])){
                    $this->_accountAddressManagement->changeToShippingDefaultAddress($model->getId());
                }

                $this->messageManager->addSuccess('The address was saved successfully');
                $resultRedirect->setPath('companyaccount/account/address/');
            }
            catch (\Exception $e){
                $this->messageManager->addError($e);
                $resultRedirect->setPath('companyaccount/account/address/');
            }
        }

        return $resultRedirect;
    }

}