<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Tigren\CompanyAccount\Block\Customer\Widget;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

class Account extends \Magento\Framework\View\Element\Template
{

    protected $_accountManagement;

    public function __construct(
        Template\Context $context,
        \Tigren\CompanyAccount\Api\AccountManagementInterface $accountManagement,
        array $data = []
    ){
        parent::__construct($context, $data);
        $this->_accountManagement = $accountManagement;
    }

    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('customer/widget/account.phtml');
    }

    public function getAllAccount(){
        return $this->_accountManagement->getList();
    }
}