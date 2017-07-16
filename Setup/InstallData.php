<?php
/**
 * Copyright © 2011-2017 Karliuka Vitalii(karliuka.vitalii@gmail.com)
 * 
 * See COPYING.txt for license details.
 */
namespace Faonni\Bitcoin\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Sales\Setup\SalesSetupFactory;
use Faonni\Bitcoin\Model\Bitcoin;

/**
 * Class InstallData
 */
class InstallData implements InstallDataInterface
{
    /**
     * Sales setup factory
     *
     * @var SalesSetupFactory
     */
    private $salesSetupFactory;

    /**
     * @param SalesSetupFactory $salesSetupFactory
     */
    public function __construct(
        SalesSetupFactory $salesSetupFactory
    ) {
        $this->salesSetupFactory = $salesSetupFactory;
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        /** @var \Magento\Sales\Setup\SalesSetup $salesSetup */
        $salesSetup = $this->salesSetupFactory
			->create(['setup' => $setup]);

        /**
         * Install eav entity types to the eav/entity_type table
         */
        $salesSetup->installEntities();

        /**
         * Install order statuses from config
         */
        $data = [];
        $statuses = [
            Bitcoin::STATUS_PENDING_TRANSFER  => __('Pending Transfer Bitcoin'),
            Bitcoin::STATUS_PENDING_CONFIRMED => __('Pending Confirmed Transfer'),
        ];
        foreach ($statuses as $code => $info) {
            $data[] = [
				'status' => $code, 
				'label'  => $info
			];
        }
        $setup->getConnection()->insertArray(
			$setup->getTable('sales_order_status'), 
			['status', 'label'], 
			$data
		);

        /**
         * Install order states from config
         */
        $data = [];
        $states = [
            'pending_payment' => [
                'statuses' => [
					Bitcoin::STATUS_PENDING_TRANSFER  => [], 
					Bitcoin::STATUS_PENDING_CONFIRMED => []
				]
            ],
        ];

        foreach ($states as $code => $info) {
            if (isset($info['statuses'])) {
                foreach ($info['statuses'] as $status => $statusInfo) {
                    $data[] = [
                        'status' => $status,
                        'state' => $code,
                        'is_default' => 0,
						'visible_on_front' => 1				
                    ];
                }
            }
        }
		
        $setup->getConnection()->insertArray(
            $setup->getTable('sales_order_status_state'),
            ['status', 'state', 'is_default', 'visible_on_front'],
            $data
        );
    }
}
