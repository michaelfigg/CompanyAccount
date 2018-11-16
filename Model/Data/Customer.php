<?php

namespace Tigren\CompanyAccount\Model\Data;

use Magento\Framework\Api\AttributeValueFactory;

/**
 * Class Customer
 * @SuppressWarnings(PHPMD.ExcessivePublicCount)
 */
class Customer extends \Magento\Customer\Model\Data\Customer implements
    \Tigren\CompanyAccount\Api\Data\CustomerInterface
{
    public function __construct(
        \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory,
        AttributeValueFactory $attributeValueFactory, 
        \Magento\Customer\Api\CustomerMetadataInterface $metadataService, 
        array $data = []
    ) {
        parent::__construct($extensionFactory, $attributeValueFactory, $metadataService, $data);
    }

    /**
     * Get account id
     *
     * @return int
     */
    public function getAccountId()
    {
        return $this->_get(self::ACCOUNT_ID);
    }

    /**
     * Set account id
     *
     * @param int $accountId
     * @return $this
     */
    public function setAccountId($accountId)
    {
        return $this->setData(self::ACCOUNT_ID, $accountId);
    }

    /**
     * Get full name
     *
     * @return string
     */
    public function getFullname()
    {
        return $this->_get(self::FULL_NAME);
    }

    /**
     * Set full name
     *
     * @param string $fullName
     * @return $this
     */
    public function setFullname($fullName)
    {
        return $this->setData(self::FULL_NAME, $fullName);
    }

    /**
     * Get ENABLE_ENTER_SHIPPING_ADDRESS
     *
     * @return int
     */
    public function getEnableEnterShippingAddress()
    {
        return $this->_get(self::ENABLE_ENTER_SHIPPING_ADDRESS);
    }

    /**
     * Set ENABLE_ENTER_SHIPPING_ADDRESS
     *
     * @param int $enableEnterShippingAddress
     * @return $this
     */
    public function setEnableEnterShippingAddress($enableEnterShippingAddress)
    {
        return $this->setData(self::ENABLE_ENTER_SHIPPING_ADDRESS, $enableEnterShippingAddress);
    }

}
