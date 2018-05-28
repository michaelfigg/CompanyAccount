<?php

namespace Tigren\CompanyAccount\Controller\Adminhtml\Account;

class DeleteAddress extends \Magento\Backend\App\Action
{

    protected $resultForwardFactory;
    protected $_datetime;
    protected $_accountAddressManagement;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory,
        \Magento\Framework\Stdlib\DateTime\DateTime $dateTime,
        \Tigren\CompanyAccount\Api\AccountAddressManagementInterface $accountAddressManagement
    )
    {
        $this->resultForwardFactory = $resultForwardFactory;
        $this->_datetime            = $dateTime;
        $this->_accountAddressManagement    = $accountAddressManagement;
        parent::__construct($context);
    }


    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        $addressId = $this->getRequest()->getParam('address_id');
        if($addressId){
            try{
                $model = $this->_objectManager->create('\Tigren\CompanyAccount\Model\AccountAddress');
                $model->load($addressId);
                $model->delete();

                $this->messageManager->addSuccess('The address was deleted successfully');
                $resultRedirect->setPath('companyaccount/account/addresses/account_id/'. $this->getRequest()->getParam('account_id'));
            }
            catch (\Exception $e){
                $this->messageManager->addError($e);
                $resultRedirect->setPath('companyaccount/account/addresses/account_id/'. $this->getRequest()->getParam('account_id'));
            }
        }

        return $resultRedirect;
    }

}
