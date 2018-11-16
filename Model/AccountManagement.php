<?php

namespace Tigren\CompanyAccount\Model;

use Tigren\CompanyAccount\Api\Data\AccountInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Tigren\CompanyAccount\Api\Data\CustomerInterface;
use Magento\Customer\Api\AddressRepositoryInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\Config\Share as ConfigShare;
use Magento\Customer\Model\Customer as CustomerModel;
use Magento\Customer\Model\EmailNotificationInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Encryption\EncryptorInterface as Encryptor;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\MailException;
use Magento\Framework\Exception\State\InputMismatchException;
use Magento\Framework\Math\Random;
use Magento\Framework\Registry;
use Magento\Framework\Stdlib\DateTime;
use Magento\Framework\Stdlib\StringUtils as StringHelper;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

class AccountManagement implements \Tigren\CompanyAccount\Api\AccountManagementInterface
{
    const XML_PATH_MINIMUM_PASSWORD_LENGTH = 'customer/password/minimum_password_length';
    const XML_PATH_REQUIRED_CHARACTER_CLASSES_NUMBER = 'customer/password/required_character_classes_number';
    const NEW_ACCOUNT_EMAIL_REGISTERED = 'registered';
    const XML_PATH_IS_CONFIRM = 'customer/create_account/confirm';
    /**
     * Welcome email, when confirmation is enabled
     *
     * @deprecated
     */
    const NEW_ACCOUNT_EMAIL_CONFIRMATION = 'confirmation';
    /**
     * Welcome email, when password setting is required
     *
     * @deprecated
     */
    const NEW_ACCOUNT_EMAIL_REGISTERED_NO_PASSWORD = 'registered_no_password';

    protected $accountManagement;
    protected $stringHelper;
    protected $_logger;
    protected $dateTime;
    protected $registry;
    /**
     * @var CustomerModel
     */
    protected $customerModel;
    protected $customerFactory;
    private $scopeConfig;
    private $encryptor;
    private $customerRepository;
    private $storeManager;
    private $addressRepository;
    private $mathRandom;
    private $customerRegistry;
    private $emailNotification;
    /**
     * @var ConfigShare
     */
    private $configShare;

    /**
     * @var \Tigren\CompanyAccount\Model\AccountFactory
     */
    protected $accountFactory;

    /**
     * @var \Tigren\CompanyAccount\Helper\Data
     */
    protected $helper;
    protected $_customerCollectionFactory;
    protected $_customers;
    protected $_resource;
    protected $_connection;
    protected $_accountAdminTable;
    protected $accountAddressManagement;
    protected $accountPaymentManagement;


    public function __construct(
        \Tigren\CompanyAccount\Model\AccountFactory $accountFactory,
        \Tigren\CompanyAccount\Helper\Data $helper,
        StringHelper $stringHelper,
        ScopeConfigInterface $scopeConfig,
        Encryptor $encryptor,
        \Psr\Log\LoggerInterface $logger,
        CustomerRepositoryInterface $customerRepository,
        ConfigShare $configShare,
        StoreManagerInterface $storeManager,
        AddressRepositoryInterface $addressRepository,
        Random $mathRandom,
        \Magento\Customer\Model\CustomerRegistry $customerRegistry,
        DateTime $dateTime,
        CustomerModel $customerModel,
        \Magento\Customer\Model\CustomerFactory $customerFactory,
        Registry $registry,
        \Magento\Customer\Model\ResourceModel\Customer\CollectionFactory $customerCollectionFactory,
        \Magento\Customer\Model\Customer $customer,
        \Magento\Framework\App\ResourceConnection $resource,
        \Tigren\CompanyAccount\Model\AccountAddressManagement $accountAddressManagement,
        \Tigren\CompanyAccount\Model\AccountPaymentManagement $accountPaymentManagement
    ){
        $this->_resource = $resource;
        $this->_connection = $this->_resource->getConnection('core_write');
        $this->_accountAdminTable = $this->_resource->getTableName('tigren_comaccount_account_admin');
        $this->accountFactory = $accountFactory;
        $this->helper = $helper;
        $this->stringHelper = $stringHelper;
        $this->scopeConfig = $scopeConfig;
        $this->encryptor = $encryptor;
        $this->_logger = $logger;
        $this->customerRepository = $customerRepository;
        $this->configShare = $configShare;
        $this->storeManager = $storeManager;
        $this->addressRepository = $addressRepository;
        $this->mathRandom = $mathRandom;
        $this->customerRegistry = $customerRegistry;
        $this->dateTime = $dateTime;
        $this->customerModel = $customerModel;
        $this->customerFactory = $customerFactory;
        $this->registry = $registry;
        $this->_customerCollectionFactory = $customerCollectionFactory;
        $this->_customers = $customer;
        $this->accountAddressManagement = $accountAddressManagement;
        $this->accountPaymentManagement = $accountPaymentManagement;
    }

