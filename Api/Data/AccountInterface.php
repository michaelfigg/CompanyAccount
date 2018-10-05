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

}
