<?php

namespace Tigren\CompanyAccount\Plugin\Sales;

class OrderRepository
{
    /**
     * @var \Tigren\CompanyAccount\Helper\Data
     */
    protected $caHelper;

    /**
     * @var \Magento\Sales\Api\Data\OrderItemExtensionFactory
     */
    protected $orderItemExtensionFactory;

     /**
     * @var \Magento\Sales\Api\Data\OrderItemExtensionFactory
     */
    protected $orderExtensionFactory;

    /**
     * constructor.
     * @param \Tigren\CompanyAccount\Helper\Data $caHelper
     * @param \Magento\Sales\Api\Data\OrderItemExtensionFactory $orderItemExtensionFactory
     */
    public function __construct(
        \Tigren\CompanyAccount\Helper\Data $caHelper,
        \Magento\Sales\Api\Data\OrderItemExtensionFactory $orderItemExtensionFactory,
        \Magento\Sales\Api\Data\OrderExtensionFactory $orderExtensionFactory
    ){
        $this->caHelper = $caHelper;
        $this->orderItemExtensionFactory = $orderItemExtensionFactory;
        $this->orderExtensionFactory = $orderExtensionFactory;
    }

    public function afterGet(
        \Magento\Sales\Api\OrderRepositoryInterface $subject,
        \Magento\Sales\Api\Data\OrderInterface $order
    ){
        foreach ($order->getItems() as $item){
            $this->addExtensionDataForItem($item);
        }
        $this->addExtensionDataForOrder($order);
        return $order;
    }
    
    /**
     * @param \Magento\Sales\Api\Data\OrderItemInterface $item
     * @return self
     */
    private function addExtensionDataForItem(\Magento\Sales\Api\Data\OrderItemInterface $item)
    {
        $extensionAttributes = $item->getExtensionAttributes(); 
        if (empty($extensionAttributes)) {
            $extensionAttributes = $this->orderItemExtensionFactory->create();
        }
        $data = $this->caHelper->getExtensionDataOrderItem($item);
        if (isset($data['image'])) $extensionAttributes->setImage($data['image']);
        if (isset($data['manufacturer'])) $extensionAttributes->setManufacturer($data['manufacturer']);

        $item->setExtensionAttributes($extensionAttributes);
        return $this;
    }

    /**
     * @param \Magento\Sales\Api\Data\OrderInterface $order
     * @return self
     */
    private function addExtensionDataForOrder(\Magento\Sales\Api\Data\OrderInterface $order)
    {
        $extensionAttributes = $order->getExtensionAttributes(); 
        if (empty($extensionAttributes)) {
            $extensionAttributes = $this->orderExtensionFactory->create();
        }
        $accountId = $this->caHelper->getAccountIdByCustomer($order->getCustomerId());
        $extensionAttributes->setAccountId($accountId);
        $order->setExtensionAttributes($extensionAttributes);
        return $this;
    }

}