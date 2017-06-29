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
 * @copyright   Copyright (c) 2017 Karliuka Vitalii(karliuka.vitalii@gmail.com) 
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
namespace Faonni\Bitcoin\Model\Client;

class Rpc 
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
    protected $_username;
	
    /**
     * Password for JSON-RPC connections
     *
     * @var string
     */	
    protected $_password;
	
    /**
     * Validate params
     *
     * @return bool
     */
    public function validate()
    {
		return true;
    }
	
    /**
     * Retrieve server url
     *
     * @return string
     */
    public function getUrl()
    {
		if ($this->validate()) {
			return ($this->_ssl ? 'https' : 'http') .
				'://' . $this->_username . ':' . $this->_password .
				'@' . $this->_host . ':' . $this->_port;
		}
		return false;
    }
	
    /**
     * Returns the Zend Http Client
	 * 
     * @return \Zend_Http_Client
     */
    public function getClient() 
	{
		if (false !== ($url = $this->getUrl())) {
			return new \Zend_Http_Client($url, array(
				'adapter'     => 'Zend_Http_Client_Adapter_Curl',
				'curloptions' => array(CURLOPT_SSL_VERIFYPEER => false),
			));			
		}
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
			$client
				->setRawData(json_encode(array('method' => $method, 'params' => $params)))
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
     * Returns an object containing various state info
	 * 
     * @return stdClass
     */
    public function getInfo() 
	{
		return $this->call('getinfo', array());
    }
	
    /**
     * Returns a new coin address for receiving payments. If account is specified payments received 
	 * with the address will be credited to account 
	 * 
     * @return string
     */
    public function getNewAddress($account = null) 
	{
		return $this->call('getnewaddress', (null === $account) ? array() : array($account));
    }
	
    /**
     * Returns the amount received by address in transactions with at least confirmations. 
	 * It correctly handles the case where someone has sent to the address in multiple transactions. 
	 * Keep in mind that addresses are only ever used for receiving transactions. Works only for 
	 * addresses in the local wallet, external addresses will always show 0 
	 * 
     * @return stdClass
     */
    public function getReceivedByAddress($address) 
	{
		return $this->call('getreceivedbyaddress', array($address));
    }		
}
