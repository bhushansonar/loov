<?php

/**
 * Copyright (c) 2014, Skalfa LLC
 * All rights reserved.
 *
 * ATTENTION: This commercial software is intended for exclusive use with SkaDate Dating Software (http://www.skadate.com) and is licensed under SkaDate Exclusive License by Skalfa LLC.
 *
 * Full text of this license can be found at http://www.skadate.com/sel.pdf
 */
//BOL_ComponentAdminService::getInstance()->deleteWidget('USEARCH_CMP_QuickSearchWidget');
//
//try {
//    OW::getNavigation()->deleteMenuItem('usearch', 'menu_item_search');
//}
//catch ( Exception $e ) { }
//
//try {
//    /* @var $menu BOL_MenuItem */
//    $menu = BOL_NavigationService::getInstance()->findMenuItem('base', 'users_main_menu_item');
//
//    if ( !empty($menu) )
//    {
//        $menu->type = BOL_NavigationService::MENU_TYPE_MAIN;
//        BOL_NavigationService::getInstance()->saveMenuItem($menu);
//    }
//}
//catch ( Exception $e ) { }


OW::getNavigation()->deleteMenuItem('loovlisting', 'menu_item_woman_list');
OW::getNavigation()->deleteMenuItem('loovlisting', 'menu_item_men_list');