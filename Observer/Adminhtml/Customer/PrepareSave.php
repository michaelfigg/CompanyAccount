<?php

namespace Tigren\CompanyAccount\Observer\Adminhtml\Customer;

use Magento\Framework\Event\ObserverInterface;

class PrepareSave implements ObserverInterface
{
    /**
     * @var \Tigren\CompanyAccount\Model\AccountFactory
     */
    protected $accountFactory;

    public function __construct(
        \Tigren\CompanyAccount\Model\AccountFactory $accountFactory
    )
    {
        $this->accountFactory = $accountFactory;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $customer = $observer->getCustomer();
        $params = $observer->getEvent()->getRequest()->getParams();
        if (!empty($params['customer']['account_id'])) {
            $account_id = $params['customer']['account_id'];
            $account = $this->accountFactory->create();
            $account->load($account_id);
            if ($account->getId()) {
               $customer->setCustomAttribute('account_id',$account_id);
            }
        }
    }
}