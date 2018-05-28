<?php

namespace Tigren\CompanyAccount\Controller\Account;

use Magento\Customer\Model\Registration;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class AddressEdit extends \Magento\Customer\Controller\AbstractAccount
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

    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend(__(' Edit Company Address '));
        $companyAddressBlock = $resultPage->getLayout()->getBlock('customer-users-links.company-address');
        if ($companyAddressBlock) {
            $companyAddressBlock->setIsHighlighted(true);
        }
        return $resultPage;
    }
}
