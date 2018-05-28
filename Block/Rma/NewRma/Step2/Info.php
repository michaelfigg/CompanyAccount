<?php
/**
 * Mirasvit
 *
 * This source file is subject to the Mirasvit Software License, which is available at https://mirasvit.com/license/.
 * Do not edit or add to this file if you wish to upgrade the to newer versions in the future.
 * If you wish to customize this module for your needs.
 * Please refer to http://www.magentocommerce.com for more information.
 *
 * @category  Mirasvit
 * @package   mirasvit/module-rma
 * @version   2.0.12
 * @copyright Copyright (C) 2017 Mirasvit (https://mirasvit.com/)
 */



namespace Tigren\CompanyAccount\Block\Rma\NewRma\Step2;

class Info extends \Mirasvit\Rma\Block\Rma\NewRma\Step2\Info
{
    /**
     * @var \Mirasvit\Rma\Helper\Controller\Rma\AbstractStrategy
     */
    protected $strategy;

    public function __construct(
        \Mirasvit\Rma\Helper\Controller\Rma\StrategyFactory $strategyFactory,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Sales\Model\Order\Address\Renderer $addressRenderer,
        \Magento\Framework\View\Element\Template\Context $context,
        array $data = []
    ) {
        $this->strategy        = $strategyFactory->create();
        $this->customerSession = $customerSession;
        $this->addressRenderer = $addressRenderer;
        $this->context         = $context;

        parent::__construct($strategyFactory,$customerSession,$addressRenderer,$context, $data);
    }

    /**
     * @throws \Magento\Framework\Exception\LocalizedException
     * @return void
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $this->pageConfig->getTitle()->set(__('Create RMA'));
        $pageMainTitle = $this->getLayout()->getBlock('page.main.title');
        if ($pageMainTitle) {
            if ($order = $this->getOrder()) {
                $pageMainTitle->setPageTitle(__('New Return for Order #%1', $order->getExtOrderId()));
            }
        }
    }

    /**
     * @return \Magento\Customer\Model\Customer
     */
    protected function getCustomer()
    {
        return $this->customerSession->getCustomer();
    }

    /**
     * @var \Magento\Sales\Model\Order
     */
    protected $order;

    /**
     * @return \Magento\Sales\Model\Order
     */
    public function getOrder()
    {
        if (!$this->order) {
            if ($orderId = $this->context->getRequest()->getParam('order_id')) {
                $items = $this->strategy->getAllowedOrderList($this->getCustomer());
                if (isset($items[$orderId])) {
                    $this->order = $items[$orderId];
                }
            }
        };

        return $this->order;
    }

    /**
     * Returns string with formatted address.
     *
     * @param \Magento\Sales\Model\Order\Address $address
     *
     * @return null|string
     */
    public function getFormattedAddress(\Magento\Sales\Model\Order\Address $address)
    {
        return $this->addressRenderer->format($address, 'html');
    }

}