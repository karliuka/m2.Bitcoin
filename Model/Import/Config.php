<?php
/**
 * Copyright © 2011-2017 Karliuka Vitalii(karliuka.vitalii@gmail.com)
 * 
 * See COPYING.txt for license details.
 */
namespace Faonni\Bitcoin\Model\Import;

/**
 * Import service factory config
 */
class Config
{
    /**
     * @var array
     */
    private $_config;

    /**
     * Validate format of services configuration array
     *
     * @param array $config
     * @throws \InvalidArgumentException
     */
    public function __construct(array $config)
    {
        foreach ($config as $serviceName => $serviceInfo) {
            if (!is_string($serviceName) || empty($serviceName)) {
                throw new \InvalidArgumentException('Name for a import service has to be specified.');
            }
            if (empty($serviceInfo['class'])) {
                throw new \InvalidArgumentException('Class for a import service has to be specified.');
            }
            if (empty($serviceInfo['label'])) {
                throw new \InvalidArgumentException('Label for a import service has to be specified.');
            }
        }
        $this->_config = $config;
    }

    /**
     * Retrieve unique names of all available import services
     *
     * @return array
     */
    public function getAvailableServices()
    {
        return array_keys($this->_config);
    }

    /**
     * Retrieve name of a class that corresponds to service name
     *
     * @param string $serviceName
     * @return string|null
     */
    public function getServiceClass($serviceName)
    {
        if (isset($this->_config[$serviceName]['class'])) {
            return $this->_config[$serviceName]['class'];
        }
        return null;
    }

    /**
     * Retrieve already translated label that corresponds to service name
     *
     * @param string $serviceName
     * @return \Magento\Framework\Phrase|null
     */
    public function getServiceLabel($serviceName)
    {
        if (isset($this->_config[$serviceName]['label'])) {
            return __($this->_config[$serviceName]['label']);
        }
        return null;
    }
}
