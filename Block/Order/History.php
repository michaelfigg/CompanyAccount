<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Tigren\CompanyAccount\Block\Order;

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
    protected $_customerRepositoryInterface;
    protected $profile;
    protected $_customerFactory;
    protected $info;
    protected $orderUser;

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
        \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Sales\Model\Order\Config $orderConfig,
        \Magento\Framework\App\ResourceConnection $resource,
        \Tigren\CompanyAccount\Helper\Data $helper,
        \Tigren\CompanyAccount\Block\Company\Profile $profile,
        \Magento\Customer\Model\CustomerFactory $customerFactory,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepositoryInterface,
        \Tigren\CompanyAccount\Block\Account\Dashboard\Info $info,
        \Tigren\CompanyAccount\Block\Order\User $orderUser,
        array $data = []
    )
    {
        $this->orderUser = $orderUser;
        $this->profile = $profile;
        $this->_orderCollectionFactory = $orderCollectionFactory;
        $this->_customerSession = $customerSession;
        $this->_orderConfig = $orderConfig;
        $this->_resource = $resource;
        $this->helper = $helper;
        $this->_customerFactory = $customerFactory;
        $this->_customerRepositoryInterface = $customerRepositoryInterface;
        $this->info = $info;
        parent::__construct($context, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->pageConfig->getTitle()->set(__('My Orders'));
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
     * @return $this|\Magento\Framework\View\Element\Template
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if ($this->getOrders()) {
            $pager = $this->getLayout()->createBlock(
                'Magento\Theme\Block\Html\Pager',
                'tigren.companyaccount.record.pager'
            )
                ->setAvailableLimit([10 => 10])
                ->setShowPerPage(true)
                ->setCollection($this->getOrders());
            $this->setChild('pager', $pager);
            $this->getOrders()->load();
        }
        return $this;
    }

    /**
     * @return bool|\Magento\Sales\Model\ResourceModel\Order\Collection
     */
    public function getOrders()
    {
        $order = null;
        if (!empty($this->getAccountId())) {
            $order = $this->getOrderAccount();
        } else {
            $order = $this->getOrdersUser();
        }
        return $order;
    }

    public function getOrderAccount()
    {
        if (!$this->orders) {
            $customerId = $this->_customerSession->getCustomerId();
            $listUsers = $this->helper->getAllUserOfAccount($customerId);
            if ($listUsers) {
                $this->orders = $this->_orderCollectionFactory->create()
                    ->addFieldToSelect('*')
                    ->addFieldToFilter('customer_id', ['in' => $listUsers])
                    ->addFieldToFilter('status', ['in' => $this->_orderConfig->getVisibleOnFrontStatuses()])
                    ->setOrder('created_at', 'desc');
            }
        }
        return $this->orders;
    }

    public function getAccountName()
    {
        $accountId = $this->helper->getAccountIdByCustomer($this->getCustomerId());
        $accountName = $this->helper->getAccountNameById($accountId);
        return $accountName;
    }

    public function getOrdersUser()
    {
        return $this->orderUser->getOrdersUser();
    }

    public function getAccountId()
    {
        return $this->helper->isInAvailableAccount($this->getCustomerId());
    }

    /**
     * @return int|null
     */
    public function getCustomerId()
    {
        return $this->_customerSession->getCustomerId();
    }
}
