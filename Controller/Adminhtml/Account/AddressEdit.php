<?php

namespace Tigren\CompanyAccount\Controller\Adminhtml\Account;

class AddressEdit extends \Magento\Backend\App\Action
{

    protected $_resultForwardFactory;
    protected $_datetime;
    protected $_accountAddressManagement;
    protected $_resultJsonFactory;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory,
        \Magento\Framework\Stdlib\DateTime\DateTime $dateTime,
        \Tigren\CompanyAccount\Api\AccountAddressManagementInterface $accountAddressManagement,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
    )
    {
        $this->_resultForwardFactory = $resultForwardFactory;
        $this->_datetime            = $dateTime;
        $this->_accountAddressManagement    = $accountAddressManagement;
        $this->_resultJsonFactory = $resultJsonFactory;
        parent::__construct($context);
    }


    public function execute()
    {
        $response = [];
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        $data = $this->getRequest()->getParams();
        if($data){
            try{
                $addressId = $this->getRequest()->getParam('address_id');
                $model = $this->_objectManager->create('\Tigren\CompanyAccount\Model\AccountAddress');
                $model->load($addressId);
                $response = $model->getData();
            }
            catch (\Exception $e){
                $response['error'] = 1;
                $this->messageManager->addError($e);
            }
        }

        return $this->_resultJsonFactory->create()->setData($response);
    }

}
