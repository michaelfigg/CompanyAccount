<?php

namespace Tigren\CompanyAccount\Controller\Account;

use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Profile extends \Magento\Framework\App\Action\Action
{
    protected $_resultPageFactory;
    protected $_helper;
    protected $_sessionCustomer;
    protected $pageFactory;

    public function __construct(
        Context $context,
        PageFactory $pageFactory,
        \Tigren\CompanyAccount\Helper\Data $helper,
        \Magento\Customer\Model\Session $sessionCustomer
    ) {
        $this->_resultPageFactory       = $pageFactory;
        $this->_helper                  = $helper;
        $this->_sessionCustomer         = $sessionCustomer;
        parent::__construct($context);
    }

    public function execute()
    {
        if (!$this->_sessionCustomer->isLoggedIn()) {
            $resultRedirect = $this->resultRedirectFactory->create();
            $resultRedirect->setPath('customer/account/login');
            return $resultRedirect;
        }
        $resultPage = $this->_resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend(__(' Company Profile '));
        return $resultPage;
    }
}