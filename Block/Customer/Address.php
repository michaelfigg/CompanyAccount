<?php

namespace Tigren\CompanyAccount\Block\Customer;

use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\View\Element\Template;

class Address extends Template
{
    protected $_customerCollectionFactory;
    protected $subAccounts;
    protected $helper;
    protected $_customerSession;
    protected $_companyAccountAddressCollectionFactory;
    protected $_addresses;
    protected $_billingAddressCompany;
    protected $_shippingAddressCompany;
    protected $_addressBook;
    protected $_countryFactory;

    public function __construct(
        Template\Context $context,
        \Magento\Customer\Model\ResourceModel\Customer\CollectionFactory $customerCollectionFactory,
        \Tigren\CompanyAccount\Helper\Data $helper,
        CustomerSession $customerSession,
        \Tigren\CompanyAccount\Model\ResourceModel\AccountAddress\CollectionFactory $companyAccountAddressCollectionFactory,
        \Tigren\CompanyAccount\Block\Account\Dashboard\Address $shippingAddressCompany,
        \Tigren\CompanyAccount\Block\Company\Profile $billingAddressCompany,
        \Magento\Customer\Block\Address\Book $addressBook,
        \Magento\Directory\Model\CountryFactory $countryFactory,
        array $data = []
    )
    {
        $this->_addressBook = $addressBook;
        $this->_billingAddressCompany = $billingAddressCompany;
        $this->_shippingAddressCompany = $shippingAddressCompany;
        $this->_customerCollectionFactory = $customerCollectionFactory;
        $this->helper = $helper;
        $this->_customerSession = $customerSession;
        $this->_companyAccountAddressCollectionFactory = $companyAccountAddressCollectionFactory;
        $this->_countryFactory = $countryFactory;
        parent::__construct($context, $data);
    }

    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');

    }

    protected function _construct()
    {
        parent::_construct();
        $this->pageConfig->getTitle()->set(__('My Company Address'));
    }

    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if ($this->getListAddress()) {
            $pager = $this->getLayout()->createBlock(
                'Magento\Theme\Block\Html\Pager',
                'tigren.companyaccount.record.pager'
            )
                ->setCollection($this->getListAddress());

            $this->setChild('pager', $pager);

            $this->getListAddress()->load();
        }
        return $this;
    }

    public function getListAddress()
    {
        $customerId = $this->_customerSession->getCustomerId();
        $accountId = $this->helper->getAccountIdByCustomer($customerId);
        if (!$this->_addresses) {
            $this->_addresses = $this->_companyAccountAddressCollectionFactory->create()
                ->addFieldToFilter('account_id', $accountId);
        }
        return $this->_addresses;
    }

    public function getActionUrl($action, $userId = null, $addressSort = null)
    {
        $actionUrl = $this->getUrl('companyaccount/account/' . $action);
        $url = $userId ? $actionUrl . '?id=' . $userId . '&&add=' . $addressSort : $actionUrl;
        return $url;
    }

    public function getCountryName($countryCode)
    {
        $country = $this->_countryFactory->create()->loadByCode($countryCode);
        return $country->getName();
    }

    public function getBillingAddressCompanyDefault()
    {
        return $this->_billingAddressCompany->getPrimaryBillingAddressHtml();
    }

    public function getShippingAddressCompanyDefault()
    {
        return $this->_shippingAddressCompany->getPrimaryShippingAddressHtml();
    }

    public function getDefaultBilling()
    {
        return $this->_addressBook->getDefaultBilling();
    }
}