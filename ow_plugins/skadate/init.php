<?php

/**
 * Copyright (c) 2014, Skalfa LLC
 * All rights reserved.
 * 
 * ATTENTION: This commercial software is intended for exclusive use with SkaDate Dating Software (http://www.skadate.com) and is licensed under SkaDate Exclusive License by Skalfa LLC.
 * 
 * Full text of this license can be found at http://www.skadate.com/sel.pdf
 */

$router = OW::getRouter();
$router->addRoute(new OW_Route('skadate.settigns', 'skadate/settings', 'SKADATE_CTRL_Admin', 'settings'));
$router->addRoute(new OW_Route('skadate.uninstall', 'skadate/uninstall', 'SKADATE_CTRL_Admin', 'uninstall'));


$router->removeRoute('base_join');
$router->addRoute(new OW_Route('base_join', 'join', 'SKADATE_CTRL_Join', 'index'));

function skadate_remove_admin_bottom_link()
{
    if ( (bool) OW::getConfig()->getValue('skadate', 'brand_removal') )
    {
        OW_ViewRenderer::getInstance()->assignVar('bottomPoweredByLink', '');
    }
    else
    {
        OW_ViewRenderer::getInstance()->assignVar('bottomPoweredByLink', '<a href="http://www.skadate.com/" target="_blank" title="Powered by Skadate"><img alt="Powered by Skadate" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAE4AAAAbCAYAAADS6blZAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyRpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMy1jMDExIDY2LjE0NTY2MSwgMjAxMi8wMi8wNi0xNDo1NjoyNyAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNiAoTWFjaW50b3NoKSIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDpDREU3MTFBMEZDNzQxMUUzODRGQkMyMjAxOTY5RUYxMCIgeG1wTU06RG9jdW1lbnRJRD0ieG1wLmRpZDpDREU3MTFBMUZDNzQxMUUzODRGQkMyMjAxOTY5RUYxMCI+IDx4bXBNTTpEZXJpdmVkRnJvbSBzdFJlZjppbnN0YW5jZUlEPSJ4bXAuaWlkOkNERTcxMTlFRkM3NDExRTM4NEZCQzIyMDE5NjlFRjEwIiBzdFJlZjpkb2N1bWVudElEPSJ4bXAuZGlkOkNERTcxMTlGRkM3NDExRTM4NEZCQzIyMDE5NjlFRjEwIi8+IDwvcmRmOkRlc2NyaXB0aW9uPiA8L3JkZjpSREY+IDwveDp4bXBtZXRhPiA8P3hwYWNrZXQgZW5kPSJyIj8+J/RKhAAAColJREFUeNrsWQls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" /></a>');
    }
}
OW::getEventManager()->bind(OW_EventManager::ON_BEFORE_DOCUMENT_RENDER, 'skadate_remove_admin_bottom_link');

function skadate_get_question_label( OW_Event $event )
{
    $params = $event->getParams();
    $presentation = !empty($params['presentation']) ? $params['presentation'] : null;
    $fieldName = !empty($params['fieldName']) ? $params['fieldName'] : null;
    $configs = !empty($params['configs']) ? $params['configs'] : null;
    $type = !empty($params['type']) ? $params['type'] : null;

    if ( $type == 'view' && $fieldName == 'sex' )
    {
        $event->setData(OW::getLanguage()->text('skadate', 'questions_question_sex_label'));
    }
}
OW::getEventManager()->bind('base.questions_field_get_label', 'skadate_get_question_label');

function skadate_zip_dir( $source, $destination )
{
    if ( !extension_loaded('zip') || !file_exists($source) )
    {
        return false;
    }

    $zip = new ZipArchive();
    if ( !$zip->open($destination, ZIPARCHIVE::CREATE) )
    {
        return false;
    }

    $source = str_replace('\\', '/', realpath($source));

    if ( is_dir($source) === true )
    {
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);

        foreach ( $files as $file )
        {
            $file = str_replace('\\', '/', $file);

            // Ignore "." and ".." folders
            if ( in_array(substr($file, strrpos($file, '/') + 1), array('.', '..')) )
                continue;

            $file = realpath($file);

            if ( is_dir($file) === true )
            {
                $zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
            }
            else if ( is_file($file) === true )
            {
                $zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
            }
        }
    }
    else if ( is_file($source) === true )
    {
        $zip->addFromString(basename($source), file_get_contents($source));
    }

    return $zip->close();
}

