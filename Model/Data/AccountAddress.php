<?php

namespace Tigren\CompanyAccount\Model\Data;

use Tigren\CompanyAccount\Api\Data\AccountAddressInterface;

class AccountAddress implements \Tigren\CompanyAccount\Api\Data\AccountAddressInterface
{

    protected $_addressId;
    protected $_accountId;
    protected $_createdAt;
    protected $_updatedAt;
    protected $_region;
    protected $_regionId;
    protected $_countryId;
    protected $_street;
    protected $_company;
    protected $_telephone;
    protected $_fax;
    protected $_postcode;
    protected $_city;
    protected $_firstname;
    protected $_lastname;
    protected $_middlename;
    protected $_prefix;
    protected $_suffix;
    protected $_vatId;
    protected $_isBilling;
    protected $_isShippingDefault;

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getAddressId()
    {
        return $this->_addressId;
    }

    /**
     * Set ID
     *
     * @param int $id
     * @return $this
     */
    public function setAddressId($id)
    {
        $this->_addressId = $id;
        return $this;
    }

    /**
     * Get region
     *
     * @return string|null
     */
    public function getRegion()
    {
        return $this->_region;
    }

    /**
     * Set region
     *
     * @param string $region
     * @return $this
     */
    public function setRegion($region)
    {
        $this->_region = $region;
        return $this;
    }

    /**
     * Get region ID
     *
     * @return int|null
     */
    public function getRegionId()
    {
        return $this->_regionId;
    }

    /**
     * Set region ID
     *
     * @param int $regionId
     * @return $this
     */
    public function setRegionId($regionId)
    {
        $this->_regionId = $regionId;
        return $this;
    }

    /**
     * Two-letter country code in ISO_3166-2 format
     *
     * @return string|null
     */
    public function getCountryId()
    {
        return $this->_countryId;
    }

    /**
     * Set country id
     *
     * @param string $countryId
     * @return $this
     */
    public function setCountryId($countryId)
    {
        $this->_countryId = $countryId;
        return $this;
    }

    /**
     * Get street
     *
     * @return string|null
     */
    public function getStreet()
    {
        return $this->_street;
    }

    /**
     * Set street
     *
     * @param string $street
     * @return $this
     */
    public function setStreet($street)
    {
        $this->_street = $street;
        return $this;
    }

    /**
     * Get company
     *
     * @return string|null
     */
    public function getCompany()
    {
        return $this->_company;
    }

    /**
     * Set company
     *
     * @param string $company
     * @return $this
     */
    public function setCompany($company)
    {
        $this->_company = $company;
        return $this;
    }

    /**
     * Get telephone number
     *
     * @return string|null
     */
    public function getTelephone()
    {
        return $this->_telephone;
    }

    /**
     * Set telephone number
     *
     * @param string $telephone
     * @return $this
     */
    public function setTelephone($telephone)
    {
        $this->_telephone = $telephone;
        return $this;
    }

    /**
     * Get fax number
     *
     * @return string|null
     */
    public function getFax()
    {
        return $this->_fax;
    }

    /**
     * Set fax number
     *
     * @param string $fax
     * @return $this
     */
    public function setFax($fax)
    {
        $this->_fax = $fax;
        return $this;
    }

    /**
     * Get postcode
     *
     * @return string|null
     */
    public function getPostcode()
    {
        return $this->_postcode;
    }

    /**
     * Set postcode
     *
     * @param string $postcode
     * @return $this
     */
    public function setPostcode($postcode)
    {
        $this->_postcode = $postcode;
        return $this;
    }

    /**
     * Get city name
     *
     * @return string|null
     */
    public function getCity()
    {
        return $this->_city;
    }

    /**
     * Set city name
     *
     * @param string $city
     * @return $this
     */
    public function setCity($city)
    {
        $this->_city = $city;
        return $this;
    }

    /**
     * Get first name
     *
     * @return string|null
     */
    public function getFirstname()
    {
        return $this->_firstname;
    }

    /**
     * Set first name
     *
     * @param string $firstName
     * @return $this
     */
    public function setFirstname($firstName)
    {
        $this->_firstname = $firstName;
        return $this;
    }

    /**
     * Get last name
     *
     * @return string|null
     */
    public function getLastname()
    {
        return $this->_lastname;
    }

    /**
     * Set last name
     *
     * @param string $lastName
     * @return $this
     */
    public function setLastname($lastName)
    {
        $this->_lastname = $lastName;
        return $this;
    }

    /**
     * Get middle name
     *
     * @return string|null
     */
    public function getMiddlename()
    {
        return $this->_middlename;
    }

    /**
     * Set middle name
     *
     * @param string $middleName
     * @return $this
     */
    public function setMiddlename($middleName)
    {
        $this->_middlename = $middleName;
        return $this;
    }

    /**
     * Get prefix
     *
     * @return string|null
     */
    public function getPrefix()
    {
        return $this->_prefix;
    }

    /**
     * Set prefix
     *
     * @param string $prefix
     * @return $this
     */
    public function setPrefix($prefix)
    {
        $this->_prefix = $prefix;
        return $this;
    }

    /**
     * Get suffix
     *
     * @return string|null
     */
    public function getSuffix()
    {
        return $this->_suffix;
    }

    /**
     * Set suffix
     *
     * @param string $suffix
     * @return $this
     */
    public function setSuffix($suffix)
    {
        $this->_suffix = $suffix;
        return $this;
    }

    /**
     * Get Vat id
     *
     * @return string|null
     */
    public function getVatId()
    {
        return $this->_vatId;
    }

    /**
     * Set Vat id
     *
     * @param string $vatId
     * @return $this
     */
    public function setVatId($vatId)
    {
        $this->_vatId = $vatId;
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
     * Get Created At
     *
     * @return string|null
     */
    public function getCreatedAt()
    {
        return $this->_createdAt;
    }

    /**
     * Set Created At
     *
     * @param string $createdAt
     * @return $this
     */
    public function setCreatedAt($createdAt)
    {
        $this->_createdAt = $createdAt;
        return $this;
    }

    /**
     * Get Updated At
     *
     * @return string|null
     */
    public function getUpdatedAt()
    {
        return $this->_updatedAt;
    }

    /**
     * Set Updated At
     *
     * @param string $updatedAt
     * @return $this
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->_updatedAt = $updatedAt;
        return $this;
    }

    /**
     * Get Is Billing
     *
     * @return boolean|null
     */
    public function getIsBilling()
    {
        return $this->_isBilling;
    }

    /**
     * Set Is Billing
     *
     * @param boolean $isBilling
     * @return $this
     */
    public function setIsBilling($isBilling)
    {
        $this->_isBilling = $isBilling;
        return $this;
    }

    /**
     * Get Is Shipping Default
     *
     * @return boolean|null
     */
    public function getIsShippingDefault()
    {
        return $this->_isShippingDefault;
    }

    /**
     * Set Is Shipping Default
     *
     * @param boolean $isShippingDefault
     * @return $this
     */
    public function setIsShippingDefault($isShippingDefault)
    {
        $this->_isShippingDefault = $isShippingDefault;
        return $this;
    }
}