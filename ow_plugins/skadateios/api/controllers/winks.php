<?php

/**
 * Copyright (c) 2014, Skalfa LLC
 * All rights reserved.
 *
 * ATTENTION: This commercial software is intended for exclusive use with SkaDate Dating Software (http://www.skadate.com) and is licensed under SkaDate Exclusive License by Skalfa LLC.
 *
 * Full text of this license can be found at http://www.skadate.com/sel.pdf
 */

class SKADATEIOS_ACTRL_Winks extends OW_ApiActionController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function sendWink( $params )
    {
        $viewerId = OW::getUser()->getId();

        if ( !$viewerId )
        {
            throw new ApiResponseErrorException();
        }

        if ( empty($params['userId']) )
        {
            throw new ApiResponseErrorException();
        }

        $userId = (int) $params['userId'];

        $service = WINKS_BOL_Service::getInstance();

        $service->sendWink($viewerId, $userId);
    }

    public function sendWinkBack( $params )
    {
        $partnerId = OW::getUser()->getId();

        if ( !$partnerId )
        {
            throw new ApiResponseErrorException();
        }

        if ( empty($params['userId']) )
        {
            throw new ApiResponseErrorException();
        }

        $userId = (int) $params['userId'];

        $service = WINKS_BOL_Service::getInstance();

        $wink = $service->findWinkByUserIdAndPartnerId($userId, $partnerId);

        if ( empty($wink) )
        {
            throw new ApiResponseErrorException();
        }

        $service->setWinkback($wink->getId(), TRUE);

        $event = new OW_Event('winks.onWinkBack', array(
            'userId' => $wink->getUserId(),
            'partnerId' => $wink->getPartnerId(),
            'conversationId' => $wink->getConversationId(),
            'content' => array(
                'entityType' => 'wink',
                'eventName' => 'renderWinkBack',
                'params' => array(
                    'winkId' => $wink->id,
                    'messageId' => $params['messageId']
                )
            )
        ));
        OW::getEventManager()->trigger($event);
    }

    public function acceptWink( $params )
    {
        $partnerId = OW::getUser()->getId();

        if ( !$partnerId )
        {
            throw new ApiResponseErrorException();
        }

        if ( empty($params['userId']) )
        {
            throw new ApiResponseErrorException();
        }

        $service = WINKS_BOL_Service::getInstance();
        $userId = $params['userId'];

        /**
         * @var WINKS_BOL_Winks $wink
         */
        $wink = $service->findWinkByUserIdAndPartnerId($userId, $partnerId);
        
        if ( empty($wink) )
        {
            throw new ApiResponseErrorException();
        }

        $wink->setStatus(WINKS_BOL_WinksDao::STATUS_ACCEPT);
        WINKS_BOL_WinksDao::getInstance()->save($wink);

        if ( ($_wink = $service->findWinkByUserIdAndPartnerId($partnerId, $userId)) !== NULL )
        {
            $_wink->setStatus(WINKS_BOL_WinksDao::STATUS_IGNORE);
            WINKS_BOL_WinksDao::getInstance()->save($_wink);
        }

        $params = array(
            'userId' => $userId,
            'partnerId' => $partnerId,
            'content' => array(
                'entityType' => 'wink',
                'eventName' => 'renderWink',
                'params' => array(
                    'winkId' => $wink->id,
                    'winkBackEnabled' => 1
                )
            )
        );

        $event = new OW_Event('winks.onAcceptWink', $params);
        OW::getEventManager()->trigger($event);

        $data = $event->getData();

        if ( !empty($data['conversationId']) )
        {
            $wink->setConversationId($data['conversationId']);
            WINKS_BOL_WinksDao::getInstance()->save($wink);
        }

        $this->assign('result', true);
    }
}
