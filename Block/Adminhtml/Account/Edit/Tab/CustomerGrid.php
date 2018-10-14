<?php
/**
 * @copyright Copyright (c) 2016 www.tigren.com
 */

namespace Tigren\CompanyAccount\Block\Adminhtml\Account\Edit\Tab;

use Magento\Backend\Block\Widget\Grid\Column;

class CustomerGrid extends \Magento\Backend\Block\Widget\Grid\Extended
{
    protected $_customers;
    protected $_accountFactory;
    protected $helper;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Magento\Customer\Model\Customer $customers,
        \Tigren\CompanyAccount\Model\AccountFactory $accountFactory,
        \Tigren\CompanyAccount\Helper\Data $helper,
        array $data = []
    ){
        parent::__construct($context, $backendHelper, $data);
        $this->_customers = $customers;
        $this->_accountFactory = $accountFactory;
        $this->helper = $helper;
    }

    /**
     * Set grid params
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('account_customer_grid');
        $this->setDefaultSort('entity_id');
        $this->setUseAjax(true);
    }

    /**
     * Prepare collection
     *
     * @return \Magento\Backend\Block\Widget\Grid\Extended
     */
    protected function _prepareCollection()
    {
        $unAvailableCustomers = $this->helper->getUnAvailableCustomers($this->getAccountId());

        $collection = $this->getCustomerCollection();
        if ($unAvailableCustomers) {
            $collection->addFieldToFilter('entity_id',['nin' => $unAvailableCustomers]);
        }
        if($this->getAccountId())
            $collection->addFieldToFilter('entity_id', ['in' => $this->helper->getCustomerIdsByAccount($this->getAccountId())]);
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * Add filter
     *
     * @param Column $column
     * @return $this
     */
    protected function _addColumnFilterToCollection($column)
    {
        // Set custom filter for in customer flag
        if ($column->getId() == 'in_customers') {
            $customerIds = $this->_getSelectedCustomerIds();
            if (empty($customerIds)) {
                $customerIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('entity_id', ['in' => $customerIds]);
            } else {
                if ($customerIds) {
                    $this->getCollection()->addFieldToFilter('entity_id', ['nin' => $customerIds]);
                }
            }
        } elseif ($column->getId() == 'is_admin') {
            $adminIds = $this->getSelectedAdminIds();
            if (empty($adminIds)) {
                $adminIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('entity_id', ['in' => $adminIds]);
            } else {
                if ($adminIds) {
                    $this->getCollection()->addFieldToFilter('entity_id', ['nin' => $adminIds]);
                }
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }
        return $this;
    }

    /**
     * Add columns to grid
     *
     * @return $this
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'in_customers',
            [
                'type' => 'checkbox',
                'name' => 'in_customers',
                'values' => $this->_getSelectedCustomerIds(),
                'align' => 'center',
                'index' => 'entity_id',
                'header_css_class' => 'col-select',
                'column_css_class' => 'col-select',
                'width' => 50
            ]
        );

        $this->addColumn(
            'entity_id',
            [
                'header' => __('ID'),
                'sortable' => true,
                'index' => 'entity_id',
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id'
            ]
        );

        $this->addColumn(
            'firstname',
            [
                'header' => __('First Name'),
                'sortable' => true,
                'index' => 'firstname',
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id'
            ]
        );

        $this->addColumn(
            'lastname',
            [
                'header' => __('Last Name'),
                'sortable' => true,
                'index' => 'lastname',
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id'
            ]
        );

        // $this->addColumn(
        //     'fullname',
        //     [
        //         'header' => __('Full name'),
        //         'index' => 'fullname',
        //         'renderer' => '\Tigren\CompanyAccount\Block\Adminhtml\Account\Grid\Column\Renderer\Fullname'
        //     ]
        // );
        
        $this->addColumn(
            'email',
            [
                'header' => __('Email'),
                'index' => 'email',
                'header_css_class' => 'col-email',
                'column_css_class' => 'col-email'
            ]
        );

        $this->addColumn(
            'is_admin',
            [
                'header' => __('Is Admin'),
                'name' => 'is_admin',
                'type' => 'checkbox',
                'index' => 'entity_id',
                'values' => $this->getSelectedAdminIds(),
                'disabled_values' => $this->_getUnselectedCustomerIds(),
                'editable' => true,
                'edit_only' => true,
                'header_css_class' => 'col-is_admin',
                'column_css_class' => 'col-is_admin',
                'use_index' => true
            ]
        );

        return parent::_prepareColumns();
    }

    /**
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('companyaccount/account/customergrid', ['_current' => true]);
    }

    public function getCustomerCollection()
    {
        return $this->_customers->getCollection();
    }

    public function _getSelectedCustomerIds()
    {
        $customers = $this->getRequest()->getPost('selected_customers');
        if ($customers === null) {
            $customers = array_keys($this->getSelectedCustomerIds());
        }
        return $customers;
    }

    /**
     * Retrieve selected items key
     *
     * @return array
     */
    public function getSelectedCustomerIds()
    {
        $accountId = $this->getRequest()->getParam('account_id');
        $account = $this->_accountFactory->create()->load($accountId);
        $customerIds = $account->getCustomerIds();

        if (!$customerIds) {
            return [];
        }

        $customerIdArr = [];
        foreach ($customerIds as $customerId) {
            $customerIdArr[$customerId] = ['id' => $customerId];
        }
        return $customerIdArr;
    }

    public function getSelectedAdminIds()
    {
        $account = $this->_accountFactory->create()->load($this->getAccountId());
        return $account->getAdminIds();
    }

    protected function _getUnselectedCustomerIds()
    {
        $customerIds = [];
        foreach ($this->getCustomerCollection()->getItems() as $item) {
            array_push($customerIds, $item->getId());
        }
        $selectedCustomerIds = $this->_getSelectedCustomerIds();
        $unSelectedCustomerIds = array_diff($customerIds, $selectedCustomerIds);
        return $unSelectedCustomerIds;
    }

    public function getAccountId()
    {
        return $this->getRequest()->getParam('account_id');
    }
}