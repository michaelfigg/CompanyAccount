<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Tigren\CompanyAccount\Block\Customer;

use Magento\Customer\Api\AccountManagementInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\AccountManagement;

/**
 * Customer edit form block
 *
 * @SuppressWarnings(PHPMD.DepthOfInheritance)
 */
class Edit extends \Magento\Customer\Block\Account\Dashboard
{
    protected $_coreRegistry;
    protected $_customerSession;
    protected $_customerCollectionFactory;
    protected $request;
    protected $customerFactory;
    protected $helper;
    protected $_customers;

    /**
     * Restore entity data from session. Entity and form code must be defined for the form.
     *
     * @param \Magento\Customer\Model\Metadata\Form $form
     * @param null $scope
     * @return \Magento\Customer\Block\Form\Register
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\App\RequestInterface $request,
        \Magento\Customer\Model\CustomerFactory $customerFactory,
        \Magento\Framework\Registry $registry,
        \Magento\Customer\Model\ResourceModel\Customer\CollectionFactory $customerCollectionFactory,
        \Magento\Newsletter\Model\SubscriberFactory $subscriberFactory,
        CustomerRepositoryInterface $customerRepository,
        AccountManagementInterface $customerAccountManagement,
        array $data = [],
        \Tigren\CompanyAccount\Helper\Data $helper,
        \Magento\Customer\Model\Customer $customer
    ) {
        $this->_coreRegistry = $registry;
        $this->request = $request;
        $this->customerFactory = $customerFactory;
        $this->_customerCollectionFactory = $customerCollectionFactory;
        $this->_customerSession = $customerSession;
        $this->helper = $helper;
        $this->_customers  = $customer;
        parent::__construct($context, $customerSession, $subscriberFactory, $customerRepository, $customerAccountManagement, $data);
    }

    public function restoreSessionData(\Magento\Customer\Model\Metadata\Form $form, $scope = null)
    {
        $formData = $this->getFormData();
        if (isset($formData['customer_data']) && $formData['customer_data']) {
            $request = $form->prepareRequest($formData['data']);
            $data = $form->extractData($request, $scope, false);
            $form->restoreData($data);
        }

        return $this;
    }

    /**
     * Retrieve form data
     *
     * @return array
     */
    protected function getFormData()
    {
        $data = $this->getData('form_data');
        if ($data === null) {
            $formData = $this->customerSession->getCustomer()->getCustomerFormData(true);
            $data = [];
            if ($formData) {
                $data['data'] = $formData;
                $data['customer_data'] = 1;
            }
            $this->setData('form_data', $data);
        }
        return $data;
    }

    public function getUserId()
    {
        return $this->request->getParam('id');
    }
    public function getUserJobTitle()
    {
        $setCustomercustomer = $this->_customers->load($this->getUserId());
        $customerData = $setCustomercustomer->getDataModel();
        $jobTitle = $customerData->getCustomAttribute('job_title')->getValue();
        return $jobTitle;
    }
    public function getUserPhoneNumber()
    {
        $setCustomercustomer = $this->_customers->load($this->getUserId());
        $customerData = $setCustomercustomer->getDataModel();
        $phoneNumber = $customerData->getCustomAttribute('phone_number')->getValue();
        return $phoneNumber;
    }
    public function getUserIsActive()
    {
        $setCustomercustomer = $this->_customers->load($this->getUserId());
        $customerData = $setCustomercustomer->getDataModel();
        $isActive = $customerData->getCustomAttribute('is_active')->getValue();
        return $isActive;
    }
    /**
     * Return the Customer given the user Id
     *
     * @return \Magento\Customer\Api\Data\CustomerInterface
     */
    public function getCustomer()
    {
        return $this->customerRepository->getById($this->getUserId());
    }

    /**
     * Return whether the form should be opened in an expanded mode showing the change password fields
     * @return bool
     * @SuppressWarnings(PHPMD.BooleanGetMethodName)
     */
    public function getChangePassword()
    {
        return $this->customerSession->getChangePassword();
    }

    /**
     * Get minimum password length
     *
     * @return string
     */
    public function getMinimumPasswordLength()
    {
        return $this->_scopeConfig->getValue(AccountManagement::XML_PATH_MINIMUM_PASSWORD_LENGTH);
    }

    /**
     * Get minimum password length
     *
     * @return string
     */
    public function getRequiredCharacterClassesNumber()
    {
        return $this->_scopeConfig->getValue(AccountManagement::XML_PATH_REQUIRED_CHARACTER_CLASSES_NUMBER);
    }

    public function isAdminOfAccount()
    {
         if($this->helper->isAdminOfAccount($this->getUserId())){
             return 1;
         }else{
             return 0;
         }
    }

}
