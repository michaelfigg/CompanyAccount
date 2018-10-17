<?php
/**
 * @copyright Copyright (c) 2016 www.tigren.com
 */
namespace Tigren\CompanyAccount\Controller\Adminhtml\Account;

class Delete extends \Magento\Backend\App\Action
{
    private $accountFactory;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Tigren\CompanyAccount\Model\AccountFactory $accountFactory
    ){
        parent::__construct($context);
        $this->accountFactory = $accountFactory;
    }
    /**
     * Delete action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('account_id');
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($id) {
            try {
                //TODO: Use of object manager
                $model = $this->accountFactory->create();
                $model->load($id);
                $model->delete();
                $this->messageManager->addSuccess(__('The account has been deleted.'));
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
                return $resultRedirect->setPath('*/*/edit', ['account_id' => $id]);
            }
        }
        $this->messageManager->addError(__('We can\'t find a account to delete.'));
        return $resultRedirect->setPath('*/*/');
    }

    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Tigren_CompanyAccount::delete');
    }

}
