<?php
/**
 * Copyright © 2011-2017 Karliuka Vitalii(karliuka.vitalii@gmail.com)
 * 
 * See COPYING.txt for license details.
 */
namespace Faonni\Bitcoin\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

/**
 * Faonni_Bitcoin InstallSchema
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * Installs DB schema for a module Faonni_Bitcoin
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
        $connection = $installer->getConnection();

        /**
         * Create table 'faonni_bitcoin_explorer'
         */
        if (!$installer->tableExists('faonni_bitcoin_explorer')) {
			$table = $connection
				->newTable($installer->getTable('faonni_bitcoin_explorer'))
				->addColumn(
					'order_id',
					Table::TYPE_INTEGER,
					null,
					['unsigned' => true, 'nullable' => false, 'primary' => true],
					'Order Id'
				)				
				->addColumn(
					'amount',
					Table::TYPE_DECIMAL,
					'24,12',
					['nullable' => false],
					'Bitcoin Amount'
				)									
				->addColumn(
					'rate',
					Table::TYPE_DECIMAL,
					'24,12',
					['nullable' => false],
					'Currency Conversion Rate'
				)
				->addColumn(
					'account',
					Table::TYPE_TEXT,
					255,
					['nullable' => false],
					'Account'
				)				
				->addColumn(
					'address',
					Table::TYPE_TEXT,
					255,
					['nullable' => false],
					'Address'
				)				
				->addColumn(
					'created_at',
					Table::TYPE_TIMESTAMP,
					null,
					['nullable' => false, 'default' => Table::TIMESTAMP_INIT],
					'Created At'
				)
				->addColumn(
					'updated_at',
					Table::TYPE_TIMESTAMP,
					null,
					['nullable' => false, 'default' => Table::TIMESTAMP_INIT_UPDATE],
					'Updated At'
				)
				->addColumn(
					'status',
					Table::TYPE_SMALLINT,
					null,
					['unsigned' => true, 'nullable' => false, 'default' => '0'],
					'Status'
				)					
				->addIndex(
					$installer->getIdxName('faonni_bitcoin_explorer', ['order_id']),
					['order_id']
				)
				->addIndex(
					$installer->getIdxName('faonni_bitcoin_explorer', ['address']),
					['address']
				)													
				->addIndex(
					$installer->getIdxName('faonni_bitcoin_explorer', ['created_at']),
					['created_at']
				)
				->addIndex(
					$installer->getIdxName('faonni_bitcoin_explorer', ['updated_at']),
					['updated_at']
				)
				->addIndex(
					$installer->getIdxName('faonni_bitcoin_explorer', ['status']),
					['status']
				)					
				->addForeignKey(
					$installer->getFkName('faonni_bitcoin_explorer', 'order_id', 'sales_order', 'entity_id'),
					'order_id',
					$installer->getTable('sales_order'),
					'entity_id',
					Table::ACTION_CASCADE
				)											
				->setComment('Bitcoin Explorer Table');

			$connection->createTable($table);		
		}
		
        /**
         * Create table 'faonni_bitcoin_rate'
         */
        if (!$installer->tableExists('faonni_bitcoin_rate')) {
			$table = $connection
				->newTable($installer->getTable('faonni_bitcoin_rate'))
				->addColumn(
					'currency_code',
					Table::TYPE_TEXT,
					3,
					['nullable' => false, 'primary' => true, 'default' => false],
					'Currency Code'
				)
				->addColumn(
					'rate',
					Table::TYPE_DECIMAL,
					'24,12',
					['nullable' => false],
					'Conversion Rate'
				)
				->addColumn(
					'created_at',
					Table::TYPE_TIMESTAMP,
					null,
					['nullable' => false, 'default' => Table::TIMESTAMP_INIT],
					'Created At'
				)												
				->addColumn(
					'updated_at',
					Table::TYPE_TIMESTAMP,
					null,
					['nullable' => false, 'default' => Table::TIMESTAMP_INIT_UPDATE],
					'Updated At'
				)										
				->setComment('Bitcoin Rate Table');

			$connection->createTable($table);		
		} 
				
        $installer->endSetup();
    }
}
