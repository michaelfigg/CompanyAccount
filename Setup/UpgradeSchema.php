<?php

namespace Tigren\CompanyAccount\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\DB\Ddl\Table;

class UpgradeSchema implements UpgradeSchemaInterface
{
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {

        $installer = $setup;

        $installer->startSetup();

        if (version_compare($context->getVersion(), '1.2.0', '<')) {
            $table = $installer->getConnection()
                ->newTable($installer->getTable('tigren_comaccount_account_address'))
                ->addColumn(
                    'address_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    null,
                    ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                    'Id'
                )
                ->addColumn(
                    'account_id',
                    Table::TYPE_INTEGER,
                    11,
                    [
                        'unsigned' => true,
                        'nullable' => false,
                        'primary' => true
                    ]
                )
                ->addColumn(
                    'created_at',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                    null,
                    [],
                    'Created At'
                )
                ->addColumn(
                    'updated_at',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                    null,
                    [],
                    'Created At'
                )
                ->addColumn('city',Table::TYPE_TEXT,255,['nullable' => false],'City')
                ->addColumn('company',Table::TYPE_TEXT,255,['nullable' => false],'Company Name')
                ->addColumn('country_id',Table::TYPE_TEXT,255,['nullable' => false],'Country Id')
                ->addColumn('fax',Table::TYPE_TEXT,255,['nullable' => true],'Fax')
                ->addColumn('firstname',Table::TYPE_TEXT,255,['nullable' => false],'First Name')
                ->addColumn('lastname',Table::TYPE_TEXT,255,['nullable' => false],'Last Name')
                ->addColumn('middlename',Table::TYPE_TEXT,255,['nullable' => true],'Middle Name')
                ->addColumn('postcode',Table::TYPE_TEXT,255,['nullable' => false],'Zip/Postal Code')
                ->addColumn('prefix',Table::TYPE_TEXT,255,['nullable' => true],'Name Prefix')
                ->addColumn('region',Table::TYPE_TEXT,255,['nullable' => true],'State/Province')
                ->addColumn('region_id',Table::TYPE_TEXT,255,['nullable' => true],'State/Province')
                ->addColumn('street',Table::TYPE_TEXT,255,['nullable' => false],'Street Address')
                ->addColumn('suffix',Table::TYPE_TEXT,255,['nullable' => true],'Name Suffix')
                ->addColumn('telephone',Table::TYPE_TEXT,255,['nullable' => false],'Phone Number')
                ->addColumn('vat_id',Table::TYPE_TEXT,255,['nullable' => true],'VAT number')
                ->addColumn('vat_is_valid',Table::TYPE_TEXT,255,['nullable' => true],'VAT number validity')
                ->addColumn('vat_request_date',Table::TYPE_TEXT,255,['nullable' => true],'VAT number validation request date')
                ->addColumn('vat_request_id',Table::TYPE_TEXT,255,['nullable' => true],'VAT number validation request ID')
                ->addColumn('vat_request_success',Table::TYPE_TEXT,255,['nullable' => true],'VAT number validation request success')
                ->addForeignKey(
                    $installer->getFkName('tigren_comaccount_account_address', 'account_id', 'tigren_comaccount_account', 'account_id'),
                    'account_id',
                    $installer->getTable('tigren_comaccount_account'),
                    'account_id',
                    \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
                )
                ->setComment('Account Address');

            $installer->getConnection()->createTable($table);
        }

        if (version_compare($context->getVersion(), '1.3.0', '<')) {
            $table = $setup->getTable('tigren_comaccount_account_address');
            $connection = $setup->getConnection();
            $connection->addColumn(
                $table,
                'is_billing',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                    6,
                    'default' => 0,
                    'nullable' => false,
                    'comment' =>'Is Billing Address'
                ]
            );
        }

        if (version_compare($context->getVersion(), '1.3.1', '<')) {
            $table = $setup->getTable('tigren_comaccount_account');
            $connection = $setup->getConnection();
            $connection->addColumn(
                $table,
                'logo_image_link',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    null,
                    'default' => '',
                    'nullable' => true,
                    'comment' =>'Company Logo'
                ]
            );
        }

        $installer->endSetup();
    }
}
