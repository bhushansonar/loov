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
 *
 * @author Zarif Safiullin <zaph.saph@gmail.com>
 * @package ow_plugins.matchmaking.classes
 * @since 1.6.1
 */
class MATCHMAKING_CLASS_PreferencesForm extends BASE_CLASS_UserQuestionForm
{
    private $questionService;

    public function __construct()
    {
        parent::__construct('MATCHMAKING_PreferencesForm');

        $this->questionService = BOL_QuestionService::getInstance();
        $language = OW::getLanguage();

        $save = new Submit('save');
        $save->setValue($language->text('matchmaking', 'btn_label_save'));
        $this->addElement($save);

    }

    public function process($questionArray, $data)
    {
        $language = OW::getLanguage();
        $user = OW::getUser()->getUserObject();

        foreach ( $questionArray as $section )
        {
            foreach ( $section as $key => $question )
            {
                switch ( $question['presentation'] )
                {
                    case 'multicheckbox':

                        if ( is_array($data[$question['name']]) )
                        {
                            $data[$question['name']] = array_sum($data[$question['name']]);
                        }
                        else
                        {
                            $data[$question['name']] = 0;
                        }

                        break;
                }
            }
        }

        // save user data
        if ( !empty($user->id) )
        {
            if ( $this->questionService->saveQuestionsData($data, $user->id) )
            {
                $event = new OW_Event(OW_EventManager::ON_USER_EDIT, array('userId' => $user->id, 'method' => 'native',));
                OW::getEventManager()->trigger($event);

                return true;
            }
            else
            {
                return false;
            }
        }
        else
        {
            return false;
        }
    }
}