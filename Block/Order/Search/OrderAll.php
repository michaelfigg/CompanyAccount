<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Tigren\CompanyAccount\Block\Order\Search;

/**
 * Sales order history block
 */
class OrderAll extends \Magento\Framework\View\Element\Template
{
    protected $helper;
    protected $_registry;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Registry $registry,
        array $data = []
    )
    {
        $this->_registry = $registry;
        parent::__construct($context, $data);
    }

    protected function _prepareLayout()
    {
        if ($this->getOrders()) {
            $pager = $this->getLayout()->createBlock(
                'Magento\Theme\Block\Html\Pager',
                'tigren.companyaccount.record.pager'
            )
                ->setAvailableLimit([10 => 10])
                ->setShowPerPage(true)
                ->setCollection($this->getOrders());

            $this->setChild('pager', $pager);

            $this->getOrders()->load();
        }
        return $this;
    }

    public function getOrders()
    {
        return $this->_registry->registry('orders-search-all');
    }

    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }

}
