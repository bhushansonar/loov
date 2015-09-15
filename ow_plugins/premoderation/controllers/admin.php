<?php

class MODERATION_CTRL_Admin extends ADMIN_CTRL_Abstract
{
    public function index()
    {
        $groups = MODERATION_BOL_Service::getInstance()->getContentGroups();
        
        if ( OW::getRequest()->isPost() )
        {
            $selectedGroups = empty($_POST["groups"]) ? array() : $_POST["groups"];
            
            $types = array(); 
            foreach ( $groups as $group )
            {
                $selected = in_array($group["name"], $selectedGroups);
                foreach ( $group["entityTypes"] as $type )
                {
                    $types[$type] = $selected;
                }
            }
            
            OW::getConfig()->saveConfig("moderation", "content_types", json_encode($types));
            
            OW::getFeedback()->info(OW::getLanguage()->text("moderation", "content_types_saved_message"));
            $this->redirect(OW::getRouter()->urlForRoute("moderation.admin"));
        }
        
        $this->setPageHeading(OW::getLanguage()->text("moderation", "admin_heading"));
        $this->setPageTitle(OW::getLanguage()->text("moderation", "admin_title"));
        
        $form = new Form("contentTypes");
        
        $submit = new Submit("save");
        $submit->setLabel(OW::getLanguage()->text("admin", "save_btn_label"));
        $form->addElement($submit);
        
        $this->addForm($form);
        
        $this->assign("groups", $groups);
    }
}
