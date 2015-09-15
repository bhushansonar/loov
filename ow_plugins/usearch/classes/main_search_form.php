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
 * Users main search component
 *
 * @author Egor Bulgakov <egor.bulgakov@gmail.com>, Podyachev Evgeny <joker.OW2@gmail.com>
 * @package ow.ow_plugins.usearch.components
 * @since 1.5.3
 */
class USEARCH_CLASS_MainSearchForm extends BASE_CLASS_UserQuestionForm
{
    const SUBMIT_NAME = 'MainSearchFormSubmit';

    const FORM_SESSEION_VAR = 'MAIN_SEARCH_FORM_DATA';

    public $controller;
    public $accountType;
    public $matchSexValue;
    public $displayAccountType = false;
    public $displayGender = false;
    public $displayMainSearch = true;

    /**
     * @param OW_ActionController $controller
     */
    public function __construct( $controller )
    {
        parent::__construct('MainSearchForm');

        $this->controller = $controller;

        $questionService = BOL_QuestionService::getInstance();

        $this->setId('MainSearchForm');

        $submit = new Submit(self::SUBMIT_NAME);
        $submit->setValue(OW::getLanguage()->text('base', 'user_search_submit_button_label'));
        $this->addElement($submit);

        $questionData = OW::getSession()->get(self::FORM_SESSEION_VAR);
        
        if ( $questionData === null )
        {
            $questionData = array();

            if ( OW::getUser()->isAuthenticated() )
            {
                $data = BOL_QuestionService::getInstance()->getQuestionData(array(OW::getUser()->getId()), array('match_sex'));
                $questionData['match_sex'] = $data[OW::getUser()->getId()]['match_sex'];

                $questionData['googlemap_location']['distance'] = 50;
                
                OW::getSession()->set(self::FORM_SESSEION_VAR, $questionData);
            }
        }

        if ( !empty($questionData['match_sex']) )
        {
            if ( is_array($questionData['match_sex']) )
            {
                $questionData['match_sex'] = array_shift($questionData['match_sex']);
            }
            else
            {
                for ( $i = 0; $i < 31; $i++ )
                {
                    if( pow(2, $i) & $questionData['match_sex'] )
                    {
                        $questionData['match_sex'] = pow(2, $i);
                        break;
                    }
                }
            }
        }

        $accounts = $this->getAccountTypes();

        $accountList = array();

        foreach ( $accounts as $key => $account )
        {
            $accountList[$key] = $account;
        }

        $keys = array_keys($accountList);

        $this->accountType = $keys[0];
        $this->matchSexValue = USEARCH_BOL_Service::getInstance()->getGenderByAccounType($this->accountType);
        
        if ( isset($questionData['match_sex']) )
        {
            $accountType = USEARCH_BOL_Service::getInstance()->getAccounTypeByGender($questionData['match_sex']);

            if ( !empty($accountType) )
            {
                $this->accountType = $accountType;
                $this->matchSexValue = $questionData['match_sex'];
            }
        }

        $questions = $questionService->findSearchQuestionsForAccountType($this->accountType);

        $mainSearchQuestion = array();
        $questionNameList = array('sex' => 'sex', 'match_sex' => 'match_sex');

        foreach ( $questions as $key => $question )
        {
            $sectionName = $question['sectionName'];

            $questionNameList[] = $question['name'];
            $isRequired = in_array($question['name'], array('googlemap_location', 'match_sex')) ? 1 : 0;
            $questions[$key]['required'] = $isRequired;

            if ( $question['name'] == 'sex' || $question['name'] == 'match_sex' )
            {
                unset($questions[$key]);
            }
            else
            {
                $mainSearchQuestion[$sectionName][] = $question;
            }
        }

        $questionValueList = $questionService->findQuestionsValuesByQuestionNameList($questionNameList);

        $controller->assign('displayGender', false);
        
        if ( count($accounts) > 1  )
        {
            $this->displayAccountType = true;
            
            if ( !OW::getUser()->isAuthenticated() )
            {
                $controller->assign('displayGender', true);

                $sex = new Selectbox('sex');
                $sex->setLabel(BOL_QuestionService::getInstance()->getQuestionLang('sex'));
                $sex->setRequired();
                $sex->setHasInvitation(false);

                //$accountType->setHasInvitation(false);
                $this->setFieldOptions($sex, 'sex', $questionValueList['sex']);

                if ( !empty($questionData['sex']) )
                {
                    $sex->setValue($questionData['sex']);
                }

                $this->addElement($sex);
            }
            else
            {
                $sexData = BOL_QuestionService::getInstance()->getQuestionData(array(OW::getUser()->getId()), array('sex'));
                
                        
                if ( !empty($sexData[OW::getUser()->getId()]['sex']) )
                {
                    $sex = new HiddenField('sex');
                    $sex->setValue($sexData[OW::getUser()->getId()]['sex']);
                    $this->addElement($sex);
                }
            }

            $matchSex = new Selectbox('match_sex');
            $matchSex->setLabel(BOL_QuestionService::getInstance()->getQuestionLang('match_sex'));
            $matchSex->setRequired();
            $matchSex->setHasInvitation(false);

            //$accountType->setHasInvitation(false);
            $this->setFieldOptions($matchSex, 'match_sex', $questionValueList['sex']);
            
            if ( !empty($questionData['match_sex']) )
            {
                $matchSex->setValue($questionData['match_sex']);
            }

            $this->addElement($matchSex);
        }

        $this->addQuestions($questions, $questionValueList, $questionData);

        $locationField = $this->getElement('googlemap_location');
        if ( $locationField )
        {
            $value = $locationField->getValue();
            if ( empty($value['json']) )
            {
                $locationField->setDistance(50);
            }
        }

        $controller->assign('questionList', $mainSearchQuestion);
        $controller->assign('displayAccountType', $this->displayAccountType);
        
        // 'online' field
        $onlineField = new CheckboxField('online');
        if ( !empty($questionData) && is_array($questionData) && array_key_exists('online', $questionData) )
        {
            $onlineField->setValue($questionData['online']);
        }
        $onlineField->setLabel(OW::getLanguage()->text('usearch', 'online_only'));
        $this->addElement($onlineField);
        
//        if ( OW::getPluginManager()->isPluginActive('photo') )
//        {
            // with photo
            $withPhoto = new CheckboxField('with_photo');
            if ( !empty($questionData) && is_array($questionData) && array_key_exists('with_photo', $questionData) )
            {
                $withPhoto->setValue($questionData['with_photo']);
            }
            $withPhoto->setLabel(OW::getLanguage()->text('usearch', 'with_photo'));
            $this->addElement($withPhoto);
//        }
    }

