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
 * User search page controller.
 *
 * @author Egor Bulgakov <egor.bulgakov@gmail.com>
 * @package ow.ow_plugins.usearch.controllers
 * @since 1.5.3
 */
class USEARCH_CTRL_Search extends OW_ActionController
{
    private function getMenu()
    {
        $lang = OW::getLanguage();
        $router = OW::getRouter();

        $items = array();
        $item = new BASE_MenuItem();
        $item->setLabel($lang->text('usearch', 'user_list'));
        $item->setOrder(0);
        $item->setKey('photo_gallery');
        $item->setIconClass('ow_ic_picture');
        $item->setUrl($router->urlForRoute('users-search-result'));
        array_push($items, $item);

//        $item = new BASE_MenuItem();
//        $item->setLabel($lang->text('usearch', 'profile_details'));
//        $item->setOrder(1);
//        $item->setKey('profile_details');
//        $item->setIconClass('ow_ic_comment');
//        $item->setUrl($router->urlForRoute('usearch.details'));
//        array_push($items, $item);

        if ( OW::getPluginManager()->isPluginActive('googlelocation') )
        {
            $item = new BASE_MenuItem();
            $item->setLabel($lang->text('usearch', 'map'));
            $item->setOrder(2);
            $item->setKey('map');
            $item->setIconClass('ow_ic_places');
            $item->setUrl($router->urlForRoute('usearch.map'));
            array_push($items, $item);
        }

        return new BASE_CMP_ContentMenu($items);
    }

    public function form()
    {
        $mainSearchForm = OW::getClassInstance('USEARCH_CLASS_MainSearchForm', $this);
        $mainSearchForm->process($_POST);        
        $this->addForm($mainSearchForm);
    }

    protected function getOrderType( $params )
    {
        $orderTypes = USEARCH_BOL_Service::getInstance()->getOrderTypes();
        
        $orderType = !empty($params['orderType']) ? $params['orderType'] : USEARCH_BOL_Service::LIST_ORDER_LATEST_ACTIVITY;
        
        if ( empty($orderTypes)  )
        {
            $orderType = USEARCH_BOL_Service::LIST_ORDER_LATEST_ACTIVITY;
            
        }
        else if( !in_array($orderType, $orderTypes) )
        {
            $orderType = reset($orderTypes);
        }
        
        return $orderType;
    }
    
    public function searchResultMenu( $order )
    {
        $lang = OW::getLanguage();
        $router = OW::getRouter();
        $config = OW::getConfig()->getValues('usearch');
        
        $items = array();
        
        if ( $config['order_latest_activity'] )
        {
            $item = array(
                'label' => $lang->text('usearch', 'latest'),
                'url' => $router->urlForRoute('users-search-result', array('orderType' => USEARCH_BOL_Service::LIST_ORDER_LATEST_ACTIVITY)),
                'isActive' => $order == USEARCH_BOL_Service::LIST_ORDER_LATEST_ACTIVITY);
            
            array_push($items, $item);
        }
        
        if ( $config['order_recently_joined'] )
        {
            $item = array(
                'label' => $lang->text('usearch', 'recently_joined'),
                'url' => $router->urlForRoute('users-search-result', array('orderType' => USEARCH_BOL_Service::LIST_ORDER_NEW)),
                'isActive' => $order == USEARCH_BOL_Service::LIST_ORDER_NEW);
            
            array_push($items, $item);
        }
        
        if ( OW::getPluginManager()->isPluginActive('matchmaking') && $config['order_match_compatibitity'] && OW::getUser()->isAuthenticated() )
        {
            $item = array(
                'label' => $lang->text('usearch', 'match_compatibitity'),
                'url' => $router->urlForRoute('users-search-result', array('orderType' => USEARCH_BOL_Service::LIST_ORDER_MATCH_COMPATIBILITY)),
                'isActive' => $order == USEARCH_BOL_Service::LIST_ORDER_MATCH_COMPATIBILITY);
            
            array_push($items, $item);
        }

        if ( OW::getPluginManager()->isPluginActive('googlelocation') && $config['order_distance'] && OW::getUser()->isAuthenticated() )
        {
            $item = array(
                'label' => $lang->text('usearch', 'distance'),
                'url' => $router->urlForRoute('users-search-result', array('orderType' => USEARCH_BOL_Service::LIST_ORDER_DISTANCE)),
                'isActive' => $order == USEARCH_BOL_Service::LIST_ORDER_DISTANCE);
            
            array_push($items, $item);
            
        }
        
        $event = new OW_Event('usearch.get_list_order_menu', array('order' => $order), $items);
        
        OW::getEventManager()->trigger($event);

        $items = $event->getData();
        
        if ( !empty($items) )
        {
            return new BASE_CMP_SortControl($items);
        }
        
        return null;
    }
    
