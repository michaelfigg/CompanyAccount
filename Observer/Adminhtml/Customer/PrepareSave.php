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

        if(empty($params['customer']['account_id'])){
            throw new \Magento\Framework\Exception\LocalizedException(__('Account ID is required'));
        }
        
        $account_id = $params['customer']['account_id'];
        $account = $this->accountFactory->create();
        $account->load($account_id);

        if (!$account->getId()) {
            throw new \Magento\Framework\Exception\LocalizedException(__('Account does not exist'));
        }
        
        $customer->setCustomAttribute('account_id', $account_id);
    }
}