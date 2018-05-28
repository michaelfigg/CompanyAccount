<?php

namespace Tigren\CompanyAccount\Observer\Customer;

use Magento\Framework\Event\ObserverInterface;

class CustomerLogin implements ObserverInterface
{
    protected $_customerRepositoryInterface;
    protected $_url;
    protected $_response;
    protected $_sessionManager;

    public function __construct(
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepositoryInterface,
        \Magento\Framework\App\ResponseFactory $_response,
        \Magento\Framework\App\ResponseFactory $responseFactory,
        \Magento\Framework\UrlInterface $url,
        \Magento\Framework\Session\SessionManagerInterface $sessionManager
    )
    {
        $this->_customerRepositoryInterface = $customerRepositoryInterface;
        $this->_response = $_response;
        $this->_responseFactory = $responseFactory;
        $this->_url = $url;
        $this->_sessionManager = $sessionManager;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $customerEvent = $observer->getEvent()->getCustomer();
        $customerId = $customerEvent->getId();
        $customer = $this->_customerRepositoryInterface->getById($customerId);
        if($customer->getCustomAttribute('pricerules_group') != null) {
            $attr = $customer->getCustomAttribute('pricerules_group')->getValue();
            $this->_sessionManager->setPricerulesGroup($attr);
        }
        if(empty($customer->getCustomAttribute('is_active'))){
            return $this;
        }
        $attr = $customer->getCustomAttribute('is_active')->getValue();
        if($attr == 2){
            $url = $this->_url->getUrl('*/*/logout');
            $this->_response->create()
                ->setRedirect($url)
                ->sendResponse();
            exit(0);
            return $this;
        }
        return $this;
    }
}
