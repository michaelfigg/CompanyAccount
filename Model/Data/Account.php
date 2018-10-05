<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Tigren\CompanyAccount\Model\Data;

class Account implements \Tigren\CompanyAccount\Api\Data\AccountInterface
{
    private $accountId;
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
}
