<?php
/**
 * Copyright © 2011-2018 Karliuka Vitalii(karliuka.vitalii@gmail.com)
 * 
 * See COPYING.txt for license details.
 */
namespace Faonni\Bitcoin\Model\Import;

/**
 * Bitcoincharts import service model
 */
class Bitcoincharts extends AbstractService
{
    /**
     * Blockchain api url
     * 
     * @var string
     */
    protected $_url = 'http://api.bitcoincharts.com/v1/weighted_prices.json';
    	
    /**
     * Fetch rate
     *
     * @return array
     */
    public function fetchRates()
	{
		$result = [];
		set_time_limit(0);
        try {
            $response = $this->_getServiceResponse();
			if($response){
				foreach ($response as $currencyCode => $rateInfo) {
					$method = '24h';
					$rate = (float)$rateInfo->{$method};
					if ($rate > 0) {
						$result[$currencyCode] = $this->_numberFormat(1/$rate);
					} else $this->_setErrorRateMessage($currencyCode);
				}
			} else $this->_setErrorResponseMessage($this->_url);
        } catch (\Exception $e) {
            $this->_messages[] = $e->getMessage();
        } finally {
            ini_restore('max_execution_time');
        }
		return $result;
	}
	
    /**
     * Get Blockchain.info service response
     *
     * @return array
     */
    private function _getServiceResponse()
    {
		$client = $this->getClient($this->_url);
		$response = $client->request(\Zend_Http_Client::GET);
		
		if($response->isSuccessful()){
			$json = json_decode($response->getBody());
			if($json){
				return $json;
			}		
		}		
        return false;
    }	
}
