<?php

namespace Tigren\CompanyAccount\Controller\Account;

use Magento\Customer\Model\Registration;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class AddressCreate extends \Magento\Customer\Controller\AbstractAccount
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
    ){
        parent::__construct($context);
        $this->_customerSession = $customerSession;
        $this->pageFactory = $pageFactory;
        $this->session = $customerSession;
        $this->resultPageFactory = $resultPageFactory;
        $this->registration = $registration;
        $this->helper = $helper;
    }

    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend(__('New Company Address'));
        $companyAddressBlock = $resultPage->getLayout()->getBlock('customer-users-links.company-address');
        if ($companyAddressBlock) {
            $companyAddressBlock->setIsHighlighted(true);
        }
        return $resultPage;
    }
}
