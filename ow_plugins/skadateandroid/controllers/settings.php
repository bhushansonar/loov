<?php

/**
 * Copyright (c) 2014, Skalfa LLC
 * All rights reserved.
 *
 * ATTENTION: This commercial software is intended for exclusive use with SkaDate Dating Software (http://www.skadate.com) and is licensed under SkaDate Exclusive License by Skalfa LLC.
 *
 * Full text of this license can be found at http://www.skadate.com/sel.pdf
 */

/**
 * @author Podyachev Evgeny <joker.OW2@gmail.com>
 * @package ow_system_plugins.skandroid.controllers
 * @since 1.0
 */
 
class SKANDROID_CTRL_Settings extends ADMIN_CTRL_Abstract
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $language = OW::getLanguage();

        $configSaveForm = new ConfigSaveForm();
        $this->addForm($configSaveForm);


        $configs = OW::getConfig()->getValues('skandroid');

        if ( OW::getRequest()->isPost() && isset($_POST['save']) )
        {
            $res = $configSaveForm->process();
            OW::getFeedback()->info($language->text('skandroid', 'settings_saved'));

            $this->redirect();
        }

        if ( !OW::getRequest()->isAjax() )
        {
            OW::getDocument()->setHeading(OW::getLanguage()->text('skandroid', 'admin_settings'));
            OW::getDocument()->setHeadingIconClass('ow_ic_gear_wheel');
        }
        
        $billingEnabled = $configs['billing_enabled'] === '1' ? true : false;
        
        $configSaveForm->getElement('public_key')->setValue($configs['public_key']);
        $configSaveForm->getElement('billing_enabled')->setValue($billingEnabled);
        
        $this->assign('billingEnabled', $billingEnabled);
        
        $script = " $('input[name=billing_enabled]').click(function() {
                    if( $(this).is( ':checked' ) )
                    {
                        $('tr.billing_enabled_settings').removeClass('ow_hidden');
                    }
                    else
                    {
                        $('tr.billing_enabled_settings').addClass('ow_hidden');
                    }
                } ) ";
        
        OW::getDocument()->addOnloadScript($script); 
    }
}

class ConfigSaveForm extends Form
{

    /**
     * Class constructor
     *
     */
    public function __construct()
    {
        parent::__construct('configSaveForm');

        $language = OW::getLanguage();

        $field = new TextField('public_key');
        $field->addValidator(new ConfigRequireValidator());
        $this->addElement($field);

        $field = new CheckboxField('billing_enabled');
        $this->addElement($field);
        
        // submit
        $submit = new Submit('save');
        $submit->setValue($language->text('admin', 'save_btn_label'));
        $this->addElement($submit);

        $promoUrl = new TextField('app_url');
        $promoUrl->setRequired();
        $promoUrl->addValidator(new UrlValidator());
        $promoUrl->setLabel($language->text('skandroid', 'app_url_label'));
        $promoUrl->setDescription($language->text('skandroid', 'app_url_desc'));
        $promoUrl->setValue(OW::getConfig()->getValue('skandroid', 'app_url'));
        $this->addElement($promoUrl);

        $smartBanner = new CheckboxField('smart_banner');
        $smartBanner->setLabel($language->text('skandroid', 'smart_banner_label'));
        $smartBanner->setDescription($language->text('skandroid', 'smart_banner_desc'));
        $smartBanner->setValue(OW::getConfig()->getValue('skandroid', 'smart_banner'));
        $this->addElement($smartBanner);
    }

    /**
     * Updates video plugin configuration
     *
     * @return boolean
     */
    public function process()
    {
        $config = OW::getConfig();
        
        $config->saveConfig('skandroid', 'public_key', !empty($_POST['public_key']) ? $_POST['public_key'] : "");
        $config->saveConfig('skandroid', 'billing_enabled', !empty($_POST['billing_enabled']) && $_POST['billing_enabled'] ? '1' : '0');
        $config->saveConfig('skandroid', 'app_url', $_POST['app_url']);
        $config->saveConfig('skandroid', 'smart_banner', $_POST['smart_banner']);

        return array('result' => true);
    }
}

class ConfigRequireValidator extends RequiredValidator {
    
    public function getJsValidator()
    {
        return '{
        	validate : function( value ){
                    if ( $("input[name=billing_enabled]").is( ":checked" ) )
                    {
                        if( $.isArray(value) ){ if(value.length == 0  ) throw ' . json_encode($this->getError()) . "; return;}
                        else if( !value || $.trim(value).length == 0 ){ throw " . json_encode($this->getError()) . "; }
                    }
                },
        	getErrorMessage : function(){ return " . json_encode($this->getError()) . " }
        }";
    }
}
