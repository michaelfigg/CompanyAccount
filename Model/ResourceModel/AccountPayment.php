<?php

namespace Tigren\CompanyAccount\Model\ResourceModel;

use Magento\Framework\Model\AbstractModel;

class AccountPayment extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    protected function _construct()
    {
        $this->_init('tigren_comaccount_payment_options', 'option_id');
    }

}