function skadate_after_route_handler()
{
    OW_ViewRenderer::getInstance()->assignVar('adminDashboardIframeUrl', "//static.oxwall.org/spotlight/?platform=skadate&platform-version=" . OW::getConfig()->getValue('base', 'soft_version') . "&platform-build=" . OW::getConfig()->getValue('base', 'soft_build'));

    $config = OW::getConfig();

    if ( !$config->configExists('skadate', 'installInit') )
    {
        return;
    }

    $installDir = dirname(__FILE__) . DS . "files" . DS;
    $installPluginfiles = $installDir . 'ow_pluginfiles' . DS;
    $installUserfiles = $installDir . 'ow_userfiles' . DS;

    /* read all plugins from DB */
    $plugins = BOL_PluginService::getInstance()->findActivePlugins();
    $pluginList = array();

    /* @var $value BOL_Plugin */
    foreach ( $plugins as $value )
    {
        $pluginList[$value->getKey()] = ( $value->isSystem ?
                new OW_SystemPlugin(array('dir_name' => $value->getModule(), 'key' => $value->getKey(), 'active' => $value->isActive(), 'dto' => $value)) :
                new OW_Plugin(array('dir_name' => $value->getModule(), 'key' => $value->getKey(), 'active' => $value->isActive(), 'dto' => $value))
            );
    }

    /* @var $plugin OW_Plugin */
    foreach ( $pluginList as $plugin )
    {
        if ( !file_exists($plugin->getPluginFilesDir()) )
        {
            mkdir($plugin->getPluginFilesDir());
        }

        chmod($plugin->getPluginFilesDir(), 0777);

        if ( file_exists($installPluginfiles . $plugin->getModuleName()) )
        {
            UTIL_File::copyDir($installPluginfiles . $plugin->getModuleName(), $plugin->getPluginFilesDir());
        }

        if ( !file_exists($plugin->getUserFilesDir()) )
        {
            mkdir($plugin->getUserFilesDir());
        }

        chmod($plugin->getUserFilesDir(), 0777);

        if ( file_exists($installUserfiles . $plugin->getModuleName()) )
        {
            UTIL_File::copyDir($installUserfiles . $plugin->getModuleName(), $plugin->getUserFilesDir());
        }
    }

    $config->deleteConfig('skadate', 'installInit');
}
OW::getEventManager()->bind(OW_EventManager::ON_AFTER_ROUTE, 'skadate_after_route_handler');

function skadate_on_plugin_info_update( OW_Event $event )
{
    $data = $event->getParams();

    $data['bundle'] = 'skadate';
    $data['bundleKey'] = OW::getConfig()->getValue('skadate', 'license_key');

    $event->setData($data);
}
OW::getEventManager()->bind('base.on_plugin_info_update', 'skadate_on_plugin_info_update');

function skadate_on_finalize( OW_Event $event )
{
    $plugin = OW::getPluginManager()->getPlugin("skadate");
    OW::getDocument()->addStyleSheet($plugin->getStaticCssUrl() . "skadate.css");
    OW::getDocument()->addScript($plugin->getStaticJsUrl() . "skadate.js");
}
OW::getEventManager()->bind(OW_EventManager::ON_FINALIZE, 'skadate_on_finalize');

//if ( !(bool) OW::getConfig()->getValue('skadate', 'license_key_valid') )
//{
//    function skadate_license_key_valid( BASE_CLASS_EventCollector $e )
//    {
//        $e->add(OW::getLanguage()->text('skadate', 'invalid_license_key'));
//    }
//    OW::getEventManager()->bind('admin.add_admin_warning', 'skadate_license_key_valid');
//}

