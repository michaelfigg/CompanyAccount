<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Tigren\CompanyAccount\Block\Customer;

use Magento\Customer\Model\Session as CustomerSession;

class User extends \Magento\Framework\View\Element\Template
{
    protected $_customerSession;
    protected $helper;
    protected $_customerRepositoryInterface;

    public function __construct(
        \Tigren\CompanyAccount\Helper\Data $helper,
        \Magento\Framework\View\Element\Template\Context $context,
        CustomerSession $customerSession,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepositoryInterface,
        array $data = []
    )
    {
        $this->_customerSession = $customerSession;
        $this->helper = $helper;
        $this->_customerRepositoryInterface = $customerRepositoryInterface;
        parent::__construct($context, $data);
    }

    protected function _prepareLayout()
    {
        $customer = $this->_customerRepositoryInterface->getById($this->getCustomerId());
        $attrIsBusiness = $customer->getCustomAttribute('is_business')->getValue();
        if ($this->helper->isInAvailableAccount($this->getCustomerId()) && $attrIsBusiness ==1) {
          $this->addChild(
                'company-profile',
                'Magento\Framework\View\Element\Html\Link\Current',
                [
                    'label' => 'Company Profile',
                    'path' => 'companyaccount/account/profile'
                ]
            );
            $this->addChild(
                'list-users',
                'Magento\Framework\View\Element\Html\Link\Current',
                [
                    'label' => 'Users',
                    'path' => 'companyaccount/account/listuser'
                ]
            );
            $this->addChild(
                'company-address',
                'Magento\Framework\View\Element\Html\Link\Current',
                [
                    'label' => 'Address Book',
                    'path' => 'companyaccount/account/address'
                ]
            );
            $this->addChild(
                'order-users',
                'Magento\Framework\View\Element\Html\Link\Current',
                [
                    'label' => 'Company Orders',
                    'path' => 'companyaccount/order/history'
                ]
            );
        }
        if ($this->helper->isInAvailableAccount($this->getCustomerId()) && $attrIsBusiness ==0) {
            $this->addChild(
                'company-address',
                'Magento\Framework\View\Element\Html\Link\Current',
                [
                    'label' => 'Address Book',
                    'path' => 'companyaccount/account/address'
                ]
            );
        }
        parent::_prepareLayout();
    }

    public function getCustomerId()
    {
        return $this->_customerSession->getCustomerId();
    }
}
