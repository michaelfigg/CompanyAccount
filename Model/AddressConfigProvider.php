<?php
namespace Tigren\CompanyAccount\Model;

use Magento\Checkout\Model\ConfigProviderInterface;
use function Sodium\add;

class AddressConfigProvider implements ConfigProviderInterface
{

    protected $_checkoutSession;
    protected $_logger;
    protected $_companyAccountAddressCollectionFactory;
    protected $_currentCustomer;
    protected $_companyAccountHelper;

    public function __construct(
        \Magento\Checkout\Model\Session $checkoutSession,
        \Psr\Log\LoggerInterface $logger,
        \Tigren\CompanyAccount\Model\ResourceModel\AccountAddress\CollectionFactory $companyAccountAddressCollectionFactory,
        \Magento\Customer\Helper\Session\CurrentCustomer $currentCustomer,
        \Tigren\CompanyAccount\Helper\Data $companyAccountHelper
    )
    {
        $this->_checkoutSession = $checkoutSession;
        $this->_logger = $logger;
        $this->_companyAccountAddressCollectionFactory = $companyAccountAddressCollectionFactory;
        $this->_currentCustomer = $currentCustomer;
        $this->_companyAccountHelper = $companyAccountHelper;
    }

    public function getConfig()
    {
        $companyAccountData = [];
        if($this->_currentCustomer->getCustomerId()){
            $accountId = $this->_companyAccountHelper->getAccountIdByCustomer($this->_currentCustomer->getCustomerId());
            if($accountId){
//                if($this->_companyAccountHelper->getCustomerEnableEnterShippingAddress($this->_currentCustomer->getCustomerId()) != '1'){
                    $collection = $this->_companyAccountAddressCollectionFactory
                        ->create()
                        ->addFieldToFilter('account_id', $accountId);
                    if($collection->getSize()){
                        foreach ($collection as $address){
                            $companyAccountData['companyAccountData'][] = [
                                'city' => $address->getCity(),
                                'company' => $address->getCompany(),
                                'country_id' => $address->getCountryId(),
                                'customerAddressId' => $this->_currentCustomer->getCustomerId(),
                                'customer_id' => $this->_currentCustomer->getCustomerId(),
                                'default_shipping' => 1,
                                'firstname' => $address->getFirstname(),
                                'id' => $address->getId(),
                                'inline' => $address->getFirstname().' '.$address->getLastname().', '.$address->getStreet(),
                                'lastname' => $address->getLastname(),
                                'postcode' => $address->getPostcode(),
                                'region' => [
                                    'region' => $address->getRegion(),
                                    'region_id' => $address->getRegionId()
                                ],
                                'region_id' => $address->getRegionId(),
                                'street' => [$address->getStreet()],
                                'telephone' => $address->getTelephone(),
                                'is_billing' => $address->getIsBilling()
                            ];
                        }
                    }
//                }
            }
        }
        return $companyAccountData;
    }
}
