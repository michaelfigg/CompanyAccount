<?php

namespace Tigren\CompanyAccount\Api;

interface AccountPaymentManagementInterface
{

    /**
     * Save account payment.
     *
     * @param \Tigren\CompanyAccount\Api\Data\AccountPaymentInterface $payment
     * @return \Tigren\CompanyAccount\Api\Data\AccountPaymentInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(\Tigren\CompanyAccount\Api\Data\AccountPaymentInterface $paymentOption);

    /**
     * Retrieve account payment.
     *
     * @param int $optionId
     * @return \Tigren\CompanyAccount\Api\Data\AccountPaymentInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($optionId);

    /**
     * Get account payment by account id.
     *
     * @param int $accountId
     * @return \Tigren\CompanyAccount\Api\Data\AccountPaymentInterface[]
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getByAccount($accountId);

    /**
     * Get all company accounts payment option
     *
     * @return \Tigren\CompanyAccount\Api\Data\AccountPaymentInterface[]
     */
    public function getAllPaymentOption();

    /**
     * Delete payment options.
     *
     * @param int $optionId
     * @return boolean
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete($optionId);


}
