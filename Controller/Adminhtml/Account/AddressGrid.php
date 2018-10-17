<?php

namespace Tigren\CompanyAccount\Controller\Adminhtml\Account;

class AddressGrid extends \Magento\Backend\App\Action
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
                'Tigren\CompanyAccount\Block\Adminhtml\Account\Edit\Tab\AddressesGrid',
                'account_edit_tab_account_addresses_grid'
            )->toHtml()
        );
    }

}
