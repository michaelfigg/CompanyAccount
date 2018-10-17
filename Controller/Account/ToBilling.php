<?php

namespace Tigren\CompanyAccount\Controller\Account;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class ToBilling extends \Tigren\CompanyAccount\Controller\Account\AccountAbstract
{
    protected $_customerRepository;
    protected $_pageFactory;
    protected $_dataObjectHelper;
    protected $_session;
    protected $_resultPageFactory;
    protected $_accountAddressManagement;

    public function __construct(
        Context $context,
        PageFactory $pageFactory,
        \Magento\Customer\Model\Session $customerSession,
        PageFactory $resultPageFactory,
        CustomerRepositoryInterface $customerRepository,
        DataObjectHelper $dataObjectHelper,
        \Tigren\CompanyAccount\Helper\Data $helper,
        \Tigren\CompanyAccount\Api\AccountAddressManagementInterface $accountAddressManagement
    ){
        parent::__construct($context, $customerSession, $pageFactory, $helper);
        $this->_session = $customerSession;
        $this->_resultPageFactory = $resultPageFactory;
        $this->_customerRepository = $customerRepository;
        $this->_dataObjectHelper = $dataObjectHelper;
        $this->_accountAddressManagement = $accountAddressManagement;
    }

    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        if(!$this->helper->isLoggedIn()){
            return $resultRedirect->setPath('customer/account/login');
        }
        $addressId = $this->getRequest()->getParam('id');
        if($addressId){
            try{
                $toBilling = $this->_accountAddressManagement->changeToBillingAddress($addressId);
                if($toBilling)
                    $this->messageManager->addSuccess('Default billing address was changed successfully');
                else
                    $this->messageManager->addSuccess('Error change address to billing');
            }catch (\Exception $e){
                $this->messageManager->addError($e);
            }
        }
        return $resultRedirect->setPath('companyaccount/account/address/');
    }
}
