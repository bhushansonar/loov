<?php

/**
 * Copyright (c) 2014, Skalfa LLC
 * All rights reserved.
 *
 * ATTENTION: This commercial software is intended for exclusive use with SkaDate Dating Software (http://www.skadate.com) and is licensed under SkaDate Exclusive License by Skalfa LLC.
 *
 * Full text of this license can be found at http://www.skadate.com/sel.pdf
 */
//OW_Auth::getInstance()->login(1);

$service = SKANDROID_ABOL_Service::getInstance();

if( SKANDROID_ACLASS_Plugin::getInstance()->isAndroidRequest() ) {
    SKANDROID_ACLASS_Plugin::getInstance()->init();
}
