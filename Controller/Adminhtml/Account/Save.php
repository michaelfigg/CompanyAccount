<?php
/**
 * @copyright Copyright (c) 2016 www.tigren.com
 */
namespace Tigren\CompanyAccount\Controller\Adminhtml\Account;

use Magento\Backend\App\Action;
use Magento\Framework\App\Filesystem\DirectoryList;

class Save extends \Magento\Backend\App\Action
{
    protected $_customerFactory;
    protected $jsHelper;
    protected $jsonDecoder;

    public function __construct(
        Action\Context $context,
        \Magento\Backend\Helper\Js $jsHelper,
        \Magento\Framework\Json\DecoderInterface $jsonDecoder
    ) {
        $this->jsHelper = $jsHelper;
        $this->jsonDecoder = $jsonDecoder;
        parent::__construct($context);
    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            $account = $this->_objectManager->create('Tigren\CompanyAccount\Model\Account');
            $id = $this->getRequest()->getParam('account_id');
            if ($id) {
                $account->load($id);
                if ($id != $account->getId()) {
                    throw new \Magento\Framework\Exception\LocalizedException(__('The wrong account is specified'));
                }
            }
            if (isset($data['account_customers'])) {
                $customerAdmins = $this->jsonDecoder->decode($data['account_customers']);
                $data['customer_ids'] = array_keys($customerAdmins);
                $data['admin_ids'] = [];
                foreach ($customerAdmins as $key => $value) {
                    if ($value == 1) $data['admin_ids'][] = $key;
                }
            }
            $account->setData($data);
            try {
                $account->save();

                $this->_eventManager->dispatch(
                    'company_account_save_after',
                    ['request' => $data, 'account' => $account]
                );

                $this->messageManager->addSuccess(__('You saved this account'));
                $this->_objectManager->get('Magento\Backend\Model\Session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['account_id' => $account->getId(), '_current' => true]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the account'));
            }

            $this->_getSession()->setFormData($data);
            if ($id) {
                return $resultRedirect->setPath('*/*/edit', ['account_id' => $id, '_current' => true]);
            } else {
                return $resultRedirect->setPath('*/*/new', ['_current' => true]);
            }
        }
        return $resultRedirect->setPath('*/*/');
    }

    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Tigren_CustomerAccount::save');
    }

}
