<?php

namespace Tigren\CompanyAccount\Model;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Customer\Model\Session as CustomerSession;

class AccountPaymentManagement implements \Tigren\CompanyAccount\Api\AccountPaymentManagementInterface
{

    protected $_accountPaymentFactory;
    protected $_accountPaymentCollectionFactory;
    protected $_dataObjectHelper;
    protected $_objectManager;
    protected $_customerSession;
    protected $helper;

    public function __construct(
        \Tigren\CompanyAccount\Model\AccountPaymentFactory $accountPaymentFactory,
        \Tigren\CompanyAccount\Model\ResourceModel\AccountPayment\CollectionFactory $accountPaymentCollectionFactory,
        \Magento\Framework\Api\DataObjectHelper $dataObjectHelper,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        CustomerSession $customerSession,
        \Tigren\CompanyAccount\Helper\Data $helper
    )
    {
        $this->_accountPaymentFactory = $accountPaymentFactory;
        $this->_accountPaymentCollectionFactory = $accountPaymentCollectionFactory;
        $this->_dataObjectHelper = $dataObjectHelper;
        $this->_objectManager = $objectManager;
        $this->_customerSession = $customerSession;
        $this->helper = $helper;
    }

    /**
     * Retrieve account payment.
     *
     * @param int $optionId
     * @return \Tigren\CompanyAccount\Api\Data\AccountPaymentInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($optionId)
    {
        $paymentData = $this->_accountPaymentFactory->create()->load($optionId);
        return $paymentData;
    }

    /**
     * Get account payment by account id.
     *
     * @param int $accountId
     * @return \Tigren\CompanyAccount\Api\Data\AccountPaymentInterface[]
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getByAccount($accountId)
    {
        $payments = [];
        $paymentCollection = $this->_accountPaymentCollectionFactory
            ->create()
            ->addFieldToFilter('account_id', $accountId);
        if ($paymentCollection->getSize()) {
            foreach ($paymentCollection as $payment)
                $payments[] = $this->getById($payment->getId());
        }
        return $payments;
    }

    /**
     * Get all company accounts payment
     *
     * @return \Tigren\CompanyAccount\Api\Data\AccountPaymentInterface[]
     */
    public function getAllPayment()
    {
        $payments = [];
        $paymentsCollection = $this->_accountPaymentCollectionFactory->create();
        if ($paymentsCollection->getSize()) {
            foreach ($paymentsCollection as $payment)
                $payments[] = $this->getById($payment->getId());
        }
        return $payments;
    }

    /**
     * Get all company accounts payment option
     *
     * @return \Tigren\CompanyAccount\Api\Data\AccountPaymentInterface[]
     */
    public function getAllPaymentOption()
    {
        $payments = [];
        $paymentCollection = $this->_accountPaymentCollectionFactory->create();
        if ($paymentCollection->getSize()) {
            foreach ($paymentCollection as $payment)
                $payments[] = $this->getById($payment->getId());
        }
        return $payments;
    }

    /**
     * Delete payment.
     *
     * @param int $optionId
     * @return boolean
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete($optionId)
    {
        $payment = $this->_accountPaymentFactory->create()->load($optionId);
        if (!$payment->getId()) {
            throw new NoSuchEntityException(
                __('We can\'t specify an payment options.')
            );
        }
        $payment->delete();
        return true;
    }


    public function getCustomerId()
    {
        return $this->_customerSession->getCustomerId();
    }

    /**
     * Save account payment option.
     *
     * @param \Tigren\CompanyAccount\Api\Data\AccountPaymentInterface $payment
     * @return \Tigren\CompanyAccount\Api\Data\AccountPaymentInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(\Tigren\CompanyAccount\Api\Data\AccountPaymentInterface $payment)
    {
        $paymentSave = $this->_accountPaymentFactory->create();
        if ($payment->getOptionId()) {
            $paymentSave->load($payment->getOptionId());
            if (!$paymentSave->getId()) {
                throw new NoSuchEntityException(
                    __('We can\'t specify an payment option.')
                );
            }
        }
        $accountExist = $this->_accountPaymentFactory->create()->getCollection()
            ->addFieldToSelect('*')
            ->addFieldToFilter("account_id", ["eq" => $payment->getAccountId()])->getData();
        if (!empty($accountExist[0]['option_id'])) {
            $paymentSave->load($accountExist[0]['option_id']);
        }
        $paymentSave->setAccountId((int)$payment->getAccountId());
        $paymentSave->setCreditCard((int)$payment->getCreditCard());
        $paymentSave->setLeasing((int)$payment->getLeasing());
        $paymentSave->setOnAccount((int)$payment->getOnAccount());

        $paymentSave->save();

        return $paymentSave;
    }
}