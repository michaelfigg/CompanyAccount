<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Tigren\CompanyAccount\Block\Order;

/**
 * Sales order history block
 */
use Magento\Framework\Registry;

class Tracking extends \Magento\Framework\View\Element\Template
{
    protected $_customerSession;
    protected $registry;

    /**
     * History constructor.
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Sales\Model\Order\Config $orderConfig
     * @param \Magento\Framework\App\ResourceConnection $resource
     * @param \Tigren\CompanyAccount\Helper\Data $helper
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        Registry $registry,
        array $data = []
    ){
        parent::__construct($context, $data);
        $this->_customerSession = $customerSession;
        $this->registry = $registry;
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->pageConfig->getTitle()->set(__('Tracking Orders'));
    }

    public function getOrderTracking(){
        $arrTracking = [];
        $order =  $this->registry->registry('current_order_tracking');
        foreach ($order->getShipmentsCollection() as $shipment) {
            if(empty($shipment)) continue;
            foreach ($shipment->getAllTracks() as $tracking){
                $arrTracking[] = [
                    "title" => $tracking->getTitle(),
                    "number" => $tracking->getNumber(),
                    "carrier" => $tracking->getCarrierCode(),
                    "description" => $tracking->getDescription()
                ];
            }
        }
        return $arrTracking;
    }
    /**
     * @return string
     */
    public function getBackUrl()
    {
        $order =  $this->registry->registry('current_order_tracking');
        return $this->getUrl('companyaccount/order/view', ['order_id' => $order->getId()]);
    }

    /**
     * @return int|null
     */
    public function getCustomerId()
    {
        return $this->_customerSession->getCustomerId();
    }
}