function skadate_admin_page_hanler()
{
    if ( (bool) OW::getConfig()->getValue('skadate', 'license_key_valid') )
    {
        return;
    }
    $attrs = OW::getRequestHandler()->getHandlerAttributes();

    if (
        ($attrs[OW_RequestHandler::ATTRS_KEY_CTRL] == 'SKADATE_CTRL_Admin' && $attrs[OW_RequestHandler::ATTRS_KEY_ACTION] == 'invalidKey') || ($attrs[OW_RequestHandler::ATTRS_KEY_CTRL] == 'ADMIN_CTRL_Plugins' && $attrs[OW_RequestHandler::ATTRS_KEY_ACTION] == 'manualUpdateRequest')
    )
    {
        return;
    }

    if ( in_array('ADMIN_CTRL_Abstract', class_parents($attrs[OW_RequestHandler::ATTRS_KEY_CTRL])) )
    {
       OW::getRequestHandler()->setHandlerAttributes(array(OW_RequestHandler::ATTRS_KEY_CTRL => 'SKADATE_CTRL_Admin', OW_RequestHandler::ATTRS_KEY_ACTION => 'invalidKey'));
    }
}
OW::getEventManager()->bind(OW_EventManager::ON_AFTER_ROUTE, 'skadate_admin_page_hanler');

function skadate_admin_menu_hanler()
{
    $attrs = OW::getRequestHandler()->getHandlerAttributes();

    if ( in_array('ADMIN_CTRL_Abstract', class_parents($attrs[OW_RequestHandler::ATTRS_KEY_CTRL])) )
    {
        if ( OW::getDocument()->getMasterPage() != null )
        {
            OW::getDocument()->getMasterPage()->deleteMenu('menu_mobile');
        }
    }
}
OW::getEventManager()->bind(OW_EventManager::ON_BEFORE_DOCUMENT_RENDER, 'skadate_admin_menu_hanler');

//SKADATE_BOL_LicenceService::getInstance()->validateKey();

function skadate_disable_gender( OW_Event $e )
{
    $params = $e->getParams();
    $data = $e->getData();

    if ( !empty($params['questionDto']) && $params['questionDto'] instanceof BOL_Question && $params['questionDto']->name == 'sex' )
    {
        $disableActionList = array(
            'disable_account_type' => true,
            'disable_answer_type' => true,
            'disable_presentation' => true,
            'disable_column_count' => true,
            'disable_display_config' => true,
            'disable_possible_values' => true,
            'disable_required' => true,
            'disable_on_join' => true,
            'disable_on_view' => false,
            'disable_on_search' => true,
            'disable_on_edit' => true
        );

        $e->setData($disableActionList);
    }

    if ( !empty($params['questionDto']) && $params['questionDto'] instanceof BOL_Question && $params['questionDto']->name == 'birthdate' )
    {
        $disableActionList = array(
            'disable_account_type' => true,
            'disable_answer_type' => true,
            'disable_presentation' => true,
            'disable_column_count' => true,
            'disable_display_config' => false,
            'disable_possible_values' => true,
            'disable_required' => true,
            'disable_on_join' => true,
            'disable_on_view' => false,
            'disable_on_search' => false,
            'disable_on_edit' => false
        );

        $e->setData($disableActionList);
    }

    if ( !empty($params['questionDto']) && $params['questionDto'] instanceof BOL_Question && $params['questionDto']->name == 'match_sex' )
    {
        $disableActionList = array(
            'disable_account_type' => true,
            'disable_answer_type' => true,
            'disable_presentation' => true,
            'disable_column_count' => false,
            'disable_display_config' => true,
            'disable_possible_values' => true,
            'disable_required' => true,
            'disable_on_join' => true,
            'disable_on_view' => false,
            'disable_on_search' => true,
            'disable_on_edit' => false
        );

        $e->setData($disableActionList);
    }
}
OW::getEventManager()->bind('admin.disable_fields_on_edit_profile_question', 'skadate_disable_gender');

