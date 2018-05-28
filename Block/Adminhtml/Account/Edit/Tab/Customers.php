<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Tigren\CompanyAccount\Block\Adminhtml\Account\Edit\Tab;

class Customers extends \Magento\Backend\Block\Template
{
    /**
     * Block template
     *
     * @var string
     */
    protected $_template = 'account/edit/assign_customer.phtml';

    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * @var \Magento\Framework\Json\EncoderInterface
     */
    protected $jsonEncoder;

    /**
     * @var \Tigren\CompanyAccount\Block\Adminhtml\Account\Edit\Tab\CustomerGrid
     */
    protected $blockGrid;

    /**
     * @var \Tigren\CompanyAccount\Block\Adminhtml\Account\Edit\Tab\Customer\ActionCustomerTab
     */
    protected $actionCustomerTab;

    /**
     * @var \Tigren\CompanyAccount\Block\Adminhtml\Account\Edit\Tab\Customer\AssignActionCustomerTab
     */
    protected $assignActionCustomerTab;

    /**
     * @var \Tigren\CompanyAccount\Helper\Data
     */
    protected $helper;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        \Tigren\CompanyAccount\Helper\Data $helper,
        array $data = []
    ) {
        $this->registry = $registry;
        $this->jsonEncoder = $jsonEncoder;
        $this->helper = $helper;
        parent::__construct($context, $data);
    }

    /**
     * Retrieve instance of grid block
     *
     * @return \Magento\Framework\View\Element\BlockInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getBlockGrid()
    {
        if (null === $this->blockGrid) {
            $this->blockGrid = $this->getLayout()->createBlock(
                'Tigren\CompanyAccount\Block\Adminhtml\Account\Edit\Tab\CustomerGrid',
                'account.customers.grid'
            );
        }
        return $this->blockGrid;
    }

    /**
     * Retrieve current category instance
     *
     * @return array|null
     */
    public function getAccount()
    {
        return $this->registry->registry('companyaccount_account');
    }

    /**
     * @return string
     */
    public function getCustomersJson()
    {
        $customerAdmin = $this->helper->getCustomerAdmin(($this->getAccount()) ? $this->getAccount()->getId() : $this->getAccountId());
        if (!empty($customerAdmin)) {
            return $this->jsonEncoder->encode($customerAdmin);
        }
        return '{}';
    }

    /**
     * Return HTML of grid block
     *
     * @return string
     */
    public function getGridHtml()
    {
        return $this->getBlockGrid()->toHtml();
    }

    public function getActionCustomerTabBlock(){
        if (null === $this->actionCustomerTab) {
            $this->actionCustomerTab = $this->getLayout()->createBlock(
                'Tigren\CompanyAccount\Block\Adminhtml\Account\Edit\Tab\Customer\ActionCustomerTab',
                'account.customers.action.tab'
            );
            if(!$this->getAccount())
                $this->actionCustomerTab->setAccountId($this->getAccountId());
        }
        return $this->actionCustomerTab;
    }

}
