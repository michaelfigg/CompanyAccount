<?php

namespace Tigren\CompanyAccount\Block\Customer\Address;

use Magento\Customer\Model\AccountManagement;
use Magento\Framework\View\Element\Template;
use Magento\Customer\Model\AttributeChecker;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\App\ObjectManager;

class Edit extends \Magento\Customer\Block\Address\Edit
{

    protected $_companyAddressFactory;

    public function __construct(
        Template\Context $context,
        \Tigren\CompanyAccount\Model\AccountAddressFactory $companyAddressFactory,
        array $data = [],
        \Magento\Directory\Helper\Data $directoryHelper,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        \Magento\Framework\App\Cache\Type\Config $configCacheType,
        \Magento\Directory\Model\ResourceModel\Region\CollectionFactory $regionCollectionFactory,
        \Magento\Directory\Model\ResourceModel\Country\CollectionFactory $countryCollectionFactory,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Customer\Api\AddressRepositoryInterface $addressRepository,
        \Magento\Customer\Api\Data\AddressInterfaceFactory $addressDataFactory,
        \Magento\Customer\Helper\Session\CurrentCustomer $currentCustomer,
        \Magento\Framework\Api\DataObjectHelper $dataObjectHelper,
        AttributeChecker $attributeChecker = null
    )
    {
        parent::__construct(
            $context,
            $directoryHelper,
            $jsonEncoder,
            $configCacheType,
            $regionCollectionFactory,
            $countryCollectionFactory,
            $customerSession,
            $addressRepository,
            $addressDataFactory,
            $currentCustomer,
            $dataObjectHelper,
            $data
        );
        $this->_companyAddressFactory = $companyAddressFactory;
    }

    public function getPostActionUrl()
    {
        if($this->getIdAddress())
            return $this->getUrl('companyaccount/account/addressEditPost');
        else
            return $this->getUrl('companyaccount/account/addressCreatePost');
    }

    public function getIdAddress(){
        return ($this->getRequest()->getParam('id')) ? $this->getRequest()->getParam('id') : null;
    }

    public function getAddressData(){
        if($this->getIdAddress()){
            $companyAccountAddress = $this->_companyAddressFactory->create()->load($this->getIdAddress());
            return $companyAccountAddress;
        }
        else
            return null;
    }

}
