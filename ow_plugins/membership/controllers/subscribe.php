<?php

/**
 * Copyright (c) 2009, Skalfa LLC
 * All rights reserved.

 * ATTENTION: This commercial software is intended for use with Oxwall Free Community Software http://www.oxwall.org/
 * and is licensed under Oxwall Store Commercial License.
 * Full text of this license can be found at http://www.oxwall.org/store/oscl
 */

/**
 * Membership subscribe page controller.
 *
 * @author Egor Bulgakov <egor.bulgakov@gmail.com>
 * @package ow.ow_plugins.membership.controllers
 * @since 1.0
 */
class MEMBERSHIP_CTRL_Subscribe extends OW_ActionController {

    public function index() {

//        $billingService = BOL_BillingService::getInstance();
//        $membershipService = MEMBERSHIP_BOL_MembershipService::getInstance();
//
////$url = OW::getRouter()->urlForRoute('membership_subscribe');
//        $lang = OW::getLanguage();
//        $planId = 16;
//        $userId = 8;
//        if (!$plan = $membershipService->findPlanById($planId)) {
//
//            $message = $lang->text('membership', 'plan_not_found');
////            OW::getApplication()->redirect($url);
//            $this->jsonEncodeResponse(array("status" => "false", "message" => $message));
//        }
//
//        if ($plan->price == 0) { // trial plan
//// check if trial plan used
//            $used = $membershipService->isTrialUsedByUser($userId);
//
//            if ($used) {
//                $message = $lang->text('membership', 'trial_used_error');
//// OW::getApplication()->redirect($url);
//                // $this->jsonEncodeResponse(array("status" => "false", "message" => $message));
//            } else { // give trial plan
//                $userMembership = new MEMBERSHIP_BOL_MembershipUser();
//
//                $userMembership->userId = $userId;
//                $userMembership->typeId = $plan->typeId;
//                $userMembership->expirationStamp = time() + (int) $plan->period * 3600 * 24;
//                $userMembership->recurring = 0;
//                $userMembership->trial = 1;
//
//                $membershipService->setUserMembership($userMembership);
//                $membershipService->addTrialPlanUsage($userId, $plan->id, $plan->period);
//
////                $message = $lang->text('membership', 'trial_granted', array('days' => $plan->period));
//////OW::getApplication()->redirect($url);
////                $this->jsonEncodeResponse(array("status" => "true", "message" => $message));
//            }
//        }
        if (!OW::getUser()->isAuthenticated()) {
            throw new AuthenticateException();
        }

        $form = new SubscribeForm();
        $this->addForm($form);

        if (OW::getRequest()->isPost() && $form->isValid($_POST)) {
            $form->process();
        }

        $membershipService = MEMBERSHIP_BOL_MembershipService::getInstance();
        $authService = BOL_AuthorizationService::getInstance();

        $actions = $membershipService->getSubscribePageGroupActionList();
        $this->assign('groupActionList', $actions);

        $accTypeName = OW::getUser()->getUserObject()->getAccountType();
        $accType = BOL_QuestionService::getInstance()->findAccountTypeByName($accTypeName);

        $mTypes = $membershipService->getTypeList($accType->id);

        /* @var $defaultRole BOL_AuthorizationRole */
        $defaultRole = $authService->getDefaultRole();

        /* @var $default MEMBERSHIP_BOL_MembershipType */
        $default = new MEMBERSHIP_BOL_MembershipType();
        $default->roleId = $defaultRole->id;

        $mTypes = array_merge(array($default), $mTypes);

        $userId = OW::getUser()->getId();
        $userMembership = $membershipService->getUserMembership($userId);
        $userRoleIds = array($defaultRole->id);

        if ($userMembership) {
            $type = $membershipService->findTypeById($userMembership->typeId);
            if ($type) {
                $userRoleIds[] = $type->roleId;
                $this->assign('currentTitle', $membershipService->getMembershipTitle($type->roleId));
            }

            $this->assign('current', $userMembership);
        }

        $permissions = $authService->getPermissionList();

        $perms = array();
        foreach ($permissions as $permission) {
            /* @var $permission BOL_AuthorizationPermission */
            $perms[$permission->roleId][$permission->actionId] = true;
        }

        $exclude = $membershipService->getUserTrialPlansUsage($userId);

        $mPlans = $membershipService->getTypePlanList($exclude);

        $plansNumber = 0;
        $mTypesPermissions = array();
        foreach ($mTypes as $membership) {
            $mId = $membership->id;
            $plans = isset($mPlans[$mId]) ? $mPlans[$mId] : null;
            $data = array(
                'id' => $mId,
                'title' => $membershipService->getMembershipTitle($membership->roleId),
                'roleId' => $membership->roleId,
                'permissions' => isset($perms[$membership->roleId]) ? $perms[$membership->roleId] : null,
                'current' => in_array($membership->roleId, $userRoleIds),
                'plans' => $plans
            );
            $plansNumber += count($plans);

            $mTypesPermissions[$mId] = $data;
        }

        $this->assign('mTypePermissions', $mTypesPermissions);
        $this->assign('plansNumber', $plansNumber);
        $this->assign('typesNumber', count($mTypes));

        // collecting labels
        $event = new BASE_CLASS_EventCollector('admin.add_auth_labels');
        OW::getEventManager()->trigger($event);
        $data = $event->getData();

        $dataLabels = empty($data) ? array() : call_user_func_array('array_merge', $data);
        $this->assign('labels', $dataLabels);

        $gateways = BOL_BillingService::getInstance()->getActiveGatewaysList();
        $this->assign('gatewaysActive', (bool) $gateways);

        $lang = OW::getLanguage();

        $this->setPageHeading($lang->text('membership', 'subscribe_page_heading'));
        $this->setPageHeadingIconClass('ow_ic_user');
    }

}

