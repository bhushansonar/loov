<?php

/**
 * Copyright (c) 2009, Skalfa LLC
 * All rights reserved.

 * ATTENTION: This commercial software is intended for use with Oxwall Free Community Software http://www.oxwall.org/
 * and is licensed under Oxwall Store Commercial License.
 * Full text of this license can be found at http://www.oxwall.org/store/oscl
 */
BOL_BillingService::getInstance()->activateProduct('membership_plan');

OW::getNavigation()->addMenuItem(OW_Navigation::MAIN, 'membership_subscribe', 'membership', 'subscribe_page_heading', OW_Navigation::VISIBLE_FOR_MEMBER);

$widgetService = BOL_ComponentAdminService::getInstance();

$widget = $widgetService->addWidget('MEMBERSHIP_CMP_MyMembershipWidget', false);
$placeWidget = $widgetService->addWidgetToPlace($widget, BOL_ComponentService::PLACE_DASHBOARD);
$widgetService->addWidgetToPosition($placeWidget, BOL_ComponentService::SECTION_LEFT);
$widgetService->saveComponentSettingList($placeWidget->uniqName, array('freeze' => 1));

$widget = $widgetService->addWidget('MEMBERSHIP_CMP_UserMembershipWidget', false);
$placeWidget = $widgetService->addWidgetToPlace($widget, BOL_ComponentService::PLACE_PROFILE);
$widgetService->addWidgetToPosition($placeWidget, BOL_ComponentService::SECTION_LEFT);
$widgetService->saveComponentSettingList($placeWidget->uniqName, array('freeze' => 1));

$widget = $widgetService->addWidget('MEMBERSHIP_CMP_PromoWidget', false);
$placeWidget = $widgetService->addWidgetToPlace($widget, BOL_ComponentService::PLACE_INDEX);
$widgetService->addWidgetToPosition($placeWidget, BOL_ComponentService::SECTION_SIDEBAR);