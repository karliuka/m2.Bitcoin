<?php
/**
 * Copyright © 2011-2018 Karliuka Vitalii(karliuka.vitalii@gmail.com)
 * 
 * See COPYING.txt for license details.
 */
namespace Faonni\Bitcoin\Model\ResourceModel\Explorer;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Bitcoin Explorer ResourceModel Collection
 */
class Collection extends AbstractCollection
{
    /**
     * Initialize collection
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
			'Faonni\Bitcoin\Model\Explorer', 
			'Faonni\Bitcoin\Model\ResourceModel\Explorer'
		);	
    } 
    
    /**
     * Limit collection by expire date
     *
     * @param string $interval
     * @return $this
     */
    public function addExpireDateFilter($interval)
    {
        $this->getSelect()->where(
            "created_at <= NOW() - INTERVAL ? DAY",
            (int)$interval
        );
        return $this;
    }                
}
