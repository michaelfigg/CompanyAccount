<?php
/**
 * @copyright Copyright (c) 2016 www.tigren.com
 */
namespace Tigren\CompanyAccount\Controller\Adminhtml\Account;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Index extends \Magento\Backend\App\Action
{
    protected $resultPageFactory;

    public function __construct(
        Context $context, 
        PageFactory $resultPageFactory
    )
    {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * Index action
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Tigren_CompanyAccount::manage_accounts');
        $resultPage->addBreadcrumb(__('Company Account'), __('Company Account'));
        $resultPage->addBreadcrumb(__('Manage Account'), __('Manage Account'));
        $resultPage->getConfig()->getTitle()->prepend(__('Manage Account'));

        return $resultPage;
    }

    /**
     * Is the user allowed to view the grid.
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Tigren_CompanyAccount::manage_accounts');
    }

}
