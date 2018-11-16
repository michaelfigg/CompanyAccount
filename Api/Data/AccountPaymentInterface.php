<?php

namespace Tigren\CompanyAccount\Api\Data;

interface AccountPaymentInterface
{
    const ID = 'option_id';
    const ACCOUNT_ID = 'account_id';
    const CREDIT_CARD = 'credit_card';
    const LEASING = 'leasing';
    const ON_ACCOUNT = 'on_account';

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getOptionId();

    /**
     * Set ID
     *
     * @param int $id
     * @return $this
     */
    public function setOptionId($id);

    /**
     * Get Account id
     *
     * @return int|null
     */
    public function getAccountId();

    /**
     * Set Account id
     *
     * @param int $accountId
     * @return $this
     */
    public function setAccountId($accountId);
    /**
     * Get credit card
     *
     * @return boolean|null
     */
    public function getCreditCard();

    /**
     * Set credit card
     *
     * @param boolean $creditCard
     * @return $this
     */
    public function setCreditCard($creditCard);

    /**
     * Get leasing
     *
     * @return boolean|null
     */
    public function getLeasing();

    /**
     * Set leasing
     *
     * @param boolean $leasing
     * @return $this
     */
    public function setLeasing($leasing);

    /**
     * Get on account
     *
     * @return boolean|null
     */
    public function getOnAccount();

    /**
     * Set on account
     *
     * @param boolean $onAccount
     * @return $this
     */
    public function setOnAccount($onAccount);


}
