<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Customer Chooser 
 *
 * @author     Magento Core Team <core@magentocommerce.com>
 */
namespace Tigren\CompanyAccount\Block\Adminhtml\Account\Widget;

use Magento\Backend\Block\Widget\Grid;
use Magento\Backend\Block\Widget\Grid\Column;
use Magento\Backend\Block\Widget\Grid\Extended;
use Magento\Framework\Data\Form\Element\AbstractElement;

class Chooser extends Extended
{
    protected $_customers;
    protected $helper;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Magento\Customer\Model\Customer $customers,
        \Tigren\CompanyAccount\Helper\Data $helper,
        array $data = []
    )
    {
        $this->_customers = $customers;
        $this->helper = $helper;

        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * Block construction, prepare grid params
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setDefaultSort('entity_id');
        $this->setUseAjax(true);
    }

    /**
     * Prepare chooser element HTML
     *
     * @param AbstractElement $element Form Element
     * @return AbstractElement
     */
    public function prepareElementHtml(AbstractElement $element)
    {
        $element->setData('after_element_html', $this->_getAfterElementHtml($element));
        return $element;
    }

    public function _getAfterElementHtml($element)
    {
        $html = <<<HTML
    <style>
         .control .control-value {
            display: none !important;
        }
    </style>
HTML;

        $chooserHtml = $this->getLayout()
            ->createBlock('Tigren\CompanyAccount\Block\Adminhtml\Account\Widget\ChooserJs')
            ->setElement($element);

        $html .= $chooserHtml->toHtml();

        return $html;
    }


    public function getCheckboxCheckCallback()
    {
        return "function (grid, element) {
                $(grid.containerId).fire('banners:changed', {element: element});
            }";
    }


    // protected function _addColumnFilterToCollection($column)
    // {
    //     if ($column->getId() == 'in_banners') {
    //         $selected = $this->getSelectedBanners();
    //         if ($column->getFilter()->getValue()) {
    //             $this->getCollection()->addFieldToFilter('banner_id', ['in' => $selected]);
    //         } else {
    //             $this->getCollection()->addFieldToFilter('banner_id', ['nin' => $selected]);
    //         }
    //     } else {
    //         parent::_addColumnFilterToCollection($column);
    //     }
    //     return $this;
    // }

    /**
     * Prepare products collection, defined collection filters (category, product type)
     *
     * @return Extended
     */
    protected function _prepareCollection()
    {
        $collection = $this->getCustomerCollection()->addAttributeToSelect('*')
            ->addFieldToFilter('entity_id',['nin' => $this->helper->getUnAvailableCustomers()]);
        $this->setCollection($collection);
        return parent::_prepareCollection();
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
                // 'values' => $this->_getSelectedCustomerIds(),
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
                'type' => 'number',
                'index' => 'entity_id',
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id',
                'width' => 50
            ]
        );

        $this->addColumn(
            'fullname',
            [
                'header' => __('Full name'),
                'index' => 'fullname',
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
                // 'values' => $this->getSelectedAdminIds(),
                // 'disabled_values' => $this->_getUnselectedCustomerIds(),
                'editable' => true,
                'edit_only' => true,
                'header_css_class' => 'col-is_admin',
                'column_css_class' => 'col-is_admin',
                'width' => 50
            ]
        );

        return parent::_prepareColumns();
    }

    public function getCustomerCollection()
    {
        return $this->_customers->getCollection();
    }

    public function getGridUrl()
    {
        return $this->getUrl(
            'companyaccount/account_widget/chooser',
            [
                'customers_grid' => true,
                '_current' => true,
                'uniq_id' => $this->getId(),

            ]
        );
    }

    public function _getSelectedCustomerIds()
    {
        $customers = array_keys($this->getSelectedCustomerIds());
        return $customers;
    }

    /**
     * Retrieve selected items key
     *
     * @return array
     */
    public function getSelectedCustomerIds()
    {
        // $accountId = $this->getRequest()->getParam('account_id');
        // $account = $this->_accountFactory->create()->load($accountId);
        // $customerIds = $account->getCustomerIds();

        // if (!$customerIds) {
        //     return [];
        // }

        // $customerIdArr = [];

        // foreach ($customerIds as $customerId) {
        //     $customerIdArr[$customerId] = ['id' => $customerId];
        // }

        // return $customerIdArr;
        return [];
    }
}
