<?php

namespace Tigren\CompanyAccount\Controller\Adminhtml\Account;

class NewAddress extends \Magento\Backend\App\Action
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

        $data = $this->getRequest()->getParams();

        if($data){
            try{
                $accountId = $this->getRequest()->getParam('account_id');
                $model = $this->accountAddressFactory->create();

                if($this->getRequest()->getParam('address_id')){
                    $model->load($this->getRequest()->getParam('address_id'));
                    $model->setData($this->getRequest()->getParams());
                    $model->setStreet($data['street'][0]. ' ' .$data['street'][1]);
                    $model->setCompany($data['address_company']);
                    $model->setAccountId($accountId);
                    $model->setUpdatedAt($this->_datetime->gmtDate());
                    if($data['region'] > 0)
                        $model->setRegionId($data['region']);
                    $model->save();
                    if(isset($data['is_billing']))
                        $this->_accountAddressManagement->changeToBillingAddress($model->getId());
                    else
                        $model->setIsBilling(0)->save();
                }else{
                    $model->setData($this->getRequest()->getParams());
                    $model->setStreet($data['street'][0]. ' ' .$data['street'][1]);
                    $model->setCompany($data['address_company']);
                    $model->setAccountId($accountId);
                    $model->setCreatedAt($this->_datetime->gmtDate());
                    $model->setUpdatedAt($this->_datetime->gmtDate());
                    if($data['region'] > 0)
                        $model->setRegionId($data['region']);
                    $model->setId(NULL)->save();

                    if(isset($data['is_billing']))
                        $this->_accountAddressManagement->changeToBillingAddress($model->getId());
                    else
                        $model->setIsBilling(0)->save();
                }

                $this->messageManager->addSuccess('The address was saved successfully');
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
