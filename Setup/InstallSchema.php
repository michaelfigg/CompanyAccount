<?php
/**
 * @copyright Copyright (c) 2017 www.tigren.com
 */

namespace Tigren\CompanyAccount\Setup;

use Magento\Framework\DB\Ddl\Table;

class InstallSchema implements \Magento\Framework\Setup\InstallSchemaInterface
{
    /**
     * install tables
     *
     * @param \Magento\Framework\Setup\SchemaSetupInterface $setup
     * @param \Magento\Framework\Setup\ModuleContextInterface $context
     * @return void
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(\Magento\Framework\Setup\SchemaSetupInterface $setup, \Magento\Framework\Setup\ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
        $table = $installer->getConnection()->newTable(
            $installer->getTable('tigren_comaccount_account')
        )
            ->addColumn(
                'account_id',
                Table::TYPE_INTEGER,
                null,
                [
                    'identity' => true,
                    'nullable' => false,
                    'primary' => true,
                    'unsigned' => true,
                ],
                'Account ID'
            )
            ->addColumn('company', Table::TYPE_TEXT, 255, ['nullable => false'], 'Company Name')
            ->addColumn('telephone', Table::TYPE_TEXT, 255, [], 'Telephone')
            ->addColumn('tax', Table::TYPE_TEXT, 255, [], 'Tax Number')
            ->addColumn('primary_manager', Table::TYPE_TEXT, 255, [
                'defalut' => null
            ], 'Tax Number')
            ->addColumn('secondary_manager', Table::TYPE_TEXT, 255, [
                'defalut' => null
            ], 'Tax Number')
            ->setComment('Company Account Table');
        $installer->getConnection()->createTable($table);

        $table = $installer->getConnection()
            ->newTable($installer->getTable('tigren_comaccount_account_admin'))
            ->addColumn(
                'account_id', Table::TYPE_INTEGER, 11, ['unsigned' => true, 'nullable' => false, 'primary' => true]
            )
            ->addColumn(
                'admin_id', Table::TYPE_INTEGER, 11, ['unsigned' => true, 'nullable' => false, 'primary' => true]
            )
            ->addIndex(
                $installer->getIdxName('tigren_comaccount_account_admin', ['account_id']), ['account_id']
            )
            ->addIndex(
                $installer->getIdxName('tigren_comaccount_account_admin', ['admin_id']), ['admin_id']
            )
            ->setComment('Account Customer Admin');
        $installer->getConnection()->createTable($table);

        $table = $installer->getConnection()
            ->newTable($installer->getTable('tigren_comaccount_account_store'))
            ->addColumn(
                'account_id', Table::TYPE_INTEGER, 11, ['unsigned' => true, 'nullable' => false, 'primary' => true]
            )
            ->addColumn(
                'store_id', Table::TYPE_SMALLINT, 6, ['unsigned' => true, 'nullable' => false, 'primary' => true]
            )
            ->addIndex(
                $installer->getIdxName('tigren_comaccount_account_store', ['account_id']), ['account_id']
            )
            ->addIndex(
                $installer->getIdxName('tigren_comaccount_account_store', ['store_id']), ['store_id']
            )
            ->addForeignKey(
                $installer->getFkName('tigren_comaccount_account_store', 'account_id', 'tigren_comaccount_account', 'account_id'), 'account_id', $installer->getTable('tigren_comaccount_account'), 'account_id', Table::ACTION_CASCADE
            )
            ->addForeignKey(
                $installer->getFkName('tigren_comaccount_account_store', 'store_id', 'store', 'store_id'), 'store_id', $installer->getTable('store'), 'store_id', Table::ACTION_CASCADE
            )
            ->setComment('Account Store');
        $installer->getConnection()->createTable($table);

        $installer->endSetup();
    }
}