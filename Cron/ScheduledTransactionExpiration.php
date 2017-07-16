<?php
/**
 * Copyright © 2011-2017 Karliuka Vitalii(karliuka.vitalii@gmail.com)
 * 
 * See COPYING.txt for license details.
 */
namespace Faonni\Bitcoin\Cron;

use Magento\Sales\Api\OrderRepositoryInterface;
use Faonni\Bitcoin\Model\ExplorerFactory;
use Faonni\Bitcoin\Helper\Data as BitcoinHelper;

/**
 * Transaction expiration job instance
 */
class ScheduledTransactionExpiration
{
    /**
     * Explorer instance
     *
     * @var \Faonni\Bitcoin\Model\Explorer
     */
    protected $_explorer;
    
    /**
     * Bitcoin Helper instance
     *
     * @var \Faonni\Bitcoin\Helper\Data
     */
    protected $_helper;
    
    /**
     * Order Repository
     *
     * @var \Magento\Sales\Api\OrderRepositoryInterface
     */
	protected $_orderRepository;	
       
    /**
	 * Initialize job instance
	 *
     * @param ExplorerFactory $explorerFactory
     * @param BitcoinHelper $helper
     * @param OrderRepositoryInterface $orderRepository
     */
    public function __construct(
		ExplorerFactory $explorerFactory,
        BitcoinHelper $helper,
        OrderRepositoryInterface $orderRepository
    ) {
		$this->_explorer = $explorerFactory->create();
		$this->_helper = $helper;
        $this->_orderRepository = $orderRepository;	
    }
	
    /**
     * Search transaction expiration
     *
     * @return void
     */
    public function execute()
    {
		if (!$this->_helper->isEnabled()) {
			return;
		}
		
		$collection = $this->_explorer->getCollection();
		$collection
			->addFieldToFilter('status', 0)
			->addExpireDateFilter($this->_helper->getExpire());
		
		foreach ($collection as $explorer) {
			$this->_orderRepository->cancel($explorer->getOrderId());
			$explorer->setStatus(2)
				->save();
		}
    }
}
