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
namespace Faonni\Bitcoin\Model\Rate;

use Faonni\Bitcoin\Model\Rate\ImportInterface;

class AbstractImport implements ImportInterface
{
    /**
     * Import rate
     *
     * @return float
     */
    public function importRate()
    {
	}

    /**
     * Fetch rate
     *
     * @return array
     */
    public function fetchRate()
    {
	}

    /**
     * Return messages
     *
     * @return array
     */
    public function getMessages()
    {
	}
}