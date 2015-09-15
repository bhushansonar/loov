<?php

/**
 * Copyright (c) 2014, Skalfa LLC
 * All rights reserved.
 * 
 * ATTENTION: This commercial software is intended for exclusive use with SkaDate Dating Software (http://www.skadate.com) and is licensed under SkaDate Exclusive License by Skalfa LLC.
 * 
 * Full text of this license can be found at http://www.skadate.com/sel.pdf
 */
class SKADATE_CLASS_EventHandler
{
    /**
     * @var SKADATE_CLASS_EventHandler
     */
    private static $classInstance;

    /**
     * @return SKADATE_CLASS_EventHandler
     */
    public static function getInstance()
    {
        if ( self::$classInstance === null )
        {
            self::$classInstance = new self();
        }

        return self::$classInstance;
    }

    public function __construct()
    {
        
    }

    public function genericInit()
    {
        OW::getEventManager()->bind(BOL_QuestionService::EVENT_ON_GET_QUESTION_LANG, array($this, 'onGetGenderLangValue'));

        OW::getEventManager()->bind(BOL_QuestionService::EVENT_ON_ACCOUNT_TYPE_ADD, array($this, 'onUpdateAccountTypes'));
        OW::getEventManager()->bind(BOL_QuestionService::EVENT_ON_ACCOUNT_TYPE_DELETE, array($this, 'onUpdateAccountTypes'));
        OW::getEventManager()->bind(BOL_QuestionService::EVENT_ON_ACCOUNT_TYPE_REORDER, array($this, 'onUpdateAccountTypes'));

        OW::getEventManager()->bind(OW_EventManager::ON_BEFORE_USER_COMPLETE_PROFILE, array($this, 'onBeforeCompleteAccountType'));
        OW::getEventManager()->bind(OW_EventManager::ON_BEFORE_USER_COMPLETE_ACCOUNT_TYPE, array($this, 'onBeforeCompleteAccountType'));

        OW::getEventManager()->bind('base.questions_get_data', array($this, 'onGetGenderData'));
        OW::getEventManager()->bind('base.questions_save_data', array($this, 'onUserUpdate'));
        OW::getEventManager()->bind('base.after_avatar_change', array($this, 'onAvatarChange'));

        OW::getEventManager()->bind(OW_EventManager::ON_USER_REGISTER, array($this, 'onUserRegister'));
        OW::getEventManager()->bind(OW_EventManager::ON_USER_UNREGISTER, array($this, 'onUserUnregister'));

        OW::getEventManager()->bind('usercredits.get_product_id', array($this, 'usercreditsGetProductId'));
        OW::getEventManager()->bind('membership.get_product_id', array($this, 'membershipGetProductId'));

        OW::getEventManager()->bind('speedmatch.suggest_users', array($this, 'suggestSpeedmatchUsers'));

        OW::getEventManager()->bind('photo.collect_extended_settings', array($this, 'photoCollectExtendedSettings'));
        OW::getEventManager()->bind('photo.save_extended_settings', array($this, 'photoSaveExtendedSettings'));
        OW::getEventManager()->bind('photo.getPhotoList', array($this, 'photoGetPhotoList'));
        OW::getEventManager()->bind('admin.filter_themes_to_choose', array($this, 'filterAdminThemes'));
        OW::getEventManager()->bind('core.get_text', array($this, 'onGetText'));
    }

    public function apiInit()
    {
        OW::getEventManager()->bind('speedmatch.display_mutual_message', array($this, 'displaySpeedmatchMutualMessageForApi'));
    }

    public function filterAdminThemes( OW_Event $e )
    {
        $themesArr = $e->getData();
        unset($themesArr[BOL_ThemeService::DEFAULT_THEME]);
        $e->setData($themesArr);
    }

    public function onGetGenderLangValue( OW_Event $event )
    {
        $params = $event->getParams();

        $type = $params['type'];
        $name = $params['name'];
        $value = $params['value'];

        $data = null;

        if ( $type == BOL_QuestionService::LANG_KEY_TYPE_QUESTION_VALUE && ( $name == 'sex' || $name == 'match_sex' ) )
        {
            $accountTypesToGender = SKADATE_BOL_AccountTypeToGenderService::getInstance()->findAll();

            foreach ( $accountTypesToGender as $item )
            {
                /* @var $value SKADATE_BOL_AccountTypeToGender */
                if ( $item->genderValue == $value )
                {
                    $data = 'questions_account_type_' . $item->accountType;
                    break;
                }
            }

            $event->setData($data);
        }
    }

