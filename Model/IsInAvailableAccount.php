<?php

namespace Tigren\CompanyAccount\Model;

use Magento\Checkout\Model\ConfigProviderInterface;

class IsInAvailableAccount implements ConfigProviderInterface
{

    protected $_currentCustomer;
    protected $_companyAccountHelper;

    public function __construct(
        \Magento\Customer\Helper\Session\CurrentCustomer $currentCustomer,
        \Tigren\CompanyAccount\Helper\Data $companyAccountHelper
    )
    {
        $this->_currentCustomer = $currentCustomer;
        $this->_companyAccountHelper = $companyAccountHelper;
    }

    public function getConfig()
    {
        $config = [];
        if (!empty($this->_currentCustomer->getCustomerId()) && !empty($this->_companyAccountHelper->isInAvailableAccount($this->_currentCustomer->getCustomerId()))) {
            $config['checkAccount'] = true;
        } else {
            $config['checkAccount'] = false;
        }

        return $config;
    }
}
