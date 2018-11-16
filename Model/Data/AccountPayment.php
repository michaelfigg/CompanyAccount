<?php

namespace Tigren\CompanyAccount\Model\Data;

class AccountPayment implements \Tigren\CompanyAccount\Api\Data\AccountPaymentInterface
{

    protected $_optionId;
    protected $_accountId;
    protected $_creditCard;
    protected $_leasing;
    protected $_onAccount;

    /**
     * Get option id
     *
     * @return int|null
     */
    public function getOptionId()
    {
        return $this->_optionId;
    }

    /**
     * Set option id
     *
     * @param int $optionId
     * @return $this
     */
    public function setOptionId($optionId)
    {
        $this->_optionId = $optionId;
        return $this;
    }

    /**
     * Get Account id
     *
     * @return int|null
     */
    public function getAccountId()
    {
        return $this->_accountId;
    }

    /**
     * Set Account id
     *
     * @param int $accountId
     * @return $this
     */

    public function setAccountId($accountId)
    {
        $this->_accountId = $accountId;
        return $this;
    }

    /**
     * Get credit card
     *
     * @return boolean|null
     */
    public function getCreditCard()
    {
        return $this->_creditCard;
    }

    /**
     * Set credit card
     *
     * @param boolean $creditCard
     * @return $this
     */

    public function setCreditCard($creditCard)
    {
        $this->_creditCard = $creditCard;
        return $this;
    }

    /**
     * Get leasing
     *
     * @return boolean|null
     */
    public function getLeasing()
    {
        return $this->_leasing;
    }

    /**
     * Set leasing
     *
     * @param boolean $leasing
     * @return $this
     */

    public function setLeasing($leasing)
    {
        $this->_leasing = $leasing;
        return $this;
    }

    /**
     * Get on account
     *
     * @return boolean|null
     */
    public function getOnAccount()
    {
        return $this->_onAccount;
    }

    /**
     * Set on account
     *
     * @param boolean $onAccount
     * @return $this
     */

    public function setOnAccount($onAccount)
    {
        $this->_onAccount = $onAccount;
        return $this;
    }
}