    public function searchResult($params)
    {
        //OW::getSession()->set(BOL_SearchService::SEARCH_RESULT_ID_VARIABLE,999);
        
        $listId = OW::getSession()->get(BOL_SearchService::SEARCH_RESULT_ID_VARIABLE);

        $page = (!empty($_GET['page']) && intval($_GET['page']) > 0 ) ? $_GET['page'] : 1;
        $orderType = $this->getOrderType($params);
        
        if ( !OW::getUser()->isAuthenticated()  )
        {
            if ( in_array($orderType, array(USEARCH_BOL_Service::LIST_ORDER_MATCH_COMPATIBILITY, USEARCH_BOL_Service::LIST_ORDER_DISTANCE)) )
            {
                throw new Redirect404Exception();
            }
        }
        
        $limit = 16;
        
        $itemCount = BOL_SearchService::getInstance()->countSearchResultItem($listId);

        $list = USEARCH_BOL_Service::getInstance()->getSearchResultList($listId, $orderType, ($page -1) * $limit, $limit);

        $idList = array();
        
        foreach ( $list as $dto )
        {
            $idList[] = $dto->id;
        }
        
        $searchResultMenu = $this->searchResultMenu($orderType);
        
        if ( !empty($searchResultMenu) )
        {
            $this->addComponent('searchResultMenu', $searchResultMenu);
        }

        $cmp = OW::getClassInstance('USEARCH_CMP_SearchResultList', $list, $page, $orderType);
        $this->addComponent('cmp', $cmp);
        
        $script = '$(".back_to_search_button").click(function(){
            window.location = ' . json_encode(OW::getRouter()->urlForRoute('users-search')) . ';
        });  ';
        
        OW::getDocument()->addOnloadScript($script);
        
        $jsParams = array(
            'excludeList' => $idList,
            'respunderUrl' => OW::getRouter()->urlForRoute('usearch.load_list_action'),
            'orderType' => $orderType,
            'page' => $page,
            'listId' => $listId,
            'count' => $limit
        );
        
        $script = ' USEARCH_ResultList.init('.  json_encode($jsParams).', $(".ow_search_results_photo_gallery_container")); ';
                
        OW::getDocument()->addOnloadScript($script);
        OW::getDocument()->addScript(OW::getPluginManager()->getPlugin('usearch')->getStaticJsUrl().'result_list.js');

        $this->addComponent('menu', $this->getMenu());
        $this->assign('itemCount', $itemCount);
        $this->assign('page', $page);
        $this->assign('searchUrl', OW::getRouter()->urlForRoute('users-search'));

        OW::getDocument()->setHeading(OW::getLanguage()->text('usearch', 'search_result'));
        OW::getDocument()->setDescription(OW::getLanguage()->text('base', 'users_list_user_search_meta_description'));
    }
    
    public function map()
    {
        $listId = OW::getSession()->get(BOL_SearchService::SEARCH_RESULT_ID_VARIABLE);
        $list = BOL_UserService::getInstance()->findSearchResultList($listId, 0, BOL_SearchService::USER_LIST_SIZE);

        $userIdList = array();
        if ( $list )
        {
            foreach ( $list as $dto )
            {
                $userIdList[] = $dto->getId();
            }

            $event = new OW_Event('googlelocation.get_map_component', array('userIdList' => $userIdList));
            OW::getEventManager()->trigger($event);
            $cmp = $event->getData();
            if ( $cmp )
            {
                $this->assign('mapCmp', $cmp);
            }
        }
        else
        {
            $this->assign('mapCmp', null);
            $this->assign('searchUrl', OW::getRouter()->urlForRoute('users-search'));
        }

        $this->addComponent('menu', $this->getMenu());

        OW::getDocument()->setHeading(OW::getLanguage()->text('usearch', 'search_result'));
        OW::getDocument()->setDescription(OW::getLanguage()->text('base', 'users_list_user_search_meta_description'));
    }
}