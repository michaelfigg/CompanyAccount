<?php

namespace Tigren\CompanyAccount\Block\Customer;

use Magento\Customer\Model\Session as CustomerSession;

class User extends \Magento\Framework\View\Element\Template
{
    /**
     * @var CustomerSession
     */
    protected $_customerSession;

    /**
     * @var \Tigren\CompanyAccount\Helper\Data
     */

    protected $helper;
    /**
     * @var \Magento\Customer\Api\CustomerRepositoryInterface
     */
    protected $_customerRepositoryInterface;

    /**
     * User constructor.
     * @param \Tigren\CompanyAccount\Helper\Data $helper
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param CustomerSession $customerSession
     * @param \Magento\Customer\Api\CustomerRepositoryInterface $customerRepositoryInterface
     * @param array $data
     */
    public function __construct(
        \Tigren\CompanyAccount\Helper\Data $helper,
        \Magento\Framework\View\Element\Template\Context $context,
        CustomerSession $customerSession,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepositoryInterface,
        array $data = []
    ){
        parent::__construct($context, $data);
        $this->_customerSession = $customerSession;
        $this->helper = $helper;
        $this->_customerRepositoryInterface = $customerRepositoryInterface;
    }

    /**
     * @return \Magento\Framework\View\Element\Template|void
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    protected function _prepareLayout()
    {
        if(!$this->_customerSession->isLoggedIn()){
            return;
        }
        $customer = $this->_customerRepositoryInterface->getById($this->getCustomerId());
        if (!empty($customer->getCustomAttribute('is_business'))) {
            $attrIsBusiness = $customer->getCustomAttribute('is_business')->getValue();
            if ($this->helper->isInAvailableAccount($this->getCustomerId()) && $attrIsBusiness == 1) {
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
                        'label' => 'My Addresses',
                        'path' => 'companyaccount/account/address'
                    ]
                );
            }

            if ($this->helper->isInAvailableAccount($this->getCustomerId()) && $attrIsBusiness == 0) {
                $this->addChild(
                    'company-address',
                    'Magento\Framework\View\Element\Html\Link\Current',
                    [
                        'label' => 'My Addresses',
                        'path' => 'companyaccount/account/address'
                    ]
                );
            }
        } elseif ($this->helper->isInAvailableAccount($this->getCustomerId())) {
            $this->addChild(
                'company-address',
                'Magento\Framework\View\Element\Html\Link\Current',
                [
                    'label' => 'My Addresses',
                    'path' => 'companyaccount/account/address'
                ]
            );
        }
        $this->addChild(
            'order-users',
            'Magento\Framework\View\Element\Html\Link\Current',
            [
                'label' => 'My Orders',
                'path' => 'companyaccount/order/history/'
            ]
        );
        parent::_prepareLayout();
    }

    /**
     * @return int|null
     */
    public function getCustomerId()
    {
        return $this->_customerSession->getCustomerId();
    }
}
