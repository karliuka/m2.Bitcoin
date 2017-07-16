<?php
/**
 * Copyright © 2011-2017 Karliuka Vitalii(karliuka.vitalii@gmail.com)
 * 
 * See COPYING.txt for license details.
 */
namespace Faonni\Bitcoin\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;
use Faonni\Bitcoin\Model\Import\Config;

/**
 * Import service source
 */
class Service implements ArrayInterface
{
    /**
     * Service factory config
     *	
     * @var \Faonni\Bitcoin\Model\Import\Config
     */
    private $_config;

    /**
     * Options
     *	
     * @var array
     */
    private $_options;

    /**
	 * Initialize source
	 *	
     * @param Config $config
     */
    public function __construct(
        Config $config
    ) {
        $this->_config = $config;
    }

    /**
     * Retrieve service as options
     * 
     * @return array
    */
    public function toOptionArray()
    {
        if (null === $this->_options) {
            $this->_options = [];
            foreach ($this->_config->getAvailableServices() as $serviceName) {
                $this->_options[] = [
                    'label' => $this->_config->getServiceLabel($serviceName),
                    'value' => $serviceName,
                ];
            }
        }
        return $this->_options;
    }
}
