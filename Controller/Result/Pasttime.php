<?php

namespace Tigren\CompanyAccount\Controller\Result;

use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\ResultFactory;

class Pasttime extends \Magento\Framework\App\Action\Action
{
    /**
     * @var Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    protected $resultJsonFactory;

    protected $resultFactory;

    protected $_customerFactory;

    protected $customerSession;

    protected $_orderCollectionFactory;

    protected $helper;

    protected $_orderConfig;

    protected $_registry;

    protected $date;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        JsonFactory $resultJsonFactory,
        ResultFactory $resultFactory,
        \Magento\Customer\Model\CustomerFactory $customerFactory,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory,
        \Tigren\CompanyAccount\Helper\Data $helper,
        \Magento\Sales\Model\Order\Config $orderConfig,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Stdlib\DateTime\DateTime $date
    ){
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->resultFactory = $resultFactory;
        $this->_customerFactory = $customerFactory;
        $this->customerSession = $customerSession;
        $this->_orderCollectionFactory = $orderCollectionFactory;
        $this->helper = $helper;
        $this->_orderConfig = $orderConfig;
        $this->_registry = $registry;
        $this->date = $date;
    }


    public function execute()
    {
        $value = $this->getRequest()->getParam('value');
        $role = $this->getRequest()->getParam('role');
        $date = date_create($this->getCurrentTime());
        if ($value == '6-moths') {
            $time = date_modify($date, "-6 months");
        } elseif ($value == '1-year') {
            $time = date_modify($date, "-1 years");
        } else {
            return false;
        }
        $timeEx = date_format($time, "Y-m-d H:i:s");
        $orders = $this->getOrderPastTime($role, $timeEx);
        $this->_registry->register('orders-past-time', $orders);

        $resultPage = $this->resultPageFactory->create();
        $responseTemplate = $resultPage->getLayout()
            ->createBlock('Tigren\CompanyAccount\Block\Order\Search\PastTime')
            ->setTemplate('Tigren_CompanyAccount::result/ordersearch/orderpasttime.phtml')
            ->toHtml();
        $result = $this->resultJsonFactory->create()->setData(['response' => $responseTemplate]);
        return $result;
    }

    public function getOrderPastTime($role, $timeEx)
    {
        $orders = null;
        $customerId = $this->getCustomerId();
        if ($role == 'companyorder') {
            $listUsers = $this->helper->getAllUserOfAccount($customerId);
            if ($listUsers) {
                $orders = $this->_orderCollectionFactory->create()
                    ->addFieldToSelect('*')
                    ->addFieldToFilter('customer_id', ['in' => $listUsers])
                    ->addFieldToFilter('status', ['in' => $this->_orderConfig->getVisibleOnFrontStatuses()])
                    ->addFieldToFilter('created_at', ["from" => $timeEx, "to" => $this->getCurrentTime()])
                    ->setOrder('created_at', 'desc');
            }
        } elseif ($role == 'myorder') {
            $orders = $this->_orderCollectionFactory->create()
                ->addFieldToSelect('*')
                ->addFieldToFilter('customer_id', ['eq' => $customerId])
                ->addFieldToFilter('status', ['in' => $this->_orderConfig->getVisibleOnFrontStatuses()])
                ->addFieldToFilter('created_at', ["from" => $timeEx, "to" => $this->getCurrentTime()])
                ->setOrder('created_at', 'desc');
        }
        return $orders;
    }

    public function getCurrentTime()
    {
        return $this->date->gmtDate();
    }

    public function getCustomerId()
    {
        return $this->customerSession->getCustomer()->getId();
    }
}
