<?php
/**
 * Copyright © 2011-2017 Karliuka Vitalii(karliuka.vitalii@gmail.com)
 * 
 * See COPYING.txt for license details.
 */
namespace Faonni\Bitcoin\Model\Import;

use Magento\Framework\ObjectManagerInterface;

/**
 * Import service factory
 */
class ServiceFactory
{
    /**
     * Object Manager instance
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager;

    /**
     * Service factory config
     *	
     * @var \Faonni\Bitcoin\Model\Import\Config
     */
    protected $_config;

    /**
	 * Initialize factory
	 *	
     * @param ObjectManagerInterface $objectManager
     * @param Config $config
     */
    public function __construct(
        ObjectManagerInterface $objectManager,
        Config $config
    ) {
        $this->_objectManager = $objectManager;
        $this->_config = $config;
    }

    /**
     * Create new import service object
     *
     * @param string $serviceName
     * @param array $data
     * @throws \InvalidArgumentException
     * @throws \UnexpectedValueException
     * @return ServiceInterface
     */
    public function create($serviceName, array $data = [])
    {
        $serviceClass = $this->_config->getServiceClass($serviceName);
        if (!$serviceClass) {
            throw new \InvalidArgumentException("Import service '{$serviceName}' is not defined.");
        }
		
        $serviceInstance = $this->_objectManager
			->create($serviceClass, $data);
			
        if (!$serviceInstance instanceof ServiceInterface) {
            throw new \UnexpectedValueException(
                "Class '{$serviceClass}' has to implement \\Faonni\\Bitcoin\\Model\\Import\\ServiceInterface."
            );
        }
        return $serviceInstance;
    }
}
