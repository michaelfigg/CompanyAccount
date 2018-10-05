<?php
/**
 *
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Tigren\CompanyAccount\Controller\Order;

use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Sales\Controller\OrderInterface;

class History extends \Magento\Framework\App\Action\Action implements OrderInterface
{
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;
    protected $_customerSession;
    protected $helper;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        \Magento\Customer\Model\Session $customerSession,
        \Tigren\CompanyAccount\Helper\Data $helper
    )
    {
        $this->resultPageFactory = $resultPageFactory;
        $this->_customerSession = $customerSession;
        $this->helper = $helper;
        parent::__construct($context);
    }

    /**
     * Customer order history
     *
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        if (!$this->_customerSession->isLoggedIn()) {
            $this->_redirect('customer/account/login');
            return ;
        }
//        if (!$this->helper->isInAvailableAccount($this->getCustomerId())) {
//            $this->_redirect('customer/account');
//            return ;
//        }
        /** @var \Magento\Framework\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->set(__('My Orders'));
        return $resultPage;
    }

    public function getCustomerId()
    {
        return $this->_customerSession->getCustomerId();
    }
}
