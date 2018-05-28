<?php
/**
 *
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Tigren\CompanyAccount\Controller\Account;

use Magento\Customer\Model\Registration;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Create extends \Magento\Customer\Controller\AbstractAccount
{
    protected $registration;
    protected $pageFactory;
    protected $session;
    protected $resultPageFactory;
    protected $helper;

    public function __construct(
        Context $context,
        Session $customerSession,
        PageFactory $pageFactory,
        PageFactory $resultPageFactory,
        Registration $registration,
        \Tigren\CompanyAccount\Helper\Data $helper
    ) {
        $this->_customerSession = $customerSession;
        $this->pageFactory = $pageFactory;
        $this->session = $customerSession;
        $this->resultPageFactory = $resultPageFactory;
        $this->registration = $registration;
        $this->helper = $helper;
        parent::__construct($context);
    }

    /**
     * Customer register form page
     *
     * @return \Magento\Framework\Controller\Result\Redirect|\Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        if (!$this->registration->isAllowed()) {
            /** @var \Magento\Framework\Controller\Result\Redirect $resultRedirect */
            $resultRedirect = $this->resultRedirectFactory->create();
            $resultRedirect->setPath('*/*');
            return $resultRedirect;
        }

        if (!$this->session->isLoggedIn()) {
            $resultRedirect = $this->resultRedirectFactory->create();
            $resultRedirect->setPath('customer/account/login');
            return $resultRedirect;
        }
        if ($this->session->isLoggedIn() && !$this->helper->isInAvailableAccount($this->session->getCustomerId())) {
            $resultRedirect = $this->resultRedirectFactory->create();
            $resultRedirect->setPath('customer/account/index');
            $this->messageManager->addError(__('You can not create user'));
            return $resultRedirect;
        }
        /** @var \Magento\Framework\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $companyUserBlock = $resultPage->getLayout()->getBlock('customer-users-links.list-users');
        if ($companyUserBlock) {
            $companyUserBlock->setIsHighlighted(true);
        }
        return $resultPage;
    }
}
