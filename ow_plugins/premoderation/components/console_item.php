<?php

class MODERATION_CMP_ConsoleItem extends OW_Component
{
    /**
     *
     * @var BASE_CMP_ConsoleDropdownClick
     */
    protected $consoleItem;
    protected $userId;

    public function __construct( $groups )
    {
        parent::__construct();

        $this->userId = OW::getUser()->getId();
        $this->consoleItem = new BASE_CMP_ConsoleDropdownClick(OW::getLanguage()
                ->text('moderation', 'console_pending_approval'), "pending-approval");
        
        $this->consoleItem->addClass("ow_pending_approval_list");
        $this->assign("items", $groups);
    }

    public function render()
    {
        $this->consoleItem->setContent(parent::render());

        return $this->consoleItem->render();
    }
}