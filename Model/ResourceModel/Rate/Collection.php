<?php
/**
 * Copyright © 2011-2018 Karliuka Vitalii(karliuka.vitalii@gmail.com)
 * 
 * See COPYING.txt for license details.
 */
namespace Faonni\Bitcoin\Model\ResourceModel\Rate;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Bitcoin Rate ResourceModel Collection
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
			'Faonni\Bitcoin\Model\Rate', 
			'Faonni\Bitcoin\Model\ResourceModel\Rate'
		);	
    }             
}
