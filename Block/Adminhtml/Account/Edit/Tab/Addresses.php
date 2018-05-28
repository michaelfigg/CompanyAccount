<?php

namespace Tigren\CompanyAccount\Block\Adminhtml\Account\Edit\Tab;

class Addresses extends \Magento\Directory\Block\Data
{

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Directory\Helper\Data $directoryHelper,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        \Magento\Framework\App\Cache\Type\Config $configCacheType,
        \Magento\Directory\Model\ResourceModel\Region\CollectionFactory $regionCollectionFactory,
        \Magento\Directory\Model\ResourceModel\Country\CollectionFactory $countryCollectionFactory,
        array $data = []
    )
    {
        parent::__construct(
            $context,
            $directoryHelper,
            $jsonEncoder,
            $configCacheType,
            $regionCollectionFactory,
            $countryCollectionFactory,
            $data
        );
    }

    public function getPostActionUrl()
    {
        if($this->getIdAddress())
            return $this->getUrl('companyaccount/account/addressEditPost');
        else
            return $this->getUrl('companyaccount/account/newAddress/');
    }

    public function getAccountId(){
        return $this->getRequest()->getParam('account_id');
    }

    public function getIdAddress(){
        return ($this->getRequest()->getParam('id')) ? $this->getRequest()->getParam('id') : null;
    }

    public function getAddressData(){
        return null;
    }

    public function getConfig($path)
    {
        return $this->_scopeConfig->getValue($path, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    public function getRegionId()
    {
        return 0;
    }
}

