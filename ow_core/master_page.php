<?php

/**
 * EXHIBIT A. Common Public Attribution License Version 1.0
 * The contents of this file are subject to the Common Public Attribution License Version 1.0 (the “License”);
 * you may not use this file except in compliance with the License. You may obtain a copy of the License at
 * http://www.oxwall.org/license. The License is based on the Mozilla Public License Version 1.1
 * but Sections 14 and 15 have been added to cover use of software over a computer network and provide for
 * limited attribution for the Original Developer. In addition, Exhibit A has been modified to be consistent
 * with Exhibit B. Software distributed under the License is distributed on an “AS IS” basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License for the specific language
 * governing rights and limitations under the License. The Original Code is Oxwall software.
 * The Initial Developer of the Original Code is Oxwall Foundation (http://www.oxwall.org/foundation).
 * All portions of the code written by Oxwall Foundation are Copyright (c) 2011. All Rights Reserved.

 * EXHIBIT B. Attribution Information
 * Attribution Copyright Notice: Copyright 2011 Oxwall Foundation. All rights reserved.
 * Attribution Phrase (not exceeding 10 words): Powered by Oxwall community software
 * Attribution URL: http://www.oxwall.org/
 * Graphic Image as provided in the Covered Code.
 * Display of Attribution Information is required in Larger Works which are defined in the CPAL as a work
 * which combines Covered Code or portions thereof with code not governed by the terms of the CPAL.
 */

/**
 * Master page is a common markup "border" for controller's output.
 * It includes menus, sidebar, header, etc.
 *
 * @author Sardar Madumarov <madumarov@gmail.com>
 * @package ow_core
 * @since 1.0
 */
class OW_MasterPage extends OW_Renderable {
    /*
     * List of default master page templates.
     */
    //const TEMPLATE_HTML_DOCUMENT = 'html_document';

    const TEMPLATE_GENERAL = 'general';
    const TEMPLATE_BLANK = 'blank';
    const TEMPLATE_ADMIN = 'admin';
    const TEMPLATE_SIGN_IN = 'sign_in';
    const TEMPLATE_INDEX = 'index';

    /**
     * @var array
     */
    protected $menus;

    /**
     * Constructor.
     */
    public function __construct() {
        parent::__construct();
        $this->init();
    }

    /**
     * Adds menu components to master page object.
     *
     * @param string $name
     * @param BASE_CMP_Menu $menu Adds
     */
    public function addMenu($name, BASE_CMP_Menu $menu) {
        $this->menus[$name] = $menu;
    }

    /**
     * Returns master page menu components.
     *
     * @param string $name
     * @return BASE_CMP_Menu
     */
    public function getMenu($name) {
        if (isset($this->menus[$name])) {
            return $this->menus[$name];
        }

        return null;
    }

    /**
     * @param string $name
     */
    public function deleteMenu($name) {
        if (isset($this->menus[$name])) {
            unset($this->menus[$name]);
        }
    }

    /**
     * Master page can't handle forms.
     *
     * @see OW_Renderable::addForm()
     * @param Form $form
     * @throws LogicException
     */
    public function addForm(Form $form) {
        throw new LogicException('Cant add form to master page object!');
    }

    /**
     * Master page can't handle forms.
     *
     * @see OW_Renderable::getForm()
     * @param string $name
     * @throws LogicException
     */
    public function getForm($name) {
        throw new LogicException('Master page cant cantain forms!');
    }

