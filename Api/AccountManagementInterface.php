<?php
/**
 *
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Tigren\CompanyAccount\Api;

/**
 * Interface for managing company accounts.
 * @api
 */
interface AccountManagementInterface
{

    const MAX_PASSWORD_LENGTH = 256;

	/**
     * Get company account by Id.
     *
     * @param int $accountId
     * @return \Tigren\CompanyAccount\Api\Data\AccountInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($accountId);

    /**
     * Get account by company name
     *
     * @param string $company
     * @return \Tigren\CompanyAccount\Api\Data\AccountInterface[]
     */
    public function getByCompany($company);

    /**
     * Get all company accounts
     *
     * @return \Tigren\CompanyAccount\Api\Data\AccountInterface[]
     */
    public function getList();

    /**
     * Create or Edit company account.
     *
     * @param \Tigren\CompanyAccount\Api\Data\AccountInterface $account
     * @return \Tigren\CompanyAccount\Api\Data\AccountInterface
     */

    public function save(\Tigren\CompanyAccount\Api\Data\AccountInterface $account );

    /**
     * Delete company account.
     *
     * @param int $accountId
     * @return boolean
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function delete($accountId);

    /**
     * Assign customer to account.
     * @param int $accountId
     * @param int $customerId
     * @return boolean
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function assign($accountId,$customerId);

    /**
     * Create customer account. Perform necessary business operations like sending email.
     *
     * @param \Tigren\CompanyAccount\Api\Data\CustomerInterface $customer
     * @param string $password
     * @param string $redirectUrl
     * @return \Magento\Customer\Api\Data\CustomerInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function createAccount(
        \Tigren\CompanyAccount\Api\Data\CustomerInterface $customer,
        $password = null,
        $redirectUrl = ''
    );

    /**
     * @param Data\AccountInterface $account
     * @param Data\CustomerInterface $customer
     * @param string|null $password
     * @param string $redirectUrl
     * @return \Magento\Customer\Api\Data\CustomerInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function verify(
        \Tigren\CompanyAccount\Api\Data\AccountInterface $account,
        \Tigren\CompanyAccount\Api\Data\CustomerInterface $customer,
        $password = null,
        $redirectUrl = ''
    );

    /**
     * Get all customers of account.
     *
     * @param int $accountId
     * @return \Magento\Customer\Api\Data\CustomerInterface[]
     */
    public function getCustomersByAccount($accountId);
}
