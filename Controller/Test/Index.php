<?php

namespace Tigren\CompanyAccount\Controller\Test;

use Magento\Backend\App\Action\Context;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\ResourceModel\Customer\CollectionFactory;
use Magento\Eav\Model\Entity\Collection\AbstractCollection;
use Magento\Framework\View\Result\PageFactory;
use Magento\Ui\Component\MassAction\Filter;


class Index extends \Magento\Framework\App\Action\Action
{
    protected $_accountAddressManagement;
    protected $_resultPageFactory;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Tigren\CompanyAccount\Api\AccountAddressManagementInterface $accountAddressManagement
    ) {
        parent::__construct($context);
        $this->_resultPageFactory       = $resultPageFactory;
        $this->_accountAddressManagement = $accountAddressManagement;

    }

    public function execute()
    {

    }


}
