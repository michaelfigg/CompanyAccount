<?php
namespace Tigren\CompanyAccount\Controller\Result;

use Magento\Framework\Controller\ResultFactory;

class Order extends \Magento\Framework\App\Action\Action
{
    protected $resultPageFactory;
    protected $resultJsonFactory;
    protected $resultFactory;
    protected $helper;
    protected $_customerSession;
    protected $info;

    /**
     * Order constructor.
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Tigren\CompanyAccount\Helper\Data $helper
     * @param \Magento\Customer\Model\Session $customerSession
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Tigren\CompanyAccount\Helper\Data $helper,
        \Magento\Customer\Model\Session $customerSession,
        ResultFactory $resultFactory,
        \Tigren\CompanyAccount\Block\Account\Dashboard\Info $info
    ){
        parent::__construct($context);
        $this->resultJsonFactory = $resultJsonFactory;
        $this->resultPageFactory  = $resultPageFactory;
        $this->helper = $helper;
        $this->info   = $info;
        $this->resultFactory = $resultFactory;
        $this->_customerSession = $customerSession;
    }

    public function execute()
    {
        $role = $this->getRequest()->getParam("data");
        $resultPage = $this->resultPageFactory->create();
        if ($role == "myorder") {
            $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
            $html = $resultPage->getLayout()
                ->createBlock('Tigren\CompanyAccount\Block\Order\User')
                ->setTemplate('Tigren_CompanyAccount::result/myorder.phtml')
                ->toHtml();
            $resultJson->setData($html);
            return $resultJson;
        }
        if ($role == "companyorder") {
            $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
            $html = $resultPage->getLayout()
                ->createBlock('Tigren\CompanyAccount\Block\Order\History')
                ->setTemplate('Tigren_CompanyAccount::result/companyorder.phtml')
                ->toHtml();
            $resultJson->setData($html);
            return $resultJson;
        }
    }
    public function getCustomerId()
    {
        return $this->_customerSession->getCustomerId();
    }

    public function getCompanyAcount()
    {
        return $this->info->getAccount();
    }
}
