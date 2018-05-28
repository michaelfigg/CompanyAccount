<?php
/**
 * @copyright Copyright (c) 2016 www.tigren.com
 */
namespace Tigren\CompanyAccount\Controller\Adminhtml\Account;

class Delete extends \Magento\Backend\App\Action
{

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
                $model = $this->_objectManager->create('Tigren\CompanyAccount\Model\Account');
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
