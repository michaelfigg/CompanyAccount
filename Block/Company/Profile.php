<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Tigren\CompanyAccount\Block\Company;

use Magento\Customer\Api\Data\AddressInterface;
use Magento\Customer\Model\Address\Mapper;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Customer\Model\Session as CustomerSession;

/**
 * Class to manage customer dashboard addresses section
 *
 * @api
 * @since 100.0.2
 */
class Profile extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Magento\Customer\Model\Address\Config
     */
    protected $_addressConfig;

    /**
     * @var \Magento\Customer\Helper\Session\CurrentCustomer
     */
    protected $currentCustomer;

    /**
     * @var \Magento\Customer\Helper\Session\CurrentCustomerAddress
     */
    protected $currentCustomerAddress;

    /**
     * @var Mapper
     */
    protected $addressMapper;

    protected $addressFactory;

    protected $_customerSession;

    protected $helper;

    protected $_customerFactory;

    protected $_storeManager;

    protected $_countryFactory;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Customer\Helper\Session\CurrentCustomer $currentCustomer
     * @param \Magento\Customer\Helper\Session\CurrentCustomerAddress $currentCustomerAddress
     * @param \Magento\Customer\Model\Address\Config $addressConfig
     * @param Mapper $addressMapper
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Customer\Helper\Session\CurrentCustomer $currentCustomer,
        \Magento\Customer\Helper\Session\CurrentCustomerAddress $currentCustomerAddress,
        \Magento\Customer\Model\Address\Config $addressConfig,
        Mapper $addressMapper,
        \Tigren\CompanyAccount\Model\AccountAddressFactory $addressFactory,
        CustomerSession $customerSession,
        \Tigren\CompanyAccount\Helper\Data $helper,
        \Magento\Customer\Model\CustomerFactory $customerFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Directory\Model\CountryFactory $countryFactory,
        array $data = []
    )
    {
        $this->currentCustomer = $currentCustomer;
        $this->currentCustomerAddress = $currentCustomerAddress;
        $this->_addressConfig = $addressConfig;
        parent::__construct($context, $data);
        $this->addressMapper = $addressMapper;
        $this->addressFactory = $addressFactory;
        $this->_customerSession = $customerSession;
        $this->helper = $helper;
        $this->_customerFactory = $customerFactory;
        $this->_storeManager = $storeManager;
        $this->_countryFactory = $countryFactory;
    }

    /**
     * Get the logged in customer
     *
     * @return \Magento\Customer\Api\Data\CustomerInterface|null
     */
     public function getBaseUrl()
     {
         return $this->_storeManager->getStore()->getBaseUrl();
     }
    public function getCustomer()
    {
        try {
            return $this->currentCustomer->getCustomer();
        } catch (NoSuchEntityException $e) {
            return null;
        }
    }

    public function getCustomerId()
    {
        return $this->_customerSession->getCustomerId();
    }

    public function getAccountId()
    {
        $account_id = $this->helper->isInAvailableAccount($this->getCustomerId());
        return $account_id;
    }

    public function getIdAddressAccount()
    {
        return $this->helper->getIdAddressAccount($this->getAccountId());
    }

    public function getAccountAddress($addressId)
    {
        $collection = $this->addressFactory->create()->getCollection();
        $data = $collection->addFieldToFilter('address_id', ['in' => $addressId])->addFieldToFilter('is_billing', ['eq' => 1])->getData();
        return $data;
    }

    public function getPrimaryBillingAddressHtml()
    {
        if ($this->helper->isInAvailableAccount($this->getCustomerId())) {
            $addressAccount = $this->getAccountAddress($this->getIdAddressAccount());
            if (!empty($addressAccount)) {
                return $addressAccount[0]['lastname'] . " " . $addressAccount[0]['firstname'] . "</br>" .
                    "City : " . $addressAccount[0]['city'] . "</br>"
                    . "Street : " . $addressAccount[0]['street'] . "</br>"
                    . "Company : " . $addressAccount[0]['company'] . "</br>"
                    . "Zip/Postal Code : " . $addressAccount[0]['postcode'] . "</br>"
                    . "Telephone : " . $addressAccount[0]['telephone'];
            }
            return __('Company have not set a default billing address.');
        }
    }

    public function getAccountInfo()
    {
        if ($this->helper->isInAvailableAccount($this->getCustomerId())) {
            $addressAccount = $this->getAccountAddress($this->getIdAddressAccount());
            if (!empty($addressAccount)) {
                return "Company : " . $addressAccount[0]['company'] . "</br>"
                     . "Email : " . $this->getEmailAccount()['email'] . "</br>"
                    . "Zip/Postal Code : " . $addressAccount[0]['postcode'];
            }
            return __('Company have not set a default billing address.');
        }
    }
    public function getLegalAddress(){
      if ($this->helper->isInAvailableAccount($this->getCustomerId())) {
          $addressAccount = $this->getAccountAddress($this->getIdAddressAccount());
          if (!empty($addressAccount)) {
              $country = $this->_countryFactory->create()->loadByCode($addressAccount[0]['country_id']);
              return  "Street : " . $addressAccount[0]['street'] . "</br>"
                      ."City : " . $addressAccount[0]['city'] . "</br>"
                      ."Country : ".$country->getName(). "</br>"
                      . "Telephone : " . $addressAccount[0]['telephone'];
          }
          return __('Company have not set a legal address.');
      }
    }
    public function getEmailAccount(){
      $account_id = $this->helper->getAccountIdByCustomer($this->getCustomerId());
      $arrAdminId = $this->helper->getAdminIds($account_id);
      $emailAccount = $this->_customerFactory->create()->getCollection()->addAttributeToFilter('entity_id', ['in' => $arrAdminId])->setOrder('entity_id', 'asc')->getFirstItem()->getData();
      return $emailAccount;
    }
    public function getAllAdminAccount(){
      $account_id = $this->helper->getAccountIdByCustomer($this->getCustomerId());
      $arrAdminId = $this->helper->getAdminIds($account_id);
      $customer = $this->_customerFactory->create()->getCollection()->addAttributeToFilter('entity_id', ['in' => $arrAdminId]);
      return $customer->getData();
    }

    protected function _getAddressHtml($address)
    {
        /** @var \Magento\Customer\Block\Address\Renderer\RendererInterface $renderer */
        $renderer = $this->_addressConfig->getFormatByCode('html')->getRenderer();

        return $renderer->renderArray($this->addressMapper->toFlatArray($address));
    }
}
