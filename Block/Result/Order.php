<?php
namespace Tigren\CompanyAccount\Block\Result;

use Magento\Framework\View\Element\Template;

class Order extends \Magento\Framework\View\Element\Template
{
    protected $_storeManager;
    public function __construct(
        Template\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        array $data = []
    ){
        parent::__construct($context, $data);
        $this->_storeManager = $storeManager;
    }

    public function getBaseUrl()
    {
        return $this->_storeManager->getStore()->getBaseUrl();
    }

    public function getOrderAdmin()
    {
        return $this->getAdminOrder();
    }

    public function getOrderUser()
    {
        return $this->getUserOrder();
    }
}