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
            $this->createAddressTable($installer);
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
                    'comment' => 'Is Billing Address'
                ]
            );
            $connection->addColumn(
                $table,
                'is_shipping_default',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                    6,
                    'default' => 0,
                    'nullable' => false,
                    'comment' => 'Is Shipping Default Address'
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
                    'comment' => 'Company Logo'
                ]
            );
        }

        if (version_compare($context->getVersion(), '1.3.2', '<')) {
            $this->upgrade132($setup);
        }

        if (version_compare($context->getVersion(), '1.3.3', '<')) {
            $this->addIsShippingDefaultAndAddressEmail($setup);
        }

        if (version_compare($context->getVersion(), '1.4.0', '<')){
            $this->addAccountAdminFK($installer);
        }

        if (version_compare($context->getVersion(), '1.5.0', '<')){
            $this->upgrade150($installer);
        }

        $installer->endSetup();
    }

    private function createAddressTable(SchemaSetupInterface $installer)
    {
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
            ->addColumn('city', Table::TYPE_TEXT, 255, ['nullable' => false], 'City')
            ->addColumn('company', Table::TYPE_TEXT, 255, ['nullable' => false], 'Company Name')
            ->addColumn('country_id', Table::TYPE_TEXT, 255, ['nullable' => false], 'Country Id')
            ->addColumn('fax', Table::TYPE_TEXT, 255, ['nullable' => true], 'Fax')
            ->addColumn('firstname', Table::TYPE_TEXT, 255, ['nullable' => false], 'First Name')
            ->addColumn('lastname', Table::TYPE_TEXT, 255, ['nullable' => false], 'Last Name')
            ->addColumn('middlename',Table::TYPE_TEXT, 255, ['nullable' => true], 'Middle Name')
            ->addColumn('postcode', Table::TYPE_TEXT, 255, ['nullable' => false], 'Zip/Postal Code')
            ->addColumn('prefix', Table::TYPE_TEXT, 255, ['nullable' => true], 'Name Prefix')
            ->addColumn('region', Table::TYPE_TEXT, 255, ['nullable' => true], 'State/Province')
            ->addColumn('region_id', Table::TYPE_TEXT, 255, ['nullable' => true], 'State/Province')
            ->addColumn('street', Table::TYPE_TEXT, 255, ['nullable' => false], 'Street Address')
            ->addColumn('suffix', Table::TYPE_TEXT, 255, ['nullable' => true], 'Name Suffix')
            ->addColumn('telephone', Table::TYPE_TEXT, 255, ['nullable' => false], 'Phone Number')
            ->addColumn('vat_id', Table::TYPE_TEXT, 255, ['nullable' => true],'VAT number')
            ->addColumn('vat_is_valid', Table::TYPE_TEXT, 255, ['nullable' => true], 'VAT number validity')
            ->addColumn('vat_request_date', Table::TYPE_TEXT, 255, ['nullable' => true], 'VAT number validation request date')
            ->addColumn('vat_request_id', Table::TYPE_TEXT, 255, ['nullable' => true], 'VAT number validation request ID')
            ->addColumn('vat_request_success', Table::TYPE_TEXT, 255, ['nullable' => true], 'VAT number validation request success')
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

    private function upgrade132(SchemaSetupInterface $setup)
    {
        $table = $setup->getTable('tigren_comaccount_account');
        $connection = $setup->getConnection();
        $connection->addColumn(
            $table,
            'pay_on_account',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_BOOLEAN,
                null,
                'default' => 1,
                'nullable' => true,
                'comment' =>'Pay On Account'
            ]
        );
        $connection->addColumn(
            $table,
            'account_group_id',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                'default' => null,
                'nullable' => true,
                'comment' =>'Account Group ID'
            ]
        );
        $connection->addColumn(
            $table,
            'public_notes',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                'default' => null,
                'nullable' => true,
                'comment' =>'Public Notes'
            ]
        );
        $connection->addColumn(
            $table,
            'manager_first_name',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                'default' => null,
                'nullable' => true,
                'comment' =>'Manager First Name'
            ]
        );
        $connection->addColumn(
            $table,
            'manager_last_name',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                'default' => null,
                'nullable' => true,
                'comment' =>'Manager Last Name'
            ]
        );
        $connection->addColumn(
            $table,
            'manager_telephone',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                'default' => null,
                'nullable' => true,
                'comment' =>'Manager Telephone'
            ]
        );
        $connection->addColumn(
            $table,
            'manager_email',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                'default' => null,
                'nullable' => true,
                'comment' =>'Manager Email'
            ]
        );
        $connection->addColumn(
            $table,
            'manager_profile',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                'default' => null,
                'nullable' => true,
                'comment' =>'Manager Profile Image Link'
            ]
        );
    }

    private function addIsShippingDefaultAndAddressEmail(SchemaSetupInterface $setup)
    {
        $table = $setup->getTable('tigren_comaccount_account_address');
        $connection = $setup->getConnection();

        $connection->addColumn(
            $table,
            'is_shipping_default',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                6,
                'default' => 0,
                'nullable' => false,
                'comment' =>'Is Shipping Default Address'
            ]
        );
        $connection->addColumn(
            $table,
            'email',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                'default' => null,
                'nullable' => true,
                'comment' =>'Address Email'
            ]
        );
    }

    private function addAccountAdminFK(SchemaSetupInterface $installer)
    {
        //TODO: Do we need to manually clear any orphaned records before adding this FK
        $installer->getConnection()->addForeignKey(
            $installer->getFkName('tigren_comaccount_account_admin', 'account_id', 'tigren_comaccount_account', 'account_id'),
            $installer->getTable('tigren_comaccount_account_admin'),
            'account_id',
            $installer->getTable('tigren_comaccount_account'),
            'account_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
        );
    }

    private function upgrade150($installer)
    {
        $setup = $installer;
        $connection = $setup->getConnection();
        $accountTable = $setup->getTable('tigren_comaccount_account');

        //Tigren version 1.3.4
        $externalLoginTable = $connection
            ->newTable($installer->getTable('tigren_comaccount_customer_login_external'))
            ->addColumn(
                'id',
                Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'nullable' => false, 'primary' => true, 'unsigned' => true],
                'Number ID'
            )
            ->addColumn(
                'customer_email',
                Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Customer Email'
            )
            ->addColumn(
                'customer_token',
                Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Customer Token'
            )
            ->addColumn(
                'created_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
                'Created At'
            )
            ->addColumn(
                'updated_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT_UPDATE],
                'Updated At'
            )
            ->setComment('Customer login external');
        $connection->createTable($externalLoginTable);

        //Tigren version 1.3.5
        $connection->addColumn(
            $accountTable,
            'portal_source',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                'default' => null,
                'nullable' => true,
                'comment' => 'Portal Source'
            ]
        );
        $connection->addColumn(
            $accountTable,
            'portal_username',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                'default' => null,
                'nullable' => true,
                'comment' => 'Portal Username'
            ]
        );
        $connection->addColumn(
            $accountTable,
            'credit_limit',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                'default' => null,
                'nullable' => true,
                'comment' => 'Credit Limit'
            ]
        );
        $connection->addColumn(
            $accountTable,
            'credit_terms',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                'default' => null,
                'nullable' => true,
                'comment' => 'Credit Terms'
            ]
        );
        $connection->addColumn(
            $accountTable,
            'balance',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                'default' => null,
                'nullable' => true,
                'comment' => 'Balance'
            ]
        );
        $connection->addColumn(
            $accountTable,
            'payment_options',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                'default' => null,
                'nullable' => true,
                'comment' => 'Payment Options'
            ]
        );

        //Tigren version 1.3.6
        $connection->changeColumn(
            $accountTable,
            'balance',
            'balance',
            ['type' => Table::TYPE_DECIMAL, 'length' => '10,3', 'nullable' => true, 'default' => null],
            'Balance'
        );
        $connection->changeColumn(
            $accountTable,
            'credit_limit',
            'credit_limit',
            ['type' => Table::TYPE_DECIMAL, 'length' => '10,3', 'nullable' => true, 'default' => null],
            'Credit Limit'
        );
        $connection->changeColumn(
            $accountTable,
            'credit_terms',
            'credit_terms',
            ['type' => Table::TYPE_NUMERIC, 'length' => '10,3', 'nullable' => true, 'default' => null],
            'Credit Terms'
        );

        //Tigren version 1.3.7
        $connection->dropColumn(
            $accountTable,
            'payment_options'
        );
        $connection->addColumn(
            $accountTable,
            'account_number',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                'default' => null,
                'nullable' => true,
                'comment' => 'Account Number'
            ]
        );

        $payOptsTable = $connection
            ->newTable($installer->getTable('tigren_comaccount_payment_options'))
            ->addColumn(
                'option_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Id'
            )
            ->addColumn(
                'account_id',
                Table::TYPE_INTEGER,
                11,
                ['unsigned' => true, 'nullable' => false, 'primary' => true]
            )
            ->addColumn(
                'credit_card',
                \Magento\Framework\DB\Ddl\Table::TYPE_BOOLEAN,
                null,
                ['nullable' => true, 'default' => 0],
                'Credit Card'
            )
            ->addColumn(
                'leasing',
                \Magento\Framework\DB\Ddl\Table::TYPE_BOOLEAN,
                null,
                ['nullable' => true, 'default' => 0],
                'Leasing'
            )
            ->addColumn(
                'on_account',
                \Magento\Framework\DB\Ddl\Table::TYPE_BOOLEAN,
                null,
                ['nullable' => true, 'default' => 0],
                'On Account'
            )
            ->addForeignKey(
                $installer->getFkName('tigren_comaccount_payment_options', 'account_id', 'tigren_comaccount_account', 'account_id'),
                'account_id',
                $accountTable,
                'account_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
            ->setComment('Account Payment Options');
        $installer->getConnection()->createTable($payOptsTable);

        //Tigren version 1.4.0
        $connection->changeColumn(
            'tigren_comaccount_account',
            'account_number',
            'account_number',
            ['type' => Table::TYPE_TEXT, 'nullable' => true, 'default' => null],
            'Account Number'
        );
    }
}
