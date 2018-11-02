<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Tigren\CompanyAccount\Block\Account\Dashboard;

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
class Address extends \Magento\Framework\View\Element\Template
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

    protected $Api;

    protected $accountFactory;


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
        \Tigren\CompanyAccount\Model\ApiConnection $Api,
        \Tigren\CompanyAccount\Model\AccountFactory $accountFactory,
        array $data = []
    ){
        parent::__construct($context, $data);
        $this->currentCustomer = $currentCustomer;
        $this->currentCustomerAddress = $currentCustomerAddress;
        $this->_addressConfig = $addressConfig;
        $this->addressMapper = $addressMapper;
        $this->addressFactory = $addressFactory;
        $this->_customerSession = $customerSession;
        $this->helper = $helper;
        $this->Api = $Api;
        $this->accountFactory = $accountFactory;
    }

    /**
     * Get the logged in customer
     *
     * @return \Magento\Customer\Api\Data\CustomerInterface|null
     */
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

    public function getAccountAddressBilling($addressId)
    {
        $collection = $this->addressFactory->create()->getCollection();
        $data = $collection->addFieldToFilter('address_id', ['in' => $addressId])->addFieldToFilter('is_billing', ['eq' => 1])->getData();
        return $data;
    }

    public function getAccountAddressShipping($addressId)
    {
        $collection = $this->addressFactory->create()->getCollection();
        $data = $collection->addFieldToFilter('address_id', ['in' => $addressId])
            ->addFieldToFilter('is_shipping_default', ['eq' => 1])
            ->setOrder('address_id', 'asc')->getFirstItem()->getData();
        return $data;
    }

    public function disableEdit()
    {
        return $this->helper->isInAvailableAccount($this->getCustomerId()) ? 1 : 0;
    }

    public function getAccountManager()
    {
        $accountId = $this->getAccountId();
        $infoManageAccount = $this->accountFactory->create()
            ->getCollection()
            ->addFieldToFilter('account_id', ['eq' => $accountId])
            ->getData();
        if (!empty($infoManageAccount[0]['manager_first_name']) && !empty($infoManageAccount[0]['manager_email'])) {
            $name = $infoManageAccount[0]['manager_first_name'] . ' ' . $infoManageAccount[0]['manager_last_name'];
            $managerInfo = '<div class="manager-image">';

            if (!empty($infoManageAccount[0]['manager_profile'])) {
                $managerInfo .= '<image src="' . $infoManageAccount[0]['manager_profile'] . '"/>';
            }
            $managerInfo .= '</div><div class="manager-info"><strong>Account Manager</strong>';
            $managerInfo .= "{$name}<br/>Email: {$infoManageAccount[0]['manager_email']}<br />";
            if(!empty($infoManageAccount[0]['manager_telephone'])){
                $managerInfo .= "Phone: {$infoManageAccount[0]['manager_telephone']}<br/>";
            }
            $managerInfo .= '</div>';
            return $managerInfo;
        } else {
            return 'No Contact';
        }
    }

    /**
     * HTML for Shipping Address
     *
     * @return \Magento\Framework\Phrase|string
     */
    public function getPrimaryShippingAddressHtml()
    {
        if ($this->helper->isInAvailableAccount($this->getCustomerId())) {
            $addressAccount = $this->getAccountAddressShipping($this->getIdAddressAccount());
            if (!empty($addressAccount)) {
                return "{$addressAccount['lastname']}  {$addressAccount['firstname']}</br>" .
                    "Street: {$addressAccount['street']}</br>" .
                    "City: {$addressAccount['city']}</br>" .
                    "Company: {$addressAccount['company']}</br>" .
                    "Zip/Postal Code: {$addressAccount['postcode']}</br>" .
                    "Telephone: {$addressAccount['telephone']}";
            }
            return __('Your company has not set a default shipping address.');
        }

        try {
            $address = $this->currentCustomerAddress->getDefaultShippingAddress();
        } catch (NoSuchEntityException $e) {
            return __('You have not set a default shipping address.');
        }

        if ($address) {
            return $this->_getAddressHtml($address);
        } else {
            return __('You have not set a default shipping address.');
        }
    }

    /**
     * HTML for Billing Address
     *
     * @return \Magento\Framework\Phrase|string
     */
    public function getPrimaryBillingAddressHtml()
    {
        if ($this->helper->isInAvailableAccount($this->getCustomerId())) {
            $addressAccount = $this->getAccountAddressBilling($this->getIdAddressAccount());
            if (!empty($addressAccount)) {
                return "{$addressAccount[0]['lastname']} {$addressAccount[0]['firstname']}</br>" .
                    "Street: {$addressAccount[0]['street']}</br>" .
                    "City: {$addressAccount[0]['city']}</br>" .
                    "Company: {$addressAccount[0]['company']}</br>" .
                    "Zip/Postal Code: {$addressAccount[0]['postcode']}</br>" .
                    "Telephone: {$addressAccount[0]['telephone']}";
            }
            return __('Your company has not set a default billing address.');
        }
        try {
            $address = $this->currentCustomerAddress->getDefaultBillingAddress();
        } catch (NoSuchEntityException $e) {
            return __('You have not set a default billing address.');
        }

        if ($address) {
            return $this->_getAddressHtml($address);
        } else {
            return __('You have not set a default billing address.');
        }
    }

    /**
     * @return string
     */
    public function getPrimaryShippingAddressEditUrl()
    {
        if (!$this->getCustomer()) {
            return '';
        }
        $address = $this->currentCustomerAddress->getDefaultShippingAddress();
        $addressId = $address ? $address->getId() : null;
        return $this->_urlBuilder->getUrl(
            'customer/address/edit',
            ['id' => $addressId]
        );
    }

    /**
     * @return string
     */
    public function getPrimaryBillingAddressEditUrl()
    {
        if (!$this->getCustomer()) {
            return '';
        }
        $address = $this->currentCustomerAddress->getDefaultBillingAddress();
        $addressId = $address ? $address->getId() : null;
        return $this->_urlBuilder->getUrl(
            'customer/address/edit',
            ['id' => $addressId]
        );
    }

    /**
     * @return string
     */
    public function getAddressBookUrl()
    {
        return $this->getUrl('companyaccount/account/address/');
    }

    /**
     * Render an address as HTML and return the result
     *
     * @param AddressInterface $address
     * @return string
     */
    protected function _getAddressHtml($address)
    {
        /** @var \Magento\Customer\Block\Address\Renderer\RendererInterface $renderer */
        $renderer = $this->_addressConfig->getFormatByCode('html')->getRenderer();
        return $renderer->renderArray($this->addressMapper->toFlatArray($address));
    }
}