function skadate_gender_disable_values_massage( OW_Event $e )
{
    $params = $e->getParams();

    if ( !empty($params['name']) && ( $params['name'] == 'sex' || $params['name'] == 'match_sex' ) )
    {
        $e->setData(OW::getLanguage()->text('skadate', 'disable_possible_values_disable_message'));
    }
}
OW::getEventManager()->bind('admin.get.possible_values_disable_message', 'skadate_gender_disable_values_massage');

function skadate_gender_get_admin_labels( OW_Event $e )
{
    $params = $e->getParams();
    $data = $e->getData();

    if ( !empty($params['question']['name']) && $params['question']['name'] == 'sex' )
    {
        $e->setData(OW::getLanguage()->text('skadate', 'admin_sex_values_label'));
    }

    if ( !empty($params['question']['name']) && $params['question']['name'] == 'sex' )
    {
        $e->setData(OW::getLanguage()->text('skadate', 'admin_match_sex_values_label'));
    }
}
OW::getEventManager()->bind('admin.questions.get_edit_delete_question_buttons_content', 'skadate_gender_get_admin_labels');

function skadate_gender_get_edit_delete_question_buttons_content( OW_Event $e )
{
    $params = $e->getParams();
    $data = $e->getData();

    if ( !empty($params['question']['name']) && $params['question']['name'] == 'sex' )
    {
        $e->setData(" ");
    }
}
OW::getEventManager()->bind('admin.questions.get_edit_delete_question_buttons_content', 'skadate_gender_get_edit_delete_question_buttons_content');

function skadate_get_preview_question_values_content( OW_Event $e )
{
    $params = $e->getParams();
    $data = $e->getData();

    if ( !empty($params['question']['name']) )
    {
        if ( $params['question']['name'] == 'sex' )
        {
            //$e->setData(OW::getLanguage()->text('skadate', 'preview_sex_values'));
        }

        if ( $params['question']['name'] == 'match_sex' )
        {

            $values = BOL_QuestionService::getInstance()->findQuestionsValuesByQuestionNameList(array('match_sex'));
            $values = $values['match_sex']['values'];
            $valuesHtml = '';

            /* @var $value BOL_QuestionValue */
            foreach ( $values as $value )
            {
                $valuesHtml .= '<li>' . BOL_QuestionService::getInstance()->getQuestionValueLang('match_sex', $value->value) . '</li>';
            }

            $html = '';
            if ( !empty($valuesHtml) )
            {
                $html = '<div class="question_values_div">
                    <center><a class="question_values" href="javascript://">' . OW::getLanguage()->text('admin', 'questions_values_count', array('count' => count($values))) . '</a></center>

                    <div style="padding:0 0 0 15px;text-align:left;display:none;width:100px;overflow:hidden;" >
                        <ul style="list-style-type:disc;">
                            ' . $valuesHtml . '
                        </ul>
                    </div>
                </div>';
            }

            $e->setData($html);
        }
    }
}
OW::getEventManager()->bind('admin.questions.get_preview_question_values_content', 'skadate_get_preview_question_values_content');

function skadate_gender_get_account_types_checkbox_content( OW_Event $e )
{
    $params = $e->getParams();
    $data = $e->getData();

    if ( !empty($params['question']['name']) && ($params['question']['name'] == 'sex' || $params['question']['name'] == 'match_sex') )
    {
        //$e->setData(" ");
    }
}
OW::getEventManager()->bind('admin.questions.get_account_types_checkbox_content', 'skadate_gender_get_account_types_checkbox_content');

function skadate_add_questions_to_new_account_type( OW_Event $e )
{
    $params = $e->getParams();

    $data = $e->getData();

    if ( !empty($params['dto']) && $params['dto'] instanceof BOL_QuestionAccountType )
    {
        $data['sex'] = 'sex';
        $data['match_sex'] = 'match_sex';
        $e->setData($data);
    }
    return $data;
}
OW::getEventManager()->bind(BOL_QuestionService::EVENT_BEFORE_ADD_QUESTIONS_TO_NEW_ACCOUNT_TYPE, 'skadate_add_questions_to_new_account_type');

