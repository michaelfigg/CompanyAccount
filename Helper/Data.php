<?php

namespace Tigren\CompanyAccount\Helper;

use Magento\Customer\Model\Session as CustomerSession;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    protected $_resource;
    protected $_connection;
    protected $_objectManager;
    protected $_storeManager;
    protected $_customerSession;
    protected $_customers;
    protected $_accountTable;
    protected $_accountStoreTable;
    protected $_accountAdminTable;
    protected $_accountAddressTable;
    protected $_customerLoginExternalTable;
    protected $_baseUrl;
    protected $_eavAttribute;
    protected $_customerFactory;
    protected $_appEmulation;
    protected $_imageHelperFactory;
    protected $_productFactory;
    protected $addressFactory;
    protected $accountFactory;
    /**
     * @var \Magento\Customer\Api\CustomerRepositoryInterface
     */
    protected $_customerRepositoryInterface;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\App\ResourceConnection $resource,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        CustomerSession $customerSession,
        \Magento\Customer\Model\Customer $customer,
        \Magento\Eav\Model\ResourceModel\Entity\Attribute $eavAttribute,
        \Magento\Customer\Model\CustomerFactory $customerFactory,
        \Magento\Store\Model\App\Emulation $appEmulation,
        \Magento\Catalog\Helper\ImageFactory $imageHelperFactory,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Tigren\CompanyAccount\Model\AccountAddressFactory $addressFactory,
        \Tigren\CompanyAccount\Model\AccountFactory $accountFactory,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepositoryInterface
    ){
        parent::__construct($context);
        $this->_resource = $resource;
        $this->_connection = $this->_resource->getConnection('core_write');
        $this->_accountTable = $this->_resource->getTableName('tigren_comaccount_account');
        $this->_accountStoreTable = $this->_resource->getTableName('tigren_comaccount_account_store');
        $this->_accountAdminTable = $this->_resource->getTableName('tigren_comaccount_account_admin');
        $this->_accountAddressTable = $this->_resource->getTableName('tigren_comaccount_account_address');
        $this->_customerLoginExternalTable = $this->_resource->getTableName('tigren_comaccount_customer_login_external');
        $this->_objectManager = $objectManager;
        $this->_storeManager = $storeManager;
        $this->_customerSession = $customerSession;
        $this->_customers = $customer;
        $this->_eavAttribute = $eavAttribute;
        $this->_customerFactory = $customerFactory;
        $this->_appEmulation = $appEmulation;
        $this->_imageHelperFactory = $imageHelperFactory;
        $this->_productFactory = $productFactory;
        $this->addressFactory = $addressFactory;
        $this->accountFactory = $accountFactory;
        $this->_customerRepositoryInterface = $customerRepositoryInterface;
    }

    public function checkCustomerExternelLogin(){

    }

    /**
     * @param $email
     * @return bool
     */
    public function getCustomerByEmail($email)
    {
        try {
            $customer = $this->_customerRepositoryInterface->get($email, $this->getWebsiteId());
            if ($customer->getId()) {
                return true;
            } else {
                return false;
            }
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * @return int
     */
    public function getWebsiteId()
    {
        return $this->_storeManager->getStore()->getWebsiteId();
    }

    public function insertCustomerExternal($customerEmail, $token)
    {
        if (!empty($customerEmail) && !empty($token)) {
            $dataInsert = [
                'customer_email' => $customerEmail,
                'customer_token' => $token
            ];
            $this->_connection->insert($this->_customerLoginExternalTable, $dataInsert);
        }
    }

    public function getCreatedAtCustomerExternalByToken($token)
    {
        $created_at = null;
        if (!empty($token)) {
        $select = $this->getSelect()->from(['alx' => $this->_customerLoginExternalTable], 'created_at')
        ->where('alx.customer_token = "' . $token . '"');
        $created_at = $this->_connection->fetchOne($select);
        }
        return $created_at;
    }

    public function isPortalEnabled()
    {
        return $this->scopeConfig->getValue(
            'company_account/portal/enable',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $this->getStore()->getId()
        );
    }

    public function getTimeExternalLogin()
    {
        return $this->scopeConfig->getValue(
            'company_account/portal/token_expiration',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $this->getStore()->getId()
        );
    }

    public function getResponse($url, $email, $token, $message)
    {
        $result = [];
        $result['urlLogin'] = $url . '?email=' . urlencode($email) . '&&token=' . $token;
        $result['message'] = $message;
        return $result;
    }

    public function getStore()
    {
        return $this->_storeManager->getStore();
    }

    public function randString()
    {
        $length = 10;
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $size = strlen($chars);
        $str = null;
        for ($i = 0; $i < $length; $i++) {
            $str .= $chars[rand(0, $size - 1)];
        }
        return $str;
    }

    public function getAccountNameById($accountId)
    {
        $account = $this->accountFactory->create()->load($accountId);
        if(!$account){
            return null;
        }
        $accountName = $account->getCompany();
        return $accountName;
    }

    public function getAllAccountIds()
    {
        $select = $this->getSelect()->from($this->_accountTable, 'account_id');
        return $this->_connection->fetchCol($select);
    }

    public function isAdminOfAccount($customerId)
    {
        $select = $this->getSelect()->from($this->_accountAdminTable, 'account_id')
            ->where('admin_id = ' . $customerId);
        $accountId = $this->_connection->fetchOne($select);
        return $accountId;
    }

    public function getAllUserOfAccount($customerId)
    {
        if ($customerId) {
            $accountId = $this->getAccountIdByCustomer($customerId);
            if ($accountId && $customerId) {
                $select = $this->selectTableCustomerInt()->where('value = ' . $accountId);
                return $this->_connection->fetchCol($select);
            }
        }
        return null;
    }

    public function isLoggedIn()
    {
        return $this->_customerSession->isLoggedIn();
    }

    public function getCustomerId()
    {
        return $this->_customerSession->getCustomerId();
    }

    public function getCustomerCollection()
    {
        return $this->_customers->getCollection();
    }

    public function createAccount($company, $customerId, $is_business)
    {
        $account = $this->accountFactory
            ->create()
            ->setData(
                [
                    'company' => $company,
                    'customer_ids' => [$customerId],
                    'admin_ids' => [$customerId],
                    'stores' => [$this->_storeManager->getStore()->getId()]
                ]
            );
        $account->save();

        if ($accountId = $account->getId()) {
            $customer = $this->_customers->load($customerId);
            $customerData = $customer->getDataModel();
            $customerData->setCustomAttribute('account_id', $accountId);
            $customerData->setCustomAttribute('enable_enter_shipping_address', 0);
            $customerData->setCustomAttribute('enable_enter_shipping_address', 0);
            $customerData->setCustomAttribute('is_business', $is_business);
            $customer->updateData($customerData)->save();
        }
    }

    public function assignAdmin($userId)
    {
        if ($accountId = $this->getAccountIdByCustomer($this->getCustomerId())) {
            $select = $this->getSelect()->from(['acd' => $this->_accountAdminTable], 'admin_id')
                ->where('acd.account_id = ' . $accountId . ' AND admin_id=' . $userId);
            $admin_id = $this->_connection->fetchOne($select);
            if (empty($admin_id)) {
                $dataInsert = ['admin_id' => $userId, 'account_id' => $accountId];
                $this->_connection->insert($this->_accountAdminTable, $dataInsert);
            }
        }
    }

    public function assignCustomer($userId, $isAdmin = null)
    {
        if ($accountId = $this->getAccountIdByCustomer($this->getCustomerId())) {
            $this->assignToAccount($accountId, $userId);
            //assign admin if is admin
            if ($isAdmin == 1) {
                $adminInsert = ['admin_id' => $userId, 'account_id' => $accountId];
                $this->_connection->insert($this->_accountAdminTable, $adminInsert);
            }
        }
    }

    public function assignToAccount($accountId, $customerId)
    {
//	    if($accountIsArray == true){
//            $account = $this->_objectManager->create('Tigren\CompanyAccount\Model\Account');
//            $accountModel = $account->load($accountId);
//            $accountModel->setCustomerIds([$customerId])->save();
//        }
        $customer = $this->_customerFactory->create();
        $customer->load($customerId);
        if ($customer->getId()) {
            $customerData = $customer->getDataModel();
            $customerData->setCustomAttribute('account_id', $accountId);
            $customerData->setCustomAttribute('enable_enter_shipping_address', 0);
            $customer->updateData($customerData)->save();
        }
    }

    public function unsetAccountId($accountId, $customerId)
    {
        $customer = $this->_customerFactory->create();
        $customer->load($customerId);
        if ($customer->getId()) {
            $customerData = $customer->getDataModel();
            $customerData->setCustomAttribute('account_id', null);
            $customer->updateData($customerData)->save();
        }
    }

    public function updateEnterShippingAddress($customerId, $value)
    {
        $customer = $this->_customerFactory->create();
        $customer->load($customerId);
        if ($customer->getId()) {
            $customerData = $customer->getDataModel();
            $customerData->setCustomAttribute('enable_enter_shipping_address', $value);
            $customer->updateData($customerData)->save();
        }
    }


    public function getBaseUrl()
    {
        if (!$this->_baseUrl) {
            $this->_baseUrl = $this->_storeManager->getStore()->getBaseUrl();
        }
        return $this->_baseUrl;
    }

    public function getUrlApi($link)
    {
        return $this->getBaseUrl() . 'index.php/rest/V1/' . $link . '/';
    }

    public function getStoreId()
    {
        return $this->_storeManager->getStore()->getId();
    }

    public function getAttributeIdByCode($attribute_code)
    {
        return $this->_eavAttribute->getIdByCode('customer', $attribute_code);
    }

    public function selectTableCustomerInt()
    {
        $customerEntityIntTable = $this->_resource->getTableName('customer_entity_int');
        $attributeid = $this->getAttributeIdByCode('account_id');
        $select = $this->getSelect()->from($customerEntityIntTable, 'entity_id');
        if ($attributeid) {
            $select->where('attribute_id = ' . $attributeid);
        }
        return $select;
    }

    public function getUnAvailableCustomers($accountId)
    {
        $select = $this->selectTableCustomerInt();
        if ($accountId) {
            $select->where('value <> ' . $accountId);
        }
        return $this->_connection->fetchCol($select) ?: [];
    }

    public function getCustomerIdsByAccount($accountId = null)
    {
        if (!$accountId) {
            return [];
        }
        $select = $this->selectTableCustomerInt()->where('value = ' . $accountId);
        return $this->_connection->fetchCol($select) ?: [];
    }

    public function getSelect()
    {
        return $this->_connection->select();
    }

    public function isInAvailableAccount($customerId)
    {
        if(empty($customerId)){
            return false;
        }
        $select = $this->getSelect()->from(['acd' => $this->_accountAdminTable], 'account_id')
            ->join(['acs' => $this->_accountStoreTable], 'acd.account_id = acs.account_id')
            ->where('acs.store_id = ' . $this->getStoreId() . ' AND admin_id=' . $customerId);
        $account_id = $this->_connection->fetchOne($select);
        return $account_id;
    }

    public function getIdAddressAccount($accountId)
    {
        $collection = $this->addressFactory->create()->getCollection();
        $address_id = $collection->addFieldToSelect('address_id')->addFieldToFilter('account_id', ['in' => $accountId])->getData();
        return $address_id;
    }

    public function getAccountIdByCustomer($customerId = null)
    {
        if (!$customerId) {
            return null;
        }
        $customer = $this->_customerFactory->create();
        $customer->load($customerId);
        if (!$customer->getId()) {
            return null;
        }
        $customerData = $customer->getDataModel();
        $accountIdAttribute = $customerData->getCustomAttribute('account_id');
        if (!$accountIdAttribute) {
            return null;
        };
        return $accountIdAttribute->getValue();
    }

    public function availablePayOnAccount()
    {
        $accountId = $this->getAccountIdByCustomer($this->getCustomerId());
        $account = $this->accountFactory->create()->load($accountId);
        $pay_on = $account->getPayOnAccount();
        if ($pay_on == 1) {
            return true;
        } else {
            return false;
        }
    }

    public function getCustomerEnableEnterShippingAddress($customerId)
    {
        $enable = 0;
        $customer = $this->_customerFactory->create();
        $customer->load($customerId);
        if ($customer->getId()) {
            $customerData = $customer->getDataModel();
            $enable = $customerData->getCustomAttribute('enable_enter_shipping_address')->getValue();
        }
        return $enable;
    }

    public function unAssignAdmin($customerId)
    {
        if ($accountId = $this->isAdminOfAccount($customerId)) {
            $condition = ['admin_id = ?' => $customerId];
            $this->_connection->delete($this->_accountAdminTable, $condition);
        }
    }

    public function getCustomerAdmin($accountId = null)
    {
        if (!$accountId) {
            return [];
        }
        $customerAdmin = [];
        $customerIds = $this->getCustomerIdsByAccount($accountId);
        $adminIds = $this->getAdminIds($accountId);
        foreach ($customerIds as $id) {
            if (in_array($id, $adminIds)) {
                $customerAdmin[$id] = 1;
            } else $customerAdmin[$id] = 0;
        }
        return $customerAdmin;
    }

    public function getAdminIds($accountId = null)
    {
        if (!$accountId) {
            return [];
        }
        $select = $this->getSelect()->from(
            $this->_accountAdminTable, 'admin_id')
            ->where('account_id = ?', $accountId);
        return $this->_connection->fetchCol($select);
    }

    public function getExtensionDataOrderItem(\Magento\Sales\Api\Data\OrderItemInterface $item)
    {
        $data = [
            'image' => '',
            'manufacturer' => ''
        ];

        if (!$item->getProductId()) {
            if (!$item->getSku()) {
                return $data;
            } else {
                $product = $this->_productFactory->create()->loadByAttribute('sku', $item->getSku());
            }
        } else $product = $this->_productFactory->create()->load($item->getProductId());
        if (!$product || !$product->getId()) {
            return $data;
        }
        $data['image'] = $this->getImageProduct($product);
        $data['manufacturer'] = $product->getAttributeText('manufacturer') ?: '';
        return $data;
    }

    public function getImageProduct($product)
    {
        $imageId = 'product_thumbnail_image';
        $this->_appEmulation->startEnvironmentEmulation($this->getStoreId(), \Magento\Framework\App\Area::AREA_FRONTEND, true);
        $image = $this->_imageHelperFactory->create()->init($product, $imageId)
            ->constrainOnly(TRUE)
            ->keepAspectRatio(TRUE)
            ->keepTransparency(TRUE)
            ->keepFrame(FALSE);
        $this->_appEmulation->stopEnvironmentEmulation();
        return $image->getUrl();
    }

    public function getCustomersInAccount()
    {
        $customers = $this->selectTableCustomerInt();
        return $this->_connection->fetchCol($customers);
    }

}
