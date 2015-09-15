<?php

/**
 * Copyright (c) 2009, Skalfa LLC
 * All rights reserved.

 * ATTENTION: This commercial software is intended for use with Oxwall Free Community Software http://www.oxwall.org/
 * and is licensed under Oxwall Store Commercial License.
 * Full text of this license can be found at http://www.oxwall.org/store/oscl
 */
OW::getRouter()->addRoute(new OW_Route('membership_subscribe', 'membership/subscribe', 'MEMBERSHIP_MCTRL_Subscribe', 'index'));

MEMBERSHIP_CLASS_EventHandler::getInstance()->genericInit();