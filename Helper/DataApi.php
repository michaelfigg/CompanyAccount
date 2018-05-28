<?php

namespace Tigren\CompanyAccount\Helper;

class DataApi extends \Magento\Framework\App\Helper\AbstractHelper
{
    protected $_customerSession;
    protected $_storeManager;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    )
    {
        $this->_customerSession = $customerSession;
        $this->_storeManager = $storeManager;
        return parent::__construct($context);
    }

    public function isLoggedIn()
    {
        if ($this->_customerSession->isLoggedIn()) {
            return true;
        }
        return false;
    }

    public function getCustomerId(){
        if($this->isLoggedIn()){
            return $this->_customerSession->getCustomer()->getId();
        }

        return false;
    }

    public function getStore()
    {
        return $this->_storeManager->getStore();
    }

    public function isAllow()
    {
        if ($this->_moduleManager->isOutputEnabled($this->_getModuleName()) && $this->scopeConfig->getValue(
                'company_account/general/active',
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
                $this->getStore()->getId()
            )
        ) {
            return true;
        }
        return false;
    }

    public function getClientId()
    {
        return $this->scopeConfig->getValue(
            'company_account/general/client_id',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $this->getStore()->getId()
        );
    }

    public function getClientSecret()
    {
        return $this->scopeConfig->getValue('company_account/general/client_secret',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,$this->getStore()->getId());
    }

}
