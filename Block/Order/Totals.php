<?php
namespace Tigren\CompanyAccount\Block\Order;
class Totals extends \Magento\Sales\Block\Order\Totals
{
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Registry $registry, array $data = []
    ){
        parent::__construct($context, $registry, $data);
    }
}