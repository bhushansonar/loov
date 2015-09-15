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
 * @author Sergey Kambalin <greyexpert@gmail.com>
 * @package ow_system_plugins.skadateios.api.controllers
 * @since 1.0
 */
class SKADATEIOS_ACTRL_SignUp extends OW_ApiActionController
{
    public function questionList( $params )
    {
        $questionService = BOL_QuestionService::getInstance();
        
        $fixedQuestionNames = array("sex", "match_sex", "email", "password", "username", "realname");
        
        if ($params["step"] == 1) {
            $questionNames = array("sex", "match_sex");
        }
        else
        {
            $gender = (int) $params["gender"];
            $accountType = SKADATE_BOL_AccountTypeToGenderService::getInstance()->getAccountType($gender);
            $signUpQuestions = $questionService->findSignUpQuestionsForAccountType($accountType);

            foreach ( $signUpQuestions as $question )
            {
                if ( $question["required"] && !in_array($question["name"], $fixedQuestionNames) )
                {
                    $questionNames[] = $question["name"];
                }
            }
        }
        
        $questionList = $questionService->findQuestionByNameList($questionNames);
        $questionOptions = $questionService->findQuestionsValuesByQuestionNameList($questionNames);
        
        $questions = array();
        
        foreach ( $questionList as $question )
        {
            /* @var $question BOL_Question */

            $custom = json_decode($question->custom, true);
            $value = null;
            
            switch ($question->presentation)
            {
                case BOL_QuestionService::QUESTION_PRESENTATION_RANGE :
                    $value = "18-33";
                    break;
                
                case BOL_QuestionService::QUESTION_PRESENTATION_BIRTHDATE :
                case BOL_QuestionService::QUESTION_PRESENTATION_AGE :
                case BOL_QuestionService::QUESTION_PRESENTATION_DATE :

                    $value = date("Y-m-d H:i:s", strtotime("-18 year"));
                    break;
            }
            
            $questions[] = array(
                'id' => $question->id,
                'name' => $question->name,
                'label' => $questionService->getQuestionLang($question->name),
                'custom' => $custom,
                'presentation' => $question->name == 'googlemap_location' ? $question->name : $question->presentation,
                'options' => self::formatOptionsForQuestion($question->name, $questionOptions),
                
                'value' => $value,
                'rawValue' => $value
            );
        }
        
        $this->assign("list", array_reverse($questions));
    }

    private static function formatOptionsForQuestion( $name, $allOptions )
    {
        $options = array();
        $questionService = BOL_QuestionService::getInstance();

        if ( !empty($allOptions[$name]) )
        {
            $optionList = array();
            foreach ( $allOptions[$name]['values'] as $option )
            {
                $optionList[] = array(
                    'label' => $questionService->getQuestionValueLang($option->questionName, $option->value),
                    'value' => $option->value
                );
            }

            $allOptions[$name]['values'] = $optionList;
            $options = $allOptions[$name];
        }

        return $options;
    }
    
    public function tryLogIn( $params )
    {
        $fbId = $params["facebookId"];
        $email = $params["email"];
        
        $userId = null;
        
        $authAdapter = new OW_RemoteAuthAdapter($fbId, "facebook");
        
        if ( $authAdapter->isRegistered() )
        {
            $authResult = OW_Auth::getInstance()->authenticate($authAdapter);
            $userId = $authResult->isValid()
                    ? $authResult->getUserId()
                    : null;
        } 
        else
        {
            $userByEmail = BOL_UserService::getInstance()->findByEmail($email);
        
            if ( $userByEmail !== null )
            {
                OW::getUser()->login($userByEmail->id);
                $userId = $userByEmail->id;
            }
        }
        
        $this->assign("loggedIn", !empty($userId));
        
        if ( !empty($userId) )
        {
            $this->respondUserData($userId);
        }
    }
    
    public function save( $params )
    {
        $data = $params["data"];
        
        $authAdapter = new OW_RemoteAuthAdapter($data["facebookId"], "facebook");
        
        $nonQuestions = array("name", "email", "avatarUrl");
        $nonQuestionsValue = array();
        foreach ( $nonQuestions as $name )
        {
            $nonQuestionsValue[$name] = empty($data[$name]) ? null : $data[$name];
            unset($data[$name]);
        }
        
        $data["realname"] = $nonQuestionsValue["name"];
        
        $email = $nonQuestionsValue["email"];
        $password = uniqid();
        
        $user = BOL_UserService::getInstance()->findByEmail($email);
        $newUser = false;
        
        if ( $user === null )
        {
            $newUser = true;
            $username = $this->makeUseranme($nonQuestionsValue["name"]);
            $user = BOL_UserService::getInstance()->createUser($username, $password, $email, null, true);
        }
        
        BOL_QuestionService::getInstance()->saveQuestionsData(array_filter($data), $user->id);
        
        if ( !empty($nonQuestionsValue["avatarUrl"]) )
        {
            $avatarUrl = $nonQuestionsValue["avatarUrl"];
            $pluginfilesDir = OW::getPluginManager()->getPlugin("skadateios")->getPluginFilesDir();
            $ext = UTIL_File::getExtension($avatarUrl);
            $tmpFile = $pluginfilesDir . uniqid("avatar-") . (empty($ext) ? "" : "." . $ext);
            copy($avatarUrl, $tmpFile);
            
            BOL_AvatarService::getInstance()->setUserAvatar($user->id, $tmpFile);
            @unlink($tmpFile);
        }
        
        if ( !$authAdapter->isRegistered() ) 
        {
            $authAdapter->register($user->id);
        }
        
        if ( $newUser )
        {
            $event = new OW_Event(OW_EventManager::ON_USER_REGISTER, array(
                'method' => 'facebook',
                'userId' => $user->id,
                'params' => array()
            ));
            OW::getEventManager()->trigger($event);
        }
        
        OW::getUser()->login($user->id);
        $this->respondUserData($user->id);
    }
    
    private function respondUserData( $userId )
    {
        $avatarService = BOL_AvatarService::getInstance();
        $userService = BOL_UserService::getInstance();
        
        $this->assign("userId", $userId);
        $this->assign("displayName", $userService->getDisplayName($userId));
        $this->assign("avatar", array(
            "url" => $avatarService->getAvatarUrl($userId)
        ));
        
        $this->assign("suspended", BOL_UserService::getInstance()->isSuspended($userId));
        $this->assign("approved", BOL_UserService::getInstance()->isApproved($userId));
        
        $service = SKADATEIOS_ABOL_Service::getInstance();
        $mainMenu = $service->getMenu($userId, 'main');
        $this->assign("mainMenu", $mainMenu);

        $bottomMenu = $service->getMenu($userId, 'bottom');
        $this->assign("bottomMenu", $bottomMenu);

        $this->assign('newCounter', $service->getNewItemsCount($mainMenu));
        
        $token = OW_Auth::getInstance()->getAuthenticator()->getId();
        $this->assign("token", $token);
    }
    
    private function makeUseranme( $name, $counter = 0 )
    {
        list($fn, $ln) = explode(' ', strtolower($name));
        $username = $fn . mb_substr($ln, 0, 1);
        
        if ( $counter > 0 ) {
            $username .= $counter;
        }
        
        if ( BOL_UserService::getInstance()->isExistUserName($username) )
        {
            return $this->makeUseranme($name, $counter + 1);
        }
        
        return $username;
    }
}