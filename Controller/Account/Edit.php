<?php

namespace Tigren\CompanyAccount\Controller\Account;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Edit extends \Tigren\CompanyAccount\Controller\Account\AccountAbstract
{
    protected $customerRepository;
    protected $pageFactory;
    protected $dataObjectHelper;
    protected $session;
    protected $resultPageFactory;

    public function __construct(
        Context $context,
        PageFactory $pageFactory,
        \Magento\Customer\Model\Session $customerSession,
        PageFactory $resultPageFactory,
        CustomerRepositoryInterface $customerRepository,
        DataObjectHelper $dataObjectHelper,
        \Tigren\CompanyAccount\Helper\Data $helper
    ){
        parent::__construct($context, $customerSession, $pageFactory,$helper);
        $this->session = $customerSession;
        $this->resultPageFactory = $resultPageFactory;
        $this->customerRepository = $customerRepository;
        $this->dataObjectHelper = $dataObjectHelper;
    }

    /**
     * Forgot customer account information page
     *
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        /** @var \Magento\Framework\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();

        $block = $resultPage->getLayout()->getBlock('customer_edit');
        if ($block) {
            $block->setRefererUrl($this->_redirect->getRefererUrl());
        }
        $resultPage->getConfig()->getTitle()->set(__('User Information'));
        $resultPage->getLayout()->getBlock('messages')->setEscapeMessageFlag(true);
        $companyUserBlock = $resultPage->getLayout()->getBlock('customer-users-links.list-users');
        if ($companyUserBlock) {
            $companyUserBlock->setIsHighlighted(true);
        }
        return $resultPage;
    }
}
