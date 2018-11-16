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
    const FULL_NAME = 'fullname';
    const ENABLE_ENTER_SHIPPING_ADDRESS = 'enable_enter_shipping_address';
    const URL_LOGIN_EXTERNAL = 'url_login_external';
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
     * Get full name
     *
     * @return string|null
     */
    public function getFullname();
    /**
     * Set full name
     *
     * @param string $fullName
     * @return $this
     */
    public function setFullname($fullName);

    /**
     * Set enable_enter_shipping_address
     *
     * @param int $enableEnterShippingAddress
     * @return $this
     */
    public function setEnableEnterShippingAddress($enableEnterShippingAddress);

}
