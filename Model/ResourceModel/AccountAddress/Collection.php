<?php

namespace Tigren\CompanyAccount\Model\ResourceModel\AccountAddress;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected function _construct()
    {
        $this->_init('Tigren\CompanyAccount\Model\AccountAddress','Tigren\CompanyAccount\Model\ResourceModel\AccountAddress');
        $this->_idFieldName = 'address_id';
    }

}
