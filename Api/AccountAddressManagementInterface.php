<?php

namespace Tigren\CompanyAccount\Api;

interface AccountAddressManagementInterface
{

    /**
     * Save account address.
     *
     * @param \Tigren\CompanyAccount\Api\Data\AccountAddressInterface $address
     * @return \Tigren\CompanyAccount\Api\Data\AccountAddressInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(\Tigren\CompanyAccount\Api\Data\AccountAddressInterface $address);

    /**
     * Retrieve account address.
     *
     * @param int $addressId
     * @return \Tigren\CompanyAccount\Api\Data\AccountAddressInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($addressId);

    /**
     * Get account address by account id.
     *
     * @param int $accountId
     * @return \Tigren\CompanyAccount\Api\Data\AccountAddressInterface[]
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getByAccount($accountId);

    /**
     * Get all company accounts address
     *
     * @return \Tigren\CompanyAccount\Api\Data\AccountAddressInterface[]
     */
    public function getAllAddress();

    /**
     * Delete address.
     *
     * @param int $addressId
     * @return boolean
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete($addressId);

    /**
     * Change address to billing address
     *
     * @param int $addressId
     * @return boolean
     * @throws \Magento\Framework\Exception\LocalizedException
    */
    public function changeToBillingAddress($addressId);

}
