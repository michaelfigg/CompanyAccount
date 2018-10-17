<?php
/**
 *
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Tigren\CompanyAccount\Controller\Order;

use Magento\Framework\App\Action;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Registry;

class Tracking extends Action\Action
{
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var \Magento\Sales\Model\OrderFactory
     */
    protected $orderFactory;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;
    protected $_customerSession;
    protected $helper;

    public function __construct(
        Action\Context $context,
        PageFactory $resultPageFactory,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Sales\Model\OrderFactory $orderFactory,
        Registry $registry,
        \Tigren\CompanyAccount\Helper\Data $helper
    ){
        parent::__construct($context);
        $this->orderFactory = $orderFactory;
        $this->resultPageFactory = $resultPageFactory;
        $this->_customerSession = $customerSession;
        $this->registry = $registry;
        $this->helper = $helper;
    }

    /**
     * Order view page
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {

        if (!$this->_customerSession->isLoggedIn()) {
            $this->_redirect('customer/account/login');
            return ;
        }
        $orderId = (int)$this->_request->getParam('order_id');
        if (!$orderId) {
            /** @var \Magento\Framework\Controller\Result\Forward $resultForward */
            $resultForward = $this->resultForwardFactory->create();
            return $resultForward->forward('noroute');
        }
        $order = $this->orderFactory->create()->load($orderId);
        $this->registry->register('current_order_tracking', $order);
        /** @var \Magento\Framework\View\Result\Page $resultPage */

        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->set(__('Order #'.$order->getExtOrderId()));
        $companyOrderBlock = $resultPage->getLayout()->getBlock('customer-users-links.order-users');
        if ($companyOrderBlock) {
            $companyOrderBlock->setIsHighlighted(true);
        }
        return $resultPage;
    }

    public function getCustomerId()
    {
        return $this->_customerSession->getCustomerId();
    }
}
