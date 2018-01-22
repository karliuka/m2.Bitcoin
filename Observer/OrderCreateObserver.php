<?php
/**
 * Copyright © 2011-2018 Karliuka Vitalii(karliuka.vitalii@gmail.com)
 * 
 * See COPYING.txt for license details.
 */
namespace Faonni\Bitcoin\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Model\OrderNotifierFactory;
use Magento\Framework\Exception\LocalizedException;
use Faonni\Bitcoin\Model\RateFactory;
use Faonni\Bitcoin\Model\ExplorerFactory;
use Faonni\Bitcoin\Model\Bitcoin;
use Faonni\Bitcoin\Model\Transport;
use Faonni\Bitcoin\Helper\Data as BitcoinHelper;

/**
 * Order create observer
 */
class OrderCreateObserver implements ObserverInterface
{
    /**
     * Rate instance
     *
     * @var \Faonni\Bitcoin\Model\Rate
     */
    protected $_rate;
    
    /**
     * Explorer instance
     *
     * @var \Faonni\Bitcoin\Model\Explorer
     */
    protected $_explorer;
    
    /**
     * OrderNotifier instance
     *
     * @var \Magento\Sales\Model\OrderNotifier
     */
    protected $_notifier;     
    
    /**
     * Bitcoin Helper instance
     *
     * @var \Faonni\Bitcoin\Helper\Data
     */
    protected $_helper;
    	
    /**
     * Transport instance
     *
     * @var \Faonni\Bitcoin\Model\Transport
     */
    protected $_transport;           
       
    /**
	 * Initialize observer
	 *
     * @param RateFactory $rateFactory 
     * @param ExplorerFactory $explorerFactory
     * @param OrderNotifierFactory $notifierFactory 
     * @param BitcoinHelper $helper
     * @param Transport $transport
     */
    public function __construct(
		RateFactory $rateFactory,
		ExplorerFactory $explorerFactory,
		OrderNotifierFactory $notifierFactory,
        BitcoinHelper $helper,
        Transport $transport      
    ) {
        $this->_rate = $rateFactory->create();
		$this->_explorer = $explorerFactory->create();
		$this->_notifier = $notifierFactory->create();
		$this->_helper = $helper;
        $this->_transport = $transport;
        $this->_transport->setConfig($helper->getConfig());       		
    }

    /**
     * Handler for order create event
     *
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
		$order = $observer->getEvent()->getOrder();		
		if (!$this->_helper->isEnabled() || !$order->getId()) {
			return $this;
		}

		$payment = $order->getPayment();
		if ($payment->getMethod() == Bitcoin::PAYMENT_METHOD_CODE) {
			$rate = $this->_rate->load($order->getBaseCurrencyCode());
			if (0 < $rate->getRate()) {
				$account = $this->_helper->getAccount($order);
				$address = $this->_transport->getNewAddress($account);
				$amount = $this->_helper->formatAmount($order->getBaseTotalDue() * $rate->getRate());

				$this->_explorer
					->setId($order->getId())
					->setAmount($amount)
					->setRate($rate->getRate())
					->setAccount($account)
					->setAddress($address)
					->save();

				$payment
					->setAdditionalInformation('address', $address)
					->setAdditionalInformation('amount', $amount)
					->save();

				$this->_notifier->notify($order);		
			} else {
				throw new LocalizedException(__('Unable to save order'));
			}		
		}	
        return $this;
    }
}  
