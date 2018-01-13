<?php
/**
 * Copyright © 2011-2018 Karliuka Vitalii(karliuka.vitalii@gmail.com)
 * 
 * See COPYING.txt for license details.
 */
namespace Faonni\Bitcoin\Cron;

use Magento\Sales\Model\Order;
use Magento\Sales\Api\OrderRepositoryInterface;
use Faonni\Bitcoin\Model\ExplorerFactory;
use Faonni\Bitcoin\Model\Bitcoin;
use Faonni\Bitcoin\Model\Transport;
use Faonni\Bitcoin\Helper\Data as BitcoinHelper;

/**
 * Transaction confirmation job instance
 */
class ScheduledTransactionConfirmation
{
    /**
     * Explorer instance
     *
     * @var \Faonni\Bitcoin\Model\Explorer
     */
    protected $_explorer;
    
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
     * Order Repository
     *
     * @var \Magento\Sales\Api\OrderRepositoryInterface
     */
    protected $_orderRepository;           
       
    /**
	 * Initialize job instance
	 *
     * @param ExplorerFactory $explorerFactory
     * @param BitcoinHelper $helper
     * @param Transport $transport
     * @param OrderRepositoryInterface $orderRepository
     */
    public function __construct(
		ExplorerFactory $explorerFactory,
        BitcoinHelper $helper,
        Transport $transport,
        OrderRepositoryInterface $orderRepository      
    ) {
		$this->_explorer = $explorerFactory->create();
		$this->_helper = $helper;
        $this->_transport = $transport;
        $this->_transport->setConfig($helper->getConfig());	
        $this->_orderRepository = $orderRepository;	      
    }
       	
    /**
     * Search transaction confirmation
     *
     * @return void
     */
    public function execute()
    {
		if (!$this->_helper->isEnabled()) {
			return;
		}
		
		$collection = $this->_explorer->getCollection();
		$collection->addFieldToFilter('status', 0);
		
		foreach ($collection as $explorer) {
			$order = $this->_orderRepository->get($explorer->getOrderId());
			if (!$order->getId()) {
				continue;
			}			
			
			$transaction = $this->getTransaction($explorer->getAccount(), $explorer->getAddress());			
			if ($transaction) {
				$this->updateOrder($order);
				if ($this->matchConfirmation($transaction->confirmations)) {
					$payment = $order->getPayment();
					
					$payment->setTxid($transaction->txid);
					$payment->setTransactionAdditionalInfo('account', $explorer->getAccount());
					$payment->setTransactionAdditionalInfo('address', $explorer->getAddress());
					$payment->setTransactionAdditionalInfo('amount', $transaction->amount);
					/* fraud detected */
					if (!$this->matchAmount($explorer->getAmount(), $transaction->amount)) {
						$payment->setIsFraudDetected(true);			
					}			
					$this->capture($order);										
					$explorer->setStatus(1);		
				}														
			}
			$explorer->save();		
		}
    }
    
    /**
     * Lookup an transaction using btc account and address
     * 
     * @param string $account
     * @param string $address
     * @return stdClass|false
     */
    protected function getTransaction($account, $address)
    {
		$transactions = $this->_transport->listTransactions($account);			
		if (is_array($transactions) && 0 < count($transactions)) {
			foreach ($transactions as $transaction) {
				if ($transaction->address == $address) {
					return $transaction;
				}
			}
		}
		return false;
    }

    /**
     * Capture the payment
     *
     * @param Order $order
     * @return void
     */
    protected function capture(Order $order)
    {
		$payment = $order->getPayment();
		$payment->capture();			
		$order->save();	       
    }
    
    /**
     * Compare confirmation
     *
     * @param int $amount
     * @return bool
     */
    protected function matchConfirmation($confirmation)
    {
        return $confirmation >= $this->_helper->getConfirmation();
    }

    /**
     * Compare amount with amount from transaction
     *
     * @param float $amount
     * @param float $transactionAmount
     * @return bool
     */
    protected function matchAmount($amount, $transactionAmount)
    {
        return $this->formatAmount($amount) == $this->formatAmount($transactionAmount);
    }
       
    /**
     * Add status to order history
     *
     * @param Order $order
     * @return void
     */
    protected function updateOrder(Order $order)
    {
		if ($order->getStatus() == Bitcoin::STATUS_PENDING_TRANSFER) {
			$order->setStatus(Bitcoin::STATUS_PENDING_CONFIRMED);
			$order->addStatusHistoryComment(__('Transaction is successful. Waiting of confirmations.'));
			$order->save();
		}
    }
       
    /**
     * Round up and cast specified amount to float or string
     *
     * @param string|float $amount
     * @return string
     */
    protected function formatAmount($amount)
    {
        return $this->_helper->formatAmount($amount);
    }    
}
