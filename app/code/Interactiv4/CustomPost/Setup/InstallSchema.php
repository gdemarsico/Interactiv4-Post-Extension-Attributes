<?php
/**
 * @author Interactiv4 Team
 * @copyright  Copyright © Interactiv4 (https://www.interactiv4.com)
 */

namespace Interactiv4\CustomPost\Setup;

use Interactiv4\CustomPost\Api\Data\PostInterface;
use Interactiv4\Post\Api\Data\EntityInterface;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * Class InstallSchema
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * {@inheritdoc}
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();

        //Custom post
        $installer->getConnection()->dropTable($installer->getTable(PostInterface::SCHEMA_TABLE));
        $this->installTableCustomPost($installer);

        $installer->endSetup();
    }


    /**
     * Create table relations between custom entity and custom post
     *
     * @param SchemaSetupInterface $installer
     * @throws \Zend_Db_Exception
     */
    private function installTableCustomPost(SchemaSetupInterface $installer)
    {
        $table = $installer->getConnection()->newTable(
            $installer->getTable(PostInterface::SCHEMA_TABLE)
        )->addColumn(
            PostInterface::FIELD_ID,
            Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
            'Custom post Id'
        )->addColumn(
            PostInterface::FIELD_POST_ID,
            Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => false],
            'Post Id'
        )->addColumn(
            PostInterface::FIELD_SHORT_DESCRIPTION,
            Table::TYPE_TEXT,
            null,
            ['nullable' => false, 'default' => ''],
            'Short description'
        )->addIndex(
            $installer->getIdxName(
                $installer->getTable(PostInterface::SCHEMA_TABLE),
                [PostInterface::FIELD_POST_ID],
                AdapterInterface::INDEX_TYPE_UNIQUE
            ),
            [PostInterface::FIELD_POST_ID],
            ['type' => AdapterInterface::INDEX_TYPE_UNIQUE]
        )->addForeignKey(
            $installer->getFkName(
                PostInterface::SCHEMA_TABLE,
                PostInterface::FIELD_POST_ID,
                EntityInterface::TABLE,
                EntityInterface::ID
            ),
            PostInterface::FIELD_POST_ID,
            $installer->getTable(EntityInterface::TABLE),
            EntityInterface::ID,
            Table::ACTION_CASCADE
        )->setComment(
            'Custom Post'
        );

        $installer->getConnection()->createTable($table);
    }
}
