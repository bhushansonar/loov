<?php

/**
 * Copyright (c) 2014, Skalfa LLC
 * All rights reserved.
 *
 * ATTENTION: This commercial software is intended for exclusive use with SkaDate Dating Software (http://www.skadate.com) and is licensed under SkaDate Exclusive License by Skalfa LLC.
 *
 * Full text of this license can be found at http://www.skadate.com/sel.pdf
 */

/**
 * User search service class.
 *
 * @author Egor Bulgakov <egor.bulgakov@gmail.com>
 * @package ow.plugin.usearch.bol
 * @since 1.5.3
 */
final class LOOVLISTING_BOL_Service {

    const LIST_ORDER_LATEST_ACTIVITY = 'latest_activity';
    const LIST_ORDER_NEW = 'new';
    const LIST_ORDER_MATCH_COMPATIBILITY = 'match_compatibility';
    const LIST_ORDER_DISTANCE = 'distanse';
    const LIST_ORDER_WITHOUT_SORT = 'without_sort';

    /**
     * Class instance
     *
     * @var USEARCH_BOL_Service
     */
    private static $classInstance;
    private $listingDao;

    /**
     * Class constructor
     */
    private function __construct() {

        $this->listingDao = LOOVLISTING_BOL_ListingDao::getInstance();
    }

    /**
     * Returns class instance
     *
     * @return USEARCH_BOL_Service
     */
    public static function getInstance() {
        if (null === self::$classInstance) {
            self::$classInstance = new self();
        }

        return self::$classInstance;
    }

    public static function findCountWomenList() {
        return $this->listing_dao->findCountWomenList();
    }

}

