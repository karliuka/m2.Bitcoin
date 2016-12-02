<?php
/**
 * Faonni
 *  
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade module to newer
 * versions in the future.
 * 
 * @package     Faonni_Bitcoin
 * @copyright   Copyright (c) 2016 Karliuka Vitalii(karliuka.vitalii@gmail.com) 
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
namespace Faonni\Bitcoin\Helper;

use \Magento\Framework\App\Helper\Context;
use \Magento\Store\Model\StoreManagerInterface;
use \Magento\Framework\App\Helper\AbstractHelper;

/**
 * Bitcoin Helper Data
 */
class Data extends AbstractHelper
{
    /**
     * Store Manager Instance
     *
     * @var Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;
	
    /**
     * Constructor	
	 *
     * @param Context $context
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        Context $context,
		StoreManagerInterface $storeManager
    ) {
        $this->storeManager = $storeManager;
		parent::__construct($context);
    }
	
    /**
     * Return Store fixture
     *
     * @return Store
     */	
    public function getStore()
	{
		return $this->storeManager->getStore();
    }
    
    /**
     * Retrieve store base currency code
     *
     * @return string
     */
    public function getBaseCurrencyCode()
    {
        return $this->getStore()->getBaseCurrencyCode();
    }  
      
    /**
     * Retrieve current store currency code
     *
     * @return string
     */
    public function getCurrentCurrencyCode()
    {
        return $this->getStore()->getCurrentCurrencyCode();
    }
        
    /**
     * Retrieve BTC rate for the base currency
     *
     * @return float
     */
    public function getExchangeRate()
    {
		$blockchain = new \Blockchain\Blockchain();
		$rates = new \Blockchain\Rates\Rates($blockchain);
		return $rates->toBTC(1, $this->getCurrentCurrencyCode());
	}	
}
