<?php

namespace Tigren\CompanyAccount\Block\Adminhtml\Account\Edit\Tab\Customer\Assign;

use Magento\Backend\Block\Widget\Grid\Column;

class AssignActionCustomerTab extends \Magento\Backend\Block\Widget\Grid\Extended
{
    protected $_customers;
    protected $_accountFactory;
    protected $_helperCa;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Magento\Customer\Model\Customer $customers,
        \Tigren\CompanyAccount\Model\AccountFactory $accountFactory,
        \Tigren\CompanyAccount\Helper\Data $helperCa,
        array $data = []
    ){
        parent::__construct($context, $backendHelper, $data);
        $this->_customers = $customers;
        $this->_accountFactory = $accountFactory;
        $this->_helperCa = $helperCa;
    }

    protected function _construct()
    {
        parent::_construct();
        $this->setId('assign_account_customer_grid');
        $this->setDefaultSort('entity_id');
        $this->setUseAjax(true);
    }

    protected function _prepareCollection()
    {
        $collection = $this->getCustomerCollection();
        $collection->addFieldToFilter('entity_id', ['nin' => $this->_helperCa->getCustomersInAccount()]);
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _addColumnFilterToCollection($column)
    {
        parent::_addColumnFilterToCollection($column);
        return $this;
    }

    protected function _prepareColumns()
    {
        $this->addColumn(
            'in_assign_customers',
            [
                'type' => 'checkbox',
                'name' => 'in_assign_customers',
                'field_name' => 'selected_in_assign_customers[]',
                'align' => 'center',
                'index' => 'entity_id',
                'header_css_class' => 'col-select',
                'column_css_class' => 'col-select',
                'width' => 50
            ]
        );

        $this->addColumn(
            'assign_entity_id',
            [
                'header' => __('ID'),
                'sortable' => true,
                'index' => 'entity_id',
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id'
            ]
        );

        $this->addColumn(
            'assign_firstname',
            [
                'header' => __('First Name'),
                'sortable' => true,
                'index' => 'firstname',
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id'
            ]
        );

        $this->addColumn(
            'assign_lastname',
            [
                'header' => __('Last Name'),
                'sortable' => true,
                'index' => 'lastname',
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id'
            ]
        );

        $this->addColumn(
            'assign_email',
            [
                'header' => __('Email'),
                'index' => 'email',
                'header_css_class' => 'col-email',
                'column_css_class' => 'col-email'
            ]
        );

        return parent::_prepareColumns();
    }

    /**
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('companyaccount/account/assignCustomerGrid', ['_current' => true]);
    }

    public function getCustomerCollection()
    {
        return $this->_customers->getCollection();
    }


    public function getAccountId()
    {
        return $this->getRequest()->getParam('account_id');
    }

}