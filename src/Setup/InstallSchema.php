<?php
/**
 * InstallSchema.php
 *
 * PHP Version 5
 *
 * @category addictedtomagento_dynamic-forms
 * @package  addictedtomagento_dynamic-forms
 * @author   David Verholen <david@verholen.com>
 * @license  http://opensource.org/licenses/OSL-3.0 OSL-3.0
 * @link     http://github.com/davidverholen
 */

namespace AddictedToMagento\DynamicForms\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Adapter\AdapterInterface;

/**
 * Class InstallSchema
 *
 * @category addictedtomagento_dynamic-forms
 * @package  ${NAMESPACE}
 * @author   David Verholen <david@verholen.com>
 * @license  http://opensource.org/licenses/OSL-3.0 OSL-3.0
 * @link     http://github.com/davidverholen
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();

        /**
         * Create table 'atm_dynamic_form'
         */
        $table = $installer->getConnection()->newTable(
            $installer->getTable('atm_dynamic_form')
        )->addColumn(
            'form_id',
            Table::TYPE_SMALLINT,
            null,
            ['identity' => true, 'nullable' => false, 'primary' => true],
            'Form ID'
        )->addColumn(
            'title',
            Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Form Title'
        )->addColumn(
            'identifier',
            Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Form String Identifier'
        )->addColumn(
            'css_classes',
            Table::TYPE_TEXT,
            255,
            [],
            'Form Css Classes'
        )->addColumn(
            'creation_time',
            Table::TYPE_TIMESTAMP,
            null,
            [],
            'Form Creation Time'
        )->addColumn(
            'update_time',
            Table::TYPE_TIMESTAMP,
            null,
            [],
            'Form Modification Time'
        )->addColumn(
            'is_active',
            Table::TYPE_SMALLINT,
            null,
            ['nullable' => false, 'default' => '1'],
            'Is Form Active'
        )->addIndex(
            $setup->getIdxName(
                $installer->getTable('atm_dynamic_form'),
                ['title', 'identifier'],
                AdapterInterface::INDEX_TYPE_FULLTEXT
            ),
            ['title', 'identifier'],
            ['type' => AdapterInterface::INDEX_TYPE_FULLTEXT]
        )->setComment(
            'Dynamic Form Table'
        );
        $installer->getConnection()->createTable($table);

        /**
         * Create table 'atm_dynamic_form_store'
         */
        $table = $installer->getConnection()->newTable(
            $installer->getTable('atm_dynamic_form_store')
        )->addColumn(
            'form_id',
            Table::TYPE_SMALLINT,
            null,
            ['nullable' => false, 'primary' => true],
            'Form ID'
        )->addColumn(
            'store_id',
            Table::TYPE_SMALLINT,
            null,
            ['unsigned' => true, 'nullable' => false, 'primary' => true],
            'Store ID'
        )->addIndex(
            $installer->getIdxName('atm_dynamic_form_store', ['store_id']),
            ['store_id']
        )->addForeignKey(
            $installer->getFkName('atm_dynamic_form_store', 'form_id', 'atm_dynamic_form', 'form_id'),
            'form_id',
            $installer->getTable('atm_dynamic_form'),
            'form_id',
            Table::ACTION_CASCADE
        )->addForeignKey(
            $installer->getFkName('atm_dynamic_form_store', 'store_id', 'store', 'store_id'),
            'store_id',
            $installer->getTable('store'),
            'store_id',
            Table::ACTION_CASCADE
        )->setComment(
            'Dynamic FOrm To Store Linkage Table'
        );
        $installer->getConnection()->createTable($table);

        $installer->endSetup();
    }
}
