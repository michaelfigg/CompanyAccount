<?php

namespace Tigren\CompanyAccount\Block\Account\Dashboard;

use Magento\Framework\Exception\NoSuchEntityException;

class Info extends \Magento\Customer\Block\Account\Dashboard\Info
{

    protected $_helperCa;
    protected $_accountFactory;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Customer\Helper\Session\CurrentCustomer $currentCustomer,
        \Magento\Newsletter\Model\SubscriberFactory $subscriberFactory,
        \Magento\Customer\Helper\View $helperView,
        \Tigren\CompanyAccount\Helper\Data $helperCa,
        \Tigren\CompanyAccount\Model\AccountFactory $accountFactory,
        array $data = []
    )
    {
        parent::__construct(
            $context,
            $currentCustomer,
            $subscriberFactory,
            $helperView,
            $data
        );
        $this->_helperCa = $helperCa;
        $this->_accountFactory = $accountFactory;
    }

    public function getAccount()
    {
        $customerId = $this->getCustomer()->getId();
        $accountId = $this->_helperCa->getAccountIdByCustomer($customerId);
        $account = null;
        if ($accountId)
            $account = $this->_accountFactory->create()->load($accountId);

        return $account;
    }

    public function getTitleWelcomeCompany($name)
    {
        return 'Welcome to ' . $name . ' dashboard !';
    }
}
