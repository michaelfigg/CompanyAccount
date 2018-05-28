<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Tigren\CompanyAccount\Api\Data;

/**
 * Customer interface.
 * @api
 */
interface CustomerInterface extends \Magento\Customer\Api\Data\CustomerInterface
{
    const ACCOUNT_ID = 'account_id';
    const ENABLE_ENTER_SHIPPING_ADDRESS = 'enable_enter_shipping_address';
    /**
     * Get account id
     *
     * @return int|null
     */
    public function getAccountId();

    /**
     * Get enable_enter_shipping_address
     *
     * @return int|null
     */
    public function getEnableEnterShippingAddress();

    /**
     * Set account id
     *
     * @param int $accountId
     * @return $this
     */
    public function setAccountId($accountId);

    /**
     * Set enable_enter_shipping_address
     *
     * @param int $enableEnterShippingAddress
     * @return $this
     */
    public function setEnableEnterShippingAddress($enableEnterShippingAddress);

}
