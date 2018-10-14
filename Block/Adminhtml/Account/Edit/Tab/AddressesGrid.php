<?php

namespace Tigren\CompanyAccount\Block\Adminhtml\Account\Edit\Tab;

class AddressesGrid extends \Magento\Backend\Block\Widget\Grid\Extended
{
    protected $_addressCollectionFactory;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Tigren\CompanyAccount\Model\ResourceModel\AccountAddress\CollectionFactory $addressCollectionFactory,
        array $data = []
    ){
        parent::__construct($context, $backendHelper, $data);
        $this->_addressCollectionFactory = $addressCollectionFactory;
    }

    /**
     * Set grid params
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('addresses_grid');
        $this->setDefaultSort('address_id');
        $this->setUseAjax(true);
        $this->setRowClickCallback('rowAddressCallBack');
    }

    public function getAccountId()
    {
        return $this->getRequest()->getParam('account_id');
    }

    public function getAddressesCollection()
    {
        return $this->_addressCollectionFactory->create()->addFieldToFilter('account_id', $this->getAccountId());
    }

    /**
     * Prepare collection
     *
     * @return \Magento\Backend\Block\Widget\Grid\Extended
     */
    protected function _prepareCollection()
    {
        $collection = $this->getAddressesCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }



    protected function _addColumnFilterToCollection($column)
    {
        parent::_addColumnFilterToCollection($column);
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
            'address_edit_id',
            [
                'type' => 'radio',
                'html_name' => 'address_edit_id',
                'align' => 'center',
                'index' => 'address_id',
                'header_css_class' => 'no-display',
                'column_css_class' => 'no-display',
                'width' => 50
            ]
        );

        $this->addColumn(
            'address_id',
            [
                'header' => __('ID'),
                'sortable' => true,
                'type' => 'number',
                'index' => 'address_id',
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id',
                'width' => 50
            ]
        );

        $this->addColumn(
            'firstname',
            [
                'header' => __('First name'),
                'index' => 'firstname',
            ]
        );

        $this->addColumn(
            'lastname',
            [
                'header' => __('Last name'),
                'index' => 'lastname',
            ]
        );

        $this->addColumn(
            'company',
            [
                'header' => __('Company'),
                'index' => 'company'
            ]
        );

         $this->addColumn(
             'is_billing',
             [
                 'header' => __('Is Billing'),
                 'index' => 'is_billing',
                 'filter' => false,
                 'renderer' => '\Tigren\CompanyAccount\Block\Adminhtml\Account\Edit\Tab\AddressGrid\Column\Renderer\IsBilling'
             ]
         );

        $this->addColumn(
            'action',
            [
                'header'    => __('Action'),
                'type'      => 'action',
                'actions'   => [
                    [
                        'caption' => 'Edit',
                    ]
                ],
                'filter'    => false,
                'sortable'  => false
            ]
        );

        return parent::_prepareColumns();
    }

    public function getRowUrl($item)
    {
        return $this->getUrl('companyaccount/account/addressEdit/address_id/' . $item->getId());
    }

    public function getGridUrl()
    {
        return $this->getUrl('companyaccount/account/addressGrid/', ['account_id' => $this->getRequest()->getParam('account_id')]);
    }
}