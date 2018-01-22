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
     * Enabled Config Path
     */
    const XML_CONFIG_ENABLED = 'payment/faonni_bitcoin/active';
    	
    /**
     * Account Prefix Config Path
     */
    const XML_CONFIG_PREFIX = 'payment/faonni_bitcoin/prefix';    
    	
    /**
     * Host Config Path
     */
    const XML_CONFIG_HOST = 'payment/faonni_bitcoin/host';

    /**
     * Port Config Path
     */
    const XML_CONFIG_PORT = 'payment/faonni_bitcoin/port';
 
    /**
     * Ssl Config Path
     */
    const XML_CONFIG_SSL = 'payment/faonni_bitcoin/ssl';

    /**
     * Username Config Path
     */
    const XML_CONFIG_USER = 'payment/faonni_bitcoin/user';

    /**
     * Password Config Path
     */
    const XML_CONFIG_PASS = 'payment/faonni_bitcoin/pass';
    
    /**
     * Confirmation Config Path
     */
    const XML_CONFIG_CONFIRM = 'payment/faonni_bitcoin/confirm';    
    
    /**
     * Expire Days Config Path
     */
    const XML_CONFIG_EXPIRE = 'payment/faonni_bitcoin/expire';  
    
    /**
     * Rate Import Service Config Path
     */
    const XML_CONFIG_SERVICE = 'payment/faonni_bitcoin/service';  
    
    /**
     * Check Bitcoin Payment Functionality Should Be Enabled
     *
     * @return bool
     */
    public function isEnabled()
    {
        return $this->isModuleOutputEnabled() && 
			$this->_getConfig(self::XML_CONFIG_ENABLED);
    }
    	     
    /**
     * Retrieve Confirmation
     *
     * @return string
     */
    public function getConfirmation()
    {
        return $this->_getConfig(self::XML_CONFIG_CONFIRM);
    }
             
    /**
     * Retrieve Expire
     *
     * @return string
     */
    public function getExpire()
    {
        return $this->_getConfig(self::XML_CONFIG_EXPIRE);
    }
             
    /**
     * Retrieve Service
     *
     * @return string
     */
    public function getService()
    {
        return $this->_getConfig(self::XML_CONFIG_SERVICE);
    }
    
    /**
     * Retrieve Prefix
     *
     * @return string
     */
    public function getPrefix()
    {
        return $this->_getConfig(self::XML_CONFIG_PREFIX);
    }    
	
    /**
     * Round Up And Cast Specified Amount To Float Or String
     *
     * @param string|float $amount
     * @return string
     */
    public function formatAmount($amount)
    {
        return (string)number_format($amount, 10);
    } 
    
    /**
     * Retrieve Account Neme
     *
     * @param Order $order
     * @return string
     */
    public function getAccount(Order $order)
    {
		return $this->getPrefix() . $order->getIncrementId();
    }
    	
    /**
     * Retrieve Configure Smtp Settings
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
     * Retrieve Store Configuration Data
     *
     * @param   string $path
     * @return  string|null
     */
    protected function _getConfig($path)
    {
        return $this->scopeConfig->getValue($path, ScopeInterface::SCOPE_STORE);
    }
}
