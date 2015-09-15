<?php

/**
 * Copyright (c) 2014, Skalfa LLC
 * All rights reserved.
 *
 * ATTENTION: This commercial software is intended for exclusive use with SkaDate Dating Software (http://www.skadate.com) and is licensed under SkaDate Exclusive License by Skalfa LLC.
 *
 * Full text of this license can be found at http://www.skadate.com/sel.pdf
 */
class LOOVLISTING_BOL_ListingDao extends OW_BaseDao {

    /**
     * Constructor.
     *
     */
    protected function __construct() {
        parent::__construct();
    }

    /**
     * Singleton instance.
     *
     * @var USEARCH_BOL_SearchDao
     */
    private static $classInstance;

    /**
     * Returns an instance of class (singleton pattern implementation).
     *
     * @return USEARCH_BOL_SearchDao
     */
    public static function getInstance() {
        if (self::$classInstance === null) {
            self::$classInstance = new self();
        }

        return self::$classInstance;
    }

    /**
     * @see OW_BaseDao::getDtoClassName()
     *
     */
    public function getDtoClassName() {
        return 'BOL_User';
    }

    /**
     * @see OW_BaseDao::getTableName()
     *
     */
    public function getTableName() {
        return null;
    }

    public function findCountWomenList() {
        $example = new OW_Example();
        $example->andFieldEqual('buy_rose_status', HAMMU_BOL_Service::STATUS_ACTIVE);
        return $this->countByExample($example);
    }

}

