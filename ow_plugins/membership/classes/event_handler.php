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
 * @package ow_plugins.membership.classes
 * @since 1.6.0
 */
class MEMBERSHIP_CLASS_EventHandler
{
    /**
     * @var MEMBERSHIP_CLASS_EventHandler
     */
    private static $classInstance;

    /**
     * @return MEMBERSHIP_CLASS_EventHandler
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

    public function deleteUserMembership( OW_Event $event )
    {
        $params = $event->getParams();

        $userId = (int) $params['userId'];

        if ( $userId > 0 )
        {
            MEMBERSHIP_BOL_MembershipService::getInstance()->deleteUserTrialsByUserId($userId);
            MEMBERSHIP_BOL_MembershipService::getInstance()->deleleUserMembershipByUserId($userId);
        }
    }

    public function deleteRole( OW_Event $event )
    {
        $params = $event->getParams();

        $roleId = (int) $params['roleId'];

        if ( $roleId > 0 )
        {
            MEMBERSHIP_BOL_MembershipService::getInstance()->deleteUserMembershipsByRoleId($roleId);
            MEMBERSHIP_BOL_MembershipService::getInstance()->deleteMembershipTypeByRoleId($roleId);
        }
    }

    public function addAdminNotification( BASE_CLASS_EventCollector $coll )
    {
        $membershipService = MEMBERSHIP_BOL_MembershipService::getInstance();

        $types = $membershipService->getTypeListWithPlans();
        //printVar($types);
        $plans = 0;

        if ( $types )
        {
            foreach ( $types as $type )
            {
                if ( !empty($type['plans']) )
                {
                $plans += count($type['plans']);
            }
        }
        }

        if ( !$types || !$plans )
        {
            $coll->add(
                OW::getLanguage()->text(
                    'membership',
                    'plugin_configuration_notice',
                    array('url' => OW::getRouter()->urlForRoute('membership_admin'))
                )
            );
        }
    }

    public function adsEnabled( BASE_CLASS_EventCollector $event )
    {
        $event->add('membership');
    }

    public function addAuthLabels( BASE_CLASS_EventCollector $event )
    {
        $language = OW::getLanguage();
        $event->add(
            array(
                'membership' => array(
                    'label' => $language->text('membership', 'auth_group_label')
                )
            )
        );
    }

    public function billingAddGatewayProduct( BASE_CLASS_EventCollector $event )
    {
        $service = MEMBERSHIP_BOL_MembershipService::getInstance();
        $types = $service->getTypePlanList();

        if ( !$types )
        {
            return;
        }

        foreach ( $types as $type )
        {
            foreach ( $type as $plan )
            {
                $data[] = array(
                    'pluginKey' => 'membership',
                    'label' => $plan['plan_format'],
                    'entityType' => 'membership_plan',
                    'entityId' => $plan['dto']->id
                );
            }
        }

        if ( empty($data) )
        {
            return;
        }

        $event->add($data);
    }

    public function onCollectProfileActionToolbarItem( BASE_CLASS_EventCollector $event )
    {
        $params = $event->getParams();

        if ( empty($params['userId']) )
        {
            return;
        }

        if ( !OW::getUser()->isAuthorized('membership') )
        {
            return;
        }

        $userId = (int) $params['userId'];
        $linkId = uniqid('toolbar-action-set-membership');

        $service = MEMBERSHIP_BOL_MembershipService::getInstance();
        $types = $service->getTypeList();

        if ( !$types )
        {
            return;
        }

        $label = OW::getLanguage()->text('membership', 'edit_membership');
        $script =
        '$("#' . $linkId . '").click(function(){
            document.setMembershipFloatBox = OW.ajaxFloatBox(
                "MEMBERSHIP_CMP_SetMembership",
                { userId: ' . $userId . ' },
                { width: 500, title: ' . json_encode($label) . ' }
            );
        });';

        OW::getDocument()->addOnloadScript($script);

        $resultArray = array(
            BASE_CMP_ProfileActionToolbar::DATA_KEY_ITEM_KEY => "membership.edit_membership",
            BASE_CMP_ProfileActionToolbar::DATA_KEY_LABEL => $label,
            BASE_CMP_ProfileActionToolbar::DATA_KEY_LINK_HREF => 'javascript://',
            BASE_CMP_ProfileActionToolbar::DATA_KEY_LINK_ID => $linkId,
            BASE_CMP_ProfileActionToolbar::DATA_KEY_LINK_ORDER => 2,
            
            BASE_CMP_ProfileActionToolbar::DATA_KEY_LINK_GROUP_KEY => 'base.moderation',
            BASE_CMP_ProfileActionToolbar::DATA_KEY_LINK_GROUP_LABEL => OW::getLanguage()->text('base', 'profile_toolbar_group_moderation')
        );

        $event->add($resultArray);
    }

    public function onAuthLayerCheck( BASE_CLASS_EventCollector $event )
    {
        /*$params = $event->getParams();
        if ( empty($params['actionName']) )
        {
            return;
        }

        $groupName = $params['groupName'];
        $actionName = $params['actionName'];
        $userId = $params['userId'];

        $authService = BOL_AuthorizationService::getInstance();
        $action = $authService->findAction($groupName, $actionName);

        if ( !$action )
        {
            return;
        }

        $isAuthorized = $authService->isActionAuthorizedForUser($userId, $groupName, $actionName, $params['ownerId']);

        $data = array(
            'pluginKey' => 'membership',
            'priority' => 1,
            'permission' => $isAuthorized
        );

        $event->add($data);*/
    }

    public function onAuthLayerCheckCollectError( BASE_CLASS_EventCollector $event )
    {
        $params = $event->getParams();
        $actionName = $params['actionName'];
        $groupName = $params['groupName'];

        $service = MEMBERSHIP_BOL_MembershipService::getInstance();
        $authService = BOL_AuthorizationService::getInstance();
        $action = $authService->findAction($groupName, $actionName);

        if ( !$action )
        {
            return;
        }

        $canPurchase = $service->actionCanBePurchased($action->id);

        if ( !$canPurchase )
        {
            return;
        }

        $data = array(
            'pluginKey' => 'membership',
            'label' => OW::getLanguage()->text('membership', 'upgrade'),
            'url' => OW::getRouter()->urlForRoute('membership_subscribe'),
            'priority' => 1
        );

        $event->add($data);
    }

    public function init()
    {
        $this->genericInit();
        $em = OW::getEventManager();

        $em->bind(OW_EventManager::ON_USER_UNREGISTER, array($this, 'deleteUserMembership'));
        $em->bind(BOL_AuthorizationService::ON_BEFORE_ROLE_DELETE, array($this, 'deleteRole'));
        $em->bind('admin.add_admin_notification', array($this, 'addAdminNotification'));
        $em->bind('admin.add_auth_labels', array($this, 'addAuthLabels'));
        $em->bind('base.billing_add_gateway_product', array($this, 'billingAddGatewayProduct'));
        $em->bind(BASE_CMP_ProfileActionToolbar::EVENT_NAME, array($this, 'onCollectProfileActionToolbarItem'));
    }

    public function genericInit()
    {
        $em = OW::getEventManager();

        $em->bind('ads.enabled_plugins', array($this, 'adsEnabled'));
        $em->bind('authorization.layer_check', array($this, 'onAuthLayerCheck'));
        $em->bind('authorization.layer_check_collect_error', array($this, 'onAuthLayerCheckCollectError'));
    }
}