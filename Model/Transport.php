<?php
/**
 * Copyright © 2011-2017 Karliuka Vitalii(karliuka.vitalii@gmail.com)
 * 
 * See COPYING.txt for license details.
 */
namespace Faonni\Bitcoin\Model;

use Magento\Framework\Encryption\EncryptorInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * Bitcoin Model Transport
 */
class Transport 
{
    /**
     * Host for JSON-RPC connections
     *
     * @var string
     */	
    protected $_host;
	
    /**
     * Port for JSON-RPC connections
     *
     * @var string
     */	
    protected $_port;		
	
    /**
     * Use Secure Sockets Layer (also known as TLS or HTTPS) to communicate with server
     *
     * @var bool
     */	
    protected $_ssl;
	
    /**
     * Username for JSON-RPC connections
     *
     * @var string
     */	
    protected $_user;
	
    /**
     * Password for JSON-RPC connections
     *
     * @var string
     */	
    public $_pass;
    
    /**
     * Encryptor Interface
     * 
     * @var \Magento\Framework\Encryption\EncryptorInterface
     */
    protected $_encryptor;
    
    /**
     * Scope Config
     * 
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;         
    
    /**
	 * Initialize transport instance
	 *
     * @param EncryptorInterface $encryptor
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        EncryptorInterface $encryptor,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->_encryptor = $encryptor;
        $this->_scopeConfig = $scopeConfig;
    }  
        
    /**
	 * Performs a jsonRCP request and gets the results
	 *
	 * @param string $method
	 * @param array $params
     * @return stdClass
     */
    public function call($method, array $params) 
	{
		if (false != ($client = $this->getClient())) {
			$client->setRawData(
					json_encode([
						'method' => $method, 
						'params' => $params
					])
				)
				->setHeaders('Content-type', 'application/json');
				
			$response = $client->request(\Zend_Http_Client::POST);		
			if($response->isSuccessful()){
				$data = json_decode($response->getBody());
				if (empty($data->error) && !empty($data->result)){
					return $data->result;
				}
			}		
		}
		return false;
    }
       
    /**
     * Config instance getter
     * 
	 * @param string $config
	 * @param bool $checkEncrypt	 
     * @return this
     */
    public function setConfig(array $config, $checkEncrypt=false)
    {
		foreach(['host', 'port', 'ssl', 'user', 'pass'] as $field) {
			if (isset($config[$field])) {
				$param = "_{$field}";
				$value = $config[$field];			
				if (preg_match('#^\*+$#', $value)) {
					// needed add store id
					$value = $this->_scopeConfig->getValue(
						'payment/faonni_bitcoin/' . $field, 
						ScopeInterface::SCOPE_STORE
					);
				} elseif (
					$checkEncrypt && 
					in_array($field, ['user', 'pass']) && 
					base64_encode(base64_decode($value)) === $value
				) {
					$value = $this->_encryptor->decrypt($value);
				}			
				$this->{$param} = $value;
			}
		}
	} 
	
    /**
     * Retrieve server url
     *
     * @return string
     */
    public function getUrl()
    {
		return ($this->_ssl ? 'https' : 'http') .
			'://' . $this->_user . ':' . $this->_pass .
			'@' . $this->_host . ':' . $this->_port;
    }
    
    /**
     * Returns the Zend Http Client
	 * 
     * @return \Zend_Http_Client
     */
    public function getClient() 
	{
		return new \Zend_Http_Client($this->getUrl(), [
			'adapter'     => 'Zend_Http_Client_Adapter_Curl',
			'curloptions' => [CURLOPT_SSL_VERIFYPEER => false],
		]);			
    }    
    	
    /**
     * Returns an object containing various state info
	 * 
     * @return stdClass
     */
    public function getInfo() 
	{
		return $this->call(
			'getinfo', 
			[]
		);
    }
	
    /**
     * Returns a new coin address for receiving payments. If account is specified payments received 
	 * with the address will be credited to account 
	 * 
     * @return string
     */
    public function getNewAddress($account=null) 
	{
		return $this->call(
			'getnewaddress',
			(null === $account) ? [] : [$account]
		);
    }
	
    /**
     * Returns the amount received by address in transactions with at least confirmations. It 
	 * correctly handles the case where someone has sent to the address in multiple transactions. 
	 * Keep in mind that addresses are only ever used for receiving transactions. Works only for 
	 * addresses in the local wallet, external addresses will always show 0 
	 * 
     * @return stdClass
     */
    public function getReceivedByAddress($address) 
	{
		return $this->call(
			'getreceivedbyaddress', 
			[$address]
		);
    }
    
    /**
     * Returns up to most recent transactions skipping the first transactions for account.If account
	 * not provided it'll return recent transactions from all accounts. 
	 * 
     * @return stdClass
     */
    public function listTransactions($account=null) 
	{
		return $this->call(
			'listtransactions', 
			[$account]
		);
    }    
}
