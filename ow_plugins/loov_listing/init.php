<?php

/**
 * Copyright (c) 2014, Skalfa LLC
 * All rights reserved.
 *
 * ATTENTION: This commercial software is intended for exclusive use with SkaDate Dating Software (http://www.skadate.com) and is licensed under SkaDate Exclusive License by Skalfa LLC.
 *
 * Full text of this license can be found at http://www.skadate.com/sel.pdf
 */
OW::getRouter()->addRoute(new OW_Route('loovlisting.index', 'woman', 'LOOVLISTING_CTRL_Listing', 'index'));
OW::getRouter()->addRoute(new OW_Route('loovlisting.men', 'men', 'LOOVLISTING_CTRL_Listing', 'men'));
try {

    //OW::getNavigation()->addMenuItem(OW_Navigation::MAIN, 'loovlisting.index', 'loovlisting', 'menu_item_woman_list', OW_Navigation::VISIBLE_FOR_ALL);
    //OW::getNavigation()->addMenuItem(OW_Navigation::MAIN, 'loovlisting.men', 'loovlisting', 'menu_item_men_list', OW_Navigation::VISIBLE_FOR_ALL);
    //exit;
} catch (Exception $e) {

}
//LOOVLISTING_CLASS_EventHandler::getInstance()->init();
//
//function usearch_set_presentation( OW_Event $event )
//{
//    $params = $event->getParams();
//
//    if ( $params['type'] != 'search' || !in_array($params['fieldName'], array('sex', 'birthdate')) )
//    {
//        return;
//    }
//
//    $lang = OW::getLanguage();
//    $sessionData = OW::getSession()->get(USEARCH_CLASS_QuickSearchForm::FORM_SESSEION_VAR);
//
//    switch( $params['fieldName'] )
//    {
//        case 'sex':
//            $field = new Selectbox('sex');
//            $field->setLabel($lang->text('usearch', 'search_label_sex'));
//            $field->setHasInvitation(false);
//            if ( !empty($sessionData['sex']) )
//            {
//                $field->setValue($sessionData['sex']);
//            }
//            break;
//
//        case 'birthdate':
//            $field = new USEARCH_CLASS_AgeRangeField('birthdate');
//            $field->setLabel($lang->text('usearch', 'age'));
//            if ( !empty($sessionData['birthdate']['from']) && !empty($sessionData['birthdate']['to']) )
//            {
//                $field->setValue($sessionData['birthdate']);
//            }
//
//            $configs = !empty($params['configs']) ? BOL_QuestionService::getInstance()->getQuestionConfig($params['configs'], 'year_range') : null;
//            $max = !empty($configs['from']) ? date("Y") - (int) $configs['from'] : null;
//            $min = !empty($configs['to']) ? date("Y") - (int) $configs['to'] : null;
//
//            $field->setMaxAge($max);
//            $field->setMinAge($min);
//
//            $validator = new USEARCH_CLASS_AgeRangeValidator($min, $max);
//            $errorMsg = $lang->text('usearch', 'age_range_incorrect_values', array('min' => $min, 'max' => $max));
//            $validator->setErrorMessage($errorMsg);
//            $field->addValidator($validator);
//
//            break;
//    }
//
//    if ( !empty($field) )
//    {
//        $event->setData($field);
//    }
//}
//OW::getEventManager()->bind('base.questions_field_init', 'usearch_set_presentation');
//
//function usearch_set_question_sql( BASE_CLASS_QueryBuilderEvent $event )
//{
//    $params = $event->getParams();
//
//    if ( empty($params['question']) || !$params['question'] instanceof BOL_Question || empty($params['value'])
//        || !in_array($params['question']->name, array('sex', 'match_sex')) )
//    {
//        return;
//    }
//    $value = is_array($params['value']) ? array_sum($params['value']) : (int) $params['value'];
//
//    $prefix = !empty($params['prefix']) ? $params['prefix'] : 'q'.rand(100, 10000);
//    $questionName = $params['question']->name == 'sex' ? 'match_sex' : 'sex';
//
//    $innerJoin = " INNER JOIN `" . BOL_QuestionDataDao::getInstance()->getTableName() . "` `" . $prefix . "`
//        ON ( `user`.`id` = `" . $prefix . "`.`userId` AND `" . $prefix . "`.`questionName` = '" .
//        OW::getDbo()->escapeString($questionName) ."' AND `" . $prefix . "`.`intValue` & ".OW::getDbo()->escapeString($value)." ) ";
//
//    $event->addJoin($innerJoin);
//}
//OW::getEventManager()->bind('base.question.search_sql', 'usearch_set_question_sql');
//
//function usearch_disable_fields_on_edit_profile_question(OW_Event $event)
//{
//    $params = $event->getParams();
//    $data = $event->getData();
//
//    if ( !empty($params['questionDto']) && $params['questionDto'] instanceof BOL_Question )
//    {
//        $dto = $params['questionDto'];
//
//        if ( in_array( $dto->name, array('sex', 'match_sex', 'match_age') ) )
//        {
//            $data['disable_on_search'] = true;
//            $event->setData($data);
//        }
//    }
//}
//OW::getEventManager()->bind('admin.disable_fields_on_edit_profile_question', 'usearch_disable_fields_on_edit_profile_question');
//
//function usearch_after_plugins_init()
//{
//    $router = OW::getRouter()->getRoute('googlelocation_user_map');
//
//    if ( !empty($router) )
//    {
//        OW::getRouter()->removeRoute('googlelocation_user_map');
//    }
//}
//
//OW::getEventManager()->bind(OW_EventManager::ON_PLUGINS_INIT, 'usearch_after_plugins_init');
//
//function usearch_remove_user_list_routes()
//{
//    if( OW::getPluginManager()->isPluginActive('usearch') )
//    {
//        $routesToDelete = array('users', 'base_user_lists');
//        $router = OW::getRouter();
//
//        foreach ( $routesToDelete as $route )
//        {
//            $routeObj = $router->getRoute($route);
//            $routeObj->setDispatchAttrs(array(OW_RequestHandler::ATTRS_KEY_CTRL => 'USEARCH_CTRL_Search', OW_RequestHandler::ATTRS_KEY_ACTION => 'form'));
//            $router->removeRoute($route);
//            $router->addRoute($routeObj);
//        }
//    }
//}
//OW::getEventManager()->bind(OW_EventManager::ON_PLUGINS_INIT, 'usearch_remove_user_list_routes');