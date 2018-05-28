<?php

namespace Tigren\CompanyAccount\Block\Adminhtml\Account\Edit\Tab\Customer;

class AssignActionCustomer extends \Magento\Backend\Block\Template
{
    protected $_template = 'account/edit/assign_customer/actions/assign.phtml';
    protected $_registry;
    protected $_jsonEncoder;
    protected $_helper;
    protected $_coreRegistry;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        \Tigren\CompanyAccount\Helper\Data $helper,
        array $data = []
    ) {
        $this->_registry = $registry;
        $this->_jsonEncoder = $jsonEncoder;
        $this->_helper = $helper;
        $this->_coreRegistry = $registry;
        parent::__construct($context, $data);
    }

    public function getAssignCustomerActionTabBlock(){
        $block = $this->getLayout()->createBlock(
            'Tigren\CompanyAccount\Block\Adminhtml\Account\Edit\Tab\Customer\Assign\AssignActionCustomerTab',
            'account.assign.customers.action.tab.detail'
        );
        if(!$this->getAccount())
            $block->setAccountId($this->getAccountId());

        return $block;
    }

    public function getAccount()
    {
        return $this->_coreRegistry->registry('companyaccount_account');
    }

}