    public function process( $data )
    {
        if ( OW::getRequest()->isPost() && !$this->isAjax() && isset($data['form_name']) && $data['form_name'] === $this->getName() )
        {
            OW::getSession()->set(self::FORM_SESSEION_VAR, $data);
            OW::getSession()->set('usearch_search_data', $data);

            if ( isset($data[self::SUBMIT_NAME]) && $this->isValid($data) && !$this->isAjax() )
            {
                if ( !OW::getUser()->isAuthorized('base', 'search_users') )
                {
                    $status = BOL_AuthorizationService::getInstance()->getActionStatus('base', 'search_users');;
                    OW::getFeedback()->warning($status['msg']);
                    $this->controller->redirect();
                }
                
                $data = USEARCH_BOL_Service::getInstance()->updateSearchData( $data );

                $addParams = array('join' => '', 'where' => '');

                if ( $data['online'] )
                {
                    $addParams['join'] .= " INNER JOIN `".BOL_UserOnlineDao::getInstance()->getTableName()."` `online` ON (`online`.`userId` = `user`.`id`) ";
                }

                if ( $data['with_photo'] )
                {
                     $addParams['join'] .= " INNER JOIN `".OW_DB_PREFIX . "base_avatar` avatar ON (`avatar`.`userId` = `user`.`id`) ";
//                    $addParams['join'] .= " INNER JOIN `".OW_DB_PREFIX . "photo_album` album ON (`album`.`userId` = `user`.`id`)
//                            INNER JOIN `". OW_DB_PREFIX . "photo` `photo` ON (`album`.`id` = `photo`.`albumId`) ";
                }
                
                $userIdList = BOL_UserService::getInstance()->findUserIdListByQuestionValues($data, 0, BOL_SearchService::USER_LIST_SIZE, false, $addParams);
                $listId = 0;

                if ( OW::getUser()->isAuthenticated() )
                {
                    foreach ( $userIdList as $key => $id )
                    {
                        if ( OW::getUser()->getId() == $id )
                        {
                            unset($userIdList[$key]);
                        }
                    }
                }

                if ( count($userIdList) > 0 )
                {
                    $listId = BOL_SearchService::getInstance()->saveSearchResult($userIdList);
                }

                OW::getSession()->set(BOL_SearchService::SEARCH_RESULT_ID_VARIABLE, $listId);

                BOL_AuthorizationService::getInstance()->trackAction('base', 'search_users');
                $this->controller->redirect(OW::getRouter()->urlForRoute("users-search-result", array()));
            }
            
            $this->controller->redirect(OW::getRouter()->urlForRoute("users-search"));
        }
    }

    protected function getPresentationClass( $presentation, $questionName, $configs = null )
    {
        return BOL_QuestionService::getInstance()->getSearchPresentationClass($presentation, $questionName, $configs);
    }

    protected function setFieldOptions( $formField, $questionName, array $questionValues )
    {
        parent::setFieldOptions($formField, $questionName, $questionValues);

        if ( $questionName == 'match_sex' )
        {
            $options = array_reverse($formField->getOptions(), true);
            $formField->setOptions($options);
        }

        $formField->setLabel(OW::getLanguage()->text('base', 'questions_question_' . $questionName . '_label'));
    }

    protected function setFieldValue( $formField, $presentation, $value )
    {
        if ( !empty($value) )
        {
            $value = BOL_QuestionService::getInstance()->prepareFieldValueForSearch($presentation, $value);
            $formField->setValue($value);
        }
    }
}