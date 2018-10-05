<?php

namespace Tigren\CompanyAccount\Model;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Customer\Model\Session as CustomerSession;

class AccountAddressManagement implements \Tigren\CompanyAccount\Api\AccountAddressManagementInterface
{

    protected $_accountAddressFactory;
    protected $_accountAddressCollectionFactory;
    protected $_dataObjectHelper;
    protected $_objectManager;
    protected $_customerSession;
    protected $helper;

    public function __construct(
        \Tigren\CompanyAccount\Model\AccountAddressFactory $accountAddressFactory,
        \Tigren\CompanyAccount\Model\ResourceModel\AccountAddress\CollectionFactory $accountAddressCollectionFactory,
        \Magento\Framework\Api\DataObjectHelper $dataObjectHelper,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        CustomerSession $customerSession,
        \Tigren\CompanyAccount\Helper\Data $helper
    )
    {
        $this->_accountAddressFactory = $accountAddressFactory;
        $this->_accountAddressCollectionFactory = $accountAddressCollectionFactory;
        $this->_dataObjectHelper = $dataObjectHelper;
        $this->_objectManager = $objectManager;
        $this->_customerSession = $customerSession;
        $this->helper = $helper;
    }

    /**
     * Retrieve account address.
     *
     * @param int $addressId
     * @return \Tigren\CompanyAccount\Api\Data\AccountAddressInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($addressId)
    {
        $addressData = $this->_accountAddressFactory->create()->load($addressId);
//        $addressDataObject = $this->_objectManager->create('\Tigren\CompanyAccount\Api\Data\AccountAddressInterface');
//        $addressDataObject->setData($addressData);
        return $addressData;
    }

    /**
     * Get account address by account id.
     *
     * @param int $accountId
     * @return \Tigren\CompanyAccount\Api\Data\AccountAddressInterface[]
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getByAccount($accountId)
    {
        $addresses = [];
        $addressCollection = $this->_accountAddressCollectionFactory
            ->create()
            ->addFieldToFilter('account_id', $accountId);
        if ($addressCollection->getSize()) {
            foreach ($addressCollection as $address)
                $addresses[] = $this->getById($address->getId());
        }
        return $addresses;
    }

    /**
     * Get all company accounts address
     *
     * @return \Tigren\CompanyAccount\Api\Data\AccountAddressInterface[]
     */
    public function getAllAddress()
    {
        $addresses = [];
        $addressCollection = $this->_accountAddressCollectionFactory->create();
        if ($addressCollection->getSize()) {
            foreach ($addressCollection as $address)
                $addresses[] = $this->getById($address->getId());
        }
        return $addresses;
    }

    /**
     * Delete address.
     *
     * @param int $addressId
     * @return boolean
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete($addressId)
    {
        $address = $this->_accountAddressFactory->create()->load($addressId);
        if (!$address->getId()) {
            throw new NoSuchEntityException(
                __('We can\'t specify an address.')
            );
        }
        $address->delete();
        return true;
    }

    /**
     * Change address to billing address
     *
     * @param int $addressId
     * @return boolean
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function changeToBillingAddress($addressId)
    {
        $account_id = $this->helper->isInAvailableAccount($this->getCustomerId());
        $addressCollection = $this->_accountAddressCollectionFactory->create()->addFieldToFilter('account_id', ['eq' => $account_id]);
        foreach ($addressCollection as $addressItem) {
            if ($addressItem->getIsBilling() == 1) {
                $address = $this->_accountAddressFactory->create()->load($addressItem->getId());
                $address->setIsBilling(0);
                $address->save();
            }
        }

        $addressUpdate = $this->_accountAddressFactory->create()->load($addressId);
        if (!$addressUpdate->getId()) {
            throw new NoSuchEntityException(
                __('We can\'t specify an address.')
            );
        }
        $addressUpdate->setIsBilling(1);
        $addressUpdate->save();
        return true;
    }

    public function changeToShippingDefaultAddress($addressId)
    {
        $account_id = $this->helper->isInAvailableAccount($this->getCustomerId());
        $addressCollection = $this->_accountAddressCollectionFactory->create()->addFieldToFilter('account_id', ['eq' => $account_id]);
        foreach ($addressCollection as $addressItem) {
            if ($addressItem->getIsShippingDefault() == 1) {
                $address = $this->_accountAddressFactory->create()->load($addressItem->getId());
                $address->setIsShippingDefault(0);
                $address->save();
            }
        }

        $addressUpdate = $this->_accountAddressFactory->create()->load($addressId);
        if (!$addressUpdate->getId()) {
            throw new NoSuchEntityException(
                __('We can\'t specify an address.')
            );
        }
        $addressUpdate->setIsShippingDefault(1);
        $addressUpdate->save();
        return true;
    }
    
    public function getCustomerId()
    {
        return $this->_customerSession->getCustomerId();
    }

    /**
     * Save account address.
     *
     * @param \Tigren\CompanyAccount\Api\Data\AccountAddressInterface $address
     * @return \Tigren\CompanyAccount\Api\Data\AccountAddressInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(\Tigren\CompanyAccount\Api\Data\AccountAddressInterface $address)
    {
        $addressSave = $this->_accountAddressFactory->create();
        if ($address->getAddressId()) {
            $addressSave->load($address->getAddressId());
            if (!$addressSave->getId()) {
                throw new NoSuchEntityException(
                    __('We can\'t specify an address.')
                );
            }
        }
        $addressSave->setRegion($address->getRegion());
        $addressSave->setRegionId($address->getRegionId());
        $addressSave->setCountryId($address->getCountryId());
        $addressSave->setStreet($address->getStreet());
        $addressSave->setCompany($address->getCompany());
        $addressSave->setTelephone($address->getTelephone());
        $addressSave->setFax($address->getFax());
        $addressSave->setPostcode($address->getPostcode());
        $addressSave->setCity($address->getCity());
        $addressSave->setFirstname($address->getFirstname());
        $addressSave->setLastname($address->getLastname());
        $addressSave->setMiddlename($address->getMiddlename());
        $addressSave->setPrefix($address->getPrefix());
        $addressSave->setSuffix($address->getSuffix());
        $addressSave->setVatId($address->getVatId());
        $addressSave->setAccountId($address->getAccountId());
        $addressSave->setCreatedAt($address->getCreatedAt());
        $addressSave->setUpdatedAt($address->getUpdatedAt());
        $addressSave->setIsBilling($address->getIsBilling());
        $addressSave->save();

        if ($address->getIsBilling())
            $this->changeToBillingAddress($addressSave->getId());

        return $addressSave;
    }
}