    public function onUpdateAccountTypes( OW_Event $event )
    {
        SKADATE_BOL_AccountTypeToGenderService::getInstance()->getInstance()->updateGenderValues();

        $accountTypes = BOL_QuestionService::getInstance()->findAllAccountTypes();
        $sex = BOL_QuestionService::getInstance()->findQuestionByName('sex');
        $match_sex = BOL_QuestionService::getInstance()->findQuestionByName('match_sex');

        if ( !empty($sex) )
        {

            $sex->onEdit = 0;
            $sex->onJoin = 0;
            $sex->onSearch = 0;
            //$sex->onView = 1;
            $sex->required = 1;

            if ( count($accountTypes) < 2 )
            {
                $sex->onEdit = 0;
                $sex->onJoin = 0;
                $sex->onSearch = 0;
                $sex->onView = 0;
                $sex->required = 1;
            }

            BOL_QuestionService::getInstance()->saveOrUpdateQuestion($sex);
        }

        //    if ( count($accountTypes) > 1 )
        //    {
        //        if ( !empty($match_sex) )
        //        {
        //            $match_sex->onEdit = 1;
        //            $match_sex->onSearch = 1;
        //        }
        //    }
        //    else
        //    {
        if ( !empty($match_sex) )
        {
            //$match_sex->onEdit = 0;
            $match_sex->onSearch = 1;
            $match_sex->onEdit = 1;
            $match_sex->onJoin = 1;
            //$match_sex->onView = 1;

            if ( count($accountTypes) < 2 )
            {
                $match_sex->onSearch = 0;
                $match_sex->onEdit = 0;
                $match_sex->onJoin = 0;
                $match_sex->onView = 0;
            }

            BOL_QuestionService::getInstance()->saveOrUpdateQuestion($match_sex);
        }
        //    }
        //
    //    if ( !empty($match_sex) )
        //    {
        //        BOL_QuestionService::getInstance()->saveOrUpdateQuestion($match_sex);
        //    }
    }

    public function onBeforeCompleteAccountType( OW_Event $event )
    {
        $params = $event->getParams();
        $user = $params['user'];

        if ( empty($user) )
        {
            return;
        }

        $this->updateMatchSex($user);
    }

    protected function updateMatchSex( $user )
    {
        $accountTypes = BOL_QuestionService::getInstance()->findAllAccountTypes();

        if ( count($accountTypes) < 2 )
        {
            $match_sex = BOL_QuestionService::getInstance()->findQuestionByName('match_sex');

            if ( !empty($match_sex) )
            {
                $accountType = BOL_QuestionService::getInstance()->getDefaultAccountType();

                $gender = SKADATE_BOL_AccountTypeToGenderService::getInstance()->getGender($accountType->name);

                if ( !empty($gender) )
                {
                    BOL_QuestionService::getInstance()->saveQuestionsData(array('match_sex' => $gender), $user->id);
                }
            }
        }
    }

    public function onGetGenderData( OW_Event $e )
    {
        $params = $e->getParams();
        $data = $e->getData();

        foreach ( $data as $userId => $questions )
        {
            foreach ( $questions as $key => $value )
            {
                if ( $key == 'sex' )
                {
                    $user = BOL_UserService::getInstance()->findUserById($userId);
                    $dtoList = SKADATE_BOL_AccountTypeToGenderService::getInstance()->findAll();

                    if ( !empty($data[$userId][$key]) )
                    {
                        unset($data[$userId][$key]);
                    }

                    $value = 0;

                    foreach ( $dtoList as $dto )
                    {
                        /* @var $dto SKADATE_BOL_AccountTypeToGender */
                        if ( $dto->accountType == $user->accountType )
                        {
                            $value = $dto->genderValue;
                            break;
                        }
                    }

                    if ( !empty($value) )
                    {
                        $data[$userId][$key] = $value;
                    }
                    else
                    {
                        unset($data[$userId][$key]);
                    }
                }

                if ( $key == 'match_sex' )
                {
                    $dtoList = SKADATE_BOL_AccountTypeToGenderService::getInstance()->findAll();

                    if ( empty($data[$userId][$key]) )
                    {
                        unset($data[$userId][$key]);
                        break;
                    }

                    $value = 0;
                    foreach ( $dtoList as $dto )
                    {
                        //$value = $dto->genderValue;

                        /* @var $dto SKADATE_BOL_AccountTypeToGender */

                        if ( (int) $dto->genderValue & (int) $data[$userId][$key] )
                        {
                            $value += $dto->genderValue;
                        }
                    }

                    if ( !empty($value) )
                    {
                        $data[$userId][$key] = $value;
                    }
                    else
                    {
                        unset($data[$userId][$key]);
                    }
                }
            }
        }

        $e->setData($data);
    }

