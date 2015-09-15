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
 * @author Sergey Kambalin <greyexpert@gmail.com>
 * @package ow_system_plugins.skadateios.controllers
 * @since 1.0
 */
 
class SKADATEIOS_CTRL_Settings extends ADMIN_CTRL_Abstract
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $language = OW::getLanguage();

        $configs = OW::getConfig()->getValues('skadateios');
        
        $configSaveForm = new SKADATEIOS_ConfigForm($configs);
        $this->addForm($configSaveForm);

        if ( OW::getRequest()->isPost() )
        {
            $configSaveForm->isValid($_POST);
            $configSaveForm->process();
            OW::getFeedback()->info($language->text('skadateios', 'settings_saved'));

            $this->redirect();
        }

        if ( !OW::getRequest()->isAjax() )
        {
            OW::getDocument()->setHeading(OW::getLanguage()->text('skadateios', 'admin_settings'));
            OW::getDocument()->setHeadingIconClass('ow_ic_gear_wheel');
        }
        
        $billingEnabled = (bool) $configs['billing_enabled'];
        
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

class SKADATEIOS_ConfigForm extends Form
{

    /**
     * Class constructor
     *
     */
    public function __construct( $configs )
    {
        parent::__construct('configSaveForm');

        $language = OW::getLanguage();

        $field = new RadioField('itunes_mode');
        $field->setOptions(array(
            "test" => $language->text("skadateios", "itunes_mode_test"),
            "live" => $language->text("skadateios", "itunes_mode_live")
        ));
        
        $field->setValue($configs["itunes_mode"]);
        $this->addElement($field);

        $field = new CheckboxField('billing_enabled');
        $field->setValue($configs["billing_enabled"]);
        $this->addElement($field);
        
        $field = new TextField('itunes_secret');
        $field->addValidator(new ConfigRequireValidator());
        $field->setValue($configs["itunes_secret"]);
        $this->addElement($field);

        $promoUrl = new TextField('app_url');
        $promoUrl->setRequired();
        $promoUrl->addValidator(new UrlValidator());
        $promoUrl->setLabel($language->text('skadateios', 'app_url_label'));
        $promoUrl->setDescription($language->text('skadateios', 'app_url_desc'));
        $promoUrl->setValue($configs['app_url']);
        $this->addElement($promoUrl);

        $smartBanner = new CheckboxField('smart_banner');
        $smartBanner->setLabel($language->text('skadateios', 'smart_banner_label'));
        $smartBanner->setDescription($language->text('skadateios', 'smart_banner_desc'));
        $smartBanner->setValue($configs['smart_banner']);
        $this->addElement($smartBanner);
        
        // submit
        $submit = new Submit('save');
        $submit->setValue($language->text('admin', 'save_btn_label'));
        $this->addElement($submit);
    }

    /**
     * Updates video plugin configuration
     *
     * @return boolean
     */
    public function process()
    {
        $values = $this->getValues();
        $config = OW::getConfig();
        
        $config->saveConfig('skadateios', 'billing_enabled', $values["billing_enabled"]);
        $config->saveConfig('skadateios', 'itunes_secret', $values["itunes_secret"]);
        $config->saveConfig('skadateios', 'itunes_mode', $values["itunes_mode"]);
        $config->saveConfig('skadateios', 'app_url', $values['app_url']);
        $config->saveConfig('skadateios', 'smart_banner', $values['smart_banner']);
    }
}

class ConfigRequireValidator extends RequiredValidator {
    
    public function getJsValidator()
    {
        return '{
        	validate : function( value ){
                    if ( $("input[name=billing_enabled]").is( ":checked" ) )
                    {
                        if( $.isArray(value) ){ if(value.length == 0  ) throw ' . json_encode($this->getError()) . "; }
                        else if( !value || $.trim(value).length == 0 ){ throw " . json_encode($this->getError()) . "; }
                    }
                },
        	getErrorMessage : function(){ return " . json_encode($this->getError()) . " }
        }";
    }
}
