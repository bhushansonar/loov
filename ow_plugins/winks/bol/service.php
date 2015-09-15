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
 * @author Kairat Bakitow <kainisoft@gmail.com>
 * @package ow_plugins.winks.bol
 * @since 1.0
 */
class WINKS_BOL_Service
{
    CONST EMAIL_SEND = 'send';
    CONST EMAIL_BACK = 'back';

    CONST LIMIT_TIMESTAMP = 604800; // week
    
    private static $classInstance;
    
    public static function getInstance()
    {
        if ( !isset(self::$classInstance) )
        {
            self::$classInstance = new self();
        }

        return self::$classInstance;
    }

    private $winksDao;

    private function __construct()
    {
        $this->winksDao = WINKS_BOL_WinksDao::getInstance();
    }

    public function isLimited( $userId, $partnerId )
    {
        return $this->winksDao->isLimited($userId, $partnerId);
    }
    
    public function sendWink( $userId, $partnerId )
    {
        if ( empty($userId) || empty($partnerId) )
        {
            return FALSE;
        }

        if ( ($wink = $this->findByUserIdAndPartnerId($userId, $partnerId)) === NULL )
        {
            $wink = new WINKS_BOL_Winks();
        }
        
        $activeModes = json_decode(OW::getConfig()->getValue('mailbox', 'active_modes'));

        $wink->setUserId($userId);
        $wink->setPartnerId($partnerId);
        $wink->setTimeStamp(time());
        $wink->setStatus(WINKS_BOL_WinksDao::STATUS_WAIT);
        $wink->setViewed(0);
        $wink->setConversationId(0);
        $wink->setMessageType((in_array('chat', $activeModes) ? 'chat' : 'mail'));
        $wink->setWinkback(0);
        $this->winksDao->save($wink);

        return TRUE;
    }

    public function findByUserIdAndPartnerId( $userId, $partnerId )
    {
        return $this->winksDao->findByUserIdAndPartnerId($userId, $partnerId);
    }
    
    public function countWinksForUser( $partnerId, $status = NULL, $viewed = NULL, array $activeModes = array())
    {
        return $this->winksDao->countWinksForUser($partnerId, $status, $viewed, $activeModes);
    }
    
    public function countWinksForPartner( $userId, $status = NULL, $viewed = NULL )
    {
        return $this->winksDao->countWinksForPartner($userId, $status, $viewed);
    }
    
    public function countWinkBackedByUserId( $userId, array $activeModes = array() )
    {
        return $this->winksDao->countWinkBackedByUserId($userId, $activeModes);
    }

    public function findWinkList( $partnerId, $first, $limit, array $activeModes = array() )
    {
        return $this->winksDao->findWinkList($partnerId, $first, $limit, $activeModes);
    }
    
    public function markViewedByIds( $winksIds )
    {
        return $this->winksDao->markViewedByIds($winksIds);
    }
    
    public function findWinkByUserIdAndPartnerId( $userId, $partnerId )
    {
        return $this->winksDao->findWinkByUserIdAndPartnerId($userId, $partnerId);
    }
    
    public function findWinkById( $id )
    {
        return $this->winksDao->findById($id);
    }
    
    public function findExpiredDate( $timeStamp )
    {
        return $this->winksDao->findExpiredDate($timeStamp);
    }
    
    public function deleteWinkById( $id )
    {
        return $this->winksDao->deleteById($id);
    }
    
    public function deleteWinkByUserId( $userId )
    {
        return $this->winksDao->deleteWinkByUserId($userId);
    }
    
    public function setWinkback( $winkId, $flag = TRUE )
    {
        if ( empty($winkId) || ($wink = $this->winksDao->findById($winkId)) === NULL )
        {
            return FALSE;
        }
        
        $wink->setWinkback($flag);
        $this->winksDao->save($wink);
        
        return TRUE;
    }

    public function isWinkBacked( $winkId )
    {
        if ( empty($winkId) || ($wink = $this->findWinkById($winkId)) === null )
        {
            return false;
        }

        return $wink->getWinkback() == 1;
    }
    
    public function isCompleted( $userId, $partnerId )
    {
        return $this->winksDao->isCompleted($userId, $partnerId);
    }
    
    public function setStatusByUserId( $userId, $status )
    {
        return $this->winksDao->setStatusByUserId($userId, $status);
    }

    public function getActiveModes()
    {
        if ( OW::getPluginManager()->getPlugin('winks')->getDto()->getBuild() >= 14 )
        {
            return json_decode(OW::getConfig()->getValue('mailbox', 'active_modes'));
        }

         return array();
    }

    public function sendWinkEmailNotification( $userId, $partnerId, $winkType )
    {
        if ( empty($userId) || empty($partnerId) ||
            ($user = BOL_UserService::getInstance()->findUserById($userId)) === null ||
            ($partner = BOL_UserService::getInstance()->findUserById($partnerId)) === null )
        {
            return false;
        }

        $avatar = BOL_AvatarService::getInstance()->getDataForUserAvatars(array($userId, $partnerId), true, true, true, false);

        switch ( $winkType )
        {
            case self::EMAIL_SEND:
                $subjectKey = 'wink_send_email_subject';
                $subjectArr = array('displayname' => $avatar[$userId]['title']);

                $textContentKey = 'wink_send_email_text_content';
                $htmlContentKey = 'wink_send_email_html_content';
                $contentArr = array(
                    'src' => $avatar[$userId]['src'],
                    'displayname' => $avatar[$userId]['title'],
                    'url' => $avatar[$userId]['url'],
                    'home_url' => OW_URL_HOME
                );
                break;
            case self::EMAIL_BACK:
            default:
                $subjectKey = 'wink_back_email_subject';
                $subjectArr = array('displayname' => $avatar[$userId]['title']);

                $textContentKey = 'wink_back_email_text_content';
                $htmlContentKey = 'wink_back_email_html_content';
                $contentArr = array(
                    'src' => $avatar[$userId]['src'],
                    'displayname' => $avatar[$userId]['title'],
                    'url' => $avatar[$userId]['url'],
                    'conversation_url' => OW::getRouter()->urlForRoute('mailbox_messages_default')
                );
                break;
        }

        $language = OW::getLanguage();
        $mail = OW::getMailer()->createMail();

        $mail->addRecipientEmail($partner->email);
        $mail->setSubject($language->text('winks', $subjectKey, $subjectArr));
        $mail->setTextContent($language->text('winks', $textContentKey, $contentArr));
        $mail->setHtmlContent($language->text('winks', $htmlContentKey, $contentArr));

        OW::getMailer()->send($mail);
    }
}
