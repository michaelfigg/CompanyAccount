<?php

/**
 * @copyright Copyright (c) 2016 www.tigren.com
 */

namespace Tigren\CompanyAccount\Block\Adminhtml\Account\Edit\Tab;

use Magento\Backend\Block\Widget\Form\Generic;

class Info extends Generic
{

    protected $_systemStore;
    protected $_companyaccountHelper;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context, 
            \Magento\Framework\Registry $registry, 
            \Magento\Framework\Data\FormFactory $formFactory, 
            \Magento\Store\Model\System\Store $systemStore, 
            \Tigren\CompanyAccount\Helper\Data $companyaccountHelper, 
            array $data = []
    )
    {
        $this->_systemStore = $systemStore;
        $this->_companyaccountHelper = $companyaccountHelper;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    protected function _prepareForm()
    {
        $account = $this->_coreRegistry->registry('companyaccount_account');
        $accountId = $account->getId();

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('account_');

        $fieldset = $form->addFieldset(
            'base_fieldset', ['legend' => __('Account Information'), 'class' => 'fieldset-wide']
        );

        if ($accountId) {  
            $fieldset->addField('account_id', 'hidden', ['name' => 'account_id']);
        }

        $fieldset->addField(
            'company', 'text', [
                'name' => 'company',
                'label' => __('Company'),
                'title' => __('Company'),
                'required' => true
            ]
        );

        $fieldset->addField(
            'logo_image_link', 'text', [
                'name' => 'logo_image_link',
                'label' => __('Logo Image'),
                'title' => __('Logo Image'),
            ]
        );

        $fieldset->addField(
            'telephone', 'text', [
                'name' => 'telephone',
                'label' => __('Telephone'),
                'title' => __('Telephone'),
            ]
        );

        $fieldset->addField(
            'tax', 'text', [
                'name' => 'tax',
                'label' => __('Tax Number'),
                'title' => __('Tax Number'),
            ]
        );
        
        if (!$this->_storeManager->hasSingleStore()) {
            $field = $fieldset->addField(
                'select_stores', 'multiselect', [
                    'label' => __('Store View'),
                    'required' => true,
                    'name' => 'stores[]',
                    'values' => $this->_systemStore->getStoreValuesForForm(false, true)
                ]
            );
            $renderer = $this->getLayout()->createBlock(
                'Magento\Backend\Block\Store\Switcher\Form\Renderer\Fieldset\Element'
            );
            $field->setRenderer($renderer);
            $account->setSelectStores($account->getStores());
        } else {
            $fieldset->addField(
                'select_stores', 'hidden', [
                    'name' => 'stores[]',
                    'value' => $this->_storeManager->getStore(true)->getId()
                ]
            );
            $account->setSelectStores($this->_storeManager->getStore(true)->getId());
        }

        $form->setValues($account->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }

}
