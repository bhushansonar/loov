<?php

$preference = BOL_PreferenceService::getInstance()->findPreference("pcgallery_source");

if ( empty($preference) )
{
    $preference = new BOL_Preference();
}

$preference->key = 'pcgallery_source';
$preference->sectionName = 'general';
$preference->defaultValue = "all";
$preference->sortOrder = 1;

BOL_PreferenceService::getInstance()->savePreference($preference);

$preference = BOL_PreferenceService::getInstance()->findPreference('pcgallery_album');

if ( empty($preference) )
{
    $preference = new BOL_Preference();
}

$preference->key = 'pcgallery_album';
$preference->sectionName = 'general';
$preference->defaultValue = 0;
$preference->sortOrder = 1;

BOL_PreferenceService::getInstance()->savePreference($preference);


Updater::getLanguageService()->importPrefixFromZip(dirname(__FILE__) . DS . 'langs.zip', "pcgallery");