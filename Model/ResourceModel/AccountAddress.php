<?php

namespace Tigren\CompanyAccount\Model\ResourceModel;

use Magento\Framework\Model\AbstractModel;

class AccountAddress extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    protected function _construct()
    {
        $this->_init('tigren_comaccount_account_address', 'address_id');
    }

}
