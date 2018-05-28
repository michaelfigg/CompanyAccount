<?php

namespace Tigren\CompanyAccount\Observer\Customer;

use Magento\Framework\Event\ObserverInterface;

class CreateUserSuccess implements ObserverInterface
{

    protected $helper;
    protected $_customers;
    protected $_sessionManager;
    public function __construct(
        \Tigren\CompanyAccount\Helper\Data $helper,
        \Magento\Customer\Model\Customer $customer,
        \Magento\Framework\Session\SessionManagerInterface $sessionManager
    )
    {
        $this->helper = $helper;
        $this->_customers  = $customer;
        $this->_sessionManager = $sessionManager;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $this->_sessionManager->start();
        $job_title = $this->_sessionManager->getJobTitle();
        $phone_number = $this->_sessionManager->getPhoneNumber();
        $is_active = $this->_sessionManager->getIsActive();

        $controller = $observer->getData('account_controller');
        $customer = $observer->getData('customer');
        $isAdmin = $controller->getRequest()->getParam('is_admin');
        //set attribute
        $setCustomercustomer = $this->_customers->load($customer->getId());
        $customerData = $setCustomercustomer->getDataModel();
        $customerData->setCustomAttribute('job_title', $job_title);
        $customerData->setCustomAttribute('phone_number', $phone_number);
        $customerData->setCustomAttribute('is_active', $is_active);
        $setCustomercustomer->updateData($customerData)->save();

        $this->helper->assignCustomer($customer->getId(),$isAdmin);

        $enableEnterShippingAddress = $controller->getRequest()->getParam('enable_enter_shipping_address');
        if($enableEnterShippingAddress)
            $this->helper->updateEnterShippingAddress($customer->getId(), $enableEnterShippingAddress);
        else
            $this->helper->updateEnterShippingAddress($customer->getId(), 0);
    }
}
