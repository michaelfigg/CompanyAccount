<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Tigren\CompanyAccount\Block;

use Magento\Framework\View\Element\Template;

/**
 * Class Index
 *
 * @api
 */
class Index extends \Magento\Swagger\Block\Index
{
    /**
     * @return mixed|string
     */
    private function getParamStore()
    {
        return $this->getRequest()->getParam('store') ?: '';
    }

    /**
     * @return string
     */
    public function getSchemaUrl()
    {
        return rtrim($this->getBaseUrl(), '/') . '/rest/' . $this->getParamStore() . '/schema?services=all';
    }
}
