<?php

/**
 * @copyright Copyright (c) 2016 www.tigren.com
 */

namespace Tigren\CompanyAccount\Block\Adminhtml\Account\Edit\Tab;

class Info extends \Magento\Backend\Block\Widget\Form\Generic
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
    ){
        parent::__construct($context, $registry, $formFactory, $data);
        $this->_systemStore = $systemStore;
        $this->_companyaccountHelper = $companyaccountHelper;
    }

    protected function _prepareForm()
    {
        $account = $this->_coreRegistry->registry('companyaccount_account');
        $accountId = $account->getId();

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('account_');

        $fieldset = $form->addFieldset(
            'base_fieldset',
            [
                'legend' => __('Account Information'),
                'class' => 'fieldset-wide'
            ]
        );

        if ($accountId) {  
            $fieldset->addField('account_id', 'hidden', ['name' => 'account_id']);
        }

        $fieldset->addField(
            'company',
            'text',
            [
                'name' => 'company',
                'label' => __('Company'),
                'title' => __('Company'),
                'required' => true
            ]
        );

        $fieldset->addField(
            'logo_image_link',
            'text',
            [
                'name' => 'logo_image_link',
                'label' => __('Logo Image'),
                'title' => __('Logo Image'),
            ]
        );

        $fieldset->addField(
            'telephone',
            'text',
            [
                'name' => 'telephone',
                'label' => __('Telephone'),
                'title' => __('Telephone'),
            ]
        );

        $fieldset->addField(
            'tax', 'text',
            [
                'name' => 'tax',
                'label' => __('Tax Number'),
                'title' => __('Tax Number'),
            ]
        );

        $fieldset->addField(
            'pay_on_account',
            'select',
            [
                'name' => 'pay_on_account',
                'label' => __('Pay On Account'),
                'title' => __('Pay On Account'),
                'values' => [
                    ['value' => '0', 'label' => 'Disable'],
                    ['value' => '1', 'label' => 'Enable']
                ],
            ]
        );

        $fieldset->addField(
            'public_notes',
            'text',
            [
                'name' => 'public_notes',
                'label' => __('Public Notes'),
                'title' => __('Public Notes'),
            ]
        );

        $fieldset->addField(
            'manager_first_name',
            'text',
            [
                'name' => 'manager_first_name',
                'label' => __('Manager First Name'),
                'title' => __('Manager First Name'),
            ]
        );

        $fieldset->addField(
            'manager_last_name',
            'text',
            [
                'name' => 'manager_last_name',
                'label' => __('Manager Last Name'),
                'title' => __('Manager Last Name'),
            ]
        );

        $fieldset->addField(
            'manager_telephone',
            'text',
            [
                'name' => 'manager_telephone',
                'label' => __('Manager Telephone'),
                'title' => __('Manager Telephone'),
            ]
        );

        $fieldset->addField(
            'manager_email',
            'text',
            [
                'name' => 'manager_email',
                'label' => __('Manager Email'),
                'title' => __('Manager Email'),
            ]
        );

        $fieldset->addField(
            'manager_profile',
            'text',
            [
                'name' => 'manager_profile',
                'label' => __('Manager Profile Image Link'),
                'title' => __('Manager Profile Image Link'),
            ]
        );

        $fieldset->addField(
            'credit_limit', 'text', [
                'name' => 'credit_limit',
                'label' => __('Credit Limit'),
                'title' => __('Credit Limit'),
            ]
        );

        $fieldset->addField(
            'credit_terms', 'text', [
                'name' => 'credit_terms',
                'label' => __('Credit Terms'),
                'title' => __('Credit Terms'),
            ]
        );

        $fieldset->addField(
            'balance', 'text', [
                'name' => 'balance',
                'label' => __('Balance'),
                'title' => __('Balance'),
            ]
        );

        $fieldset->addField(
            'account_number', 'text', [
                'name' => 'account_number',
                'label' => __('Account Number'),
                'title' => __('Account Number'),
            ]
        );

        $fieldset->addField(
            'portal_source', 'text', [
                'name' => 'portal_source',
                'label' => __('Portal Source'),
                'title' => __('Portal Source'),
            ]
        );

        $fieldset->addField(
            'portal_username', 'text', [
                'name' => 'portal_username',
                'label' => __('Portal Username'),
                'title' => __('Portal Username'),
            ]
        );
        
        if (!$this->_storeManager->hasSingleStore()) {
            $field = $fieldset->addField(
                'select_stores',
                'multiselect',
                [
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
                'select_stores',
                'hidden',
                [
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
