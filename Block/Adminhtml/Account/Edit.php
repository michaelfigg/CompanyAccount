<?php

/**
 * @copyright Copyright (c) 2016 www.tigren.com
 */

namespace Tigren\CompanyAccount\Block\Adminhtml\Account;

use Magento\Framework\UrlInterface;

class Edit extends \Magento\Backend\Block\Widget\Form\Container
{
    protected $urlBuilder;

    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        UrlInterface $urlBuilder,
        array $data = []
    ) {
        $this->urlBuilder = $urlBuilder;
        parent::__construct($context, $data);
    }

    /**
     * Retrieve text for header element depending on loaded blocklist
     *
     * @return \Magento\Framework\Phrase
     */
    public function getHeaderText()
    {
        $model = $this->_coreRegistry->registry('companyaccount_account');
        if ($model->getId()) {
            return __("Edit Account '%1'", $this->escapeHtml($model->getTitle()));
        } else {
            return __('New Account');
        }
    }

    /**
     * Initialize edit block
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_objectId = 'account_id';
        $this->_blockGroup = 'Tigren_CompanyAccount';
        $this->_controller = 'adminhtml_account';

        parent::_construct();

        if ($this->_isAllowedAction('Tigren_CompanyAccount::save')) {
            $this->buttonList->update('save', 'label', __('Save Account'));
            $this->buttonList->add(
                'saveandcontinue', [
                'label' => __('Save and Continue Edit'),
                'class' => 'save',
                'data_attribute' => [
                    'mage-init' => [
                        'button' => ['event' => 'saveAndContinueEdit', 'target' => '#edit_form'],
                    ],
                ]
            ], -100
            );
        } else {
            $this->buttonList->remove('save');
        }
        // if ($id = $this->getRequest()->getParam('id')) {
        //     $addCustomerUrl = $this->urlBuilder->getUrl(
        //         '*/*/addCustomer',
        //         [
        //             'id' => $id,
        //         ]
        //     );
        // } else {
        //     $addCustomerUrl = $this->urlBuilder->getUrl('*/*/addCustomer');
        // }

        // $this->buttonList->add('addcustomer', [
        //     'label' => __('Add Customers'),
        //     'class' => 'add',
        //     'onclick' => 'setLocation("' . $addCustomerUrl . '")'

        // ], -100);

        if ($this->_isAllowedAction('Tigren_CompanyAccount::account_delete')) {
            $this->buttonList->update('delete', 'label', __('Delete Account'));
        } else {
            $this->buttonList->remove('delete');
        }
    }

    /**
     * Check permission for passed action
     *
     * @param string $resourceId
     * @return bool
     */
    protected function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }

    /**
     * Getter of url for "Save and Continue" button
     * tab_id will be replaced by desired by JS later
     *
     * @return string
     */
    protected function _getSaveAndContinueUrl()
    {
        return $this->getUrl('companyaccount/*/save', ['_current' => true, 'back' => 'edit', 'active_tab' => '']);
    }

}
