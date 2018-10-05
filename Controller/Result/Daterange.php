<?php

namespace Tigren\CompanyAccount\Controller\Result;

use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\ResultFactory;

class Daterange extends \Magento\Framework\App\Action\Action
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
        \Magento\Framework\Registry $registry
    )
    {

        $this->resultPageFactory = $resultPageFactory;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->resultFactory = $resultFactory;
        $this->_customerFactory = $customerFactory;
        $this->customerSession = $customerSession;
        $this->_orderCollectionFactory = $orderCollectionFactory;
        $this->helper = $helper;
        $this->_orderConfig = $orderConfig;
        $this->_registry = $registry;
        return parent::__construct($context);
    }


    public function execute()
    {
        $fromDate = $this->getRequest()->getParam('from_date');
        $toDate = $this->getRequest()->getParam('to_date');
        $role = $this->getRequest()->getParam('role');

        $createDateFrom = date_format(date_create($fromDate), "Y-m-d H:i:s");
        $createDateTo = date_format(date_create($toDate), "Y-m-d H:i:s");

        $orders = $this->getOrders($createDateFrom, $createDateTo, $role);
        $this->_registry->register('orders-date-range', $orders);

        $resultPage = $this->resultPageFactory->create();
        $responseTemplate = $resultPage->getLayout()
            ->createBlock('Tigren\CompanyAccount\Block\Order\Search\DateRange')
            ->setTemplate('Tigren_CompanyAccount::result/ordersearch/searchorder.phtml')
            ->toHtml();
        $result = $this->resultJsonFactory->create()->setData(['response' => $responseTemplate]);
        return $result;
    }

    public function getOrders($fromDate, $toDate, $role)
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
                    ->addFieldToFilter('created_at', ["from" => $fromDate, "to" => $toDate])
                    ->setOrder('created_at', 'desc');
            }
        } elseif ($role == 'myorder') {
            $orders = $this->_orderCollectionFactory->create()
                ->addFieldToSelect('*')
                ->addFieldToFilter('customer_id', ['eq' => $customerId])
                ->addFieldToFilter('status', ['in' => $this->_orderConfig->getVisibleOnFrontStatuses()])
                ->addFieldToFilter('created_at', ["from" => $fromDate, "to" => $toDate])
                ->setOrder('created_at', 'desc');
        }

        return $orders;
    }

    public function getCustomerId()
    {
        return $this->customerSession->getCustomer()->getId();
    }
}
