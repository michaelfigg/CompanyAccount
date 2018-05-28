<?php

namespace Tigren\CompanyAccount\Observer\Customer;

use Magento\Framework\Event\ObserverInterface;

class RegisterSuccess implements ObserverInterface
{

    protected $helper;
    public function __construct(
        \Tigren\CompanyAccount\Helper\Data $helper
    )
    {
        $this->helper = $helper;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $controller = $observer->getData('account_controller');
        $customer = $observer->getData('customer');
        $company = $controller->getRequest()->getParam('company');
        $firstname = $controller->getRequest()->getParam('firstname');
        $lastname = $controller->getRequest()->getParam('lastname');

        $companyName = null;
        $is_business = null;
        if (!empty($company)) {
            $companyName = $company;
            $is_business = 1;
        }else{
            $companyName = $firstname.' '.$lastname;
            $is_business = 0;
        }
        $this->helper->createAccount($companyName, $customer->getId(), $is_business);
        return $this;
    }
}
