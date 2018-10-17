<?php

namespace Tigren\CompanyAccount\Controller\Result;

use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\ResultFactory;

class Result extends \Magento\Framework\App\Action\Action
{
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;
    protected $resultJsonFactory;
    protected $resultFactory;
    protected $_customerFactory;

    /**
     * Result constructor.
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param JsonFactory $resultJsonFactory
     * @param ResultFactory $resultFactory
     * @param \Magento\Customer\Model\CustomerFactory $customerFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        JsonFactory $resultJsonFactory,
        ResultFactory $resultFactory,
        \Magento\Customer\Model\CustomerFactory $customerFactory
    ){
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->resultFactory = $resultFactory;
        $this->_customerFactory = $customerFactory;
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $adminId = $this->getRequest()->getParam('value');
        $Admin = $this->_customerFactory->create()
            ->getCollection()
            ->addAttributeToFilter('entity_id', ['in' => $adminId])
            ->setOrder('entity_id', 'asc')
            ->getFirstItem()
            ->getData();
        $response = $Admin['lastname']." ".$Admin['firstname']."<br/>"
        .$Admin['email'];
        $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        $resultJson->setData($response);
        return $resultJson;
    }
}
