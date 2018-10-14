<?php

namespace Tigren\CompanyAccount\Controller\Account;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class AccountAbstract extends Action
{
    protected $pageFactory;
    protected $_customerSession;
    protected $helper;
   
    public function __construct(
        Context $context,
        \Magento\Customer\Model\Session $customerSession,
        PageFactory $pageFactory,
        \Tigren\CompanyAccount\Helper\Data $helper
    ){
        parent::__construct($context);
        $this->_customerSession = $customerSession;
        $this->pageFactory = $pageFactory;
        $this->helper = $helper;
    }

    public function execute()
    {
        $resultPage = $this->pageFactory->create();
        return $resultPage;
    }

    public function dispatch(\Magento\Framework\App\RequestInterface $request)
    {
        if (!$this->_customerSession->isLoggedIn()) {
            $this->_redirect('customer/account/login');
            return ;
        }
        if (!$this->helper->isInAvailableAccount($this->getCustomerId())) {
            $this->_redirect('customer/account');
            return ;
        }

        return parent::dispatch($request);
    }

    public function getCustomerId()
    {
        return $this->_customerSession->getCustomerId();
    }

}