    /**
     * {@inheritdoc}
     */
    public function getById($accountId)
    {
        $accountModel = $this->accountFactory->create();
        $accountModel->load($accountId);
        if (!$accountModel->getId()) {
            throw new NoSuchEntityException(
                __('We can\'t specify an account.')
            );
        }
        return $accountModel;
    }

    /**
     * {@inheritdoc}
     */
    public function save(AccountInterface $account)
    {
        $accountModel = $this->accountFactory->create();
        $data = [
            'account_id' => $account->getAccountId(),
            'company' => $account->getCompany(),
            'telephone' => $account->getTelephone(),
            'tax' => $account->getTax(),
            'stores' => [$this->helper->getStoreId()],
            'logo_image_link' => $account->getLogoImageLink(),
            'pay_on_account' => $account->getPayOnAccount(),
            'account_group_id' => $account->getAccountGroupId(),
            'public_notes' => $account->getPublicNotes(),
            'manager_first_name' => $account->getManagerFirstName(),
            'manager_last_name' => $account->getManagerLastName(),
            'manager_telephone' => $account->getManagerTelephone(),
            'manager_email' => $account->getManagerEmail(),
            'manager_profile' => $account->getManagerProfile(),
            'portal_source' => $account->getPortalSource(),
            'portal_username' => $account->getPortalUsername(),
            'credit_limit' => $account->getCreditLimit(),
            'credit_terms' => $account->getCreditTerms(),
            'balance' => $account->getBalance(),
            'payment_options' => $account->getPaymentOptions()
        ];

        if (!empty($data['account_id'])) {
            $customerCollection = $this->customerFactory->create()->getCollection()
                ->addAttributeToSelect("*")
                ->addAttributeToFilter("account_id", ["eq" => $data['account_id']])->load();

            $accountModel->load($data['account_id']);
            if (!$accountModel->getId()) {
                throw new NoSuchEntityException(
                    __('We can\'t specify an account.')
                );
            }
        }

        $accountModel->setData($data)->save();
        if (!empty($data['account_id']) && !empty($customerCollection)) {
            foreach ($customerCollection as $cus) {
                $customer = $this->customerFactory->create();
                $customer->load($cus->getId());
                $customerData = $customer->getDataModel();
                $customerData->setCustomAttribute('account_id', $data['account_id']);
                $customer->updateData($customerData)->save();
            }
        }
        return $accountModel;
    }

