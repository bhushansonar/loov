<?php

class MODERATION_BOL_Entity extends OW_Entity
{
    /**
     *
     * @var string
     */
    public $entityType;
    
    /**
     *
     * @var int
     */
    public $entityId;
    
    /**
     *
     * @var int
     */
    public $timeStamp;
    
    /**
     *
     * @var string
     */
    public $data;
    
    /**
     *
     * @var int
     */
    public $userId;
    
    public function setData( array $data )
    {
        $this->data = json_encode($data);
    }
    
    public function getData()
    {
        if ( empty($this->data) )
        {
            return null;
        }
        
        return json_decode($this->data, true);
    }
}
