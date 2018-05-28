<?php
namespace Tigren\CompanyAccount\Block\Adminhtml\Account\Edit;

class Customers extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * constructor
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_controller = 'adminhtml_account_customers';
        $this->_blockGroup = 'Tigren_CompanyAccount';
        $this->_headerText = __('Choose Customers for Company Account');
        $this->_addButtonLabel = __('Add Customers To Account');
        parent::_construct();
    }

    /**
     * Prepare button and grid
     *
     * @return \Magento\Catalog\Block\Adminhtml\Product
     */
    protected function _prepareLayout()
    {

        $this->setChild(
            'grid',
            $this->getLayout()->createBlock('Tigren\CompanyAccount\Block\Adminhtml\Account\Customers\Grid',
                'tigren.companyaccount.customer.grid')
        );
        return parent::_prepareLayout();
    }

    /**
     *
     *
     * @return array
     */
    protected function _getAddButtonOptions()
    {

        $splitButtonOptions[] = [
            'label' => __('Add New'),
            'onclick' => "setLocation('" . $this->_getCreateUrl() . "')"
        ];

        return $splitButtonOptions;
    }

    /**
     * Render grid
     *
     * @return string
     */
    public function getGridHtml()
    {
        return $this->getChildHtml('grid');
    }
}