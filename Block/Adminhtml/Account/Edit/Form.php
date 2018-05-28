<?php
/**
 * @copyright Copyright (c) 2016 www.tigren.com
 */

namespace Tigren\CompanyAccount\Block\Adminhtml\Account\Edit;

/**
 * Adminhtml edit form
 */
class Form extends \Magento\Backend\Block\Widget\Form\Generic
{
    /**
     * Init form
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('account_form');
        $this->setTitle(__('Account Information'));
    }

    /**
     * Prepare form
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create(
            [
                'data' => [
                    'id' => 'edit_form',
                    'class' => 'admin__scope-old',
                    'action' => $this->getUrl('companyaccount/account/save'),
                    'method' => 'post',
                    'enctype' => 'multipart/form-data'
                ],
            ]
        );
        $form->setUseContainer(true);
        $this->setForm($form);
        return parent::_prepareForm();
    }
}