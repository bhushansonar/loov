<?php

/**
 * Copyright (c) 2014, Skalfa LLC
 * All rights reserved.
 *
 * ATTENTION: This commercial software is intended for exclusive use with SkaDate Dating Software (http://www.skadate.com) and is licensed under SkaDate Exclusive License by Skalfa LLC.
 *
 * Full text of this license can be found at http://www.skadate.com/sel.pdf
 */
$config = OW::getConfig();
$pluginKey = "skandroid";

if ( !$config->configExists($pluginKey , 'billing_enabled') )
{
    $config->addConfig($pluginKey, 'billing_enabled', 0, 'Billing enabled');
}

if ( !$config->configExists($pluginKey, 'public_key') )
{
    $config->addConfig($pluginKey, 'public_key', '', 'Application public key');
}

if ( !$config->configExists($pluginKey, 'app_url') )
{
    $config->addConfig($pluginKey, 'app_url', 'https://play.google.com/store/apps/details?id=com.skadatexapp&hl=en');
}

if ( !$config->configExists($pluginKey, 'smart_banner') )
{
    $config->addConfig($pluginKey, 'smart_banner', true);
}

$billingService = BOL_BillingService::getInstance();

$gateway = new BOL_BillingGateway();
$gateway->gatewayKey = 'skadateandroid';
$gateway->adapterClassName = 'SKANDROID_ACLASS_InAppPurchaseAdapter';
$gateway->active = 0;
$gateway->mobile = 1;
$gateway->recurring = 1;
$gateway->dynamic = 0;
$gateway->hidden = 1;
$gateway->currencies = 'AUD,CAD,EUR,GBP,JPY,USD';

$billingService->addGateway($gateway);

OW::getPluginManager()->addPluginSettingsRouteName('skandroid', 'skandroid_admin_settings');

OW::getLanguage()->importPluginLangs(OW::getPluginManager()->getPlugin($pluginKey)->getRootDir() . 'langs.zip', $pluginKey);