    /**
     * {@inheritdoc}
     */
    public function delete($accountId)
    {
        $account = $this->accountFactory->create();
        $account->load($accountId);
        if (!$account->getId()) {
            throw new NoSuchEntityException(
                __('We can\'t specify an account.')
            );
        }
        $account->delete();
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function assign($accountId, $customerId)
    {
        $account = $this->accountFactory->create();
        $account->load($accountId);
        if (!$account->getId()) {
            throw new NoSuchEntityException(
                __('We can\'t specify an account.')
            );
            return false;
        }
        $this->helper->assignToAccount($accountId, $customerId);
        return true;
    }

    /**getList
     * {@inheritdoc}
     */
    public function getList()
    {
        $result = [];
        $collection = $this->accountFactory->create()->getCollection();
        if (count($collection)) {
            foreach ($collection as $account) {
                $result[] = $this->getById($account->getId());
            }
        }
        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function getByCompany($company)
    {
        $result = [];
        $collection = $this->accountFactory->create()->getCollection();
        if ($company) {
            $collection->addFieldToFilter('company', $company);
        }
        if (count($collection)) {
            foreach ($collection as $account) {
                $result[] = $this->getById($account->getId());
            }
        }
        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function createAccount(
        CustomerInterface $customer,
        $password = null,
        $redirectUrl = ''
    )
    {
        $accountId = $customer->getAccountId();
        if (!$accountId) {
            throw new InputException(
                __(
                    'Please enter a customer account ID.'
                )
            );
        }
        $accountModel = $this->accountFactory->create();
        $accountModel->load($accountId);
        if (!$accountModel->getId()) {
            throw new NoSuchEntityException(
                __('We can\'t specify an account.')
            );
        }
        if ($password !== null) {
            $this->checkPasswordStrength($password);
            $hash = $this->createPasswordHash($password);
        } else {
            $hash = null;
        }

        $customer->setCustomAttribute('account_id', $customer->getAccountId());

        return $this->createAccountWithPasswordHash($customer, $hash, $redirectUrl, $customer->getAccountId());
    }

    /**
     * {@inheritdoc}
     */
    public function verify(AccountInterface $account, CustomerInterface $customer,
                           $password = null,
                           $redirectUrl = '')
    {
        //create or edit account company
        $data = [
            'account_id' => $account->getAccountId(),
            'account_number' => $account->getAccountNumber(),
            'company' => $account->getCompany(),
            'telephone' => $account->getTelephone(),
            'tax' => $account->getTax(),
            'stores' => [$this->helper->getStoreId()],
            'logo_image_link' => $account->getLogoImageLink(),
            'pay_on_account' => $account->getPayOnAccount(),
            'account_group_id' => $account->getAccountGroupId(),
            'public_notes' => $account->getPublicNotes(),
            'manager_first_name' => $account->getManagerFirstName(),
            'manager_last_name' => $account->getManagerLastName(),
            'manager_telephone' => $account->getManagerTelephone(),
            'manager_email' => $account->getManagerEmail(),
            'manager_profile' => $account->getManagerProfile(),
            'portal_source' => $account->getPortalSource(),
            'portal_username' => $account->getPortalUsername(),
            'credit_limit' => $account->getCreditLimit(),
            'credit_terms' => $account->getCreditTerms(),
            'balance' => $account->getBalance()
        ];
        foreach ($data as $key => $value) {
            if ($key != 'account_id' && $key != 'stores' && $key != 'portal_username' && $key != 'manager_profile') {
                if (empty($value)) {
                    $error = $key . ' is required, please check again.';
                    throw new NoSuchEntityException(
                        __($error)
                    );
                }
            }
        }
        $customerCollection = null;
        $checkAccountExist = false;
        $accountModel = $this->accountFactory->create();
        if (!empty($data['account_number'])) {
            $accountExist = $this->accountFactory->create()->getCollection()
                ->addFieldToSelect('*')
                ->addFieldToFilter("account_number", ["eq" => $data['account_number']])->getData();
            if (!empty($accountExist[0]['account_id'])) {
                $accountModel->load($accountExist[0]['account_id']);
                $checkAccountExist = true;
                $customerCollection = $this->customerFactory->create()->getCollection()
                    ->addAttributeToSelect("*")
                    ->addAttributeToFilter("account_id", ["eq" => $accountExist[0]['account_id']])->load();
            }
        }
        if (!empty($this->helper->getCustomerByEmail($customer->getEmail())) && $checkAccountExist == false) {
            throw new NoSuchEntityException(
                __('Account for customer does not exist.')
            );
        }
        if (!empty($customer->getCustomAttribute('portal_username')) && empty($this->helper->getCustomerByEmail($customer->getEmail()))) {
            $checkCustomer = $this->_customerCollectionFactory->create()
                ->addAttributeToFilter('portal_username', $customer->getCustomAttribute('portal_username')->getValue())->getSize();
            if ($checkCustomer > 0) {
                throw new NoSuchEntityException(
                    __('Customer with portal username already exists.')
                );
            }
        }

        $accountModel = $this->updateAccount($data, $accountModel);
        if (!empty($customerCollection)) {
            foreach ($customerCollection as $cus) {
                $customerUpdate = $this->customerFactory->create();
                $customerUpdate->load($cus->getId());
                $customerData = $customerUpdate->getDataModel();
                $customerData->setCustomAttribute('account_id', $accountExist[0]['account_id']);
                $customerUpdate->updateData($customerData)->save();
            }
        }
        //create account payment options
        if (empty($account->getPaymentOptions())) {
            throw new NoSuchEntityException(
                __('Payment options is required, please check again.')
            );
        }
        $paymentOption = $account->getPaymentOptions();
        $paymentOption->setAccountId($accountModel->getAccountId());
        $this->accountPaymentManagement->save($paymentOption);

        //create account address shipping
        if (!empty($account->getAddressShipping())) {
            $addressShipping = $account->getAddressShipping();
            $addressShipping->setAccountId($accountModel->getAccountId());
            $this->accountAddressManagement->save($addressShipping);
        }

        //create account address billing
        if (empty($account->getAddressBilling())) {
            throw new NoSuchEntityException(
                __('Billing address is required, please check again.')
            );
        }
        $addressBilling = $account->getAddressBilling();
        $addressBilling->setAccountId($accountModel->getAccountId());
        $this->accountAddressManagement->save($addressBilling);

        //create customer then assign account
        if ($password !== null) {
            $this->checkPasswordStrength($password);
            $hash = $this->createPasswordHash($password);
        } else {
            $hash = null;
        }

        $customer->setCustomAttribute('account_id', $accountModel->getAccountId());

        $token = $this->helper->randString();
        $result = $this->verifyAccountWithPasswordHash($customer, $hash, $redirectUrl, $accountModel->getAccountId());
        $this->helper->insertCustomerExternal($customer->getEmail(), $token);
        if ($result['checkCustomerExist'] == true) {
            $message = 'Customer already exists, you can login this customer within ' . $this->helper->getTimeExternalLogin() . ' minutes, then the token for customer login will expire.';
        } else {
            $message = 'Customer was added successfully, you can login this customer within ' . $this->helper->getTimeExternalLogin() . ' minutes, then the token for customer login will expire.';
        }

        return $this->helper->getResponse($result['url'], $customer->getEmail(), $token, $message);
    }

    /**
     * @param $data
     * @param $accountModel
     */
    protected function updateAccount($data, $accountModel)
    {
        if (!empty($data['account_number'])) {
            $accountModel->setAccountNumber($data['account_number']);
        }
        if (!empty($data['company'])) {
            $accountModel->setCompany($data['company']);
        }
        if (!empty($data['telephone'])) {
            $accountModel->setTelephone($data['telephone']);
        }
        if (!empty($data['tax'])) {
            $accountModel->setTax($data['tax']);
        }
        if (!empty($data['stores'])) {
            $accountModel->setStores($data['stores']);
        }
        if (!empty($data['logo_image_link'])) {
            $accountModel->setLogoImageLink($data['logo_image_link']);
        }
        if (!empty($data['pay_on_account'])) {
            $accountModel->setPayOnAccount($data['pay_on_account']);
        }
        if (!empty($data['account_group_id'])) {
            $accountModel->setAccountGroupId($data['account_group_id']);
        }
        if (!empty($data['public_notes'])) {
            $accountModel->setPublicNotes($data['public_notes']);
        }
        if (!empty($data['manager_first_name'])) {
            $accountModel->setManagerFirstName($data['manager_first_name']);
        }
        if (!empty($data['manager_last_name'])) {
            $accountModel->setManagerLastName($data['manager_last_name']);
        }
        if (!empty($data['manager_telephone'])) {
            $accountModel->setManagerTelephone($data['manager_telephone']);
        }
        if (!empty($data['manager_email'])) {
            $accountModel->setManagerEmail($data['manager_email']);
        }
        if (!empty($data['manager_profile'])) {
            $accountModel->setManagerProfile($data['manager_profile']);
        }
        if (!empty($data['portal_source'])) {
            $accountModel->setPortalSource($data['portal_source']);
        }
        if (!empty($data['portal_username'])) {
            $accountModel->setPortalUsername($data['portal_username']);
        }
        if (!empty($data['credit_limit'])) {
            $accountModel->setCreditLimit($data['credit_limit']);
        }
        if (!empty($data['credit_terms'])) {
            $accountModel->setCreditTerms($data['credit_terms']);
        }
        if (!empty($data['balance'])) {
            $accountModel->setBalance($data['balance']);
        }
        $accountModel->save();
        return $accountModel;
    }

    /**
     * @param CustomerInterface $customer
     * @param $hash
     * @param string $redirectUrl
     * @param $accountId
     * @return array
     * @throws InputException
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    protected function verifyAccountWithPasswordHash(CustomerInterface $customer, $hash, $redirectUrl = '', $accountId)
    {
        $result = [];
        // This logic allows an existing customer to be added to a different store.  No new account is created.
        // The plan is to move this logic into a new method called something like 'registerAccountWithStore'
        if ($customer->getId()) {
            $customer = $this->customerRepository->get($customer->getEmail());
            $websiteId = $customer->getWebsiteId();

            if ($this->isCustomerInStore($websiteId, $customer->getStoreId())) {
                $result['checkCustomerExist'] = true;
                $result['url'] = $this->helper->getBaseUrl() . 'companyaccount/api/login';
                return $result;
            }
            // Existing password hash will be used from secured customer data registry when saving customer
        }
        // Create first, last name by full name
        if (!empty($customer->getFullname())) {
            $arrName = explode(" ", $customer->getFullname());
            $customer->setFirstname($arrName[0]);
            if (isset($arrName[1])) {
                $customer->setLastname($arrName[1]);
            } else {
                $customer->setLastname($arrName[0]);
            }
        }
        // Make sure we have a storeId to associate this customer with.
        if (!$customer->getStoreId()) {
            if ($customer->getWebsiteId()) {
                $storeId = $this->storeManager->getWebsite($customer->getWebsiteId())->getDefaultStore()->getId();
            } else {
                $storeId = $this->storeManager->getStore()->getId();
            }
            $customer->setStoreId($storeId);
        }

        // Associate website_id with customer
        if (!$customer->getWebsiteId()) {
            $websiteId = $this->storeManager->getStore($customer->getStoreId())->getWebsiteId();
            $customer->setWebsiteId($websiteId);
        }

        // Update 'created_in' value with actual store name
        if ($customer->getId() === null) {
            $storeName = $this->storeManager->getStore($customer->getStoreId())->getName();
            $customer->setCreatedIn($storeName);
        }

        $customerAddresses = $customer->getAddresses() ?: [];
        $customer->setAddresses(null);
        try {
            // If customer exists existing hash will be used by Repository
            $customer = $this->customerRepository->save($customer, $hash);
        } catch (AlreadyExistsException $e) {
            $result['checkCustomerExist'] = true;
            $result['url'] = $this->helper->getBaseUrl() . 'companyaccount/api/login';
            return $result;
        } catch (LocalizedException $e) {
            throw $e;
        }
        try {
            foreach ($customerAddresses as $address) {
                if ($address->getId()) {
                    $newAddress = clone $address;
                    $newAddress->setId(null);
                    $newAddress->setCustomerId($customer->getId());
                    $this->addressRepository->save($newAddress);
                } else {
                    $address->setCustomerId($customer->getId());
                    $this->addressRepository->save($address);
                }
            }
        } catch (InputException $e) {
            $this->customerRepository->delete($customer);
            throw $e;
        }

        $customer = $this->customerRepository->getById($customer->getId());
        $newLinkToken = $this->mathRandom->getUniqueHash();
        $this->changeResetPasswordLinkToken($customer, $newLinkToken);
        $this->sendEmailConfirmation($customer, $redirectUrl);
        $this->assignAdmin($accountId, $customer->getId());

        $result['checkCustomerExist'] = false;
        $result['url'] = $this->helper->getBaseUrl() . 'companyaccount/api/login';
        return $result;
    }

    protected function checkPasswordStrength($password)
    {
        $length = $this->stringHelper->strlen($password);
        if ($length > self::MAX_PASSWORD_LENGTH) {
            throw new InputException(
                __(
                    'Please enter a password with at most %1 characters.',
                    self::MAX_PASSWORD_LENGTH
                )
            );
        }
        $configMinPasswordLength = $this->getMinPasswordLength();
        if ($length < $configMinPasswordLength) {
            throw new InputException(
                __(
                    'Please enter a password with at least %1 characters.',
                    $configMinPasswordLength
                )
            );
        }
        if ($this->stringHelper->strlen(trim($password)) != $length) {
            throw new InputException(__('The password can\'t begin or end with a space.'));
        }

        $requiredCharactersCheck = $this->makeRequiredCharactersCheck($password);
        if ($requiredCharactersCheck !== 0) {
            throw new InputException(
                __(
                    'Minimum of different classes of characters in password is %1.' .
                    ' Classes of characters: Lower Case, Upper Case, Digits, Special Characters.',
                    $requiredCharactersCheck
                )
            );
        }
    }

    protected function getMinPasswordLength()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_MINIMUM_PASSWORD_LENGTH);
    }

    protected function makeRequiredCharactersCheck($password)
    {
        $counter = 0;
        $requiredNumber = $this->scopeConfig->getValue(self::XML_PATH_REQUIRED_CHARACTER_CLASSES_NUMBER);
        $return = 0;

        if (preg_match('/[0-9]+/', $password)) {
            $counter++;
        }
        if (preg_match('/[A-Z]+/', $password)) {
            $counter++;
        }
        if (preg_match('/[a-z]+/', $password)) {
            $counter++;
        }
        if (preg_match('/[^a-zA-Z0-9]+/', $password)) {
            $counter++;
        }

        if ($counter < $requiredNumber) {
            $return = $requiredNumber;
        }

        return $return;
    }

    protected function createPasswordHash($password)
    {
        return $this->encryptor->getHash($password, true);
    }

    protected function createAccountWithPasswordHash(CustomerInterface $customer, $hash, $redirectUrl = '', $accountId)
    {
        // This logic allows an existing customer to be added to a different store.  No new account is created.
        // The plan is to move this logic into a new method called something like 'registerAccountWithStore'
        if ($customer->getId()) {
            $customer = $this->customerRepository->get($customer->getEmail());
            $websiteId = $customer->getWebsiteId();

            if ($this->isCustomerInStore($websiteId, $customer->getStoreId())) {
                throw new InputException(__('This customer already exists in this store.'));
            }
            // Existing password hash will be used from secured customer data registry when saving customer
        }

        // Make sure we have a storeId to associate this customer with.
        if (!$customer->getStoreId()) {
            if ($customer->getWebsiteId()) {
                $storeId = $this->storeManager->getWebsite($customer->getWebsiteId())->getDefaultStore()->getId();
            } else {
                $storeId = $this->storeManager->getStore()->getId();
            }
            $customer->setStoreId($storeId);
        }

        // Associate website_id with customer
        if (!$customer->getWebsiteId()) {
            $websiteId = $this->storeManager->getStore($customer->getStoreId())->getWebsiteId();
            $customer->setWebsiteId($websiteId);
        }

        // Update 'created_in' value with actual store name
        if ($customer->getId() === null) {
            $storeName = $this->storeManager->getStore($customer->getStoreId())->getName();
            $customer->setCreatedIn($storeName);
        }

        $customerAddresses = $customer->getAddresses() ?: [];
        $customer->setAddresses(null);
        try {
            // If customer exists existing hash will be used by Repository
            $customer = $this->customerRepository->save($customer, $hash);
        } catch (AlreadyExistsException $e) {
            throw new InputMismatchException(
                __('A customer with the same email already exists in an associated website.')
            );
        } catch (LocalizedException $e) {
            throw $e;
        }
        try {
            foreach ($customerAddresses as $address) {
                if ($address->getId()) {
                    $newAddress = clone $address;
                    $newAddress->setId(null);
                    $newAddress->setCustomerId($customer->getId());
                    $this->addressRepository->save($newAddress);
                } else {
                    $address->setCustomerId($customer->getId());
                    $this->addressRepository->save($address);
                }
            }
        } catch (InputException $e) {
            $this->customerRepository->delete($customer);
            throw $e;
        }

        $customer = $this->customerRepository->getById($customer->getId());
        $newLinkToken = $this->mathRandom->getUniqueHash();
        $this->changeResetPasswordLinkToken($customer, $newLinkToken);
        $this->sendEmailConfirmation($customer, $redirectUrl);
        $this->assignAdmin($accountId, $customer->getId());

        return $customer;
    }

    public function assignAdmin($accountId, $userId)
    {
        $select = $this->getSelect()->from(['acd' => $this->_accountAdminTable], 'admin_id')
            ->where('acd.account_id = ' . $accountId . ' AND admin_id=' . $userId);
        $admin_id = $this->_connection->fetchOne($select);
        if (empty($admin_id)) {
            try {
                $dataInsert = ['admin_id' => $userId, 'account_id' => $accountId];
                $this->_connection->insert($this->_accountAdminTable, $dataInsert);
            } catch (\Exception $e) {

            }
        }
    }

    public function getSelect()
    {
        return $this->_connection->select();
    }

    /**
     * {@inheritDoc}
     */
    public function isCustomerInStore($customerWebsiteId, $storeId)
    {
        $ids = [];
        if ((bool)$this->configShare->isWebsiteScope()) {
            $ids = $this->storeManager->getWebsite($customerWebsiteId)->getStoreIds();
        } else {
            foreach ($this->storeManager->getStores() as $store) {
                $ids[] = $store->getId();
            }
        }

        return in_array($storeId, $ids);
    }

    public function changeResetPasswordLinkToken($customer, $passwordLinkToken)
    {
        if (!is_string($passwordLinkToken) || empty($passwordLinkToken)) {
            throw new InputException(
                __(
                    'Invalid value of "%value" provided for the %fieldName field.',
                    ['value' => $passwordLinkToken, 'fieldName' => 'password reset token']
                )
            );
        }
        if (is_string($passwordLinkToken) && !empty($passwordLinkToken)) {
            $customerSecure = $this->customerRegistry->retrieveSecureData($customer->getId());
            $customerSecure->setRpToken($passwordLinkToken);
            $customerSecure->setRpTokenCreatedAt((new \DateTime())->format(DateTime::DATETIME_PHP_FORMAT));
            $this->customerRepository->save($customer);
        }
        return true;
    }

    /**
     * Send either confirmation or welcome email after an account creation
     *
     * @param \Magento\Customer\Api\Data\CustomerInterface $customer
     * @param string $redirectUrl
     * @return void
     */

    protected function sendEmailConfirmation(\Magento\Customer\Api\Data\CustomerInterface $customer, $redirectUrl)
    {
        try {
            $hash = $this->customerRegistry->retrieveSecureData($customer->getId())->getPasswordHash();
            $templateType = self::NEW_ACCOUNT_EMAIL_REGISTERED;
            if ($this->isConfirmationRequired($customer) && $hash != '') {
                $templateType = self::NEW_ACCOUNT_EMAIL_CONFIRMATION;
            } elseif ($hash == '') {
                $templateType = self::NEW_ACCOUNT_EMAIL_REGISTERED_NO_PASSWORD;
            }
            $this->getEmailNotification()->newAccount($customer, $templateType, $redirectUrl, $customer->getStoreId());
        } catch (MailException $e) {
            // If we are not able to send a new account email, this should be ignored
            $this->_logger->critical($e);
        }
    }


    protected function isConfirmationRequired($customer)
    {
        if ($this->canSkipConfirmation($customer)) {
            return false;
        }

        return (bool)$this->scopeConfig->getValue(
            self::XML_PATH_IS_CONFIRM,
            ScopeInterface::SCOPE_WEBSITES,
            $customer->getWebsiteId()
        );
    }

    protected function canSkipConfirmation($customer)
    {
        if (!$customer->getId()) {
            return false;
        }

        /* If an email was used to start the registration process and it is the same email as the one
           used to register, then this can skip confirmation.
           */
        $skipConfirmationIfEmail = $this->registry->registry("skip_confirmation_if_email");
        if (!$skipConfirmationIfEmail) {
            return false;
        }

        return strtolower($skipConfirmationIfEmail) === strtolower($customer->getEmail());
    }

    private function getEmailNotification()
    {
        if (!($this->emailNotification instanceof EmailNotificationInterface)) {
            return \Magento\Framework\App\ObjectManager::getInstance()->get(
                EmailNotificationInterface::class
            );
        } else {
            return $this->emailNotification;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getCustomersByAccount($accountId)
    {
        $result = [];

        $accountModel = $this->accountFactory->create();
        $accountModel->load($accountId);
        if (!$accountModel->getId()) {
            throw new NoSuchEntityException(
                __('We can\'t specify an account.')
            );
        }
        $collection = $this->_customerCollectionFactory->create()
            ->addAttributeToSelect('*')
            ->addAttributeToFilter('account_id', $accountId);
        foreach ($collection as $customer) {
            $result[] = $this->customerRepository->getById($customer->getId());
        }
        return $result;
    }
}