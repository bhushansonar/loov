<?php

/**
 * Copyright (c) 2009, Skalfa LLC
 * All rights reserved.

 * ATTENTION: This commercial software is intended for use with Oxwall Free Community Software http://www.oxwall.org/
 * and is licensed under Oxwall Store Commercial License.
 * Full text of this license can be found at http://www.oxwall.org/store/oscl
 */

/**
 * Data Transfer Object for `membership_user_trial` table.
 *
 * @author Egor Bulgakov <egor.bulgakov@gmail.com>
 * @package ow.ow_plugins.membership.bol
 * @since 1.6.0
 */
class MEMBERSHIP_BOL_MembershipUserTrial extends OW_Entity
{
    /**
     * @var int
     */
    public $id;
    /**
     * @var int
     */
    public $planId;
    /**
     * @var int
     */
    public $userId;
    /**
     * @var int
     */
    public $startStamp;
    /**
     * @var int
     */
    public $expirationStamp;
}