<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Tigren\CompanyAccount\Controller\Account;

use Magento\Backend\App\Action\Context;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\ResourceModel\Customer\CollectionFactory;
use Magento\Eav\Model\Entity\Collection\AbstractCollection;
use Magento\Framework\View\Result\PageFactory;
use Magento\Ui\Component\MassAction\Filter;


class Delete extends AccountAbstract
{
    protected $pageFactory;
    protected $customerFactory;
    protected $registry;
    protected $_messageManager;
    protected $_coreRegistry = null;
    protected $url;
    protected $resultRedirect;
    protected $helper;
    protected $_customerRepository;

    public function __construct(
        Context $context,
        PageFactory $pageFactory,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\UrlInterface $url,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Framework\Registry $registry,
        CustomerRepositoryInterface $customerRepository,
        \Magento\Customer\Model\CustomerFactory $customerFactory,
        \Tigren\CompanyAccount\Helper\Data $helper
    ) {
        $this->pageFactory = $pageFactory;
        $this->customerFactory = $customerFactory;
        $this->url = $url;
        $this->_messageManager = $messageManager;
        $this->registry = $registry;
        $this->helper = $helper;
        $this->_customerRepository = $customerRepository;
        parent::__construct($context,$customerSession,$pageFactory,$helper);

    }

    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $userId = $this->_request->getParam('id');
        $this->registry->register('isSecureArea', true);
        $customer = $this->customerFactory->create()->load($userId);
        try {
            $this->_customerRepository->deleteById($userId);
            $this->_messageManager->addSuccess(__('The user was removed successfully'));
        } catch (\Exception $e) {
            $this->_messageManager->addError(__('Something went wrong when remove this user .'));
        }
        return $resultRedirect->setUrl($this->url->getUrl('companyaccount/account/listuser'));
    }


}
