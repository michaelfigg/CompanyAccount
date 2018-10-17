<?php

namespace Tigren\CompanyAccount\Controller\Adminhtml\Account;

class DeleteAddress extends \Magento\Backend\App\Action
{

    protected $resultForwardFactory;
    protected $_datetime;
    protected $_accountAddressManagement;
    private $accountAddressFactory;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory,
        \Magento\Framework\Stdlib\DateTime\DateTime $dateTime,
        \Tigren\CompanyAccount\Api\AccountAddressManagementInterface $accountAddressManagement,
        \Tigren\CompanyAccount\Model\AccountAddressFactory $accountAddressFactory
    ){
        parent::__construct($context);
        $this->resultForwardFactory = $resultForwardFactory;
        $this->_datetime = $dateTime;
        $this->_accountAddressManagement = $accountAddressManagement;
        $this->accountAddressFactory = $accountAddressFactory;
    }


    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        $addressId = $this->getRequest()->getParam('address_id');
        if($addressId){
            try{
                $model = $this->accountAddressFactory->create();
                $model->load($addressId);
                $model->delete();
                $this->messageManager->addSuccess('The address was deleted successfully');
            }
            catch (\Exception $e){
                $this->messageManager->addError($e);
            }
        }

        return $resultRedirect->setPath('companyaccount/account/addresses/account_id/'. $this->getRequest()->getParam('account_id'));;
    }

}
