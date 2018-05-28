<?php
/**
 * @copyright Copyright (c) 2016 www.tigren.com
 */

namespace Tigren\CompanyAccount\Block\Adminhtml\Account\Edit;

use Magento\Backend\Block\Template\Context;
use Magento\Backend\Model\Auth\Session;
use Magento\Framework\Json\EncoderInterface;
use Magento\Framework\Registry;
use Magento\Framework\Translate\InlineInterface;

class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    /**
     * @var InlineInterface
     */
    protected $_translateInline;

    /**
     * Core registry
     *
     * @var Registry
     */
    protected $_coreRegistry = null;

    public function __construct(
        Context $context,
        EncoderInterface $jsonEncoder,
        Session $authSession,
        Registry $registry,
        InlineInterface $translateInline,
        array $data = []
    )
    {
        $this->_coreRegistry = $registry;
        $this->_translateInline = $translateInline;
        parent::__construct($context, $jsonEncoder, $authSession, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('companyaccount_account_edit_tabs');
        $this->setDestElementId('edit_form');
    }

    protected function _prepareLayout()
    {
        $this->setTitle(__('Account'));
        $this->addTab(
            'main',
            [
                'label' => __('Information'),
                'content' => $this->getLayout()->createBlock(
                    'Tigren\CompanyAccount\Block\Adminhtml\Account\Edit\Tab\Info'
                )->toHtml()
            ]
        );
        $this->addTab(
            'customers',
            [
                'label' => __('Customers'),
                'content' => $this->getLayout()->createBlock(
                    'Tigren\CompanyAccount\Block\Adminhtml\Account\Edit\Tab\Customers'
                )->toHtml()
            ]
        );
        $model = $this->_coreRegistry->registry('companyaccount_account');
        if($model->getId()){
            $this->addTab(
                'addresses',
                [
                    'label' => __('Manage Addresses'),
                    'url' => $this->getUrl('companyaccount/account/addresses', ['_current' => true]),
                    'class' => 'ajax',
                ]
            );
        }

        return parent::_prepareLayout();
    }

    public function getAccount()
    {
        if (!$this->getData('companyaccount_account') instanceof \Tigren\CompanyAccount\Model\Account) {
            $this->setData('companyaccount_account', $this->_coreRegistry->registry('companyaccount_account'));
        }
        return $this->getData('companyaccount_account');
    }

    /**
     * Translate html content
     *
     * @param string $html
     * @return string
     */
    protected function _translateHtml($html)
    {
        $this->_translateInline->processResponseBody($html);
        return $html;
    }
}