function skadate_get_join_form( OW_Event $e )
{
    $params = $e->getParams();
    $data = $e->getData();
    $data = new SKADATE_CLASS_JoinForm($params['arguments'][0]);
    $e->setData($data);
    
    return $data;
}
OW::getEventManager()->bind('class.get_instance.JoinForm', 'skadate_get_join_form');

function skadate_base_user_register( OW_Event $e )
{
    $params = $e->getParams();

    if ( empty($params['userId']) )
    {
        return;
    }

    $event = new OW_Event('base.create_user_album', array('userId' => $params['userId']));
    OW::getEventManager()->trigger($event);
}
OW::getEventManager()->bind(OW_EventManager::ON_USER_REGISTER, 'skadate_base_user_register');

//function skadate_mailbox_on_available_mode_collect( BASE_CLASS_EventCollector $event )
//{
//    $event->add('chat');
//}
//OW::getEventManager()->bind('plugin.mailbox.on_available_mode_collect', 'skadate_mailbox_on_available_mode_collect');

//function skadate_mailbox_on_get_active_modes( OW_Event $event )
//{
//    $event->setData(array('chat'));
//}
//OW::getEventManager()->bind('plugin.mailbox.get_active_modes', 'skadate_mailbox_on_get_active_modes');

function skadate_onAddFakeQuestions( OW_Event $e )
{
    $params = $e->getParams();

    if ( !empty($params['name']) && $params['name'] == 'match_age' )
    {
        $e->setData(false);
    }
}
OW::getEventManager()->bind('base.questions_field_add_fake_questions', 'skadate_onAddFakeQuestions');

//function skadate_addAuthLabelsForMailbox( OW_Event $event )
//{
//    $language = OW::getLanguage();
//
//    $actions = array(
//        'send_chat_message' => $language->text('mailbox', 'auth_action_label_send_message_alt'),
//        'read_chat_message' => $language->text('mailbox', 'auth_action_label_read_message_alt'),
//        'reply_to_chat_message' => $language->text('mailbox', 'auth_action_label_reply_to_message_alt'),
//    );
//
//    $event->setData(array('actions' => $actions));
//}
//OW::getEventManager()->bind('mailbox.admin.add_auth_labels', 'skadate_addAuthLabelsForMailbox');

function skadate_class_get_instance_BASE_CMP_Users( OW_Event $event )
{
    $params = $event->getParams();

    $data = $event->getData();
    
    $data =  new SKADATE_CMP_UserList($params['arguments'][0], $params['arguments'][1], $params['arguments'][2]);
    
    $event->setData($data);
    
    return $data;
}
OW::getEventManager()->bind('class.get_instance.BASE_CMP_Users', 'skadate_class_get_instance_BASE_CMP_Users');


OW::getThemeManager()->addDecorator('user_big_list_item', 'skadate');

function skadate_get_question_page_checkbox_content( OW_Event $event )
{
    $params = $event->getParams();
    $data = $event->getData();

    if ( empty($params['question']['name']) )
    {
        return;
    }

    if ( $params['question']['name'] == 'sex' )
    {
        $data['join'] = '<div class="on_join ow_checkbox ow_checkbox_cell_marked_lock"></div>';
        $event->setData($data);
    }
}
OW::getEventManager()->bind('admin.questions.get_question_page_checkbox_content', 'skadate_get_question_page_checkbox_content');

SKADATE_CLASS_EventHandler::getInstance()->genericInit();
OW::getEventManager()->bind('speedmatch.display_mutual_message', array(SKADATE_CLASS_EventHandler::getInstance(), 'displaySpeedmatchMutualMessage'));

