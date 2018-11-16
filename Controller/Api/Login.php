<?php

namespace Tigren\CompanyAccount\Controller\Api;

use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\ResultFactory;

class Login extends \Magento\Framework\App\Action\Action
{
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    protected $resultJsonFactory;

    protected $resultFactory;

    protected $_customerFactory;

    protected $helper;

    protected $storeManager;

    protected $customerSession;

    protected $customerRepository;

    protected $date;

    /**
     * Login constructor.
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param JsonFactory $resultJsonFactory
     * @param ResultFactory $resultFactory
     * @param \Magento\Customer\Model\CustomerFactory $customerFactory
     * @param \Tigren\CompanyAccount\Helper\Data $helper
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $date
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        JsonFactory $resultJsonFactory,
        ResultFactory $resultFactory,
        \Magento\Customer\Model\CustomerFactory $customerFactory,
        \Tigren\CompanyAccount\Helper\Data $helper,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \Magento\Framework\Stdlib\DateTime\DateTime $date
    )
    {

        $this->resultPageFactory = $resultPageFactory;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->resultFactory = $resultFactory;
        $this->_customerFactory = $customerFactory;
        $this->helper = $helper;
        $this->storeManager = $storeManager;
        $this->customerSession = $customerSession;
        $this->customerRepository = $customerRepository;
        $this->date = $date;
        return parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();

        if(!$this->helper->isPortalEnabled()){
            $this->messageManager->addError(__('Portal functionality is not currently enabled'));
            $resultRedirect->setPath('customer/account/login');
            return $resultRedirect;
        }
        
        $params = $this->_request->getParams();
        $token = $params['token'];
        $createAt = $this->helper->getCreatedAtCustomerExternalByToken($token);
        $dateCurrent = $this->date->gmtDate();
        // add 3 minutes
        if (!empty($createAt)) {
            $minute = !empty($this->helper->getTimeExternalLogin()) ? $this->helper->getTimeExternalLogin() : 3;
            $createAtPlus = date('Y-m-d H:i:s', strtotime('+' . $minute . ' minutes', strtotime($createAt)));
            if ($createAtPlus > $dateCurrent) {
                $customer = $this->customerRepository->get($params['email']);
                $customer = $this->_customerFactory->create()->load($customer->getId());
                $this->customerSession->setCustomerAsLoggedIn($customer);
                $this->messageManager->addSuccess(__('Login successful.'));
            }else{
                $this->customerSession->logout();
                $this->messageManager->addError(__('Login fail, the token has expired.'));
            }
        }

        $resultRedirect->setPath('customer/account/');
        return $resultRedirect;
    }
}
