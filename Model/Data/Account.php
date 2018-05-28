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
}
