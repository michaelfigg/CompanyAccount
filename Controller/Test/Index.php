<?php

namespace Tigren\CompanyAccount\Controller\Test;

use Magento\Backend\App\Action\Context;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\ResourceModel\Customer\CollectionFactory;
use Magento\Eav\Model\Entity\Collection\AbstractCollection;
use Magento\Framework\View\Result\PageFactory;
use Magento\Ui\Component\MassAction\Filter;


class Index extends \Magento\Framework\App\Action\Action
{
    protected $_accountAddressManagement;
    protected $_resultPageFactory;

    protected $orders;
    protected $_customerSession;
    protected $helper;
    protected $_orderCollectionFactory;
    protected $_orderConfig;

    protected $orderRepository;
    protected $orderItemRepository;
    protected $addressRepository;

    protected $searchCriteriaBuilder;
    protected $filterBuilder;
    protected $filterGroupBuilder;

    protected $_helperCategory;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Tigren\CompanyAccount\Api\AccountAddressManagementInterface $accountAddressManagement,
        \Magento\Customer\Model\Session $customerSession,
        \Tigren\CompanyAccount\Helper\Data $helper,
        \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory,
        \Magento\Sales\Model\Order\Config $orderConfig,

        \Magento\Sales\Model\OrderRepository $orderRepository,
        \Magento\Sales\Model\Order\ItemRepository $orderItemRepository,
        \Magento\Sales\Model\Order\AddressRepository $addressRepository,

        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder,
        \Magento\Framework\Api\FilterBuilder $filterBuilder,
        \Magento\Framework\Api\Search\FilterGroupBuilder $filterGroupBuilder,
        \Magento\Catalog\Helper\Category $catalogCategory
    ){
        parent::__construct($context);
        $this->_resultPageFactory = $resultPageFactory;
        $this->_accountAddressManagement = $accountAddressManagement;
        $this->_customerSession = $customerSession;
        $this->helper = $helper;
        $this->_orderCollectionFactory = $orderCollectionFactory;
        $this->_orderConfig = $orderConfig;
        $this->orderRepository = $orderRepository;
        $this->orderItemRepository = $orderItemRepository;
        $this->addressRepository = $addressRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->filterBuilder = $filterBuilder;
        $this->filterGroupBuilder = $filterGroupBuilder;
        $this->_helperCategory    = $catalogCategory;

    }

    public function execute()
    {
        $activeCategories = [];
        foreach ($this->getStoreCategories() as $child) {
            if ($child->getIsActive()) {
                $activeCategories[] = $child;
            }
        }
        var_dump($activeCategories);die;
    }

    public function getStoreCategories()
    {
        return $this->_helperCategory->getStoreCategories();
    }

    public function getOrders()
    {
        $filter1 = $this->filterBuilder
            ->setField('customer_email')
            ->setValue('test11@gmail.com')
            ->setConditionType('eq')
            ->create();
        $filter2 = $this->filterBuilder
            ->setField('customer_firstname')
            ->setValue('test11')
            ->setConditionType('eq')
            ->create();
        $filter3 = $this->filterBuilder
            ->setField('customer_lastname')
            ->setValue('pham')
            ->setConditionType('eq')
            ->create();

        $filterGroup1 = $this->filterGroupBuilder->setFilters([$filter1, $filter2, $filter3])->create();

        $filter5 = $this->filterBuilder
            ->setField('status')
            ->setValue('canceled')
            ->setConditionType('neq')
            ->create();

        $filterGroup2 = $this->filterGroupBuilder->setFilters([$filter5])->create();

        $searchCriteriaBuilder = $this->searchCriteriaBuilder;
        $searchCriteriaBuilder->setFilterGroups([$filterGroup1, $filterGroup2]);

        $orderList = $this->orderRepository->getList($searchCriteriaBuilder->create());

        return $orderList;
    }

    public function getOrdersByProduct()
    {
        $filter1 = $this->filterBuilder
            ->setField('name')
            ->setValue('%Workout%')
            ->setConditionType('like')
            ->create();
        $filter2 = $this->filterBuilder
            ->setField('sku')
            ->setValue('WS03-XS-Red')
            ->setConditionType('like')
            ->create();

        $filterGroup1 = $this->filterGroupBuilder->setFilters([$filter1, $filter2])->create();

        $searchCriteriaBuilder = $this->searchCriteriaBuilder;
        $searchCriteriaBuilder->setFilterGroups([$filterGroup1]);

        $orderList = $this->orderItemRepository->getList($searchCriteriaBuilder->create());

        return $orderList;
    }

    public function getOrdersByAddress()
    {
        $filter1 = $this->filterBuilder
            ->setField('city')
            ->setValue('%hanoi%')
            ->setConditionType('like')
            ->create();
        $filter2 = $this->filterBuilder
            ->setField('street')
            ->setValue('%Bluff%')
            ->setConditionType('like')
            ->create();

        $filterGroup1 = $this->filterGroupBuilder->setFilters([$filter1, $filter2])->create();

        $searchCriteriaBuilder = $this->searchCriteriaBuilder;
        $searchCriteriaBuilder->setFilterGroups([$filterGroup1]);

        $orderList = $this->addressRepository->getList($searchCriteriaBuilder->create());

        return $orderList;
    }
}
