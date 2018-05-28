<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Tigren\CompanyAccount\Block\Order;

use Magento\Sales\Model\ResourceModel\Order\CollectionFactoryInterface;

/**
 * Sales order history block
 */
class History extends \Magento\Framework\View\Element\Template
{
    protected $orders;
    protected $_orderCollectionFactory;
    protected $_parrent;
    protected $_customerSession;
    protected $_orderConfig;
    protected $_resource;
    protected $orderCollectionFactory;
    protected $helper;
    
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Sales\Model\Order\Config $orderConfig,
        \Magento\Framework\App\ResourceConnection $resource,
        \Tigren\CompanyAccount\Helper\Data $helper,
        array $data = []
    )
    {
        $this->_orderCollectionFactory = $orderCollectionFactory;
        $this->_customerSession = $customerSession;
        $this->_orderConfig = $orderConfig;
        $this->_resource = $resource;
        $this->helper = $helper;
        parent::__construct($context, $data);
    }

    /**
     * @return string
     */
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }

    /**
     * @param object $order
     * @return string
     */
    public function getViewUrl($order)
    {
        return $this->getUrl('companyaccount/order/view', ['order_id' => $order->getId()]);
    }

    /**
     * @return string
     */
    public function getBackUrl()
    {
        return $this->getUrl('customer/account/');
    }

    /**
     * @return $this
     */

    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if ($this->getorders()) {
            $pager = $this->getLayout()->createBlock(
                'Magento\Theme\Block\Html\Pager',
                'tigren.companyaccount.record.pager'
            )
                ->setAvailableLimit([10 => 10])
                ->setShowPerPage(true)
                ->setCollection($this->getOrders());

            $this->setChild('pager', $pager);

            $this->getorders()->load();
        }
        return $this;
    }

    /**
     * @return bool|\Magento\Sales\Model\ResourceModel\Order\Collection
     */
    public function getOrders()
    {
        if (!$this->orders) {
            $customerId = $this->_customerSession->getCustomerId();
            $listUsers = $this->helper->getAllUserOfAccount($customerId);

            if ($listUsers) {
                $this->orders = $this->_orderCollectionFactory->create()
                    ->addFieldToSelect('*')
                    ->addFieldToFilter('customer_id', ['in' => $listUsers])
                    ->addFieldToFilter('status', ['in' => $this->_orderConfig->getVisibleOnFrontStatuses()])
                    ->setOrder('created_at','desc');
            }
        }
        return $this->orders;
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->pageConfig->getTitle()->set(__('My Orders'));
    }


}
