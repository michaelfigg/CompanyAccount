<?php

namespace Tigren\CompanyAccount\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Customer\Model\Session as CustomerSession;

class RemoveBlock implements ObserverInterface
{
    protected $_customerSession;
    protected $helper;

    public function __construct(
        \Tigren\CompanyAccount\Helper\Data $helper,
        CustomerSession $customerSession
    )
    {
        $this->_customerSession = $customerSession;
        $this->helper = $helper;
    }

    public function execute(Observer $observer)
    {
        /** @var \Magento\Framework\View\Layout $layout */
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $customerSession = $objectManager->get('Magento\Customer\Model\Session');
        if($customerSession->isLoggedIn()) {
            if ($this->helper->isInAvailableAccount($this->getCustomerId())) {
                $layout = $observer->getLayout();
                $block = $layout->getBlock('customer-account-navigation-address-link');
                if($block){
                    $layout->unsetElement('customer-account-navigation-address-link');
                }
                return $this;
            }
        }
        return $this;
    }

    public function getCustomerId()
    {
        return $this->_customerSession->getCustomerId();
    }
}