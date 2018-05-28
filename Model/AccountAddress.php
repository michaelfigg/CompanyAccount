<?php

namespace Tigren\CompanyAccount\Model;

use Tigren\CompanyAccount\Api\Data\AccountAddressInterface;

class AccountAddress extends \Magento\Framework\Model\AbstractModel
{

    protected function _construct()
    {
        $this->_init('Tigren\CompanyAccount\Model\ResourceModel\AccountAddress');
    }
}