    public function onUserUpdate( OW_Event $event )
    {
        $data = $event->getData();

        if ( !empty($data['accountType']) )
        {
            $genderToAccountTypeList = SKADATE_BOL_AccountTypeToGenderService::getInstance()->findAll();

            foreach ( $genderToAccountTypeList as $value )
            {
                if ( $value->accountType == $data['accountType'] )
                {
                    $data['sex'] = $value->genderValue;
                }
            }
        }
        else if ( !empty($data['sex']) )
        {
            $genderToAccountTypeList = SKADATE_BOL_AccountTypeToGenderService::getInstance()->findAll();
            /* @var $value SKADATE_BOL_AccountTypeToGender */
            foreach ( $genderToAccountTypeList as $value )
            {
                if ( $value->genderValue == $data['sex'] )
                {
                    $data['accountType'] = $value->accountType;
                }
            }
        }

        $event->setData($data);
    }

    public function onUserRegister( OW_Event $event )
    {
        $params = $event->getParams();

        if ( empty($params['userId']) )
        {
            return;
        }

        $user = BOL_UserService::getInstance()->findUserById($params['userId']);

        if ( empty($user) )
        {
            return;
        }

        $data = array();

        $accountType = $user->accountType;
        $questionData = BOL_QuestionService::getInstance()->getQuestionData(array($user->id), array('sex'));

        $sex = null;

        if ( !empty($questionData[$user->id]['sex']) )
        {
            $sex = $questionData[$user->id]['sex'];
        }

        if ( !empty($accountType) )
        {
            $genderToAccountTypeList = SKADATE_BOL_AccountTypeToGenderService::getInstance()->findAll();

            foreach ( $genderToAccountTypeList as $value )
            {
                if ( $value->accountType == $accountType )
                {
                    $data['sex'] = $value->genderValue;
                }
            }
        }
        else if ( !empty($sex) )
        {
            $genderToAccountTypeList = SKADATE_BOL_AccountTypeToGenderService::getInstance()->findAll();
            /* @var $value SKADATE_BOL_AccountTypeToGender */
            foreach ( $genderToAccountTypeList as $value )
            {
                if ( $value->genderValue == $sex )
                {
                    $data['accountType'] = $value->accountType;
                }
            }
        }

        BOL_QuestionService::getInstance()->saveQuestionsData($data, $user->id);

        $this->updateMatchSex($user);
    }

    public function onUserUnregister( OW_Event $event )
    {
        $params = $event->getParams();

        if ( empty($params['userId']) )
        {
            return;
        }

        $userId = (int) $params['userId'];
        $service = SKADATE_BOL_Service::getInstance();

        $service->removeBigAvatar($userId);

        $service->removeSpeedmatchRelationsByUserId($userId);

        $service->removeCurrentLocationByUserId($userId);
    }

    public function onAvatarChange( OW_Event $e )
    {
        $params = $e->getParams();

        if ( empty($params['userId']) )
        {
            return;
        }

        $userId = (int) $params['userId'];
        SKADATE_BOL_Service::getInstance()->copyBigAvatar($userId);
    }

    public function usercreditsGetProductId( OW_Event $e )
    {
        $params = $e->getParams();

        $productId = mb_strtoupper(USERCREDITS_CLASS_UserCreditsPackProductAdapter::PRODUCT_KEY . '_' . $params['id']);

        $e->setData($productId);
    }

    public function membershipGetProductId( OW_Event $e )
    {
        $params = $e->getParams();

        $productId = mb_strtoupper(MEMBERSHIP_CLASS_MembershipPlanProductAdapter::PRODUCT_KEY . '_' . $params['id']);

        $e->setData($productId);
    }

    public function suggestSpeedmatchUsers( OW_Event $event )
    {
        $params = $event->getParams();
        $userId = $params["userId"];

        $first = (int) $params["first"];
        $count = (int) $params["count"];

        $service = SKADATE_BOL_Service::getInstance();
        $list = $service->findSpeedmatchOpponents($userId, $first, $count, $params['criteria'], $params['exclude']);

        $event->setData($list);

        return $list;
    }

