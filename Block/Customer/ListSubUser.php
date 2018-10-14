<?php

namespace Tigren\CompanyAccount\Block\Customer;

use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\View\Element\Template;

class ListSubUser extends Template
{
    protected $_template = 'listsub.phtml';
    protected $_customerCollectionFactory;
    protected $subAccounts;
    protected $helper;
    protected $_customerSession;
    protected $_subUsers;

    /**
     * @param Template\Context $context
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        \Magento\Customer\Model\ResourceModel\Customer\CollectionFactory $customerCollectionFactory,
        \Tigren\CompanyAccount\Helper\Data $helper,
        CustomerSession $customerSession,
        array $data = []
    ){
        parent::__construct($context, $data);
        $this->_customerCollectionFactory = $customerCollectionFactory;
        $this->helper = $helper;
        $this->_customerSession = $customerSession;
    }

    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }

    protected function _construct()
    {
        parent::_construct();
        $this->pageConfig->getTitle()->set(__('My Company Users'));
    }

    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if ($this->getListUser()) {
            $pager = $this
                ->getLayout()
                ->createBlock(
                    'Magento\Theme\Block\Html\Pager',
                    'tigren.companyaccount.record.pager'
                )->setCollection($this->getListUser());
            $this->setChild('pager', $pager);
            $this->getListUser()->load();
        }
        return $this;
    }

    public function getListUser()
    {
        $customerId = $this->_customerSession->getCustomerId();
        $accountId = $this->helper->getAccountIdByCustomer($customerId);
        if (!$this->_subUsers) {
            $this->_subUsers = $this->_customerCollectionFactory->create()
                ->addAttributeToSelect('*')
                ->addAttributeToFilter('account_id', $accountId);
            //->addAttributeToFilter('entity_id',['neq' => $customerId]);
        }
        return $this->_subUsers;
    }

    public function isAdminOfAccount($customerId)
    {
        return $this->helper->isAdminOfAccount($customerId);
    }

    public function getActionUrl($action, $userId = null)
    {
        $actionUrl = $this->getUrl('companyaccount/account/' . $action);
        return $userId ? "{$actionUrl}?id={$userId}" : $actionUrl;
    }
}