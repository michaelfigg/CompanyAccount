<?php

namespace Tigren\CompanyAccount\Setup;

use Magento\Customer\Model\Customer;
use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Eav\Model\Entity\Attribute\SetFactory as AttributeSetFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

/**
 * @codeCoverageIgnore
 */
class InstallData implements InstallDataInterface
{
    /**
     * Customer setup factory
     *
     * @var CustomerSetupFactory
     */
    private $customerSetupFactory;
    /**
     * @var AttributeSetFactory
     */
    private $attributeSetFactory;

    public function __construct(
        CustomerSetupFactory $customerSetupFactory,
        AttributeSetFactory $attributeSetFactory
    ){
        $this->customerSetupFactory = $customerSetupFactory;
        $this->attributeSetFactory = $attributeSetFactory;
    }

    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        /** @var CustomerSetup $customerSetup */
        $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);
        $setup->startSetup();

        $customerEntity = $customerSetup->getEavConfig()->getEntityType('customer');
        $attributeSetId = $customerEntity->getDefaultAttributeSetId();

        $attributeSet = $this->attributeSetFactory->create();
        $attributeGroupId = $attributeSet->getDefaultGroupId($attributeSetId);

        $customerSetup->addAttribute(Customer::ENTITY, 'account_id', [
            'type' => 'int',
            'label' => 'Account ID',
            'required' => false,
            'visible' => false,
            'user_defined' => true,
            'position' => 999,
            'system' => false,
            'sort_order' => 900,
//            'is_used_in_grid' => true,
            'is_filterable_in_grid' => true
        ]);

        $attribute = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, 'account_id')
            ->addData([
                'attribute_set_id' => $attributeSetId,
                'attribute_group_id' => $attributeGroupId,
                'used_in_forms' => ['adminhtml_customer']
            ]);
        $attribute->save();
        // create attribute Job title
        $customerSetup->addAttribute(Customer::ENTITY, 'job_title', [
            'type' => 'varchar',
            'label' => 'Job Title',
            'input' => 'text',
            'required' => false,
            'visible' => false,
            'user_defined' => true,
            'position' => 999,
            'system' => false,
            'sort_order' => 900,
//            'is_used_in_grid' => true,
            'is_filterable_in_grid' => true
        ]);

        $attribute = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, 'job_title')
            ->addData([
                'attribute_set_id' => $attributeSetId,
                'attribute_group_id' => $attributeGroupId,
                'used_in_forms' => ['adminhtml_customer','customer_account_create']
            ]);
        $attribute->save();
        // create attribute phone number
        $customerSetup->addAttribute(Customer::ENTITY, 'phone_number', [
            'type' => 'varchar',
            'label' => 'Phone Number',
            'input' => 'text',
            'required' => false,
            'visible' => false,
            'user_defined' => true,
            'position' => 999,
            'system' => false,
            'sort_order' => 900,
//            'is_used_in_grid' => true,
            'is_filterable_in_grid' => true
        ]);

        $attribute = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, 'phone_number')
            ->addData([
                'attribute_set_id' => $attributeSetId,
                'attribute_group_id' => $attributeGroupId,
                'used_in_forms' => ['adminhtml_customer','customer_account_create']
            ]);
        $attribute->save();
        // create attribute is active
        $customerSetup->addAttribute(Customer::ENTITY, 'is_active', [
            'type' => 'int',
            'label' => 'Is Active',
            'required' => false,
            'default' => 1,
            'visible' => false,
            'user_defined' => true,
            'position' => 999,
            'system' => false,
            'sort_order' => 900,
//            'is_used_in_grid' => true,
            'is_filterable_in_grid' => true
        ]);

        $attribute = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, 'is_active')
            ->addData([
                'attribute_set_id' => $attributeSetId,
                'attribute_group_id' => $attributeGroupId,
                'used_in_forms' => ['adminhtml_customer','customer_account_create']
            ]);
        $attribute->save();
        $customerSetup->addAttribute(Customer::ENTITY, 'is_business', [
            'type' => 'int',
            'label' => 'Is Business',
            'required' => false,
            'default' => 1,
            'visible' => false,
            'user_defined' => true,
            'position' => 999,
            'system' => false,
            'sort_order' => 900,
//            'is_used_in_grid' => true,
            'is_filterable_in_grid' => true
        ]);

        $attribute = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, 'is_business')
            ->addData([
                'attribute_set_id' => $attributeSetId,
                'attribute_group_id' => $attributeGroupId,
                'used_in_forms' => ['adminhtml_customer','customer_account_create']
            ]);
        $attribute->save();

        $setup->endSetup();

    }

}
