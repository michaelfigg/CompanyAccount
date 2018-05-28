<?php
/**
 * @copyright Copyright (c) 2016 www.tigren.com
 */

namespace Tigren\CompanyAccount\Block\Adminhtml\Account\Customers;

class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{

    protected $_customers;
    protected $helper;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Magento\Customer\Model\Customer $customers,
        \Tigren\CompanyAccount\Helper\Data $helper,
        array $data = []
    ) {
        $this->_customers = $customers;
        $this->helper = $helper;

        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('listsGrid');
        $this->setDefaultSort('id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
        $this->setVarNameFilter('lists_filter');
    }

    /**
     * Prepare collection
     *
     * @return \Magento\Backend\Block\Widget\Grid\Extended
     */
    protected function _prepareCollection()
    {
        $collection = $this->getCustomerCollection()->addAttributeToSelect('*')
            ->addFieldToFilter('entity_id',['nin' => $this->helper->getUnAvailableCustomers()]);
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * @return $this
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'in_customers',
            [
                'type' => 'checkbox',
                'name' => 'customer_account',
                'index' => 'entity_id',
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
                'type' => 'number',
                'index' => 'entity_id',
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id',
                'name'=>'entity_id',
                'width' => '215px',
            ]
        );
        $this->addColumn(
            'full_name',
            [
                'header' => __('Full name'),
                'name'=>'full_name',
                'renderer' => '\Tigren\CompanyAccount\Block\Adminhtml\Account\Grid\Column\Renderer\Fullname'
            ]
        );
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
                'editable' => true,
                'edit_only' => true,
                'header_css_class' => 'col-is_admin',
                'column_css_class' => 'col-is_admin',
                'width' => 50
            ]
        );
    }

    public function getCustomerCollection()
    {
        return $this->_customers->getCollection();
    }

    /**
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('companyaccount/*/grid', ['_current' => true]);
    }

}