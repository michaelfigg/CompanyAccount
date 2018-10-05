<?php

namespace Tigren\CompanyAccount\Observer\Customer;

use Magento\Framework\Event\ObserverInterface;

class SaveAddressCompany implements ObserverInterface
{
    protected $_currentCustomer;
    protected $_companyAccountHelper;
    protected $_accountAddressManagement;
    protected $_datetime;
    protected $accountAddressFactory;
    protected $_order;

    public function __construct(
        \Magento\Customer\Helper\Session\CurrentCustomer $currentCustomer,
        \Tigren\CompanyAccount\Helper\Data $companyAccountHelper,
        \Tigren\CompanyAccount\Api\AccountAddressManagementInterface $accountAddressManagement,
        \Magento\Framework\Stdlib\DateTime\DateTime $dateTime,
        \Tigren\CompanyAccount\Model\AccountAddressFactory $accountAddressFactory,
        \Magento\Sales\Api\Data\OrderInterface $order
    )
    {
        $this->_currentCustomer = $currentCustomer;
        $this->_companyAccountHelper = $companyAccountHelper;
        $this->_accountAddressManagement = $accountAddressManagement;
        $this->_datetime = $dateTime;
        $this->accountAddressFactory = $accountAddressFactory;
        $this->_order = $order;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $orderids = $observer->getEvent()->getOrderIds();
        foreach ($orderids as $orderid) {
            $order = $this->_order->load($orderid);
        }
        $address = $order->getShippingAddress()->getData();

        $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/hung.log');
        $logger = new \Zend\Log\Logger();
        $logger->addWriter($writer);
        $logger->info(print_r($address, true));

        //if(!empty($address->getSaveInAddressBook())){
        $this->SaveAddressAccount($address);
        //}
        return $this;
    }

    protected function SaveAddressAccount($data)
    {
        if ($data) {
            $data['is_billing'] = 1;
            try {
                $customerId = $this->_currentCustomer->getCustomerId();
                $accountId = $this->_companyAccountHelper->getAccountIdByCustomer($customerId);
                $model = $this->accountAddressFactory->create();

                $model->setData($data);
                $model->setStreet($data['street']);
                $model->setAccountId($accountId);
                $model->setCreatedAt($this->_datetime->gmtDate());
                $model->setUpdatedAt($this->_datetime->gmtDate());
                $model->setId(NULL)->save();

                if (!empty($data['is_billing']))
                    $this->_accountAddressManagement->changeToBillingAddress($model->getId());
            } catch (\Exception $e) {
            }
        }

        return true;
    }
}