/**
 * Subscribe form class
 */
class SubscribeForm extends Form {

    public function __construct() {
        parent::__construct('subscribe-form');

        $planField = new RadioGroupItemField('plan');
        $planField->setRequired();
        $this->addElement($planField);

        $gatewaysField = new BillingGatewaySelectionField('gateway');
        $gatewaysField->setRequired();
        $this->addElement($gatewaysField);

        $submit = new Submit('subscribe');
        $submit->setValue(OW::getLanguage()->text('membership', 'checkout'));
        $this->addElement($submit);
    }

    public function process() {
        $values = $this->getValues();
        $lang = OW::getLanguage();
        $userId = OW::getUser()->getId();

        $billingService = BOL_BillingService::getInstance();
        $membershipService = MEMBERSHIP_BOL_MembershipService::getInstance();

        $url = OW::getRouter()->urlForRoute('membership_subscribe');

        if (!$plan = $membershipService->findPlanById($values['plan'])) {
            OW::getFeedback()->error($lang->text('membership', 'plan_not_found'));
            OW::getApplication()->redirect($url);
        }

        if ($plan->price == 0) { // trial plan
            // check if trial plan used
            $used = $membershipService->isTrialUsedByUser($userId);

            if ($used) {
                OW::getFeedback()->error($lang->text('membership', 'trial_used_error'));
                OW::getApplication()->redirect($url);
            } else { // give trial plan
                $userMembership = new MEMBERSHIP_BOL_MembershipUser();

                $userMembership->userId = $userId;
                $userMembership->typeId = $plan->typeId;
                $userMembership->expirationStamp = time() + (int) $plan->period * 3600 * 24;
                $userMembership->recurring = 0;
                $userMembership->trial = 1;

                $membershipService->setUserMembership($userMembership);
                $membershipService->addTrialPlanUsage($userId, $plan->id, $plan->period);

                OW::getFeedback()->info($lang->text('membership', 'trial_granted', array('days' => $plan->period)));
                OW::getApplication()->redirect($url);
            }
        }

        if (empty($values['gateway']['url']) || empty($values['gateway']['key'])) {
            OW::getFeedback()->error($lang->text('base', 'billing_gateway_not_found'));
            OW::getApplication()->redirect($url);
        }

        $gateway = $billingService->findGatewayByKey($values['gateway']['key']);
        if (!$gateway || !$gateway->active) {
            OW::getFeedback()->error($lang->text('base', 'billing_gateway_not_found'));
            OW::getApplication()->redirect($url);
        }

        // create membership plan product adapter object
        $productAdapter = new MEMBERSHIP_CLASS_MembershipPlanProductAdapter();

        // sale object
        $sale = new BOL_BillingSale();
        $sale->pluginKey = 'membership';
        $sale->entityDescription = $membershipService->getFormattedPlan($plan->price, $plan->period, $plan->recurring);
        $sale->entityKey = $productAdapter->getProductKey();
        $sale->entityId = $plan->id;
        $sale->price = floatval($plan->price);
        $sale->period = $plan->period;
        $sale->userId = $userId ? $userId : 0;
        $sale->recurring = $plan->recurring;

        $saleId = $billingService->initSale($sale, $values['gateway']['key']);

        if ($saleId) {
            // sale Id is temporarily stored in session
            $billingService->storeSaleInSession($saleId);
            $billingService->setSessionBackUrl($productAdapter->getProductOrderUrl());

            // redirect to gateway form page
            OW::getApplication()->redirect($values['gateway']['url']);
        }
    }

}

