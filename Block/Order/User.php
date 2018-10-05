<?php

namespace Tigren\CompanyAccount\Block\Order;

class User extends \Magento\Sales\Block\Order\History
{
    protected $helper;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory,
        \Magento\Customer\Model\Session $customerSession,
        \Tigren\CompanyAccount\Helper\Data $helper,
        \Magento\Sales\Model\Order\Config $orderConfig, array $data = [])
    {
        $this->helper = $helper;
        parent::__construct($context, $orderCollectionFactory, $customerSession, $orderConfig, $data);
    }

    public function getOrdersUser()
    {
        if (!$this->orders) {
            $customerId = $this->_customerSession->getCustomerId();
            $this->orders = $this->_orderCollectionFactory->create()
                ->addFieldToSelect('*')
                ->addFieldToFilter('customer_id', ['eq' => $customerId])
                ->addFieldToFilter('status', ['in' => $this->_orderConfig->getVisibleOnFrontStatuses()])
                ->setOrder('created_at','desc');
        }
        return $this->orders;
    }
    /**
     * @param object $order
     * @return string
     */
    public function getViewUrl($order)
    {
        return $this->getUrl('companyaccount/order/view', ['order_id' => $order->getId()]);
    }
}