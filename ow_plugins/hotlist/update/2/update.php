<?php
/**
 * Copyright (c) 2014, Skalfa LLC
 * All rights reserved.
 *
 * ATTENTION: This commercial software is intended for exclusive use with SkaDate Dating Software (http://www.skadate.com) and is licensed under SkaDate Exclusive License by Skalfa LLC.
 *
 * Full text of this license can be found at http://www.skadate.com/sel.pdf
 */

$authorization = OW::getAuthorization();
$groupName = 'hotlist';
$authorization->addGroup($groupName);
$authorization->addAction($groupName, 'add_to_list');

Updater::getLanguageService()->importPrefixFromZip(dirname(dirname(dirname(__FILE__))) . DS . 'langs.zip', 'hotlist');