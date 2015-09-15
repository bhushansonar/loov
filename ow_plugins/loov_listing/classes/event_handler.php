<?php

/**
 * Copyright (c) 2012, Oxwall CandyStore
 * All rights reserved.

 * This software is intended for use with Oxwall Free Community Software http://www.oxwall.org/ and is
 * licensed under The BSD license.
 */

/**
 * Guests event handler
 *
 * @author Oxwall CandyStore <plugins@oxcandystore.com>
 * @package ow_plugins.ocs_guests.classes
 * @since 1.6.0
 */
class LOOVLISTING_CLASS_EventHandler {

    /**
     * Class instance
     *
     * @var OCSGUESTS_CLASS_EventHandler
     */
    private static $classInstance;

    /**
     * Class constructor
     *
     */
    private function __construct() {

    }

    /**
     * Returns class instance
     *
     * @return OCSGUESTS_CLASS_EventHandler
     */
    public static function getInstance() {
        if (null === self::$classInstance) {
            self::$classInstance = new self();
        }

        return self::$classInstance;
    }

    public function init() {
//        echo "comming";
//        exit;
//        $em = OW::getEventManager();
//
//        $em->bind('base.widget_panel.content.top', array($this, 'onProfilePageRender'));
    }

}
