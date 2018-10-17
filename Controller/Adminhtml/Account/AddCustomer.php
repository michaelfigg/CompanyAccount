<?php
namespace Tigren\CompanyAccount\Controller\Adminhtml\Account;

class AddCustomer extends \Magento\Backend\App\Action
{
	protected $_resultPageFactory = false;
	protected $_resultPage;
	
	public function __construct(
		\Magento\Backend\App\Action\Context $context,
		\Magento\Framework\View\Result\PageFactory $resultPageFactory
	){
		parent::__construct($context);
		$this->_resultPageFactory = $resultPageFactory;
	}

	public function execute()
	{
		$accountId = $this->getRequest()->getParam('account_id');
		if (is_null($this->_resultPage)) {
            $this->_resultPage = $this->_resultPageFactory->create();
        }
        $this->_resultPage->setActiveMenu('Tigren_CompanyAccount::customers');
        $this->_resultPage->getConfig()->getTitle()->prepend((__('Add Customers To Account')));

        //Add bread crumb
        $this->_resultPage->addBreadcrumb(__('Tigren'), __('Tigren'));
        $this->_resultPage->addBreadcrumb(__('Company Account'), __('Add Customers To Account'));
        return $this->_resultPage;
	}

	/*
	 * Check permission via ACL resource
	 */
	protected function _isAllowed()
	{
		return $this->_authorization->isAllowed('Tigren_CompanyAccount::add_customer');
	}

}