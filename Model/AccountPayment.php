<?php

namespace Tigren\CompanyAccount\Model;

use Tigren\CompanyAccount\Api\Data\AccountAddressInterface;

class AccountPayment extends \Magento\Framework\Model\AbstractModel
{

    protected function _construct()
    {
        $this->_init('Tigren\CompanyAccount\Model\ResourceModel\AccountPayment');
    }
}
