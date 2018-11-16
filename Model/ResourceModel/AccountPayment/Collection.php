<?php

namespace Tigren\CompanyAccount\Model\ResourceModel\AccountPayment;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected function _construct()
    {
        $this->_init('Tigren\CompanyAccount\Model\AccountPayment','Tigren\CompanyAccount\Model\ResourceModel\AccountPayment');
        $this->_idFieldName = 'option_id';
    }

}
