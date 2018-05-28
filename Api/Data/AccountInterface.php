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
}
