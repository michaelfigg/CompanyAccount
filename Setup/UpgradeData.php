<?php

namespace Tigren\CompanyAccount\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Customer\Model\Customer;
use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Eav\Model\Entity\Attribute\SetFactory as AttributeSetFactory;

class UpgradeData implements UpgradeDataInterface
{
    private $customerSetupFactory;
    private $attributeSetFactory;
    private $resourceConn;

    public function __construct(
        CustomerSetupFactory $customerSetupFactory,
        AttributeSetFactory $attributeSetFactory,
        \Magento\Framework\App\ResourceConnection $resourceConn
    ){
        $this->customerSetupFactory = $customerSetupFactory;
        $this->attributeSetFactory = $attributeSetFactory;
        $this->resourceConn = $resourceConn;
    }

    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);
        $setup->startSetup();

        $customerEntity = $customerSetup->getEavConfig()->getEntityType('customer');
        $attributeSetId = $customerEntity->getDefaultAttributeSetId();

        $attributeSet = $this->attributeSetFactory->create();
        $attributeGroupId = $attributeSet->getDefaultGroupId($attributeSetId);

        if (version_compare($context->getVersion(), '1.1.0', '<')) {

            $customerSetup->addAttribute(Customer::ENTITY, 'enable_enter_shipping_address', [
                'type' => 'int',
                'label' => 'Enable Enter Shipping Address',
                'required' => false,
                'visible' => false,
                'user_defined' => true,
                'position' => 1000,
                'system' => false,
                'sort_order' => 901,
//                'is_used_in_grid' => true,
                'is_filterable_in_grid' => true,
                'default' => 0
            ]);

            $attribute = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, 'enable_enter_shipping_address')
                ->addData([
                    'attribute_set_id' => $attributeSetId,
                    'attribute_group_id' => $attributeGroupId,
                    'used_in_forms' => ['adminhtml_customer']
                ]);
            $attribute->save();

        }

        if (version_compare($context->getVersion(), '1.4.0', '<')) {
            $conn = $this->getConnection();
            $customer_entity = $conn->getTableName('customer_entity');
            $customer_entity_int = $conn->getTableName('customer_entity_int');
            $eav_attribute = $conn->getTableName('eav_attribute');

            //Delete all customers with no account_id set
            $this->getConnection()->query(
                "DELETE FROM {$customer_entity}
                WHERE entity_id NOT IN (
                    SELECT DISTINCT cei.entity_id FROM {$customer_entity_int} cei
                    LEFT JOIN {$eav_attribute} ea ON cei.attribute_id = ea.attribute_id
                    WHERE ea.attribute_code = 'account_id'
                )"
            );
            
            $customerSetup->updateAttribute(
                \Magento\Customer\Model\Customer::ENTITY,
                'account_id',                    
                'required',
                true
            );
        }

        if (version_compare($context->getVersion(), '1.5.0', '<')) {
            $this->setupAttributes150($customerSetup, $attributeSetId, $attributeGroupId);
        }

        $setup->endSetup();
    }

    private function getConnection()
    {
        return $this->resourceConn->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);
    }

    private function setupAttributes150($customerSetup, $attributeSetId, $attributeGroupId)
    {
        //Tigren version 1.3.3 (Daisy)

        //create attribute Portal Source
        $customerSetup->addAttribute(Customer::ENTITY, 'portal_source', [
            'type' => 'varchar',
            'label' => 'Portal Source',
            'required' => false,
            'visible' => false,
            'user_defined' => true,
            'position' => 1000,
            'system' => false,
            'sort_order' => 901,
            'is_filterable_in_grid' => true
        ]);

        $attribute = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, 'portal_source')
            ->addData([
                'attribute_set_id' => $attributeSetId,
                'attribute_group_id' => $attributeGroupId,
                'used_in_forms' => ['adminhtml_customer']
            ]);
        $attribute->save();

        //create attribute Portal Username
        $customerSetup->addAttribute(Customer::ENTITY, 'portal_username', [
            'type' => 'varchar',
            'label' => 'Portal Username',
            'required' => false,
            'visible' => false,
            'user_defined' => true,
            'position' => 1000,
            'system' => false,
            'sort_order' => 901,
            'is_filterable_in_grid' => true
        ]);

        $attribute = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, 'portal_username')
            ->addData([
                'attribute_set_id' => $attributeSetId,
                'attribute_group_id' => $attributeGroupId,
                'used_in_forms' => ['adminhtml_customer']
            ]);
        $attribute->save();

        //create attribute Credit Limit
        $customerSetup->addAttribute(Customer::ENTITY, 'credit_limit', [
            'type' => 'varchar',
            'label' => 'Credit Limit',
            'required' => false,
            'visible' => false,
            'user_defined' => true,
            'position' => 1000,
            'system' => false,
            'sort_order' => 901,
            'is_filterable_in_grid' => true
        ]);

        $attribute = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, 'credit_limit')
            ->addData([
                'attribute_set_id' => $attributeSetId,
                'attribute_group_id' => $attributeGroupId,
                'used_in_forms' => ['adminhtml_customer']
            ]);
        $attribute->save();

        //create attribute Credit Terms
        $customerSetup->addAttribute(Customer::ENTITY, 'credit_terms', [
            'type' => 'varchar',
            'label' => 'Credit Terms',
            'required' => false,
            'visible' => false,
            'user_defined' => true,
            'position' => 1000,
            'system' => false,
            'sort_order' => 901,
            'is_filterable_in_grid' => true
        ]);

        $attribute = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, 'credit_terms')
            ->addData([
                'attribute_set_id' => $attributeSetId,
                'attribute_group_id' => $attributeGroupId,
                'used_in_forms' => ['adminhtml_customer']
            ]);
        $attribute->save();

        //create attribute Credit Terms
        $customerSetup->addAttribute(Customer::ENTITY, 'credit_terms', [
            'type' => 'varchar',
            'label' => 'Credit Terms',
            'required' => false,
            'visible' => false,
            'user_defined' => true,
            'position' => 1000,
            'system' => false,
            'sort_order' => 901,
            'is_filterable_in_grid' => true
        ]);

        $attribute = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, 'credit_terms')
            ->addData([
                'attribute_set_id' => $attributeSetId,
                'attribute_group_id' => $attributeGroupId,
                'used_in_forms' => ['adminhtml_customer']
            ]);
        $attribute->save();

        //create attribute Balance
        $customerSetup->addAttribute(Customer::ENTITY, 'balance', [
            'type' => 'varchar',
            'label' => 'Balance',
            'required' => false,
            'visible' => false,
            'user_defined' => true,
            'position' => 1000,
            'system' => false,
            'sort_order' => 901,
            'is_filterable_in_grid' => true
        ]);

        $attribute = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, 'balance')
            ->addData([
                'attribute_set_id' => $attributeSetId,
                'attribute_group_id' => $attributeGroupId,
                'used_in_forms' => ['adminhtml_customer']
            ]);
        $attribute->save();

        //create attribute Sales Person ID
        $customerSetup->addAttribute(Customer::ENTITY, 'sales_person_id', [
            'type' => 'int',
            'label' => 'Sales Person ID',
            'required' => false,
            'visible' => false,
            'user_defined' => true,
            'position' => 1000,
            'system' => false,
            'sort_order' => 901,
            'is_filterable_in_grid' => true
        ]);

        $attribute = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, 'sales_person_id')
            ->addData([
                'attribute_set_id' => $attributeSetId,
                'attribute_group_id' => $attributeGroupId,
                'used_in_forms' => ['adminhtml_customer']
            ]);
        $attribute->save();

        //create attribute Sales Person Name
        $customerSetup->addAttribute(Customer::ENTITY, 'sales_person_name', [
            'type' => 'varchar',
            'label' => 'Sales Person ID',
            'required' => false,
            'visible' => false,
            'user_defined' => true,
            'position' => 1000,
            'system' => false,
            'sort_order' => 901,
            'is_filterable_in_grid' => true
        ]);

        $attribute = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, 'sales_person_name')
            ->addData([
                'attribute_set_id' => $attributeSetId,
                'attribute_group_id' => $attributeGroupId,
                'used_in_forms' => ['adminhtml_customer']
            ]);
        $attribute->save();

        //create attribute Payment Options
        $customerSetup->addAttribute(Customer::ENTITY, 'payment_options', [
            'type' => 'varchar',
            'label' => 'Payment Options',
            'required' => false,
            'visible' => false,
            'user_defined' => true,
            'position' => 1000,
            'system' => false,
            'sort_order' => 901,
            'is_filterable_in_grid' => true
        ]);

        $attribute = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, 'payment_options')
            ->addData([
                'attribute_set_id' => $attributeSetId,
                'attribute_group_id' => $attributeGroupId,
                'used_in_forms' => ['adminhtml_customer']
            ]);
        $attribute->save();

        //Tigren version 1.3.8

        //create attribute url_login_external
        $customerSetup->addAttribute(Customer::ENTITY, 'url_login_external', [
            'type' => 'varchar',
            'label' => 'Url Login External',
            'required' => false,
            'visible' => false,
            'user_defined' => true,
            'position' => 1000,
            'system' => false,
            'sort_order' => 901,
            'is_filterable_in_grid' => true
        ]);

        $attribute = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, 'url_login_external')
            ->addData([
                'attribute_set_id' => $attributeSetId,
                'attribute_group_id' => $attributeGroupId,
                'used_in_forms' => ['adminhtml_customer']
            ]);
        $attribute->save();

        //create attribute url_login_external
        $customerSetup->addAttribute(Customer::ENTITY, 'message_login_external', [
            'type' => 'varchar',
            'label' => 'Message Login External',
            'required' => false,
            'visible' => false,
            'user_defined' => true,
            'position' => 1000,
            'system' => false,
            'sort_order' => 901,
            'is_filterable_in_grid' => true
        ]);

        $attribute = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, 'message_login_external')
            ->addData([
                'attribute_set_id' => $attributeSetId,
                'attribute_group_id' => $attributeGroupId,
                'used_in_forms' => ['adminhtml_customer']
            ]);
        $attribute->save();

        //Tigren version 1.3.9
        //create attribute url_login_external
        $customerSetup->addAttribute(Customer::ENTITY, 'sales_person_email', [
            'type' => 'varchar',
            'label' => 'Sales Person Email',
            'required' => false,
            'visible' => false,
            'user_defined' => true,
            'position' => 1000,
            'system' => false,
            'sort_order' => 901,
            'is_filterable_in_grid' => true
        ]);

        $attribute = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, 'sales_person_email')
            ->addData([
                'attribute_set_id' => $attributeSetId,
                'attribute_group_id' => $attributeGroupId,
                'used_in_forms' => ['adminhtml_customer']
            ]);
        $attribute->save();
    }
}
