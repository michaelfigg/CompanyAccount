<?php

namespace Tigren\CompanyAccount\Api\Data;

interface AccountAddressInterface
{
    const ID = 'address_id';
    const ACCOUNT_ID = 'account_id';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    const REGION = 'region';
    const REGION_ID = 'region_id';
    const COUNTRY_ID = 'country_id';
    const STREET = 'street';
    const COMPANY = 'company';
    const TELEPHONE = 'telephone';
    const FAX = 'fax';
    const POSTCODE = 'postcode';
    const CITY = 'city';
    const FIRSTNAME = 'firstname';
    const LASTNAME = 'lastname';
    const MIDDLENAME = 'middlename';
    const PREFIX = 'prefix';
    const SUFFIX = 'suffix';
    const VAT_ID = 'vat_id';
    const IS_BILLING = 'is_billing';

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getAddressId();

    /**
     * Set ID
     *
     * @param int $id
     * @return $this
     */
    public function setAddressId($id);

    /**
     * Get region
     *
     * @return string|null
     */
    public function getRegion();

    /**
     * Set region
     *
     * @param string $region
     * @return $this
     */
    public function setRegion($region);

    /**
     * Get region ID
     *
     * @return int|null
     */
    public function getRegionId();

    /**
     * Set region ID
     *
     * @param int $regionId
     * @return $this
     */
    public function setRegionId($regionId);

    /**
     * Two-letter country code in ISO_3166-2 format
     *
     * @return string|null
     */
    public function getCountryId();

    /**
     * Set country id
     *
     * @param string $countryId
     * @return $this
     */
    public function setCountryId($countryId);

    /**
     * Get street
     *
     * @return string|null
     */
    public function getStreet();

    /**
     * Set street
     *
     * @param string $street
     * @return $this
     */
    public function setStreet($street);

    /**
     * Get company
     *
     * @return string|null
     */
    public function getCompany();

    /**
     * Set company
     *
     * @param string $company
     * @return $this
     */
    public function setCompany($company);

    /**
     * Get telephone number
     *
     * @return string|null
     */
    public function getTelephone();

    /**
     * Set telephone number
     *
     * @param string $telephone
     * @return $this
     */
    public function setTelephone($telephone);

    /**
     * Get fax number
     *
     * @return string|null
     */
    public function getFax();

    /**
     * Set fax number
     *
     * @param string $fax
     * @return $this
     */
    public function setFax($fax);

    /**
     * Get postcode
     *
     * @return string|null
     */
    public function getPostcode();

    /**
     * Set postcode
     *
     * @param string $postcode
     * @return $this
     */
    public function setPostcode($postcode);

    /**
     * Get city name
     *
     * @return string|null
     */
    public function getCity();

    /**
     * Set city name
     *
     * @param string $city
     * @return $this
     */
    public function setCity($city);

    /**
     * Get first name
     *
     * @return string|null
     */
    public function getFirstname();

    /**
     * Set first name
     *
     * @param string $firstName
     * @return $this
     */
    public function setFirstname($firstName);

    /**
     * Get last name
     *
     * @return string|null
     */
    public function getLastname();

    /**
     * Set last name
     *
     * @param string $lastName
     * @return $this
     */
    public function setLastname($lastName);

    /**
     * Get middle name
     *
     * @return string|null
     */
    public function getMiddlename();

    /**
     * Set middle name
     *
     * @param string $middleName
     * @return $this
     */
    public function setMiddlename($middleName);

    /**
     * Get prefix
     *
     * @return string|null
     */
    public function getPrefix();

    /**
     * Set prefix
     *
     * @param string $prefix
     * @return $this
     */
    public function setPrefix($prefix);

    /**
     * Get suffix
     *
     * @return string|null
     */
    public function getSuffix();

    /**
     * Set suffix
     *
     * @param string $suffix
     * @return $this
     */
    public function setSuffix($suffix);

    /**
     * Get Vat id
     *
     * @return string|null
     */
    public function getVatId();

    /**
     * Set Vat id
     *
     * @param string $vatId
     * @return $this
     */
    public function setVatId($vatId);

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
     * Get Created At
     *
     * @return string|null
     */
    public function getCreatedAt();

    /**
     * Set Created At
     *
     * @param string $createdAt
     * @return $this
     */
    public function setCreatedAt($createdAt);

    /**
     * Get Updated At
     *
     * @return string|null
     */
    public function getUpdatedAt();

    /**
     * Set Updated At
     *
     * @param string $updatedAt
     * @return $this
     */
    public function setUpdatedAt($updatedAt);

    /**
     * Get Is Billing
     *
     * @return boolean|null
     */
    public function getIsBilling();

    /**
     * Set Is Billing
     *
     * @param boolean $isBilling
     * @return $this
     */
    public function setIsBilling($isBilling);
}
