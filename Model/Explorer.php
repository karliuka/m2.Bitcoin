<?php
/**
 * Copyright © 2011-2017 Karliuka Vitalii(karliuka.vitalii@gmail.com)
 * 
 * See COPYING.txt for license details.
 */
namespace Faonni\Bitcoin\Model;

use Magento\Framework\Model\AbstractModel;

/**
 * Bitcoin Model Explorer
 */
class Explorer extends AbstractModel
{
    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'faonni_bitcoin_explorer';
	
    /**
     * Parameter name in event
     * In observe method you can use $observer->getEvent()->getObject() 
     * in this case
     *
     * @var string
     */
    protected $_eventObject = 'explorer';
	
    /**
     * Model construct that should be used for object initialization
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();		
        $this->_init(
			'Faonni\Bitcoin\Model\ResourceModel\Explorer'
		);
    }
}
