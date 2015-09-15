<?php

/**
 * Copyright (c) 2012, Skalfa LLC
 * All rights reserved.

 * ATTENTION: This commercial software is intended for use with Oxwall Free Community Software http://www.oxwall.org/
 * and is licensed under Oxwall Store Commercial License.
 * Full text of this license can be found at http://www.oxwall.org/store/oscl
 */

$sql = "ALTER TABLE `".OW_DB_PREFIX."usercredits_log` ADD `additionalParams` VARCHAR(2048) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ";

try
{
    Updater::getDbo()->query($sql);
}
catch ( Exception $e )
{
    $exArr[] = $e;
}