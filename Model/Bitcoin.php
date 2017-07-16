<?php
/**
 * Copyright © 2011-2017 Karliuka Vitalii(karliuka.vitalii@gmail.com)
 * 
 * See COPYING.txt for license details.
 */
namespace Faonni\Bitcoin\Model;

use Magento\Framework\DataObject;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;
use Magento\Framework\Api\ExtensionAttributesFactory;
use Magento\Framework\Api\AttributeValueFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Payment\Model\Method\AbstractMethod;
use Magento\Payment\Model\Method\Logger;
use Magento\Payment\Model\InfoInterface;
use Magento\Payment\Helper\Data as PaymentHelper;
use Magento\Sales\Model\Order;
use Faonni\Bitcoin\Model\RateFactory;
use Faonni\Bitcoin\Helper\Data as BitcoinHelper;

/**
 * Bitcoin payment method model
 */
class Bitcoin extends AbstractMethod
{
    /**
     * Pending transfer status
     */
    const STATUS_PENDING_TRANSFER = 'bitcoin_pending_transfer';	
	
    /**
     * Pending confirmed status
     */
    const STATUS_PENDING_CONFIRMED = 'bitcoin_pending_confirmed';		
	
    /**
     * Payment method code
     */	
	const PAYMENT_METHOD_CODE = 'faonni_bitcoin';
	
    /**
     * Payment method code
     */	
	protected $_code = self::PAYMENT_METHOD_CODE;
    
    /**
     * Need to run payment initialize while order place?
     *
     * @var bool
     */
    protected $_isInitializeNeeded = true;
    
    /**
     * Payment Method feature
     *
     * @var bool
     */
    protected $_canCapture = true;    
     
    /**
     * Can admins use this payment method?
     *
     * @var bool
     */
    protected $_canUseInternal = false;        

    /**
     * Info instructions block path
     *
     * @var string
     */
    protected $_infoBlockType = 'Faonni\Bitcoin\Block\Info';

    /**
     * Rate instance
     *
     * @var \Faonni\Bitcoin\Model\Rate
     */
    protected $_rate;       
	
    /**
	 * Initialize method
	 *
     * @param Context $context
     * @param Registry $registry
     * @param ExtensionAttributesFactory $extensionFactory
     * @param AttributeValueFactory $customAttributeFactory
     * @param PaymentHelper $paymentData
     * @param ScopeConfigInterface $scopeConfig
     * @param Logger $logger
     * @param BitcoinHelper $helper
     * @param RateFactory $rateFactory 
     * @param AbstractResource $resource
     * @param AbstractDb $resourceCollection	 
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        ExtensionAttributesFactory $extensionFactory,
        AttributeValueFactory $customAttributeFactory,
        PaymentHelper $paymentData,
        ScopeConfigInterface $scopeConfig,
        Logger $logger,
		BitcoinHelper $helper,
		RateFactory $rateFactory,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->_rate = $rateFactory->create();
		
		parent::__construct(
            $context,
            $registry,
            $extensionFactory,
            $customAttributeFactory,
            $paymentData,
            $scopeConfig,
            $logger,
            $resource,
            $resourceCollection,
            $data
        );
    }
    
    /**
     * Method that will be executed instead of authorize or capture if 
     * flag isInitializeNeeded set to true
     *
     * @param string $paymentAction
     * @param object $stateObject
     * @return $this
     */
    public function initialize($paymentAction, $stateObject)
    {
		$payment = $this->getInfoInstance();
		$order = $payment->getOrder();		
		
		$order->setCanSendNewEmailFlag(false);
		$payment->setBaseAmountAuthorized($order->getBaseTotalDue());
		$payment->setAmountAuthorized($order->getTotalDue());
		
		$stateObject->setState(Order::STATE_PENDING_PAYMENT);
		$stateObject->setStatus(self::STATUS_PENDING_TRANSFER);
		$stateObject->setIsNotified(false);	

        return $this;
    }
    
    /**
     * Capture payment abstract method
     *
     * @param \Magento\Framework\DataObject|InfoInterface $payment
     * @param float $amount
     * @return $this
     */
    public function capture(InfoInterface $payment, $amount)
    {
		$payment->setParentTransactionId(null)
			->setTransactionId($payment->getTxid())
			->setIsTransactionClosed(true);

        return $this;
    }

    /**
     * Check method for processing with base currency
     *
     * @param string $currencyCode
     * @return bool
     */
    public function canUseForCurrency($currencyCode)
    {
		$rate = $this->_rate->load($currencyCode);                  
        return (bool)(0 < $rate->getRate());
    } 
} 