    public function displaySpeedmatchMutualMessage( OW_Event $event )
    {
        $params = $event->getParams();

        if ( empty($params['userId']) || empty($params['opponentId']) )
        {
            return '';
        }

        $userService = BOL_UserService::getInstance();

        $userId = OW::getUser()->getId() == $params['userId'] ? $params['opponentId'] : $params['userId'];

        if ( !$userService->findUserById($userId) )
        {
            return '';
        }

        $message = OW::getLanguage()->text(
            'skadate', 'speedmatch_mutual_message', array('username' => $userService->getDisplayName($userId))
        );

        $event->setData($message);

        return $message;
    }

    public function displaySpeedmatchMutualMessageForApi( OW_Event $event )
    {
        $params = $event->getParams();

        if ( empty($params['userId']) || empty($params['opponentId']) )
        {
            return '';
        }

        $userService = BOL_UserService::getInstance();

        $userId = OW::getUser()->getId() == $params['userId'] ? $params['opponentId'] : $params['userId'];

        if ( !$userService->findUserById($userId) )
        {
            return '';
        }

        $message = OW::getLanguage()->text(
            'skadate', 'speedmatch_mutual_message', array('username' => $userService->getDisplayName($userId))
        );

        $data = array(
            'text' => $message
        );

        $event->setData($data);

        return $message;
    }

    public function photoCollectExtendedSettings( BASE_CLASS_EventCollector $event )
    {
        $input = new CheckboxField('matching_only');
        $input->setLabel(OW::getLanguage()->text('skadate', 'photo_setting_matching_label'));
        $input->setDescription(OW::getLanguage()->text('skadate', 'photo_setting_matching_desc'));
        $input->setValue((bool)OW::getConfig()->getValue('skadate', 'photo_filter_setting_matching'));

        $event->add(array(
            'section' => 'filter_settings',
            'section_lang' => 'skadate+photo_filter_section_label',
            'settings' => array(
                'matching_only' => $input
            )
        ));
    }

    public function photoSaveExtendedSettings( OW_Event $event )
    {
        $params = $event->getParams();

        if ( !array_key_exists('matching_only', $params) )
        {
            return;
        }

        OW::getConfig()->saveConfig('skadate', 'photo_filter_setting_matching', (bool)$params['matching_only']);
    }

    public function photoGetPhotoList( BASE_CLASS_QueryBuilderEvent $event )
    {
        $params = $event->getParams();
        $aliases = $params['aliases'];

        if ( empty($params['listType']) || !in_array($params['listType'], array('latest', 'featured', 'toprated', 'most_discussed', 'searchByDesc', 'searchByHashtag', 'searchByUsername')) )
        {
            return;
        }

        if (
            !OW::getUser()->isAuthenticated() ||
            !(bool)OW::getConfig()->getValue('skadate', 'photo_filter_setting_matching') ||
            OW::getUser()->isAuthorized('photo') ||
            OW::getUser()->isAuthorized('base')
        )
        {
            return;
        }

        $userId = OW::getUser()->getId();
        $matchValue = BOL_QuestionService::getInstance()->getQuestionData(array($userId), array('sex', 'match_sex'));

        if ( empty($matchValue[$userId]['match_sex']) )
        {
            return;
        }

        $join = 'INNER JOIN `' . BOL_UserDao::getInstance()->getTableName() . '` AS `sk_u` ON(`' . $aliases['album'] . '`.`userId` = `sk_u`.`id`)
            INNER JOIN `' . BOL_QuestionDataDao::getInstance()->getTableName() . '` AS `sk_qd` ON (`sk_qd`.`userId` = `sk_u`.`id` AND `sk_qd`.`questionName` = :sk_sexQuestionName AND `sk_qd`.`intValue` & :sk_matchSexValue)
            INNER JOIN `' . BOL_QuestionDataDao::getInstance()->getTableName() . '` AS `sk_qd1` ON (`sk_qd1`.`userId` = `sk_u`.`id` AND `sk_qd1`.`questionName` = :sk_matchSexQuestionName AND `sk_qd1`.`intValue` & :sk_sexValue)';
        $params = array(
            'sk_sexQuestionName' => 'sex',
            'sk_matchSexQuestionName' => 'match_sex',
            'sk_sexValue' => $matchValue[$userId]['sex'],
            'sk_matchSexValue' => $matchValue[$userId]['match_sex']
        );

        $event->addJoin($join);
        $event->addBatchQueryParams($params);
    }

    public function onGetText( OW_Event $event )
    {
        $params = $event->getParams();

        if ( $params['prefix'] == 'base' && ($params['key'] == 'welcome_letter_template_html' 
                || $params['key'] == 'welcome_letter_template_text' 
                || $params['key'] == 'welcome_widget_content'))
        {
            $event->setData(OW::getLanguage()->text('skadate', $params['key'], $params['vars']));
        }
    }
}
