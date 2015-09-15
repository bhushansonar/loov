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
 * @author Egor Bulgakov <egor.bulgakov@gmail.com>
 * @package ow_plugins.billing_ccbill.classes
 * @since 1.6.0
 */
class BILLINGCCBILL_CLASS_EventHandler
{
    /**
     * @var BILLINGCCBILL_CLASS_EventHandler
     */
    private static $classInstance;

    /**
     * @return BILLINGCCBILL_CLASS_EventHandler
     */
    public static function getInstance()
    {
        if ( self::$classInstance === null )
        {
            self::$classInstance = new self();
        }

        return self::$classInstance;
    }

    private function __construct() { }

    public function addAdminNotification( BASE_CLASS_EventCollector $coll )
    {
        $billingService = BOL_BillingService::getInstance();

        $key = BILLINGCCBILL_CLASS_CcbillAdapter::GATEWAY_KEY;
        $notify =
            !mb_strlen($billingService->getGatewayConfigValue($key, 'clientAccnum')) ||
                !mb_strlen($billingService->getGatewayConfigValue($key, 'clientSubacc')) ||
                ( !mb_strlen($billingService->getGatewayConfigValue($key, 'ccFormName')) && !mb_strlen($billingService->getGatewayConfigValue($key, 'ckFormName'))
                    && !mb_strlen($billingService->getGatewayConfigValue($key, 'dpFormName')) && !mb_strlen($billingService->getGatewayConfigValue($key, 'edFormName')) ) ||
                !mb_strlen($billingService->getGatewayConfigValue($key, 'dynamicPricingSalt')) ||
                !mb_strlen($billingService->getGatewayConfigValue($key, 'datalinkUsername')) ||
                !mb_strlen($billingService->getGatewayConfigValue($key, 'datalinkPassword'));

        if ( $notify )
        {
            $coll->add(
                OW::getLanguage()->text(
                    'billingccbill',
                    'plugin_configuration_notice',
                    array('url' => OW::getRouter()->urlForRoute('billing_ccbill_admin'))
                )
            );
        }
    }

    public function addAccessException( BASE_CLASS_EventCollector $e )
    {
        $e->add(array('controller' => 'BILLINGCCBILL_CTRL_Order', 'action' => 'postback'));
    }

    public function addUsercreditsConfig( OW_Event $e )
    {
        $params = $e->getParams();

        if ( $params['pluginKey'] != 'usercredits' || $params['entityKey'] != 'user_credits_pack' )
        {
            return;
        }

        $conf = BOL_BillingService::getInstance()->getGatewayConfigValue(
            BILLINGCCBILL_CLASS_CcbillAdapter::GATEWAY_KEY, 'clientSubaccCredits'
        );

        $e->setData($conf);
    }

    public function usercreditsPluginActivate( OW_Event $e )
    {
        $params = $e->getParams();
        $pluginKey = $params['pluginKey'];

        if ( $pluginKey != 'usercredits' )
        {
            return;
        }

        BOL_BillingService::getInstance()->addConfig('billingccbill', 'clientSubaccCredits', '0000');
    }

    public function usercreditsPluginDeactivate( OW_Event $e )
    {
        $params = $e->getParams();
        $pluginKey = $params['pluginKey'];

        if ( $pluginKey != 'usercredits' )
        {
            return;
        }

        BOL_BillingService::getInstance()->deleteConfig('billingccbill', 'clientSubaccCredits');
    }

    public function init()
    {
        $em = OW::getEventManager();

        $em->bind('admin.add_admin_notification', array($this, 'addAdminNotification'));
        $em->bind('base.members_only_exceptions', array($this, 'addAccessException'));
        $em->bind('base.password_protected_exceptions', array($this, 'addAccessException'));
        $em->bind('base.splash_screen_exceptions', array($this, 'addAccessException'));
        $em->bind('ccbill.get-subaccount-config', array($this, 'addUsercreditsConfig'));
        $em->bind(OW_EventManager::ON_AFTER_PLUGIN_ACTIVATE, array($this, 'usercreditsPluginActivate'));
        $em->bind(OW_EventManager::ON_BEFORE_PLUGIN_DEACTIVATE, array($this, 'usercreditsPluginDeactivate'));
    }
}