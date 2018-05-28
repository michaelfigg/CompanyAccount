<?php
/**
 * @copyright Copyright (c) 2016 www.tigren.com
 */

namespace Tigren\CompanyAccount\Block\Adminhtml\Account\Grid\Column\Renderer;

class Fullname extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer
{
    public function render(\Magento\Framework\DataObject $row)
    {
        $fullname = $row->getFirstname() . ' ' . $row->getMiddlename() . ' ' . $row->getLastname();
        return $fullname;
    }
}