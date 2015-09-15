<?php

/**
 * This software is intended for use with Oxwall Free Community Software http://www.oxwall.org/ and is
 * licensed under The BSD license.

 * ---
 * Copyright (c) 2011, Oxwall Foundation
 * All rights reserved.

 * Redistribution and use in source and binary forms, with or without modification, are permitted provided that the
 * following conditions are met:
 *
 *  - Redistributions of source code must retain the above copyright notice, this list of conditions and
 *  the following disclaimer.
 *
 *  - Redistributions in binary form must reproduce the above copyright notice, this list of conditions and
 *  the following disclaimer in the documentation and/or other materials provided with the distribution.
 *
 *  - Neither the name of the Oxwall Foundation nor the names of its contributors may be used to endorse or promote products
 *  derived from this software without specific prior written permission.

 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES,
 * INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR
 * PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO,
 * PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED
 * AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 */

/**
 * Data Access Object for `base_banner_position` table.
 * 
 * @author Sardar Madumarov <madumarov@gmail.com>
 * @package ow_plugins.advertisement.bol
 * @since 1.0
 */
class ADS_BOL_BannerPositionDao extends OW_BaseDao
{
    const BANNER_ID = 'bannerId';
    const POSITION = 'position';
    const PLUGIN_KEY = 'pluginKey';

    const POSITION_VALUE_TOP = 'top';
    const POSITION_VALUE_SIDEBAR = 'sidebar';
    const POSITION_VALUE_BOTTOM = 'bottom';

    /**
     * Singleton instance.
     *
     * @var ADS_BOL_BannerLocationDao
     */
    private static $classInstance;

    /**
     * Returns an instance of class (singleton pattern implementation).
     *
     * @return ADS_BOL_BannerLocationDao
     */
    public static function getInstance()
    {
        if ( self::$classInstance === null )
        {
            self::$classInstance = new self();
        }

        return self::$classInstance;
    }

    /**
     * Constructor.
     *
     */
    protected function __construct()
    {
        parent::__construct();
    }

    /**
     * @see OW_BaseDao::getDtoClassName()
     *
     */
    public function getDtoClassName()
    {
        return 'ADS_BOL_BannerPosition';
    }

    /**
     * @see OW_BaseDao::getTableName()
     *
     */
    public function getTableName()
    {
        return OW_DB_PREFIX . 'ads_banner_position';
    }

    public function deleteByPositionAndPluginKey( $position, $pluginKey )
    {
        $example = new OW_Example();
        $example->andFieldEqual(self::POSITION, $position);
        $example->andFieldEqual(self::PLUGIN_KEY, $pluginKey);
        $this->deleteByExample($example);
    }

    public function findBannersCount( $position, $pluginKey )
    {
        $example = new OW_Example();
        $example->andFieldEqual(self::POSITION, $position);
        $example->andFieldEqual(self::PLUGIN_KEY, $pluginKey);

        return $this->countByExample($example);
    }

    public function findBannerList( $position, $pluginKey )
    {
        $example = new OW_Example();
        $example->andFieldEqual(self::POSITION, $position);
        $example->andFieldEqual(self::PLUGIN_KEY, $pluginKey);

        return $this->findListByExample($example);
    }

    public function deleteByBannerId( $bannerId )
    {
        $example = new OW_Example();
        $example->andFieldEqual(self::BANNER_ID, (int) $bannerId);
        $this->deleteByExample($example);
    }

    protected function clearCache()
    {
        OW::getCacheManager()->clean(array(ADS_BOL_BannerDao::CACHE_TAG_ADS_BANNERS));
    }
}