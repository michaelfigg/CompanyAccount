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

        $setup->endSetup();
    }

    private function getConnection()
    {
        return $this->resourceConn->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);
    }
}
