<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Tigren\CompanyAccount\Model;

use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Quote shipping/billing address validator service.
 *
 */
class QuoteAddressValidator extends \Magento\Quote\Model\QuoteAddressValidator
{
    /**
     * Address factory.
     *
     * @var \Magento\Customer\Api\AddressRepositoryInterface
     */
    protected $addressRepository;

    /**
     * Customer repository.
     *
     * @var \Magento\Customer\Api\CustomerRepositoryInterface
     */
    protected $customerRepository;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    protected $helper;

    /**
     * Constructs a quote shipping address validator service object.
     *
     * @param \Magento\Customer\Api\AddressRepositoryInterface $addressRepository
     * @param \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository Customer repository.
     * @param \Magento\Customer\Model\Session $customerSession
     */
    public function __construct(
        \Magento\Customer\Api\AddressRepositoryInterface $addressRepository,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \Magento\Customer\Model\Session $customerSession,
        \Tigren\CompanyAccount\Helper\Data $helper
    )
    {
        $this->addressRepository = $addressRepository;
        $this->customerRepository = $customerRepository;
        $this->customerSession = $customerSession;
        $this->helper = $helper;
    }

    /**
     * Validates the fields in a specified address data object.
     *
     * @param \Magento\Quote\Api\Data\AddressInterface $addressData The address data object.
     * @return bool
     * @throws \Magento\Framework\Exception\InputException The specified address belongs to another customer.
     * @throws \Magento\Framework\Exception\NoSuchEntityException The specified customer ID or address ID is not valid.
     */
    public function validate(\Magento\Quote\Api\Data\AddressInterface $addressData)
    {
        //validate customer id
        if ($addressData->getCustomerId()) {
            $customer = $this->customerRepository->getById($addressData->getCustomerId());
            if (!$customer->getId()) {
                throw new \Magento\Framework\Exception\NoSuchEntityException(
                    __('Invalid customer id %1', $addressData->getCustomerId())
                );
            }
        }

        if ($addressData->getCustomerAddressId()) {
            try {
                $this->addressRepository->getById($addressData->getCustomerAddressId());
            } catch (NoSuchEntityException $e) {
                throw new \Magento\Framework\Exception\NoSuchEntityException(
                    __('Invalid address id %1', $addressData->getId())
                );
            }

            $applicableAddressIds = [];
            if (!empty($this->helper->getCustomerId())) {
                $accountId = $this->helper->getAccountIdByCustomer($this->helper->getCustomerId());
                $arrAddressId = $this->helper->getIdAddressAccount($accountId);
                foreach ($arrAddressId as $key => $value) {
                    $applicableAddressIds[] = $value['address_id'];
                }
            }
            
            if (!in_array($addressData->getCustomerAddressId(), $applicableAddressIds)) {
                throw new \Magento\Framework\Exception\NoSuchEntityException(
                    __('Invalid customer address id %1', $addressData->getCustomerAddressId())
                );
            }
        }
        return true;
    }
}
