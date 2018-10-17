<?php

namespace Tigren\CompanyAccount\Controller\Adminhtml\Account;

class Addresses extends \Magento\Backend\App\Action
{
    protected $resultLayoutFactory;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\LayoutFactory $resultLayoutFactory
    ){
        parent::__construct($context);
        $this->resultLayoutFactory = $resultLayoutFactory;
    }

    /**
     * Get grid and serializer block
     *
     * @return \Magento\Framework\View\Result\Layout
     */
    public function execute()
    {
        $resultLayout = $this->resultLayoutFactory->create();
        $isFilter = $this->getRequest()->getParam('filter');
        $resultLayout
            ->getLayout()
            ->getBlock('account_edit_tab_account_addresses')
            ->setIsFilter(isset($isFilter));
        return $resultLayout;
    }
}
