<?php
/**
 * Copyright © 2011-2017 Karliuka Vitalii(karliuka.vitalii@gmail.com)
 * 
 * See COPYING.txt for license details.
 */
namespace Faonni\Bitcoin\Cron;

use Faonni\Bitcoin\Model\Import\ServiceFactory;
use Faonni\Bitcoin\Helper\Data as BitcoinHelper;

/**
 * Update rates job instance
 */
class ScheduledRateUpdate
{
    /**
     * Service Factory instance
     *
     * @var \Faonni\Bitcoin\Model\Import\ServiceFactory
     */
    protected $_serviceFactory;
    
    /**
     * Bitcoin Helper instance
     *
     * @var \Faonni\Bitcoin\Helper\Data
     */
    protected $_helper;    
	
    /**
	 * Initialize job instance
	 *
     * @param ServiceFactory $serviceFactory
     * @param BitcoinHelper $helper
     */
    public function __construct(
		ServiceFactory $serviceFactory,
		BitcoinHelper $helper
    ) {
        $this->_serviceFactory = $serviceFactory;
        $this->_helper = $helper;
    }	
	
    /**
     * Update rates
     *
     * @return Faonni\Bitcoin\Cron\ScheduledRateUpdate
     */
    public function execute()
    {
		if ($this->_helper->isEnabled()) {
			$messages = $this->getService()
				->importRates()->getMessages();
			if (0 < count($messages)) {
				// log errors or send email
			}
		}
		return $this;
    }
	
    /**
     * Retrieve service instance
     *
     * @return Faonni\Bitcoin\Model\Import\ServiceInterface
     */
    public function getService()
    {
		return $this->_serviceFactory->create(
			$this->_helper->getService()
		);
    }	
}
