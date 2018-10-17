<?php
/**
 * @copyright Copyright (c) 2016 www.tigren.com
 */
namespace Tigren\CompanyAccount\Controller\Adminhtml\Account;

class Edit extends \Magento\Backend\App\Action
{
    protected $_coreRegistry = null;
    protected $resultPageFactory;
    private $accountFactory;
    private $session;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Registry $registry,
        \Tigren\CompanyAccount\Model\AccountFactory $accountFactory,
        \Magento\Backend\Model\Session $session
    ){
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->_coreRegistry = $registry;
        $this->accountFactory = $accountFactory;
        $this->session = $session;
    }

    /**
     * Edit Action
     *
     * @return \Magento\Backend\Model\View\Result\Page|\Magento\Backend\Model\View\Result\Redirect
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('account_id');
        $account = $this->accountFactory->create();

        if ($id) {
            $account->load($id);

            if (!$account->getId()) {
                $this->messageManager->addError(__('This account no longer exists.'));
                /** \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }

        $data = $this->session->getFormData(true);
        if (!empty($data)) {
            $account->setData($data);
        }

        $this->_coreRegistry->register('companyaccount_account', $account);

        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->_initAction();
        $resultPage->addBreadcrumb(
            $id ? __('Edit Account') : __('New Account'), $id ? __('Edit Account') : __('New Account')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Company Accounts'));
        $resultPage->getConfig()->getTitle()
            ->prepend($account->getId() ? __('Edit Account ') . $account->getTitle() : __('New Account'));

        return $resultPage;
    }

    /**
     * Init actions
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    protected function _initAction()
    {
        // load layout, set active menu and breadcrumbs
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Tigren_CompanyAccount::manage_accounts')
            ->addBreadcrumb(__('Company Accounts'), __('Company Accounts'))
            ->addBreadcrumb(__('Manage Accounts'), __('Manage Accounts'));
        return $resultPage;
    }

    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Tigren_CompanyAccount::save');
    }

}
