<?php

namespace Tigren\CompanyAccount\Controller\Adminhtml\Account;

class AssignCustomerGrid extends \Magento\Backend\App\Action
{
    protected $resultRawFactory;
    protected $layoutFactory;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Controller\Result\RawFactory $resultRawFactory,
        \Magento\Framework\View\LayoutFactory $layoutFactory
    ){
        parent::__construct($context);
        $this->resultRawFactory = $resultRawFactory;
        $this->layoutFactory = $layoutFactory;
    }

    /**
     * Get grid and serializer block
     *
     * @return \Magento\Framework\View\Result\Layout
     */
    public function execute()
    {
        $resultRaw = $this->resultRawFactory->create();
        return $resultRaw->setContents(
            $this->layoutFactory->create()->createBlock(
                'Tigren\CompanyAccount\Block\Adminhtml\Account\Edit\Tab\Customer\Assign\AssignActionCustomerTab',
                'account.assign.customers.action.tab.detail'
            )->toHtml()
        );
    }

}
