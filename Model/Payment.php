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
 * @copyright   Copyright (c) 2016 Karliuka Vitalii(karliuka.vitalii@gmail.com) 
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
namespace Faonni\Bitcoin\Model;

use Magento\Payment\Model\Method\AbstractMethod;

/**
 * Bitcoin payment method model
 *
 * @method \Magento\Quote\Api\Data\PaymentMethodExtensionInterface getExtensionAttributes()
 */
class Payment extends AbstractMethod
{
    /**
     * Payment method code
     *
     * @var string
     */	
	const PAYMENT_METHOD_BITCOIN_CODE = 'faonni_bitcoin';
	
    /**
     * Payment method code
     *
     * @var string
     */	
	protected $_code = self::PAYMENT_METHOD_BITCOIN_CODE;
	
    /**
     * Bitcoin payment block paths
     *
     * @var string
     */
    protected $_formBlockType = 'Faonni\Bitcoin\Block\Form';

    /**
     * Info instructions block path
     *
     * @var string
     */
    protected $_infoBlockType = 'Faonni\Bitcoin\Block\Info';	
} 
