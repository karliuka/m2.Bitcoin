<?php
/**
 * Copyright © 2011-2017 Karliuka Vitalii(karliuka.vitalii@gmail.com)
 * 
 * See COPYING.txt for license details.
 */
namespace Faonni\Bitcoin\Model\Import;

/**
 * Import service interface
 */
interface ServiceInterface
{
    /**
     * Import rate
     *
     * @return float
     */
    public function importRates();

    /**
     * Fetch rate
     *
     * @return array
     */
    public function fetchRates();

    /**
     * Return messages
     *
     * @return array
     */
    public function getMessages();
}
