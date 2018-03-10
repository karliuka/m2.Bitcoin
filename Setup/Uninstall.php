<?php
/**
 * Copyright © 2011-2018 Karliuka Vitalii(karliuka.vitalii@gmail.com)
 * 
 * See COPYING.txt for license details.
 */
namespace Faonni\Bitcoin\Setup;

use Magento\Framework\Setup\UninstallInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * Bitcoin Uninstall
 */
class Uninstall implements UninstallInterface
{
    /**
     * Uninstall DB Schema for a Module Bitcoin
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     */
    public function uninstall(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
        $connection = $installer->getConnection();
		
        /**
         * Remove table 'faonni_bitcoin_explorer'
         */		
        $tableName = 'faonni_bitcoin_explorer';
        if ($installer->tableExists($tableName)) {			
            $connection->dropTable($installer->getTable($tableName));
		}
		
        /**
         * Remove table 'faonni_bitcoin_rate'
         */		
        $tableName = 'faonni_bitcoin_rate';
        if ($installer->tableExists($tableName)) {			
            $connection->dropTable($installer->getTable($tableName));
		}	
		
        $installer->endSetup();
    }
}