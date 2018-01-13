<?php
/**
 * Copyright © 2011-2018 Karliuka Vitalii(karliuka.vitalii@gmail.com)
 * 
 * See COPYING.txt for license details.
 */
namespace Faonni\Bitcoin\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;
use Magento\Sales\Model\Order;

/**
 * Bitcoin Helper Data
 */
class Data extends AbstractHelper
{
    /**
     * Enabled config path
     */
    const XML_CONFIG_ENABLED = 'payment/faonni_bitcoin/active';
    	
    /**
     * Account prefix config path
     */
    const XML_CONFIG_PREFIX = 'payment/faonni_bitcoin/prefix';    
    	
    /**
     * Host config path
     */
    const XML_CONFIG_HOST = 'payment/faonni_bitcoin/host';

    /**
     * Port config path
     */
    const XML_CONFIG_PORT = 'payment/faonni_bitcoin/port';
 
    /**
     * Ssl config path
     */
    const XML_CONFIG_SSL = 'payment/faonni_bitcoin/ssl';

    /**
     * Username config path
     */
    const XML_CONFIG_USER = 'payment/faonni_bitcoin/user';

    /**
     * Password config path
     */
    const XML_CONFIG_PASS = 'payment/faonni_bitcoin/pass';
    
    /**
     * Confirmation config path
     */
    const XML_CONFIG_CONFIRM = 'payment/faonni_bitcoin/confirm';    
    
    /**
     * Expire days config path
     */
    const XML_CONFIG_EXPIRE = 'payment/faonni_bitcoin/expire';  
    
    /**
     * Rate import service config path
     */
    const XML_CONFIG_SERVICE = 'payment/faonni_bitcoin/service';  
    
    /**
     * Check bitcoin payment functionality should be enabled
     *
     * @return bool
     */
    public function isEnabled()
    {
        return $this->isModuleOutputEnabled() && 
			$this->_getConfig(self::XML_CONFIG_ENABLED);
    }
    	     
    /**
     * Retrieve confirmation
     *
     * @return string
     */
    public function getConfirmation()
    {
        return $this->_getConfig(self::XML_CONFIG_CONFIRM);
    }
             
    /**
     * Retrieve expire
     *
     * @return string
     */
    public function getExpire()
    {
        return $this->_getConfig(self::XML_CONFIG_EXPIRE);
    }
             
    /**
     * Retrieve service
     *
     * @return string
     */
    public function getService()
    {
        return $this->_getConfig(self::XML_CONFIG_SERVICE);
    }
    
    /**
     * Retrieve prefix
     *
     * @return string
     */
    public function getPrefix()
    {
        return $this->_getConfig(self::XML_CONFIG_PREFIX);
    }    
	
    /**
     * Round up and cast specified amount to float or string
     *
     * @param string|float $amount
     * @return string
     */
    public function formatAmount($amount)
    {
        return (string)number_format($amount, 10);
    } 
    
    /**
     * Retrieve account neme
     *
     * @param Order $order
     * @return string
     */
    public function getAccount(Order $order)
    {
		return $this->getPrefix() . $order->getIncrementId();
    }
    	
    /**
     * Retrieve configure smtp settings
     *
     * @return array
     */
    public function getConfig()
    {
		return [
			'host' => $this->_getConfig(self::XML_CONFIG_HOST),
			'port' => $this->_getConfig(self::XML_CONFIG_PORT),
			'ssl'  => $this->_getConfig(self::XML_CONFIG_SSL),
			'user' => $this->_getConfig(self::XML_CONFIG_USER),
			'pass' => $this->_getConfig(self::XML_CONFIG_PASS)
		];        
    }    
    
    /**
     * Retrieve store configuration data
     *
     * @param   string $path
     * @return  string|null
     */
    protected function _getConfig($path)
    {
        return $this->scopeConfig->getValue($path, ScopeInterface::SCOPE_STORE);
    }
}
