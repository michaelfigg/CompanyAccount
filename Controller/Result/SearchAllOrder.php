<?php

namespace Tigren\CompanyAccount\Controller\Result;

use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Controller\Result\JsonFactory;

class SearchAllOrder extends \Magento\Framework\App\Action\Action
{
    /**
     * @var Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;
    protected $resultJsonFactory;
    protected $customerSession;
    protected $_orderCollectionFactory;
    protected $helper;
    protected $_orderConfig;
    protected $_registry;
    protected $orderRepository;
    protected $orderItemRepository;
    protected $addressRepository;
    protected $searchCriteriaBuilder;
    protected $filterBuilder;
    protected $filterGroupBuilder;


    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        JsonFactory $resultJsonFactory,
        \Magento\Customer\Model\CustomerFactory $customerFactory,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory,
        \Tigren\CompanyAccount\Helper\Data $helper,
        \Magento\Sales\Model\Order\Config $orderConfig,
        \Magento\Framework\Registry $registry,
        \Magento\Sales\Model\OrderRepository $orderRepository,
        \Magento\Sales\Model\Order\ItemRepository $orderItemRepository,
        \Magento\Sales\Model\Order\AddressRepository $addressRepository,
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder,
        \Magento\Framework\Api\FilterBuilder $filterBuilder,
        \Magento\Framework\Api\Search\FilterGroupBuilder $filterGroupBuilder
    ){
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->_customerFactory = $customerFactory;
        $this->customerSession = $customerSession;
        $this->_orderCollectionFactory = $orderCollectionFactory;
        $this->helper = $helper;
        $this->_orderConfig = $orderConfig;
        $this->_registry = $registry;
        $this->orderRepository = $orderRepository;
        $this->orderItemRepository = $orderItemRepository;
        $this->addressRepository = $addressRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->filterBuilder = $filterBuilder;
        $this->filterGroupBuilder = $filterGroupBuilder;
    }
    
    public function execute()
    {
        $search = $this->getRequest()->getParam('search');
        if (empty($search)) {
            return false;
        }
        $role = $this->getRequest()->getParam('role');
        $ordersByCustomer = $this->getOrdersByCustomer($search);
        $ordersByProduct = $this->getOrdersByProduct($search);
        $ordersByAddress = $this->getOrdersByAddress($search);
        $arrOrderId = array_unique(array_merge($ordersByCustomer, $ordersByProduct, $ordersByAddress));
        $orderSearch = $this->getOrderSearch($role, $arrOrderId);

        $this->_registry->register('orders-search-all', $orderSearch);

        $resultPage = $this->resultPageFactory->create();
        $responseTemplate = $resultPage->getLayout()
            ->createBlock('Tigren\CompanyAccount\Block\Order\Search\OrderAll')
            ->setTemplate('Tigren_CompanyAccount::result/ordersearch/searchorderall.phtml')
            ->toHtml();
        $result = $this->resultJsonFactory->create()->setData(['response' => $responseTemplate]);
        return $result;
    }

    public function getOrderSearch($role, $arrOrderId)
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
                    ->addFieldToFilter('entity_id', ['in' => $arrOrderId])
                    ->setOrder('created_at', 'desc');
            }
        } elseif ($role == 'myorder') {
            $orders = $this->_orderCollectionFactory->create()
                ->addFieldToSelect('*')
                ->addFieldToFilter('customer_id', ['eq' => $customerId])
                ->addFieldToFilter('status', ['in' => $this->_orderConfig->getVisibleOnFrontStatuses()])
                ->addFieldToFilter('entity_id', ['in' => $arrOrderId])
                ->setOrder('created_at', 'desc');
        }

        return $orders;
    }

    public function getOrdersByCustomer($search = null)
    {
        $filter1 = $this->filterBuilder
            ->setField('customer_email')
            ->setValue($search)
            ->setConditionType('eq')
            ->create();
        $filter2 = $this->filterBuilder
            ->setField('customer_firstname')
            ->setValue($search)
            ->setConditionType('eq')
            ->create();
        $filter3 = $this->filterBuilder
            ->setField('customer_lastname')
            ->setValue($search)
            ->setConditionType('eq')
            ->create();

        $filterGroup1 = $this->filterGroupBuilder->setFilters([$filter1, $filter2, $filter3])->create();

//        $filter5 = $this->filterBuilder
//            ->setField('status')
//            ->setValue('canceled')
//            ->setConditionType('neq')
//            ->create();

//        $filterGroup2 = $this->filterGroupBuilder->setFilters([$filter5])->create();

        $searchCriteriaBuilder = $this->searchCriteriaBuilder;
        $searchCriteriaBuilder->setFilterGroups([$filterGroup1]);

        $orderList = $this->orderRepository->getList($searchCriteriaBuilder->create());
        $arrOrder = [];
        foreach ($orderList as $order) {
            $arrOrder[] = $order->getId();
        }
        return $arrOrder;
    }

    public function getOrdersByProduct($search = null)
    {
        $filter1 = $this->filterBuilder
            ->setField('name')
            ->setValue('%' . $search . '%')
            ->setConditionType('like')
            ->create();
        $filter2 = $this->filterBuilder
            ->setField('sku')
            ->setValue('%' . $search . '%')
            ->setConditionType('like')
            ->create();

        $filterGroup1 = $this->filterGroupBuilder->setFilters([$filter1, $filter2])->create();

        $searchCriteriaBuilder = $this->searchCriteriaBuilder;
        $searchCriteriaBuilder->setFilterGroups([$filterGroup1]);

        $orderList = $this->orderItemRepository->getList($searchCriteriaBuilder->create());

        $arrOrder = [];
        foreach ($orderList as $order) {
            if (!in_array($order->getOrderId(), $arrOrder))
                $arrOrder[] = $order->getOrderId();
        }
        return $arrOrder;
    }

    public function getOrdersByAddress($search = null)
    {
        $filter1 = $this->filterBuilder
            ->setField('city')
            ->setValue($search)
            ->setConditionType('eq')
            ->create();
        $filter2 = $this->filterBuilder
            ->setField('street')
            ->setValue($search)
            ->setConditionType('eq')
            ->create();

        $filterGroup1 = $this->filterGroupBuilder->setFilters([$filter1, $filter2])->create();

        $searchCriteriaBuilder = $this->searchCriteriaBuilder;
        $searchCriteriaBuilder->setFilterGroups([$filterGroup1]);

        $orderList = $this->addressRepository->getList($searchCriteriaBuilder->create());

        $arrOrder = [];
        foreach ($orderList as $order) {
            if (!in_array($order->getParentId(), $arrOrder))
                $arrOrder[] = $order->getParentId();
        }
        return $arrOrder;
    }

    public function getCustomerId()
    {
        return $this->customerSession->getCustomer()->getId();
    }
}
