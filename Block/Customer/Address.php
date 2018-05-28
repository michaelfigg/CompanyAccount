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

    public function __construct(
        Template\Context $context,
        \Magento\Customer\Model\ResourceModel\Customer\CollectionFactory $customerCollectionFactory,
        \Tigren\CompanyAccount\Helper\Data $helper,
        CustomerSession $customerSession,
        \Tigren\CompanyAccount\Model\ResourceModel\AccountAddress\CollectionFactory $companyAccountAddressCollectionFactory,
        array $data = []
    ) {
        $this->_customerCollectionFactory = $customerCollectionFactory;
        $this->helper = $helper;
        $this->_customerSession = $customerSession;
        $this->_companyAccountAddressCollectionFactory = $companyAccountAddressCollectionFactory;
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
                ->addFieldToFilter('account_id',$accountId);
        }
        return $this->_addresses;
    }

    public function getActionUrl($action, $userId=null)
    {
        $actionUrl = $this->getUrl('companyaccount/account/'.$action);
        $url = $userId ? $actionUrl.'?id='.$userId : $actionUrl;
        return $url;
    }
}