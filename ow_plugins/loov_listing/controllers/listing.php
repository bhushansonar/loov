<?php

class LOOVLISTING_CTRL_Listing extends OW_ActionController {

    public function index() {
        if (!OW::getUser()->isAuthenticated()) {
            throw new AuthenticateException();
        }
        $sex = "2";
        $data_arr = array("sex" => $sex);
        //paging
        $onPage = 15;
        $page = isset($_GET['page']) && (int) $_GET['page'] ? (int) $_GET['page'] : 1;
        $userCount = BOL_UserService::getInstance()->countUsersByQuestionValuesCustom($data_arr, 0, 500, false, "");
        $first = ( $page - 1 ) * $onPage;
        $pages = (int) ceil($userCount / $onPage);
        $paging = new BASE_CMP_Paging($page, $pages, $onPage);
//
        $userIdList = BOL_UserService::getInstance()->findUserIdListByQuestionValuesCustom($data_arr, $first, $onPage, false, "");
        $serach_result = $this->loovListingResult($userIdList);
        $this->addComponent('paging', $paging);
        $this->assign('womanListing', $serach_result);
        //page
    }

    public function men() {
        if (!OW::getUser()->isAuthenticated()) {
            throw new AuthenticateException();
        }
        $sex = "1";
        $data_arr = array("sex" => $sex);

        //paging
        $onPage = 15;
        $page = isset($_GET['page']) && (int) $_GET['page'] ? (int) $_GET['page'] : 1;
        $userCount = BOL_UserService::getInstance()->countUsersByQuestionValuesCustom($data_arr, 0, 500, false, "");
        $first = ( $page - 1 ) * $onPage;
        $pages = (int) ceil($userCount / $onPage);
        $paging = new BASE_CMP_Paging($page, $pages, $onPage);
//
        $userIdList = BOL_UserService::getInstance()->findUserIdListByQuestionValuesCustom($data_arr, $first, $onPage, false, "");
        $serach_result = $this->loovListingResult($userIdList);
        $this->addComponent('paging', $paging);
        $this->assign('menListing', $serach_result);
    }

    protected function loovListingResult($listId) {

        $questionList = BOL_QuestionService::getInstance()->getQuestionData($listId, array('username', 'sex', 'birthdate', 'email', 'realname', 'birthdate', 'googlemap_location'));
        $flag = "1";
        $avatar = BOL_AvatarService::getInstance()->getDataForUserAvatars($listId, true, true, true, true, $flag);
//$onlineStatus = $Userservice->findOnlineStatusForUserList($user_ids);
        $dob = "";
        $age = "";
        foreach ($questionList as $key => $user) {
            $dob = date("Y/m/d", strtotime($user["birthdate"]));
            $age = $this->ageCalculate($dob);
            $user_data[] = array(
                "user_id" => $key,
                "user_name" => $user["username"],
                "realname" => $user["realname"],
                "profile_picture" => $avatar[$key]["src"],
                "age" => $age);
        }
        return $user_data;
    }

    protected function ageCalculate($dob) {
        if (!empty($dob)) {
            $birthdate = new DateTime($dob);
            $today = new DateTime('today');
            $age = $birthdate->diff($today)->y;
            return $age;
        } else {
            return 0;
        }
    }

}

