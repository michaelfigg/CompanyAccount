<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Tigren\CompanyAccount\Model\Data;

class Account extends \Magento\Framework\Api\AbstractExtensibleObject implements \Tigren\CompanyAccount\Api\Data\AccountInterface
{
    private $accountId;
    private $accountNumber;
    private $company;
    private $telephone;
    private $tax;
    private $logoImageLink;
    private $payOnAccount;
    private $accountGroupId;
    private $publicNotes;
    private $managerFirstName;
    private $managerLastName;
    private $managerTelephone;
    private $managerEmail;
    private $managerProfile;
    private $portalSource;
    private $portalUsername;
    private $creditLimit;
    private $creditTerms;
    private $balance;

    /**
     * {@inheritdoc}
     */
    public function getAccountId()
    {
        return $this->accountId;
    }

    /**
     * {@inheritdoc}
     */
    public function setAccountId($accountId)
    {
        $this->accountId = $accountId;
        return $this;
    }
    /**
     * {@inheritdoc}
     */
    public function getAccountNumber()
    {
        return $this->accountNumber;
    }

    /**
     * {@inheritdoc}
     */
    public function setAccountNumber($accountNumber)
    {
        $this->accountNumber = $accountNumber;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * {@inheritdoc}
     */
    public function setCompany($company)
    {
        $this->company = $company;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * {@inheritdoc}
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getTax()
    {
        return $this->tax;
    }

    /**
     * {@inheritdoc}
     */
    public function setTax($tax)
    {
        $this->tax = $tax;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getLogoImageLink()
    {
        return $this->logoImageLink;
    }

    /**
     * {@inheritdoc}
     */
    public function setLogoImageLink($logoImageLink)
    {
        $this->logoImageLink = $logoImageLink;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getPayOnAccount()
    {
        return $this->payOnAccount;
    }

    /**
     * {@inheritdoc}
     */
    public function setPayOnAccount($payOnAccount)
    {
        $this->payOnAccount = $payOnAccount;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getAccountGroupId()
    {
        return $this->accountGroupId;
    }

    /**
     * {@inheritdoc}
     */
    public function setAccountGroupId($accountGroupId)
    {
        $this->accountGroupId = $accountGroupId;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getPublicNotes()
    {
        return $this->publicNotes;
    }

    /**
     * {@inheritdoc}
     */
    public function setPublicNotes($publicNotes)
    {
        $this->publicNotes = $publicNotes;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getManagerFirstName()
    {
        return $this->managerFirstName;
    }

    /**
     * {@inheritdoc}
     */
    public function setManagerFirstName($managerFirstName)
    {
        $this->managerFirstName = $managerFirstName;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getManagerLastName()
    {
        return $this->managerLastName;
    }

    /**
     * {@inheritdoc}
     */
    public function setManagerLastName($managerLastName)
    {
        $this->managerLastName = $managerLastName;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getManagerTelephone()
    {
        return $this->managerTelephone;
    }

    /**
     * {@inheritdoc}
     */
    public function setManagerTelephone($managerTelephone)
    {
        $this->managerTelephone = $managerTelephone;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getManagerEmail()
    {
        return $this->managerEmail;
    }

    /**
     * {@inheritdoc}
     */
    public function setManagerEmail($managerEmail)
    {
        $this->managerEmail = $managerEmail;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getManagerProfile()
    {
        return $this->managerProfile;
    }

    /**
     * {@inheritdoc}
     */
    public function setManagerProfile($managerProfile)
    {
        $this->managerProfile = $managerProfile;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getPortalSource()
    {
        return $this->portalSource;
    }

    /**
     * {@inheritdoc}
     */
    public function setPortalSource($portalSource)
    {
        $this->portalSource = $portalSource;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getPortalUsername()
    {
        return $this->portalUsername;
    }

    /**
     * {@inheritdoc}
     */
    public function setPortalUsername($portalUsername)
    {
        $this->portalUsername = $portalUsername;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getCreditLimit()
    {
        return $this->creditLimit;
    }

    /**
     * {@inheritdoc}
     */
    public function setCreditLimit($creditLimit)
    {
        $this->creditLimit = $creditLimit;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getCreditTerms()
    {
        return $this->creditTerms;
    }

    /**
     * {@inheritdoc}
     */
    public function setCreditTerms($creditTerms)
    {
        $this->creditTerms = $creditTerms;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getBalance()
    {
        return $this->balance;
    }

    /**
     * {@inheritdoc}
     */
    public function setBalance($balance)
    {
        $this->balance = $balance;
        return $this;
    }

    /**
     * Get account payment options.
     *
     * @return \Tigren\CompanyAccount\Api\Data\AccountPaymentInterface|null
     */
    public function getPaymentOptions()
    {
        return $this->_get(self::KEY_PAYMENT_OPTION_ACCOUNT);
    }

    /**
     * Set account payment options.
     *
     * @param \Tigren\CompanyAccount\Api\Data\AccountPaymentInterface $address
     * @return $this
     */
    public function setPaymentOptions(\Tigren\CompanyAccount\Api\Data\AccountPaymentInterface $paymentOption)
    {
        return $this->setData(self::KEY_PAYMENT_OPTION_ACCOUNT, $paymentOption);
    }

    /**
     * Get address
     *
     * @return \Tigren\CompanyAccount\Api\Data\AccountAddressInterface|null
     */
    public function getAddressShipping()
    {
        return $this->_get(self::KEY_ADDRESSES_SHIPPING_ACCOUNT);
    }

    /**
     * Set account address.
     *
     * @param \Tigren\CompanyAccount\Api\Data\AccountAddressInterface $address
     * @return $this
     */
    public function setAddressShipping(\Tigren\CompanyAccount\Api\Data\AccountAddressInterface $address)
    {
        return $this->setData(self::KEY_ADDRESSES_SHIPPING_ACCOUNT, $address);
    }

    /**
     * Get address
     *
     * @return \Tigren\CompanyAccount\Api\Data\AccountAddressInterface|null
     */
    public function getAddressBilling()
    {
        return $this->_get(self::KEY_ADDRESSES_BILLING_ACCOUNT);
    }

    /**
     * Set account address.
     *
     * @param \Tigren\CompanyAccount\Api\Data\AccountAddressInterface $address
     * @return $this
     */
    public function setAddressBilling(\Tigren\CompanyAccount\Api\Data\AccountAddressInterface $address)
    {
        return $this->setData(self::KEY_ADDRESSES_BILLING_ACCOUNT, $address);
    }
}
