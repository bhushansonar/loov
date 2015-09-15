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

if ( !$config->configExists('skadateios', 'billing_enabled') )
{
    $config->addConfig('skadateios', 'billing_enabled', 0, 'Billing enabled');
}

if ( !$config->configExists('skadateios', 'itunes_secret') )
{
    $config->addConfig('skadateios', 'itunes_secret', '', 'Itunes shared secret');
}

if ( !$config->configExists('skadateios', 'itunes_mode') )
{
    $config->addConfig('skadateios', 'itunes_mode', 'test', 'Itunes mode');
}

if ( !$config->configExists('skadateios', 'app_url') )
{
    $config->addConfig('skadateios', 'app_url', 'https://itunes.apple.com/us/app/dating-app/id872986237?ls=1&mt=8');
}

if ( !$config->configExists('skadateios', 'smart_banner') )
{
    $config->addConfig('skadateios', 'smart_banner', true);
}

OW::getPluginManager()->addPluginSettingsRouteName('skadateios', 'skadateios.admin_settings');

$billingService = BOL_BillingService::getInstance();

$gateway = new BOL_BillingGateway();
$gateway->gatewayKey = 'skadateios';
$gateway->adapterClassName = 'SKADATEIOS_ACLASS_InAppPurchaseAdapter';
$gateway->active = 0;
$gateway->mobile = 1;
$gateway->recurring = 1;
$gateway->dynamic = 0;
$gateway->hidden = 1;
$gateway->currencies = 'AUD,CAD,EUR,GBP,JPY,USD';

$billingService->addGateway($gateway);

OW::getLanguage()->importPluginLangs(OW::getPluginManager()->getPlugin('skadateios')->getRootDir() . 'langs.zip', 'skadateios');