function skadate_get_soft_version_text( OW_Event $event )
{
    $plugin = OW::getPluginManager()->getPlugin('skadate')->getDto();

    $var = array(
        'skadate_version' => 'Skadate', // skadate version name
        'skadate_build' => $plugin->build,
        'oxwall_version' => OW::getConfig()->getValue('base', 'soft_version'),
        'oxwall_build' => OW::getConfig()->getValue('base', 'soft_build')
    );

    $text = OW::getLanguage()->text('skadate', 'soft_version', $var);

    $event->setData($text);
}
OW::getEventManager()->bind('admin.get_soft_version_text', 'skadate_get_soft_version_text');

function skadate_after_plugin_init( OW_Event $event )
{
    if ( OW::getPluginManager()->getPlugin('skadate')->getDto()->build == 7651 && OW::getConfig()->configExists('skadate', 'update_gender_values') )
    {
        SKADATE_BOL_AccountTypeToGenderService::getInstance()->getInstance()->updateGenderValues();
        OW::getConfig()->saveConfig('skadate', 'update_gender_values', false);
    }
}
OW::getEventManager()->bind(OW_EventManager::ON_PLUGINS_INIT, 'skadate_after_plugin_init');

// temp fix for pcgallery plugin, should be deleted in 1.7.1
function skadate_update_plugin_devkey()
{
    OW::getDbo()->query("UPDATE `" . OW_DB_PREFIX . "base_plugin` SET developerKey=:dk  WHERE `key`=:k", array(
        "dk" => "99d6bdd5bb6468beaf118c4664dd92ff",
        "k" => "pcgallery"
    ));
}
OW::getEventManager()->bind(OW_EventManager::ON_AFTER_REQUEST_HANDLE, 'skadate_update_plugin_devkey');

function skadate_add_members_only_exception( BASE_CLASS_EventCollector $event )
{
    $event->add(array('controller' => 'SKADATE_CTRL_Join', 'action' => 'index'));
    $event->add(array('controller' => 'SKADATE_CTRL_Join', 'action' => 'joinFormSubmit'));
}
OW::getEventManager()->bind('base.members_only_exceptions', 'skadate_add_members_only_exception');

function skadate_on_plugin_deactivate( OW_Event $event )
{
    $params = $event->getParams();

    if ( ($params['pluginKey'] == 'skadateios' && !OW::getPluginManager()->isPluginActive('skandroid')) ||
        ($params['pluginKey'] == 'skandroid' && !OW::getPluginManager()->isPluginActive('skadateios'))
    )
    {
        BOL_ComponentAdminService::getInstance()->deleteWidget('SKADATE_CMP_MobileExperience');
    }
}
OW::getEventManager()->bind(OW_EventManager::ON_BEFORE_PLUGIN_UNINSTALL, 'skadate_on_plugin_deactivate');
OW::getEventManager()->bind(OW_EventManager::ON_BEFORE_PLUGIN_DEACTIVATE, 'skadate_on_plugin_deactivate');

function skadate_on_plugin_activate( OW_Event $event )
{
    $params = $event->getParams();

    if ( in_array($params['pluginKey'], array('skadateios', 'skandroid')) )
    {
        $widgetService = BOL_ComponentAdminService::getInstance();
        $widget = $widgetService->addWidget('SKADATE_CMP_MobileExperience', false);

        try
        {
            $placeWidget = $widgetService->addWidgetToPlace($widget, BOL_ComponentAdminService::PLACE_INDEX);
            $widgetService->addWidgetToPosition($placeWidget, BOL_ComponentService::SECTION_RIGHT);

            $placeWidget = $widgetService->addWidgetToPlace($widget, BOL_ComponentAdminService::PLACE_DASHBOARD);
            $widgetService->addWidgetToPosition($placeWidget, BOL_ComponentService::SECTION_RIGHT);
        }
        catch ( Exception $e )
        {
            OW::getLogger('skadate.activate_widget_mobile_experience')->addEntry(json_encode($e));
        }
    }
}
OW::getEventManager()->bind(OW_EventManager::ON_AFTER_PLUGIN_INSTALL, 'skadate_on_plugin_activate');
OW::getEventManager()->bind(OW_EventManager::ON_AFTER_PLUGIN_ACTIVATE, 'skadate_on_plugin_activate');
