<?php
/**
 *
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Tigren\CompanyAccount\Api\Data;

/**
 * Company Account Interface
 * @api
 */
interface AccountInterface
{
    const KEY_ADDRESSES_SHIPPING_ACCOUNT = 'address_shipping';
    const KEY_ADDRESSES_BILLING_ACCOUNT = 'address_billing';
    const KEY_PAYMENT_OPTION_ACCOUNT = 'payment_options';

    /**
     * Get account id.
     *
     * @return int|null
     */
    public function getAccountId();

    /**
     * Set account id
     *
     * @param int $accountId
     * @return $this
     */
    public function setAccountId($accountId);

    /**
     * Get account number.
     *
     * @return string|null
     */
    public function getAccountNumber();

    /**
     * Set account number
     *
     * @param string $accountNumber
     * @return $this
     */
    public function setAccountNumber($accountNumber);

    /**
     * Get company name
     *
     * @return string|null
     */
    public function getCompany();

    /**
     * Set company name
     *
     * @param string $company
     * @return $this
     */
    public function setCompany($company);

    /**
     * Get telephone
     *
     * @return string|null
     */
    public function getTelephone();

    /**
     * Set telephone
     *
     * @param string $telephone
     * @return $this
     */
    public function setTelephone($telephone);

    /**
     * Get tax number
     *
     * @return string|null
     */
    public function getTax();

    /**
     * Set tax number
     *
     * @param string $tax
     * @return $this
     */
    public function setTax($tax);

    /**
     * Get logo image link
     *
     * @return string|null
     */
    public function getLogoImageLink();

    /**
     * Set logo image link
     *
     * @param string $logoImageLink
     * @return $this
     */
    public function setLogoImageLink($logoImageLink);

    /**
     * Get pay on account.
     *
     * @return int|null
     */
    public function getPayOnAccount();

    /**
     * Set pay on account
     *
     * @param int $payOnAccount
     * @return $this
     */
    public function setPayOnAccount($payOnAccount);

    /**
     * Get account group id.
     *
     * @return int|null
     */
    public function getAccountGroupId();

    /**
     * Set account group id
     *
     * @param int $accountGroupId
     * @return $this
     */
    public function setAccountGroupId($accountGroupId);

    /**
     * Get public notes
     *
     * @return string|null
     */
    public function getPublicNotes();

    /**
     * Set public notes
     *
     * @param string $publicNotes
     * @return $this
     */
    public function setPublicNotes($publicNotes);

    /**
     * Get manager first name
     *
     * @return string|null
     */
    public function getManagerFirstName();

    /**
     * Set manager first name
     *
     * @param string $managerFirstName
     * @return $this
     */
    public function setManagerFirstName($managerFirstName);

    /**
     * Get manager last name
     *
     * @return string|null
     */
    public function getManagerLastName();

    /**
     * Set manager last name
     *
     * @param string $managerLastName
     * @return $this
     */
    public function setManagerLastName($managerLastName);

    /**
     * Get manager telephone
     *
     * @return string|null
     */
    public function getManagerTelephone();

    /**
     * Set manager telephone
     *
     * @param string $managerTelephone
     * @return $this
     */
    public function setManagerTelephone($managerTelephone);

    /**
     * Get manager email
     *
     * @return string|null
     */
    public function getManagerEmail();

    /**
     * Set manager email
     *
     * @param string $managerEmail
     * @return $this
     */
    public function setManagerEmail($managerEmail);

    /**
     * Get manager profile
     *
     * @return string|null
     */
    public function getManagerProfile();

    /**
     * Set manager profile
     *
     * @param string $managerProfile
     * @return $this
     */
    public function setManagerProfile($managerProfile);

    /**
     * Get portal source
     *
     * @return string|null
     */
    public function getPortalSource();

    /**
     * Set portal source
     *
     * @param string $portalSource
     * @return $this
     */
    public function setPortalSource($portalSource);

    /**
     * Get portal username
     *
     * @return string|null
     */
    public function getPortalUsername();

    /**
     * Set portal username
     *
     * @param string $portalUsername
     * @return $this
     */
    public function setPortalUsername($portalUsername);

    /**
     * Get credit limit
     *
     * @return float|null
     */
    public function getCreditLimit();

    /**
     * Set credit limit
     *
     * @param float $creditLimit
     * @return $this
     */
    public function setCreditLimit($creditLimit);

    /**
     * Get credit terms
     *
     * @return float|null
     */
    public function getCreditTerms();

    /**
     * Set credit terms
     *
     * @param float $creditTerms
     * @return $this
     */
    public function setCreditTerms($creditTerms);

    /**
     * Get balance
     *
     * @return float|null
     */
    public function getBalance();

    /**
     * Set balance
     *
     * @param float $balance
     * @return $this
     */
    public function setBalance($balance);

    /**
     * Get account payment options.
     *
     * @return \Tigren\CompanyAccount\Api\Data\AccountPaymentInterface|null
     */
    public function getPaymentOptions();

    /**
     * Set account payment options.
     *
     * @param \Tigren\CompanyAccount\Api\Data\AccountPaymentInterface $paymentOptions
     * @return $this
     */
    public function setPaymentOptions(\Tigren\CompanyAccount\Api\Data\AccountPaymentInterface $paymentOptions);

    /**
     * Get account addresses.
     *
     * @return \Tigren\CompanyAccount\Api\Data\AccountAddressInterface|null
     */
    public function getAddressShipping();

    /**
     * Set account addresses.
     *
     * @param \Tigren\CompanyAccount\Api\Data\AccountAddressInterface $address
     * @return $this
     */
    public function setAddressShipping(\Tigren\CompanyAccount\Api\Data\AccountAddressInterface $address);

    /**
     * Get account addresses.
     *
     * @return \Tigren\CompanyAccount\Api\Data\AccountAddressInterface|null
     */
    public function getAddressBilling();

    /**
     * Set account addresses.
     *
     * @param \Tigren\CompanyAccount\Api\Data\AccountAddressInterface $address
     * @return $this
     */
    public function setAddressBilling(\Tigren\CompanyAccount\Api\Data\AccountAddressInterface $address);

}
