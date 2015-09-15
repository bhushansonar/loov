<?php

$_entityList = MODERATION_BOL_Service::getInstance()->findAllEntityList();
$entityList = array();
foreach ( $_entityList as $entity )
{
    /* @var $entity MODERATION_BOL_Entity */
    $entityList[$entity->entityType] = empty($entityList[$entity->entityType]) 
            ? array() 
            : $entityList[$entity->entityType];
    
    $entityList[$entity->entityType][] = $entity->entityId;
}

foreach ( $entityList as $entityType => $entityIds )
{
    try 
    {
        BOL_ContentService::getInstance()->updateContentList($entityType, $entityIds, array(
            "status" =>  BOL_ContentService::STATUS_ACTIVE
        ));
    } 
    catch (Exception $ex) {
        // Pass
    }
}
