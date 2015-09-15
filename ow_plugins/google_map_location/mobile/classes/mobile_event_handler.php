<?php

class GOOGLELOCATION_MCLASS_MobileEventHandler
{

    public function __construct()
    {
        
    }

    public function getMapItemCmp( OW_Event $event )
    {
        $params = $event->getParams();
        if ( !empty($params['className']) && $params['className'] == 'GOOGLELOCATION_CMP_MapItem' )
        {
            $event->setData(new GOOGLELOCATION_MCMP_MapItem());
        }
    }

    public function getMapItemListCmp( OW_Event $event )
    {
        $params = $event->getParams();
        if ( !empty($params['className']) && $params['className'] == 'GOOGLELOCATION_CMP_MapUserList' )
        {
            $event->setData(new GOOGLELOCATION_MCMP_MapUserList($params['arguments'][0], $params['arguments'][1], $params['arguments'][2], $params['arguments'][3]));
        }
    }

    /* public function userListData( BASE_CLASS_EventCollector $event )
    {
        $data = array(
            'key' => 'google_map_mobile_userlist',
            'dataProvider' => array($this, 'getUserListData'),
            'label' => '',
            'url' => '',
            'iconClass' => ''
        );
        $event->add($data);
    } */

    /* public function getUserListData( $first, $count )
    {
        $listKey = empty($_POST['list']) ? null : strtolower(trim($_POST['list']));

        if ( OW::getRequest()->isAjax() && OW::getRequest()->isPost() && $listKey == 'google_map_mobile_userlist' )
        {
            $excludeList = empty($_POST['excludeList']) ? array() : $_POST['excludeList'];
            $showOnline = empty($_POST['showOnline']) ? false : $_POST['showOnline'];

            $userIdList = GOOGLELOCATION_BOL_LocationService::getInstance()->getEntityListFromSession($_GET['hash']);

            foreach ( $userIdList as $key => $value )
            {
                if ( in_array($value, $excludeList) )
                {
                    unset($userIdList[$key]);
                }
            }

            $userList = GOOGLELOCATION_BOL_LocationService::getInstance()->findUserListByCoordinates($lat, $lon, 1, $usersPerPage, $userIdList);
            $usersCount = GOOGLELOCATION_BOL_LocationService::getInstance()->findUserCountByCoordinates($lat, $lon, $userIdList);

            return array($userList, $usersCount);
        }
    }*/

    public function init()
    {
        OW::getEventManager()->bind('class.get_instance', array($this, 'getMapItemListCmp'));
        OW::getEventManager()->bind('class.get_instance', array($this, 'getMapItemCmp'));
        //OW::getEventManager()->bind('base.add_user_list', array($this, 'userListData'));
    }
}