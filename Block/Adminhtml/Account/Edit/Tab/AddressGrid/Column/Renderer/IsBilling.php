<?php

namespace Tigren\CompanyAccount\Block\Adminhtml\Account\Edit\Tab\AddressGrid\Column\Renderer;

class IsBilling extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer
{
    public function render(\Magento\Framework\DataObject $row)
    {
        $isBilling = $row->getIsBilling();
        $html = null;
        if($isBilling){
            $html = "<span class='grid-severity-notice'><span>Default billing address</span></span>";
        }
        return $html;
    }
}