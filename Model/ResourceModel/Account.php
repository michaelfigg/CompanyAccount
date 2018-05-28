<?php
/**
 * @copyright Copyright (c) 2016 www.tigren.com
 */
namespace Tigren\CompanyAccount\Model\ResourceModel;

use Magento\Framework\Model\AbstractModel;

/**
 * Mysql resource
 */
class Account extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected $_accountTable;
    protected $_accountStoreTable;
    protected $_accountAdminTable;
    protected $_accountFactory;
    protected $_storeManager;
    protected $_accountHelper;
    protected $_connection;
    protected $_customEntityIntTable;

    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        \Tigren\CompanyAccount\Model\AccountFactory $accountFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Tigren\CompanyAccount\Helper\Data $accountHelper,
        $resourcePrefix = null
    )
    {
        parent::__construct($context, $resourcePrefix);
        $this->_accountFactory = $accountFactory;
        $this->_storeManager = $storeManager;
        $this->_accountHelper = $accountHelper;
    }
    
    protected function _construct()
    {
        $this->_init('tigren_comaccount_account', 'account_id');
        $this->_accountTable = $this->getTable('tigren_comaccount_account');
        $this->_accountStoreTable = $this->getTable('tigren_comaccount_account_store');
        $this->_accountAdminTable = $this->getTable('tigren_comaccount_account_admin');
        $this->_customEntityIntTable = $this->getTable('customer_entity_int');
        $this->_connection = $this->getConnection();
        
    }

    protected function getSelect()
    {
        return $this->_connection->select();
    }
    
    public function getStoreIds($accountId)
    {
        $select = $this->getSelect()->from(
            $this->_accountStoreTable, 'store_id')
            ->where('account_id = ?', $accountId);
        return $this->_connection->fetchCol($select);
    }
    
    public function getCustomerIds($accountId) {
        return $this->_accountHelper->getCustomerIdsByAccount($accountId);
    }

    public function getAdminIds($accountId) {
        $select = $this->getSelect()->from(
            $this->_accountAdminTable, 'admin_id')
            ->where('account_id = ?', $accountId);
        return $this->_connection->fetchCol($select);
    }
    
    protected function _afterLoad(AbstractModel $object)
    {
        parent::_afterLoad($object);
        if (!$object->getId()) {   //if create new
            return $this;
        }
        if ($object->getId()) {
            $object->setStores($this->getStoreIds((int)$object->getId()));
            $object->setCustomerIds($this->getCustomerIds((int)$object->getId()));
            $object->setAdminIds($this->getAdminIds((int)$object->getId()));
        }
        return $this;
    }
    
    protected function _beforeSave(AbstractModel $object)
    {
        if ($object->hasData('stores') && !is_array($object->getStores())) {
            $object->setStores([$object->getStores()]);
        }
        return parent::_beforeSave($object);
    }

    protected function _afterSave(AbstractModel $object)
    {
        $connection = $this->_connection;
        $accountId = $object->getId();
        //Save Account Stores
        $condition = ['account_id = ?' => $accountId];
        $connection->delete($this->_accountStoreTable, $condition);
        $stores = $object->getStores();
        if (!empty($stores)) {
            $insertedStoreIds = [];
            $fullStoreIds = $this->getAllStoreIds();
            foreach ($stores as $storeId) {
                if (in_array($storeId, $insertedStoreIds) || !in_array((int)$storeId, $fullStoreIds)) {
                    continue;
                }
                $insertedStoreIds[] = $storeId;
                $storeInsert = ['store_id' => $storeId, 'account_id' => $accountId];
                $connection->insert($this->_accountStoreTable, $storeInsert);
            }
        }
        
        //Save Account Customers
        $customerIds = $object->getCustomerIds() ?: [];
        $oldCustomerIds = $this->getCustomerIds($accountId) ?: [];
        if(is_array($customerIds)){
            $insert = array_diff($customerIds, $oldCustomerIds);
            $delete = array_diff($oldCustomerIds, $customerIds);

            foreach ($insert as $idInsert) {
                $this->_accountHelper->assignToAccount($accountId,$idInsert);
            }

            foreach ($delete as $idDelete) {
                $this->_accountHelper->unsetAccountId($accountId,$idDelete);
            }
        }

        //Save Account Admins
        $adminIds = $object->getAdminIds() ?: [];
        $oldAdminIds = $this->getAdminIds($accountId) ?: [];
        $insert = array_diff($adminIds, $oldAdminIds);
        $delete = array_diff($oldAdminIds, $adminIds);

        if (!empty($delete)) {
            $condition = [
                'account_id = ?' => $accountId,
                'admin_id IN (?)' => $delete
            ];
            $connection->delete($this->_accountAdminTable, $condition);
        }
        
        if (!empty($insert)) {
            foreach ($insert as $adminId) {
                $insert = ['admin_id' => $adminId, 'account_id' => $accountId];
                $connection->insert($this->_accountAdminTable, $insert);
            }
        }
        return $this;
    }
    
    protected function _afterDelete(AbstractModel $object) {
        $conditionAdmin = ['account_id = ?' => $object->getId()];
        $conditionCustomer = ['value = ?' => $object->getId()];
        $connection = $this->_connection;
        $connection->delete($this->_accountAdminTable, $conditionAdmin);
        $connection->delete($this->_customEntityIntTable, $conditionCustomer);
        
        return parent::_afterDelete($object);
    }

    public function getCurrentStoreId()
    {
        return $this->_storeManager->getStore(true)->getId();
    }
    
    public function getAllStoreIds() {
        $stores = $this->_storeManager->getStores();
        $ids = array_keys($stores);
        array_unshift($ids, 0); 
        return $ids;
    } 
}
