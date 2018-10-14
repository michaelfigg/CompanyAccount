<?php

namespace Tigren\CompanyAccount\Controller\Account;

use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Address extends \Magento\Framework\App\Action\Action
{
    protected $_resultPageFactory;
    protected $_helper;
    protected $_sessionCustomer;

    public function __construct(
        Context $context,
        \Magento\Customer\Model\Session $customerSession,
        PageFactory $pageFactory,
        \Tigren\CompanyAccount\Helper\Data $helper
    ){
        parent::__construct($context);
        $this->_resultPageFactory = $pageFactory;
        $this->_helper = $helper;
        $this->_sessionCustomer = $customerSession;
    }

    public function execute()
    {
        if (!$this->_sessionCustomer->isLoggedIn()) {
            $resultRedirect = $this->resultRedirectFactory->create();
            $resultRedirect->setPath('customer/account/login');
            return $resultRedirect;
        }
        $resultPage = $this->_resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend(__('Company Addresses'));
        return $resultPage;
    }
}