    /**
     * Master page init actions. Template assigning, registering standard cmps, etc.
     * Default version works for `general` master page.
     */
    protected function init() {
//        echo OW::getPluginManager()->getPlugin('base')->getCmpViewDir();
//        exit;
        //add womans list
        $sex = "2";
        $data_arr = array("sex" => $sex);
        $userIdList = BOL_UserService::getInstance()->findUserIdListByQuestionValuesCustom($data_arr, 0, 500, false, "");

        $listId = 0;
        $listId = BOL_SearchService::getInstance()->saveSearchResult($userIdList);
        $serach_result = $this->searchResult(array('orderType' => array(OW_Route::PARAM_OPTION_DEFAULT_VALUE => 'latest_activity')), $listId);
        $this->assign('womanList', $serach_result);
        // add main menu
        $mainMenu = new BASE_CMP_MainMenu();
        $this->addMenu(BOL_NavigationService::MENU_TYPE_MAIN, $mainMenu);
        $this->addComponent('main_menu', $mainMenu);

        // add bottom menu
        $bottomMenu = new BASE_CMP_BottomMenu();
        $this->addMenu(BOL_NavigationService::MENU_TYPE_BOTTOM, $bottomMenu);
        $this->addComponent('bottom_menu', $bottomMenu);

        // assign image control values
        $currentTheme = OW::getThemeManager()->getCurrentTheme()->getDto();
        $values = json_decode(OW::getConfig()->getValue('base', 'master_page_theme_info'), true);

        if (isset($values[$currentTheme->getId()])) {
            $this->assign('imageControlValues', $values[$currentTheme->getId()]);
        }
    }

    protected function getOrderType($params) {

        $orderTypes = USEARCH_BOL_Service::getInstance()->getOrderTypes();
        $orderType = !empty($params['orderType']) ? $params['orderType'] : USEARCH_BOL_Service::LIST_ORDER_LATEST_ACTIVITY;
        if (empty($orderTypes)) {
            $orderType = USEARCH_BOL_Service::LIST_ORDER_LATEST_ACTIVITY;
        } else if (!in_array($orderType, $orderTypes)) {
            $orderType = reset($orderTypes);
        }
        return $orderType;
    }

    protected function searchResult($params, $listId) {
        $page = (!empty($_GET['page']) && intval($_GET['page']) > 0 ) ? $_GET['page'] : 1;
        $orderType = $this->getOrderType($params);
//bhushan changes
        if (!OW::getUser()->isAuthenticated()) {
            if (in_array($orderType, array(USEARCH_BOL_Service::LIST_ORDER_MATCH_COMPATIBILITY, USEARCH_BOL_Service::LIST_ORDER_DISTANCE))) {
                throw new Redirect404Exception();
            }
//    }
//end bhushan changes
            $limit = 16;
            $itemCount = BOL_SearchService::getInstance()->countSearchResultItem($listId);
            $list = USEARCH_BOL_Service::getInstance()->getSearchResultList($listId, $orderType, ($page - 1) * $limit, $limit);
            foreach ($list as $key => $list_id) {
                $ids[] = $list_id->id;
            }
            $user_ids = array();
            $user_data = array();
            if (!empty($ids)) {
                $user_ids = array_values($ids);
                $single_id = $user_ids[0];
            }
            $userinfoData = array();
            $userinfoData = BOL_QuestionService::getInstance()->getQuestionData($user_ids, array('id', 'username', 'realname', 'birthdate', 'googlemap_location'));
            $flag = "1";
            $avatar = BOL_AvatarService::getInstance()->getDataForUserAvatars($user_ids, true, true, true, true, $flag);

//$onlineStatus = $Userservice->findOnlineStatusForUserList($user_ids);
            foreach ($userinfoData as $key => $user) {
                $user_data[] = array(
                    "user_id" => $key,
                    "user_name" => $user["username"],
                    "realname" => $user["realname"],
                    "profile_picture" => $avatar[$key]["src"],);
            }
            return $user_data;
        }
    }

    public function onBeforeRender() {
        if (OW::getUser()->isAuthenticated()) {
            $this->setTemplate(OW::getThemeManager()->getMasterPageTemplate(self::TEMPLATE_GENERAL));
        }
        if ($this->getTemplate() === null) {
            $this->setTemplate(OW::getThemeManager()->getMasterPageTemplate(self::TEMPLATE_GENERAL));
        }
        //echo "template->" . $this->getTemplate();
        parent::onBeforeRender();
    }

}

