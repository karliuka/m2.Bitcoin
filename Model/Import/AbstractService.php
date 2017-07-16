<?php
/**
 * Copyright © 2011-2017 Karliuka Vitalii(karliuka.vitalii@gmail.com)
 * 
 * See COPYING.txt for license details.
 */
namespace Faonni\Bitcoin\Model\Import;

use Faonni\Bitcoin\Model\RateFactory;

/**
 * Import service abstract model
 */
abstract class AbstractService implements ServiceInterface
{
    /**
     * Error messages
     *
     * @var array
     */
    protected $_messages = [];
	
    /**
     * Rate model instance
     *
     * @var Faonni\Bitcoin\Model\RateFactory
     */
    protected $_rateFactory;
	
    /**
	 * Initialize model
	 *	
     * @param RateFactory $rateFactory
     */
    public function __construct(
        RateFactory $rateFactory
    ) {
        $this->_rateFactory = $rateFactory;
    }
	
    /**
     * Import rates
     *
     * @return Faonni\Bitcoin\Model\Rate\ServiceInterface
     */
    public function importRates()
    {
        return $this->_saveRates(
			$this->fetchRates()
		);
    }

    /**
     * Fetch rate
     *
     * @return array
     */
    abstract public function fetchRates();

    /**
     * Retrieve messages
     *
     * @return array
     */
    public function getMessages()
    {
		return $this->_messages;
	}
	
    /**
     * Saving rates
     *
     * @param array $rates
     * @return Faonni\Bitcoin\Model\Rate\ServiceInterface
     */
    protected function _saveRates($rates)
    {
        foreach ($rates as $currencyCode => $rate) {
            $this->_rateFactory->create()
				->setId($currencyCode)
				->setRate($rate)
				->save();
        }
        return $this;
    }
	
    /**
     * Retrieve formatted version of number
	 *
     * @param float $number
     * @return float
     */
    protected function _numberFormat($number)
    {
        return number_format($number, 10);
    }
	
    /**
     * set error rate message
     *
     * @param string $currencyCode
     * @return Faonni\Bitcoin\Model\Rate\ServiceInterface
     */
    protected function _setErrorRateMessage($currencyCode)
    {
		$this->_messages[] = __('We can\'t retrieve a rate from %1.', $currencyCode);
        return $this;
    }
	
    /**
     * set error response message
     *
     * @param string $url
     * @return Faonni\Bitcoin\Model\Rate\ServiceInterface
     */
    protected function _setErrorResponseMessage($url)
    {
		$this->_messages[] = __('We can\'t retrieve a correct response from %1.', $url);
        return $this;
    }
	
    /**
     * Retrieve the Zend Http Client
	 *
     * @param string $url	 
     * @return Zend_Http_Client
     */
    public function getClient($url) 
	{
		return new \Zend_Http_Client($url, array(
			'adapter'     => 'Zend_Http_Client_Adapter_Curl',
			'curloptions' => array(CURLOPT_SSL_VERIFYPEER => false),
		));
    }	
}
