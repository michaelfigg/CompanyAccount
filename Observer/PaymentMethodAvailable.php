<?php

namespace Tigren\CompanyAccount\Observer;

use Magento\Framework\Event\ObserverInterface;


class PaymentMethodAvailable implements ObserverInterface
{
    protected $helper;
    protected $accountFactory;

    public function __construct(
        \Tigren\CompanyAccount\Helper\Data $helper,
        \Tigren\CompanyAccount\Model\AccountFactory $accountFactory
    )
    {
        $this->helper = $helper;
        $this->accountFactory = $accountFactory;
    }

    /**
     * payment_method_is_active event handler.
     *
     * @param \Magento\Framework\Event\Observer $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $accountId = $this->helper->getAccountIdByCustomer($this->helper->getCustomerId());
        $account = $this->accountFactory->create()->load($accountId);
        $pay_on = $account->getPayOnAccount();

        if ($observer->getEvent()->getMethodInstance()->getCode() == "purchaseorder" && $pay_on == 0) {
            $checkResult = $observer->getEvent()->getResult();
            $checkResult->setData('is_available', false);
        }
    }
}